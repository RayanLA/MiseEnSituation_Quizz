<html>
    <head>
        
        <link rel="stylesheet" href="css/blog.css"/>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.css"/>
        <title>QUIZZ</title>
    <!-- Custom styles for this template -->
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:700,900" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/blog.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
  <header class="blog-header py-3">
    <?php
      include 'header.php';
    ?>
  </header>
  
  

  <div class="row mb-2">
    <?php 
    $conn = OpenCon();
    try {
      if($result = $conn->query("SELECT * FROM quizz WHERE id_categorie=".$_GET['idCategorie']." ")){
         while ($row = $result->fetch_assoc()) {
            echo '<div class="col-md-6">
            <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
              <div class="col p-4 d-flex flex-column position-static">
                <h3 class="mb-0">'.$row['nom'].'</h3>
                <p class="card-text mb-auto"> Ce quizz comporte ';

                $query = "SELECT count(question)  FROM questions WHERE id_quizz=".$row['id']." ";
                $result1 = mysqli_query($conn,$query) or die (mysqli_error());
                $resultat=mysqli_fetch_row($result1);
                echo $resultat[0]. ' questions</p>

                <p><i>'.$row['description'].' </i></p>

                <a href="Quizz.php?idQuizz='.$row['id'].'" class="stretched-link">Tester mes connaissances</a>
              </div>
              <div class="col-auto d-none d-lg-block">
                <img class="bd-placeholder-img" width="200" height="250" focusable="false" role="img" aria-label="Placeholder: Thumbnail" src="'.$row["url"].'" style="overflow: hidden;object-fit: contain;"></img>
              </div>
            </div>
          </div>'; 
        }
        $result->free();
      }
    } catch (Exception $e) {
      echo $e;
    }
    

    CloseCon($conn);


    ?>
   


  </div><!-- /.row -->

</main><!-- /.container -->

<?php
      include 'footer.php'
?>