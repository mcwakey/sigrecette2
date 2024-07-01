<?php

namespace App\Http\Controllers\Apps;

use App\Models\User;
use App\Models\UserLogs;
use Illuminate\Http\Request;
use App\Models\PasswordActionLog;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, UsersDataTable $dataTable)
    {
        $validatedData = $request->validate([
            'disable' => 'nullable|integer',
            'type'=>['nullable', 'string', Rule::in(['col'])]
        ]);

        $disable = $validatedData['disable'] ?? null;
        $type = $validatedData['type'] ?? null;

        return $dataTable->with([
            'disable' => $disable,
            'type'=> $type
        ])->render('pages/apps.user-management.users.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $userActionLog = UserLogs::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->limit(3)
            ->get();

        $passwordActionLog = PasswordActionLog::where('user_id', $user->id)->get();

        return view('pages/apps.user-management.users.show', compact('user', 'userActionLog', 'passwordActionLog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
