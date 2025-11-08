<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Tenant;

class SuperAdminTenantController extends Controller
{
    // public function createTenant(Request $request)
    // {
    //     $slug = Str::slug($request->slug ?? $request->name);
    //     $database = 'tenant_' . strtolower($slug);
    //     $dbUsername = 'tenant_user_' . strtolower($slug);

    //     $tenant = Tenant::create([
    //         'name' => $request->name,
    //         'slug' => $slug,
    //         'domain' => $request->domain,
    //         'contact' => $request->contact,
    //         'email' => $request->email,
    //         'password' => bcrypt($request->password),
    //         'logo' => $request->logo ?? null,
    //         'address' => $request->address ?? null,
    //         'gst_number' => $request->gst_number ?? null,
    //         'license_number' => $request->license_number ?? null,
    //         'type' => $request->type ?? null,
    //         'plan' => $request->plan ?? null,
    //         'isolation' => $request->isolation ?? 'database',
    //         'database' => $database,
    //         'db_username' => $dbUsername,
    //         'db_password' => Str::random(12),
    //     ]);

    //     // Create the new database
    //     DB::statement('CREATE DATABASE ' . $tenant->database);

    //     // Run migrations specifically for that tenant
    //     Artisan::call('migrate', [
    //         '--database' => 'tenant_connection',
    //         '--path' => '/database/migrations/tenant',
    //         '--force' => true,
    //     ]);

    //     return response()->json(['message' => 'Tenant created successfully']);
    // }
    public function listTenants()
    {
        $tenants = Tenant::select('id', 'name', 'contact', 'email', 'domain')->orderBy('name')->get();

        return response()->json($tenants);
    }
    public function index()
    {
        $tenants = Tenant::latest()->get();
        return view('super_admin.tenant', compact('tenants'));
    }

    /**
     * Store a newly created tenant (JSON Response).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'slug'      => 'nullable|string|max:255|unique:tenants,slug',
            'domain'    => 'nullable|string|max:255|unique:tenants,domain',
            'contact'   => 'required|string|max:15',
            'email'     => 'required|email|unique:tenants,email',
            'password'  => 'required|min:6',
            'type'      => 'nullable|string',
            'plan'      => 'nullable|string',
            'isolation' => 'required|in:database,shared_schema',
            'address'   => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:50',
            'license_number' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $data = $validator->validated();
            $slug = Str::slug($data['slug'] ?? $data['name']);
            $database = 'tenant_' . strtolower($slug);
            $dbUsername = 'tenant_user_' . strtolower($slug);

            $tenant = Tenant::create([
                ...$data,
                'slug'        => $slug,
                'database'    => $database,
                'db_username' => $dbUsername,
                'db_password' => Str::random(12),
                'password'    => Hash::make($request->password),
                'status'      => 1,
            ]);

            if ($tenant->isolation === 'database') {
                DB::statement("CREATE DATABASE IF NOT EXISTS `$database`");

                config(['database.connections.tenant_connection.database' => $database]);
                Artisan::call('migrate', [
                    '--database' => 'tenant_connection',
                    '--path' => '/database/migrations/tenant',
                    '--force' => true,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tenant created successfully.',
                'data'    => $tenant,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create tenant: ' . $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single tenant details (for edit modal).
     */
    public function edit($id)
    {
        $tenant = Tenant::find($id);

        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $tenant,
        ]);
    }

    /**
     * Update the specified tenant.
     */
    public function update(Request $request, $id)
    {
        $tenant = Tenant::find($id);

        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'domain'    => 'nullable|string|max:255|unique:tenants,domain,' . $tenant->id,
            'contact'   => 'required|string|max:15',
            'email'     => 'required|email|unique:tenants,email,' . $tenant->id,
            'password'  => 'nullable|min:6',
            'type'      => 'nullable|string',
            'plan'      => 'nullable|string',
            'address'   => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:50',
            'license_number' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $validator->validated();
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            $data['password'] = $tenant->password;
        }

        $tenant->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Tenant updated successfully.',
            'data' => $tenant,
        ]);
    }

    /**
     * Delete tenant.
     */
    public function destroy($id)
    {
        $tenant = Tenant::find($id);

        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not found.',
            ], 404);
        }

        try {
            if ($tenant->isolation === 'database' && $tenant->database) {
                DB::statement("DROP DATABASE IF EXISTS `$tenant->database`");
            }

            $tenant->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tenant deleted successfully.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete tenant: ' . $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle tenant status (active/inactive).
     */
    public function toggleStatus($id)
    {
        $tenant = Tenant::find($id);

        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not found.',
            ], 404);
        }

        $tenant->status = !$tenant->status;
        $tenant->save();

        return response()->json([
            'success' => true,
            'message' => 'Tenant status updated successfully.',
            'data' => ['status' => $tenant->status],
        ]);
    }
}
