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
  $array = [];
  $filename = [];
  $k=0;
  foreach ($images as $img) {
      $info = pathinfo($img);
      $filename[$k] = basename($img,'.'.$info['extension']);
      $img = strtolower(strrchr($img, '.'));
      $array[$k] = $img;
      $k++;
  }

  $dif = array_diff($array, $extensions);
  foreach ($images as $key1 => $img){
      foreach ($dif as $key2 => $dif2){
          if ($key1==$key2){
              unset($images[$key1]);
              unset($filename[$key1]);
          }
      }
  }
$gallery = array_chunk($filename, 3);
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
                        <img src="<?php echo file_get_contents($link . urlencode($item) . '&size=' . $size[0]) ?>">
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
                    <img src="<?php echo file_get_contents($link . urlencode($imageByClick) . '&size=' . $item);?> ">
                </td>
            <?php } ?>
        </tr>
    </table>
    <?php
};

