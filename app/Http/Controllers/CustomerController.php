<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CustomerController extends Controller
{
    //
    public function index(){
        return Inertia::render('Customers/Index', [
            'filters' => Request::all('search', 'trashed'),
            'customers' => Auth::user()->account
                ->customer()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
        ]);
    }

    public function edit(Customer $customer){
        return Inertia::render('Customers/Edit', [
            'customer' => [
                'id' => $customer->id,
                'contact_id' => $customer->contact_id,
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'city' => $customer->city,
                'region' => $customer->region,
                'country' => $customer->country,
                'postal_code' => $customer->postal_code,
                'deleted_at' => $customer->deleted_at,
            ],
            'contacts' => Auth::user()->account->contacts()
                ->get()
                ->map
                ->only('id', 'first_name', 'last_name'),
        ]);
    }

    public function update(Customer $customer){
        $customer->update(
            Request::validate([
                'contact_id' => [
                    'nullable',
                    Rule::exists('contacts', 'id')->where(function ($query) {
                        $query->where('account_id', Auth::user()->account_id);
                })],
                'first_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ]),
        );

        return Redirect::back()->with('success', 'Customer updated.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return Redirect::back()->with('success', 'Customer deleted.');
    }

    public function restore(Customer $customer)
    {
        $customer->restore();

        return Redirect::back()->with('success', 'Customer restored.');
    }

    public function create(){
        return Inertia::render('Customers/Create', [
            'contacts' => Auth::user()->account->contacts()
                ->get()
                ->map
                ->only('id', 'first_name', 'last_name')
        ]);
    }

    public function store(){
        Auth::user()->account->customer()->create(
            Request::validate([
                'contact_id' => [
                    'nullable',
                    Rule::exists('contacts', 'id')->where(function ($query) {
                        $query->where('account_id', Auth::user()->account_id);
                })],
                'first_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::route('customers')->with('success', 'Customer created. ');
    }
}
