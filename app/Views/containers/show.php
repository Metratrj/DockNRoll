<?php
/*
 * Copyright (c) 2025.
 */

/** @var ContainerInspectResponse $container */

use OpenAPI\Client\Model\ContainerInspectResponse;

?>
<div class="flex flex-col gap-8">
    <div>
        <h3 class="text-3xl font-bold text-white/90">Container Details</h3>
        <p class="mt-2 text-base text-[#9dabb9]">Manage <?= ltrim($container->getName(), '/') ?></p>
    </div>

    <section>
        <h3 class="mb-4 text-xl font-bold tracking-[-0.015em]">Container Actions</h3>
        <div class="flex flex-wrap gap-4">
            <a
                href="/containers/<?= $container->getId() ?>/start"
                class="flex h-6 items-center justify-center gap-2 rounded-md bg-[#0f66bd] px-4 text-sm font-medium text-white transition-colors hover:bg-blue-600">
                <span>Start</span>
            </a>
            <a
                href="/containers/<?= $container->getId() ?>/stop"
                class="flex h-6 items-center justify-center gap-2 rounded-md bg-[#283039] px-4 text-sm font-medium text-white transition-colors hover:bg-[#3b4754]">
                <span>Stop</span>
            </a>
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
        <h3 class="mb-4 text-xl font-bold tracking-[-0.015em]">Container Details</h3>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="space-y-2">
                <p class="text-sm text-white/60">Name</p>
                <p class="text-base text-white/90"><?= ltrim($container->getName(), '/') ?></p>
            </div>
            <div class="space-y-2">
                <p class="text-sm text-white/60">ID</p>
                <p class="text-base text-white/90"><?= substr($container->getId(), 0, 12) ?></p>
            </div>
            <div class="space-y-2">
                <p class="text-sm text-white/60">Status</p>
                <p class="text-base text-white/90"><?= $container->getState()->getStatus() ?></p>
            </div>
            <div class="space-y-2">
                <p class="text-sm text-white/60">Image</p>
                <p class="text-base text-white/90"><?= $container->getConfig()->getImage() ?></p>
            </div>
            <div class="space-y-2">
                <p class="text-sm text-white/60">Command</p>
                <p class="text-base text-white/90"><?= implode(' ', $container->getConfig()->getCmd()) ?></p>
            </div>
            <div class="space-y-2">
                <p class="text-sm text-white/60">Created</p>
                <p class="text-base text-white/90"><?= $container->getCreated() ?></p>
            </div>
        </div>
    </section>

    <section>
        <h3 class="mb-4 text-xl font-bold tracking-[-0.015em]">Network Settings</h3>
        <div class="space-y-4">
            <?php foreach ($container->getNetworkSettings()->getPorts() as $port => $bindings) { ?>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <p class="text-sm text-white/60">Port</p>
                    <p class="text-base text-white/90"><?= $port ?></p>
                </div>
                <div class="space-y-2">
                    <p class="text-sm text-white/60">Binding</p>
                    <?php if (!empty($bindings)) { ?> <?php foreach ($bindings as $binding) { ?>
                    <p class="text-base text-white/90"><?= $binding->getHostIp() ?>:<?= $binding->getHostPort() ?></p>
                    <?php } ?> <?php } else { ?>
                    <p class="text-base text-white/90">Not mapped</p>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </section>

    <section>
        <h3 class="mb-4 text-xl font-bold tracking-[-0.015em]">Environment Variables</h3>
        <div class="space-y-2">
            <?php foreach ($container->getConfig()->getEnv() as $env) { ?>
            <p class="text-base text-white/90"><?= $env ?></p>
            <?php } ?>
        </div>
    </section>

    <section>
        <h3 class="mb-4 text-xl font-bold tracking-[-0.015em]">Volumes</h3>
        <div class="space-y-2">
            <?php if ($container->getMounts()) { ?> <?php foreach ($container->getMounts() as $mount) { ?>
            <p class="text-base text-white/90"><?= $mount->getSource() ?>:<?= $mount->getDestination() ?></p>
            <?php } ?> <?php } else { ?>
            <p class="text-base text-white/90">No volumes</p>
            <?php } ?>
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
        const containerId = "<?= $container->getId() ?>";
        const eventSource = new EventSource(`/containers/${containerId}/stats`);

        eventSource.onmessage = function (event) {
            const data = JSON.parse(event.data);
            const cpuStats = data.cpu_stats;
            const memoryStats = data.memory_stats;

            const cpuUsage = cpuStats.cpu_usage.total_usage;
            const systemCpuUsage = cpuStats.system_cpu_usage;
            const cpuPercent = (cpuUsage / systemCpuUsage) * 100;

            const memoryUsage = memoryStats.usage / (1024 * 1024); // in MB
            const memoryLimit = memoryStats.limit / (1024 * 1024); // in MB
            const memoryPercent = (memoryUsage / memoryLimit) * 100;

            statsOutput.innerHTML = `CPU: ${cpuPercent.toFixed(2)}%\nMemory: ${memoryUsage.toFixed(2)} MB / ${memoryLimit.toFixed(2)} MB (${memoryPercent.toFixed(2)}%)`;
        };

        eventSource.onerror = function (err) {
            console.error("EventSource failed:", err);
            eventSource.close();
        };
    });
</script>
