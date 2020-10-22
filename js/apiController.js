class Redactor {
    constructor(id, lname, fname, mail) {
        this.id = id;

        this.lname = lname;
        this.fname = fname;
        this.mail = mail;
    }

    toString() {
        return this.lname + " " + this.fname;
    }
}

class Theme {
    constructor(id, labelRAW, color, icon_theme) {
        this.id = id;

        var label = JSON.parse(labelRAW)
        this.en = label.en;
        this.fr = label.fr;

        this.color = color;
        this.icon_theme = icon_theme;
    }

    toString() {
        var lang = getCookie('lang');
        return this[(lang != "" ? lang : 'fr')];
    }
}

class News {
    constructor(id, contentRAW, theme, redactor, date, lang) {
        this.id = id;

        var content = JSON.parse(contentRAW);
        this.title = content.title;
        this.text = content.text;
        this.imgURL = content.imgURL;

        this.theme = theme;
        this.redactor = redactor;
        this.date = date;
        this.lang = lang;
    }
}

function jsonRequest(address, asyncProc = false, reqType = "GET", content = '') {
    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
    if (asyncProc) {
        xhr.onreadystatechange = function () {
            if (this.readyState == 4) {
                asyncProc(this.response);
            }
        };
        xhr.responseType = 'json';
    }
    xhr.open(reqType, address, !(!asyncProc));
    if (reqType == 'POST')
        xhr.setRequestHeader('Content-Type', ' application/x-www-form-urlencoded');
    xhr.send(content);
    if (!asyncProc)
        return (xhr.status == 200 ? xhr.response : false);
}

function jsonToRedactors(docJSON) {
    var redactorList = new Array();
    if (docJSON['redactors'] != null)
        docJSON['redactors'].forEach(redactor => {
            redactorList.push(new Redactor(redactor.id, redactor.lname, redactor.fname, redactor.mail));
        });
    return redactorList;
}

function getRedactors(id = "", asyncProc = false) {
    if (!asyncProc) {
        var docJSON = JSON.parse(jsonRequest("api/redactors.php?ID=" + id));
        return jsonToRedactors(docJSON);
    } else {
        jsonRequest("api/redactors.php?ID=" + id, function (docJSON) {
            asyncProc(jsonToRedactors(docJSON));
        });
    }
}

function jsonToTheme(docJSON) {
    var themeList = new Array();
    if (docJSON['themes'] != null)
        docJSON['themes'].forEach(theme => {
            themeList.push(new Theme(theme.id, theme.label, theme.color, theme.icon_theme));
        });
    return themeList;
}

function getThemes(id = "", asyncProc = false) {
    if (!asyncProc) {
        var docJSON = JSON.parse(jsonRequest("api/themes.php?ID=" + id));
        return jsonToTheme(docJSON);
    } else {
        jsonRequest("api/themes.php?ID=" + id, function (docJSON) {
            asyncProc(jsonToTheme(docJSON));
        });
    }
}

function jsonToNews(docJSON) {
    var newsList = new Array();
    if (docJSON['news'] != null)
        docJSON['news'].forEach(news => {
            newsList.push(new News(news.id, news.content, getThemes(news.theme)[0], getRedactors(news.redactor)[0], news.date, news.lang))
        });
    return newsList;
}

function getNews(theme = "", sort = "", lang = "", asyncProc = false) {
    if (!asyncProc) {
        var docJSON = JSON.parse(jsonRequest("api/news.php?Theme=" + theme + "&Sort=" + sort + "&Lang=" + lang));
        jsonToNews(docJSON);
    } else {
        jsonRequest("api/news.php?Theme=" + theme + "&Sort=" + sort + "&Lang=" + lang, function (docJSON) {
            asyncProc(jsonToNews(docJSON));
        });
    }
}

function getCookie(name) {
    var cname = name + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(cname) == 0) {
            return c.substring(cname.length, c.length);
        }
    }
    return "";
}