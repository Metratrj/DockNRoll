<?php
/** @var ContainerStatsResponse $stats */

use OpenAPI\Client\Model\ContainerStatsResponse;

?>
<h1>Container <?php $stats->getName() ?></h1>
<?php foreach ($stats as $stat) { echo '
<h2>' . $stat . '</h2>
'; }
