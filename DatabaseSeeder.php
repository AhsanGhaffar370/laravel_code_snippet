<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\CreateWorkingDayTableSeeder;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\ConnectRelationshipsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      $this->call(CreateHomeSettingTableSeeder::class);
      $this->call(CreateTestimonialsTableSeeder::class);
      $this->call(CreatePageTableSeeder::class);
      $this->call(CreateCategoriesTableSeeder::class);
      $this->call(CreateApprovalStatusTableSeeder::class);
      $this->call(CreateWorkingDayTableSeeder::class);
      $this->call(CreateCompanySettingTableSeeder::class);
      $this->call(PermissionsTableSeeder::class);
      $this->call(RolesTableSeeder::class);
      $this->call(ConnectRelationshipsSeeder::class);
      $this->call(UsersTableSeeder::class);
    }
}
