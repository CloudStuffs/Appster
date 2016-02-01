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
                "image" => "",
                "description" => RequestMethods::post("description", ""),
                "base_im" => $this->_upload("base_im"),
                "src_x" => RequestMethods::post("src_x"),
                "src_y" => RequestMethods::post("src_y"),
                "src_w" => RequestMethods::post("src_w", ""),
                "src_h" => RequestMethods::post("src_h", ""),
                "usr_x" => RequestMethods::post("usr_x"),
                "usr_y" => RequestMethods::post("usr_y"),
                "usr_w" => RequestMethods::post("usr_w", ""),
                "usr_h" => RequestMethods::post("usr_h", ""),
                "txt_x" => RequestMethods::post("txt_x"),
                "txt_y" => RequestMethods::post("txt_y"),
                "txt_size" => RequestMethods::post("txt_size"),
                "txt_angle" => RequestMethods::post("txt_angle"),
                "txt_color" => RequestMethods::post("txt_color")
            ));
            $looklike->save();

            self::redirect("/game/item/".$looklike->id);
        }
	}

	/**
     * @before _secure, changeLayout, _admin
     */
	public function item($looklike_id) {
		$this->seo(array("title" => "Looklike Content", "view" => $this->getLayoutView()));
        $view = $this->getActionView();

        $looklike = LookLike::first(array("id = ?" => $looklike_id));
        $items = Item::all(array("looklike_id = ?" => $looklike->id));

        if (RequestMethods::post("action") == "shuffle") {
            $item = new Item(array(
                "looklike_id" => $looklike->id,
                "image" => $this->_upload("image"),
                "text" => RequestMethods::post("text")
            ));
            $item->save();
        }

        $view->set("looklike", $looklike);
        $view->set("items", $items);
	}

	/**
     * @before _secure, changeLayout, _admin
     */
	public function all() {
		$this->seo(array("title" => "All Games", "view" => $this->getLayoutView()));
        $view = $this->getActionView();

        $limit = RequestMethods::get("limit", 10);
        $page = RequestMethods::get("page", 1);

        $looklikes = LookLike::all(array(), array("title", "image", "created"), "created", "desc", $limit, $page);
        $count = LookLike::count();

        $view->set("looklikes", $looklikes);
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
