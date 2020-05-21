<html>
    <?php
    	include 'header.php';	
    	$tab = addQuizz();
    	if(count($tab)==2){
    		echo '
    			<form action="quizz.php" method="post">
    				<input type="hidden" value="'.$tab[0].'" name="idCategorie">
    				<input type="hidden" value="'.$tab[1].'" name="idQuizz">
    				<button type="submit" id="buttonForm">
    			</form>
    			<script type="text/javascript">
    				$("#buttonForm").click();
    			</script>
    		';
    	}else{
    		echo '
    		<form action="creationQuizz.php" method="post">
    				<input type="hidden" value="1" name="errorCreation">
    				<button type="submit" id="buttonForm">
    			</form>
    			<script type="text/javascript">
    				$("#buttonForm").click();
    			</script>
    		';

    	}
    ?>

<br>
<?php 
	include 'footer.php';
?>
</html>