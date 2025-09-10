<?php
$menu = require __DIR__ . "/../../config/menu.php";
$current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>

<!DOCTYPE html>
<html lang="de">
  <head>
    <title><?= htmlspecialchars($title ?? 'Dock’n’Roll') ?></title>
    <!--<link rel="stylesheet" href="css/style.css" />-->
    <link rel="stylesheet" href="css/out.css" />
    <link rel="stylesheet" href="/assets/fontawesome/css/all.min.css" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  </head>
  <body class="bg-gray-950 !text-white scheme-dark">
    <div class="wrapper flex h-screen overflow-hidden">
      <aside
        class="sidebar static top-0 left-0 z-9999 flex h-screen w-[290px] translate-x-0 flex-col overflow-y-hidden border-r border-gray-800 bg-black px-5"
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
                  class="group <?= $current_path == $item['path'] ? 'menu-item-active' : 'menu-item-inactive' ?> menu-item"
                >
                  <span class="menu-item-text inline">
                    <?= htmlspecialchars($item['label']) ?>
                  </span>
                </a>
              </li>

              <?php endforeach; ?>
            </ul>
          </nav>
        </div>
      </aside>
      <main
        class="content relative flex flex-1 flex-col overflow-x-hidden overflow-y-auto"
      >
        <header
          class="sticky top-0 z-99999 flex w-full border-b border-gray-800 bg-gray-900"
        >
          <div class="flex grow flex-row items-center justify-between px-6">
            <div
              class="flex w-full items-center justify-normal gap-2 border-gray-800 px-3 py-4"
            >
              Hello
            </div>
            <div class="flex w-full items-center justify-end gap-4 px-5 py-4">
              <!-- NOTIFICATIONS -->
              <div class="relative">
                <button
                  class="relative flex h-11 w-11 items-center justify-center rounded-full border border-gray-400 bg-gray-900 text-gray-400 transition-colors hover:bg-gray-800 hover:text-white"
                >
                  <span
                    class="absolute top-0.5 right-0 z-1 flex h-2 w-2 rounded-full bg-orange-400"
                  >
                    <span
                      class="absolute -z-1 inline-flex h-full w-full animate-ping rounded-full bg-orange-400 opacity-75"
                    >
                    </span>
                  </span>
                  <i class="fa-regular fa-bell fa-lg"></i>
                </button>
              </div>

              <!-- USER AREA -->
              <div class="relative">
                <a class="flex items-center text-gray-400">
                  <div
                    class="mr-3 h-11 w-11 overflow-hidden rounded-full"
                  ></div>
                  <span class="mr-1 block font-medium"> USER NAME </span>
                </a>
              </div>
            </div>
          </div>
        </header>
        <?php /** @var string $view_file */ include $view_file; ?>
      </main>
    </div>
  </body>
</html>
