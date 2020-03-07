<div align=center>
	<a href="form_db.php"><button type="submit">Загрузить текст</button></a>
</div>

<?php 
include 'db.php';

 $selectQuery = 'SELECT id, content, words_count FROM uploaded_text'; // запрос выводит все первые 100 символов основного текста 
 $text_fromdb_to_count = $pdo->query($selectQuery)->fetchAll(PDO::FETCH_ASSOC);


foreach ($text_fromdb_to_count as $result){
echo '<b>Идентификатор:</b> '.$result['id'];
echo '		'.mb_substr(strip_tags($result['content']),0,100, 'utf-8').'...';
echo '<b>Количество слов:</b> '.$result['words_count'].'<br/>';
echo '<form action="detail.php"method="post" enctype="multipart/form-data">';
echo '<button type="submit" name="button" value="'.$result['id'].'">Смотреть детально</button></form>'; // присваиваем значению кнопки id для перехода на детальную страницу
echo '<p>______________________________________________________________________________________________________________________________________________________</p>';
}
?>




