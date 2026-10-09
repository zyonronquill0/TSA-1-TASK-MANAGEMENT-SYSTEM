<?php

namespace App\Controllers;

use App\Models\TaskModel;
use CodeIgniter\I18n\Time;

class Home extends BaseController
{
    public function index(): string
    {
        $today = Time::today(config('App')->appTimezone);
        $tasks = (new TaskModel())->forDate($today->toDateString());

        return view('pages/welcome', [
            'pageTitle'  => 'Welcome',
            'activePage' => 'welcome',
            'today'      => $today,
            'tasks'      => $tasks,
            'completed' => count(array_filter($tasks, static fn (array $task): bool => $task['status'] === 'completed')),
            'inProgress' => count(array_filter($tasks, static fn (array $task): bool => $task['status'] === 'in_progress')),
        ]);
    }
}
