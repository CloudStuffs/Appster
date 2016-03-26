<?php
	
	define('SITE', "http://$_SERVER[HTTP_HOST]/");
	define("URL", "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    
    $arr = explode("/", URL);
    preg_match("/\/(\d+)/", URL, $matches);

    if (!isset($matches[1])) {
        include 'view/static.php';
    } else {
        $id = $matches[1];
        $m = new MongoClient();
        $db = $m->stats;
        $participants = $db->participants;
        $item = $participants->findOne(array('participant_id' => (int) $id));
        if (isset($item)) {
            include 'view/dynamic.php';
        } else {
            include 'view/static.php';
        }
    }