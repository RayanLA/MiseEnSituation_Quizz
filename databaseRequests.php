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

	 function phpAlert($msg) {
		echo '<script type="text/javascript">alert("' . $msg . '")</script>';
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
	 	//echo("theme : ".$theme);
	 	try {
	 		$ch = curl_init();
			// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
			// in most cases, you should set it to true
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$url  = "https://www.googleapis.com/customsearch/v1?key=AIzaSyCeLjOtZ7FVDuS7nbUIG-ZjzJuwHV9R3QQ&cx=001962025405331380680%3Abxstdd8lquo&q='".urlencode($theme)."'&searchType=image";
			curl_setopt($ch, CURLOPT_URL, $url);
			$result = curl_exec($ch);
			/*var_dump($result);
			var_dump($url);*/
			curl_close($ch);

			$obj = json_decode($result);
			//var_dump($obj);
			if(is_object($obj)){
				$tab = $obj->{'items'};
				//echo(($tab[0]->{'link'})."<br>");
				return $tab[0]->{'link'};
			}
			return "https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/1024px-No_image_available.svg.png";
	 		
	 	} catch (Exception $e) {
	 		return 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/1024px-No_image_available.svg.png';
	 	}
	 	
	 }

	 function updateImages(){
	 	$conn = OpenCon();

	 	try {
	 		if($result = $conn->query("SELECT id, nom FROM quizz WHERE url IS NULL LIMIT 3")){
	 			 while (($row = $result->fetch_assoc())) {
	 			 	$urlImage = getURL($row['nom']);
	 			 	//echo ($row['nom']."<br>");
	 			 	//echo ("UPDATE quizz SET url='".$urlImage.'\' WHERE id='.$row['id']);
                   $conn->query("UPDATE quizz SET url='".$urlImage.'\' WHERE id='.$row['id']);
            	}
            	$result->free();
	 		}else{;
	 			//ECHEC 
	 		}
	 	} catch (Exception $e) {
	 		echo $e;
	 	}
	 	

	 	CloseCon($conn);
	 }

	function verifyIdentity($login, $mdp){
	 	try {
	 		$bd = OpenCon();
	 		if ($stmt = $bd->prepare("SELECT COUNT(id) FROM utilisateurs WHERE login=? AND mdp=?")){

	 			$stmt->bind_param("ss", $_SESSION['login'], $_POST['password1']);
	 			$stmt->execute();
	 			$count_result = 0; 
	 			$stmt->bind_result($count_result);
	 			$stmt->fetch();

	 			CloseCon($bd);
	 			//var_dump("Result dbRequest : ".$count_result."<br>");
	 			return $count_result;
	 			
	 		}
	 	} catch (Exception $e) {
	 		echo $e;
	 		return 0;
	 	}
        return 0;
	 }


	function getQuizzInfo($idQuizz, $idCategorie){
		try {
			$conn = OpenCon();

			$requestSQL = "SELECT q.nom as nom, q.date_creation as date_creation, q.description as description, q.url as url, c.nom as nomCategorie 
						FROM quizz q, categories c
						 WHERE c.id=q.id_categorie AND q.id=".$idQuizz;
			
			/*var_dump($requestSQL);*/

			if($result = $conn->query($requestSQL)){
	 			 while (($row = $result->fetch_assoc())) {
	 			 	return $row;
            	}
            	$result->free();
	 		}else{;
	 			//ECHEC 
	 		}

			CloseCon($conn);
		} catch (Exception $e) {
			echo $e;	
		}
	}

	function getScoreBoard($idQuizz){
		try {
			$res = [];$i=0;
			$conn = OpenCon();
			
			$requestSQL = "SELECT u.login, s.score 
						   FROM quizz q, scores s, utilisateurs u 
						   WHERE q.id=s.id_quizz AND u.id=s.id_utilisateur AND q.id=".$idQuizz." 
						   ORDER BY s.score DESC LIMIT 5";

			/*var_dump($requestSQL);*/

			if($result = $conn->query($requestSQL)){
	 			 while (($row = $result->fetch_assoc())) {
	 			 	$res[$i] = $row; $i++;
            	}
            	$result->free();
	 		}else{;
	 			//ECHEC 
	 		}
			CloseCon($conn);
		} catch (Exception $e) {
			echo $e;	
		}
		return $res;
	}

	function getUserScore($id_utilisateur, $idQuizz){
		try {
			$conn = OpenCon();
			
			$requestSQL = "SELECT s.score as score
						   FROM utilisateurs u, scores s, quizz q 
						   WHERE s.id_utilisateur=u.id AND q.id=s.id_quizz AND 
						   		 u.id=".$id_utilisateur." AND q.id=".$idQuizz;

			/*var_dump($requestSQL);*/

			if($result = $conn->query($requestSQL)){
	 			 while (($row = $result->fetch_assoc())) {
	 			 	$result->free();
	 			 	return $row['score'];
            	}
            	
	 		}else{;
	 			//ECHEC 
	 		}
			CloseCon($conn);
			return null;
		} catch (Exception $e) {
			echo $e;	
			return null;
		}
	}

	function getExistingCategories(){
		try {
			$res=[];$i=0;

			$conn = OpenCon();
			$requestSQL = "SELECT nom, id 
						   FROM categories";

			if($result = $conn->query($requestSQL)){
	 			 while (($row = $result->fetch_assoc())) {
	 			 	$res[$i] = $row; $i++;
            	}
            	$result->free();
            }

			CloseCon($conn);
			return $res;

		} catch (Exception $e) {
			return [];
		}
	}

	function createquizz($conn, $nomQuizz, $description, $url ){
		/*var_dump("
					INSERT INTO quizz (id_createur, nom, id_categorie, description, url)
			 		VALUES (".$_SESSION['idUtilisateur'].", ".$nomQuizz.", ".$_POST['categorie'].", ".$description.", ".$url.")");*/
		try {
			$SQLRequest = "
					INSERT INTO quizz (id_createur, nom, id_categorie, description, url)
			 		VALUES (".$_SESSION['idUtilisateur'].", ?, ".$_POST['categorie'].", ?, ?)";

			if ($stmt = $conn->prepare($SQLRequest)){
	 			$stmt->bind_param("sss", $nomQuizz, $description, $url);
	 			$stmt->execute();
	 			$stmt->fetch();
	 		}
		} catch (Exception $e) {
			var_dump($e);
		}
	}

	function createQuestion($conn, $question, $idQuizz){
		/*var_dump("
					INSERT INTO questions (id_quizz, question)
			 		VALUES (".$idQuizz.", ".$question.")");*/
		try {
			$SQLRequest = "
					INSERT INTO questions (id_quizz, question)
			 		VALUES (".$idQuizz.", ?)";

			if ($stmt = $conn->prepare($SQLRequest)){
	 			$stmt->bind_param("s", $question);
	 			$stmt->execute();
	 			$stmt->fetch();
	 		}
		} catch (Exception $e) {
			var_dump($e);
		}
	}

	function createAnswer($conn, $reponse, $correct, $idQuestion){
		/*var_dump("
					INSERT INTO reponses (id_question, reponse, correct)
			 		VALUES (".$idQuestion.", ".$reponse.", ".(int)($correct=="true").")");*/
		try {
			$SQLRequest = "
					INSERT INTO reponses (id_question, reponse, correct)
			 		VALUES (".$idQuestion.", ?, ".(int)($correct=="true").")";

			if ($stmt = $conn->prepare($SQLRequest)){
	 			$stmt->bind_param("s", $reponse);
	 			$stmt->execute();
	 			$stmt->fetch();
	 		}
		} catch (Exception $e) {
			var_dump($e);
		}
	}

	function getLatestId($conn){
		$requestSQL = "SELECT LAST_INSERT_ID() as res";
		if($result = $conn->query($requestSQL)){
			while (($row = $result->fetch_assoc())) {
				$result->free();
				return $row['res'];
			}
		}else{;
	 			//ECHEC 
		}
	}

	function addQuizz()
	{
		try {

			$conn = OpenCon();
			
			createquizz($conn, $_POST['nomQuizz'], $_POST['description'], $_POST['url']);
			$latestIdQuizz = getLatestId($conn);

			$array = $_POST['quizzData'];

			for($i=0; $i<count($array); $i++){
				$aQuestion = $array[$i];
				$theQuestion = $aQuestion['question'];

				createQuestion($conn, $theQuestion, $latestIdQuizz);
				$latestIdQuestion = getLatestId($conn);
				
				foreach ($aQuestion['reponses']['reponse'] as $id => $value) {
					$reponse   = $value;
					$isCorrect = $aQuestion['reponses']['correct'][$id];
					createAnswer($conn, $reponse, $isCorrect, $latestIdQuestion);
				}
			}

			CloseCon($conn);

			return [$_POST['categorie'], $latestIdQuizz];

		} catch (Exception $e) {
			var_dump($e);
			return [];
		}
		return [];
  }

  function prepareDataForQuizzCreation(){
  	$aray = [];
  	foreach ($_POST as $name => $val)
	{
		/*Question*/
		if( substr($name, 0, strlen("question_")) == "question_" ){
			$array[substr($name, strlen("question_"), strlen($name))]["question"] = $val;
			unset($_POST[$name]);
		}

		/*reponses cq_X_Y */
		if( substr($name, 0, strlen("cq_")) == "cq_" ){
			$reste = substr($name, strlen("cq_"), strlen($name));
			$pos = strpos($reste, "_");
			if($pos !== false){
				$qID = substr($reste, 0, $pos);
				$rID = substr($reste, $pos+1, strlen($reste));
				$array[$qID]["reponses"]["correct"][$rID] = $val; 
			}
			unset($_POST[$name]);
		}

		/*is a correct answer : q_iqQuestion_idReponse*/
		if( substr($name, 0, strlen("q_")) == "q_" ){
			$reste = substr($name, strlen("q_"), strlen($name));
			$pos = strpos($reste, "_");
			if($pos !== false){
				$qID = substr($reste, 0, $pos);
				$rID = substr($reste, $pos+1, strlen($reste));
				$array[$qID]["reponses"]["reponse"][$rID] = $val; 
			}
			unset($_POST[$name]);
		}
	}
	$_POST["quizzData"] = $array;
  }




	function generateCardQuizz($row){
		echo("<div class=\"col-md-6\">");
      echo("<div class=\"row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative\">");
      echo("<div class=\"col p-4 d-flex flex-column position-static\">");
      echo("<strong class=\"d-inline-block mb-2 text-primary\">".$row["cnom"]."</strong>");
      

      echo("<h3 class=\"mb-0\">".$row["qnom"]."</h3>");

      /*Start  nb résultat */
      echo '<p class="card-text mb-auto"> Ce quizz comporte ';
      $conn = OpenCon();
      $query = "SELECT count(question)  FROM questions WHERE id_quizz=".$row['id_quizz']." ";
      $result1 = mysqli_query($conn,$query) or die (mysqli_error());
      $resultat=mysqli_fetch_row($result1);
      echo $resultat[0]. ' questions</p>';
      CloseCon($conn);
      /*End */


      
      echo("<div class=\"mb-1 text-muted\">".$row["crea"]."</div>");
      echo("<p class=\"mb-auto\">".$row["description"]."</p>");

      echo('<form action="quizz.php" method="post">
          <input type="text" name="idQuizz" value="'.$row["id_quizz"].'" style="display:none">
          <input type="text" name="idCategorie" value="'.$row["id_categorie"].'" style="display:none">
          <span class="stretched-link link" onclick="validateForm(this)">Tester mes connaissances</span>
        </form>');

      echo("</div>");
      echo("<div class=\"col-auto d-none d-lg-block\">");
      echo("<img class=\"bd-placeholder-img thumbnailImage\" width=\"200\" height=\"250\" focusable=\"false\" role=\"img\" aria-label=\"Placeholder: Thumbnail\" src='".$row["url"]."'></img>");
      echo("</div>");
      echo("</div>");
      echo("</div>");
	}


	function JS_Redirect($url){
		echo ' <script type="text/javascript">
            window.location.replace("'.$url.'");
        </script>' ;
	}

	function JS_RedirectSubscription($url){
		echo ' 
				<form action="'.$url.'" method="post">
					<input type="hidden" name="inscription" value="1">
					<button type="submit" id="buttonForm">
    			</form>
    			<script type="text/javascript">
    				$("#buttonForm").click();
    			</script>';
	}

	function openModalAuth(){
		echo '<script type="text/javascript">
					$( document ).ready(function(){
						$("#modalAuth").click();
						$("#inscrivezVous").hide();
						$("#inscriptionMessage").removeClass("hide");
					});	
    			</script>';

    	unset($_POST['inscription']);
	}

 ?> 


