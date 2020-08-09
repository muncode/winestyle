<?php
require_once 'db.php';

if(isset($_GET['name'])&isset($_GET['size'])) {

    $size = $_GET['size'];
    $name = $_GET['name'];

    // Поиск файла с расширением по названию изображения из GET запроса
    $directory = __DIR__ . '/gallery/';
    $images = array_diff(scandir($directory), array('..', '.')); // Все изображения в галерее
    foreach ($images as $img) {
        $info = pathinfo($img);
        $filename = basename($img,'.'.$info['extension']);
        if ($name == $filename){
            $name = $img;
        }
    }

    // Галерея изображений
    $src = __DIR__ . '/gallery/' . $name;
    // Размер изображения по запросу
    $query = "select size_w, size_h from winestyle_size where name='$size'";
    $result = $mysqli->query($query)->fetch_assoc();
    $size_w = $result['size_w'];
    $size_h = $result['size_h'];

    // Создание нового изображения на основе входящего
    function openImage($file)
    {
        // *** Get extension
        $extension = strtolower(strrchr($file, '.'));

        switch ($extension) {
            case '.jpg':
            case '.jpeg':
                $img = @imagecreatefromjpeg($file);
                break;
            case '.gif':
                $img = @imagecreatefromgif($file);
                break;
            case '.png':
                $img = @imagecreatefrompng($file);
                break;
            case '.bmp':
                $img = @imagecreatefrombmp($file);
                break;
            default:
                $img = false;
                break;
        }
        return $img;
    }
    // Генерация нового размера изображения
    function imageresize($outfile, $infile, $neww, $newh, $quality = '100')
    {
        if (openImage($infile)) {
            $im = openImage($infile);

            $k1 = $neww / imagesx($im);
            $k2 = $newh / imagesy($im);

            $k = $k1 > $k2 ? $k2 : $k1;

            $w = intval(imagesx($im) * $k);
            $h = intval(imagesy($im) * $k);

            $im1 = imagecreatetruecolor($w, $h);

            imagecopyresampled($im1, $im, 0, 0, 0, 0, $w, $h, imagesx($im), imagesy($im));

            imagejpeg($im1, $outfile, $quality);

            imagedestroy($im);
            imagedestroy($im1);
        } else {
            echo 'Не верный формат изображения';
        }
    }
    // cache и название нового изображения
    $saveTo = 'cache/' . $name . '-' . $size . '-' . $size_w . 'x' . $size_h . '.jpg';
    // Проверка на наличие кэша
    (file_exists($saveTo)) ?: imageresize($saveTo, $src, $size_w, $size_h);

    echo $saveTo;
}

