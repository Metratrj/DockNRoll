<?php
/** @var ContainerSummary[] $containers */

use OpenAPI\Client\Model\ContainerSummary;

?>
<!-- Breadcrumb -->
<div>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-xl font-semibold text-white/90">
            Containers
        </h2>
        <nav>
            <ol class="flex items-center gap-1.5">
                <li>
                    <a href="/" class="inline-flex items-center gap-1.5 text-sm text-gray-400">
                        Home
                    </a>
                </li>
                <li class="text-sm text-white/90">
                    Containers
                </li>
            </ol>
        </nav>

    </div>

</div>

<div class="space-y-5">
    <div class="rounded-2xl border border-gray-800 bg-white/[0.03] ">
        <div class="px-5 py-4">
            <h3 class="text-base font-medium text-white/90">Container List</h3>
        </div>
        <div class="p-5 border-t border-gray-800">

            <!--TABLE-->

            <div class="overflow-hidden rounded-xl border border-gray-800 bg-white/[0.03]"

            >
                <div class="max-w-full overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                        <tr class="border-b border-gray-800">
                            <th class="px-5 py-3">
                                <div class="flex items-center">
                                    <p class="font-medium text-gray-400 ">
                                        Name
                                    </p>
                                </div>

                            </th>
                            <th class="px-5 py-3">Image</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Ports</th>
                            <th class="px-5 py-3">Aktionen</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($containers as $ctr): ?>
                            <tr>
                                <td>
                                    <a href="/containers/<?= $ctr->getId() ?>"
                                    ><?= htmlspecialchars($ctr->getNames()[0]) ?></a
                                    >
                                </td>
                                <td><?= htmlspecialchars($ctr->getImage()) ?></td>
                                <td class="status-<?= strtolower($ctr->getState()) ?>">
                                    <?= htmlspecialchars($ctr->getState()) ?>
                                </td>
                                <td>
                                    <?php foreach ($ctr->getPorts() as $p): ?> <?= ($p->getPublicPort() ??
                                            '') . ' → ' . ($p->getPrivatePort() ?? '') ?><br/>
                                    <?php endforeach; ?>
                                </td>
                                <td>
                                    <form
                                            method="POST"
                                            action="/containers/<?= $ctr->getId() ?>/start"
                                            style="display: inline"
                                    >
                                        <button type="submit">Start</button>
                                    </form>
                                    <form
                                            method="POST"
                                            action="/containers/<?= $ctr->getId() ?>/stop"
                                            style="display: inline"
                                    >
                                        <button type="submit">Stop</button>
                                    </form>
                                    <form
                                            method="POST"
                                            action="/containers/<?= $ctr->getId() ?>/restart"
                                            style="display: inline"
                                    >
                                        <button type="submit">Restart</button>
                                    </form>
                                    <form
                                            method="POST"
                                            action="/containers/<?= $ctr->getId() ?>/delete"
                                            style="display: inline"
                                            onsubmit="return confirm('Container wirklich löschen?');"
                                    >
                                        <button type="submit">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>

            </div>

        </div>
    </div>
</div>


