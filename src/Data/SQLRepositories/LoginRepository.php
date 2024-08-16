<?php

namespace Data\SQLRepositories;

use Business\Domain\DomainAdmin;
use Business\Interfaces\RepositoryInterface\LoginRepositoryInterface;
use Illuminate\Database\Capsule\Manager as Capsule;

class LoginRepository implements LoginRepositoryInterface
{
    public function findByUsername(string $username): ?DomainAdmin
    {
        $adminData = Capsule::table('admins')->where('username', $username)->first();

        if ($adminData) {
            return new DomainAdmin(
                $adminData->id,
                $adminData->username,
                $adminData->password,
                $adminData->token
            );
        }

        return null;
    }

    public function create(string $username, string $hashedPassword): DomainAdmin
    {
        $adminId = Capsule::table('admins')->insertGetId([
            'username' => $username,
            'password' => $hashedPassword,
            'token' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return new DomainAdmin($adminId, $username, $hashedPassword);
    }
}
