<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table = 'tasks';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['title', 'status', 'task_date', 'created_at'];
    protected $useTimestamps = false;

    /** Return only records scheduled for the supplied calendar date. */
    public function forDate(string $date): array
    {
        return $this->where('task_date', $date)->orderBy('id', 'ASC')->findAll();
    }

    /** Return every task, with a stable order within each date. */
    public function allByDate(): array
    {
        return $this->orderBy('task_date', 'ASC')->orderBy('id', 'ASC')->findAll();
    }
}
