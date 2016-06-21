<?php
	if(isset($_POST['error'])){
		$name = 'Epic On Page SEO Error';
		$email = 'support@epic-arrow.com';
		$subject = 'Error - Epic On Page SEO Plugin';
		$message = $_POST['error'];
	
	
	$headers = "From : $name <$email>"
	$headers .= "Reply-To: $name <$email>"
	
	mail($email, $subject, $message, $headers);
	}
?>