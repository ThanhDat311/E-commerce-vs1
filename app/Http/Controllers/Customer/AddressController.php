<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Address\StoreAddressRequest;
use App\Services\AddressService; // Nhớ tạo file này
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    protected $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    public function index()
    {
        $addresses = $this->addressService->getUserAddresses(Auth::id());
        $user = Auth::user();

        return view('profile.addresses.index', compact('addresses', 'user'));
    }

    public function store(StoreAddressRequest $request)
    {
        try {
            $this->addressService->createAddress(Auth::id(), $request->validated());

            return redirect()->back()->with('success', 'Address added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(StoreAddressRequest $request, $id)
    {
        try {
            $this->addressService->updateAddress($id, Auth::id(), $request->validated());

            return redirect()->back()->with('success', 'Address updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Update failed: '.$e->getMessage());
        }
    }

    public function setDefault($id)
    {
        try {
            $this->addressService->setDefault($id, Auth::id());

            return redirect()->back()->with('success', 'Default address updated.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->addressService->deleteAddress($id, Auth::id());

            return redirect()->back()->with('success', 'Address deleted.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
