<?php
/*
 * Copyright (c) 2025.
 */

/** @var int $resultCount */
?>
<!--FOOTER-->
<div class="flex items-center justify-between gap-3 border-t border-gray-800 px-6 py-4">
  <div>
    <p class="text-sm text-gray-400">
      <span class="font-semibold text-gray-200"> <?= $resultCount ?? 0 ?> </span>
      Results
    </p>
  </div>
  <div>
    <div class="inline-flex gap-x-2">
      <button
        class="inline-flex items-center gap-x-2 rounded-lg border border-gray-800 bg-gray-900 px-3 py-2 text-sm font-medium text-white shadow-2xs hover:bg-neutral-700 focus:bg-neutral-700 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50">
        <svg
          class="size-4 shrink-0"
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round">
          <path d="m15 18-6-6 6-6"></path>
        </svg>
        Prev
      </button>
      <button
        class="inline-flex items-center gap-x-2 rounded-lg border border-gray-800 bg-gray-900 px-3 py-2 text-sm font-medium text-white shadow-2xs hover:bg-neutral-700 focus:bg-neutral-700 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50">
        <svg
          class="size-4 shrink-0"
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round">
          <path d="m9 18 6-6-6-6"></path>
        </svg>
        Next
      </button>
    </div>
  </div>
</div>
<!--ENDFOOTER-->
