jQuery(document).ready(function($) {
    $('.nav-tab').click(function(e) {
        e.preventDefault();
        $('.nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        $('.tab-content').hide();
        $($(this).attr('href')).show();
    });

    const forms = $('form');
    forms.each(function() {
        const form = $(this);
        const inputs = form.find('input, textarea');

        inputs.each(function() {
            const saved = localStorage.getItem($(this).attr('name'));
            if (saved) $(this).val(saved);
        });

        inputs.on('input', function() {
            localStorage.setItem($(this).attr('name'), $(this).val());
        });

        form.on('submit', function() {
            inputs.each(function() {
                localStorage.removeItem($(this).attr('name'));
            });
        });
    });
});