<div align=center>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="docs[]" multiple> <br>
        <textarea name="description"></textarea> <br>
        <input type="submit">
    </form>
</div>


<?php

$fromfdir = __DIR__ . DIRECTORY_SEPARATOR . 'fromfile'; // директория куда будут помещены файлы сформированные из файла
$fromtextdir = __DIR__ . DIRECTORY_SEPARATOR . 'fromtextarea';// директория куда будут помещены файлы сформированные из текстового поля

$startnamef = 'fromf.txt'; // базовое имя для файлов сформированных из файла
$startnamet = 'fromt.txt'; // базовое имя для файлов сформированных из текстового поля


if ($_POST['description'] == '' and $_FILES['docs']['name']['0'] == '') {
    echo 'Введите текст или загрузите файл с текстом';
} else {
    if ($_POST['description'] !== '' and $_FILES['docs']['name']['0'] !== '') { // обрабатываем все поля
        $array_from_tarea = count_word_from_text($_POST['description']);
        array_to_csv($array_from_tarea, save_file($startnamet, $fromtextdir));
        if ($_FILES['docs']['type']['0'] !== 'text/plain') {
            echo 'Загружаемый файл не является тестовым !';
        } else {
            $text_from_file = get_text_from_file($_FILES['docs']);
            $array_from_file = count_word_from_text($text_from_file);
            array_to_csv($array_from_file, save_file($startnamef, $fromfdir));
        }
    } else {
        if ($_POST['description'] !== '' and $_FILES['docs']['name']['0'] == '') { //обрабатываем текстовое поле
            $array_from_tarea = count_word_from_text($_POST['description']);
            array_to_csv($array_from_tarea, save_file($startnamet, $fromtextdir));
        }
        if ($_POST['description'] == '' and $_FILES['docs']['name']['0'] !== '') { //обрабатываем файловое поле
            if ($_FILES['docs']['type']['0'] !== 'text/plain') {
                echo 'Загружаемый файл не является тестовым !';
            } else {
                $text_from_file = get_text_from_file($_FILES['docs']);
                $array_from_file = count_word_from_text($text_from_file);
                array_to_csv($array_from_file, save_file($startnamef, $fromfdir));
            }
        }
    }
}


function array_to_csv($array, $outfile)// записывает ассоциативный массив (слово => количество слов) в файл
{ 
    foreach ($array as $word => $count) {

        fwrite(fopen($outfile, 'a'), "{$word};{$count}" . PHP_EOL);
    }

}


function save_file($filename, $dir) // функция сохраняет файл с префиксом, который позволяет не затирать файл
{
    if (is_dir($dir)) {

    } else {
        mkdir($dir);
    }
    $info = pathinfo($filename);
    $name = $dir . '/' . $info['filename'];
    $prefix = '';
    $ext = (empty($info['extension'])) ? 'csv' : '.' . 'csv';

    if (is_file($name . $ext)) {
        $i = 1;
        $prefix = '_' . $i;
        while (is_file($name . $prefix . $ext)) {
            $prefix = '_' . ++$i;
        }
    }

    return $name . $prefix . $ext;
}


function get_text_from_file($docs)// функция возвращает текст взятый из загруженного текстового файла
{ 
    foreach ($docs['tmp_name'] as $index => $tmpPath) {
        if (!array_key_exists($index, $docs['name'])) {
            continue;
        }

        move_uploaded_file($tmpPath, __DIR__ . DIRECTORY_SEPARATOR . $docs['name'][$index]);

        $contents = file_get_contents($docs['name'][$index]);
        unlink($docs['name'][$index]);
    }


    return $contents;
}


function count_word_from_text($text_to_count)//  функция создает ассоциированный массив где ключ - слово, а значение - количество слов
{ 
    if ($text_to_count == '') {
        exit;
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
