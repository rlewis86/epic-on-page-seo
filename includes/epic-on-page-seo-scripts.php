<?php

function eops_add_scripts() {
	wp_enqueue_script('eops-main-script', plugins_url () . '/epic-on-page-seo/js/main.js?v=1.1.3');
	wp_enqueue_script('eops-block-script', plugins_url () . '/epic-on-page-seo/js/blockUI.js?v=1.1.3');
	wp_enqueue_style ( 'eops-main-style', plugins_url () . '/epic-on-page-seo/css/style.css?v=1.1.3' );
}

add_action ( 'admin_enqueue_scripts', 'eops_add_scripts' );