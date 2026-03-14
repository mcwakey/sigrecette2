<?php
namespace App\Http\Controllers\Api;

use App\Enums\PaymentStatusEnums;
use App\Enums\PaymentTypeEnums;
use App\Http\Controllers\Controller;
use App\Http\Resources\SyncPaymentResource;
use App\Models\Payment;
use App\Models\Year;
use App\Services\Sync\PaymentImportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SyncV1PaymentsController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:200',
            'updated_since' => 'sometimes|date',
            'status' => 'sometimes|string',
        ]);

        $perPage = (int) $request->query('per_page', 50);
        $page = (int) $request->query('page', 1);
        $updatedSince = $request->query('updated_since');

        $activeYear = Year::getActiveYear();
        $startOfYear = Carbon::createFromDate((int) $activeYear->name, 1, 1)->startOfDay();
        $endOfYear = Carbon::createFromDate((int) $activeYear->name, 12, 31)->endOfDay();

        $query = Payment::query()
            ->whereBetween('created_at', [$startOfYear, $endOfYear]);

        if ($updatedSince) {
            $query->where('updated_at', '>=', Carbon::parse($updatedSince));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $paginator = $query
            ->with('invoice')
            ->orderBy('updated_at')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => SyncPaymentResource::collection(collect($paginator->items()))->resolve(),
            'meta' => [
                'page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'updated_since' => $updatedSince,
                'active_year' => (int) $activeYear->name,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'payments' => 'required|array|min:1',
            'payments.*.uuid' => 'required|string',
            'payments.*.invoice_uuid' => 'required|string',
            'payments.*.taxpayer_id' => 'nullable|integer',
            'payments.*.amount' => 'required|numeric',
            'payments.*.payment_type' => ['required', 'string', Rule::in([
                PaymentTypeEnums::CASH,
                PaymentTypeEnums::CHEQUE,
                PaymentTypeEnums::DIGI,
            ])],
            'payments.*.invoice_type' => 'nullable|string',
            'payments.*.reference' => 'nullable|string',
            'payments.*.description' => 'nullable|string',
            'payments.*.remaining_amount' => 'nullable|numeric',
            'payments.*.status' => ['required', 'string', Rule::in([
                PaymentStatusEnums::PENDING,
                PaymentStatusEnums::DONE,
                PaymentStatusEnums::ACCOUNTED,
                PaymentStatusEnums::CANCELED,
            ])],
            'payments.*.deposit' => 'nullable|numeric',
            'payments.*.notes' => 'nullable',
        ]);

        $service = new PaymentImportService();
        return response()->json($service->import($payload['payments']));
    }
}
