<?php
	
	define('SITE', "http://$_SERVER[HTTP_HOST]/");
	define("URL", "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    
    $arr = explode("/", URL);
    $id = end($arr);
    
    if (!is_numeric($id)) {
        include 'view/static.php';
    }
    $m = new MongoClient();
    $db = $m->stats;
    $participants = $db->participants;
    $item = $participants->findOne(array('participant_id' => (int) $id));
    if (isset($item)) {
        include 'view/dynamic.php';
    } else {
    	include 'view/static.php';
    }