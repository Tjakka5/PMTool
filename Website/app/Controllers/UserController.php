<?php
class UserController {
	public function indexAction() {
		echo blade()->run("Home");
    }
    
    public function signupAction() {
        echo blade()->run("Todo"); // TODO
    }

    public function statisticsAction() {
        echo blade()->run("Todo"); // TODO
    }

    public function logoutPOSTAction() {
        $_SESSION["user_id"]  = null;
        $_SESSION["is_admin"] = null;

        Redirect::home();
    }
}
?>