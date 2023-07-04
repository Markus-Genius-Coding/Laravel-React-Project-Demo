<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultProjectSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        if (!Project::where('name', 'Test Project')->exists()) {
            $project = new Project();
            $project->name = 'Test Project';
            $project->description = 'Test description.';
            $project->save();
        }
    }
}
