<?php
require_once('conf/access.php');
 ini_set('display_errors', '1');

 $enlace = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);
if(isset($_GET['id'])){
$ultimoid=$_GET['id'];
}else{
	$ultimoid="";
}

mysqli_set_charset($enlace, "utf8");
/* Max id ya que tiene que ser desde el ultimo id para ir guardando todos, más info:
https://stackoverflow.com/questions/48780885/get-all-tweets-and-re-tweets-from-twitter-api

https://stackoverflow.com/questions/24958626/twitter-api-since-id-and-max-id
since id es para los más nuevos desde ese tweet y max id de los más antiguos a partir de ese tweet
https://developer.twitter.com/en/docs/tweets/timelines/api-reference/get-statuses-user_timeline
*/
$username = $_ENV['USER'];
// OAuth To Twitter
require "twitteroauth-0.7.2/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;
$connection = new TwitterOAuth($_ENV['CONSUMER_KEY'], $_ENV['CONSUMER_SECRET'], $_ENV['OAUTH_TOKEN'], $_ENV['OAUTH_SECRET']);
$content = $connection->get("account/verify_credentials");

//Si no encuentra max id en base de datos(de ese usuario) hace la consulta tal cual
//$tweets = $connection->get("statuses/user_timeline", ['count' => 200, 'exclude_replies' => true, 'screen_name' => $username, 'include_rts' => false]);
//si no, hace lo siguiente
//$tweets = $connection->get("statuses/user_timeline", ['count' => 200, 'since_id' =>'idultimoenbasededatos' ,'exclude_replies' => true, 'screen_name' => $username, 'include_rts' => false]);
//los más nuevos con since id 
//los mas antiguos con max id 
/*
ejemplo, listo los 200, el último, como lo mete el foreach, hago lo siguiente, lo 
borro, y busco con ese y fin, lo vuelve a meter y ya no tengo errores en la base de datos.
hay limite de 3200 tweets por usuario, los demas los tengo que meter a mano
Para ello, basta con meter en max id el id del tweet y me sacará ese(ya que saca ese y más antiguos)
*/
//Primero pregunto
$queryNuevo="SELECT `id_tweet` FROM `".$_ENV['DB_TABLE']."` order by id_tweet DESC LIMIT 1";
$recuperar=mysqli_query($enlace,$queryNuevo);
$nrecuperar=mysqli_fetch_assoc($recuperar);
if(!empty($ultimoid)){
	$paraborrarybuscar=$ultimoid;
}else{
	$paraborrarybuscar=$nrecuperar['id_tweet'];
}
echo $paraborrarybuscar." el ultimo id<br>";

//ahora buscamos más nuevos
$tweets = $connection->get("statuses/user_timeline", ['count' => 200, 'since_id' =>$paraborrarybuscar ,'exclude_replies' => true, 'screen_name' => $username, 'include_rts' => false]);

$totalTweets[] = $tweets;
	$page = 0;

	// printing recent tweets on screen
	$start = 1;
	/*echo "<pre>";
	print_r($totalTweets);
	echo "</pre>";*/
	foreach ($totalTweets as $page) {
		foreach ($page as $key) {
			/*es un array de entidades*/
			$entidades=$key->entities;
			foreach ($entidades as $entidad => $valor) {
				if($entidad=="media"){
				  $tienefoto="si";
				 /* print_r($valor);*/
				 /*Es un array con objetos en su posición*/
				  $tienefoto=$valor[0]->media_url_https;

				}else{
				$tienefoto="no";
				}
			}
			$id = mysqli_real_escape_string($enlace, $key->id);
			$texto= mysqli_real_escape_string($enlace, $key->text);
			$nret = mysqli_real_escape_string($enlace, $key->retweet_count);
			$nfav = mysqli_real_escape_string($enlace, $key->favorite_count);

			$query="INSERT INTO `".$_ENV['DB_TABLE']."`(`id_tweet`, `usuario`,`texto`, `nret`, `nfav`, `urlimagen`) VALUES ('$id','$username','$texto','$nret','$nfav','$tienefoto')";
			if ($resultado = mysqli_query($enlace, $query)) { //or die(mysqli_error($enlace))
			echo "<a href='https://twitter.com/".$username."/status/".$key->id."'>".$start . ':' . $key->text .  $key->created_at .' favoritos:'.$key->favorite_count.' retweets:'.$key->retweet_count.'</a> '.$id.'<br>';
			$start++;
			}else{
				continue;
			}
		}
	}
mysqli_close($enlace);
?>
