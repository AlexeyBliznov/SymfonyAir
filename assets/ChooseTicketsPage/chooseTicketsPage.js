import '../styles/app.css';
window.choose = function (ticketNumber, seatType, seatNumber, price) {
    let checked = document.getElementById("checked");
    let checkedTickets = document.getElementById("checkedTickets");
    let element = document.getElementById(ticketNumber);
    let button = document.getElementById("buttonChoose");
    if(element.checked) {
        checked.style.display = "block";
        let newElement = document.createElement("div");
        newElement.setAttribute("id", "checked" + ticketNumber);
        newElement.setAttribute("class", "checkedTicket")
        let text = document.createTextNode("You choose " + seatType + " seat number " + seatNumber + " - " + price + "$");
        newElement.appendChild(text);
        checkedTickets.appendChild(newElement, button);
    }  else {
        let removed = document.getElementById("checked" + ticketNumber);
        removed.remove();
        if (checkedTickets.querySelectorAll("div").length == 0) {
            checked.style.display = "none";
        }
    }
}
