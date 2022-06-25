<?php
namespace index;
use classes\Init;
require_once('conf/access.php');
require_once('classes/boot.php');
$boot = new Init;
$resultado = $boot->getTweets();
//problema de esto es que solo busca los ultimos 10 días
ini_set('display_errors', '1');
// $enlace = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);

// mysqli_set_charset($enlace, "utf8");
// $mensaje="";

// 			
// 			$resultado = mysqli_query($enlace, $query)or die(mysqli_error($enlace));

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <title>Fovstar | tweets con más interacciones</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Recopilacion de tweets <?php echo $boot->username;?>">
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
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

<style type="text/css">
  body{
    font-family: Arial, Helvetica, sans-serif;
  }

  h2{
    text-align: center;
  }
  a:focus, a:hover {
      color: #23527c;
      text-decoration: underline;
  }
  a {
      color: #337ab7;
      text-decoration: none;
  }


  .load-spinner{
    border: 16px solid #f3f3f3;
    border-radius: 50%;
    border-top: 16px solid #3498db;
    width: 120px;
    height: 120px;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;
    margin:auto;
    left:0;
    right:0;
    top:0;
    bottom:0;
    position:fixed;
  }

  @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
  }

.hide-loader{
  display:none;
}

</style>

</head>
<body>

<div class="loader" id="loader">
  <div class="load-spinner" id="load-spinner">
  </div>
</div>

<div id="container" class="container">
  <h2>Los 20 mejores tweets de <a href="https://twitter.com/<?php echo $boot->username;?>">@<?php echo $boot->username;?></a></h2>
  <br>
  <section class="list-group">
<!--   <table id="example" class="display" style="width:100%">
    <thead>
    <tr><th></th></tr>  
  </thead>
  <tbody> -->
    <?php 
    //Con array rand cojo 2 entradas aleatorias aunque solo voy a usar una
    $guardoanterior = 0;

    while ($fila = $resultado->fetch()) {
    echo '<div class="tw-tweets" twitterID="'.$fila["id_tweet"].'">
    
    </div>';  
  
     
      
    }//fin while

    ?>
 <!--   </tbody>
    </table>-->
</section>
</div>
<style>#forkongithub a{background:#000;color:#fff;text-decoration:none;font-family:arial,sans-serif;text-align:center;font-weight:bold;padding:5px 40px;font-size:1rem;line-height:2rem;position:relative;transition:0.5s;}#forkongithub a:hover{background:#c11;color:#fff;}#forkongithub a::before,#forkongithub a::after{content:"";width:100%;display:block;position:absolute;top:1px;left:0;height:1px;background:#fff;}#forkongithub a::after{bottom:1px;top:auto;}@media screen and (min-width:800px){#forkongithub{position:fixed;display:block;top:0;right:0;width:200px;overflow:hidden;height:200px;z-index:9999;}#forkongithub a{width:200px;position:absolute;top:60px;right:-60px;transform:rotate(45deg);-webkit-transform:rotate(45deg);-ms-transform:rotate(45deg);-moz-transform:rotate(45deg);-o-transform:rotate(45deg);box-shadow:4px 4px 10px rgba(0,0,0,0.8);}}</style><span id="forkongithub"><a target="_blank" href="https://github.com/C1b3r/fovstar">Fork me on GitHub</a></span>

<script id="twitter-wjs" type="text/javascript" defer src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
<script type="text/javascript" defer src="script.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js" charset="utf-8"></script> 
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" charset="utf-8"></script>  -->
<script>
//   $(document).ready(function () {
//     $('#example').DataTable();
// });

// setTimeout(function() {

//   document.getElementById("loader").classList.add("hide-loader");
//   }, 4000);


</script>

</body>
</html>