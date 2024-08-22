<?php

namespace App\Services;

use App\Enums\PermissionType;
use App\Models\User;

class ManageUserPermissionService
{

    public function assignRole($new_role, User $user)
    {
        $user->assignRole($new_role);

        $roles = PermissionType::getRoles();

        foreach ($roles as $role) {
            if ($role != $new_role) {
                $user->removeRole($role);
            }
        }

    }

    // public function __invoke2(array $permissions, User $user)
    // {
    //     $this->permissions = $permissions;
    //     $this->user = $user;

    //     if ($this->hasGivenPermission('is_super_admin')) {
    //         $this->user->assignRole(PermissionType::ROLE_SUPER_ADMIN);
    //     } else {

    //         $this->user->removeRole(PermissionType::ROLE_SUPER_ADMIN);
    //         $scope = $this;

    //         $givenPermissions = collect(PermissionType::all())
    //             ->filter(function ($value, $key) use ($scope) {
    //                 return $scope->hasGivenPermission($value);
    //             });

    //         $this->user->syncPermissions($givenPermissions->values()->all());
    //     }
    // }

    // private function hasGivenPermission($field): bool
    // {
    //     if (isset($this->permissions[$field]) && filter_var($this->permissions[$field], FILTER_VALIDATE_BOOLEAN)) {
    //         return TRUE;
    //     }
    //     return FALSE;
    // }
}
