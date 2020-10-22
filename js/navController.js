function changeLanguage(lang) {
    document.cookie = "lang=" + lang;
    onChangeLanguage(lang);
}

function showDropdown(elt) {
    clearAllDropdowns();
    elt.classList.toggle("active");
    elt.getElementsByTagName('ul')[0].classList.toggle("show");

}

function updateLangDropdown() {
    var element = document.getElementById("nav-" + getCookie('lang'));
    if (element != null && element.classList != null)
        element.classList.toggle("active");
}

function clearAllDropdowns() {
    var allDropBtns = document.getElementsByClassName('dropbtn');
    for (let i = 0; i < allDropBtns.length; i++) {
        if (allDropBtns[i].classList != null && allDropBtns[i].classList.contains('active'))
            allDropBtns[i].classList.remove("active");

        var dropDown = allDropBtns[i].getElementsByTagName('ul')[0];
        if (dropDown.classList != null && dropDown.classList.contains('show'))
            dropDown.classList.remove("show");
    }

    dropDown.childNodes.forEach(child => {
        if (child != undefined && child.classList != null && child.classList.contains('active'))
            child.classList.remove('active');
    });
}

// Close the dropdown if the user clicks outside of it
window.onclick = function (e) {
    if (!e.target.matches('.dropbtn') && !e.target.matches('.dropdown-content > li > a'))
        clearAllDropdowns();
}

function unlogAccount() {
    if (confirm("Souhaitez vous vous déconnecter ?"))
        window.location.assign("php/logout.php")
}

function closeIFrame() {
    document.getElementById('iframeLog').remove();
    document.location.reload();
}

function setIframe(link, height) {
    document.getElementById('iframeLog').src = link;
    document.getElementById('iframeLog').style.display = "block";
    document.getElementById('iframeLog').height = height;
}