<?php
/**
 * @author Faizan Ayubi
 */
use Framework\RequestMethods as RequestMethods;
use Framework\Registry as Registry;
use \Curl\Curl;

class Game extends Config {
	
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
    public function participants() {
        $this->seo(array("title" => "Game Participants", "view" => $this->getLayoutView()));
        $view = $this->getActionView();

        $limit = RequestMethods::get("limit", 10);
        $page = RequestMethods::get("page", 1);

        $participants = Participant::all(array(), array("*"), "created", "desc", $limit, $page);
        $count = Participant::count();

        $view->set("participants", $participants);
        $view->set("count", $count);
        $view->set("limit", $limit);
        $view->set("page", $page);
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
        switch ($model) {
            case 'imagetext':
                $img = $this->_process($game, $campaign);
                break;
            
            case 'image':
                # code...
                break;

            case 'text':
                # code...
                break;
        }

        $participant = Participant::first(array("user_id = ?" => $this->user->id, "campaign_id = ?" => $campaign->id));

        $view->set("img", $img);
        $view->set("participant", $participant);
    }
}
