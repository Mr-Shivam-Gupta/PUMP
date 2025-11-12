<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant;
use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    // ---------------------------
    // 🧾 INDEX PAGE
    // ---------------------------
    public function index()
    {
        $tenants = Tenant::select('id', 'name')->get();
        return view('super-admin.users.index', compact('tenants'));
    }

    // ---------------------------
    // 📊 DATATABLE LIST
    // ---------------------------
    public function list(Request $request)
    {
        $query = User::with('tenant')
            ->when($request->tenant_id, fn($q) => $q->where('tenant_id', $request->tenant_id))
            ->when($request->role, fn($q) => $q->where('role', $request->role))
            ->when($request->status != '', fn($q) => $q->where('status', $request->status));

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('tenant_name', fn($u) => $u->tenant->name ?? '-')
            ->toJson();
    }

    // ---------------------------
    // 🆕 CREATE USER
    // ---------------------------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'employee_id' => 'nullable|string|max:50',
            'role' => 'required|string',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'status' => 'required|boolean',
        ]);

        $validated['password'] = Hash::make($request->password);

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'data' => $user,
        ]);
    }

    // ---------------------------
    // ✏️ EDIT USER
    // ---------------------------
    public function edit($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        return response()->json(['success' => true, 'data' => $user]);
    }

    // ---------------------------
    // 🔄 UPDATE USER
    // ---------------------------
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'employee_id' => 'nullable|string|max:50',
            'role' => 'required|string',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:users,phone,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'status' => 'required|boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
        ]);
    }

    // ---------------------------
    // 🗑️ DELETE USER
    // ---------------------------
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }

    // ---------------------------
    // 🔁 TOGGLE STATUS
    // ---------------------------
    public function toggleStatus($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        $user->status = !$user->status;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully.',
        ]);
    }

    // ---------------------------
    // 📤 EXPORT EXCEL / CSV
    // ---------------------------
    public function export(Request $request)
    {
        $filters = [
            'tenant_id' => $request->tenant_id,
            'role' => $request->role,
            'status' => $request->status,
        ];

        $fileType = $request->type ?? 'xlsx'; // xlsx or csv
        $fileName = 'users_export_' . now()->format('Ymd_His') . '.' . $fileType;

        return Excel::download(new UserExport($filters), $fileName);
    }
}
