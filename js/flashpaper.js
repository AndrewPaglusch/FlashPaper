document.addEventListener("DOMContentLoaded", (e) => {
	// Display a warning banner when not using HTTPS
	if (location.protocol != 'https:') {
		const header = document.getElementsByTagName("header")[0];
		const dangerDiv = document.createElement("div");
		dangerDiv.innerHTML = '<div style="padding-top: 1%" class="container"><div class="alert alert-danger"><strong>Danger!</strong> This site is not being accessed over an encrypted connection. Do NOT input any sensitive information!</div></div>';
		header.appendChild(dangerDiv);
	}

	// If the secret being created is from an HTML form,
	// add a flag to the secret element
	const button = document.getElementById("submit");
	if (button != null) {
		button.addEventListener("click", () =>{
			const content = document.getElementById("content");
			const secret = document.getElementById("secret");
		});
	}

	// If the page was loaded with a template in the URL (/?t=template),
	// then check if the template contains HTML elements and render it as
	// a HTML form
	const url = new URL(document.location);
	const hasTemplateName = url.searchParams.has("t");
	const templateName = url.searchParams.get("t");
	const secret = document.getElementById("secret");
	const htmlRegex = /(?<name>.+):\s+(?<element>radio|select|number|textarea|datetime|date|time|checkbox)?(\((?<props>.+)?\))?/;
	const isHtmlTemplate = () => {
		if (secret == null) { return false; }
		return secret.value.match(htmlRegex) == null ? false : true;
	};
	if (hasTemplateName && isHtmlTemplate) {
		loadTemplate(templateName.replace(" ", "+"));
		const select = document.getElementById("select");
		select.value = templateName.replace(" ", "+");
	} else if (templateName != null) {
		show_overlay(`Template not found\r\n'${templateName}'`, "Ok");
		document.getElementById("select").selectedIndex = 0;
	}

	resize_form_html_elements();
});

function resize_form_html_elements() {
	const propertiesList = document.querySelectorAll("ul>li:first-child");
	const valuesList = document.querySelectorAll("ul>li:nth-child(2)");

	let propertyMaxWidth = 0;
	let valueMaxWidth = 0;

	// Get the largest width of label and input box
	propertiesList.forEach( e => {
		if (e.clientWidth > propertyMaxWidth) { propertyMaxWidth = e.clientWidth; }
	});
	valuesList.forEach( e => {
		if (e.firstChild == null) {return;}
		if (
			e.clientWidth > valueMaxWidth &&
			e.firstChild.nodeName != "TEXTAREA"
		) { valueMaxWidth = e.clientWidth; }
	});

	// Change the width of all elements to match the largest element
	propertiesList.forEach( e => {
		e.style.width = `${propertyMaxWidth}px`;
	});
	valuesList.forEach( e => {
		if (e.firstChild == null) {return;}
		if (
			e.firstChild.nodeName.match(/INPUT|SELECT/) &&
			e.firstChild.type != "radio"
		) {
			e.firstChild.style.width = `${valueMaxWidth}px`;
		}
	});
}

function copyText(id, type) {
	if (typeof id === 'undefined') {id = "copy";}
	if (typeof type === 'undefined') {type = null;}

	let element = null;
	let text = null;

	switch (type) {
		case "radio":
			element = document.querySelector(`[id^=${atob(id)}]:checked`);
			text = element.value;
			break;
		default:
			element = document.getElementById(id);
			text = element.value;
			break;
	}

	navigator.clipboard.writeText(text).then(function() {
		if (type == "radio") { element = element.nextSibling; }
		element.classList.add("pulse");
		window.setTimeout(() => {
			element.classList.remove("pulse");
		}, 1000);
	}, function(err) {
		console.error('Could not copy text: ', err);
	});
}