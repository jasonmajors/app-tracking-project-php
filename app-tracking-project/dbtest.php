<?php
	require 'header.inc.php';

	use Jason\Sql;

	$conditions = array('Position' => array('Cage Cashier', 'Games Dealer'));
	$db = new Sql();
	$apps = $db->select('applications', $conditions);
	foreach($apps as $app) {
		echo $app['Last_Name'] . '<br>';
	}
	
require 'footer.php'; ?>
