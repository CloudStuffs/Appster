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
        $fb = new Curl(); $access_token = RequestMethods::post("access_token");
        $fb->get('https://graph.facebook.com/me', [
            'access_token' => $access_token,
            'fields' => 'name,email,gender,id'
        ]);
        $response = $fb->response; $fb->close();

        if ($response->error || $response->id != $user->fbid) {
            throw new \Exception("Error Processing Request");
        } else {
            $user->save();
        }

        return $user;
    }

    public function fbLogin() {
        $this->JSONview();
        $view = $this->getActionView();
        $session = Registry::get("session");
        if ((RequestMethods::post("action") == "fbLogin") && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
            // process the registration
            $fbid = RequestMethods::post("fbid"); $access_token = RequestMethods::post("access_token");
            
            $user = (!$this->user) ? User::first(["fbid = ?" => $fbid]) : $this->user;
            if (!$user) {
                try {
                    $user = $this->_register();
                } catch (\Exception $e) {
                    $view->set("success", false);
                    return;
                }
            }
            $this->setUser($user);
            
            $redirect = RequestMethods::post("loc", "");
            if ($redirect != '') {
                $token = Shared\Markup::uniqueString();
                $session->set('CampaignAccessToken', $token);
                $view->set("redirect", "/". $redirect . "/{$token}");
            }
            $view->set("success", true);
        } else {
            $view->set("success", false);
        }
    }

    protected function _upload($name, $opts = array()) {
        $type = isset($opts["type"]) ? $opts["type"] : "images";
        /*** Create Directory if not present ***/
        $path = APP_PATH . "/public/assets/uploads/{$type}";
        exec("mkdir -p $path");
        $path .= "/";

        $filename = Shared\Markup::uniqueString();

        // For normal file upload via browser
        if (isset($_FILES[$name])) {
            $file = $_FILES[$name];

            $extension = pathinfo($file["name"], PATHINFO_EXTENSION);
            if (empty($extension)) {
                return false;
            }
            $filename .= ".{$extension}";
            /*** Check mime type before moving ***/
            if (isset($opts["mimes"])) {
                if (!preg_match("/^{$opts['mimes']}$/", $extension)) {
                    return false;
                }
            }
            
            if (move_uploaded_file($file["tmp_name"], $path . $filename)) {
                return $filename;
            } else {
                return FALSE;
            }
        // for app upload
        } elseif ($f = RequestMethods::post($name)) {
            if (file_put_contents($filename, base64_decode($f))) {
                return $filename;
            }
        } else {
            return false;
        }
    }
}
