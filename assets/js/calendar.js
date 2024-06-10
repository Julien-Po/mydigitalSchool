window.onload = () => {
    let calendarElt = document.querySelector("#calendar")

    let calendar = new FullCalendar.Calendar(calendarElt, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        timeZone: 'Europe/Paris',
        headerToolBar: {
            start: 'prev, next today',
            center : 'title',
            end: 'dayGridMonth,timeGridWeek'
        }
    })

    calendar.render();
}