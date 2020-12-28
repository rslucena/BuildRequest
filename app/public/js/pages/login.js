(function ($) {

    'use strict';

    $('form').on('submit', function (e) {

        e.preventDefault();

        let errors = 0;

        $('.required').each(function (index, el) {
            errors += validateEmpty(el['id']);
        });

        if (errors === 0) {
            $.ajax({

                async: true,
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                url: '/login/createlogin/',
                data: createFormData('f-log'),

                success: function (data) {

                    if (data['status'] === 'success') {
                        newtoast('Sucesso', 'Login realizado com sucesso.', 'success');
                        setTimeout(function () {
                            window.location.replace("/");
                        }, 2000);
                        return;
                    }

                    newtoast('Opss...', 'Seu e-mail ou senha podem estar incorretos. Confira o link "Esqueci minha senha" para mais informações.', 'error');

                }
            });

        } else {

            newtoast('Opss...', 'Parece que você esqueceu de preencher algum campo.', 'error');

        }

    });

})(jQuery);