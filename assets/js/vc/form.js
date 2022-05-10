
$('.flink-vc.form').each(function() {

    const form = $(this);

    form.submit(function(e) {
        
        e.preventDefault();

        // Validation

        form.unbind('submit').submit();
    });

});
