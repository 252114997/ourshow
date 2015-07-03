<?php

class MenuComposer {
	public function compose($view) {
		$menus = MainFrameController::getSubMenus(MainFrameController::ROOT_MENU_ID);
		$view->with('menus', $menus);
	}
}