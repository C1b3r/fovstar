<?php
require_once('conf/access.php');
$username = $_ENV['USER'];
//problema de esto es que solo busca los ultimos 10 días
 ini_set('display_errors', '1');
$enlace = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);

mysqli_set_charset($enlace, "utf8");
$mensaje="";
//$TipoLista=array("primary", "secondary","success", "danger","warning", "info","light", "dark");
$TipoLista=array("primary","secondary","success","warning", "info", "dark");
			$query="SELECT * FROM `".$_ENV['DB_TABLE']."` where usuario='".$username."' order by nret DESC limit 40";
			$resultado = mysqli_query($enlace, $query)or die(mysqli_error($enlace));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <title>Fovstar | tweets con más interacciones</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Recopilacion de tweets <?php echo $username;?>">
    <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Roboto+Slab&display=swap" rel="stylesheet">
  <style type="text/css">
    body{
      font-family: 'Roboto Slab', serif;
    }
    .centrarimagen{
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 50%;
    }
    #ret{
      margin-right: 5%;
    }
    #ret:hover{
      color: rgb(23, 191, 99);
    }
    #fav:hover{
      color: rgb(224, 36, 94);
    }
    .list-group-item-dark {
    color: #1b1e21;
    background-color: #c6c8ca;
}
.list-group-item-secondary {
    color: #383d41;
    background-color: #d6d8db;
}
.list-group-item-success {
    color: #6f2d2d;
    background-color: #f3cad1;
}
a.list-group-item-success, button.list-group-item-success {
    color: #6f2d2d;
}
a.list-group-item, button.list-group-item {
    border: 1px solid black;
}
  </style>
</head>
<body>

<div class="container">
  <h2>Los 40 mejores tweets de <a href="https://twitter.com/<?php echo $username;?>">@<?php echo $username;?></a></h2>
  <br>
  <div class="list-group">
    <?php 
    //Con array rand cojo 2 entradas aleatorias aunque solo voy a usar una
    $guardoanterior=0;
    $abuscar=0;
    while ($fila = mysqli_fetch_assoc($resultado)) {
    /*  $random_keys=shuffle($TipoLista);
      if($guardoanterior==$random_keys[0]){
        $abuscar=1;
        $guardoanterior=$random_keys[1];
      }else{
        $abuscar=0;
        $guardoanterior=$random_keys[0];
      }*/

        if($fila["urlimagen"]=="no"){
          $foto="";
          $textofinal=$fila["texto"];
        }else{
          $foto=$fila["urlimagen"];
          $limpiatexto=str_replace("https://t.co/", "", $fila["texto"]);
        /*elimino los 10 ultimos caracteres que corresponden a los parametros de la url que he quitado antes, el 0 es para que mantenga el resto del tweet pero elimine lo último(que casi siempre son 10 caracteres)*/
        $textofinal=substr($limpiatexto,0, -10);
        }

      
    /*  echo '<a style="white-space: pre;" href="https://twitter.com/'.$username.'/status/'.$fila["id_tweet"].'" class="list-group-item list-group-item-action list-group-item-'.$TipoLista[$random_keys[0]].'">'.$fila["texto"].'</a>';
   list-group-item-'.$TipoLista[$abuscar]
    quito el white space pre porque me respetaba los espacios pero no era responsive, pongo el nl2br que respeta el line break los enter y eso*/
     echo '<a style="text-align:center;"target="_blank" href="https://twitter.com/'.$username.'/status/'.$fila["id_tweet"].'" class="list-group-item list-group-item-action">
     '.nl2br($textofinal).'
     <img  style="width:50%;" src="'.$foto.'" class="img-responsive centrarimagen">
     <br>
     <div style="text-align:center;">
     <span id="ret">'.$fila["nret"].'<i class="fa fa-retweet"></i></span>

     <span id="fav">'.$fila["nfav"].'<i class="fa fa-heart"></i></span>
     </div>
      </a>';
      
    }//fin while

    ?>
</div>
</div>

</body>
</html>