<?php
/*
 * Copyright (c) 2025.
 */

$menu = require __DIR__ . "/../../config/menu.php";
$current_path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
?>

<!DOCTYPE html>
<html lang="de" class="bg-gray-950 !text-white scheme-dark">
  <head>
    <title><?= htmlspecialchars($title ?? "Dock’n’Roll") ?></title>
    <!--suppress HtmlUnknownTarget -->
    <link rel="stylesheet" href="/css/out.css" />
    <link rel="preconnect" href="https://rsms.me/" />
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  </head>
  <body class="bg-gray-950 !text-white scheme-dark">
    <div class="wrapper flex h-screen overflow-hidden">
      <aside
        class="sidebar static top-0 left-0 z-9999 flex h-screen w-[290px] translate-x-0 flex-col overflow-y-hidden border-r border-gray-800 bg-black px-5">
        <div class="sidebar-header flex items-center pb-7">
          <a href="/">
            <h2 class="block">Dock’n’Roll</h2>
          </a>
        </div>
        <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
          <nav>
            <div>
              <h3 class="mb-4 text-xs leading-[20px] text-gray-400 uppercase">
                <span>MENU</span>
              </h3>
            </div>
            <ul class="mb-6 flex flex-col gap-4">
              <?php foreach ($menu as $item) { $activeClass = $current_path == $item["path"] ? "menu-item-active" :
              "menu-item-inactive"; echo sprintf( '
              <li>
                <a href="%s" class="group %s menu-item"><span class="menu-item-text inline">%s</span></a>
              </li>
              ', $item["path"], $activeClass, htmlspecialchars($item["label"]), ); } ?>
            </ul>
          </nav>
        </div>
      </aside>
      <div class="content relative flex flex-1 flex-col overflow-x-hidden overflow-y-auto">
        <header class="sticky top-0 z-99999 flex w-full border-b border-gray-800 bg-gray-900">
          <div class="flex grow flex-row items-center justify-between px-6">
            <div class="flex w-full items-center justify-normal gap-2 border-gray-800 px-3 py-4">Hello</div>
            <div class="flex w-full items-center justify-end gap-4 px-5 py-4">
              <!-- NOTIFICATIONS -->
              <div class="relative">
                <button
                  class="relative flex h-11 w-11 items-center justify-center rounded-full border border-gray-800 bg-gray-900 text-gray-400 transition-colors hover:bg-gray-800 hover:text-white">
                  <span class="absolute top-0.5 right-0 z-1 flex h-2 w-2 rounded-full bg-orange-400">
                    <span
                      class="absolute -z-1 inline-flex h-full w-full animate-ping rounded-full bg-orange-400 opacity-75">
                    </span>
                  </span>
                  <svg
                    class="size-6 fill-gray-400"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                  </svg>
                </button>
              </div>

              <!-- USER AREA -->
              <div class="relative">
                <a class="flex items-center text-gray-400">
                  <div class="mr-3 h-11 w-11 overflow-hidden rounded-full"></div>
                  <span class="mr-1 block font-medium"> USER NAME </span>
                </a>
              </div>
            </div>
          </div>
        </header>
        <main>
          <div class="max-w-(1500px) mx-auto p-6"><?php /** @var string $view_file */ include_once $view_file; ?></div>
        </main>
      </div>
    </div>
  </body>
</html>
