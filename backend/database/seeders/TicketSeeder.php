<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $agents = DB::table('users')
            ->pluck('id')
            ->toArray();

        $priorities = ['low', 'medium', 'high'];
        $statuses = ['open', 'in_progress', 'closed'];

        $inserts = [];
        $faker = \Faker\Factory::create();

        foreach (range(1, 10) as $index) {
            $inserts[] = [
                'title' => $faker->sentence(6),
                'description' => $faker->paragraphs(3, true),
                'priority' => $faker->randomElement($priorities),
                'status' => $faker->randomElement($statuses),
                'assignee_id' => $faker->randomElement($agents),
                'created_at' => $faker->dateTimeBetween('-30 days')->format('Y-m-d H:i:s'),
                'updated_at' => $faker->dateTimeBetween('-7 days')->format('Y-m-d H:i:s'),
            ];
        }

        DB::table('tickets')->insert($inserts);
    }
}
