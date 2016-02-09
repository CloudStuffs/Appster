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
                "image" => "",
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

	public function play($title, $id) {
		$this->seo(array("title" => "Play Game", "view" => $this->getLayoutView()));
		$view = $this->getActionView();
	}

    protected function _setup($path, $looklike) {
        $user = $this->user;
        
        $user_img = "{$path}user-".$user->fbid.".jpg";
        if (!file_exists($user_img)) {
            if (!copy('http://graph.facebook.com/'.$user->fbid.'/picture?width='.$looklike->usr_w.'&height='.$looklike->usr_h, $user_img)) {
                die('Could not copy image');
            }
        }
        $user_img = Shared\Image::resize($user_img, $looklike->usr_w, $looklike->usr_h);

        $src_file = $path . $looklike->base_im;
        
        // create a container for the final image
        // $filename = Shared\Markup::uniqueString();
        $filename = 'user-final-'. $user->fbid;
        $final_img = $path . $filename .'.jpg';
        copy($src_file, $final_img);

        return array(
            'dest' => Shared\Image::resource($final_img),
            'usr' => Shared\Image::resource($user_img),
            'filename' => $filename
        );
    }

    public function test($looklike_id) {
        $this->noview();
        $looklike = Looklike::first(array("id = ?" => $looklike_id));
        $path = APP_PATH.'/public/assets/uploads/images/';

        // echo '<img src="'. CDN . 'uploads/images/' . $looklike->base_im . '">';
        $vars = $this->_setup($path, $looklike);
        $dest = $vars['dest'];
        echo ($vars['usr']);

        /*
        if (RequestMethods::post("action") == "fbLogin") {
            $user = User::first(array("email = ?" => RequestMethods::post("email")));
        }*/
        
        $items = Item::all(array("looklike_id = ?" => $looklike->id, "meta_key = ?" => "gender", "meta_value = ?" => strtolower($this->user->gender)));
        $key = rand(0, count($items) - 1);
        $item = $items[$key];

        imagealphablending($dest, false); 
        imagesavealpha($dest, true);

        // Now create the final image for the user with whatever campaign image + plus user image + text
        // copy the user image
        imagecopymerge($dest, $vars['usr'], $looklike->usr_x, $looklike->usr_y, 0, 0, $looklike->usr_w, $looklike->usr_h, 100);
        
        // copy the item image
        $item_img = Shared\Image::resize($path . $item->image, $looklike->src_w, $looklike->src_h);
        $item_res = Shared\Image::resource($item_img);
        
        imagecopymerge($dest, $item_res, $looklike->src_x, $looklike->src_y, 0, 0, $looklike->src_w, $looklike->src_h, 100);

        // copy celeb text
        $facebook_grey = imagecolorallocate($dest, 74, 74, 74); // Create grey color
        imagealphablending($dest, true); //bring back alpha blending for transperent font

        // replace $font with font path
        $font = APP_PATH.'/public/assets/fonts/monaco.ttf';
        imagettftext($dest, $looklike->txt_size, $looklike->txt_angle, 170, 190, $facebook_grey , $font, $item->text);

        // create image
        imagejpeg($dest, $vars['filename']);
        var_dump($vars['filename']);

        $participant = Participant::first(array("user_id = ?" => $user->id, "campaign_id = ?" => 1));
        if (!$participant) {
            $participant = new Participant(array(
                "user_id" => $user->id,
                "campaign_id" => 1
            ));
        }
        $participant->image = $vars['filename'];
        // $participant->save();*
    }
}
