<?php

/**
 * The Default Example Controller Class
 *
 * @author Faizan Ayubi
 */
use Shared\Controller as Controller;

class Home extends Controller {

    public function index() {
        $this->getLayoutView()->set("seo", Framework\Registry::get("seo"));
    }

    public function contact() {
        $this->seo(array(
            "title" => "Contact Us",
            "view" => $this->getLayoutView()
        ));
    }


}
