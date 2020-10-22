function changeLanguage(lang) {
    document.cookie = "lang=" + lang;
    onChangeLanguage(lang);
}

function showDropdown() {
    document.getElementById("navdropdown").classList.toggle("show");
    document.getElementById("navdropbtn").classList.toggle("active");
    document.getElementById("nav-" + getCookie('lang')).classList.toggle("active");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function (e) {
    if (!e.target.matches('.dropbtn')) {
        var myDropdown = document.getElementById("navdropdown");
        if (myDropdown.classList.contains('show'))
            myDropdown.classList.remove('show');

        myDropdown.childNodes.forEach(child => {
            try {
                if (child.classList.contains('active'))
                    child.classList.remove('active');
            } catch (error) {}
        });

        var myDropbtn = document.getElementById("navdropbtn");
        if (myDropbtn.classList.contains('active'))
            myDropbtn.classList.remove("active")
    }
}

function unlogAccount() {
    if (confirm("Souhaitez vous vous déconnecter ?"))
        window.location.assign("php/logout.php")
}