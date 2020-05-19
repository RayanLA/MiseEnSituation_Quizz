<html>

<?php
	include 'header.php';
	echo($_POST['nomQuizz']);
	echo($_POST['numquestion']);
	var_dump($_POST);

	if (isset($_POST['idQuizz']) && isset($_POST['nomQuizz']) && isset($_POST['numquestion']) && isset($_POST['nomcat'])){
?>

<div>
  <form action="question.php" method="post" id="autoclick">
    <div class="form-group">
    	<input name="numquestion" id="numquestion" value="<?php echo($_POST['numquestion']+1); ?>"/>
    	<input name="idQuizz" id="idQuizz" value="<?php echo($_POST['idQuizz']); ?>"/>
    	<input name="nomQuizz" id="nomQuizz" value="<?php echo($_POST['nomQuizz']); ?>"/>
    	<input name="nomcat" id="nomcat" value="<?php echo($_POST['nomcat']); ?>"/>
    	<button type="submit" class="btn btn-primary">Valider</button>
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
?>
<?php
      include 'footer.php'
?>