
<?php
	$ch = curl_init();
	// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
	// in most cases, you should set it to true
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/customsearch/v1?key=AIzaSyCeLjOtZ7FVDuS7nbUIG-ZjzJuwHV9R3QQ&cx=001962025405331380680%3Abxstdd8lquo&q=book&searchType=image");
	$result = curl_exec($ch);
	curl_close($ch);

	$obj = json_decode($result);
	$tab = $obj->{'items'};
	$url = $tab[0]->{'link'};
	var_dump($tab[0]->{'link'});
?>

