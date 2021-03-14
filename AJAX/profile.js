$(document).ready(function () {
    var header = document.getElementById("navigation");
    document.body.style.paddingTop = header.offsetHeight + 'px';
    var offset = 0;
    $('#loadHistory').click(function () {
        getHistory(offset);
        offset = offset + 10;
    });


});


function getHistory(offset) {
    $.ajax({
        url: "../model/getHistory.php",
        type: "GET",
        data: { offset: offset },
        dataType: 'json',
        success: function (data) {
            var i;
            if (!$.trim(data)) {
                $("#loadHistory").prop("disabled", true);
                $("#loadHistory").html('No more records');
            } else {
                for (i = 0; i < data.length; i++) {
                    $('#historyTable').append("<tr><td>" + document.getElementById('historyTable').rows.length + "</td><td>" + data[i].testtime + "</td><td>" + data[i].score + "</td><td><a href=" + data[i].url + ">link to test</a></td></tr>");
                }
            }
        }
    });
}

