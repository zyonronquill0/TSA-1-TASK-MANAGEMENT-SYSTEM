<?php if ($tasks === []): ?>
    <div class="empty-state">
        <h3><?= esc($emptyTitle) ?></h3>
        <p><?= esc($emptyMessage) ?></p>
    </div>
<?php else: ?>
    <div class="table-scroll" tabindex="0" role="region" aria-label="<?= esc($tableCaption, 'attr') ?>">
        <table class="task-table">
            <caption class="sr-only"><?= esc($tableCaption) ?></caption>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Task</th>
                    <th scope="col">Status</th>
                    <th scope="col">Task Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <?php $status = task_status($task['status']); ?>
                    <tr>
                        <td class="task-id"><?= esc($task['id']) ?></td>
                        <td><?= esc($task['title']) ?></td>
                        <td><span class="status status-<?= esc($status['class']) ?>"><?= esc($status['label']) ?></span></td>
                        <td class="date-cell"><time datetime="<?= esc($task['task_date'], 'attr') ?>"><?= esc(task_date_label($task['task_date'])) ?></time></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php endif ?>