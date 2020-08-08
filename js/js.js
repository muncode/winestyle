// Если выбрано превью-изображение - отобразить все допустимые размеры
if (window.location.search.replace( '?', '')) {
    $(document).ready(function() {
        document.getElementById("tab-2").checked=true;
    });
}
// Клики по превью
$( document ).ready(function() {
    $("#gallery-preview").on("click", "td",function() {
        myFunction(this.id);
    });
});
// Передача id изображения в галерею всех допустимых размеров
function myFunction(qwe) {
    console.log(qwe);
    document.location.href='?imageByClick=' + qwe;
}