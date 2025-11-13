<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        return view('super_admin.subscription');
    }
    public function list(Request $request)
    {
        $query = Subscription::with(['plan:id,name', 'tenant:id,name,email']);
        if (Auth::guard('super-admin')->check()) {
        } elseif (Auth::guard('tenant')->check()) {
            $tenantId = Auth::guard('tenant')->id();
            $query->where('tenant_id', $tenantId);
        } elseif (Auth::guard('owner')->check()) {
            $request->validate([
                'tenant_id' => 'required|exists:tenants,id',
            ]);
            $query->where('tenant_id', $request->tenant_id);
        }
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        $subscriptions = $query->paginate($request->get('length', 10));
        return response()->json([
            'data' => $subscriptions->items(),
            'recordsTotal' => $subscriptions->total(),
            'recordsFiltered' => $subscriptions->total(),
        ]);
    }
}
