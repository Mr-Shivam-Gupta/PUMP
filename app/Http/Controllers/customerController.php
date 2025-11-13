<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class customerController extends Controller
{
    public function showPlans()
    {
        $plans = Plan::select('id', 'name', 'price', 'duration_days', 'isolation')->get();
        return view('index', compact('plans'));
    }
}
