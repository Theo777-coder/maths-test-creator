$(document).ready(function() {
    var header = document.getElementById("navigation");

    window.onscroll = function() {
        if (window.pageYOffset > header.offsetTop) {
            header.classList.remove("absolute");
            header.classList.add("fixed");
        } else if (window.pageYOffset == 0) {
            header.classList.remove("fixed");
            header.classList.add("absolute");
        }
    }

    var idProfileContents = document.getElementById("profileContents");
    if (document.getElementById("profileIcon")) {
        $('#profileIcon').click(function() {
            idProfileContents.classList.toggle("profileInfoBoxShow");

        });
        window.onclick = function(e) {

            if (!(e.target.id === "profileIcon") && !(e.target.id === "profileContents")) {
                idProfileContents.classList.remove('profileInfoBoxShow');
            }
        };
    }
})