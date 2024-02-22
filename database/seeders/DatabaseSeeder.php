<?php

namespace Database\Seeders;

use App\Models\Type;
use App\Models\User;
use App\Models\Project;
use App\Models\Customer;
use App\Models\TimeEntry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create customers and projects with a relation
        $customers = Customer::factory()->count(10)->hasProjects(10)->create();

        // Refactor project types creation
        $projectTypes = collect(['Standup', 'Code Review', 'Implementing & Testing', 'Bugfixing', 'Setup'])
            ->map(function ($typeName) {
                return Type::factory()->create(['name' => $typeName]);
            });

        // Create a default user
        $user = User::factory()->create([
            'name' => 'Stijn Sagaert',
            'email' => 'stijn.sagaert@pareteum.com',
            'password' => bcrypt('password'),
            'company' => 'Pareteum',
            'email_verified_at' => now(),
        ]);

        // Create time entries with random project and type
        TimeEntry::factory(1000)->create([
            'project_id' => $customers->random()->projects->random()->id,
            'type_id' => $projectTypes->random()->id,
            'owner_id' => $user->id,
        ]);
    }
}
