<?php 
$pdo = new PDO('mysql:dbname=word_storage;host=127.0.0.1','mysql','mysql'); // создаем новый объект подключения к БД и подключаем его во всех файлах
 if ($pdo == true){
	 
 } else {
	 echo 'Error connection';
 }

?>