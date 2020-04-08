<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;

class UserSummaryController extends Controller
{
    public function show(Order $order)
    {
    	$data = $order;
        return view('user.summary', compact('data'));
    }
}
