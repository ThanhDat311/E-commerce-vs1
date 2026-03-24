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

            return redirect()->back()->with('success', __('messages.address_added_success'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('messages.error_occurred').$e->getMessage());
        }
    }

    public function update(StoreAddressRequest $request, $id)
    {
        try {
            $this->addressService->updateAddress($id, Auth::id(), $request->validated());

            return redirect()->back()->with('success', __('messages.address_updated_success'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('messages.update_failed').$e->getMessage());
        }
    }

    public function setDefault($id)
    {
        try {
            $this->addressService->setDefault($id, Auth::id());

            return redirect()->back()->with('success', __('messages.default_address_updated'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('messages.error_occurred').$e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->addressService->deleteAddress($id, Auth::id());

            return redirect()->back()->with('success', __('messages.address_deleted'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('messages.error_occurred').$e->getMessage());
        }
    }
}
