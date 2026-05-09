<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class JobTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks to truncate safely
        \Schema::disableForeignKeyConstraints();
        \App\Models\JobType::truncate();
        \Schema::enableForeignKeyConstraints();

        $data = [
            ['category' => 'Helpdesk/Network', 'name' => 'Hardware'],
            ['category' => 'Helpdesk/Network', 'name' => 'Software'],
            ['category' => 'Helpdesk/Network', 'name' => 'Printer/scanner'],
            ['category' => 'Helpdesk/Network', 'name' => 'His'],
            ['category' => 'Website/Infographic', 'name' => 'สร้างสื่อประชาสัมพันธ์'],
            ['category' => 'Website/Infographic', 'name' => 'Webอัพเดท/แก้ไข'],
            ['category' => 'Website/Infographic', 'name' => 'Program อัพเดท/แก้ไข'],
        ];

        foreach ($data as $item) {
            \App\Models\JobType::create($item);
        }
    }

}
