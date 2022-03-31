window.onload = function () {

    var fileInput = document.getElementById('fileToUpload');
    var fileDisplayArea = document.getElementById('file-display-area');

    fileInput.addEventListener('change', function () {
        var file = fileInput.files[0];

        var reader = new FileReader();

        reader.onload = function () {
            fileDisplayArea.innerHTML = "";

            var img = new Image(300);

            img.src = reader.result;

            fileDisplayArea.appendChild(img);
        }

        reader.readAsDataURL(file);

    });

}