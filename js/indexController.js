function getSyncJSON(url) {
    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject(Microsoft.XMLHTTP);
    xhr.open("GET", url, false);
    xhr.send();
    return (xhr.status == 200 ? xhr.response : false);
}

function newsJSONtoArray(docJSON) // Transformation JSON en tableau
{
    var newsRAW = docJSON['news'];
    var newsList = new Array();
    try {
        for (var i = 0; i < newsRAW.length; ++i) {
            var news = {};

            var theme = JSON.parse(getSyncJSON("models/themes.php?ID=" + newsRAW[i].theme)).themes[0];
            news.theme = JSON.parse(theme.label).fr;

            news.content = newsRAW[i].content;
            news.date = newsRAW[i].date;

            var redactor = JSON.parse(getSyncJSON("models/redactors.php?ID=" + newsRAW[i].redactor)).redactors[0];
            news.redactor = redactor.lname + " " + redactor.fname;

            news.lang = newsRAW[i].lang;

            newsList.push(news);
            newsList.sort((a, b) => a.id - b.id);
        }
    } catch (TypeError) {

    }
    return newsList;
}

function setupNews(array) {
    while (document.getElementById('news').childNodes.length > 0)
        document.getElementById('news').removeChild(
            document.getElementById('news').childNodes[0]
        );
    for (var i = 0; i < array.length; ++i) {
        var lig = document.createElement("article");
        var content = JSON.parse(array[i].content);

        var title = document.createElement("h3");
        title.innerHTML = content.title;
        lig.appendChild(title);

        var title = document.createElement("p");
        title.innerHTML = array[i].theme;
        lig.appendChild(title);

        var img = document.createElement("img");
        img.src = content.imgURL;
        lig.appendChild(img);

        var txt = document.createElement("p");
        txt.innerHTML = content.text;
        lig.appendChild(txt);

        var footer = document.createElement("p");
        footer.innerHTML = array[i].redactor + " - " + array[i].date + " - " + array[i].lang;
        lig.appendChild(footer);

        document.getElementById('news').appendChild(lig);
    }
}

function getAllNews() {
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
                setupNews(newsJSONtoArray(docJSON));
            }
        }
    };
    xhr.open("GET", "models/news.php?Theme=", true);
    xhr.responseType = 'json';
    xhr.send();
    setTimeout("getAllNews()", 5000);
}