<?php
	include 'header.php';
	var_dump($_POST);
?>

<div>
  <form action="question.php" method="post" id="autoclick">
    <div class="form-group">
    	<input name="numquestion" id="numquestion" value="1"/>
    	<input name="idQuizz" id="idQuizz" value="1"/>
    	<input name="nomQuizz" id="nomQuizz" value="Disney"/>
    	<input name="nomcat" id="nomcat" value="Films"/>
    	<button type="submit" class="btn btn-primary">Valider</button>
    </div>
   </form>
 </div>

<script type="text/javascript">
  	//document.getElementById("autoclick").submit();
</script>

<?php
      include 'footer.php'
?>