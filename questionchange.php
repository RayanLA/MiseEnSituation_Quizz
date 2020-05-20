<html>

<?php
	include 'header.php';

	if (isset($_POST['idQuizz']) && isset($_POST['nomQuizz']) && isset($_POST['numquestion']) && isset($_POST['nomcat'])){

		$array = [];
		if(!empty($_POST['question'])) {    
    	foreach($_POST['question'] as $value){
    		$array[] = $value;
    	}
  	}
  	$_SESSION["data"][] = $array;
  	//var_dump($_SESSION["data"]);
?>

<div>
  <form action="question.php" method="post" id="autoclick">
    <div class="form-group">
    	<input type="hidden" name="numquestion" id="numquestion" value="<?php echo($_POST['numquestion']+1); ?>"/>
    	<input type="hidden" name="idQuizz" id="idQuizz" value="<?php echo($_POST['idQuizz']); ?>"/>
    	<input type="hidden" name="nomQuizz" id="nomQuizz" value="<?php echo($_POST['nomQuizz']); ?>"/>
    	<input type="hidden" name="nomcat" id="nomcat" value="<?php echo($_POST['nomcat']); ?>"/>
    	<button type="submit" class="btn btn-primary"style="display:none">Valider</button>
    </div>
   </form>
 </div>

<script type="text/javascript">
  document.getElementById("autoclick").submit();
</script>

<?php
	} else {
		echo("Page introuvable");
	}

      include 'footer.php'
?>