<?php
namespace classes;

class Init
{

  private $dbhost,
          $dbname,
          $dbusername,
          $dbpassword;
  public $conexion,
         $username;        
  public function __construct()
  {
    $this->dbhost = $_ENV['DB_HOST'];
    $this->dbname = $_ENV['DB_NAME'];
    $this->dbusername = $_ENV['DB_USERNAME'];
    $this->dbpassword = $_ENV['DB_PASSWORD'];
    $this->username = $_ENV['USER'];
    $this->conectar();
  }

  public function conectar()
  {
    try {
      $this->conexion = new \PDO("mysql:=".$this->dbhost.";dbname=".$this->dbname.";charset=utf8mb4", $this->dbusername, $this->dbpassword);
        // set the PDO error mode to exception
      $this->conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      $this->conexion->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
      //  echo "Connected successfully";
    } catch(\PDOException $e) {
      die($e->getMessage());
    }
  }

  public function select($sql,$array = 0)
  {
    $stmt = $this->conexion->query($sql);
    // return $stmt->fetch();
    if($array){
      return $stmt->fetch();
    }else{
      return $stmt;
    }
  }

  public function getTweets($limte = 20)
  {
    $query = "SELECT * FROM `".$_ENV['DB_TABLE']."` where usuario='".$this->username."' order by nfav DESC limit $limte";
    return $this->select($query);
  }

  public function insert($post)
  {
      $data = [
        'id' => $post['tweet']['id'],
        'usuario' => $this->username,
        'texto' => $post['tweet']['texto'],
        'nret' => $post['tweet']['rt'],
        'nfav' => $post['tweet']['fav']
    ];
    if($this->checkId($data['id'])){
      return false;
    }
    //Si es == 1 entonces es true
    if($post['foto']){
      $data['foto'] = $post['tweet']['url'];
    }else{
      $data['foto'] = "no";
    }
    $sql = "INSERT INTO `".$_ENV['DB_TABLE']."` (`id_tweet`, `usuario`,`texto`, `nret`, `nfav`, `urlimagen`)  VALUES (:id,:usuario, :texto, :nret,:nfav,:foto)";
    $stmt = $this->conexion->prepare($sql);
    if($stmt->execute($data)){
      return true;
    }else{
      return false;
    }
  }

  public function checkId($id)
  {
    $stmt = $this->conexion->prepare("SELECT count(*) from `".$_ENV['DB_TABLE']."` WHERE `id_tweet`= :id_tweet");
    $stmt->execute(array($id));
    $norows = $stmt->fetchColumn(); 
    if ($norows > 0) {
       return true;
    } else {
      return false;
    }
  }



}