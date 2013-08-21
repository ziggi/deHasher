$(function() {
	$('a[data-type="window"]').click(function() {
		var target = $(this).attr('data-target');
		$('#' + target).css('display', 'inline-block');
		$('#' + target).trigger('windowOpen');
	});
	$('.window .close').click(function() {
		$(this).parent('div[id]').css('display', 'none');
	});
	
	$('#db_info').on('windowOpen', function() {
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
	});
	
	var type_hash = 'md5';
	var type_text = 'md5';
	
	$('div#method_hash button').click(function() {
		type_hash = $(this).html();
		$('div#method_hash button').removeClass('active');
		$(this).addClass('active');
	});
	
	$('div#method_text button').click(function() {
		type_text = $(this).html();
		$('div#method_text button').removeClass('active');
		$(this).addClass('active');
	});
	
	var encode_complete = true;
	var decode_complete = true;
	var found_count = 0;
	var notfound_count = 0;
	var output_found = "";
	var output_notfound = "";
	
	$('#result button').click(function() {
		if (!isGetComplete()) {
			return false;
		}
		$(this).addClass("active");
		$('div#method_hash button').attr('disabled', 'disabled');
		$('div#method_text button').attr('disabled', 'disabled');
		
		// reset values
		$('#output_found').val('');
		$('#output_notfound').val('');
		$('#found_count').html('0');
		$('#notfound_count').html('0');
		found_count = 0;
		notfound_count = 0;
		output_found = "";
		output_notfound = "";
		
		// get
		var input_decode = $('#input_decode').val();
		if (!isBlank(input_decode)) {
			decode_complete = false;
			var input_array = input_decode.split(/\n/gi);
			getDecode(input_array, 0);
		}
		
		var input_encode = $('#input_encode').val();
		if (!isBlank(input_encode)) {
			encode_complete = false;
			var input_array = input_encode.split(/\n/gi);
			getEncode(input_array, 0);
		}
		
		onGetComplete();
	});
	
	function isGetComplete() {
		if (encode_complete && decode_complete) {
			return true;
		}
		return false;
	}
	
	function onGetComplete() {
		if (isGetComplete()) {
			$('#result button').removeClass("active");
			$('div#method_hash button').removeAttr('disabled');
			$('div#method_text button').removeAttr('disabled');
		}
	}
	
	function getDecode(input_array, index) {
		var uot = $('#use_other_db input:checked').length;
		$.ajax({
			url: 'api.php',
			type: 'GET',
			data: 'hash=' + input_array[index] + '&type=' + type_hash + '&uot=' + uot,
			contentType: 'text/html',
			success: function(html) {
				if (isBlank(html)) {
					notfound_count++;
					$('#notfound_count').html(notfound_count);
					
					output_notfound = output_notfound + input_array[index] + '\n';
					$('#output_notfound').val(output_notfound);
				} else {
					found_count++;
					$('#found_count').html(found_count);
					
					output_found = output_found + input_array[index] + ':' + html + '\n';
					$('#output_found').val(output_found);
				}
			}
		}).done(function() {
			index++;
			if (index < input_array.length) {
				getDecode(input_array, index);
			} else {
				decode_complete = true;
				onGetComplete();
			}
		});
	}
	
	function getEncode(input_array, index) {
		$.ajax({
			url: 'api.php',
			type: 'GET',
			data: 'text=' + input_array[index] + '&type=' + type_text,
			contentType: 'text/html',
			success: function(html) {
				found_count++;
				$('#found_count').html(found_count);
				
				output_found = output_found + html + ':' + input_array[index] + '\n';
				$('#output_found').val(output_found);
			}
		}).done(function() {
			index++;
			if (index < input_array.length) {
				getEncode(input_array, index);
			} else {
				encode_complete = true;
				onGetComplete();
			}
		});
	}
	
	function isBlank(str) {
		return (!str || /^\s*$/.test(str));
	}
});
