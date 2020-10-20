function newsJSONtoArray(docJSON) // Transformation JSON en tableau
{
    var newsRAW = docJSON['news'];
    var news = new Array();
    try {
        for (var i = 0; i < newsRAW.length; ++i) {
            var theme = {};
            theme.theme = newsRAW[i].theme;
            theme.content = newsRAW[i].content;
            theme.date = newsRAW[i].date;
            theme.redactor = newsRAW[i].redactor;
            theme.lang = newsRAW[i].lang;
            news.push(theme);
            news.sort((a, b) => a.id - b.id);
        }
    } catch (TypeError) {

    }
    return news;
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