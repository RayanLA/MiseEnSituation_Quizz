<html>
	<?php include 'header.php'; 
	
	if(!isset($_POST['selectedQuizz'])) JS_Redirect("index.php");

	$tabData = explode("_", $_POST['selectedQuizz']);
	$cID=$tabData[0];
	$qID=$tabData[1];
	$quizzName=$tabData[2];

	$Categories = getExistingCategories();
	$data = getMetadata($cID, $qID);
	$description = $data["description"];
	$url = $data["url"];

	$questionReponse = getQuestionsReponse($cID, $qID);
    echo '<h3 class="pb-4 mb-4 font-italic border-bottom" id="modifyQuizz">'.$quizzName.'</h3>';

    echo '<script type="text/javascript">
            $(function(){
                $(\'#thumbnailImg\').attr(\'src\',\''.$url.'\');
            });
            var questionReponseData = '.json_encode($questionReponse).';
            generateQuestionReponses();
          </script>';
	?>

    <div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirmez la suppression : </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body text-danger">
        <?php echo 'Vous êtes sur le point de supprimer definitivement &laquo;'.$quizzName.'&raquo; ainsi que toutes ses questions, réponses et les scores de ses joueurs ! ';
        ?>
    </div>
    <div class="modal-footer">
        <button type="button" id="deleteQuizz" class="btn btn-danger">Confirmer la suppression</button>
    </div>
</div>
</div>
</div>

    <form action="modifyQuizzUpdate.php" method="POST">
        <?php 
            echo '<input type="hidden" name="idC" value="'.$cID.'">
                  <input type="hidden" name="idQ" value="'.$qID.'">';
        ?>

    	<div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-200 position-relative formQuizDiv">
    		<!-- Titre -->
    		<div class="mb-2">
    			<h5 class="pb-2 mb-2 text-primary">Informations sur le quizz</h5>
    		</div>

    		<!-- Categorie -->
    		<div class="input-group mb-4">
    			<div class="input-group-prepend">
    				<label class="input-group-text" for="inputGroupSelect01">Categories disponibles : </label>
    			</div>
    			<select class="custom-select" id="inputGroupSelect01" name="categorie">
    				<option selected>Selectionner...</option>
    				<?php
    					for($i=0; $i<count($Categories); $i++) {
    						echo '<option value="'.$Categories[$i]['id'].'" '.($Categories[$i]['id']==$cID?"selected":"").' >'.$Categories[$i]['nom'].'</option>';
    					}
    				?>
    			</select>
    		</div>

    		<!-- Nom Quizz -->
    		<div class="input-group mb-3">
    			<div class="input-group-prepend">
    				<span class="input-group-text" >Nom du Quizz : </span>
    			</div>
    			<?php 
    			echo '<input type="text" class="form-control" required="" aria-describedby="basic-addon1" name="nomQuizz" id="nomQuizz" value="'.$quizzName.'" >';
    			?>
    		</div>

    		<!-- Description -->
    		<div class="input-group mb-4">
    			<div class="input-group-prepend">
    				<span class="input-group-text">Description brève : </span>
    			</div>
    			<?php 
    			echo '<textarea class="form-control descriptionQuizz" id="description" name="description"  required="">'.$description.'</textarea>';
    			?>
    		</div>

    	</div>

    	<div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-200 position-relative formQuizDiv">

    		<div class="col-md-12">

    			<!-- Titre -->
    			<div class="row">
    				<div class="col-md-12">
    					<h5 class="pb-2 mb-2 text-primary">Image : </h5>
    				</div>
    			</div>

    			<!-- Input url -->
    			<div class="row">
    				<div class="col">
    					<label for="basic-url">N'hésite pas à modifier l'url si l'image ci-contre ne te convient pas !</label>
    					<div class="input-group mb-3">
    						<div class="input-group-prepend">
    							<span class="input-group-text" id="basic-addon3">Url de l'image:</span>
    						</div>
    						<?php 
    						echo'<input type="text" class="form-control" aria-describedby="basic-addon3" name="url" id="urlInput" value="'.$url.'">';
    						?>
    					</div>
    				</div>

    				<!-- Thumbnail -->
    				<div class="col-md-5 col-3 col-lg-3 p-4">
    					<img src="" class="thumbnailImage" id="thumbnailImg" width="200" >
    				</div>
    			</div>
    		</div>
    	</div>


    	<!-- Questions -->
    	<div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-200 position-relative formQuizDiv">
    		
    		<div class="col-md-12">

    			<!-- Titre -->
    			<div class="row">
    				<div class="col-md-12">
    					<h5 class="pb-2 mb-2 text-primary">Saisie des questions : </h5>
    				</div>
    			</div>

    			<!-- Question -->
    			<div class="row no-gutters">
    				<div class="questionContener col-md-12" ></div>
    			</div>

    			<!-- Bouton ajout question-->
    			<div class="row d-flex flex-row-reverse">
    				<div>
    					<button type="button" class="btn btn-primary" onclick="addQuestion()">Ajouter une question</button>
    				</div>
    			</div>
    		</div>
    	</div>

        <input type="hidden" name="deleteQuizz" value="0" id="deleteQuizzInput">

    	<div class="mb-4 d-flex justify-content-center">
    		<button type="submit" id="modifyQuizzSubmitButton" class="btn btn-lg btn-primary m-4">Mettre à jour le Quizz !</button>
            <button type="button" data-toggle="modal" data-target="#confirmDelete" id="confirmDeleteButton" class="btn btn-lg btn-danger m-4">Supprimer le Quizz</button>
    	</div>
    </form>


	<br>
	<?php include 'footer.php'; ?>
</html>
