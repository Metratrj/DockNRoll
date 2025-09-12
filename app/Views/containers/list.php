<?php
/*
 * Copyright (c) 2025.
 */

/** @var ContainerSummary[] $containers */

use OpenAPI\Client\Model\ContainerSummary; ?>
<!-- Breadcrumb -->
<div>
  <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h2 class="text-xl font-semibold text-white/90">Containers</h2>
    <nav>
      <ol class="flex items-center gap-1.5">
        <li>
          <a href="/" class="inline-flex items-center gap-1.5 text-sm text-gray-400"> Home </a>
        </li>
        <li class="text-sm text-white/90">Containers</li>
      </ol>
    </nav>
  </div>
</div>

<div class="space-y-5">
  <div class="rounded-2xl border border-gray-800 bg-white/[0.03]">
    <div class="px-5 py-4">
      <h3 class="text-base font-medium text-white/90">Container List</h3>
    </div>
    <div class="border-t border-gray-800 p-5">
      <!--TABLE-->

      <div class="overflow-hidden rounded-xl border border-gray-800 bg-white/[0.03]">
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
                    <p>Image</p>
                  </div>
                </th>
                <th>
                  <div>
                    <p>Status</p>
                  </div>
                </th>
                <th>
                  <div>
                    <p>Ports</p>
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
              <?php foreach ($containers as $ctr): ?>
              <tr>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <div class="flex items-center gap-3">
                      <div>
                        <a href="/containers/<?= $ctr->getId() ?>">
                          <span class="block text-sm font-medium text-[#465fff] underline">
                            <?= htmlspecialchars( $ctr->getNames()[0], ) ?>
                          </span>
                        </a>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <div class="flex items-center gap-3">
                      <span class="block font-medium text-white/90"> <?= htmlspecialchars($ctr->getImage()) ?> </span>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <p
                      class="badge-<?php echo $ctr->getState(); ?> text-s inline-flex items-center gap-x-1 rounded-full px-1.5 py-1 font-medium">
                      <?php echo match ($ctr->getState()) { "running" => '<svg
                        class="size-3"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        viewBox="0 0 16 16">
                        <path
                          d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"></path></svg
                      >', "exited" => '<svg
                        class="size-3"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        viewBox="0 0 16 16">
                        <path
                          d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path></svg
                      >', default => "", }; echo htmlspecialchars($ctr->getState()); ?>
                    </p>
                  </div>
                </td>
                <td>
                  <?php foreach ($ctr->getPorts() as $p): ?> <?php echo ($p->getPublicPort() ?? "") . " → " .
                  ($p->getPrivatePort() ?? ""); ?><br />
                  <?php endforeach; ?>
                </td>
                <td>
                  <form method="POST" action="/containers/<?= $ctr->getId() ?>/start" style="display: inline">
                    <button type="submit">Start</button>
                  </form>
                  <form method="POST" action="/containers/<?= $ctr->getId() ?>/stop" style="display: inline">
                    <button type="submit">Stop</button>
                  </form>
                  <form method="POST" action="/containers/<?= $ctr->getId() ?>/restart" style="display: inline">
                    <button type="submit">Restart</button>
                  </form>
                  <form
                    method="POST"
                    action="/containers/<?= $ctr->getId() ?>/delete"
                    style="display: inline"
                    onsubmit="return confirm('Container wirklich löschen?');">
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
