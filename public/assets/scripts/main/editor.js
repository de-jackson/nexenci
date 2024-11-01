window.editor = null;

ClassicEditor
    .create(document.querySelector('.editor'))
    .then(newEditor => {
        window.editor = newEditor;
    })
    .catch(error => {
        console.error(error);
    });