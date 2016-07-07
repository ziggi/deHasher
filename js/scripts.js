window.addEventListener('load', function() {
	const DEFAULT_ALGO = 'md5';

	// elements
	var elemSelect = document.querySelector('.control select');
	var elemInput = document.querySelector('[name=input]');
	var elemOutput = document.querySelector('[name=output]');
	var elemToHash = document.querySelector('[name=tohash]');
	var elemToText = document.querySelector('[name=totext]');
	var elemExtDb = document.querySelector('.ext-db input');
	var elemCounter = document.querySelector('[name=counter]');

	// counter
	counterUpdate();

	function counterUpdate() {
		var request = new XMLHttpRequest();
		request.open('GET', 'api/info.get?count&type=md5');

		request.onload = function(event) {
			elemCounter.textContent = event.target.responseText;
		};

		request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		request.send();
	}

	// get option for select element
	var request = new XMLHttpRequest();
	request.open('GET', 'api/info.get?algo');

	request.onload = function(event) {
		var algos = JSON.parse(event.target.responseText);

		for (var i in algos) {
			var option = document.createElement("option");
			option.innerHTML = algos[i];

			elemSelect.appendChild(option);

			if (algos[i] == DEFAULT_ALGO) {
				elemSelect.selectedIndex = i;
			}
		}
	};

	request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	request.send();

	// form handler
	document.querySelector('form').addEventListener('submit', function(event) {
		event.preventDefault();
	});

	elemToHash.addEventListener('click', function() {
		makeRequest('hash');
	});

	elemToText.addEventListener('click', function() {
		makeRequest('dehash');
	});

	function makeRequest(requestName) {
		var inputName = (requestName == 'hash') ? 'text' : 'hash';

		// get params
		window.getSelection().selectAllChildren(elemInput);
		var input = window.getSelection().toString();
		window.getSelection().removeAllRanges();

		var type = elemSelect.options[elemSelect.selectedIndex].value;
		var extdb = elemExtDb.checked ? '&include_external_db' : '';

		// check params
		if (input.length == 0) {
			return;
		}

		// set to default
		disableControls(true);
		elemOutput.textContent = '';
		elemInput.textContent = '';

		var completed = 0;

		// make array from input
		var inputList = input.split(/\r?\n/gi);
		var inputCount = inputList.length;

		// get hashs
		for (var i in inputList) {
			if (inputList[i].length == 0) {
				inputCount--;
				continue;
			}

			var request = new XMLHttpRequest();
			request.open('GET', 'api/' + requestName + '.get?' + inputName + '=' + inputList[i] + '&type=' + type + extdb);

			request.inputString = inputList[i];

			request.onload = function(event) {
				var output = event.target.responseText;

				if (output.length > 0) {
					if (requestName == 'hash') {
						elemOutput.innerHTML += '<div>' + this.inputString + ':' + output + '</div>\n';
					} else {
						elemOutput.innerHTML += '<div>' + output + ':' + this.inputString + '</div>\n';
					}

					elemInput.innerHTML += '<div>' + this.inputString + '</div>\n';
				} else {
					elemInput.innerHTML += '<div class="notfound">' + this.inputString + '</div>\n';
				}

				if (++completed == inputCount) {
					disableControls(false);
					counterUpdate();
				}
			};

			request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			request.send();
		}
	}

	function disableControls(isDisabled) {
		elemInput.disabled = isDisabled;
		elemInput.contentEditable = !isDisabled;
		elemOutput.disabled = isDisabled;
		elemToHash.disabled = isDisabled;
		elemToText.disabled = isDisabled;
		elemExtDb.disabled = isDisabled;
		elemSelect.disabled = isDisabled;
	}
});
