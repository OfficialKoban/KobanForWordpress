<?php
/*
Plugin Name: Koban Marketing
Plugin URI : http://www.koban-marketing.com
Description : Plugin Wordpress Koban Marketing. G�rez efficacement vos pages d'atterrissage, vos formulaires et votre Marketing Automation. Engagez vos visiteurs et r�cup�rez des leads qualifi�s.
Version : 0.2
Author : Koban
Author URI : http://www.koban.cloud
*/
class Koban_Marketing
{
	public function __construct()
    {
		include_once plugin_dir_path( __FILE__ ).'/km-main.php';
		new km_Main();
    }
}

new Koban_Marketing();
?>