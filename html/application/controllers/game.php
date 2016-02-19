<?php
/**
 * Description of game
 *
 * @author Faizan Ayubi
 */
use Framework\RequestMethods as RequestMethods;
use Framework\Registry as Registry;
use \Curl\Curl;

class Game extends Play {
	
    public function view($title, $id) {
        $campaign = Campaign::first(array("id = ?" => $id));
        $this->seo(array("title" => $campaign->title, "view" => $this->getLayoutView()));
        $view = $this->getActionView();

        $view->set("campaign", $campaign);
    }

    public function result($participant_id) {
        $participant = Participant::first(array("id = ?" => $participant_id));
        $campaign = Campaign::first(array("id = ?" => $participant->campaign_id));
        $this->seo(array(
            "title" => $campaign->title, 
            "description" => $campaign->description,
            "photo" => CDN . "uploads/images" .$participant->image,
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();

        $view->set("campaign", $campaign);
        $view->set("participant", $participant);
    }

	/**
     * @before _secure, changeLayout, _admin
     */
	public function looklike() {
		$this->seo(array("title" => "Looklike Game", "view" => $this->getLayoutView()));
        $view = $this->getActionView();
        $fields = array("src_x", "src_y", "src_h", "src_w", "usr_x", "usr_y", "txt_x", "txt_y", "usr_w", "usr_h", "txt_size", "txt_angle", "txt_color");
        
        if (RequestMethods::post("action") == "campaign") {
            $looklike = new \LookLike(array(
                "base_im" => $this->_upload("base_im")
            ));
            foreach ($fields as $key => $value) {
                $looklike->$value = RequestMethods::post($value);
            }
            $looklike->live = true;
            $looklike->save();

            $campaign = new \Campaign(array(
                "title" => RequestMethods::post("title"),
                "description" => RequestMethods::post("description"),
                "image" => $this->_upload("promo_im"),
                "type" => "looklike",
                "type_id" => $looklike->id
            ));
            $campaign->save();

            self::redirect("/game/item/".$looklike->id);
        }
	}

    /**
     * @before _secure, changeLayout, _admin
     */
    public function like() {
        $this->seo(array("title" => "Like Game", "view" => $this->getLayoutView()));
        $view = $this->getActionView();
        $fields = array("usr_x", "usr_y", "txt_x", "txt_y", "usr_w", "usr_h", "txt_size", "txt_angle", "txt_color");
        
        if (RequestMethods::post("action") == "campaign") {
            $imagetext = new \ImageText(array(
                "base_im" => $this->_upload("base_im")
            ));
            foreach ($fields as $key => $value) {
                $imagetext->$value = RequestMethods::post($value);
            }
            $imagetext->live = true;
            $imagetext->save();

            $campaign = new \Campaign(array(
                "title" => RequestMethods::post("title"),
                "description" => RequestMethods::post("description"),
                "image" => $this->_upload("promo_im"),
                "type" => "imagetext",
                "type_id" => $imagetext->id
            ));
            $campaign->save();

            self::redirect("/game/imagetext/".$imagetext->id);
        }
    }

	/**
     * @before _secure, changeLayout, _admin
     */
	public function item($looklike_id) {
		$this->seo(array("title" => "Looklike Content", "view" => $this->getLayoutView()));
        $view = $this->getActionView();

        $looklike = LookLike::first(array("id = ?" => $looklike_id));

        if (RequestMethods::post("action") == "shuffle") {
            $item = new Item(array(
                "looklike_id" => $looklike->id,
                "meta_key" => "gender",
                "meta_value" => RequestMethods::post("gender"),
                "image" => $this->_upload("image"),
                "live" => true,
                "text" => RequestMethods::post("text")
            ));
            $item->save();
        }
        $items = Item::all(array("looklike_id = ?" => $looklike->id));

        $view->set("looklike", $looklike);
        $view->set("items", $items);
	}

    /**
     * @before _secure, changeLayout, _admin
     */
    public function imagetext($imagetext_id) {
        $this->seo(array("title" => "ImageText Content", "view" => $this->getLayoutView()));
        $view = $this->getActionView();

        $imagetext = ImageText::first(array("id = ?" => $imagetext_id));

        if (RequestMethods::post("action") == "shuffle") {
            $item = new ImageTextItem(array(
                "looklike_id" => $imagetext->id,
                "meta_key" => "",
                "meta_value" => "",
                "image" => $this->_upload("image"),
                "live" => true,
                "text" => RequestMethods::post("text")
            ));
            $item->save();
        }
        $items = ImageTextItem::all(array("looklike_id = ?" => $imagetext->id));

        $view->set("imagetext", $imagetext);
        $view->set("items", $items);
    }

    /**
     * @before _secure, changeLayout, _admin
     */
    public function publish($id, $status) {
        $this->edit('Campaign', $id, 'live', $status);
    }

	/**
     * @before _secure, changeLayout, _admin
     */
	public function all() {
		$this->seo(array("title" => "All Games", "view" => $this->getLayoutView()));
        $view = $this->getActionView();

        $limit = RequestMethods::get("limit", 10);
        $page = RequestMethods::get("page", 1);

        $campaign = Campaign::all(array(), array("*"), "created", "desc", $limit, $page);
        $count = Campaign::count();

        $view->set("campaigns", $campaign);
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

    /**
     * @before _secure
     */
	public function authorize($id, $token) {
        $campaign = Campaign::first(array("id = ?" => $id, "live = ?" => true));

        $session = Registry::get("session");
        if ($token !== $session->get('CampaignAccessToken') || !$campaign) {
            self::redirect("/index.html");
        }
        $session->erase('CampaignAccessToken');

        $session->set('Game\Authorize:$campaign', $campaign);
        self::redirect("/game/play");
	}

    /**
     * @before _secure
     */
    public function play() {
        $this->seo(array("title" => "Play Game", "view" => $this->getLayoutView()));
        $view = $this->getActionView();
        $session = Registry::get("session");

        $campaign = $session->get('Game\Authorize:$campaign');
        if (!$campaign) {
            self::redirect("/index.html");
        }
        $session->erase('Game\Authorize:$campaign');

        $model = $campaign->type;
        $game = $model::first(array("id = ?" => $campaign->type_id));
        $img = $this->_process($game, $campaign);

        $participant = Participant::first(array("user_id = ?" => $this->user->id, "campaign_id = ?" => $campaign->id));

        $view->set("img", $img);
        $view->set("participant", $participant);
    }
}
