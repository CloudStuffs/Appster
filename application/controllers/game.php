<?php
/**
 * Description of game
 *
 * @author Faizan Ayubi
 */
use Framework\RequestMethods as RequestMethods;
use Framework\Registry as Registry;
use \Curl\Curl;

class Game extends Admin {
	
	/**
     * @before _secure, changeLayout, _admin
     */
	public function looklike() {
		$this->seo(array("title" => "Looklike Game", "view" => $this->getLayoutView()));
        $view = $this->getActionView();
        if (RequestMethods::post("action") == "campaign") {
            $looklike = new LookLike(array(
                "title" => RequestMethods::post("title"),
                "image" => $this->_upload("image"),
                "description" => RequestMethods::post("description", "")
            ));
            $looklike->save();
        }
	}

	/**
     * @before _secure, changeLayout, _admin
     */
	public function content($campaign_id) {
		$this->seo(array("title" => "Game Content", "view" => $this->getLayoutView()));
        $view = $this->getActionView();
	}

	/**
     * @before _secure, changeLayout, _admin
     */
	public function all() {
		$this->seo(array("title" => "All Items", "view" => $this->getLayoutView()));
        $view = $this->getActionView();
        $limit = RequestMethods::get("limit", 10);
        $page = RequestMethods::get("page", 1);

        $campaigns = Campaign::all(array(), array("title", "image", "created"). "created", "desc", $limit, $page);
        $count = Campaign::count();

        $view->set("campaigns", $campaigns);
        $view->set("count", $count);
        $view->set("limit", $limit);
        $view->set("page", $page);
	}

	/**
     * @before _secure, changeLayout, _admin
     */
	public function manage() {
		$this->seo(array("title" => "Manage Game", "view" => $this->getLayoutView()));
        $view = $this->getActionView();
	}

	public function play($title, $id) {
		$this->seo(array("title" => "Play Game", "view" => $this->getLayoutView()));
		$view = $this->getActionView();
	}
}
