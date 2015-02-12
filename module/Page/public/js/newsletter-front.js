jQuery(document).ready(function(){
    $('#subscribe').submit(function(e){
        e.preventDefault();

        var action = $(this).attr('action');
        var WzorMaila = /^[0-9a-z_.-]+@[0-9a-z.-]+\.[a-z]{2,3}$/i
        var aEmail = $('#name').val();
        if(!aEmail) {
            $('#name').css('border', '1px solid red').after('<span class="red name">*Wprowadź email</span>');
            emailOk = 0;
        } else {
            $('.name').remove();
            if (!WzorMaila.test(aEmail)) {
                $('#name').css('border', '1px solid red').after('<span class="red name">*Wprowadź poprawny email</span>');
                emailOk = 0;
            } else {
                emailOk = 1;
                $('.name').remove();
                $('#name').css('border', '1px solid #343434');
            }
        }

        if( emailOk == 1 )
        {
            $('#submit')
                .after('<img src="/img/ajax-loader.gif" class="loader1" />')
                .attr('disabled','disabled');

            $.post(action, {
                email: $('#name').val()
            }, function(data){
                document.getElementById('message').innerHTML = 'Na podany adres została wysłana wiadomość potwierdzająca subskrypcję.';
                $('#message').slideDown('slow');
                $('#subscribe img.loader1').fadeOut('slow',function(){$(this).remove()});
                $('#submit').removeAttr('disabled');
                if(data.match('success') != null) $('#subscribe').slideUp('slow');

                $('#email').val('');

            });
        }
    });
});