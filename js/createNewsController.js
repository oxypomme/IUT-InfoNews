function setupThemes(array) {
    while (document.getElementById('themes').childNodes.length > 0) {
        document.getElementById('themes').removeChild(
            document.getElementById('themes').childNodes[0]
        );
    }

    var blankOption = document.createElement("option");
    blankOption.selected = true;
    blankOption.disabled = true;
    blankOption.hidden = true;
    document.getElementById('themes').appendChild(blankOption);

    array.forEach(theme => {
        var lig = document.createElement("option");
        lig.value = theme.id;
        if (getPostedTheme() == theme.id)
            lig.selected = true;
        //lig.style = "background-image:url(" + theme.iconURL + ");";
        lig.innerHTML = theme;
        document.getElementById('themes').appendChild(lig);
    });
}

function onChangeLanguage() {
    onLoad();
}

function onLoad() {
    getThemes("", setupThemes);
}