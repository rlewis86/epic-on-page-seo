<?php
/*
 * File sets up the main dashboard for the plugin.
 */

// Create Dashboard Link
function opseoa_options_dashboard_link() {
	add_menu_page ( 'Epic On Page SEO', 'Epic On Page SEO ', 'manage_options', 'epic-on-page-seo', 'opseoa_dashboard_widget_function', plugins_url () . '/epic-on-page-seo/img/Arrow.png' );
}

add_action ( 'admin_menu', 'opseoa_options_dashboard_link' );

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function opseoa_dashboard_widget_function() {
	// Get plugin form
	get_main_form ();
}

function get_main_form() {
	global $eopseo_options;
	?>
<div class="wrapper">
<div style="float: left"><h1 style="vertical-align: top">Epic On Page SEO</h1></div>
<div style="float:right; margin-right:20px; margin-top:15px">For bug reports and feature requests visit: <a target="_blank" href="http://www.epic-arrow.com/support/">www.epic-arrow.com/support</a> or email us directly at <a href="support@epic-arrow.com">support@epic-arrow.com</a></div>
<br>
<br>
<br>
<label id="lblApiKey" value="<?php echo esc_attr($eopseo_options['api_key']); ?>"></label>
<label id="lblSearchEngineKey" value="<?php echo esc_attr($eopseo_options['search_engine_id']); ?>"></label>
	<p>
	Enter a keyword and hit submit to reveal your competitors on-page seo.
	</p>
	<form id="frmKeyword" method="post" action="<?php echo plugins_url().'/epic-on-page-seo/includes/epic-on-page-seo-scraper.php' ?>">
	<p><label id="lblError" class="error" value=""></label></p>
		<p style="vertical-align: middle">
			Keyword: <input type="text" id="txtkeyword" style="width:300px;" required> <input type="submit" id="btnSubmitKeyword" class="button-primary" style="margin-left: 10px">
		</p>
			
	</form>

	<div id="tables-group" style="display: none">
<!-- 		<p> -->
<!-- 			<input id="btnExcelExport" type="button" value="Export to Excel" -->
<!--  			class="button-primary" style="float: right; margin-right: 30px;" />-->	
<!-- 		</p> -->
		<p>
			<strong><span style="font-size: large; color: #006699;">Top 10
					Results - Page Url's</span></strong>
		</p>

		<table id="tblSites" class="widefat">
				<tr style="outline: thin solid">
					<th style="background: #0073aa; color: white; width: 70%; font-weight:bold;">Page
							URLs</th>
					<th style="background: #0073aa; color: white; text-align: center;font-weight:bold;">Internal
							Links</th>
					<th style="background: #0073aa; color: white; text-align: center; font-weight:bold;">External
							Links</th>
				</tr>

		</table>

		<p>
			<strong><span style="font-size: large; color: #006699;">Meta
					Keywords</span></strong>
		</p>

		<table id="tblMetaKeywords" class="widefat">
				<tr style="outline: thin solid">
					<th style="background: #0073aa; color: white; font-weight:bold;width: 40%">Page URL</th>
					<th style="background: #0073aa; color: white; font-weight:bold;">Meta-Keywords</th>
				</tr>
		</table>

		<p>
			<strong><span style="font-size: medium; color: #006699;">Meta
					Description</span></strong>
		</p>

		<table id="tblMetaDescription" class="widefat">
				<tr style="outline: thin solid">
					<th style="background: #0073aa; color: white; font-weight:bold; width: 40%">Page URL</th>
					<th style="background: #0073aa; color: white; font-weight:bold;">Meta-Description</th>
				</tr>
		</table>

		<p>
			<strong><span style="font-size: large; color: #006699;">Page Title</span></strong>
		</p>

		<table id="tblPageTitle" class="widefat">
				<tr style="outline: thin solid">
					<th style="background: #0073aa; color: white; font-weight:bold; width: 40%">Page URL</th>
					<th style="background: #0073aa; color: white; font-weight:bold;">Page Title</th>
				</tr>
		</table>

		<p>
			<strong><span style="font-size: large; color: #006699;">Page H1</span></strong>
		</p>

		<table id="tblPageH1" class="widefat">
				<tr style="outline: thin solid">
					<th style="background: #0073aa; color: white; font-weight:bold; width: 40%">Page URL</th>
					<th style="background: #0073aa; color: white; font-weight:bold;">H1</th>
				</tr>
		</table>

		<p>
			<strong><span style="font-size: large; color: #006699;">Page H2</span></strong>
		</p>

		<table id="tblPageH2" class="widefat">
				<tr style="outline: thin solid">
					<th style="background: #0073aa; color: white; font-weight:bold; width: 40%">Page URL</th>
					<th style="background: #0073aa; color: white; font-weight:bold;">H2</th>
				</tr>
		</table>

		<p>
			<strong><span style="font-size: large; color: #006699;">Page H3</span></strong>
		</p>

		<table id="tblPageH3" class="widefat">
				<tr style="outline: thin solid">
					<th style="background: #0073aa; color: white; font-weight:bold; width: 40%">Page URL</th>
					<th style="background: #0073aa; color: white; font-weight:bold;">H3</th>
				</tr>
		</table>

		<p>
			<strong><span style="font-size: large; color: #006699;">Image Alt Tags</span></strong>
		</p>

		<table id="tblImgAlts" class="widefat">
				<tr style="outline: thin solid">
					<th style="background: #0073aa; color: white; font-weight:bold; width: 40%">Page URL</th>
					<th style="background: #0073aa; color: white; font-weight:bold;">Image Alt Tags</th>
				</tr>
		</table>

		<p>
			<strong><span style="font-size: large; color: #006699;">Page Word Count</span></strong>
		</p>

		<table id="tblPageWords" class="widefat">
			<thead>
				<tr style="outline: thin solid">
					<th style="background: #0073aa; color: white; font-weight:bold; width: 40%">Page URL</th>
					<th style="background: #0073aa; color: white; font-weight:bold;">Page Word Count</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		
	</div>



</div>
<?php
}
