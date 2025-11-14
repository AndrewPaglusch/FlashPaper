function loadTemplate(templateName) {
	httpGetAsync(`${document.location.origin}/ajax.php?select=${templateName}`, (response) => {
		switch (response) {
			case "TEMPLATE_NOT_FOUND":
				show_overlay("Could not find the requested template", "Ok");
				document.getElementById("secret").blur();
				document.querySelector("#overlay").querySelector("a").setAttribute("redirect", true);
				break;

			default:
				show_flashpaper_form(response);
				resize_form_html_elements();
				break;
		}
	});
}

function show_flashpaper_form(html) {
	const content = document.getElementById("content");
	const secret = document.getElementById("secret");
	const isHtmlSecret = html.includes("html_secret");

	// Switch between a view with multiple HTML elements
	// or a single textarea, depending on the template
	if (isHtmlSecret) {
		secret.style.display = "none";
		secret.innerHTML = "";
		secret.value = "HTML_FORM_SECRET";

		content.insertAdjacentHTML("beforeend", html);
	} else {
		secret.style.display = "";
		secret.value = html;

		content.replaceChildren(secret);
	}
}

function httpGetAsync(url, callback) {
	const xmlHttp = new XMLHttpRequest();
	xmlHttp.onreadystatechange = function() {
		if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
			callback(xmlHttp.responseText);
	}
	xmlHttp.open("GET", url, true);
	xmlHttp.send(null);
}

function show_overlay(message, buttonText = "Home", callback) {
	const overlay = document.getElementById("overlay");
	const errormsg = document.getElementById("errormsg");

	document.querySelector("#overlay").querySelector("a").innerHTML = buttonText;

	errormsg.innerText = message;
	overlay.style.zIndex = 100;
	overlay.style.display = "block";
	overlay.style.opacity = 1;

	if (typeof callback !== 'undefined') { callback(); }
}

function close_overlay() {
	const overlay = document.getElementById("overlay");
	const template = document.getElementById("select");

	template.selectedIndex = 0;
	overlay.style.display = "none";
	overlay.style.opacity = 0;

	setTimeout(() => {
		const element = document.getElementById("overlay");
		element.style.zIndex = -100;
	}, 1000);
}
