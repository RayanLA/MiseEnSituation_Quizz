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

	 function connexionSuccessAlert() {
		echo "<script> $(document).ready(function (){
			$('.toast').toast('show');
		});
		 </script> ";
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

          <input type="text" name="idQuizz" value="'.$row["id_quizz"].'" class="hide">
          <input type="text" name="idCategorie" value="'.$row["id_categorie"].'" class="hide">
          <span class="stretched-link link pointeur" onclick="validateForm(this)">Tester mes connaissances</span> 
          <div class="svgShare" >
	          <span class="shareButton" onclick="openModalShare(\'Q\', '.$row["id_categorie"].', '.$row["id_quizz"].', \''.$row["qnom"].'\')">
		          <svg class="bi bi-box-arrow-up " viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
		          <path fill-rule="evenodd" d="M4.646 4.354a.5.5 0 0 0 .708 0L8 1.707l2.646 2.647a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 0 0 0 .708z"/>
		          <path fill-rule="evenodd" d="M8 11.5a.5.5 0 0 0 .5-.5V2a.5.5 0 0 0-1 0v9a.5.5 0 0 0 .5.5z"/>
		          <path fill-rule="evenodd" d="M2.5 14A1.5 1.5 0 0 0 4 15.5h8a1.5 1.5 0 0 0 1.5-1.5V7A1.5 1.5 0 0 0 12 5.5h-1.5a.5.5 0 0 0 0 1H12a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5H4a.5.5 0 0 1-.5-.5V7a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 0 0-1H4A1.5 1.5 0 0 0 2.5 7v7z"/>
		          </svg>
	          </span>
          </div>

        </form>');
      echo '';
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


	function getNbPlayedQuizzPerCategorie(){
		/*Le nb de quizz joué par catégorie*/
		try {
			$conn = OpenCon();
			$array = [];
			$queryString = "SELECT  *, COUNT(*) as nb_repet 
							FROM (
							    SELECT C.nom as nom
							    FROM `scores` as S, utilisateurs as U, categories as C, quizz as Q
								WHERE S.id_utilisateur = U.id AND U.id = ".$_SESSION['idUtilisateur']." AND Q.id = S.id_quizz AND C.id = Q.id_categorie ) T 
							GROUP BY nom 
							HAVING   COUNT(*) >=1 
							ORDER BY nb_repet DESC";

			if($result = $conn->query($queryString)){
	 			 while (($row = $result->fetch_assoc())) {
	 			 	$array[$row['nom']] = $row['nb_repet'];
            	}
				$result->free();
				CloseCon($conn);
			}

			return $array;
			
		} catch (Exception $e) {
			var_dump($e);
			return [];
		}

	}

	function getPlayedQuizzScore(){
		/*Le nombre de bonne réponse par quizz en pourcentage*/
		try {
			$conn = OpenCon();
			$array = [];
			$queryString = "SELECT quizz, nb_repet, score, S.id_quizz        
							FROM (SELECT  *, COUNT(*) as nb_repet 
							        FROM (SELECT id_quizz, Q.nom as quizz, K.question as Question, reponse  
							                    FROM questions as K, reponses as R, quizz as Q
							                    WHERE Q.id = K.id_quizz and R.id_question = K.id) as S
							        GROUP BY id_quizz 
							        HAVING   COUNT(*) >=1 
							        ORDER BY nb_repet DESC) as R, 
							        scores as S 
							WHERE S.id_quizz = R.id_quizz AND S.id_utilisateur = ".$_SESSION['idUtilisateur'];

			if($result = $conn->query($queryString)){
	 			 while (($row = $result->fetch_assoc())) {
	 			 	$array[$row['quizz']] = [$row['id_quizz'],(int)(($row['score']/$row['nb_repet'])*100)];
            	}
				$result->free();
				CloseCon($conn);
			}
			
			return $array;
			
		} catch (Exception $e) {
			var_dump($e);
			return [];
		}
	}

	function getNbOfCreatedQuizz(){
		try {
			$conn = OpenCon();
			$array = [];
			$queryString = "SELECT C.nom as categorie, Q.nom as quizz, Q.id_categorie as cID, Q.id as qID
							        FROM quizz as Q, categories as C 
							        WHERE C.id = Q.id_categorie AND id_createur=".$_SESSION['idUtilisateur']."
                                    ORDER BY categorie ASC";

			if($result = $conn->query($queryString)){
	 			 while (($row = $result->fetch_assoc())) {
	 			 if(!isset($array[$row['categorie']])) $array[$row['categorie']] = [];  			 	
	 			 	array_push($array[$row['categorie']], ["quizz"=>$row['quizz'], "cID"=>$row['cID'], "qID"=>$row['qID']]);
            	}
				$result->free();
				CloseCon($conn);
			}
			return $array;
			
		} catch (Exception $e) {
			var_dump($e);
			return [];
		}
	}

	function getInfoCreatedQuizz(){
		//Le nb de joueur à ses quizz

		try {
			$conn = OpenCon();
			$array = [];
			$queryString = "SELECT quizz, idQuizz, categorie, maxScore, avgScore, COUNT(*) as nb_repet
							FROM 
								(SELECT C.nom as categorie, Q.nom as quizz, Q.id as idQuizz, url
								 FROM quizz as Q, categories as C 
								WHERE C.id = Q.id_categorie AND id_createur=".$_SESSION['idUtilisateur'].") as R, 
								scores as S, 
							    (SELECT id_quizz, MAX(score) as maxScore
							     FROM scores as S 
							     GROUP BY S.id_quizz) as M, 
							    (SELECT id_quizz, AVG(score) as avgScore
							     FROM scores as S 
							     GROUP BY S.id_quizz) as A
							WHERE S.id_quizz = idQuizz AND M.id_quizz=A.id_quizz AND A.id_quizz = S.id_quizz
							GROUP BY S.id_quizz 
							HAVING   COUNT(*) >=1 
							ORDER BY nb_repet DESC";

			if($result = $conn->query($queryString)){
	 			 while (($row = $result->fetch_assoc())) {
	 			 	$array[$row['idQuizz']] = [$row['nb_repet'], ($row['categorie'].' - '.$row['quizz']), $row['maxScore'], floatval($row['avgScore'])];
            	}
				$result->free();
				CloseCon($conn);
			}
			
			return $array;
			
		} catch (Exception $e) {
			var_dump($e);
			return [];
		}
	}


	function checkIfQuizzExistInDB($cId, $qId){
		try {
			$res=[];$i=0;
			$bd = OpenCon();

			if ($stmt = $bd->prepare("SELECT COUNT(*) FROM quizz WHERE id=? AND id_categorie=?")){

	 			$stmt->bind_param("ii", $qId, $cId);
	 			$stmt->execute();
	 			$count_result = 0; 
	 			$stmt->bind_result($count_result);
	 			$stmt->fetch();

	 			CloseCon($bd);
	 			//var_dump("Result dbRequest : ".$count_result."<br>");
	 			return $count_result==1;
	 		}
		} catch (Exception $e) {
			return false;
		}
		return false;
	}

	function getCategorieName($cId){
		try {
			$res=[];$i=0;
			$bd = OpenCon();

			if ($stmt = $bd->prepare("SELECT nom, COUNT(*) FROM categories WHERE id=?")){

	 			$stmt->bind_param("i", $cId);
	 			$stmt->execute();
	 			$count_result = 0; $nom=""; 
	 			$stmt->bind_result($nom, $count_result);
	 			$stmt->fetch();

	 			CloseCon($bd);
	 			//var_dump("Result dbRequest : ".$count_result."<br>");
	 			return ($count_result==1)?$nom:null;
	 		}
		} catch (Exception $e) {
			return null;
		}
		return null;
	}


	function getQuizzesSearchBox(){
		try {
			$res=[];

			$conn = OpenCon();
			$requestSQL = "SELECT nom, id, id_categorie FROM quizz";

			if($result = $conn->query($requestSQL)){
	 			 while (($row = $result->fetch_assoc())) {
	 			 	$res[$row['nom']] = [$row['id_categorie'],
	 			 						 $row['id'] ]; 
            	}
            	$result->free();
            }

			CloseCon($conn);
			return $res;

		} catch (Exception $e) {
			return [];
		}
	}

	function searchBox(){
		$categories = getExistingCategories();
		$quizzes    = getQuizzesSearchBox();
		$i=0;
    
		echo '
		

		<div class="input-group mb-3" style="top:8px; opacity:0" id="divSearchBox">
		  <input type="text" class="form-control" placeholder="Quizzes et catégories"  id="data-categories">
		</div>

		<span onclick="showSearchBar()" id="buttonSearchBar">
			<svg class="bi bi-search" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="#276955" xmlns="http://www.w3.org/2000/svg">
			<path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
			<path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
			</svg>
		</span>

		
		<span id="formForSearch" class="hide"></span>
		<script type="text/javascript">
		var options = {
			data: {
				"categories": [';
				foreach ($categories as $key => $value) {
					echo '{name: "'.$value['nom'].'", type:"categorie", id: '.$value['id'].'}';
					if($i!=(count($categories)-1)) echo ',';
					$i++;
				}
		echo    '],
				"quizzes": [';
				$i=0;
				foreach ($quizzes as $key => $value) {
					echo '{name: "'.$key.'", type:"quizz", idC: '.$value[0].', idQ: '.$value[1].'}';
					if($i!=(count($quizzes)-1)) echo ',';
					$i++;
				}
		echo	']
				},

				getValue: "name",

				categories: [{
					listLocation: "categories",
					header: "--- CATEGORIES ---"
					}, {
						listLocation: "quizzes",
						header: "--- QUIZZES ---"
						}],

						list: {
							match: {
								enabled: true
								},
								maxNumberOfElements: 10,
								onChooseEvent: function() {
									if(("categorie").localeCompare( $("#data-categories").getSelectedItemData().type) == 0){
										redirectToCategorie($("#data-categories").getSelectedItemData().id,                   $("#data-categories").getSelectedItemData().name);
										}else{
											console.log("quizz");
											redirectToQuizz($("#data-categories").getSelectedItemData().idC,
											$("#data-categories").getSelectedItemData().idQ);
										}
									}
								}
							};

							$("#data-categories").easyAutocomplete(options);
							</script>
		';
	}

	function getMetadata($cId, $qID){
		try {
			$res=[];

			$conn = OpenCon();
			$requestSQL = " SELECT Q.description as description, Q.url as url
							FROM quizz Q, categories C 
							WHERE C.id=Q.id_categorie AND C.id=".$cId." AND Q.id=".$qID;

			if($result = $conn->query($requestSQL)){
	 			 while (($row = $result->fetch_assoc())) {
	 			 	return ["description"=>$row['description'], "url"=>$row['url']];
            	}
            	$result->free();
            }

			CloseCon($conn);
			return $res;

		} catch (Exception $e) {
			return [];
		}
	}

	function getQuestionsReponse($idC, $idQ){
		try {
			$res=[];

			$conn = OpenCon();
			$requestSQL = " SELECT q.question, R.reponse, R.correct
							FROM quizz Q, questions q, reponses R
							WHERE Q.id=".$idQ." AND Q.id_categorie=".$idC." AND q.id_quizz=Q.id AND R.id_question=q.id ";

			if($result = $conn->query($requestSQL)){
	 			 while (($row = $result->fetch_assoc())) {
	 			 	if(!isset($res[$row['question']])) $res[$row['question']] = [];
	 			 	array_push($res[$row['question']], ["reponse"=>$row['reponse'], "correct"=>$row['correct']]);
            	}
            	$result->free();
            }

			CloseCon($conn);
			return $res;

		} catch (Exception $e) {
			return [];
		}
	}


 ?> 