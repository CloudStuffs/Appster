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
        $fields = array("description", "src_x", "src_y", "src_h", "src_w", "usr_x", "usr_y", "txt_x", "txt_y", "usr_w", "usr_h", "txt_size", "txt_angle", "txt_color", "title", "description");
        
        if (RequestMethods::post("action") == "campaign") {
            $looklike = new LookLike(array(
                "base_im" => $this->_upload("base_im")
            ));
            foreach ($fields as $key => $value) {
                $looklike->$value = RequestMethods::post($value);
            }
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

        if (RequestMethods::post("action") == "shuffle") {
            $item = new Item(array(
                "looklike_id" => $looklike->id,
                "key" => "gender",
                "value" => RequestMethods::post("gender"),
                "image" => $this->_upload("image"),
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
    public function publish($looklike_id, $status) {
        $this->edit('LookLike', $looklike_id, 'live', $status);
    }

	/**
     * @before _secure, changeLayout, _admin
     */
	public function all() {
		$this->seo(array("title" => "All Games", "view" => $this->getLayoutView()));
        $view = $this->getActionView();

        $limit = RequestMethods::get("limit", 10);
        $page = RequestMethods::get("page", 1);

        $looklikes = LookLike::all(array(), array("id", "live", "title", "image", "created"), "created", "desc", $limit, $page);
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


    public function test($looklike_id) {
        $this->noview();
        $looklike = Looklike::first(array("id = ?" => $looklike_id));

        // check login
        /*
        if (RequestMethods::post("action") == "fbLogin") {
            $user = User::first(array("email = ?" => RequestMethods::post("email")));
        }*/
        $user = $this->user;
        
        // get user profile pic
        $user_img = APP_PATH.'/public/assets/uploads/images/user-'.$user->fbid.'.jpg';
        if (!file_exists($user_img)) {
            if (!copy('http://graph.facebook.com/'.$user->fbid.'/picture?width='.$looklike->usr_w.'&height='.$looklike->usr_h, $user_img)) {
                die('Could not copy image');
            }
        }
        $user_img_res = imagecreatefromjpeg($user_img);

        // check extension and 
        $src_file = APP_PATH.'/public/assets/uploads/images/'.$looklike->base_im;
        
        // create a container for the final image
        $final_img = APP_PATH.'/public/assets/uploads/images/user-final-'.$user->fbid .'.jpg';
        if (!file_exists($final_img)) {
            copy($src_file, $final_img);
        }

        // create image resources for processing
        $extension = pathinfo($src_file, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'png':
                $dest = imagecreatefrompng($final_img);
                break;

            case 'jpg':
            case 'jpeg':
                $dest = imagecreatefromjpeg($final_img);
                break;
            
            default:
                die('Invalid extension');
                break;
        }

        // now find random image from the items
        // $images = Item::all(array("looklike_id = ?" => $looklike->id, "key = ?" => "gender", "value = ?" => strtolower($user->gender)));
        // $random = array_rand($images);
        $random = Item::first();

        imagealphablending($dest, false); 
        imagesavealpha($dest, true);

        // Now create the final image for the user with whatever campaign image + plus user image + text
        // copy the user image
        imagecopymerge($dest, $user_img_res, $looklike->usr_x, $looklike->usr_y, 0, 0, $looklike->usr_w, $looklike->usr_h, 100);

        // copy the celebrity image
        // create image resources for processing
        $celeb_img = APP_PATH.'/public/assets/uploads/images/'. $random->image;
        $extension = pathinfo($celeb_img, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'png':
                $celebrity = imagecreatefrompng($celeb_img);
                break;

            case 'jpg':
            case 'jpeg':
                $celebrity = imagecreatefromjpeg($celeb_img);
                break;
            
            default:
                die('Invalid extension');
                break;
        }
        // need to resize the celebrity image so that it fits the final celebrity image
        // @todo - code to resize img

        imagecopymerge($dest, $celebrity, $looklike->src_x, $looklike->src_y, 0, 0, $looklike->src_w, 
            $looklike->src_h, 100);

        // copy celeb text
        $facebook_grey = imagecolorallocate($dest, 74, 74, 74); // Create grey color
        imagealphablending($dest, true); //bring back alpha blending for transperent font

        // replace $font with font path
        $font = CDN.'/public/assets/fonts/monaco.ttf';
        imagettftext($dest, $looklike->txt_size, $looklike->txt_angle, 170, 190, $facebook_grey , $font, $random->text); //Write user id to id card

        // create image
        imagejpeg($dest, $final_img);
        var_dump($final_img);

        $participant = Participant::first(array("user_id = ?" => $user->id, "campaign_id = ?" => 1));
        if (!$participant) {
            $participant = new Participant(array(
                "user_id" => $user->id,
                "image" => 'user-final-'.$user->fbid,
                "campaign_id" => 1
            ));
        }
        
        $participant->save();

        var_dump($participant);
    }
}
