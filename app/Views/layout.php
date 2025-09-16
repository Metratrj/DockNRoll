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
    <body class="relative min-h-screen bg-gray-950 !text-white antialiased scheme-dark">
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
                            <?php foreach ($menu as $item) { $activeClass = $current_path === $item["path"] ? "menu-item-active" : "menu-item-inactive";
                             echo sprintf('
                            <li>
                                <a href="%s" class="group %s menu-item"
                                    ><span class="menu-item-text inline">%s</span></a
                                >
                            </li>
                            ', $item["path"], $activeClass, htmlspecialchars($item["label"])); } ?>
                        </ul>
                    </nav>
                </div>
            </aside>

            <div class="content relative flex flex-1 flex-col overflow-x-hidden overflow-y-auto">
                <!-- Backdrop -->
                <div class="absolute inset-[25%] block rounded-full bg-white/15 blur-3xl"></div>

                <header class="sticky top-0 z-99999 flex w-full border-b border-gray-800 bg-gray-900">
                    <div class="flex grow flex-row items-center justify-between px-6">
                        <div class="flex w-full items-center justify-normal gap-2 border-gray-800 px-3 py-2">
                            <div class="block">
                                <form>
                                    <div class="relative">
                                        <span class="absolute top-1/2 left-4 -translate-y-1/2">
                                            <svg
                                                class="fill-gray-500 dark:fill-gray-400"
                                                width="20"
                                                height="20"
                                                viewBox="0 0 20 20"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    fill-rule="evenodd"
                                                    clip-rule="evenodd"
                                                    d="M3.04175 9.37363C3.04175 5.87693 5.87711 3.04199 9.37508 3.04199C12.8731 3.04199 15.7084 5.87693 15.7084 9.37363C15.7084 12.8703 12.8731 15.7053 9.37508 15.7053C5.87711 15.7053 3.04175 12.8703 3.04175 9.37363ZM9.37508 1.54199C5.04902 1.54199 1.54175 5.04817 1.54175 9.37363C1.54175 13.6991 5.04902 17.2053 9.37508 17.2053C11.2674 17.2053 13.003 16.5344 14.357 15.4176L17.177 18.238C17.4699 18.5309 17.9448 18.5309 18.2377 18.238C18.5306 17.9451 18.5306 17.4703 18.2377 17.1774L15.418 14.3573C16.5365 13.0033 17.2084 11.2669 17.2084 9.37363C17.2084 5.04817 13.7011 1.54199 9.37508 1.54199Z"
                                                    fill="" />
                                            </svg>
                                        </span>
                                        <input
                                            type="text"
                                            placeholder="Search or type command..."
                                            id="search-command"
                                            class="dark:bg-dark-900 shadow-theme-xs h-11 w-full rounded-lg border border-gray-800 bg-transparent py-2.5 pr-14 pl-12 text-sm text-white/90 placeholder:text-white/30 focus:border-[#252dae] focus:ring-3 focus:ring-[#465fff]/10 focus:outline-hidden xl:w-[430px] dark:bg-white/[0.03]" />
                                        <button
                                            id="search-button"
                                            class="absolute top-1/2 right-2.5 inline-flex -translate-y-1/2 items-center gap-0.5 rounded-lg border border-gray-800 bg-white/[0.03] px-[7px] py-[4.5px] text-xs -tracking-[0.2px] text-gray-400">
                                            <span> ⌘ </span> <span> K </span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="flex w-full items-center justify-end gap-4 px-5 py-2">
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
                    <div class="max-w-(1500px) mx-auto overflow-x-auto p-6">
                        <?php /** @var string $view_file */ include_once $view_file; ?>
                    </div>
                </main>
            </div>
        </div>

        <!-- Search Modal -->
        <div
            id="search-modal"
            class="fixed inset-0 z-[10000] flex items-start justify-center bg-black/50 pt-20 backdrop-blur-sm"
            style="display: none">
            <div class="w-full max-w-2xl">
                <div
                    id="command-suggestions"
                    class="mb-2 w-full overflow-hidden rounded-lg border border-gray-700 bg-gray-900 shadow-lg">
                    <!-- Command suggestions will be injected here -->
                </div>
                <div id="search-results" class="w-full rounded-lg border border-gray-700 bg-gray-900 shadow-lg">
                    <!-- Results will be injected here -->
                </div>
            </div>
        </div>

        <script src="/js/search.js"></script>
    </body>
</html>
