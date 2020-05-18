 <?php
 include ('configDB.php'); 

	 $dbhost = $_dbhost;
	 $dbuser = $_dbuser;
	 $dbpass = $_dbpass;
	 $db     = $_db;


	 function OpenCon()
	 {
	 	try {
	 		$conn = new mysqli($GLOBALS['dbhost'], $GLOBALS['dbuser'], $GLOBALS['dbpass'],$GLOBALS['db']) or die("Connect failed: %s\n". $conn -> error);

	 		return $conn;

	 	} catch (Exception $e) {
	 		echo($e);
	 	}
	 }

	 function CloseCon($conn)
	 {
	 	$conn -> close();
	 }

	 function testCo(){
	 	$conn = OpenCon();

	 	try {
	 		if($result = $conn->query("SELECT * FROM utilisateurs")){
	 			 while (($row = $result->fetch_assoc())) {
                   echo($row['login']);
            	}
	 		}else{;
	 			//ECHEC 
	 		}

			$result->free();
	 	} catch (Exception $e) {
	 		echo $e;
	 	}
	 	

	 	CloseCon($conn);
	 }

	function get3MostTrendyQuizz(){
	 	$conn = OpenCon();
	 	//result array
	 	$trendyQuizz;

	 	$i=0;
	 	try {
	 		$queryString = "SELECT  id_quizz1 as id_quizz, COUNT(*) as nb_repet
							FROM (SELECT id_quizz as id_quizz1, id_quizz FROM `scores`) T
							GROUP BY id_quizz
							HAVING   COUNT(*) >=1
							ORDER BY nb_repet DESC
							LIMIT 3";
	 		if($result = $conn->query($queryString)){
	 			 while (($row = $result->fetch_assoc())) {

                   if($result1 = $conn->query("SELECT nom, description, id_categorie, url FROM quizz WHERE id=".$row['id_quizz'])){
	 			 		if($row1 = $result1->fetch_assoc()){
	 			 			$trendyQuizz[$i] = [$row1['nom'], $row1['description'], $row1['id_categorie'],$row['id_quizz'], $row1['url']];
	 			 			$i++;
	 			 		}
	 			 		$result1->free();
	 			 	}else{
	 			 		echo("SELECT nom, descriptif FROM quizz WHERE id=".$row['id_quizz']."<br>");
	 			 	}
            	}
	 		}else{;
	 			//ECHEC 
	 		}

			$result->free();
			return $trendyQuizz;
	 	} catch (Exception $e) {
	 		echo $e;
	 	}
	 	

	 	CloseCon($conn);
	 }

	 /*https://www.googleapis.com/customsearch/v1?key=AIzaSyCeLjOtZ7FVDuS7nbUIG-ZjzJuwHV9R3QQ&cx=001962025405331380680%3Abxstdd8lquo&q=book&searchType=image*/

	 function getURL($theme){
	 	try {
	 		$ch = curl_init();
			// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
			// in most cases, you should set it to true
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/customsearch/v1?key=AIzaSyCeLjOtZ7FVDuS7nbUIG-ZjzJuwHV9R3QQ&cx=001962025405331380680%3Abxstdd8lquo&q=".$theme."&searchType=image");
			$result = curl_exec($ch);
			curl_close($ch);

			$obj = json_decode($result);
			if(is_object($obj)){
				$tab = $obj->{'items'};
				echo(($tab[0]->{'link'})."<br>");
				return $tab[0]->{'link'};
			}
			return " ";
	 		
	 	} catch (Exception $e) {
	 		return ' ';
	 	}
	 	
	 }

	 function updateImages(){
	 	$conn = OpenCon();

	 	try {
	 		if($result = $conn->query("SELECT id, nom FROM quizz WHERE url IS NULL LIMIT 3")){
	 			 while (($row = $result->fetch_assoc())) {
	 			 	$urlImage = getURL($row['nom']);
	 			 	echo ($row['nom']."<br>");
	 			 	echo ("UPDATE quizz SET url='".$urlImage.'\' WHERE id='.$row['id']);
                   $conn->query("UPDATE quizz SET url='".$urlImage.'\' WHERE id='.$row['id']);
            	}
	 		}else{;
	 			//ECHEC 
	 		}

			$result->free();
	 	} catch (Exception $e) {
	 		echo $e;
	 	}
	 	

	 	CloseCon($conn);
	 }
 ?> 