<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Roles;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuperAdminPlanController extends Controller
{


    public function index()
    {
        $plansQuery = Plan::query();
        $plans = $plansQuery->get();
        return view('super_admin.plan', compact('plans'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150|unique:plans,name',
            'duration_days' => 'required|integer|min:1',
            'isolation' => 'required|in:shared_schema,separate_db',
            'max_users' => 'nullable|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed. Please check the highlighted fields.',
            ], 422);
        }

        $plan = Plan::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Plan created successfully.',
            'data' => $plan,
        ]);
    }

    public function edit($id)
    {
        $plan = Plan::find($id);
        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Plan not found.',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $plan,
        ]);
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::find($id);
        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Plan not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150|unique:plans,name,' . $plan->id,
            'duration_days' => 'required|integer|min:1',
            'isolation' => 'required|in:shared_schema,separate_db',
            'max_users' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed. Please check the highlighted fields.',
            ], 422);
        }

        $plan->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Plan updated successfully.',
            'data' => $plan,
        ]);
    }

    public function destroy($id)
    {
        $plan = Plan::find($id);
        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Plan not found.',
            ], 404);
        }
        $plan->delete();
        return response()->json([
            'success' => true,
            'message' => 'Plan deleted successfully.',
        ]);
    }
}
