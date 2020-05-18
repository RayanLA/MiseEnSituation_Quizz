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

                   if($result1 = $conn->query("SELECT nom, description, id_categorie FROM quizz WHERE id=".$row['id_quizz'])){
	 			 		if($row1 = $result1->fetch_assoc()){
	 			 			$trendyQuizz[$i] = [$row1['nom'], $row1['description'], $row1['id_categorie'],$row['id_quizz'] ];
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

 ?> 