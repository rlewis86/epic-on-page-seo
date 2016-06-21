<?php
/**
* Plugin Name: Epic On Page SEO
* Description: Spy on exactly what your competitors are using for their on page SEO tactics!
* Version: 1.4
* Author: Epic Arrow
* Author URI: http://www.epic-arrow.com
* Plugin URI: http://www.epic-arrow.com/epic-on-page-seo-plugin/
* Text Domian: epic-on-page-seo
**/

// Exit if Accessed Directly

if (!defined('ABSPATH')){
exit;
}

//Global Options Variable

$eopseo_options = get_option('eopseo_settings');

// Load Scripts
require_once(plugin_dir_path(__FILE__).'includes/epic-on-page-seo-scripts.php');

//Load Simple HTML Dom Pop
require_once(plugin_dir_path(__FILE__).'includes/simple_html_dom.php');

if(is_admin()){
// Load Dashboard 
require_once(plugin_dir_path(__FILE__).'includes/epic-on-page-seo-main.php');

//Load Settings
 require_once(plugin_dir_path(__FILE__).'includes/epic-on-page-seo-settings.php');
}

// Github updater
require plugin_dir_path(__FILE__). 'includes/plugin-update-checker/plugin-update-checker.php';
$className = PucFactory::getLatestClassVersion('PucGitHubChecker');
$myUpdateChecker = new $className(
	'https://github.com/rlewis86/epic-on-page-seo',
	__FILE__,
	'master'
);

