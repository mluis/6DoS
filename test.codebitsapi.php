<?php
require_once 'TestAPI.php';
require_once 'NetworkGraph.php';
require_once 'class.database.php';
//$test = new TextAPI();
//$test->init();
$testdb = new CodeBitsDatabase();
//$testdb->clear();
$test = new NetworkGraph($testdb);
//$test->createGraph();
$test->createGraphFromDB();

$path = $test->getPath("Eriksson Monteiro", "Nuno Polónia");
echo $test->getGraph($path);
?>
