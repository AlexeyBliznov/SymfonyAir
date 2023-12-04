/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

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

const options = {
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
  };
  
  const calendarInput = new VanillaCalendar('#calendar', options);
  calendarInput.init();
  
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