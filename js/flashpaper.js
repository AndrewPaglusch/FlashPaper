if (location.protocol != 'https:') {
    document.write('<div style="padding-top: 1%" class="container"><div class="alert alert-danger"><strong>Danger!</strong> This site is not being accessed over an encrypted connection. Do NOT input any sensitive information!</div></div>');
}
function copyText() {
    var textToCopy = document.getElementById("copy");
    textToCopy.select();
    document.execCommand("copy");
}