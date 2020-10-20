function themesJSONtoArray(docJSON) // Transformation JSON en tableau
{
    var themesRAW = docJSON['themes'];
    var themes = new Array();
    try {
        for (var i = 0; i < themesRAW.length; ++i) {
            var theme = {};
            theme.id = themesRAW[i].id;
            theme.label = themesRAW[i].label;
            theme.iconURL = themesRAW[i].iconURL;
            themes.push(theme);
            themes.sort((a, b) => a.id - b.id);
        }
    } catch (TypeError) {

    }
    return themes;
}

function setupTheme(array) {
    //TODO: select by default if it was a reload
    while (document.getElementById('themes').childNodes.length > 0)
        document.getElementById('themes').removeChild(
            document.getElementById('themes').childNodes[0]
        );

    var blankOption = document.createElement("option");
    blankOption.selected = true;
    blankOption.disabled = true;
    blankOption.hidden = true;
    document.getElementById('themes').appendChild(blankOption);

    for (var i = 0; i < array.length; ++i) {
        var lig = document.createElement("option");
        lig.value = array[i].id;
        //lig.style = "background-image:url(" + array[i].iconURL + ");";
        lig.innerHTML = JSON.parse(array[i].label).fr;
        document.getElementById('themes').appendChild(lig);
    }
}

function getAllThemes() {
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
                setupTheme(themesJSONtoArray(docJSON));
            }
        }
    };
    xhr.open("GET", "models/themes.php?ID=", true);
    xhr.responseType = 'json';
    xhr.send();
    //setTimeout("loop()", 5000);
}