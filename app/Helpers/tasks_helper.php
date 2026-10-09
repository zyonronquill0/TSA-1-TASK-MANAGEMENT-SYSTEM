<?php

use CodeIgniter\I18n\Time;

// Labels and CSS classes for the statuses stored in the database.
function task_status(string $status): array
{
    return match ($status) {
        'completed'   => ['label' => 'Completed', 'class' => 'completed'],
        'in_progress' => ['label' => 'In progress', 'class' => 'in-progress'],
        'pending'     => ['label' => 'Pending', 'class' => 'pending'],
        default       => ['label' => ucfirst(str_replace('_', ' ', $status)), 'class' => 'unknown'],
    };
}

function task_date_label(string $date, string $format = 'M j, Y'): string
{
    return Time::parse($date, config('App')->appTimezone)->format($format);
}