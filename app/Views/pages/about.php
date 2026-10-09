<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>About</h1>

<section class="panel">
    <h2>About the System</h2>
    <p>This is a simple task management system made for IT0049. It shows today's tasks, a list of all tasks, and a demo user's profile.</p>
    <p>The sample tasks include school assignments, exercise, chores, and watching movies.</p>
</section>

<section class="panel">
    <h2 id="developer-title"><?= esc($developer->name) ?></h2>
    <p>Developer</p>
    <dl class="details">
        <div><dt>Course</dt><dd><?= esc($developer->course) ?></dd></div>
        <div><dt>Activity</dt><dd><?= esc($developer->assessment) ?></dd></div>
        <?php if ($developer->section !== ''): ?>
            <div><dt>Section</dt><dd><?= esc($developer->section) ?></dd></div>
        <?php endif ?>
        <?php if ($developer->professor !== ''): ?>
            <div><dt>Professor</dt><dd><?= esc($developer->professor) ?></dd></div>
        <?php endif ?>
        <div><dt>Tools used</dt><dd>CodeIgniter 4, PHP, MySQL, HTML, and CSS</dd></div>
    </dl>
</section>

<?= $this->endSection() ?>