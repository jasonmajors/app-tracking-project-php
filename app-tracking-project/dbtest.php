<?php
	require 'header.inc.php';

	use Jason\Sql;

	$conditions = array('App_Status' => "Pending");
	$updates = array('Position' => 'Board Person');
	$db = new Sql();
	try {
		$db->update('applications', $updates, $conditions);
	} catch (PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }    
	
require 'footer.php'; ?>
