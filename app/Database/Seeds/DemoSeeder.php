<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $today = Time::today(config('App')->appTimezone);
        $createdAt = Time::now(config('App')->appTimezone)->toDateTimeString();

        // Seed only empty tables so rerunning setup never duplicates or replaces data.
        if ($this->db->table('tasks')->countAllResults() === 0) {
            $samples = [
                ['Clean my room', 'completed', -1],
                ['Read class notes', 'completed', -1],
                ['Finish my web systems assignment', 'in_progress', 0],
                ['Go for a jog', 'pending', 0],
                ['Watch Avengers: Endgame', 'pending', 0],
                ['Do my math homework', 'pending', 0],
                ['Buy groceries', 'pending', 1],
                ['Watch Spider-Man: No Way Home', 'pending', 1],
            ];
            $tasks = [];

            foreach ($samples as [$title, $status, $offset]) {
                $tasks[] = [
                    'title'      => $title,
                    'status'     => $status,
                    'task_date'  => $today->addDays($offset)->toDateString(),
                    'created_at' => $createdAt,
                ];
            }

            $this->db->table('tasks')->insertBatch($tasks);
        }

        if ($this->db->table('users')->countAllResults() === 0) {
            $this->db->table('users')->insert([
                'username'   => 'zyon_dev',
                'full_name'  => 'Lord Zyon Ronquillo',
                'email'      => 'zyon.ronquillo@example.com',
                'created_at' => $createdAt,
            ]);
        }
    }
}
