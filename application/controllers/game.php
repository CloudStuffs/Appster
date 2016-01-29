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
	public function create() {
		$this->seo(array("title" => "Create Game", "view" => $this->getLayoutView()));
        $view = $this->getActionView();
        if (RequestMethods::post("action") == "campaign") {
        	$campaign = new Campaign(array(
        		"user_id" => $this->user->id,
        		"title" => RequestMethods::post("title"),
        		"image" => $this->_upload("image"),
        		"description" => RequestMethods::post("description", ""),
        		"fblogin" => RequestMethods::post("fblogin", true)
        	));
        	$campaign->save();
        	self::redirect("/game/activity/".$campaign->id);
        }
	}

	/**
     * @before _secure, changeLayout, _admin
     */
	public function activity($campaign_id) {
		$this->seo(array("title" => "Game Activity", "view" => $this->getLayoutView()));
        $view = $this->getActionView();
	}

	/**
     * @before _secure, changeLayout, _admin
     */
	public function all() {
		$this->seo(array("title" => "All Items", "view" => $this->getLayoutView()));
        $view = $this->getActionView();
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
