<?php
/*
 * File pulls back top 10 results from Google then grabs tags/meta data from each result.
 */
header ( "Content-type:text/html;charset=utf8" );
require_once ('simple_html_dom.php');


if ($_SERVER ['REQUEST_METHOD'] == "POST") {
	try {
		if (isset ( $_POST ['keyword'] ) && isset ( $_POST ['urls'] )) {
			// Get Urls
			$urls = $_POST ['urls'];
			
			// Get Keyword From Server
			$keyword = strip_tags ( trim ( $_POST ['keyword'] ) );
			
			get_website_page_data ( $urls, $keyword );
		} else {
			
			// Get Keyword From Server
			$keyword = strip_tags ( trim ( $_POST ['keyword'] ) );
			
			// Make Keyword Google Friendly
			$keyword = str_replace ( ' ', '+', $keyword );
			
			get_search_results ( $keyword );
		}
	}
	catch(Exception $e) {
		$error = $e->getMessage();
		error_log($error, 1, "support@epic-arrow.com");
		echo "An Error Has Occured. Please Try Again Later or Contact Support";
	}
}
function get_search_results($keyword) {
	try{
		$url = 'http://www.google.com/search?hl=en&safe=active&tbo=d&site=&source=hp&q=' . $keyword . '&oq=' . $keyword;
		$html = file_get_html ( $url );
		$linkObjs = $html->find ( 'h3.r a' );
		foreach ( $linkObjs as $linkObj ) {
			$link = trim ( $linkObj->href );
			
			// if it is not a direct link but url reference found inside it, then extract
			if (! preg_match ( '/^https?/', $link ) && preg_match ( '/q=(.+)&amp;sa=/U', $link, $matches ) && preg_match ( '/^https?/', $matches [1] )) {
				$link = $matches [1];
			} else if (! preg_match ( '/^https?/', $link )) { // skip if it is not a valid link
				continue;
			}
			
			if (! empty ( $link )) {
				if (! empty ( $links )) {
					$links .= '`' . $link;
				} else {
					$links = $link;
				}
			}
			// debug_to_console($linkObj);
		}
		
		http_response_code ( 200 );
		echo $links;
	} catch(Exception $e) {
		$error = $e->getMessage();
		error_log($error, 1, "support@epic-arrow.com");
		echo "An Error Has Occured. Please Try Again Later or Contact Support";
		
	}
	
}
function get_website_page_data($urls, $keyword) {
	try {
		$data = array ();
		foreach ( $urls as $url ) {
			// get start time for speed test
			$start_request = microtime ( true );
			
			// fetch html
			$html = file_get_html_code ( $url );
			
			// load html doc
			$doc = new DOMDocument ();
			@$doc->loadHTML ( $html );
			
			// get end time for speed test
			$end_request = microtime ( true );
			
			// get difference for b/w start and end request for speed test
			$speed = round ( $end_request - $start_request, 2 );
			
			// get links
			$page_links = $doc->getElementsByTagName ( 'a' );
			
			$pUrl = parse_url ( $url );
			$num_external_links = 0;
			$num_internal_links = 0;
			foreach ( $page_links as $page_link ) {
				// exclude if not link
				if (! $page_link->hasAttribute ( 'href' )) {
					continue;
				} else {
					$href = $page_link->getAttribute ( 'href' );
				}
				
				if (substr ( $href, 0, 2 ) === '//') {
					// Deal with protocol relative URLs as found on Wikipedia
					$href = $pUrl ['scheme'] . ':' . $href;
				}
				
				$pHref = @parse_url ( $href );
				if (! $pHref || ! isset ( $pHref ['host'] ) || strtolower ( $pHref ['host'] ) === strtolower ( $pUrl ['host'] )) {
					$num_internal_links ++;
				} else {
					$num_external_links ++;
				}
			}
			
			// get title
			$title_node = $doc->getElementsByTagName ( 'title' );
			
			// check to see if title tag exists
			if (isset ( $title_node->item ( 0 )->nodeValue )) {
				// get title tag
				$title = $title_node->item ( 0 )->nodeValue;
			} else {
				$title = "Title Not Found";
			}
			
			// get metas
			$metas = $doc->getElementsByTagName ( 'meta' );
			$meta_description = '';
			$meta_keywords = '';
			for($i = 0; $i < $metas->length; $i ++) {
				$meta = $metas->item ( $i );
				// get meta description
				if ($meta->getAttribute ( 'name' ) == 'description')
					$meta_description = $meta->getAttribute ( 'content' );
					$meta_description = trim($meta_description);
					// get meta keywords
				if ($meta->getAttribute ( 'name' ) == 'keywords')
					$meta_keywords = $meta->getAttribute ( 'content' );
					$meta_keywords = trim($meta_keywords);
					// check to see if meta keywords were used
				if (! isset ($meta_keywords ) || empty ($meta_keywords )) {
					$meta_keywords = "Meta Keywords Not Used";
				}
				
				// check to see if meta description was used
				if (! isset ( $meta_description ) || empty ( $meta_description ) ) {
					$meta_description = "Meta Description Not Used";
				}
			}
			
			// get h1 node
			$h1_node = $doc->getElementsByTagName ( 'h1' );
			
			// check to see h1 exists
			if (isset ( $h1_node->item ( 0 )->nodeValue ) && ! empty ( $h1_node->item ( 0 )->nodeValue )) {
				// get h1 tag
				$h1 = trim ( $h1_node->item ( 0 )->nodeValue );
			} else {
				$h1 = "H1 Tag Not Used";
			}
			
			// get h2 node
			$h2_node = $doc->getElementsByTagName ( 'h2' );
			
			// check to see h2 exists
			if (isset ( $h2_node->item ( 0 )->nodeValue ) && ! empty ( $h2_node->item ( 0 )->nodeValue )) {
				// get h2 tag
				$h2 = strip_tags ( trim ( $h2_node->item ( 0 )->nodeValue ) );
			} else {
				$h2 = "H2 Tag Not Used";
			}
			
			$h3_node = $doc->getElementsByTagName ( 'h3' );
			
			// check to see h3 exists
			if (isset ( $h3_node->item ( 0 )->nodeValue ) && ! empty ( $h3_node->item ( 0 )->nodeValue )) {
				// get h3 tag
				$h3 = strip_tags ( trim ( $h3_node->item ( 0 )->nodeValue ) );
			} else {
				$h3 = "H3 Tag Not Used";
			}
			
			// get images
			$images = $doc->getElementsByTagName ( 'img' );
			$img_alt = '';
			for($i = 0; $i < $images->length; $i ++) {
				$image = $images->item ( $i );
				
				$get_image=trim($image->getAttribute ('alt'));
				if(!empty($get_image)) {
					$img_alt .= $image->getAttribute ( 'alt' ) . ", ";
				}
			}
			
	
			
			$img_alt = trim ( $img_alt, "," );
			
			// get page body text
			$body = strip_html_tags ( $html );
			
			// get word count
			$body_word_count = str_word_count ( $body );
			if ($body_word_count > 0) {
				
				$body_keyword_density = substr_count ( strtolower ( $body ), strtolower ( $keyword ) );
				if ($body_keyword_density !== 0) {
					$body_keyword_density_percentage = $body_keyword_density / $body_word_count;
					$body_keyword_density_percentage = $body_keyword_density_percentage * 100;
					$body_keyword_density_percentage = round ( $body_keyword_density_percentage, 2 );
				} else {
					$body_keyword_density_percentage = 0;
				}
			} else {
				$body_keyword_density_percentage = 'Error Retrieving Keyword Density';
				$body_word_count = 'Error Retrieving Page Word Count';
			}
			
			if (empty ( $data )) {
				$data [] = array (
						'title' => "$title",
						'metaKeywords' => "$meta_keywords",
						'metaDescription' => "$meta_description",
						'h1' => "$h1",
						'h2' => "$h2",
						'h3' => "$h3",
						'word-count' => "$body_word_count",
						'keywordDensity' => "$body_keyword_density_percentage" . "%",
						'keywordCount' => "$body_word_count",
						'imgAlts' => "$img_alt",
						'internalLinks' => "$num_internal_links",
						'externalLinks' => "$num_external_links",
						'websiteSpeed' => "$speed" 
				);
			} else {
				array_push ( $data, array (
						'title' => "$title",
						'metaKeywords' => "$meta_keywords",
						'metaDescription' => "$meta_description",
						'h1' => "$h1",
						'h2' => "$h2",
						'h3' => "$h3",
						'word-count' => "$body_word_count",
						'keywordDensity' => "$body_keyword_density_percentage" . "%",
						'keywordCount' => "$body_word_count",
						'imgAlts' => "$img_alt",
						'internalLinks' => "$num_internal_links",
						'externalLinks' => "$num_external_links",
						'websiteSpeed' => "$speed" 
				) );
			}
		}
		
		http_response_code ( 200 );
		echo json_encode ( $data, JSON_PRETTY_PRINT );
	} catch(Exception $e) {
		$error = $e->getMessage();
		error_log($error, 1, "support@epic-arrow.com");
		echo "An Error Has Occured. Please Try Again Later or Contact Support";
	}
}

// Grabs html from sites
function curl_get_contents_data($url) {
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt ( $ch, CURLOPT_VERBOSE, true );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13' );
	
	if (curl_exec($ch) === FALSE) {
		$data = "Curl Failed: " . curl_error($ch);
			echo '<pre>';
        		print_r($val);
        		echo  '</pre>';
	} else {
   		$data = curl_exec ($ch);
	}

	curl_close ( $ch );
	return $data;
}

// Sets up request to grab html from sites
function file_get_html_code() {
		$dom = new simple_html_dom ();
		$args = func_get_args ();
		$dom->load ( call_user_func_array ( 'curl_get_contents_data', $args ), true );
		return $dom;
}
function strip_html_tags($text) {
	// Remove invisible content
	$text = preg_replace ( array (
			// ADD a (') before @<head ON NEXT LINE. Why? see below
			'@<head[^>]*?>.*?</head>@siu',
			'@<style[^>]*?>.*?</style>@siu',
			'@<script[^>]*?.*?</script>@siu',
			'@<object[^>]*?.*?</object>@siu',
			'@<embed[^>]*?.*?</embed>@siu',
			'@<applet[^>]*?.*?</applet>@siu',
			'@<noframes[^>]*?.*?</noframes>@siu',
			'@<noscript[^>]*?.*?</noscript>@siu',
			'@<noembed[^>]*?.*?</noembed>@siu',
			// Add line breaks before and after blocks
			'@</?((address)|(blockquote)|(center)|(del))@iu',
			'@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
			'@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
			'@</?((table)|(th)|(td)|(caption))@iu',
			'@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
			'@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
			'@</?((frameset)|(frame)|(iframe))@iu' 
	), array (
			' ',
			' ',
			' ',
			' ',
			' ',
			' ',
			' ',
			' ',
			' ',
			"\n\$0",
			"\n\$0",
			"\n\$0",
			"\n\$0",
			"\n\$0",
			"\n\$0",
			"\n\$0",
			"\n\$0" 
	), $text );
	return strip_tags ( $text );
}
