
$('.flink-vc.form').each(function() {

    const form = $(this).find('form');

    form.submit(function(e) {
        
        e.preventDefault();

        // Validation

        form.unbind('submit').submit();
    });

});
