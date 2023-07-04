<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $config = [
            'title' => ["Schedule"],
            'numOfClassPeriodsPerDay' => [12],
            'timeOfEachClassPeriod' => [
                1 => [
                    'start' => [7, 0],
                    'end' => [7, 45]
                ],
                2 => [
                    'start' => [7, 50],
                    'end' => [8, 35]
                ],
                3 => [
                    'start' => [8, 40],
                    'end' => [9, 25]
                ],
                4 => [
                    'start' => [9, 35],
                    'end' => [10, 20]
                ],
                5 => [
                    'start' => [10, 25],
                    'end' => [11, 10]
                ],
                6 => [
                    'start' => [11, 15],
                    'end' => [12, 0]
                ],
                7 => [
                    'start' => [12, 30],
                    'end' => [13, 15]
                ],
                8 => [
                    'start' => [13, 20],
                    'end' => [14, 5]
                ],
                9 => [
                    'start' => [14, 10],
                    'end' => [14, 55]
                ],
                10 => [
                    'start' => [15, 5],
                    'end' => [15, 50]
                ],
                11 => [
                    'start' => [15, 55],
                    'end' => [16, 40]
                ],
                12 => [
                    'start' => [16, 45],
                    'end' => [17, 30]
                ]
            ]
        ];

        foreach ($config as $name => $content) {
            $new = new \App\Models\Config;
            $new->name = $name;
            $new->content = json_encode($content);
            $new->save();
        }
    }
}
