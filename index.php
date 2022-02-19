<?php
require('./controllers/employee-index-controller.php');

$controller = new EmployeeIndexController($_GET);
$controller->main();

?>
