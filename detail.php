<div align=center>
   
<a href="main.php"><button type="submit">На главную</button></a>

</div>

<?php 
include 'db.php';
?>


<div align=justify>
<?php 
$selectQuery = 'SELECT content, date, words_count FROM uploaded_text WHERE id = "'.$_POST['button'].'"'; //запрос выводит основной текст и его атрибуты в зависимости от id
$all_text = $pdo->query($selectQuery)->fetchAll(PDO::FETCH_ASSOC);

foreach ($all_text as $res){

echo $res['content'].'<br/>';
echo '<b>Дата добавления: '.date("d-m-Y",strtotime($res['date'])).'</b><br/>';
echo '<b>Количество слов: '.$res['words_count'].'</b><br/>';

}
?>
</div>



<?php 

$selectQuery = 'SELECT text_id, word, count FROM word WHERE text_id = "'.$_POST['button'].'"'; // запрос выводит "слово-количество" из таблицы word по id 
$text_detail = $pdo->query($selectQuery)->fetchAll(PDO::FETCH_ASSOC);


?>
<div align="center">
<table  border="1" cellpadding="2">
<tbody>
<?php 

foreach ($text_detail as $result){?>

  <tr>
      <td><?php echo $result['word'];?></td>
      <td><?php echo $result['count'];?></td>
 </tr>

<?php }
?>   

</tbody>
</table>
</div>


















