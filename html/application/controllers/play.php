<?php
/**
 * Description of game
 *
 * @author Faizan Ayubi
 */
use Framework\RequestMethods as RequestMethods;
use Framework\Registry as Registry;
use \Curl\Curl;

class Play extends Admin {

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

        $m = new MongoClient();$db = $m->stats;$p = $db->participants;
        $p->insert(array(
            'participant_id' => $participant->id,
            'title' => $campaign->title,
            'description' => $campaign->description,
            'image' => $vars['filename'],
            'url' => 'game/result/'.$participant->id
        ));
        return $participant->image;
    }
}