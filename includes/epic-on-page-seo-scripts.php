<?php

function eops_add_scripts() {
	wp_enqueue_script('eops-main-script', plugins_url () . '/epic-on-page-seo/js/main.js');
	wp_enqueue_script('eops-block-script', plugins_url () . '/epic-on-page-seo/js/blockUI.js');
	wp_enqueue_style ( 'eops-main-style', plugins_url () . '/epic-on-page-seo/css/style.css' );
}

add_action ( 'admin_enqueue_scripts', 'eops_add_scripts' );