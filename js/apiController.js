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
        //TODO: Cookies
        return this.fr;
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

function jsonRequest(address, asyncProc = true, reqType = "GET") {
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
    xhr.send();
    if (!asyncProc)
        return (xhr.status == 200 ? xhr.response : false);
}

function getRedactors(id = "", asyncProc = false) {
    if (!asyncProc) {
        var docJSON = JSON.parse(jsonRequest("models/redactors.php?ID=" + id, false));
        var redactorList = new Array();
        if (docJSON['redactors'] != null)
            docJSON['redactors'].forEach(redactor => {
                redactorList.push(new Redactor(redactor.id, redactor.lname, redactor.fname, redactor.mail));
            });
        return redactorList;
    } else {
        jsonRequest("models/redactors.php?ID=" + id, function (docJSON) {
            var redactorList = new Array();
            if (docJSON['redactors'] != null)
                docJSON['redactors'].forEach(redactor => {
                    redactorList.push(new Redactor(redactor.id, redactor.lname, redactor.fname, redactor.mail));
                });
            asyncProc(redactorList);
        });
    }
}

function getThemes(id = "", asyncProc = false) {
    if (!asyncProc) {
        var docJSON = JSON.parse(jsonRequest("models/themes.php?ID=" + id, false));
        var themeList = new Array();
        if (docJSON['themes'] != null)
            docJSON['themes'].forEach(theme => {
                themeList.push(new Theme(theme.id, theme.label, theme.color, theme.icon_theme));
            });
        return themeList;
    } else {
        jsonRequest("models/themes.php?ID=" + id, function (docJSON) {
            var themeList = new Array();
            if (docJSON['themes'] != null)
                docJSON['themes'].forEach(theme => {
                    themeList.push(new Theme(theme.id, theme.label, theme.color, theme.icon_theme));
                });
            asyncProc(themeList);
        });
    }
}

function getNews(theme = "", sort = "", asyncProc = false) {
    if (!asyncProc) {
        var docJSON = JSON.parse(jsonRequest("models/news.php?Theme=" + theme + "&Sort=" + sort), false);
        var newsList = new Array();
        if (docJSON['news'] != null)
            docJSON['news'].forEach(news => {
                newsList.push(new News(news.id, news.content, getThemes(news.theme)[0], getRedactors(news.redactor)[0], news.date, news.lang))
            });
        return newsList;
    } else {
        jsonRequest("models/news.php?Theme=" + theme + "&Sort=" + sort, function (docJSON) {
            var newsList = new Array();
            if (docJSON['news'] != null)
                docJSON['news'].forEach(news => {
                    newsList.push(new News(news.id, news.content, getThemes(news.theme)[0], getRedactors(news.redactor)[0], news.date, news.lang))
                });
            asyncProc(newsList);
        });
    }
}