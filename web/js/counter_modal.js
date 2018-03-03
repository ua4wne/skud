$(document).ready(function () {
    $('.add-modal').click(function(event) { // нажатие на кнопку - выпадает модальное окно
        event.preventDefault();
        var modalContainer = $('#my-modal');
        //var modalBody = modalContainer.find('.modal-body');
        modalContainer.modal({show:true});
        $.ajax({
            url: '/main/registration/show-modal',
            type: "GET",
            data: {'modal':'true'},
            success: function (res) {
                //alert("Сервер вернул вот что: " + res);
                $('.modal-body').html(res);
                modalContainer.modal({show:true});
            },
            error: function(){
                alert('Error!');
            }
        });
    });
    $(document).on("submit", '.modal-form', function (e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: '/main/registration/add-modal',
            type: "POST",
            data: form.serialize(),
            success: function (res) {
                //alert("Сервер вернул вот что: " + res);
                if(res=='OK')
                    $('#counterlog-val').val('');
                else
                    alert('Ошибка при передаче данных!');
            },
            error: function(){
                alert('Error!');
            }
        });

    });
});