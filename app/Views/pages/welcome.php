<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>Welcome!</h1>
<p>These are your tasks for today.</p>
<p class="date-label">
    <strong>Today's date:</strong>
    <time datetime="<?= esc($today->toDateString()) ?>"><?= esc($today->format('D, M j, Y')) ?></time>
    <span class="muted">(Philippine time)</span>
</p>

<section class="panel" aria-labelledby="today-title">
    <h2 id="today-title">Today's Tasks</h2>
    <p class="task-summary">
        Total: <strong><?= count($tasks) ?></strong>
        <span>In progress: <strong><?= $inProgress ?></strong></span>
        <span>Completed: <strong><?= $completed ?></strong></span>
    </p>

    <?= view('partials/task_table', [
        'tasks' => $tasks,
        'activePage' => $activePage,
        'tableCaption' => 'Tasks scheduled for today',
        'emptyTitle' => 'No tasks for today',
        'emptyMessage' => 'There are no tasks scheduled for this date.',
    ]) ?>

    <p class="table-note">Only tasks with today's date are shown here.</p>
    <a class="button" href="<?= site_url('tasks') ?>">View All Tasks</a>
</section>

<?= $this->endSection() ?>