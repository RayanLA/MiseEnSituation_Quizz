<html>
    <?php
    	include 'header.php';	
    	prepareDataForQuizzCreation();
        updateMetaData();
        deleteAndAddAllQuestionReponses();
    	echo '
			<form action="quizz.php" method="post">
				<input type="hidden" value="'.$_POST['idC'].'" name="idCategorie">
				<input type="hidden" value="'.$_POST['idQ'].'" name="idQuizz">
				<button type="submit" id="buttonForm" class="hide">
			</form>
			<script type="text/javascript">
				$("#buttonForm").click();
			</script>';    	
    ?>
<br>


</html>