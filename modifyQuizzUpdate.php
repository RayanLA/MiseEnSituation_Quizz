<html>
    <?php
    	include 'header.php';

    	if($_POST['deleteQuizz']==1){
    		deleteQuizz($_POST['idQ']);
    		echo '
			<form action="index.php" method="post">
				<input type="hidden" value="Le quizz &laquo;'.$_POST['nomQuizz'].'&raquo; a bien été supprimé !" name="message">
				<button type="submit" id="buttonForm" class="hide">
			</form>
			<script type="text/javascript">
				$("#buttonForm").click();
			</script>';
    	}


    	prepareDataForQuizzCreation();
        updateMetaData();
        deleteAndAddAllQuestionReponses();
    	echo '
			<form action="quizz.php" method="post">
				<input type="hidden" value="'.$_POST['idC'].'" name="idCategorie">
				<input type="hidden" value="'.$_POST['idQ'].'" name="idQuizz">
				<input type="hidden" value="Votre quizz a été mis à jour !" name="message">
				<button type="submit" id="buttonForm" class="hide">
			</form>
			<script type="text/javascript">
				$("#buttonForm").click();
			</script>';    	
    ?>
<br>


</html>