<?php
	
	define('SITE', "http://$_SERVER[HTTP_HOST]/");
	define("URL", "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    
    $arr = explode("/", URL);
    
    $m = new MongoClient();
    $db = $m->stats;
    $urls = $db->urls;
    $item = $urls->findOne(array('participant_id' => (int) end($arr)));
    if (isset($item)) {
        include 'view/dynamic.php'
    } else {
    	include 'view/static.php';
    }
    