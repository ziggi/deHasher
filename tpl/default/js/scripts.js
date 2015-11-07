window.addEventListener('load', function() {
	// paste only plane text
	[].forEach.call(document.querySelectorAll('[contenteditable=true]'), function(element) {
		element.addEventListener('paste', function(event) {
			event.preventDefault();
			window.document.execCommand('insertText', false, event.clipboardData.getData('text/plain'));
		});
		element.addEventListener('drop', function(event) {
			event.preventDefault();
			this.innerText += event.dataTransfer.getData("text/plain");
		});
	});

	// adaptive
	checkSize();
	window.addEventListener("resize", checkSize);

	function checkSize() {
		if (document.documentElement.clientWidth < 992) {
			var footer = document.createElement('div');
			footer.classList.add('column-12', 'footer');

			footer.appendChild(document.querySelector('.control-counter'));
			footer.appendChild(document.querySelector('.control-api'));
			footer.appendChild(document.querySelector('.control-links'));

			document.querySelector('.container form').appendChild(footer);
		} else {
			var control = document.querySelector('.control');

			control.appendChild(document.querySelector('.control-counter'));
			control.appendChild(document.querySelector('.control-api'));
			control.appendChild(document.querySelector('.control-links'));

			var footer = document.querySelector('.footer');
			if (footer != null) {
				footer.remove();
			}
		}
	}
});
