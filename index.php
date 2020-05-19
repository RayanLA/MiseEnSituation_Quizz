<html>
    <?php

    include 'header.php';

    

    ?>
    <?php 
          $ArrayQuizz = get3MostTrendyQuizz();
          //var_dump(($ArrayQuizz));
      ?>
  
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>

    <div class="carousel-inner">
      <?php
          for ($i = 0; $i < 3; $i++) {

            if($i==0) echo '<div class="carousel-item active">';
            else echo '<div class="carousel-item">';

            /*echo '<svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img"><rect width="100%" height="100%" fill="#777"/></svg>
              <div class="container">';*/

            echo '<img class="bd-placeholder-img" width="100%" height="100%" focusable="false" role="img"src="'.$ArrayQuizz[$i][4].'" style="opacity: 0.75;"/>
              <div class="container">';

            if($i==0) echo '<div class="carousel-caption text-left">';
            elseif($i==1) echo '<div class="carousel-caption">';
            else echo '<div class="carousel-caption text-right">';

            echo '<h1>'.$ArrayQuizz[$i][0].'</h1>';
            echo '<p>'.$ArrayQuizz[$i][1].'</p>';
            echo '<p><a class="btn btn-lg btn-primary" href="'.'quiz/'.$ArrayQuizz[$i][2].'/'.$ArrayQuizz[$i][3].'" role="button">En savoir plus !</a></p>';
            echo ' </div></div></div>';
          }
      ?>
    </div>

    <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
  <div class="col-md-12 blog-main">
      <h3 class="pb-4 mb-4 font-italic border-bottom">
        Ajouter un nouveau quizz
      </h3>
  </div>  
  <div class="row mb-12">
    
    <div class="col-md-12">
      <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-200 position-relative">
        <div class="col p-4 d-flex flex-column position-static">
          <strong class="d-inline-block mb-2 text-primary">Toi aussi test les connaissances des autres</strong>
          <p class="card-text">Nous t'offrons la possibilité de créer tes propres quizz, puis cela clique 
            sur le lien en juste en dessous. Fait grandir le nombre de quizz pour encore plus de fun !! :trololol:</p>
          <a href="#" class="stretched-link" style="text-align: center;">Créer un quizz</a>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12 blog-main">
      <h3 class="pb-4 mb-4 font-italic border-bottom">
        Derniers quizz
      </h3>
  </div>  

  <div class="row mb-2">
  <?php
    $bd = OpenCon();
    $result = $bd->query("SELECT quizz.nom as qnom,categories.nom as cnom,quizz.description as description,quizz.date_creation as crea, quizz.url as url FROM quizz,categories WHERE quizz.id_categorie  = categories.id ORDER BY quizz.id DESC LIMIT 20");
     while (($row = $result->fetch_assoc())) {
      var_dump($row);
      echo("<div class=\"col-md-6\">");
      echo("<div class=\"row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative\">");
      echo("<div class=\"col p-4 d-flex flex-column position-static\">");
      echo("<strong class=\"d-inline-block mb-2 text-primary\">".$row["cnom"]."</strong>");
      echo("<h3 class=\"mb-0\">".$row["qnom"]."</h3>");
      echo("<div class=\"mb-1 text-muted\">".$row["crea"]."</div>");
      echo("<p class=\"mb-auto\">".$row["description"]."</p>");
      echo("<a href=\"#\" class=\"stretched-link\">Tester mes connaissances</a>");
      echo("</div>");
      echo("<div class=\"col-auto d-none d-lg-block\">");
      /*echo("<svg class=\"bd-placeholder-img\" width=\"200\" height=\"250\" xmlns=\"http://www.w3.org/2000/svg\" 
              preserveAspectRatio=\"xMidYMid slice\" focusable=\"false\" role=\"img\" aria-label=\"Placeholder: Thumbnail\">
              <title>Placeholder</title><rect width=\"100%\" height=\"100%\" fill=\"#55595c\"/><text x=\"50%\" y=\"50%\" fill=\"#eceeef\" dy=\".3em\">Thumbnail</text></svg>");*/
      echo("<img class=\"bd-placeholder-img\" width=\"200\" height=\"250\" focusable=\"false\" role=\"img\" aria-label=\"Placeholder: Thumbnail\" src='".$row["url"]."' style='overflow: hidden;object-fit: contain;'></img>");
      echo("</div>");
      echo("</div>");
      echo("</div>");
      
      
      //echo("<a class=\"p-2 text-muted\" href=\"#".$row["id"]."\">".$row["nom"]."</a>");

    }

    CloseCon($bd);


  ?>
    
  </div>
</div>

<div id="loginModal" class="modal fade" role="dialog">
  <!--<span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>-->
  <div class="modal-dialog form-group">
    <form class="modal-content" action="connexion.php" method="post">
      <div class="container">
        <div class="modal-header">
          <h5 class="modal-title" id="title" style="text-align: center">S'identifier</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label for="email"><b>Identifiant</b></label>
          <input type="text" placeholder="Pseudo" class="form-control" name="login" required>

          <label for="psw"><b>Mot de passe</b></label>
          <input type="password"  class="form-control" placeholder="Enter Password" name="password" required>
          <br/>
          Pas encore inscrit ? <a href="inscription.php">Inscription</a>
          

        </div>
        <div class="modal-footer">
        <!--<p>By creating an account you agree to our <a href="#" style="color:dodgerblue">Terms & Privacy</a>.</p>-->
          <div class="clearfix">
            <button type="submit" class="btn btn-secondary">Connexion</button>
          </div>
          
        </div>
      </div>
    </form>
  </div>
}
    
  ?>
  

</div>

<footer class="blog-footer">
  <p>Quizzio by RILAR.</p>
  <p>
    <a href="#">Retournez en haut</a>
  </p>
</footer>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="bootstrap/js/bootstrap.bundle.js"></script></body>
</html>

</html>