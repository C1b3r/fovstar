<?php
use classes\Init;
use Abraham\TwitterOAuth\TwitterOAuth;
require_once('conf/access.php');
require_once('classes/boot.php');
// OAuth To Twitter
require "twitteroauth-0.7.2/autoload.php";
$boot = new Init;

 ini_set('display_errors', '1');


if(isset($_GET['id'])){
	$ultimoid = $_GET['id'];
}else{
	$ultimoid = "";
}

/* Max id ya que tiene que ser desde el ultimo id para ir guardando todos, más info:
https://stackoverflow.com/questions/48780885/get-all-tweets-and-re-tweets-from-twitter-api

https://stackoverflow.com/questions/24958626/twitter-api-since-id-and-max-id
since id es para los más nuevos desde ese tweet y max id de los más antiguos a partir de ese tweet
https://developer.twitter.com/en/docs/tweets/timelines/api-reference/get-statuses-user_timeline
*/


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
$queryNuevo = "SELECT `id_tweet` FROM `".$_ENV['DB_TABLE']."` order by id_tweet DESC LIMIT 1";

$noHayDatos = false;

if(!empty($ultimoid)){
	$paraborrarybuscar = $ultimoid;
}else{
	$nrecuperar = $boot->select($queryNuevo,1);
	//Si no hay ningún tweet, que busque algo para que la proxima ejecución busque con datos
	if(!$nrecuperar){
		$noHayDatos = true;
	}else{
		$paraborrarybuscar = $nrecuperar['id_tweet'];
	}
	
}
if(!$noHayDatos){
	echo $paraborrarybuscar." el ultimo id<br>";
	//ahora buscamos más nuevos
	$tweets = $connection->get("statuses/user_timeline", ['count' => 200, 'since_id' =>$paraborrarybuscar ,'exclude_replies' => true, 'screen_name' => $boot->username, 'include_rts' => false]);
}else{
	//Como no tengo ningún tweet ni he especificado ninguno a filtrar para que haga desde ese tweet, busco los últimos tweets del usuario, que no me filtre enlaces, contestaciones y los retweets de otras personas (esto lo he sacado con busqueda avanzada)
	$search = "(from:".$boot->username.") -filter:links -filter:replies -filter:retweets";
	// $search = "(from:".$boot->username.") min_retweets:10 -filter:retweets"; //con la api estandar no te permite buscar estos datos
	$tweets = $connection->get("search/tweets", array('q' => $search , 'count' => "200"));
	$tweets = $tweets->statuses;
}

$totalTweets[] = $tweets;
	$page = 0;

	// printing recent tweets on screen
	$start = 1;
	/*echo "<pre>";
	print_r($totalTweets);
	echo "</pre>";*/
	foreach ($totalTweets as $page) {
		foreach ($page as $key) {
			$arrayData = array();
			/*es un array de entidades*/
			$entidades = $key->entities;
			foreach ($entidades as $entidad => $valor) {
				if($entidad == "media"){
				 /* print_r($valor);*/
				 /*Es un array con objetos en su posición*/
				 $arrayData['foto'] = 1;
				 $arrayData['tweet']['url'] = $valor[0]->media_url_https;

				}else{
					$arrayData['foto'] = 0;
				}
			}
			$arrayData['tweet']['id'] = $key->id;
			$arrayData['tweet']['texto'] = $key->text;
			$arrayData['tweet']['rt'] = $key->retweet_count;
			$arrayData['tweet']['fav'] = $key->favorite_count;
			if(!$boot->insert($arrayData)){
				continue;
			  }else{
				echo "<a href='https://twitter.com/".$boot->username."/status/".$key->id."'>".$start . ':' . $key->text .  $key->created_at .' favoritos:'.$key->favorite_count.' retweets:'.$key->retweet_count.'</a> '.$arrayData['tweet']['id'].'<br>';
				$start++;
			  }
		}
	}

?>
