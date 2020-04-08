<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Order;

class UserOrderController extends Controller
{
    public function index(){

    	if (request()->ajax()) {
	        $orders = Order::where('user_id', auth()->user()->id)->get();
	        return DataTables::of($orders)
	                    ->addColumn('viewBtn', '<button type="button" class="view btn-primary">Order Summary</button>')
	                    ->rawColumns(['viewBtn'])
	                    ->editColumn('created_at', function ($orders) {
	                      return date('F, d Y, g:i a', strtotime($orders->created_at));
	                    })->make(true); //return modified datatables
        }
    	return view('user.orders');
    }
}
