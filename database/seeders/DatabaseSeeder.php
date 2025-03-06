<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Project;
use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
        ]);

        $user2 = User::factory()->create([
            'first_name' => 'Ahmed',
            'last_name' => 'Abdelsalam',
            'email' => 'admin@example.com',
        ]);

        $attr = Attribute::create([
            'name' => 'department',
            'type' => 'select'
        ]);

        $attr2 = Attribute::create([
            'name' => 'budget',
            'type' => 'number'
        ]);

        $attr3 = Attribute::create([
            'name' => 'start_date',
            'type' => 'date'
        ]);

        $project = Project::create([
            'name' => 'Project 1',
            'status' => 'active'
        ]);

        $project2 = Project::create([
            'name' => 'Project 2',
            'status' => 'inactive'
        ]);

        $project->attributeValues()->create([
            'attribute_id' => $attr->id,
            'value' => 'IT',
        ]);

        $project->attributeValues()->create([
            'attribute_id' => $attr2->id,
            'value' => 5000,
        ]);

        $project->attributeValues()->create([
            'attribute_id' => $attr3->id,
            'value' => '20/11/2025',
        ]);

        $project2->attributeValues()->create([
            'attribute_id' => $attr->id,
            'value' => 'SALES',
        ]);

        $user->projects()->attach($project->id);

        $user2->projects()->attach($project->id);
        $user2->projects()->attach($project2->id);

        foreach ($user->projects as $project) {
            Timesheet::create([
                'user_id' => $user->id,
                'project_id' => $project->id,
                'task_name' => $user->id . ' Task for ' . $project->name,
                'date' => now()->toDateString(),
                'hours' => rand(1, 8),
            ]);
        }

        foreach ($user2->projects as $project) {
            Timesheet::create([
                'user_id' => $user->id,
                'project_id' => $project->id,
                'task_name' => $user->id . ' Task for ' . $project->name,
                'date' => now()->toDateString(),
                'hours' => rand(1, 8),
            ]);
        }
    }
}
