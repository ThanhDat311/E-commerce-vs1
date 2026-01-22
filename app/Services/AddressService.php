<?php

namespace App\Services;

use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Exception;

class AddressService
{
    /**
     * Lấy danh sách địa chỉ của User
     */
    public function getUserAddresses($userId)
    {
        return Address::where('user_id', $userId)
            ->orderBy('is_default', 'desc') // Default lên đầu
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Tạo địa chỉ mới
     */
    public function createAddress($userId, array $data)
    {
        // Nếu user chưa có địa chỉ nào, cái đầu tiên sẽ là default
        $count = Address::where('user_id', $userId)->count();
        if ($count === 0) {
            $data['is_default'] = true;
        }

        $data['user_id'] = $userId;

        // Nếu set default = true, cần reset các cái cũ
        if (isset($data['is_default']) && $data['is_default']) {
            Address::where('user_id', $userId)->update(['is_default' => false]);
        }

        return Address::create($data);
    }

    /**
     * Cập nhật địa chỉ
     */
    public function updateAddress($addressId, $userId, array $data)
    {
        $address = Address::where('id', $addressId)->where('user_id', $userId)->firstOrFail();

        // Xử lý logic Default
        if (isset($data['is_default']) && $data['is_default']) {
            Address::where('user_id', $userId)->where('id', '!=', $addressId)->update(['is_default' => false]);
        }

        $address->update($data);
        return $address;
    }

    /**
     * Set một địa chỉ làm mặc định
     */
    public function setDefault($addressId, $userId)
    {
        DB::transaction(function () use ($addressId, $userId) {
            // 1. Bỏ default tất cả
            Address::where('user_id', $userId)->update(['is_default' => false]);

            // 2. Set default cho cái được chọn
            Address::where('id', $addressId)
                ->where('user_id', $userId)
                ->update(['is_default' => true]);
        });
    }

    /**
     * Xóa địa chỉ
     */
    public function deleteAddress($addressId, $userId)
    {
        $address = Address::where('id', $addressId)->where('user_id', $userId)->firstOrFail();

        // Không cho xóa nếu là địa chỉ mặc định (bắt buộc phải có 1 cái, trừ khi xóa hết)
        if ($address->is_default && Address::where('user_id', $userId)->count() > 1) {
            throw new Exception("Cannot delete default address. Please set another address as default first.");
        }

        return $address->delete();
    }
}
