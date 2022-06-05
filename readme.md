# Fovstar

## Un visualizador de tweets muy basico
Usando la librería  [twitteroauth](https://twitteroauth.com/) v0.7.2.
Este repositorio permitirá recopilar tweets de los últimos 10 días del usuario que tengas configurado.
También te permite meter manualmente(manual.php?secret_token=VALORTOKEN) un tweet si accedes a manual.php pero tienes que previamente poner un token de seguridad para poder acceder (puede modificarse para poner una mayor seguridad pero es un ejemplo)

## Configuración
Para configurar este repositorio únicamente tienes que tener dada de alta una aplicación en [twitter developer](https://developer.twitter.com/en) para así obtener las keys para luego usar en las librerías.
En la carpeta conf hay un archivo llamado **access-example.php**, tienes que renombrarlo o hacer uno nuevo llamado access.php. A continuación ejecutar el script .sql en tu base de datos para crear la tabla,el **nombre de la tabla puede cambiarse**.
Explico que debe sustituirse en el archivo de configuración:

| Variables  | Significado |
| ------------- |:-------------:|
| DB_HOST     | Nombre del host de base de datos(localhost por ejemplo)     |
| DB_USERNAME     | Usuario de la base de datos     |
| DB_PASSWORD    | Contraseña de la base de datos     |
| DB_NAME     | Nombre de la base de datos    |
| DB_TABLE     | Variable que actúa sobre el nombre de la tabla de base de datos,recomendable cambiar el nombre  |
| CONSUMER_KEY     | Key de twitter      |
| CONSUMER_SECRET    | Key de twitter     |
| OAUTH_TOKEN    | Key de twitter     |
| OAUTH_SECRET    | Key de twitter      |
| USER    | User de twitter que quieres que se busque     |
| secret_token    | Token secreto para acceder al archivo manual |
###### Aclaración
Esta librería solo busca entre tus últimos tweets, para ello es necesario ejecutar el fichero **busquedanuevos.php** ,en caso de que se le especifique el id de un tweet busquedanuevos.php?id=idtweet buscará a partir de ese tweet. Actualmente la api de twitter no permite la busqueda mínima por retweets, para ello está el apartado de manual ya que el sistema de búsqueda de la web si permite realizar esta consulta **(from:twitterUsername) min_retweets:10 -filter:retweets**