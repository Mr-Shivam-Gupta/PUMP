<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Owner;
use App\Models\Tenant;
use Illuminate\Http\Request;

class SuperAdminOwnerController extends Controller
{
    /**
     * Display a listing of the owners.
     */
    public function index()
    {
        $owners = Owner::latest()->get();
        foreach ($owners as $owner) {
            if (!empty($owner->own_tenant_ids)) {
                $tenants = Tenant::whereIn('id', $owner->own_tenant_ids)
                    ->select('name', 'domain')
                    ->get()
                    ->map(function ($tenant) {
                        return "{$tenant->name} ({$tenant->domain})";
                    })
                    ->toArray();

                $owner->tenant_names = $tenants;
            } else {
                $owner->tenant_names = [];
            }
        }
        return view('super_admin.owner', compact('owners'));
    }

    /**
     * Store a newly created owner in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'owner_id' => 'required|string|max:255|unique:owners,owner_id',
            'owner_password' => 'required|string|min:6',
            'own_tenant_ids' => 'nullable|array|min:2',
            'own_tenant_ids.*' => 'integer|exists:tenants,id',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $validator->validated();

        // Ensure no tenant in own_tenant_ids is already assigned to another owner
        if (!empty($data['own_tenant_ids']) && is_array($data['own_tenant_ids'])) {
            $conflicts = [];
            foreach ($data['own_tenant_ids'] as $tenantId) {
                $existing = Owner::whereJsonContains('own_tenant_ids', $tenantId)->first();
                if ($existing) {
                    $conflicts[] = $tenantId;
                }
            }

            if (!empty($conflicts)) {
                // Try to get tenant names for a clearer message
                $tenantNames = Tenant::whereIn('id', $conflicts)->pluck('name')->toArray();
                $label = !empty($tenantNames) ? implode(', ', $tenantNames) : implode(', ', $conflicts);

                return response()->json([
                    'success' => false,
                    'message' => "Cannot assign tenant(s) [{$label}] because they are already assigned to another owner.",
                ], 422);
            }
        }

        $data['owner_password'] = Hash::make($data['owner_password']);

        $owner = Owner::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Owner created successfully.',
            'data' => $owner,
        ]);
    }

    /**
     * Get single owner details (for edit modal).
     */
    public function edit($id)
    {
        $owner = Owner::find($id);
        if (!$owner) {
            return response()->json([
                'success' => false,
                'message' => 'Owner not found.',
            ], 404);
        }

        // Return raw password as blank for security
        $owner->owner_password = '';

        return response()->json([
            'success' => true,
            'data' => $owner,
        ]);
    }

    /**
     * Update an existing owner.
     */
    public function update(Request $request, $id)
    {
        $owner = Owner::find($id);
        if (!$owner) {
            return response()->json([
                'success' => false,
                'message' => 'Owner not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'owner_id' => 'required|string|max:255|unique:owners,owner_id,' . $owner->id,
            'owner_password' => 'nullable|string|min:6',
            'own_tenant_ids' => 'nullable|array|min:2',
            'own_tenant_ids.*' => 'integer|exists:tenants,id',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $validator->validated();

        // Ensure no tenant in own_tenant_ids is already assigned to another owner (exclude this owner)
        if (!empty($data['own_tenant_ids']) && is_array($data['own_tenant_ids'])) {
            $conflicts = [];
            foreach ($data['own_tenant_ids'] as $tenantId) {
                $existing = Owner::where('id', '!=', $owner->id)
                    ->whereJsonContains('own_tenant_ids', $tenantId)
                    ->first();
                if ($existing) {
                    $conflicts[] = $tenantId;
                }
            }

            if (!empty($conflicts)) {
                $tenantNames = Tenant::whereIn('id', $conflicts)->pluck('name')->toArray();
                $label = !empty($tenantNames) ? implode(', ', $tenantNames) : implode(', ', $conflicts);

                return response()->json([
                    'success' => false,
                    'message' => "Cannot assign tenant(s) [{$label}] because they are already assigned to another owner.",
                ], 422);
            }
        }

        // If password is provided, hash it
        if (!empty($data['owner_password'])) {
            $data['owner_password'] = Hash::make($data['owner_password']);
        } else {
            unset($data['owner_password']);
        }

        $owner->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Owner updated successfully.',
            'data' => $owner,
        ]);
    }

    /**
     * Delete an owner.
     */
    public function destroy($id)
    {
        $owner = Owner::find($id);
        if (!$owner) {
            return response()->json([
                'success' => false,
                'message' => 'Owner not found.',
            ], 404);
        }

        $owner->delete();

        return response()->json([
            'success' => true,
            'message' => 'Owner deleted successfully.',
        ]);
    }

    /**
     * Toggle owner status (active/inactive).
     */
    public function toggleStatus($id)
    {
        $owner = Owner::find($id);
        if (!$owner) {
            return response()->json([
                'success' => false,
                'message' => 'Owner not found.',
            ], 404);
        }

        $owner->status = !$owner->status;
        $owner->save();

        return response()->json([
            'success' => true,
            'message' => 'Owner status updated successfully.',
            'data' => ['status' => $owner->status],
        ]);
    }
}
