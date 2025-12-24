<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["ViewAny:Announcement","View:Announcement","Create:Announcement","Update:Announcement","Delete:Announcement","Restore:Announcement","ForceDelete:Announcement","ForceDeleteAny:Announcement","RestoreAny:Announcement","Replicate:Announcement","Reorder:Announcement","ViewAny:Equipment","View:Equipment","Create:Equipment","Update:Equipment","Delete:Equipment","Restore:Equipment","ForceDelete:Equipment","ForceDeleteAny:Equipment","RestoreAny:Equipment","Replicate:Equipment","Reorder:Equipment","ViewAny:Event","View:Event","Create:Event","Update:Event","Delete:Event","Restore:Event","ForceDelete:Event","ForceDeleteAny:Event","RestoreAny:Event","Replicate:Event","Reorder:Event","ViewAny:Guide","View:Guide","Create:Guide","Update:Guide","Delete:Guide","Restore:Guide","ForceDelete:Guide","ForceDeleteAny:Guide","RestoreAny:Guide","Replicate:Guide","Reorder:Guide","ViewAny:Mentor","View:Mentor","Create:Mentor","Update:Mentor","Delete:Mentor","Restore:Mentor","ForceDelete:Mentor","ForceDeleteAny:Mentor","RestoreAny:Mentor","Replicate:Mentor","Reorder:Mentor","ViewAny:Milestone","View:Milestone","Create:Milestone","Update:Milestone","Delete:Milestone","Restore:Milestone","ForceDelete:Milestone","ForceDeleteAny:Milestone","RestoreAny:Milestone","Replicate:Milestone","Reorder:Milestone","ViewAny:ReserveEquipment","View:ReserveEquipment","Create:ReserveEquipment","Update:ReserveEquipment","Delete:ReserveEquipment","Restore:ReserveEquipment","ForceDelete:ReserveEquipment","ForceDeleteAny:ReserveEquipment","RestoreAny:ReserveEquipment","Replicate:ReserveEquipment","Reorder:ReserveEquipment","ViewAny:ReserveRoom","View:ReserveRoom","Create:ReserveRoom","Update:ReserveRoom","Delete:ReserveRoom","Restore:ReserveRoom","ForceDelete:ReserveRoom","ForceDeleteAny:ReserveRoom","RestoreAny:ReserveRoom","Replicate:ReserveRoom","Reorder:ReserveRoom","ViewAny:ReserveSupply","View:ReserveSupply","Create:ReserveSupply","Update:ReserveSupply","Delete:ReserveSupply","Restore:ReserveSupply","ForceDelete:ReserveSupply","ForceDeleteAny:ReserveSupply","RestoreAny:ReserveSupply","Replicate:ReserveSupply","Reorder:ReserveSupply","ViewAny:Role","View:Role","Create:Role","Update:Role","Delete:Role","Restore:Role","ForceDelete:Role","ForceDeleteAny:Role","RestoreAny:Role","Replicate:Role","Reorder:Role","ViewAny:Room","View:Room","Create:Room","Update:Room","Delete:Room","Restore:Room","ForceDelete:Room","ForceDeleteAny:Room","RestoreAny:Room","Replicate:Room","Reorder:Room","ViewAny:Startup","View:Startup","Create:Startup","Update:Startup","Delete:Startup","Restore:Startup","ForceDelete:Startup","ForceDeleteAny:Startup","RestoreAny:Startup","Replicate:Startup","Reorder:Startup","ViewAny:Supply","View:Supply","Create:Supply","Update:Supply","Delete:Supply","Restore:Supply","ForceDelete:Supply","ForceDeleteAny:Supply","RestoreAny:Supply","Replicate:Supply","Reorder:Supply","ViewAny:UnavailableEquipment","View:UnavailableEquipment","Create:UnavailableEquipment","Update:UnavailableEquipment","Delete:UnavailableEquipment","Restore:UnavailableEquipment","ForceDelete:UnavailableEquipment","ForceDeleteAny:UnavailableEquipment","RestoreAny:UnavailableEquipment","Replicate:UnavailableEquipment","Reorder:UnavailableEquipment","ViewAny:UnavailableSupply","View:UnavailableSupply","Create:UnavailableSupply","Update:UnavailableSupply","Delete:UnavailableSupply","Restore:UnavailableSupply","ForceDelete:UnavailableSupply","ForceDeleteAny:UnavailableSupply","RestoreAny:UnavailableSupply","Replicate:UnavailableSupply","Reorder:UnavailableSupply","ViewAny:User","View:User","Create:User","Update:User","Delete:User","Restore:User","ForceDelete:User","ForceDeleteAny:User","RestoreAny:User","Replicate:User","Reorder:User","ViewAny:Video","View:Video","Create:Video","Update:Video","Delete:Video","Restore:Video","ForceDelete:Video","ForceDeleteAny:Video","RestoreAny:Video","Replicate:Video","Reorder:Video","View:EquipmentReport","View:EventReport","View:Guidelines","View:RoomReport","View:SupplyReport","View:WelcomeCard","View:ReservationsCount","View:ReservationStatus","View:StartupsSubmission","View:UsersDonut","View:AnalyticsStats","View:AnnouncementWidget","View:DashboardEvents"]},{"name":"admin","guard_name":"web","permissions":["ViewAny:Announcement","View:Announcement","Create:Announcement","Update:Announcement","Delete:Announcement","ViewAny:Equipment","View:Equipment","Create:Equipment","Update:Equipment","Delete:Equipment","ViewAny:Event","View:Event","Create:Event","Update:Event","Delete:Event","ViewAny:Guide","View:Guide","Create:Guide","Update:Guide","Delete:Guide","ViewAny:Mentor","View:Mentor","Create:Mentor","Update:Mentor","Delete:Mentor","ViewAny:Milestone","View:Milestone","Create:Milestone","Update:Milestone","Delete:Milestone","ViewAny:ReserveEquipment","View:ReserveEquipment","Create:ReserveEquipment","Update:ReserveEquipment","Delete:ReserveEquipment","ViewAny:ReserveRoom","View:ReserveRoom","Create:ReserveRoom","Update:ReserveRoom","Delete:ReserveRoom","ViewAny:ReserveSupply","View:ReserveSupply","Create:ReserveSupply","Update:ReserveSupply","Delete:ReserveSupply","ViewAny:Room","View:Room","Create:Room","Update:Room","Delete:Room","ViewAny:Startup","View:Startup","Create:Startup","Update:Startup","Delete:Startup","ViewAny:Supply","View:Supply","Create:Supply","Update:Supply","Delete:Supply","ViewAny:UnavailableEquipment","View:UnavailableEquipment","Create:UnavailableEquipment","Update:UnavailableEquipment","Delete:UnavailableEquipment","ViewAny:UnavailableSupply","View:UnavailableSupply","Create:UnavailableSupply","Update:UnavailableSupply","Delete:UnavailableSupply","ViewAny:User","View:User","ViewAny:Video","View:Video","Create:Video","Delete:Video"]},{"name":"investor","guard_name":"web","permissions":["ViewAny:Event","View:Event","ViewAny:Startup","View:Startup"]},{"name":"incubatee","guard_name":"web","permissions":["ViewAny:Event","View:Event","ViewAny:Guide","View:Guide","ViewAny:Mentor","View:Mentor","ViewAny:Milestone","View:Milestone","Update:Milestone","ViewAny:ReserveEquipment","View:ReserveEquipment","Create:ReserveEquipment","ViewAny:ReserveRoom","View:ReserveRoom","Create:ReserveRoom","ViewAny:ReserveSupply","View:ReserveSupply","Create:ReserveSupply","ViewAny:Room","View:Room","ViewAny:Startup","View:Startup","Create:Startup","ViewAny:Video","View:Video","Create:Video"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
