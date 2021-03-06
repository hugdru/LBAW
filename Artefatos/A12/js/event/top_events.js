$().ready(loadDocument);

globalTimeout = null;

function loadDocument() {
    $('#search_bar').keyup(function () {
            if (globalTimeout !== null) {
                clearTimeout(globalTimeout);
            }
            globalTimeout = setTimeout(getEvents, 350);
        }
    );
    $('#search_bar').load(getEvents());

}

function getEvents() {
    globalTimeout = null;
    $.ajax({
        type: 'GET',
        url: '../../api/get_top_events.php',
        success: function (data) {

            var $search_results = $('#search_results');
            $search_results.empty();

            if (data) {
                var jsonArray = JSON.parse(data);

                var index = 0;
                var $row = $("<div>", {class: "row"});
                $search_results.append($row);

                for (var property in jsonArray) {
                    var event = jsonArray[property];

                    var $anchor = $("<a>", {href: "../event/view_event.php?id=" + event.idevento, class: "eventbox"});
                    $anchor.append("<h3 class=\"title\">" + event.titulo + "</h3>");
                    $anchor.append("<p class=\"details\">" + event.localizacao + "</p>");
                    $anchor.append("<p class=\"details\">" + new Date(event.datainicio) + "</p>");
                    $anchor.append("<p class=\"description\">" + event.descricao + "</p>");
                    $anchor.append("<p class=\"details\">" + event.numero_de_participantes + " Participantes</p>");

                    var $eventDiv = $("<div>", {class: "col-sm-4"});
                    $eventDiv.append($anchor);

                    $row.append($eventDiv);
                    ++index;

                    if (index == 3) {
                        $row = $("<div>", {class: "row"});
                        $search_results.append($row);
                        index = 0;
                    }
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(textStatus + " in pushJsonData: " + errorThrown + " " + jqXHR);
        }
    });
}