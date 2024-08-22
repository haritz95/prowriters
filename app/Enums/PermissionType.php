<?php

namespace App\Enums;

abstract class PermissionType
{
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_EDITOR      = 'editor';

    public static function getRoles()
    {
        return [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_EDITOR,
        ];
    }

    public static function getRolesDropdown()
    {
        return [
            ['id' => self::ROLE_SUPER_ADMIN, 'name' => __('Super Admin')],
            ['id' => self::ROLE_EDITOR, 'name' => __('Editor')],
        ];
    }
    
    public static function getRolesAsList()
    {
        return [
            self::ROLE_SUPER_ADMIN => __('Super Admin'),
            self::ROLE_EDITOR => __('Editor'),            
        ];
    }

    public static function getRoleNameById($id)
    {
        $roles = self::getRolesAsList();
        return isset($roles[$id]) ? $roles[$id] : null;
    }
    // // List of permissions
    // const MANAGE_TASKS       = 'manage_tasks';
    // const MANAGE_SETTINGS    = 'manage_settings';
    // const MANAGE_USERS       = 'manage_users';
    // const MANAGE_CUSTOMERS   = 'manage_customers';
    // const MANAGE_AUTHORS = 'manage_authors';
    // const MANAGE_BILLS       = 'manage_bills';
    // const MANAGE_HIRING      = 'manage_hiring';
    // const MANAGE_PAYMENTS    = 'manage_payments';
    // const CONTROL_QUALITY    = 'control_quality';
    // const VIEW_REPORT        = 'view_report';
    // const MANAGE_WEBSITE     = 'manage_website';
    // const MANAGE_BLOG        = 'manage_blog';

    // public static function all()
    // {
    //     return [
    //         self::MANAGE_TASKS,
    //         self::MANAGE_SETTINGS,
    //         self::MANAGE_USERS,
    //         self::MANAGE_CUSTOMERS,
    //         self::MANAGE_AUTHORS,
    //         self::MANAGE_BILLS,
    //         self::MANAGE_HIRING,
    //         self::MANAGE_PAYMENTS,
    //         self::CONTROL_QUALITY,
    //         self::VIEW_REPORT,
    //         self::MANAGE_WEBSITE,
    //         self::MANAGE_BLOG,
    //     ];
    // }

    // public static function getListForDatabase()
    // {
    //     $list = self::all();
    //     foreach ($list as $name) {
    //         $data[] = ['name' => $name];
    //     }
    //     return $data;
    // }

    // public static function getNames()
    // {
    //     return [

    //         self::MANAGE_TASKS       => __('Manage Tasks'),
    //         self::MANAGE_SETTINGS    => __('Manage Settings'),
    //         self::MANAGE_USERS       => __('Manage Users'),
    //         self::MANAGE_CUSTOMERS   => __('Manage Customers'),
    //         self::MANAGE_AUTHORS => __('Manage Authors'),
    //         self::MANAGE_BILLS       => __('Manage Bills From Authors'),
    //         self::MANAGE_HIRING      => __('Manage Author Hiring'),
    //         self::MANAGE_PAYMENTS    => __('Manage Payments'),
    //         self::CONTROL_QUALITY    => __('Manage Task Quality Control'),
    //         self::VIEW_REPORT        => __('View Reports'),
    //         self::MANAGE_WEBSITE     => __('Manage Website'),
    //         self::MANAGE_BLOG        => __('Manage Blog'),
    //     ];
    // }
}
