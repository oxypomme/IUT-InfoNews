function setupThemes(array) {
    while (document.getElementById('themes').childNodes.length > 0)
        document.getElementById('themes').removeChild(
            document.getElementById('themes').childNodes[0]
        );

    var blankOption = document.createElement("option");
    blankOption.selected = true;
    blankOption.innerHTML = "Tous";
    document.getElementById('themes').appendChild(blankOption);

    array.forEach(theme => {
        var lig = document.createElement("option");
        lig.value = theme.id;
        //lig.style = "background-image:url(" + theme.iconURL + ");";
        lig.innerHTML = theme;
        document.getElementById('themes').appendChild(lig);
    });
}

function setupNews(array) {
    while (document.getElementById('news').childNodes.length > 0)
        document.getElementById('news').removeChild(
            document.getElementById('news').childNodes[0]
        );
    array.forEach(news => {
        var lig = document.createElement("article");

        var elmnt;
        if (news.imgURL != "") {
            elmnt = document.createElement("a");
            elmnt.href = news.imgURL;
            var img = document.createElement("img");
            img.src = news.imgURL;
            img.alt = news.title + "_image";
            elmnt.appendChild(img);
        } else {
            elmnt = document.createElement("div");
            elmnt.className = "spacer image";
        }
        lig.appendChild(elmnt);

        let header = document.createElement("div");
        var theme = document.createElement("h4");
        theme.style.color = news.theme.color || "#333";
        theme.innerHTML = news.theme;
        header.appendChild(theme);

        var title = document.createElement("h3");
        title.innerHTML = news.title;
        header.appendChild(title);
        lig.appendChild(header);

        var txt = document.createElement("p");
        txt.innerHTML = news.text;
        lig.appendChild(txt);

        var footer = document.createElement("p");
        footer.innerHTML = news.redactor + " - " + news.date + " - ";
        var icon = document.createElement("img");
        icon.src = "res/" + news.lang + ".png";
        icon.alt = news.lang;
        footer.appendChild(icon);
        var sessionvars = JSON.parse(jsonRequest("api/session.php?Name=idlogin", false));
        if (sessionvars.idlogin == news.redactor.id) {
            let div = document.createElement("div");
            div.classList.add("buttonsholder");
            let delBtn = document.createElement("button");
            delBtn.innerHTML = "Supprimer";
            delBtn.onclick = function () {
                jsonRequest("api/news.php", function (response) {
                    onFilterChange();
                }, "POST", "method=DELETE&ID=" + news.id)
            };
            div.appendChild(delBtn);
            let editBtn = document.createElement("button");
            editBtn.innerHTML = "Editer";
            editBtn.onclick = function () {
                window.location.href = "news_view.php?ID=" + news.id;
            };
            div.appendChild(editBtn);
            footer.appendChild(div);
        }
        lig.appendChild(footer);

        document.getElementById('news').appendChild(lig);
    });
}

function getRadio(radios) {
    try {
        for (var i = 0, length = radios.length; i < length; i++)
            if (radios[i].checked)
                return radios[i].value;

    } catch (error) {}
    return '';
}

function onLoad() {
    onFilterChange();
    onThemeFocus();
    setTimeout("onLoad()", 60000);
}

function onChangeLanguage() {
    onFilterChange();
    onThemeFocus();
}

function onFilterChange() {
    getNews(document.getElementById("themes").value, getRadio(document.getElementsByName('sort')), getRadio(document.getElementsByName('lang')), setupNews);
}

function onThemeFocus() {
    getThemes("", setupThemes);
}