<?php
/*
 * Copyright (c) 2025.
 */

/** @var ContainerStatsResponse $stats */

use OpenAPI\Client\Model\ContainerStatsResponse;

?>
<div class="flex flex-col gap-8">
  <div>
    <h3 class="text-3xl font-bold text-white/90">Container Details</h3>
    <p class="mt-2 text-base text-[#9dabb9]">Manage <?= ltrim($stats->getName(), '/') ?></p>
  </div>

  <section>
    <h3 class="mb-4 text-xl font-bold tracking-[-0.015em]">Container Actions</h3>
    <div class="flex flex-wrap gap-4">
      <button
        class="flex h-6 items-center justify-center gap-2 rounded-md bg-[#0f66bd] px-4 text-sm font-medium text-white transition-colors hover:bg-blue-600">
        <span>Start</span>
      </button>
      <button
        class="flex h-6 items-center justify-center gap-2 rounded-md bg-[#283039] px-4 text-sm font-medium text-white transition-colors hover:bg-[#3b4754]">
        <span>Stop</span>
      </button>
      <button
        class="flex h-6 items-center justify-center gap-2 rounded-md bg-[#283039] px-4 text-sm font-medium text-white transition-colors hover:bg-[#3b4754]">
        <span>Restart</span>
      </button>
      <button
        class="flex h-6 items-center justify-center gap-2 rounded-md bg-red-800 px-4 text-sm font-medium text-white transition-colors hover:bg-red-700">
        <span>Delete</span>
      </button>
    </div>
  </section>
  <section>
    <h3 class="mb-4 text-xl font-bold tracking-[-0.015em]">Resource Usage</h3>
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
      <pre id="stats-output" class="text-white/80"></pre>
    </div>
  </section>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const statsOutput = document.getElementById("stats-output");
    const containerId = "<?= $stats->getId() ?>";
    const eventSource = new EventSource(`/containers/${containerId}/stats`);

    eventSource.onmessage = function (event) {
      statsOutput.textContent += event.data;
    };

    eventSource.onerror = function (err) {
      console.error("EventSource failed:", err);
      eventSource.close();
    };
  });
</script>

<div class="space-y-5">
  <div class="rounded-2xl border border-gray-800 bg-white/[0.03]">
    <div class="px-5 py-4"></div>

    <div class="border-t border-gray-800 p-5">
      <div class="overflow-hidden rounded-xl border border-gray-800 bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto"></div>
      </div>
    </div>
  </div>
</div>

<?php foreach ($stats as $stat) {
    echo '
<h2>' . $stat . '</h2>
';
}
