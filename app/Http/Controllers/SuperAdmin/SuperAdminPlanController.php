<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;

class SuperAdminPlanController extends Controller
{
    public function index(Request $request)
    {
        return view('super_admin.plan');
    }
}
