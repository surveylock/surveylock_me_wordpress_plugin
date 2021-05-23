jQuery(function ($) {
    $('[data-slm-custom-value-setting]').each(function () {
        const $holder = $(this),
            fieldsSelector = 'input[type="text"], .wp-picker-container';

        $holder.on('change', 'input[type="checkbox"]', function () {
            $holder.find(fieldsSelector)[$(this).prop('checked') ? 'fadeIn' : 'fadeOut']('fast');
        });
    });
    $('[data-slm-colorpicker]').wpColorPicker();
});