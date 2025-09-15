<?php
/*
 * Copyright (c) 2025.
 */
/** @var \OpenAPI\Client\Model\ImageSummary[] $images */


?>

<!-- Breadcrumb -->
<div>
  <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h2 class="text-xl font-semibold text-white/90">Images</h2>
    <nav>
      <ol class="flex items-center gap-1.5">
        <li>
          <a href="/" class="inline-flex items-center gap-1.5 text-sm text-gray-400"> Home </a>
        </li>
        <li class="text-sm text-white/90">Images</li>
      </ol>
    </nav>
  </div>
</div>

<div class="space-y-5">
  <!--CARD-->
  <div class="rounded-2xl border border-gray-800 bg-white/[0.03]">
    <?php include __DIR__ . '/../components/table_search_header.php'; ?>
    <!--TABLE-->
    <table class="min-w-full divide-y divide-gray-800">
      <thead class="bg-white/[0.03]">
        <tr>
          <!-- Checkbox -->
          <th class="py-3 ps-6 text-start" scope="col">
            <label class="flex">
              <input
                type="checkbox"
                class="shrink-0 rounded-sm border-gray-800 bg-white/[0.03] text-[#9cb9ff] checked:border-[#9cb9ff] checked:bg-[#9cb9ff] focus:ring-[#9cb9ff] focus:ring-offset-gray-800 disabled:pointer-events-none disabled:opacity-50" />
              <span class="absolute h-0 w-0 overflow-hidden p-0">Checkbox</span>
            </label>
          </th>
          <!-- First -->
          <th class="py-3 pe-6 text-start" scope="col">
            <div class="flex items-center gap-x-2">
              <span class="text-xs font-semibold text-gray-400 uppercase"> Name </span>
            </div>
          </th>
          <!-- Column -->
          <th class="px-6 py-3 text-start" scope="col">
            <div class="flex items-center gap-x-2">
              <span class="text-xs font-semibold text-gray-400 uppercase"> Size </span>
            </div>
          </th>
          <!--Last -->
          <th class="px-6 py-3 text-end" scope="col">
            <div class="flex items-center justify-end gap-x-2">
              <span class="text-xs font-semibold text-gray-400 uppercase"> Action </span>
            </div>
          </th>
        </tr>
      </thead>

      <tbody class="divide-y divide-gray-800">
        <tr>
          <!-- Checkbox -->
          <td class="size-px whitespace-nowrap">
            <!-- px-6 py-3 text-end-->
            <div class="py-2 ps-6">
              <label class="flex">
                <input
                  type="checkbox"
                  class="shrink-0 rounded-sm border-gray-800 bg-white/[0.03] text-[#9cb9ff] checked:border-[#9cb9ff] checked:bg-[#9cb9ff] focus:ring-[#9cb9ff] focus:ring-offset-gray-800 disabled:pointer-events-none disabled:opacity-50" />
                <span class="absolute h-0 w-0 overflow-hidden p-0">Checkbox</span>
              </label>
            </div>
          </td>
          <td class="size-px whitespace-nowrap">
            <div class="py-2 pe-6">T</div>
          </td>
          <td class="size-px whitespace-nowrap">
            <div class="px-6 py-2">T</div>
          </td>
          <td class="size-px whitespace-nowrap">
            <div class="flex justify-end px-6 py-2">
              <div
                class="group inline-flex items-center divide-x divide-gray-800 rounded-lg border border-gray-800 shadow-2xs transition-all">
                <div class="inline-block">
                  <!--
                           items-center gap-x-2 text-sm font-semibold rounded-s-md bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                          -->
                  <a
                    class="inline-flex items-center justify-center gap-x-2 rounded-s-md px-2 py-1.5 text-sm font-semibold text-gray-400 shadow-2xs"
                    >Run</a
                  >
                </div>
                <div class="dropdown relative inline-flex [--placement:bottom-right]">
                  <button
                    id="table-dropdown-1"
                    class="dropdown-toggle inline-flex items-center justify-center gap-x-2 rounded-e-md px-2 py-1.5 text-sm font-semibold text-gray-400 shadow-2xs">
                    <svg
                      class="size-4"
                      xmlns="http://www.w3.org/2000/svg"
                      width="16"
                      height="16"
                      fill="currentColor"
                      viewBox="0 0 16 16">
                      <path
                        d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <!--<div class="border-t border-gray-800 p-5">
      <table id="imageTable">
        <thead>
          <tr>
            <th data-sort="RepoTags">Name</th>
            <th data-sort="Size">Size</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
      <div id="pagination"></div>
      <script src="/js/images.js"></script>-->
    <!--  <div class="overflow-hidden rounded-xl border border-gray-800 bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto">
          <table class="tabl min-w-full">
            <thead>
              <tr>
                <th>
                  <div>
                    <p>Name</p>
                  </div>
                </th>
                <th>
                  <div>
                    <p>Aktionen</p>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
              <?php /*foreach ($images as $img): */?>
              <tr>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <div class="flex items-center gap-3">
                      <div>
                        <a href="/images/<?php /*= $img->getId() */?>">
                          <span class="block text-sm font-medium text-[#465fff] underline">
                            <?php /*= implode(',', $img->getRepoTags()); */?>
                          </span>
                        </a>
                      </div>
                    </div>
                  </div>
                </td>
                <td>
                  <form method="POST" action="/containers/<?php /*= $img->getId() */?>/run" style="display: inline">
                    <button type="submit">Run</button>
                  </form>
                  <form
                    method="POST"
                    action="/containers/<?php /*= $img->getId() */?>/delete"
                    style="display: inline"
                    onsubmit="return confirm('Image wirklich löschen?');">
                    <button type="submit">Remove</button>
                  </form>
                </td>
              </tr>
              <?php /*endforeach; */?>
            </tbody>
          </table>
        </div>
      </div>
    </div>-->
    <!--ENDTABLE-->
    <?php
    $resultCount = count($images);
    include __DIR__ . '/../components/table_pagination_footer.php';
    ?>
  </div>
</div>
