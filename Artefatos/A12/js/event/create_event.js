$(function () {
    var dateTomorrow = new Date();
    dateTomorrow.setDate(dateTomorrow.getDate() + 1);

    var eventDateTimePicker = $('#eventDateTimePicker');
    eventDateTimePicker.datetimepicker({
        inline: true,
        sideBySide: false,
        date: dateTomorrow
    });

    var create_event_form = $("#create_event_form");
    create_event_form.submit(function (eventObj) {
        console.log(eventObj);
        console.log(eventDateTimePicker.data("DateTimePicker").date().toISOString());
        $('<input>').attr('type', 'hidden')
            .attr('name', "dataInicio")
            .attr('value', eventDateTimePicker.data("DateTimePicker").date().toISOString())
            .appendTo(create_event_form);
        return true;
    });
});