import '../styles/app.css';

import VanillaCalendar from '@uvarov.frontend/vanilla-calendar';
import '@uvarov.frontend/vanilla-calendar/build/vanilla-calendar.min.css';

require('/images/background1.jpg');
require('/images/background2.jpg');
require('/images/background3.jpg');

window.addEventListener('load', calculateTime)
function calculateTime(){
    let date = new Date();
    let day = date.getDay();
    let hour = date.getHours();
    let minute = date.getMinutes();
    let second = date.getSeconds();
    let ampm = hour >= 12 ? 'PM' : 'AM';
    let dayNames = ['SUN','MON','TUE','WED','THU','FRI','SAT'];

    hour = hour % 12;
    hour = hour ? hour : '12';
    hour = hour < 10 ? '0' + hour : hour;
    minute = minute < 10 ? '0' + minute : minute;
    second = second < 10 ? '0' + second : second;
    
    document.getElementById('day').innerHTML = dayNames[day];
    document.getElementById('hour').innerHTML = hour;
    document.getElementById('minute').innerHTML = minute;
    document.getElementById('second').innerHTML = second;
    document.getElementById('ampm').innerHTML = ampm;

    setTimeout(calculateTime, 1000);
}

window.validateSearchForm = function(){
    let text;
    if( document.searchForm.from.value == "" & document.searchForm.to.value == "" ) {
        text = "You should enter at least one of this fields";
        let error = document.getElementById("error");
        error.style.display = "block";
        error.innerHTML = text;
        document.searchForm.from.focus();
        return false;
    } else {
        return true;
    }
}


  
const passengers = document.getElementById("passengers");


if (passengers) {
    passengers.addEventListener("click", function(e) {
        let submenu = document.getElementById("submenu");
        submenu.style.display = "block";
        document.getElementById("adjustment_count").value = passengers.value;
    })
  }

const adjustmentIncrement = document.getElementById("adjustment_increment");
const adjustmentDecrement = document.getElementById("adjustment_decrement");

adjustmentIncrement.addEventListener("click", function(e) {
    e.preventDefault();
    document.getElementById("adjustment_count").value = ++document.getElementById("adjustment_count").value;
})

adjustmentDecrement.addEventListener("click", function(e) {
  e.preventDefault();
  if (document.getElementById("adjustment_count").value <= 1) {
    document.getElementById("adjustment_count").value = document.getElementById("adjustment_count").value;
  } else {
    document.getElementById("adjustment_count").value = --document.getElementById("adjustment_count").value;
  }
})

const confirmButton = document.getElementById("button_confirm");

confirmButton.addEventListener("click", function(e) {
  e.preventDefault();
  passengers.value = document.getElementById("adjustment_count").value;
  submenu.style.display = "none";
})

let bgImages = [
  '/build/images/background1.046f949c.jpg',
  '/build/images/background2.f300fb60.jpg',
  '/build/images/background3.9f5bb8fe.jpg'
];
let bgIndex = 0;
let bgInterval = 7000;
function bgChange() {
  document.body.style.backgroundImage = "url('" + bgImages[bgIndex] + "')";
  bgIndex++;
  if (bgIndex >= bgImages.length) {
    bgIndex = 0;
  }
}
window.setInterval(bgChange, bgInterval);
window.addEventListener('load', bgChange);

// let options = {
//   input: true,
//   actions: {
//     changeToInput(e, calendar, dates, time, hours, minutes, keeping) {
//       if (dates[0]) {
//         calendar.HTMLInputElement.value = dates[0];
//         calendar.hide();
//       } else {
//         calendar.HTMLInputElement.value = '';
//       }
//     },
//   },
//   settings: {
//     range: {
//       disableAllDays: true,
//       enabled: ['2022-08-10:2022-08-13', '2022-08-22'],
//     }
//   },
// };
// let calendarInput = new VanillaCalendar('#calendar', options);
// calendarInput.init();

let inputFrom = document.getElementById("from");
let inputTo = document.getElementById("to");
$(inputFrom).change( function() {
  let from = inputFrom.value;
  $('.option').remove();
  inputTo.setAttribute('disabled', 'disabled');
  $.ajax({
    type: "GET",
    url: "/api/directions/" + from,
    data: $(this).serialize(),
            success: function(data)
            {
              let datalist = document.getElementById('mainDirections');
              let checkUnique = [];
               for(const city of data) {
                if(!checkUnique.includes(city)) {
                  let element = document.createElement('option');
                  element.setAttribute('value', city);
                  element.setAttribute('class', 'option');
                  datalist.insertBefore(element, null);
                  checkUnique.push(city);
                } else {
                  continue;
                }
              }
              inputTo.removeAttribute('disabled');
            }
  });
})

$(inputTo).change( function() {
  let from = inputFrom.value;
  let to = inputTo.value;
  $.ajax({
    type: "GET",
    url: "/api/dates/" + from + "/" + to,
    data: $(this).serialize(),
            success: function(data)
            {
              let datalist = document.getElementById('mainDirections');
              let checkUnique = [];
               for(const date of data) {
                if(!checkUnique.includes(date)) {
                  checkUnique.push(date);
                } else {
                  continue;
                }
              }
              console.log(checkUnique);
              let options = {
                input: true,
                actions: {
                  changeToInput(e, calendar, dates, time, hours, minutes, keeping) {
                    if (dates[0]) {
                      calendar.HTMLInputElement.value = dates[0];
                      calendar.hide();
                    } else {
                      calendar.HTMLInputElement.value = '';
                    }
                  },
                },
                settings: {
                  range: {
                    disableAllDays: true,
                    enabled: checkUnique,
                  }
                },
              };
              let calendarInput = new VanillaCalendar('#calendar', options);
              calendarInput.init();
            }
  });
})
