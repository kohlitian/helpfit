
<?php
	$ch = curl_init();
	$userAgent = 'Googlebot/2.1 (https://www.php.net/manual/en/reserved.variables.server.php)';
	curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);

	 $ch = curl_init();
	 curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
	 curl_setopt($ch, CURLOPT_URL,"https://reactflow.com/pricing");
	 curl_setopt($ch, CURLOPT_FAILONERROR, true);
	 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	 curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	 curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	 curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	 $html = curl_exec($ch);
	 if (!$html) {
		echo "<br />cURL error number:" .curl_errno($ch);
		echo "<br />cURL error:" . curl_error($ch);
		exit;
	}


	$dom = new DOMDocument();
	@$dom->loadHTML($html);
	$xpath = new DOMXPath($dom);
	$hrefs = $xpath->evaluate("/html/body//a");
	for ($i = 0; $i < $hrefs->length; $i++) {
	 $href = $hrefs->item($i);
	 $link = $href->getAttribute('href');
	 $text = $href->nodeValue;
	 if(empty($link) && strtolower($text) == "home"){
	 	$link = "index.php";
	 }
	     // Do what you want with the link, print it out:
	 	// echo nl2br($text." -> ".$link."\n");
	 // 	Detect /
		// If in front / == “” then add domain name
		// If in (front / != “” && != “https:” || != “http:”) || no / at all then 
		// 	detect .
		// 	if in front . == www then ignore (either own website or others website but since it have full url ady so no need to add anything)
		// 	if in front . != www then detect same .
		// 		if in front . == domain name then ignore
		// 		if in front . != domain name then detect last .
		// 			if last . == php (language) then add domain/ in front (index.php)
		// 			if last . != php (language) then I don’t know what the fuck is that 

		$splitURL = explode("/", $link);
		if(empty($splitURL[0])){
			$splitURL[0] = "https://php.net";
		} elseif(!empty($splitURL[0]) && $splitURL[0] != "https:" && $splitURL[0] != "http:") {
			// print_r($splitURL); echo "<br>";
			// print_r($splitURL[0]); echo "<br>";
			$splitDot = explode(".", $splitURL[0]);
			if($splitDot[0] != "www"){
				if($splitDot[0] != "php"){
					// echo end($splitDot); echo "<br>";
					if(end($splitDot) == "php"){
						array_unshift($splitURL, "https://php.net");
					}
					elseif(end($splitDot) == "org" || end($splitDot) == "com" || end($splitDot) == "net"){
						array_unshift($splitURL, "I Am Other Company Lo");
					}
					else {
						array_unshift($splitURL, "I Dont Know Who Am I Leh");
					}
					// echo(end($splitDot)); echo"<br>";
				} else {
					array_unshift($splitURL, "I Am Your Own Website Page Lo");
				}
			}  else {
				print_r($splitDot[1]);echo"<br>";
				if($splitDot[1] != "php"){
					array_unshift($splitURL, "I Am Also Your Own Website Lo");
				} else {
					array_unshift($splitURL, "I Am Others Website Lo");
				}
			}
			// print_r($splitDot);echo "<br/>";
		}
		// print_r($splitURL);echo "<br/>";
		// echo nl2br($splitURL[0]."\n");
	    // Or save this in an array for later processing..

	 	// echo parse_url($link, PHP_URL_PATH); echo ""

	    $links[$i]['href'] = $link;
	    $links[$i]['text'] = $text;    

	} 


 ?>

