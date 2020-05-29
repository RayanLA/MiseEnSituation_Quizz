<?php

	include 'header.php';

	function echecRedirectToIndex(){
		echo '
		<form action="index.php" method="post">
			<button type="submit" id="buttonRedirect">
		</form>';
		echo '
		<script type="text/javascript">
		$(document).ready(function(){ $("#buttonRedirect").click();});
		</script>
		';
	}

	if( isset($_GET['c']) && is_numeric( $_GET['c'] ) ){

		if(isset($_GET['q']) && is_numeric($_GET['q'])){
			//Invitation pour quizz 
			if( checkIfQuizzExistInDB($_GET['c'], $_GET['q']) ){
				echo '
					<form action="quizz.php" method="post">
						<input type="text" name="idQuizz" value="'.$_GET['q'].'" style="display:none">
						<input type="text" name="idCategorie" value="'.$_GET['c'].'" style="display:none">
						<button type="submit" id="buttonRedirect">
					</form>';
				echo '
					<script type="text/javascript">
						$("#buttonRedirect").click();
					</script>
				';
			}else{ echecRedirectToIndex(); }
		}else{
			//Invitation pour categorie
			$categorieName = getCategorieName($_GET['c']);
			if($categorieName != null){
				echo "
					<form action=\"QuizzParCategorie.php\" method=\"post\">
                    <input name=\"idCategorie\" id=\"numquestion\" value=\"".$_GET['c']."\" class=\"hide\"/>
                    <input name=\"nomCategorie\" value=\"".$categorieName."\" class=\"hide\"/>
                    <button type=\"submit\" id=\"buttonRedirect\">
                  </form>
				";
				echo '
					<script type="text/javascript">
						$("#buttonRedirect").click();
					</script>
				';
			}else{ echecRedirectToIndex();	}
		}
	}else{ echecRedirectToIndex();}

?>
