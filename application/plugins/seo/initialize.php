<?php

// initialize seo
include("seo.php");

$seo = new SEO(array(
    "title" => "LikesBazar Affiliate Network",
    "keywords" => "earn money, facebook page monetization",
    "description" => "Welcome to Our Affiliate Network, we let you Monetize your platform through us, get paid with high rpm value in india.",
    "author" => "LikesBazar Team",
    "robots" => "INDEX,FOLLOW",
    "photo" => CDN . "img/logo.png"
));

Framework\Registry::set("seo", $seo);
