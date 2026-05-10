<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\UserCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserCustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        
        $query = UserCustomer::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $customers = $query->latest()->paginate(10);
        
        return view('backend.user-customer.index', compact('customers'));
    }

    public function create()
    {
        return view('backend.user-customer.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:user_customers,email|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'province' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        UserCustomer::create($request->all());

        return redirect()->route('backend.user-customer.index')
            ->with('success', 'Customer berhasil ditambahkan');
    }

    public function show(UserCustomer $userCustomer)
    {
        return view('backend.user-customer.show', compact('userCustomer'));
    }

    public function edit(UserCustomer $userCustomer)
    {
        return view('backend.user-customer.edit', compact('userCustomer'));
    }

    public function update(Request $request, UserCustomer $userCustomer)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:user_customers,email,' . $userCustomer->id . '|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'province' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $userCustomer->update($request->all());

        return redirect()->route('backend.user-customer.index')
            ->with('success', 'Customer berhasil diperbarui');
    }

    public function destroy(UserCustomer $userCustomer)
    {
        $userCustomer->delete();

        return redirect()->route('backend.user-customer.index')
            ->with('success', 'Customer berhasil dihapus');
    }
}
