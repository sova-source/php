<?php function gomain ($new_location) {header("Location: ".$new_location);} 
if (!empty($_POST['description']) or !empty($_FILES['docs']['name']['0']) ){gomain('main.php');} else {} // если глобальным переменным присвоены значения переходим на главную
	
?>
<div align=center>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="docs[]" multiple> <br>
        <textarea name="description"></textarea> <br>
        <input type="submit">
    </form>
</div>


<?php 
include 'db.php';


 function upload_text($text_to_count){ // функция загружает в базу текст целиком и в другую таблицу по словам, принимает текст
 $pdo = new PDO('mysql:dbname=word_storage;host=127.0.0.1','mysql','mysql');
 
 $words_count = count (explode(' ',$text_to_count))+1;
 $insertQuery = 'INSERT INTO uploaded_text (content,words_count) VALUES ("'.$text_to_count.'","'.$words_count.'")';
 $statement = $pdo->query($insertQuery);
 
 $selectQuery = 'SELECT id FROM uploaded_text WHERE content="'.$text_to_count.'"';
 $id = $pdo->query($selectQuery)->fetch(PDO::FETCH_ASSOC);
echo var_dump($id['id']);
 
 $text_from_db_array = count_word_from_text ($text_to_count);
	foreach ($text_from_db_array as $word => $count) {
	$insertQuery = 'INSERT INTO word (text_id, word, count) VALUES ("'.$id['id'].'","'.$word.'","'.$count.'")';
	$statement = $pdo->query($insertQuery);
	}
			
}
	
	
$textarea = $_POST['description'];
$filearea = $_FILES['docs']['name']['0'];
$istext = $_FILES['docs']['type']['0'];

function get_text_fromf (){
	$text_from_file = get_text_from_file($_FILES['docs']);
	upload_text($text_from_file);
}


function get_text_fromt (){
	upload_text($_POST['description']);
}


if ($textarea == '' and $filearea == '') {
    echo 'Введите текст или загрузите файл с текстом';
} else {
    if ($textarea !== '' and $filearea !== '') { // обрабатываем все поля
        get_text_fromt ();
        if ($istext !== 'text/plain') {
            echo 'Загружаемый файл не является тестовым !';
        } else {
            get_text_fromf();
        }
    } else {
        if ($textarea!== '' and $filearea == '') { //обрабатываем текстовое поле
            
            get_text_fromt();
        }
        if ($textarea == '' and $filearea !== '') { //обрабатываем файловое поле
            if ($istext !== 'text/plain') {
                echo 'Загружаемый файл не является тестовым !';
            } else {
                get_text_fromf();
				
            }
        }
    }
}


function get_text_from_file($docs)// функция возвращает текст взятый из загруженного текстового файла
{ 
    foreach ($docs['tmp_name'] as $index => $tmpPath) {
        if (!array_key_exists($index, $docs['name'])) {
            continue;
        }

		$contents = file_get_contents($tmpPath);  
    }

    return $contents;
}


function count_word_from_text($text_to_count)//  функция создает ассоциированный массив где ключ - слово, а значение - количество слов
{ 
    if ($text_to_count == '') {
    } else {
        $char_to_delete = [".", "-", "...", "!", "*", "?", ',', ",", "\n", "\n\r", '\n\r', "\n", '\n']; // задаем массив непечатаемых символов и прочих для их удаления
        $spaces = ['  ', '   ', '    ', '    ', '	']; // задаем массив строк с различными вариантами пробелов для их отлова и удаления
        $text_without_char = str_replace($char_to_delete, '', $text_to_count); // чистим текст от лишних символов, можно конечно исползовать REGEXP
        $text_without_spaces = str_replace($spaces, ' ', $text_without_char); // чистим текст от 2-х и более пробелов
        $text_to_array = explode(' ', trim(mb_strtolower($text_without_spaces))); // добавляем текст в массив так, чтобы кажде слово было отдельным его элементом
        $array_assoc = []; // объявляем пустой массив

        foreach ($text_to_array as $word) {
            if (array_key_exists($word, $array_assoc)) {
                $array_assoc[$word]++;
            } else {
                $array_assoc[$word] = 1;
            }
        }
        return $array_assoc;
    }
}

?>