<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\Type;
use App\Models\User;

class TimeEntryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TimeEntry::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $timeValues = [
            0.25, 0.50, 0.75, 1.00, 2.25, 2.50, 2.75, 2.00, 3.25, 3.50, 3.75, 3.00,
            4.25, 4.50, 4.75, 4.00, 5.25, 5.50, 5.75, 5.00, 6.25, 6.50, 6.75, 6.00,
            7.25, 7.5, 7.75, 7.00, 8.25, 8.50, 8.75, 8.00, 9.25, 9.50, 9.75, 9.00,
        ];

        return [
            'date' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'project_id' => Project::factory(),
            'type_id' => Type::factory(),
            'time' => $timeValues[array_rand($timeValues)],
            'owner_id' => User::factory(),
            'comment' => $this->faker->text(),
        ];
    }
}
