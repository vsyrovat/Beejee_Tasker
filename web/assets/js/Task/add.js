"use strict";

$('.js-task-preview-button').click(function(){
    $.post('/task/preview');
});
