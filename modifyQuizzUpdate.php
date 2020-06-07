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
				<button type="submit" id="buttonForm">
			</form>
			<script type="text/javascript">
				$("#buttonForm").click();
			</script>';    	
    ?>
<br>

<?php 
	include 'footer.php';
?>
</html>