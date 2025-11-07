<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Tenant;

class SuperAdminTenantController extends Controller
{
    public function createTenant(Request $request)
    {
        $slug = Str::slug($request->slug ?? $request->name);
        $database = 'tenant_' . strtolower($slug);
        $dbUsername = 'tenant_user_' . strtolower($slug);

        $tenant = Tenant::create([
            'name' => $request->name,
            'slug' => $slug,
            'domain' => $request->domain,
            'contact' => $request->contact,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'logo' => $request->logo ?? null,
            'address' => $request->address ?? null,
            'gst_number' => $request->gst_number ?? null,
            'license_number' => $request->license_number ?? null,
            'type' => $request->type ?? null,
            'plan' => $request->plan ?? null,
            'isolation' => $request->isolation ?? 'database',
            'database' => $database,
            'db_username' => $dbUsername,
            'db_password' => Str::random(12),
        ]);

        // Create the new database
        DB::statement('CREATE DATABASE ' . $tenant->database);

        // Run migrations specifically for that tenant
        Artisan::call('migrate', [
            '--database' => 'tenant_connection',
            '--path' => '/database/migrations/tenant',
            '--force' => true,
        ]);

        return response()->json(['message' => 'Tenant created successfully']);
    }
}
