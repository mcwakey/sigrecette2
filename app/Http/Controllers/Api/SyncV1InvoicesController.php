<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SyncInvoiceResource;
use App\Models\Invoice;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SyncV1InvoicesController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:200',
            'updated_since' => 'sometimes|date',
            'include' => 'sometimes|string',
        ]);

        $perPage = (int) $request->query('per_page', 50);
        $page = (int) $request->query('page', 1);
        $updatedSince = $request->query('updated_since');

        $activeYear = Year::getActiveYear();
        $startOfYear = Carbon::createFromDate((int) $activeYear->name, 1, 1)->startOfDay();
        $endOfYear = Carbon::createFromDate((int) $activeYear->name, 12, 31)->endOfDay();

        $query = Invoice::query()
            ->where('status', 'APPROVED')
            ->where('validity', 'VALID')
            ->where(function ($q) use ($startOfYear, $endOfYear) {
                $q->whereBetween('from_date', [$startOfYear, $endOfYear])
                    ->orWhereBetween('to_date', [$startOfYear, $endOfYear])
                    ->orWhereBetween('created_at', [$startOfYear, $endOfYear]);
            });

        if ($updatedSince) {
            $query->where('updated_at', '>=', Carbon::parse($updatedSince));
        }

        $include = collect(explode(',', (string) $request->query('include', 'taxpayer,items')))
            ->map(fn ($value) => trim($value))
            ->filter();

        $with = [];
        if ($include->contains('taxpayer')) {
            $with[] = 'taxpayer';
        }
        if ($include->contains('items')) {
            $with[] = 'invoiceitems';
        }

        if (!empty($with)) {
            $query->with($with);
        }

        $paginator = $query->orderBy('updated_at')->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => SyncInvoiceResource::collection(collect($paginator->items()))->resolve(),
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
}
