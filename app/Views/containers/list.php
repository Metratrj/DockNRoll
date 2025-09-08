<?php
/** @var \OpenAPI\Client\Model\ContainerSummary[] $containers */
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Container Liste</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 0.5em; border: 1px solid #ccc; }
        .status-running { color: green; font-weight: bold; }
        .status-exited { color: red; font-weight: bold; }
        button { margin: 0 0.2em; }
    </style>
</head>
<body>
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
    <?php
    var_dump($containers);
    foreach ($containers as $ctr): ?>
        <tr>
            <td><?= htmlspecialchars($ctr->getNames()[0]) ?></td>
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
</body>
</html>
