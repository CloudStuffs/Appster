<?php
/**
 * Item controller
 *
 * @author Hemant Mann
 */
use Framework\RequestMethods as RequestMethods;
use Framework\Registry as Registry;

class Items extends Admin {
	/**
	 * @before _secure, _admin, changeLayout
	 */
	public function remove($item_id, $type) {
		$this->noview();

		try {
			$item = $type::first(array("id = ?" => $item_id));
			if ($item) {
				$property = null;
				if (property_exists($item, "_image")) {
					$property = "image";
				} elseif (property_exists($item, "_base_im")) {
					$property = "base_im";
				}

				if ($property) {
					@unlink(APP_PATH.'/public/assets/uploads/images/'. $item->$property);
				}
				$item->delete();
			}
		} catch (\Exception $e) {
			// die($e->getMessage());
		}
		$this->redirect($_SERVER['HTTP_REFERER']);
	}

	/**
     * @before _secure, changeLayout, _admin
     */
	public function domains() {
		$this->seo(array("title" => "Domains", "view" => $this->getLayoutView()));
		$view = $this->getActionView();

		if (RequestMethods::post("action") == "domain") {
			$p = Registry::get("MongoDB")->domains;
			$domain = new Meta(array(
			    "user_id" => $this->user->id,
			    "property" => "domain",
			    "value" => RequestMethods::post("domain")
			));
			$domain->save();
			$p->insert(array(
				'domain' => $domain->value
            ));
			$view->set("message", "Domain Added Successfully");
		}

		$domains = Meta::all(array("property=?" => "domain"));
		$view->set("domains", $domains);
	}

	/**
     * @before _secure, changeLayout, _admin
     */
	public function fbapps() {
		$this->seo(array("title" => "FBApps", "view" => $this->getLayoutView()));
		$view = $this->getActionView();

		if (RequestMethods::post("action") == "fbapps") {
			$fbapp = new Meta(array(
			    "user_id" => $this->user->id,
			    "property" => "fbapp",
			    "value" => RequestMethods::post("fbapp")
			));
			$fbapp->save();
			$view->set("message", "FBApp Added Successfully");
		}

		$fbapps = Meta::all(array("property=?" => "fbapp"));
		$view->set("fbapps", $fbapps);
	}
}
