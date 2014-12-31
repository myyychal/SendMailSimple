function uploadList(fieldFrom, fieldTo) {
    var file = document.getElementById(fieldFrom).files[0];
    if (file) {
        var reader = new FileReader();
        reader.readAsText(file, "UTF-8");
        reader.onload = function (evt) {
            document.getElementById(fieldTo).value = evt.target.result;
        }
        reader.onerror = function (evt) {
            document.getElementById(fieldTo).value = "error reading file";
        }
    }
}