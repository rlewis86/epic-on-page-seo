<?php

function eops_settings_sub_menu_link() {
add_submenu_page('epic-on-page-seo', 'Epic One Page SEO Settings', 'Settings', 'manage_options', 'epic-on-page-seo-settings', 'eops_sub_menu_widget' );
}

add_action ( 'admin_menu', 'eops_settings_sub_menu_link' );

function eops_sub_menu_widget() {

	//Get sub menu form
	get_sub_menu_form();
}

//Register Settings
function eopseo_register_settings(){
	register_setting('eopseo_settings_group', 'eopseo_settings');
}

add_action('admin_init', 'eopseo_register_settings');

function get_sub_menu_form() {

//Init Options Global
global $eopseo_options;

	?>
<div class="wrapper">
	<h1>Epic On Page SEO Settings</h1>
<p>For instructions visit: <a target="_blank" href="http://www.epic-arrow.com/plugin-installation/">http://www.epic-arrow.com/plugin-installation/</a></p>
<form id="frmOPSEASettings" method="post" action="options.php">
<?php settings_fields('eopseo_settings_group'); ?>
<?php do_settings_sections('eopseo_settings_group'); ?>
<p>
<label for="api_key">API Key</label>
<br>
 <input type="text" name="eopseo_settings[api_key]"  style="width:250px" value="<?php echo esc_attr($eopseo_options['api_key']); ?>">
</p>
<p>
Search Engine ID
<br>
<input type="text" name="eopseo_settings[search_engine_id]" id="eopseo_settings[SearchEngineID]" style="width:250px" value="<?php echo esc_attr($eopseo_options['search_engine_id']); ?>">
</p>
<p>
<input type="submit" id="btnSubmit" value="Save" class="button-primary">
</p>
</form>
</div>
<?php 

}
