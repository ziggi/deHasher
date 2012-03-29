jQuery(function($) {
	$('#zgshell .menu .button').click(function() {
		$('#zgshell .window').css('display','block');
	});
	$('#zgshell .window .close').click(function() {
		$('#zgshell .window').css('display','none');
	});
});
