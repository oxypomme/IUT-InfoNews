function validate() {
    var result = true;
    if (!document.forms["redactor"].lname || !document.forms["redactor"].lname.value.match(/^.{3,}$/)) {
        document.getElementById("lname").innerHTML = "Le nom doit être valide";
        result = false;
    } else
        document.getElementById("lname").innerHTML = "";

    if (!document.forms["redactor"].fname || !document.forms["redactor"].fname.value.match(/^.{3,}$/)) {
        document.getElementById("fname").innerHTML = "Le prénom doit être valide";
        result = false;
    } else
        document.getElementById("fname").innerHTML = "";

    if (!document.forms["redactor"].login || !document.forms["redactor"].login.value.match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+\.([a-zA-Z0-9-]+){2,4}$/)) {
        document.getElementById("login").innerHTML = "L'adresse mail doit être valide";
        result = false;
    } else
        document.getElementById("login").innerHTML = "";

    if (!document.forms["redactor"].passwd || !document.forms["redactor"].passwd.value.match(/^.{6,}$/)) {
        document.getElementById("passwd").innerHTML = "Le mot de passe doit faire au minimum 6 charactères";
        result = false;
    } else
        document.getElementById("passwd").innerHTML = "";

    return result;
}

function checkPassword() {
    if (document.forms["redactor"].passwd) {
        var score = getPasswordScore(document.forms["redactor"].passwd.value);
        document.getElementById("passwordStrenghBar").value = score;
        if (score > 45 && score <= 80)
            document.getElementById("passwordStrengh").innerHTML = "Moyen";
        else if (score > 80)
            document.getElementById("passwordStrengh").innerHTML = "Fort";
        else
            document.getElementById("passwordStrengh").innerHTML = "Faible";
    } else
        document.getElementById("passwordStrengh").innerHTML = "Invalide";
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