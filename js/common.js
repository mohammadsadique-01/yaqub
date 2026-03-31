$(function () {
    $('#addCreditor').on('click', function() {
        $(this).addClass('d-none');
        $('#form')[0].reset();
        $('#filterCard, #tableSection').addClass('d-none');
        $('#formSection, #backToList').removeClass('d-none')
        $('#formSection').addClass('card card-outline card-primary');
    });

    $('#backToList').on('click', function() {
        $(this).addClass('d-none');
        $('#formSection').addClass('d-none');
        $('#tableSection, #filterCard, #addCreditor').removeClass('d-none');
    });

})