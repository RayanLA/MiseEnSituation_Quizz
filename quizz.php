<html>
    <?php
    include 'header.php';
    /*var_dump($_SESSION);*/

    if(isset($_POST['idQuizz']) && isset($_POST['idCategorie'])){
    	$array = getQuizzInfo($_POST['idQuizz'], $_POST['idCategorie']);
    	echo('
	    <div class="col-md-12">
	    	<div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-200 position-relative">
	    		<div class="col p-4 d-flex flex-column position-static">
	    		<h3 class="pb-4 mb-4 font-italic border-bottom">'.$array['nom'].'</h3>
	    			<strong class="d-inline-block mb-2 text-primary">'.$array['date_creation'].'</strong>
	    			<p class="card-text">'.$array['description'].'</p>');

    	echo '<script type="text/javascript">
      function validateForm(e){e.closest("form").submit();}
    </script>
    <style type="text/css">
      .link{
        color: #007bff;
        text-decoration: none;
        background-color: transparent;
      }</style>';

	    echo '
	    <style>.hide{display:none;}</style>
	    	<form action="question.php" method="post">
		    	<input name="numquestion" id="numquestion" value="0" class="hide"/>
		    	<input name="idQuizz" id="idQuizz" value="'.$_POST['idQuizz'].'" class="hide"/>
		    	<input name="nomQuizz" id="nomQuizz" value="'.$array['nom'].'" class="hide"/>
		    	<input name="nomcat" id="nomcat" value="'.$array['nomCategorie'].'" class="hide"/>
		    	<span class="stretched-link link" onclick="validateForm(this)">Commencer le quizz !</span>
	    	</form>
	    ';

	   /* echo '<a href="#" class="stretched-link" style="text-align: center;">Commencer le quizz</a>';*/


	    echo '</div>';

    	echo "<div class=\"col-3 p-4 d-flex flex-column position-static\">
    		<img class=\"bd-placeholder-img\" width=\"200\" height=\"250\" focusable=\"false\" role=\"img\" aria-label=\"Placeholder: Thumbnail\" src='".$array["url"]."' style='overflow: hidden;object-fit: contain;'></img>
    			</div>";

	    echo('	</div>');

	    /*Score personnel */
	    
	    $UserScore = getUserScore($_SESSION['idUtilisateur'], $_POST['idQuizz']);
	    if(isset($UserScore)){
	    	echo '<h3 class="font-italic">Ton meilleur score sur ce quizz : '.$UserScore.'</h3>';
			/*echo '<div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-200 position-relative">';

			echo '	</div>';*/
	    }
	    

	    /*SCORE BOARD */
	    echo '<h3 class="font-italic">Score Board</h3>';
	    echo '<div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-200 position-relative">
	    <div class="col p-4 d-flex flex-column position-static">
	    	';
	    $bestPlayers = getScoreBoard($_POST['idQuizz']);
	    echo '<table class="table" style="text-align: center;">
			  <thead>
			    <tr>
			      <th scope="col">#</th>
			      <th scope="col">Pseudo</th>
			      <th scope="col">Score</th>
			    </tr>
			  </thead>
			  <tbody>';
		for ($i=0; $i<count($bestPlayers); $i++) {
			echo '
				<tr>
			      <th scope="row">'.($i+1).'</th>
			      <td>'.$bestPlayers[$i]['login'].'</td>
			      <td>'.$bestPlayers[$i]['score'].'</td>
			    </tr>
			';
		}

		echo '	  </tbody>
			</table>';
			if(count($bestPlayers)==0){
				echo '<div class="stretched-link" style="text-align: center;"><b>Personne n\'a encore essayé ce quizz :(<br>Soit le premier et marque l\'histoire du Quizz ! </b></div>';
			}
		echo '</div>';
	    echo '	</div>';

	    echo '</div>';
    }else{
    	echo "Page non trouvée";
    }
    ?>




<br>
<?php 
	include 'footer.php';
?>
</html>
