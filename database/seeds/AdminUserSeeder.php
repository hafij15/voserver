<?php

use Illuminate\Database\Seeder;
use App\Permission;
use App\Role;
use App\User;
use App\Company;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::create([
            'name' => 'DataSoft Manufacturing & Assembly Inc. Limited',
            'address' => 'Bangabandhu Hi-Tech City Kaliakoir, Dhaka, Bangladesh',
            'descriptionn' => ''
        ]);

        $user1 = User::create([
            'name' => 'Admin',
            'email' => 'adminvo@virtualoffice.com.bd',
            'phone' => '01779800299',
            'gender' => 'Male',
            'age' => '30',
            'is_active' => 1,
            'password' => bcrypt('Admin@2020'),
            'company_id' => 1
        ]);

        // $user2 = sub_tasks::create([
        //     'sub_task_name' => 'General Meeting',
        //     'subtask_tdo_id' => 'moderator@gmail.com',
        //     'created_at' => '01780208850',
        //     'updated_at' => 'Male'
        // ]);
        //`id`, `sub_task_name`, `subtask_tdo_id`, `created_at`, `updated_at`

        // $user3 = User::create([
        //     'name' => 'Test Doctor',
        //     'email' => 'doctor@gmail.com',
        //     'phone' => '01780208851',
        //     'gender' => 'Male',
        //     'password' => bcrypt('Doctor@2020'),
        // ]);

        $role1 = Role::create([
            'slug' => 'super-admin',
            'name' => 'Super Admin',           
        ]);

        $role2 = Role::create([
            'slug' => 'admin',
            'name' => 'Moderator',           
        ]);

        $role3 = Role::create([
            'slug' => 'power-user',
            'name' => 'PM',           
        ]);

        $role4 = Role::create([
            'slug' => 'user',
            'name' => 'employee',           
        ]);

        $user1->roles()->attach($role1->id);
        // $user2->roles()->attach($role2->id);
        // $user3->roles()->attach($role3->id);


        $createTasks = new Permission();
		$createTasks->slug = 'dashboard';
		$createTasks->name = 'Dashboard';
		$createTasks->save();
        
        $createTasks->roles()->attach($role1->id);
		
    }
}
