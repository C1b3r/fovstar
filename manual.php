<?php
use classes\Init;
require_once('conf/access.php');
require_once('classes/boot.php');
if(!isset($_GET['secret_token']) || empty($_GET['secret_token'])){
  header('HTTP/1.0 403 Forbidden');
  exit;
}else{
   if($_GET['secret_token'] !== $_ENV['secret_token']){
    header('HTTP/1.0 403 Forbidden');
    exit;
  }
}
$boot = new Init;
 ini_set('display_errors', '1');

$mensaje="";
if(isset($_POST['enviar'])){
    if(!$boot->insert($_POST)){
      echo "hubo un error,probablemente ese id ya se haya insertado";
    }else{
      echo "insertado correctamente";
    }
}

	
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <title>Manualmente</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Manualmente un tweet no registrado</h2>
  <form action="manual.php?secret_token=<?php echo $_GET['secret_token'];?>" method="POST">
    <div class="form-group">
      <label for="id">id</label>
      <input type="number" class="form-control" id="id" placeholder="Id tweet" name="tweet[id]">
    </div>
    <div class="form-group">
      <label for="texto">Texto:</label>
      <textarea class="form-control" id="texto" placeholder="Texto del tweet" name="tweet[texto]"></textarea>
    </div>
    <div class="form-group">
      <label for="rt">Número de retweet</label>
      <input type="number" class="form-control" id="rt" placeholder="Rt del tweet" name="tweet[rt]">
    </div>
    <div class="form-group">
      <label for="fav">Número de fav</label>
      <input type="number" class="form-control" id="fav" placeholder="Fav del tweet" name="tweet[fav]">
    </div>
    <div id="divfoto" style="display: none;" class="form-group">
      <label for="url">URL foto</label>
      <input type="text" class="form-control" id="url"  placeholder="Url de la foto del tweet" name="tweet[url]">
    </div>
    <div class="checkbox">
      <label><input value="1" onchange="cambiaDocu(this)" type="checkbox" name="foto">Tiene foto</label>
    </div>
     <div class="text-right">
    <button type="submit" style="margin: 0 auto;" name="enviar" class="btn btn-default">Submit</button>
    </div>
  </form>
  <p><?php echo $mensaje; ?></p>
</div>
<script type="text/javascript">
	function cambiaDocu(x){
  if(!x.checked){
    document.getElementById("divfoto").style.display="none";
  }else{
    document.getElementById("divfoto").style.display="block";
  }
}

</script>
</body>
</html>