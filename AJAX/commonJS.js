$(document).ready(function () {
    var header = document.getElementById("navigation");
    document.body.style.paddingTop = header.offsetHeight + 'px';
    var idProfileContents = document.getElementById("profileContents");
    if (document.getElementById("profileIcon")) {
        $('#profileIcon').click(function () {
            idProfileContents.classList.toggle("profileInfoBoxShow");

        });
        window.onclick = function (e) {

            if (!(e.target.id === "profileIcon") && !(e.target.id === "profileContents")) {
                idProfileContents.classList.remove('profileInfoBoxShow');
            }
        };
    }
})