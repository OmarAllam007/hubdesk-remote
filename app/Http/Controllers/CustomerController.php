<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('name')->quickSearch()->paginate();
        return view('admin.customer.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customer.create');
    }

    public function store(Request $request)
    {
        $this->validates($request, 'Customer not saved');

        Customer::create($request->all());

        flash('Customer has been saved', 'success');

        return \Redirect::route('admin.customer.index');
    }

    public function show(Customer $customer)
    {
        return view('admin.customer.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('admin.customer.edit', compact('customer'));
    }

    public function update(Customer $customer, Request $request)
    {
        $this->rules['name'] .= ',name,' . $customer->id;
        $this->validates($request, 'Customer not saved');

        $customer->update($request->all());

        flash('City has been saved', 'success');

        return \Redirect::route('admin.customer.index');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        flash('City has been deleted', 'success');

        return \Redirect::route('admin.customer.index');
    }
}
