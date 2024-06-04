import './bootstrap';

document.getElementById('custom-button').addEventListener('click', function(){
    document.getElementById('file-input').click();
});

document.getElementById('file-input').addEventListener('change', function(){
    document.getElementById('upload-form').submit();
});