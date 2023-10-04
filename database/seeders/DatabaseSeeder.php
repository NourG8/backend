<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\Role::factory(1)->create();
        $this->call(RoleTableSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(PositionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CongeSeeder::class);
        $this->call(TeamSeeder::class);
        $this->call(TeamUserSeeder::class);
        // \App\Models\User::factory(1)->create();
        $this->call(ContractSeeder::class);
        // \App\Models\Contract::factory(1)->create();
        \App\Models\UserContract::factory(1)->create();
        // $this->call(UserSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(PermissionRoleSeeder::class);
        $this->call(PositionUserSeeder::class);
        \App\Models\Faq::factory(1)->create();
        \App\Models\FaqDepartment::factory(1)->create();
        // \App\Models\Teletravail::factory(1)->create();
        $this->call(TeletravailSeeder::class);
        $this->call(HistoryRemoteWorkSeeder::class);
        // $this->call(CongeHistorySeeder::class);
        // $this->call(TeletravailSeeder::class);
        // \App\Models\Position::factory(2)->create();
        // \App\Models\PermissionRole::factory(1)->create();
        // \App\Models\PositionUser::factory(1)->create();


        // \App\Models\User::factory(1)->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
