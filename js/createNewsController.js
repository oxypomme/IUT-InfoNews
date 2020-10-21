function setupThemes(array) {
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

    array.forEach(theme => {
        var lig = document.createElement("option");
        lig.value = theme.id;
        //lig.style = "background-image:url(" + theme.iconURL + ");";
        lig.innerHTML = theme;
        document.getElementById('themes').appendChild(lig);
    });
}

function onLoad() {
    getThemes("", setupThemes);
}