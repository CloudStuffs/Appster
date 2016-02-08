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
	public function remove($item_id) {
		$this->noview();

		$item = Item::first(array("id = ?" => $item_id));
		if ($item) {
			@unlink(CDN.'uploads/images/'. $item->image);
			$item->delete();
		}
		self::redirect($_SERVER['HTTP_REFERER']);
	}
}
