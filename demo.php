<?php

// Устройство входа
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

$size = isMobile() ? ['mic', 'min', 'med'] : ['min', 'med', 'big'] ; // Допустимые размеры в зависимости от устройства
$directory = __DIR__ . '/gallery/';
$images = array_diff(scandir($directory), array('..', '.')); // Все изображения в галерее
$link = 'http://winestyle/generator.php?name='; // Ссылка на изображение

// Фильтр
  $extensions = ['.bmp', '.jpg', '.jpeg', '.gif', '.png'];
  $array1 = [];
  $k=0;
  foreach ($images as $key => $img) {
      $img = strtolower(strrchr($img, '.'));
      $array1= [$key => $img];
      $k++;
  }
  $dif = array_diff($array1, $extensions);
  foreach ($images as $key1 => $img){
      foreach ($dif as $key2 => $dif2){
          if ($key1==$key2){unset($images[$key]);}
      }
  }

$gallery = array_chunk($images, 3);

function getImage($url)
{
    return file_get_contents($url);
    /*
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
    $html = curl_exec($ch);
    curl_close($ch);
    echo $html;
    */
}

?>
    <a href="/">Сбросить</a>
    <input type="radio" id="tab-1" name="tabs" checked>
    <label for="tab-1">Галерея</label>

    <input type="radio" id="tab-2" name="tabs">
    <label for="tab-2">Все размеры выбранного изображения</label>
    <!-- Галерея превью -->
    <table id="gallery-preview" class="gallery"> <?php foreach ($gallery as $row): ?>
            <tr> <?php foreach ($row as $item): ?>
                    <td id="<?php echo $item ?>" class="images">
                        <img src="<?php echo getImage($link . urlencode($item) . '&size=' . $size[0]) ?>">
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
    <!-- Галерея всех размеров выбранного изображения -->
<?php
if (isset($_GET['imageByClick'])){
    ?>
    <table id="gallery-all" class="gallery">
        <tr>
            <?php foreach ($size as $item) {
                $imageByClick = $_GET['imageByClick'];?>
                <td>
                    <img src="<?php echo getImage($link . urlencode($imageByClick) . '&size=' . $item);?> ">
                </td>
            <?php } ?>
        </tr>
    </table>
    <?php
};

