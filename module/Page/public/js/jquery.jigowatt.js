jQuery(document).ready(function(){

	$('#comments_form').submit(function(){

		var action = $(this).attr('action');

		$("#message").slideUp(750,function() {
		$('#message').hide();

 		$('#submit')
			.after('<img src="/img/ajax-loader2.gif" class="loader1" />')
			.attr('disabled','disabled');

		$.post(action, {
			name: $('#name').val(),
			email: $('#email').val(),
			website: $('#website').val(),
			subject: $('#subject').val(),
			comments: $('#comments').val()
			//verify: $('#verify').val()
		},
			function(data){
				document.getElementById('message').innerHTML = 'Wiadomość została wysłana poporawnie.';
				$('#message').slideDown('slow');
				$('#comments_form img.loader1').fadeOut('slow',function(){$(this).remove()});
				$('#submit').removeAttr('disabled');
				if(data.match('success') != null) $('#comments_form').slideUp('slow');

			}
		);

		});

		return false;

	});

});