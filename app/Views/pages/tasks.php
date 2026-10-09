<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>Task List</h1>
<p>This page shows all tasks, ordered from the earliest date to the latest.</p>

<section class="panel" aria-labelledby="all-tasks-title">
    <h2 id="all-tasks-title">All Tasks</h2>
    <p>Total tasks: <strong><?= count($tasks) ?></strong></p>

    <?= view('partials/task_table', [
        'tasks' => $tasks,
        'activePage' => $activePage,
        'tableCaption' => 'All tasks ordered by date',
        'emptyTitle' => 'No tasks found',
        'emptyMessage' => 'There are no tasks in the database yet.',
    ]) ?>

    <p class="table-note">Past, current, and upcoming tasks are included.</p>
    <a class="button" href="<?= site_url('/') ?>">Back to Welcome</a>
</section>

<?= $this->endSection() ?>