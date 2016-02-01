<?php
/**
 * Description of auth
 *
 * @author Faizan Ayubi
 */
use Shared\Controller as Controller;
use Framework\RequestMethods as RequestMethods;
use Framework\Registry as Registry;
use \Curl\Curl;

class Auth extends Controller {
    
    protected function _register() {
        $user = new User(array(
            "name" => RequestMethods::post("name"),
            "email" => RequestMethods::post("email"),
            "gender" => RequestMethods::post("gender", ""),
            "fbid" => RequestMethods::post("fbid", ""),
            "live" => 1,
            "admin" => 0
        ));
        $user->save();

        return $user;
    }

    public function fbLogin() {
        $this->JSONview();
        $view = $this->getActionView();
        $session = Registry::get("session");
        if ((RequestMethods::post("action") == "fbLogin") && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
            // process the registration
            $email = RequestMethods::post("email");
            $user = User::first(array("email = ?" => $email));
            if (!$user) {
                $user = $this->_register();
            }
            $this->setUser($user);
            $view->set("success", true);
        } else {
            $view->set("success", false);
        }
    }

    public function upload() {
        $this->JSONview();
        $view = $this->getActionView();

        $data = RequestMethods::post("image");
        $img = explode(",", $data);
        $path = APP_PATH . "/public/assets/uploads/";
        $filename = uniqid() . ".png";
        if (file_put_contents($path.$filename,base64_decode($img[1]))) {
            $view->set("path", CDN . "uploads/" . $filename);
        }
    }
}
