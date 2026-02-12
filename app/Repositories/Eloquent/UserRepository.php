<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::all();
    }

    public function find($id)
    {
        return User::find($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = User::find($id);
        $user->update($data);

        return $user;
    }

    public function delete($id)
    {
        return User::destroy($id);
    }

    public function count()
    {
        return User::count();
    }

    public function getActiveUsersCount()
    {
        // Assuming 'active' users are those who have logged in recently or have a status
        // For simplicity, let's just count all users for now or check email_verified_at
        return User::whereNotNull('email_verified_at')->count();
    }
}
