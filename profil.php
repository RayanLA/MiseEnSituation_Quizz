<?php
    include 'header.php';
    if(!isset($_SESSION['login'])){
        header('Location:  index.php');
    }
    
    $nbPlayedQuizzPerCategorie = getNbPlayedQuizzPerCategorie();
    $playedQuizzScore = getPlayedQuizzScore();
    $nbOfCreatedQuizz = getNbOfCreatedQuizz();
    $infoCreatedQuizz = getInfoCreatedQuizz();
?>

<script type="text/javascript" language="javascript">
    var quizzInfoScore = new Array();
    var createdQuizzStats = new Array();
    <?php 
        foreach($playedQuizzScore as $key => $val){ 
            echo 'quizzInfoScore['.$val[0].'] = '.$val[1].'; ';
        } 

        foreach($infoCreatedQuizz as $key => $val){ 
            echo 'createdQuizzStats["'.$key.'"]= new Array('.$val[0].', "'.$val[1].'",'.$val[2].','.$val[3].');';
        } 
    ?>
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-deferred@1"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>


<hr/>
<div class="card">
  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs">
      <li class="nav-item" onclick="chart()">
        <a class="nav-link" id="two-tab" data-toggle="tab" href="#two" role="tab" aria-controls="Two" aria-selected="false">Statistiques</a>
      </li>
       <li class="nav-item">
        <a class="nav-link" id="one-tab" data-toggle="tab" href="#one" role="tab" aria-controls="One" aria-selected="true">Changer le mot de passe</a>
      </li> 
      <li class="nav-item">
        <a class="nav-link" id="two-tab" data-toggle="tab" href="#three" role="tab" aria-controls="Two" aria-selected="false">Derniers résultat</a>
      </li>
    </ul>
  </div>
  <div class="tab-content" id="myTabContent">

          <div class="tab-pane fade show active  p-3" id="two" role="tabpanel" aria-labelledby="two-tab"> 
            
              <!-- Le nb de quizz joué par catégorie -->
               <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-200 position-relative p-3" id="quizzJoue">
                <div class="col-md-12">
                 <h3 class="pb-4 mb-4 font-italic border-bottom">Nombre de quizz joué par catégorie : </h3>
               </div>
               <?php 
               foreach ($nbPlayedQuizzPerCategorie as $key => $value) {
                echo '
                      <div class="col-sm-4">
                        <div class="card m-2">
                          <div class="card-body">
                          <h5 class="card-title">'.$key.'</h5>
                          <p class="card-text">A été joué <span class="titleImitation">'.$value.'</span> fois !</b></p>
                          </div>
                        </div>
                      </div>
                ';
              }  ?>    
            </div>

            <!-- Le nombre de bonne réponse par quizz -->
            <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-200 position-relative p-3">
              <div class="col-md-12">
                <h3 class="pb-4 mb-4 font-italic border-bottom">Pourcentage de bonne réponse par quizz (en pourcentage) : </h3>
             </div>

             <div class="row">

              <?php 
               foreach ($playedQuizzScore as $key => $value) {
                echo '<div class="col-md-6" style="min-width:50%">
                        <div class="card m-2">
                          <div class="card-body">
                          <h5 class="card-title">'.$key.'</h5>
                            <div class="card">
                              <div class="card-body">
                                <canvas id="doughnutChart_'.$value[0].'"  ></canvas>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>';

              }  ?>  

             </div>

           </div>


            <!-- Le nombre de quizz créé --> 
           <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-200 position-relative p-3">
                <div class="col-md-12">
                 <h3 class="pb-4 mb-4 font-italic border-bottom">Vos créations : </h3>
               </div>
               <?php 
               foreach ($nbOfCreatedQuizz as $key => $value) {
                echo '
                      <div class="col-sm-4">
                        <div class="card m-2">
                          <div class="card-body">
                          <h5 class="card-title">'.$key.'</h5>
                          <p class="card-text"><span class="titleImitation">'.$value.'</span> '.($value>1?'quizzes créés':'quizz créé').' !</b></p>
                          </div>
                        </div>
                      </div>
                ';
              }  ?>    
            </div>


            
           

           <!-- Le nb de joueur à ses quizz -->
           <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-200 position-relative p-3">
              <div class="col-md-12">
                <h3 class="pb-4 mb-4 font-italic border-bottom">Statistiques sur les joueurs de vos jeux : </h3>
             </div>
              <div class="col-md-12">
                <canvas id="barChart"></canvas>
              </div>
           </div>
             

          </div>

           <div class="tab-pane fade p-3" id="one" role="tabpanel" aria-labelledby="one-tab">
          
                <form action="#" method="post">
                    <div class="form-group">
                        <label for="password1">Ancien mot de passe</label>
                        <input type="password" class="form-control" id="password1" name="password1" aria-describedby="emailHelp">
                        
                    </div>
                    <div class="form-group">
                        <label for="password2">Nouveau mot de passe</label>
                        <input type="password" class="form-control" id="password2" name="password2">
                    </div>
                    <div class="form-group">
                        <label for="password3">Confirmation nouveau mot de passe</label>
                        <input type="password" class="form-control" id="password3" name="password3" onchange=" if(document.getElementById('password3').value== document.getElementById('password2').value){
                                                                                                                    document.getElementById('checkPwd').innerHTML = 'Confirmation mot de passe validée'; 
                                                                                                                    document.getElementById('checkPwd').className = 'text-success'; 
                                                                                                                    document.getElementById('btn_valid').disabled = false;    
                                                                                                                }else{
                                                                                                                    document.getElementById('checkPwd').innerHTML = 'Confirmation mot de passe invalide';
                                                                                                                    document.getElementById('checkPwd').className = 'text-danger';     
                                                                                                                }">
                        <small id="checkPwd" class="form-text text-muted"></small>
                    </div>
                    <div class="text-center">
                    <button type="submit" class="btn btn-primary" id="btn_valid" disabled>Enregistrer</button>
                    </div>
                </form>
                         
          </div>

          

          <div class="tab-pane fade p-3" id="three" role="tabpanel" aria-labelledby="three-tab">
            <?php
             $sql = "   SELECT categories.nom as cnom,score,quizz.nom as qnom,COUNT(questions.question) as nbQuestion 
                        FROM categories,scores,quizz JOIN questions ON questions.id_quizz = quizz.id 
                        WHERE id_utilisateur = 1 AND categories.id = quizz.id_categorie AND scores.id_quizz = quizz.id 
                        GROUP BY questions.id_quizz ORDER BY scores.id DESC LIMIT 12";
             $con = OpenCon();
             $res = $con->query($sql);     
             //var_dump($res);       
             echo '<div class="row">';
             while (($row = $res->fetch_assoc())) {
                echo '<div class="col-sm-4">';
                echo    '<div class="card" >
                            <div class="card-body">';
                echo          '<h5 class="card-title">'.$row["qnom"].'</h5>';
                echo          '<h6 class="card-subtitle mb-2 text-muted">Catégorie : '.$row["cnom"].'</h6>';
                echo          '<p class="card-text">Score : <b>'.$row["score"].'/'.$row["nbQuestion"].'</b></p>';
                echo        '</div>
                        </div>';
                echo  '</div>';
             }      
             echo "</div>";
              CloseCon($con);
            ?>
          </div>

        </div>
</div>
<br/>

<?php
    try {
      /*echo 'HERE0';*/
      if(isset($_POST['password1'])){
      $pwdIsCorrect = verifyIdentity($_SESSION['login'], $_POST['password1']);
      if($pwdIsCorrect){
        $pwd = $_POST['password2'];
        $login = $_SESSION['login'];
        $requestSQL = "UPDATE utilisateurs SET mdp = '".$pwd."'  WHERE login = '".$login."'";

        $conn1 = OpenCon();
        $conn1->query($requestSQL);
        CloseCon($conn1);
      }

      }
    } catch (Exception $e) {
      var_dump($e);
    }
      
      
      include 'footer.php'
?>

