jQuery(document).ready(function(){
    $('#defaultReal').realperson();
	$('#comments_form').submit(function(){
            var action = $(this).attr('action');

		$("#message").slideUp(750,function() {
		$('#message').hide();

        var realPerson = $('input[name="defaultReal"]').val();
        var realPersonHash = $('input[name="defaultRealHash"]').val();

            $.ajax({
                type: "POST",
                url: "/captcha",
                dataType : 'json',
                data: {
                    realPerson: realPerson,
                    realPersonHash: realPersonHash
                },
                success: function(json)
                {
                    if(json.status == 'success')
                    {
                        $('.email').remove();
                        $('.name').remove();
                        $('.subject').remove();
                        $('.comments').remove();

                        $('input[name="defaultReal"]').css('border', '1px solid #343434');
                        $('.captcha').remove();

                        if($('#name').val().length > 0) {
                            nameOk = 1;
                            $('.name').remove();
                            $('#name').css('border', '1px solid #343434');

                        } else {
                            nameOk = 0;
                            $('#name').css('border', '1px solid red').after('<span class="red name">*Wprowadź imię</span>');
                        }
                        if($('#subject').val().length > 0) {
                            subjectOk = 1;
                            $('.subject').remove();
                            $('#subject').css('border', '1px solid #343434');

                        } else {
                            subjectOk = 0;
                            $('#subject').css('border', '1px solid red').after('<span class="red subject">*Wprowadź temat</span>');
                        }
                        if($('#comments').val().length > 0) {
                            commentsOk = 1;
                            $('.comments').remove();
                            $('#comments').css('border', '1px solid #343434');

                        } else {
                            commentsOk = 0;
                            $('#comments').css('border', '1px solid red').after('<span class="red comments">*Wprowadź treść</span>');
                        }
                        var WzorMaila = /^[0-9a-z_.-]+@[0-9a-z.-]+\.[a-z]{2,3}$/i
                        var aEmail = $('#email').val();
                        if(!aEmail) {
                            $('#email').css('border', '1px solid red').after('<span class="red email">*Wprowadź email</span>');
                            emailOk = 0;
                        } else {
                            $('.email').remove();
                            if (!WzorMaila.test(aEmail)) {
                                $('#email').css('border', '1px solid red').after('<span class="red email">*Wprowadź poprawny email</span>');
                                emailOk = 0;
                            } else {
                                emailOk = 1;
                                $('.email').remove();
                                $('#email').css('border', '1px solid #343434');
                            }
                        }

                        if(nameOk == 1 && emailOk == 1 && subjectOk == 1 && commentsOk == 1)
                        {
                            $('#submit')
                                .after('<img src="/img/ajax-loader.gif" class="loader1" />')
                                .attr('disabled','disabled');

                            $.post(action, {
                                name: $('#name').val(),
                                email: $('#email').val(),
                                website: $('#website').val(),
                                subject: $('#subject').val(),
                                comments: $('#comments').val()
                            }, function(data){
                                document.getElementById('message').innerHTML = 'Wiadomość została wysłana poporawnie.';
                                $('#message').slideDown('slow');
                                $('#comments_form img.loader1').fadeOut('slow',function(){$(this).remove()});
                                $('#submit').removeAttr('disabled');
                                if(data.match('success') != null) $('#comments_form').slideUp('slow');

                                $('#name').val('');
                                $('#email').val('');
                                $('#website').val('');
                                $('#subject').val('');
                                $('#comments').val('');
                                $('input[name="defaultReal"]').val('');
                            });
                        }

                    } else
                    {
                        $('input[name="defaultReal"]')
                            .css('border', '1px solid red')
                            .after('<span class="red captcha">*Wprowadź poprawny kod</span>');
                    }

                }
            });

		});

		return false;

	});

});