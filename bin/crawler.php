<?php

require __DIR__ . "/../vendor/autoload.php";

use App\Services\CrawlerService;

// Ensure we are running in CLI mode
if (php_sapi_name() !== "cli") {
    die("This script can only be run from the command line.");
}

$crawler = new CrawlerService();
$crawler->crawl();
