<?php
/*
 * Copyright (c) 2025.
 */

/** @var string $filterButtons */
?>
<!--HEADER-->
<div class="flex items-center justify-between gap-3 border-b border-gray-800 px-6 py-4">
  <!-- INPUT -->
  <div class="col-span-1">
    <label for="search" class="absolute h-0 w-0 overflow-hidden p-0">Search</label>
    <div class="relative">
      <input
        type="text"
        placeholder="Search"
        id="search"
        name="search"
        class="shadow-theme-xs block h-11 w-full rounded-lg border border-gray-800 bg-white/[0.03] px-3 py-2 ps-11 text-sm text-white/90 placeholder:text-gray-400 focus:border-[#9cb9ff] focus:ring-3 focus:ring-[#465fff]/10 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50 xl:w-[350px]" />
      <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-4">
        <svg
          class="size-4 text-gray-400 dark:text-neutral-500"
          xmlns="http://www.w3.org/2000/svg"
          width="16"
          height="16"
          fill="currentColor"
          viewBox="0 0 16 16">
          <path
            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
        </svg>
      </div>
    </div>
  </div>
  <!-- END INPUT -->

  <!-- FILTER BUTTONS -->
  <div class="col-span-2 grow">
    <div class="flex justify-end gap-x-2">
        <?php if (isset($filterButtons)): ?>
            <?= $filterButtons ?>
        <?php endif; ?>
    </div>
  </div>
</div>
<!--END HEADER-->
