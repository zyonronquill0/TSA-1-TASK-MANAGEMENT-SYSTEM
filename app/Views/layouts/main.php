<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A simple task management system for IT0049.">
    <title><?= esc($pageTitle) ?> - Tasks for Today</title>
    <link rel="icon" href="<?= base_url('assets/favicon.svg') ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body>
<a class="skip-link" href="#main-content">Skip to content</a>

<header class="site-header">
    <div class="container">
        <a class="site-title" href="<?= site_url('/') ?>">Tasks for Today Management System</a>
        <p>IT0049 - Technical Summative Assessment 1</p>
    </div>
</header>

<nav class="site-nav" aria-label="Main navigation">
    <div class="container nav-links">
        <?php foreach ([
            ['welcome', '/', 'Welcome'],
            ['tasks', 'tasks', 'Task List'],
            ['profile', 'profile', 'Profile'],
            ['about', 'about', 'About'],
        ] as [$key, $url, $label]): ?>
            <a href="<?= site_url($url) ?>"
               class="<?= $activePage === $key ? 'active' : '' ?>"
               <?= $activePage === $key ? 'aria-current="page"' : '' ?>>
                <?= esc($label) ?>
            </a>
        <?php endforeach ?>
    </div>
</nav>

<main id="main-content" class="container main-content" tabindex="-1">
    <?= $this->renderSection('content') ?>
</main>

<footer class="site-footer">
    <div class="container">Tasks for Today | Developed by Zyon Ronquillo</div>
</footer>
</body>
</html>