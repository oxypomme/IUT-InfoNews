function changeLanguage(lang) {
    document.cookie = "lang=" + lang;
}

function showDropdown() {
    document.getElementById("navdropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function (e) {
    if (!e.target.matches('.dropbtn')) {
        var myDropdown = document.getElementById("navdropdown");
        if (myDropdown.classList.contains('show')) {
            myDropdown.classList.remove('show');
        }
    }
}

function unlogAccount() {
    if (confirm("Souhaitez vous vous déconnecter ?"))
        window.location.assign("php/logout.php")
}