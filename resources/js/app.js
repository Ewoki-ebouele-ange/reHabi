import './bootstrap';
import '../css/app.css';

document.getElementById('custom-button').addEventListener('click', function(){
    document.getElementById('file-input').click();
});

document.getElementById('file-input').addEventListener('change', function(){
    document.getElementById('upload-form').submit();
});

document.getElementById('custom-button1').addEventListener('click', function(){
    document.getElementById('file-input1').click();
});

document.getElementById('file-input1').addEventListener('change', function(){
    document.getElementById('upload-form1').submit();
});