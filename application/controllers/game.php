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
            self::redirect("/404");
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
            self::redirect("/404");
        }
        $session->erase('Game\Authorize:$campaign');

        $model = $campaign->type;
        $game = $model::first(array("id = ?" => $campaign->type_id));
        $img = $this->_process($game, $campaign);

        $view->set("img", $img);
    }

    protected function _setup($path, $game, $participant) {
        $user = $this->user;
        
        $user_img = "{$path}user-".$user->fbid.".jpg";
        if (!file_exists($user_img)) {
            if (!copy('http://graph.facebook.com/'.$user->fbid.'/picture?width='.$game->usr_w.'&height='.$game->usr_h, $user_img)) {
                die('Could not copy image');
            }
        }
        $user_img = Shared\Image::resize($user_img, $game->usr_w, $game->usr_h);

        $src_file = $path . $game->base_im;
        
        if ($participant) {
            $filename = $participant->image;
        } else {
            $filename = Shared\Markup::uniqueString() . '.jpg';
        }
        $final_img = $path . $filename;
        copy($src_file, $final_img);

        return array(
            'dest' => Shared\Image::resource($final_img),
            'usr' => Shared\Image::resource($user_img),
            'file' => $final_img,
            'filename' => $filename
        );
    }

    /**
     * @param object $game
     * @param object $campaign object of class \Campaign
     * @param boolean $play_agin If user wishes to play the game again
     *
     * @return string String containing the filename of the resultant image
     */
    protected function _process($game, $campaign, $play_again = false) {
        $participant = Participant::first(array("user_id = ?" => $this->user->id, "campaign_id = ?" => $campaign->id));
        if ($participant && !$play_again) {
            return $participant->image;
        }

        $path = APP_PATH.'/public/assets/uploads/images/';
        $vars = $this->_setup($path, $game, $participant);
        $dest = $vars['dest'];
        
        $items = Item::all(array("looklike_id = ?" => $game->id, "meta_key = ?" => "gender", "meta_value = ?" => strtolower($this->user->gender)));
        $key = rand(0, count($items) - 1);
        $item = $items[$key];

        imagealphablending($dest, false); imagesavealpha($dest, true);

        imagecopymerge($dest, $vars['usr'], $game->usr_x, $game->usr_y, 0, 0, $game->usr_w, $game->usr_h, 100);
        
        $item_img = Shared\Image::resize($path . $item->image, $game->src_w, $game->src_h);
        $item_res = Shared\Image::resource($item_img);
        
        imagecopymerge($dest, $item_res, $game->src_x, $game->src_y, 0, 0, $game->src_w, $game->src_h, 100);

        $facebook_grey = imagecolorallocate($dest, 74, 74, 74); // Create grey color
        imagealphablending($dest, true); //bring back alpha blending for transperent font

        // replace $font with font path
        $font = APP_PATH.'/public/assets/fonts/monaco.ttf';
        imagettftext($dest, $game->txt_size, $game->txt_angle, 170, 190, $facebook_grey , $font, $item->text);

        // create image
        imagejpeg($dest, $vars['file']);

        if (!$participant) {
            $participant = new Participant(array(
                "user_id" => $this->user->id,
                "campaign_id" => $campaign->id,
                "live" => true
            ));
        }
        $participant->image = $vars['filename'];
        $participant->save();
        return $participant->image;
    }
}
