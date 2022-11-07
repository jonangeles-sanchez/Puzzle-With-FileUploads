<!DOCTYPE html>

<html>
<head>
  <meta charset="utf-8" />
  <title>Simple Javscript Puzzle</title>
  <link rel="stylesheet" href="puzzle.css" type="text/css" media="screen" />
   <script type="text/javascript" src="puzzle.js" defer></script>
   <?php 
   //Include external PHP file
   require_once 'functions.php';

   //Initialize variables
   $location = "./uploads/";
   $max = 1572864; //Equivalent to 1.5 M
   
   ?>
</head>
<body>
  <div id="allContent">
    <div id="topContainer">
      <header>
        <h1>Sliding Puzzle</h1>
      </header>
      <div id="sidebar">
        <div id="controls">
          <input type="button" value="Play Again" id="reset">
          <input type="text" readonly value="Best Score" id="best">
          <div id="bestText">Best Score:</div>
        </div>
        <figure>
          <img id="finalImg" src="images/guideImage.jpg" alt="result" />

          <figcaption>Guide Image</figcaption>
        </figure>

        <form action="" method="post" enctype="multipart/form-data" id="uploadImage">
          <table>
            <tr>
              <td><label for="image">Upload image:</label></td>
            </tr>
            <tr>
              <td>
                <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
                <input type="file" name="imageUpload">
                <input type="submit" name="upload" value="Upload">
                <?php
                  if(isset($_POST['upload'])){
                    //Array is passed containing information of recent file upload
                    checkFileValidity($_FILES['imageUpload']);
                    /*
                      Information passed: 
                        -fileName (name)
                        -fileFullPath (full_path)
                        -fileType (type)
                        -tmp_name
                    */
                  }
                ?>
              </td>
            </tr>
          <tr>
            <td>
              <input id="resetDefault" type="button" value="Reset to default puzzle image" onclick="window.location.assign(document.URL);">
            </td>
          </tr>
        </table>
      </form>
    </div>
    <main id="gameboard">
      <!-- nothing here yet. content will be -->
      <!-- added by JavaScript -->
    </main>
  </div>
  </div>
</body>
</html>
