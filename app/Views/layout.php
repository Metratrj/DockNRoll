<?php
$menu = require __DIR__ . "/../../config/menu.php";
$current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>

<!DOCTYPE html>
<html lang="de">
  <head>
    <title><?= htmlspecialchars($title ?? 'Dock’n’Roll') ?></title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/out.css" />

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  </head>
  <body class="bg-gray-950 text-white scheme-dark">
    <div class="wrapper flex h-screen overflow-hidden">
      <aside
        class="sidebar fixed static top-0 left-0 z-9999 flex h-screen w-[290px] translate-x-0 flex-col overflow-y-hidden border-r border-gray-800 bg-black px-5"
      >
        <div class="sidebar-header flex items-center pb-7">
          <a href="/">
            <h2 class="block">Dock’n’Roll</h2>
          </a>
        </div>
        <div
          class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear"
        >
          <nav>
            <div>
              <h3 class="mb-4 text-xs leading-[20px] text-gray-400 uppercase">
                <span>MENU</span>
              </h3>
            </div>
            <ul class="mb-6 flex flex-col gap-4">
              <?php foreach ($menu as $item): ?>
              <li>
                <a
                  href="<?= $item['path'] ?>"
                  class="menu-item group <?= $current_path == $item['path'] ? 'menu-item-active' : 'menu-item-inactive' ?>"
                >
                  <span class="menu-item-text">
                    <?= htmlspecialchars($item['label']) ?>
                  </span>
                </a>
              </li>

              <?php endforeach; ?>
            </ul>
          </nav>
        </div>
      </aside>
      <main class="content">
        <?php /** @var string $view_file */ include $view_file; ?>
      </main>
    </div>
  </body>
</html>
