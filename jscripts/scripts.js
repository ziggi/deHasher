jQuery(function($) {
	
	$('.zgshell_window').center('body');
	
	$('.hash_count').each(function() {
		var type = $(this).attr('id').replace('count_','');
		$.ajax({
			url: 'api.php',
			type: 'GET',
			data: 'type='+type+'&count',
			success: function(data) {
				$('div[id=count_'+type+']').html(data);
			}
		});
	});
	
	var type_hash = 'md5';
	var type_text = 'md5';
	
	$('div#method_hash span').click(function() {
		type_hash = $(this).html();
		$('div#method_hash span').removeClass('clicked');
		$(this).addClass('clicked');
	});
	$('div#method_text span').click(function() {
		type_text = $(this).html();
		$('div#method_text span').removeClass('clicked');
		$(this).addClass('clicked');
	});
	$('#result').click(function() {
		$('#output_found').val('');
		$('#output_notfound').val('');
		$('#found_count').html('0');
		$('#notfound_count').html('0');
		
		input_hash = $('#input_hash').val();
		if (input_hash != '')
		{
			var input_array = input_hash.split(/\n/gi);
			var uot = 0;
			if ( $('#use_other_db input').attr('checked') == 'checked' && type_hash == 'md5') {
				uot = 1;
			}
			$.each(input_array, function(index, value) {
				if (value.length == 0) {
					return true; // continue;
				}
				$.ajax({
					url: 'api.php',
					type: 'GET',
					data: 'hash='+value+'&type='+type_hash+'&uot='+uot,
					success: function(html){
						if (html == '' && html != '\0')	{
							$('#notfound_count').html( parseInt($('#notfound_count').html()) + 1 );
							$('#output_notfound').val( $('#output_notfound').val() + value + '\n');
						} else {
							$('#found_count').html( parseInt($('#found_count').html()) + 1 );
							$('#output_found').val( $('#output_found').val() + value + ':' + html + '\n');
						}
					}
				});
			});
		}
		
		input_text = $('#input_text').val();
		if (input_text != '')
		{
			var input_array = input_text.split(/\n/gi);
			$.each(input_array, function(index, value) {
				$.ajax({
					url: 'api.php',
					type: 'GET',
					data: 'text='+value+'&type='+type_text,
					contentType: 'text/html',
					success: function(html) {
						$('#found_count').html( parseInt($('#found_count').html()) + 1 );
						$('#output_found').val( $('#output_found').val() + html + ':' + value + '\n');
					}
				});
			});
		}
	});
});
