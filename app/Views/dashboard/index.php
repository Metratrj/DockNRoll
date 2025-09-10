<section class="dashboard">
    <!--<h1>Willkommen, <?php /*= htmlspecialchars($_SESSION['username'] ?? 'User') */ ?>!</h1>
-->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-7 space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <div class="rounded-2xl border border-gray-800 p-6 bg-white/[0.03]">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-800">
                        <i class="fa-regular fa-server"></i>

                    </div>
                </div>
                <div class="rounded-2xl border border-gray-800 p-6 bg-white/[0.03]">


                </div>
            </div>
        </div>
        <div class="col-span-5 space-y-6">


        </div>

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


    </div>
</section>
