function loadTemplate(templateName) {
	httpGetAsync(`${document.location}/ajax.php?select=${templateName}`, (response) => {
		switch (response) {
			case "TEMPLATE_NOT_FOUND":
				const overlay = document.getElementById("overlay");
				const errormsg = document.getElementById("errormsg");

				errormsg.innerText="Could not load the requested template"
				overlay.style.zIndex = 100;
				overlay.style.display = "block";
				overlay.style.opacity = 1;
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
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.onreadystatechange = function() {
		if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
			callback(xmlHttp.responseText);
	}
	xmlHttp.open("GET", url, true);
	xmlHttp.send(null);
}

function close_overlay() {
	const overlay = document.getElementById("overlay");
	const template = document.getElementById("select");

	template.value="./";
	overlay.style.display = "none";
	overlay.style.opacity = 0;

	setTimeout(() => {
		const element = document.getElementById("overlay");
		element.style.zIndex = -100;
	}, 1000);
}
