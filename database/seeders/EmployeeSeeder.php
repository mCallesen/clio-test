<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Employee::truncate();

        Employee::create([
            'name' => 'root',
            'height' => 0,
            'job_title' => 'CEO',
        ]);
    }
}
