<?php
/** @var ContainerSummary[] $containers */

use OpenAPI\Client\Model\ContainerSummary;

?>
<h1>Container Übersicht</h1>
<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Image</th>
        <th>Status</th>
        <th>Ports</th>
        <th>Aktionen</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($containers as $ctr): ?>
        <tr>
            <td><a href="/containers/<?= $ctr->getId() ?>"><?= htmlspecialchars($ctr->getNames()[0]) ?></a></td>
            <td><?= htmlspecialchars($ctr->getImage()) ?></td>
            <td class="status-<?= strtolower($ctr->getState()) ?>"><?= htmlspecialchars($ctr->getState()) ?></td>
            <td>
                <?php foreach ($ctr->getPorts() as $p): ?>
                    <?= ($p->getPublicPort() ?? '') . ' → ' . ($p->getPrivatePort() ?? '') ?><br>
                <?php endforeach; ?>
            </td>
            <td>
                <form method="POST" action="/containers/<?= $ctr->getId() ?>/start" style="display:inline">
                    <button type="submit">Start</button>
                </form>
                <form method="POST" action="/containers/<?= $ctr->getId() ?>/stop" style="display:inline">
                    <button type="submit">Stop</button>
                </form>
                <form method="POST" action="/containers/<?= $ctr->getId() ?>/restart" style="display:inline">
                    <button type="submit">Restart</button>
                </form>
                <form method="POST" action="/containers/<?= $ctr->getId() ?>/delete" style="display:inline"
                      onsubmit="return confirm('Container wirklich löschen?');">
                    <button type="submit">Remove</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
