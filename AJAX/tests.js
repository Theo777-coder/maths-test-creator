$(document).ready(function () {

    var noOfTerms = document.forms["testCreationFrom"]["noOfTerms"];
    var magnitudeMin = document.forms["testCreationFrom"]["magnitudeMin"];
    var magnitudeMax = document.forms["testCreationFrom"]["magnitudeMax"];
    var decimalAccuracy = document.forms["testCreationFrom"]["decimalAccuracy"];
    var topicsDiv = document.forms["testCreationFrom"]["topicsDiv"];
    var nodes = document.querySelectorAll("#testCreationFrom input[type=number]");
    var idProfileContents = document.getElementById("profileContents");
    var header = document.getElementById("navigation");
    var selected = [];

    $('#printTest').click(function printFunction() {
        window.print();
    });

    $('#shereTest').click(function shareTest() {
        var testLink = window.location.href;
        var idLinkShare = document.getElementById("linkShare");
        idLinkShare.value = testLink;
        idLinkShare.classList.remove('testCredentials');
        idLinkShare.classList.add('showNotHidden');
        idLinkShare.focus();
        idLinkShare.select();
    });

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

    document.body.style.paddingTop = header.offsetHeight + 'px';

    $('#scoreAmount').keyup(function () {
        var score = document.forms["addScoreForm"]["score"];
        score.className = "small normalBorder";
    });


    $('#createTests').click(function () {

        $('#topicsDiv input:checked').each(function () {
            selected.push($(this).attr('value'));
        });

        if (!noOfTerms.value) {
            return generateError("noOfTerms", "You must choose to have at least 2 tearms");
        }

        if (!magnitudeMin.value) {
            return generateError("magnitudeMin", "Choose a minimum number");
        }

        if (!magnitudeMax.value) {
            return generateError("magnitudeMax", "Choose a maximum number");
        }

        if (!decimalAccuracy.value) {
            return generateError("decimalAccuracy", "Choose a decimal accuracy");
        }

        for (var i = 0; i < nodes.length; i++) {
            if (nodes[i].value < 0) {
                return generateError(nodes[i].id, nodes[i].getAttribute("placeholder") + " can't be negative");
            }
        }
        if (magnitudeMax.value < magnitudeMin.value) {
            return generateError("magnitudeMax", "Magnitude Max must be bigger than Manginuted minimum");
        }

        if (!selected.length) {
            $('#validationMessageTestCreation').text("Please choose at least 1 topic");
            document.getElementById("topicsDiv").classList.add('topicsWarning');
            $("#topicsDiv").focus();
            return false;
        }
        if (getCookie("timer") === "true") {
            return showMessage(this.id, "You are doing another test. Continue?");
        }

        return true;

    });


    $('#magnitudeMax, #magnitudeMin, #noOfTerms, #decimalAccuracy').keyup(function () {
        document.getElementById(this.id).className = "normalBorder";
    });

    $('#topicsDiv').click(function () {
        document.getElementById("topicsDiv").classList.remove('topicsWarning');
    });

    if (getCookie("timer") === "true") {
        document.getElementById("timer").classList.remove('hidden');
        timer();
    }

    if (getCookie("timer") === "done") {
        document.getElementById("timer").classList.remove('hidden');
        $.ajax({
            url: "../model/getTime.php",
            type: "post",
            dataType: 'json',
            success: function (data) {
                document.getElementById("timer").textContent = data;
            }
        });
    }


    $('#checkTest').click(function () {
        var myForm = document.forms.userAnswers;
        var answerBoxes = myForm.elements['result[]'];
        for (var i = 0; i < answerBoxes.length; i++) {
            var aControl = answerBoxes[i];
            if (!aControl.value.trim()) {
                return showMessage(this.id, "You have unfilled questions, empty questions will be marked as wrong. Continue?");
            }
        }
        return true;
    });


    $('#yesButton').click(function () {
        return showMessage(this.id, "");
    });

    $('#overlay, #noButton').click(function () {
        hideMessage();
    });


    if (getCookie("timer") === "true") {
        window.addEventListener('beforeunload', function (e) {
            if (!check) {
                e.preventDefault();
                e.returnValue = '';
                recordTest();
            }

        });
    }

});


function timer() {

    var seconds = 0;
    var minutes = 0;
    var hours = 0;
    function add() {
        seconds++;
        if (seconds >= 60) {
            seconds = 0;
            minutes++;
            if (minutes >= 60) {
                minutes = 0;
                hours++;
            }
        }
        var timerID = document.getElementById("timer");
        timerID.textContent = (hours ? (hours > 9 ? hours : "0" + hours) : "00") +
            ":" + (minutes ? (minutes > 9 ? minutes : "0" + minutes) : "00") +
            ":" + (seconds > 9 ? seconds : "0" + seconds);
    }
    setInterval(add, 1000);
}

var check = false;
var buttonPressed = "";
var mostRecentButtonPressed = "";

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
function showMessage(button, message) {
    var idMessageParagraph = document.getElementById('messageParagraph');
    if (button !== "yesButton") {
        idMessageParagraph.textContent = message;
        buttonPressed = button;
        mostRecentButtonPressed = button;
    }

    if (check) {
        return true;
    }

    if (button === "yesButton") {
        check = true;
        recordTest();
        $("#" + buttonPressed + "").click();
    }


    document.getElementById("messageBox").classList.remove('hidden');
    document.getElementById("messageBox").classList.add('boxDisplay');
    document.getElementById("overlay").classList.remove('hidden');

    return false;
}
function hideMessage() {
    document.getElementById("messageBox").classList.add('hidden');
    document.getElementById("messageBox").classList.remove('boxDisplay');
    document.getElementById("overlay").classList.add('hidden');
}

function recordTest() {
    var myFormAnswers = document.forms.userAnswers.elements['result[]'];
    var answers = [];
    for (var i = 0; i < myFormAnswers.length; i++) {
        var aControl = myFormAnswers[i];
        answers.push(aControl.value.trim());
    }
    $.ajax({
        url: "../model/recordTest.php",
        type: "post",
        data: { testURL: window.location.href, testAnswers: answers }
    });
    $.ajax({
        url: "../model/markTest.php",
        type: "post",
        data: { userAnswers: answers },
        dataType: 'json',
    });
}

function generateError(id, errorMessage) {
    $('#validationMessageTestCreation').text(errorMessage);
    document.getElementById(id).className = "wrongInput";
    document.getElementById(id).focus();
    return false;
}

function getErrorMessage(field) {
    return {
        noOfTerms: "You seem to not have chosen a number of terms. Please choose a number between 2 and 25",
        warning: 'yellow',
        info: 'blue',
        error: 'red'
    }[field];
}