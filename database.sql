CREATE TABLE `pruebastwitter` (
  `id_tweet` bigint(20) NOT NULL PRIMARY KEY,
  `usuario` varchar(250) NOT NULL,
  `texto` varchar(500) NOT NULL,
  `nret` int(11) NOT NULL,
  `nfav` int(11) NOT NULL,
  `urlimagen` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;