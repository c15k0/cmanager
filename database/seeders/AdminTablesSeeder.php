<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class AdminTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // base tables
        \OpenAdmin\Admin\Auth\Database\Menu::truncate();
        \OpenAdmin\Admin\Auth\Database\Menu::insert(
            [
                [
                    "parent_id" => 0,
                    "order" => 1,
                    "title" => "Dashboard",
                    "icon" => "icon-chart-bar",
                    "uri" => "/",
                    "permission" => "dashboard"
                ],
                [
                    "parent_id" => 0,
                    "order" => 8,
                    "title" => "Admin",
                    "icon" => "icon-server",
                    "uri" => NULL,
                    "permission" => NULL
                ],
                [
                    "parent_id" => 2,
                    "order" => 10,
                    "title" => "Users",
                    "icon" => "icon-users",
                    "uri" => "auth/users",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 2,
                    "order" => 11,
                    "title" => "Roles",
                    "icon" => "icon-user",
                    "uri" => "auth/roles",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 2,
                    "order" => 12,
                    "title" => "Permission",
                    "icon" => "icon-ban",
                    "uri" => "auth/permissions",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 8,
                    "order" => 15,
                    "title" => "Menu",
                    "icon" => "icon-bars",
                    "uri" => "auth/menu",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 8,
                    "order" => 16,
                    "title" => "Operation log",
                    "icon" => "icon-history",
                    "uri" => "auth/logs",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 14,
                    "title" => "Helpers",
                    "icon" => "icon-cogs",
                    "uri" => NULL,
                    "permission" => NULL
                ],
                [
                    "parent_id" => 8,
                    "order" => 17,
                    "title" => "Scaffold",
                    "icon" => "icon-keyboard",
                    "uri" => "helpers/scaffold",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 8,
                    "order" => 18,
                    "title" => "Database terminal",
                    "icon" => "icon-database",
                    "uri" => "helpers/terminal/database",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 8,
                    "order" => 19,
                    "title" => "Laravel artisan",
                    "icon" => "icon-terminal",
                    "uri" => "helpers/terminal/artisan",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 8,
                    "order" => 20,
                    "title" => "Routes",
                    "icon" => "icon-list-alt",
                    "uri" => "helpers/routes",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 2,
                    "order" => 9,
                    "title" => "Customers",
                    "icon" => "icon-building",
                    "uri" => "customers",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 16,
                    "order" => 6,
                    "title" => "Contactos",
                    "icon" => "icon-address-card",
                    "uri" => "contacts",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 16,
                    "order" => 5,
                    "title" => "Plantillas de email",
                    "icon" => "icon-puzzle-piece",
                    "uri" => "templates",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 3,
                    "title" => "Gestión",
                    "icon" => "icon-users-cog",
                    "uri" => NULL,
                    "permission" => NULL
                ],
                [
                    "parent_id" => 16,
                    "order" => 7,
                    "title" => "Grupos",
                    "icon" => "icon-users",
                    "uri" => "groups",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 2,
                    "title" => "Campañas",
                    "icon" => "icon-broadcast-tower",
                    "uri" => "campaigns",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 2,
                    "order" => 13,
                    "title" => "Receptores",
                    "icon" => "icon-user-edit",
                    "uri" => "receivers",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 16,
                    "order" => 4,
                    "title" => "Recursos",
                    "icon" => "icon-images",
                    "uri" => "media",
                    "permission" => NULL
                ]
            ]
        );

        \OpenAdmin\Admin\Auth\Database\Permission::truncate();
        \OpenAdmin\Admin\Auth\Database\Permission::insert(
            [
                [
                    "name" => "All permission",
                    "slug" => "*",
                    "http_method" => "",
                    "http_path" => "*"
                ],
                [
                    "name" => "Dashboard",
                    "slug" => "dashboard",
                    "http_method" => "GET",
                    "http_path" => "/"
                ],
                [
                    "name" => "Login",
                    "slug" => "auth.login",
                    "http_method" => "",
                    "http_path" => "/auth/login\r\n/auth/logout"
                ],
                [
                    "name" => "User setting",
                    "slug" => "auth.setting",
                    "http_method" => "GET,PUT",
                    "http_path" => "/auth/setting"
                ],
                [
                    "name" => "Auth management",
                    "slug" => "auth.management",
                    "http_method" => "",
                    "http_path" => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs"
                ],
                [
                    "name" => "Admin helpers",
                    "slug" => "ext.helpers",
                    "http_method" => "",
                    "http_path" => "/helpers/*"
                ],
                [
                    "name" => "Management",
                    "slug" => "management",
                    "http_method" => "",
                    "http_path" => "/contacts*\r\n/groups*\r\n/templates*\r\n/campaigns*\r\n/visits*\r\n/media*"
                ],
                [
                    "name" => "Media manager",
                    "slug" => "ext.media-manager",
                    "http_method" => "",
                    "http_path" => "/media*"
                ]
            ]
        );

        \OpenAdmin\Admin\Auth\Database\Role::truncate();
        \OpenAdmin\Admin\Auth\Database\Role::insert(
            [
                [
                    "name" => "Administrator",
                    "slug" => "administrator"
                ],
                [
                    "name" => "Manager",
                    "slug" => "manager"
                ]
            ]
        );

        // pivot tables
        DB::table('admin_role_menu')->truncate();
        DB::table('admin_role_menu')->insert(
            [
                [
                    "role_id" => 1,
                    "menu_id" => 2
                ],
                [
                    "role_id" => 1,
                    "menu_id" => 8
                ],
                [
                    "role_id" => 1,
                    "menu_id" => 13
                ],
                [
                    "role_id" => 1,
                    "menu_id" => 19
                ],
                [
                    "role_id" => 1,
                    "menu_id" => 20
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 14
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 15
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 16
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 17
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 18
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 20
                ]
            ]
        );

        DB::table('admin_role_permissions')->truncate();
        DB::table('admin_role_permissions')->insert(
            [
                [
                    "role_id" => 1,
                    "permission_id" => 1
                ],
                [
                    "role_id" => 2,
                    "permission_id" => 2
                ],
                [
                    "role_id" => 2,
                    "permission_id" => 3
                ],
                [
                    "role_id" => 2,
                    "permission_id" => 4
                ],
                [
                    "role_id" => 2,
                    "permission_id" => 7
                ]
            ]
        );

        // finish
    }
}
