<?php
require('./controllers/branch-index-controller.php');

$controller = new BranchIndexController($_GET);
$controller->main();
?>
