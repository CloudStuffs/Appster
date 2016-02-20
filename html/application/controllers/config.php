<?php
/**
 * @author Faizan Ayubi
 */
use Framework\RequestMethods as RequestMethods;
use Framework\Registry as Registry;
use \Curl\Curl;

class Config extends Play {

	/**
     * @before _secure, changeLayout, _admin
     */
	public function imagetext() {
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

            self::redirect("/game/looklikeitem/".$looklike->id);
        }
	}

    /**
     * @before _secure, changeLayout, _admin
     */
    public function imagetextitem($looklike_id) {
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
    public function text() {
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
    public function textitem($imagetext_id) {
        $this->seo(array("title" => "ImageText Content", "view" => $this->getLayoutView()));
        $view = $this->getActionView();

        $imagetext = ImageText::first(array("id = ?" => $imagetext_id));

        if (RequestMethods::post("action") == "shuffle") {
            $item = new ImageTextItem(array(
                "imagetext_id" => $imagetext->id,
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
    public function image() {
        $this->seo(array("title" => "Shuffleimage Game", "view" => $this->getLayoutView()));
        $view = $this->getActionView();
        $fields = array("usr_x", "usr_y", "usr_w", "usr_h");
        
        if (RequestMethods::post("action") == "campaign") {
            $shuffleimage = new \ShuffleImage(array(
                "base_im" => $this->_upload("base_im")
            ));
            foreach ($fields as $key => $value) {
                $shuffleimage->$value = RequestMethods::post($value);
            }
            $shuffleimage->live = true;
            $shuffleimage->save();

            $campaign = new \Campaign(array(
                "title" => RequestMethods::post("title"),
                "description" => RequestMethods::post("description"),
                "image" => $this->_upload("promo_im"),
                "type" => "shuffleimage",
                "type_id" => $shuffleimage->id
            ));
            $campaign->save();

            self::redirect("/game/shuffleimageitem/".$shuffleimage->id);
        }
    }

    /**
     * @before _secure, changeLayout, _admin
     */
    public function imageitem($image_id) {
        $this->seo(array("title" => "shuffleimage Content", "view" => $this->getLayoutView()));
        $view = $this->getActionView();

        $shuffleimage = ShuffleImage::first(array("id = ?" => $shuffleimage_id));

        if (RequestMethods::post("action") == "shuffle") {
            $item = new ShuffleImageItem(array(
                "shuffleimage_id" => $shuffleimage->id,
                "meta_key" => "",
                "meta_value" => "",
                "image" => $this->_upload("image"),
                "live" => true
            ));
            $item->save();
        }
        $items = ShuffleImageItem::all(array("shuffleimage_id = ?" => $shuffleimage->id));

        $view->set("shuffleimage", $shuffleimage);
        $view->set("items", $items);
    }

    /**
     * @before _secure, changeLayout, _admin
     */
    public function publish($id, $status) {
        $this->edit('Campaign', $id, 'live', $status);
    }

}