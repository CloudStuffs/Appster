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
