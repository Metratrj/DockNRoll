<?php
/*
 * Copyright (c) 2025.
 */

/** @var \OpenAPI\Client\Model\ImageInspect $image */

use DateTime;

?>
<div class="flex flex-col gap-8">
    <div>
        <h3 class="text-3xl font-bold text-white/90">Image Details</h3>
        <p class="mt-2 text-base text-[#9dabb9]">Manage <?= $image->getId() ?></p>
    </div>

    <section>
        <h3 class="mb-4 text-xl font-bold tracking-[-0.015em]">Image Details</h3>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="space-y-2">
                <p class="text-sm text-white/60">ID</p>
                <p class="text-base text-white/90"><?= substr($image->getId(), 7, 12) ?></p>
            </div>
            <div class="space-y-2">
                <p class="text-sm text-white/60">Tags</p>
                <p class="text-base text-white/90">
                    <?= is_array($image->getRepoTags()) ? implode(', ', $image->getRepoTags()) : 'N/A' ?>
                </p>
            </div>
            <div class="space-y-2">
                <p class="text-sm text-white/60">Size</p>
                <p class="text-base text-white/90"><?= round($image->getSize() / (1024 * 1024), 2) ?> MB</p>
            </div>
            <div class="space-y-2">
                <p class="text-sm text-white/60">Created</p>
                <p class="text-base text-white/90">
                    <?php try { echo (new DateTime($image->getCreated()))->format('Y-m-d H:i:s'); } catch (Exception $e)
                    { echo 'N/A'; } ?>
                </p>
            </div>
            <div class="space-y-2">
                <p class="text-sm text-white/60">Architecture</p>
                <p class="text-base text-white/90"><?= $image->getArchitecture() ?? 'N/A' ?></p>
            </div>
            <div class="space-y-2">
                <p class="text-sm text-white/60">Operating System</p>
                <p class="text-base text-white/90"><?= $image->getOs() ?? 'N/A' ?></p>
            </div>
        </div>
    </section>

    <section>
        <h3 class="mb-4 text-xl font-bold tracking-[-0.015em]">Container Configuration</h3>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="space-y-2">
                <p class="text-sm text-white/60">Command</p>
                <p class="text-base text-white/90">
                    <?= is_array($image->getConfig()->getCmd()) ? implode(' ', $image->getConfig()->getCmd()) : 'N/A' ?>
                </p>
            </div>
            <div class="space-y-2">
                <p class="text-sm text-white/60">Entrypoint</p>
                <p class="text-base text-white/90">
                    <?= is_array($image->getConfig()->getEntrypoint()) ? implode(' ',
                    $image->getConfig()->getEntrypoint()) : 'N/A' ?>
                </p>
            </div>
            <div class="space-y-2">
                <p class="text-sm text-white/60">Working Dir</p>
                <p class="text-base text-white/90"><?= $image->getConfig()->getWorkingDir() ?? 'N/A' ?></p>
            </div>
            <div class="space-y-2">
                <p class="text-sm text-white/60">User</p>
                <p class="text-base text-white/90"><?= $image->getConfig()->getUser() ?? 'N/A' ?></p>
            </div>
        </div>
    </section>

    <section>
        <h3 class="mb-4 text-xl font-bold tracking-[-0.015em]">Environment Variables</h3>
        <div class="space-y-2">
            <?php if (is_array($image->getConfig()->getEnv()) && !empty($image->getConfig()->getEnv())): ?> <?php
            foreach ($image->getConfig()->getEnv() as $env): ?>
            <p class="text-base text-white/90"><?= $env ?></p>
            <?php endforeach; ?> <?php else: ?>
            <p class="text-base text-white/90">No environment variables</p>
            <?php endif; ?>
        </div>
    </section>

    <section>
        <h3 class="mb-4 text-xl font-bold tracking-[-0.015em]">Volumes</h3>
        <div class="space-y-2">
            <?php if (is_array($image->getConfig()->getVolumes()) && !empty($image->getConfig()->getVolumes())): ?>
            <?php foreach ($image->getConfig()->getVolumes() as $volume => $config): ?>
            <p class="text-base text-white/90"><?= $volume ?></p>
            <?php endforeach; ?> <?php else: ?>
            <p class="text-base text-white/90">No volumes</p>
            <?php endif; ?>
        </div>
    </section>
</div>
