<?php
$menu = require __DIR__ . "/../../config/menu.php";
$current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>

<!DOCTYPE html>
<html lang="de" class="bg-white dark:bg-gray-950 scheme-light dark:scheme-dark">
<head>
    <title><?= htmlspecialchars($title ?? 'Dock’n’Roll') ?></title>
    <link rel="stylesheet" href="/css/style.css">

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
<div class="wrapper">
    <aside class="sidebar">
        <h2>Dock’n’Roll</h2>
        <nav>
            <?php foreach ($menu as $item): ?>
                <a href="<?= $item['path'] ?>"
                   class="<?= $current_path == $item['path'] ? 'active' : '' ?>"><?= htmlspecialchars($item['label']) ?></a>
            <?php endforeach; ?>
        </nav>
    </aside>
    <main class="content">
        <?php /** @var string $view_file */
        include $view_file; ?>
    </main>
</div>
</body>
</html>
