<?php

namespace App\Console\Commands;

use App\Core\User\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:superadmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a superadmin user in the system';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            # Check if the 'super-admin' permission exists
            $permission = Permission::whereName('super-admin')->first();
            if (!$permission) {
                $this->error("Please run PermissionSeeder.php artisan db:seed");
                exit();
            }

            # Create or update the 'super-admin' role and sync its permissions
            $roleName = 'super-admin';
            if ((Role::whereName($roleName)->first())) {
                $this->error("Please remove '$roleName' existing role.");
                exit();
            }

            $role = new Role();
            $role->name = $roleName;
            $role->guard_name = 'web';
            $role->save();
            $role->givePermissionTo($permission->name);

            # Create the 'Super Admin' user with the assigned role and user group
            $user = User::create([
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'user_group_id' => $role->id,
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('123456a@'),
                'national_id' => '1234567879',
                'birth_date' => "1992-06-09",
                'is_approved' => 1,
                'status' => 1
            ]);
            $user->assignRole($role->name);

            $this->info('Super Admin user create successfully');
        } catch (\Exception $exception) {
            $this->error('Error:' . $exception->getMessage());
        }
    }
}
