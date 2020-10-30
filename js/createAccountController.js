const trads = new Trads(false);

function validate() {
    var result = true;
    if (!document.forms["redactor"].lname || !document.forms["redactor"].lname.value.match(/^.{3,}$/)) {
        result = false;
        document.getElementById("lname").innerHTML = trads.getTrad('lnameError');
    } else
        document.getElementById("lname").innerHTML = "";

    if (!document.forms["redactor"].fname || !document.forms["redactor"].fname.value.match(/^.{3,}$/)) {
        result = false;
        document.getElementById("lname").innerHTML = trads.getTrad('fnameError');
    } else
        document.getElementById("fname").innerHTML = "";

    if (!document.forms["redactor"].login || !document.forms["redactor"].login.value.match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+\.([a-zA-Z0-9-]+){2,4}$/)) {
        result = false;
        document.getElementById("lname").innerHTML = trads.getTrad('loginError');
    } else
        document.getElementById("login").innerHTML = "";

    if (!document.forms["redactor"].passwd || !document.forms["redactor"].passwd.value.match(/^.{6,}$/)) {
        result = false;
        document.getElementById("lname").innerHTML = trads.getTrad('passwdLengthError');
    } else
        document.getElementById("passwd").innerHTML = "";

    return result;
}

function checkPassword() {
    if (document.forms["redactor"].passwd) {
        var score = getPasswordScore(document.forms["redactor"].passwd.value);
        document.getElementById("passwordStrenghBar").value = score;
        if (score >= 45 && score < 80)
            document.getElementById("passwordStrengh").innerHTML = trads.getTrad('mid');
        else if (score >= 80)
            document.getElementById("passwordStrengh").innerHTML = trads.getTrad('strong');
        else
            document.getElementById("passwordStrengh").innerHTML = trads.getTrad('weak');
    } else
        document.getElementById("passwordStrengh").innerHTML = trads.getTrad('invalid');
}

function getPasswordScore(password) {
    var score = 0;
    if (!password)
        return score;

    // add score for each characters only if there's not more than one of it
    var chars = new Array();
    for (var i = 0; i < password.length; i++) {
        chars[password[i]] = (chars[password[i]] || 0) + 1;
        score += 3.0 / chars[password[i]];
    }

    // add score if the pass word contain specific chars
    var verifications = {
        digits: /\d/.test(password),
        lower: /[a-z]/.test(password),
        upper: /[A-Z]/.test(password),
        specialChar: /[^A-Za-z0-9]/.test(password),
        noWord: /\W/.test(password)
    }
    for (var check in verifications)
        score += (verifications[check] == true) ? 10 : 0;

    return parseInt(score);
}