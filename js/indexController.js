const trads = new Trads();
var newsTab = new Array();

function setupThemes(array) {
    while (document.getElementById('themes').childNodes.length > 0)
        document.getElementById('themes').removeChild(
            document.getElementById('themes').childNodes[0]
        );

    var blankOption = document.createElement("option");
    blankOption.selected = true;
    blankOption.innerHTML = trads.getTrad('all');
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
    newsTab = array;
    while (document.getElementById('news').childNodes.length > 0)
        document.getElementById('news').removeChild(
            document.getElementById('news').childNodes[0]
        );
    for (const news of array) {
        var lig = document.createElement("article");

        CreateNewsArticle(lig, news);

        lig.onclick = function () {
            setDetailedNews(news.id);
        };

        document.getElementById('news').appendChild(lig);
    };
}

function setDetailedNews(id) {
    // detailedNews
    while (document.getElementById('detailedNews').childNodes.length > 0)
        document.getElementById('detailedNews').removeChild(
            document.getElementById('detailedNews').childNodes[0]
        );

    for (const news of newsTab) {
        if (news.id == id) {
            var lig = document.createElement("article");

            CreateNewsArticle(lig, news);

            document.getElementById('detailedNews').appendChild(lig);
        }
    };
}

async function CreateNewsArticle(lig, news) {
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
    var sessionvars = await jsonRequest("api/session.php?Name=idlogin");
    if (sessionvars.idlogin) { // Check if a user is logged
        var curr_redactor = await getRedactors(sessionvars.idlogin);
        curr_redactor = curr_redactor[0];
        if (curr_redactor.role == 1) { // Check if it's an admin
            theme.style.cursor = "pointer";
            theme.classList.toggle("tooltip");
            theme.onclick = function () {
                window.location.href = "themes_view.php?ID=" + news.theme;
            };
            let tooltip = document.createElement("span");
            tooltip.classList.toggle("tooltiptext");
            tooltip.innerHTML = trads.getTrad('editTheme');
            theme.appendChild(tooltip);
        }
    }
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
    if (sessionvars.idlogin && (curr_redactor.id == news.redactor.id || curr_redactor.role == 1)) { // Check if a user is logged and if it's the creator, or an admin
        let div = document.createElement("div");
        div.classList.add("buttonsholder");
        let delBtn = document.createElement("button");
        delBtn.innerHTML = trads.getTrad('delete');
        delBtn.onclick = async function () {
            await jsonRequest("api/news.php", "POST", "method=DELETE&ID=" + news.id);
            onFilterChange();
        };
        div.appendChild(delBtn);
        let editBtn = document.createElement("button");
        editBtn.innerHTML = trads.getTrad('edit');
        editBtn.onclick = function () {
            window.location.href = "news_view.php?ID=" + news.id;
        };
        div.appendChild(editBtn);
        footer.appendChild(div);
    }
    lig.appendChild(footer);
}

function getRadio(radios) {
    try {
        for (var i = 0, length = radios.length; i < length; i++)
            if (radios[i].checked)
                return radios[i].value;

    } catch (error) { }
    return '';
}

function onLoad() {
    onFilterChange();
    onThemeFocus();
    setTimeout("onLoad()", 60000);
}

async function onFilterChange() {
    setupNews(await getNews(document.getElementById("themes").value, getRadio(document.getElementsByName('sort')), getRadio(document.getElementsByName('lang'))));
}

async function onThemeFocus() {
    setupThemes(await getThemes(""));
}