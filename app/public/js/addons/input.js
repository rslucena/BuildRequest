/**
 * Counts the number of valid fields based on the element
 *
 * @param elem
 * @return number
 */
function validateEmpty(elem) {
    let errors = 0;
    let element = $('#' + elem);

    if (element.val() === '' || element.val() === 0 || element.val() === 'R$ 0,00') {
        setInvalid(element);
        errors++;
    }

    return errors;
}

/**
 * Apply a border to a class
 *
 * @param elem
 */
function setInvalid(elem) {

    if (elem.attr("multiple"))
        elem.parent().find('.multi-wrapper').css({'border': '1px solid #e94437'});

    elem.closest('input').css({'border': '1px solid #e94437'});
}

/**
 * Creates a formdata based on
 * a class.form element
 *
 * @param elem
 * @returns {null|FormData}
 */
function createFormData(elem) {

    let _form = document.getElementById(elem);

    if (_form.length < 0) {
        return null;
    }

    return new FormData(_form);

}

/**
 * Changes the default behavior of send buttons
 * Adding color and changing text
 *
 * @param elem
 * @readonly
 */
function status_ajax(elem){

    let submit = elem.find('[type="submit"]');

    if( submit.data('text') === undefined){
        submit.attr('data-text', submit.val());
    }

    let progress = submit.hasClass('_inProgress');

    if( !progress ){

        submit.val('Carregando...');

        submit.addClass('_inProgress');

    }else{

        submit.removeAttr('data-text');

        submit.val( submit.data('text') );

        submit.removeClass('_inProgress');

    }

}

/**
 * Create the default event by
 * pressing Enter to trigger a
 * submit event for the closest form
 */
(function ($) {

    $('input').keypress(function (e) {
        if (e.which === '13') {
            $(this).closest('form').submit();
            return false;
        }
    });

})(jQuery);