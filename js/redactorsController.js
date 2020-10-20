function themesJSONtoArray(docJSON) // Transformation JSON en tableau
{
    var redactorsRAW = docJSON['redactors'];
    var redactors = new Array();
    try {
        for (var i = 0; i < redactorsRAW.length; ++i) {
            var redac = {};
            redac.id = redactorsRAW[i].id;
            redac.lname = redactorsRAW[i].lname;
            redac.fname = redactorsRAW[i].fname;
            redac.mail = redactorsRAW[i].mail;
            redactors.push(redac);
            redactors.sort((a, b) => a.id - b.id);
        }
    } catch (TypeError) {

    }
    return redactors;
}

function getAllRedactors() {
    var xhr;
    try {
        xhr = new XMLHttpRequest();
    } catch (e) {
        xhr = new ActiveXObject(Microsoft.XMLHTTP);
    }
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                var docJSON = xhr.response;
                themesJSONtoArray(docJSON);
            }
        }
    };
    xhr.open("GET", "models/redactors.php", true);
    xhr.responseType = 'json';
    xhr.send();
    //setTimeout("loop()", 5000);
}