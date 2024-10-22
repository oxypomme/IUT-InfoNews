class Redactor {
    constructor(id, lname, fname, mail, role) {
        this.id = id;

        this.lname = lname;
        this.fname = fname;
        this.mail = mail;
        this.role = role;
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

class Trads {
    constructor(isRoot = true) {
        this.trads = JSON.parse(jsonRequest((!isRoot ? '../' : './') + "lang/" + (getCookie('lang') || 'fr') + '.json', 'GET', '', false));
    }

    getTrad(field) {
        return this.trads[field];
    }
}

function jsonRequest(address, reqType = "GET", content = '', asyncProc = true) {
    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
    var promise;
    if (asyncProc) {
        promise = new Promise((resolve) => {
            xhr.onreadystatechange = function () {
                if (this.readyState == 4) {
                    resolve(this.response);
                }
            };
        });
        xhr.responseType = 'json';
    }
    xhr.open(reqType, address, !(!asyncProc));
    if (reqType == 'POST')
        xhr.setRequestHeader('Content-Type', ' application/x-www-form-urlencoded');
    xhr.send(content);
    if (!asyncProc)
        return (xhr.status == 200 ? xhr.response : false);
    return promise;
}

function jsonToRedactors(docJSON) {
    var redactorList = new Array();
    if (docJSON['redactors'] != null)
        docJSON['redactors'].forEach(redactor => {
            redactorList.push(new Redactor(redactor.id, redactor.lname, redactor.fname, redactor.mail, redactor.role));
        });
    return redactorList;
}

async function getRedactors(id = "", asyncProc = true) {
    if (!asyncProc) {
        var docJSON = JSON.parse(jsonRequest("api/redactors.php?ID=" + id));
        return jsonToRedactors(docJSON);
    } else {
        return jsonToRedactors(await jsonRequest("api/redactors.php?ID=" + id));
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

async function getThemes(id = "", asyncProc = true) {
    if (!asyncProc) {
        var docJSON = JSON.parse(jsonRequest("api/themes.php?ID=" + id));
        return jsonToTheme(docJSON);
    } else {
        return jsonToTheme(await jsonRequest("api/themes.php?ID=" + id));
    }
}

async function jsonToNews(docJSON) {
    var newsList = new Array();
    if (docJSON['news'] != null)
        for (const news of docJSON['news']) {
            let themes = await getThemes(news.theme);
            let redactors = await getRedactors(news.redactor);

            newsList.push(new News(news.id, news.content, themes[0], redactors[0], news.date, news.lang));
        };
    return newsList;
}

async function getNews(theme = "", sort = "", lang = "", asyncProc = true) {
    if (!asyncProc) {
        var docJSON = JSON.parse(jsonRequest("api/news.php?Theme=" + theme + "&Sort=" + sort + "&Lang=" + lang));
        jsonToNews(docJSON);
    } else {
        return jsonToNews(await jsonRequest("api/news.php?Theme=" + theme + "&Sort=" + sort + "&Lang=" + lang));
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