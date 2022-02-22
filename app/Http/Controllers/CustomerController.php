<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CustomerController extends Controller
{
    //
    public function index(){
        return Inertia::render('Customers/Index', [
            'customers' => Auth::user()->account
                ->customer()
                ->paginate(10)
        ]);
    }
}
