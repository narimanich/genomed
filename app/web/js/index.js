$(document).ready(function () {
    $('#generate-btn').on('click', function () {
        let url = $('#url-original_url').val();

        $.ajax({
            url: '/site/generate',
            method: 'POST',
            data: { url: url },
            success: function (response) {
                if (response.error) {
                    alert(response.error); // или вывод в div
                } else {
                    // Заполняем модальное окно
                    $('#modal-qr-image').attr('src', response.qr);
                    $('#modal-short-url')
                        .attr('href', response.shortUrl)
                        .text(response.shortUrl);

                    // Показываем модальное окно
                    $('#qrModal').modal('show');
                }
            },
            error: function () {
                alert('Произошла ошибка при выполнении запроса.');
            }
        });
    });
});