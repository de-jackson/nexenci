// filepond configuration

(function () {
    'use strict'

})();

// check if image exists
var imageExists = function (imgUrl) {
    if (!imgUrl) {
        return false;
    }
    return new Promise(res => {
        const image = new Image();
        image.onload = () => res(true);
        image.onerror = () => res(false);
        image.src = imgUrl;
    });
}
// Image preview
var previewImageFile = function (event) {
    var output = document.getElementById('preview-image');
    output.removeAttribute("class");
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
        URL.revokeObjectURL(output.src)
    }
};

var previewIdImage = function (event, eleID) {
    var output = document.getElementById('preview-id'+eleID);
    output.removeAttribute("class");
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
        URL.revokeObjectURL(output.src)
    }
};

var previewSignature = function (event) {
    var output = document.getElementById('preview-sign');
    output.removeAttribute("class");
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
        URL.revokeObjectURL(output.src)
    }
};

var previewAttachment = function (fileInputId, imgId) {
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById(fileInputId).files[0]);
    oFReader.onload = function (oFREvent) {
        document.getElementById(imgId).src = oFREvent.target.result;
    };
};