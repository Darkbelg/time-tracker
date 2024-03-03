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
use Illuminate\Database\Eloquent\Builder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create customers and projects with a relation
        $customers = Customer::factory()->count(10)->hasProjects(10)->create();

        // Refactor project types creation
        $projectTypes = collect(['Standup', 'Code Review', 'Implementing & Testing', 'Bugfixing', 'Setup', 'Other', null])
            ->map(function ($typeName) {
                return Type::factory()->create(['name' => $typeName]);
            });

        // Create a default user
        $user = User::factory()->create([
            'first_name' => 'Stijn',
            'last_name' => 'Sagaert',
            'email' => 'stijn.sagaert@example.com',
            'password' => bcrypt('password'),
            'company' => 'Example',
            'email_verified_at' => now(),
        ]);

        $user->assignRole('developer');

        // Create time entries with random project and type
        for ($i = 0; $i < 1000; $i++) {
            TimeEntry::factory()->create([
                'project_id' => $customers->random()->projects->random()->id,
                'type_id' => $projectTypes->random()->id,
                'owner_id' => $user->id,
            ]);
        }

        // Create a default user
        $user2 = User::factory()->create([
            'first_name' => 'Stijn',
            'last_name' => 'Sagaert',
            'email' => 'stijn.sagaert+not@example.com',
            'password' => bcrypt('password'),
            'company' => 'Example',
            'email_verified_at' => now(),
        ]);

        $user2->assignRole('developer');

        // Create time entries with random project and type
        for ($i = 0; $i < 1000; $i++) {
            TimeEntry::factory()->create([
                'project_id' => $customers->random()->projects->random()->id,
                'type_id' => $projectTypes->random()->id,
                'owner_id' => $user2->id,
            ]);
        }

        $typeOtherTimeEntries = TimeEntry::whereHas('type', function (Builder $query) {
            $query->where('name', 'Other');
        })->get();

        $typeOtherTimeEntries->each(function ($item) {
            $item->comment = fake()->text();
            $item->save();
        });

        // Create a default user
        $user3 = User::factory()->create([
            'first_name' => 'Stijn',
            'last_name' => 'Sagaert',
            'email' => 'stijn.sagaert+moderator@example.com',
            'password' => bcrypt('password'),
            'company' => 'Example',
            'email_verified_at' => now(),
        ]);

        $user3->assignRole('manager');
    }
}
