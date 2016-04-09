<?php
    require 'header.inc.php';

    use Jaywrap\Jaywrap;

    $conditions = array('App_Status' => "Pending");
    $db = new Jaywrap();
    try {
        $db->delete('applications', $conditions);
    } catch (PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }    
    
require 'footer.php'; ?>
