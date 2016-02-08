<?php

/**
 * The Default Example Controller Class
 *
 * @author Faizan Ayubi
 */
use Shared\Controller as Controller;
use Framework\RequestMethods as RequestMethods;

class Home extends Controller {

    public function index() {
        $this->getLayoutView()->set("seo", Framework\Registry::get("seo"));
        $view = $this->getActionView();

        $limit = RequestMethods::get("limit", 10);
        $page = RequestMethods::get("page", 1);

        $items = LookLike::all(array("live = ?" => true), array("id", "live", "title", "description", "base_im", "created"), "created", "desc", $limit, $page);
        $count = LookLike::count(array("live = ?" => true));

        $view->set("items", $items)
            ->set("count", $count)
            ->set("limit", $limit)
            ->set("page", $page);
    }

    public function contact() {
        $this->seo(array(
            "title" => "Contact Us",
            "view" => $this->getLayoutView()
        ));
    }

    /**
     * @before _secure
     */
    public function profile() {
        $this->seo(array(
            "title" => "Profile",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();
    }

}
