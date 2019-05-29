<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include PINTEREST_RICH_PINS_DIR_PATH . 'includes/class/authentication.php';

if(!class_exists("pinterest_rich_pin_init_actions_class")){
	class pinterest_rich_pin_init_actions_class{
		
		
		
		function save_authentication(){
			if(isset($_POST["pinterest_wcrp_submit"])){
				$appId = isset($_POST["pinterest_wcrp_app_id"]) ? $_POST["pinterest_wcrp_app_id"] : "";
				$appSecId = isset($_POST["pinterest_wcrp_app_secret_id"]) ? $_POST["pinterest_wcrp_app_secret_id"] : "";
				
				if(empty($appId)){
					$errorMsg = __("Please insert App ID",PINTEREST_RICH_PINS_TEXT_DOMAIN);
				}
				else if(empty($appSecId)){
					$errorMsg = __("Please insert App Secret ID",PINTEREST_RICH_PINS_TEXT_DOMAIN);
				}
				else{
					$appId = $_POST["pinterest_wcrp_app_id"];
					$appSecId = $_POST["pinterest_wcrp_app_secret_id"];
					$mysiteUrl = urlencode(site_url()."test-pinterest");
					wp_redirect("https://api.pinterest.com/oauth/?response_type=code&redirect_uri=".$mysiteUrl."&client_id=".$appId."&scope=read_public,write_public&state=768uyFys");
				}
				
			}
		}
		
	}
}


$newObj = new pinterest_rich_pin_init_actions_class();

if(isset($_POST['pinterest_wcrp_submit'])){
	$newObj->save_authentication();
}