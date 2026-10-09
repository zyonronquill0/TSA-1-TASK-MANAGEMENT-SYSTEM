<?php

namespace App\Controllers;

use App\Models\TaskModel;

class Tasks extends BaseController
{
    public function index(): string
    {
        return view('pages/tasks', [
            'pageTitle'  => 'Task List',
            'activePage' => 'tasks',
            'tasks'      => (new TaskModel())->allByDate(),
        ]);
    }
}
