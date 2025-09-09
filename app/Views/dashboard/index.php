<section class="dashboard">
    <h1>Willkommen, <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?>!</h1>

    <div class="cards">
        <div class="card">
            <h2>Running Containers</h2>
            <p><?= $stats['containers_running'] ?? 0 ?></p>
        </div>
        <div class="card">
            <h2>Images</h2>
            <p><?= $stats['images'] ?? 0 ?></p>
        </div>
        <div class="card">
            <h2>Volumes</h2>
            <p><?= $stats['volumes'] ?? 0 ?></p>
        </div>
        <div class="card">
            <h2>Audit-Logs (letzte 24h)</h2>
            <p><?= $stats['audit_logs'] ?? 0 ?></p>
        </div>
    </div>
</section>
