<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>Profile</h1>
<p>Information about the demo user.</p>

<section class="panel">
    <?php if ($user === null): ?>
        <h2>No profile yet</h2>
        <p>The demo user has not been added.</p>
    <?php else: ?>
        <h2><?= esc($user['full_name']) ?></h2>
        <dl class="details">
            <div><dt>User ID</dt><dd><?= esc($user['id']) ?></dd></div>
            <div><dt>Username</dt><dd><?= esc($user['username']) ?></dd></div>
            <div><dt>Full name</dt><dd><?= esc($user['full_name']) ?></dd></div>
            <div><dt>Email</dt><dd><?= esc($user['email']) ?></dd></div>
            <div>
                <dt>Date created</dt>
                <dd><time datetime="<?= esc(str_replace(' ', 'T', $user['created_at']), 'attr') ?>"><?= esc(task_date_label($user['created_at'], 'F j, Y - g:i A')) ?></time></dd>
            </div>
        </dl>
    <?php endif ?>
</section>

<?= $this->endSection() ?>