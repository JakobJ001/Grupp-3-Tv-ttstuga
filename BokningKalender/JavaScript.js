$(document).ready(function(){
/*

Vet icke vad du tänkte göra med de här

var avbrytKnapp = document.getElementById("avbryt");
var bokaKnapp = document.getElementById("boka");

var bokingInfo = document.getElementById("bookingDIV");
bokingInfo.style.display = "none";

*/

var btnArr = [];
var offset = 0;
var thisDate = new Date();
ButtonSetup("sun", 0);
ButtonSetup("mon", 1);
ButtonSetup("tue", 2);
ButtonSetup("wed", 3);
ButtonSetup("thu", 4);
ButtonSetup("fri", 5);
ButtonSetup("sat", 6);

function CheckValidDates()
{
	for (var i = 0; i < 7; ++i)
	{
		CheckValidDates(i);
	}
}

function CheckValidDay(day)
{
	var weekDay = WeekDayString(day);
	var date = new Date();
	var dif = 7 * offset + (day - date.getDay());
	date.setDate(date.getDate() + dif);
	var dateString = date.getMonth() + 1 + "-" + date.getDate();
	time = GetTime(time);
	var maxDate = new Date();
	maxDate.setDate(this.getDate + 31);
	date.setSeconds(0);
	date.setMinutes(0);
	
	for (var i = 8; i != 24; i += 2)
	{
		date.setHours(i);
		var btn = getElementById(weekDay + i);
		if (date.getDate() < thisDate.getDate())
		{
			btn.classList.remove("btn-success");
			btn.classList.remove("btn-danger");
			btn.classList.add("btn-secondary");
			btn.value = "-------";
			continue;
		}
		if (maxDate.getDate() < date.getDate())
		{
			btn.classList.remove("btn-success");
			btn.classList.remove("btn-danger");
			btn.classList.add("btn-secondary");
			btn.value = "-------";
			continue;
		}
		var dateString = date.getMonth() + 1 + "-" + date.getDate();
		dateString = date.getFullYear + "-" + dateString + " " + time + ":00";
		if (booked.includes(dateString))
		{
			btn.classList.remove("btn-success");
			btn.classList.remove("btn-secondary");
			btn.classList.add("btn-danger");
			
			btn.value = "Bokad";
		}
		
		btn.classList.remove("btn-danger");
		btn.classList.remove("btn-secondary");
		btn.classList.add("btn-success");
		btn.value = "Icke-bokad";
	}
}

function ButtonHandling(pressedButton, time, day){
  	if (pressedButton.value == "Bokad")
	{
		return;
	}
	
	for (var i = 0; i < btnArr.length; ++i)
	{
		if (pressedButton != btnArr[i] && btnArr[i].value != "Bokad")
		{
			btnArr[i].classList.remove("btn-info");
			btnArr[i].classList.add("btn-success");
			btnArr[i].value = "Icke-bokad";
		}
	}
	pressedButton.classList.remove("btn-success");
	pressedButton.classList.add("btn-info");
	pressedButton.value = "-----------"; 
	BookingSetup(time, day);
	
	bokingInfo.style.display = null; 

} 

function BookingSetup(time, day)
{
	var weekDay = WeekDayString(day);
	document.getElementById("weekday").innerHTML = "<strong>" + weekDay + "<strong>";
	var date = new Date();
	var dif = 7 * offset + (day - date.getDay());
	date.setDate(date.getDate() + dif);
	var dateString = date.getMonth() + 1 + "-" + date.getDate();
	time = GetTime(time);
	document.getElementById("date").innerHTML = "<strong>" + dateString + "<strong>";
	document.getElementById("time").innerHTML = "<strong>" + TimeString(time) + "<strong>";
	document.getElementById("postDate").value = thisDate.getFullYear() + "-" + dateString + " " + time + ":00";
}
//Returns the time as a string for display
function TimeString(time)
{
	switch(time)
	{
		case "08:00":
			return "08:00-10:00";
		case "10:00":
			return "10:00-12:00";
		case "12:00":
			return "12:00-14:00";
		case "14:00":
			return "14:00-16:00";
		case "16:00":
			return "16:00-18:00";
		case "18:00":
			return "18:00-20:00";
		case "20:00":
			return "20:00-22:00";
		case "22:00":
			return "22:00-24:00";
	}
	return "0";
	
}

//Assings the function and right parameters to all of the buttons
function ButtonSetup(day, dayNum)
{
	
	var t = document.getElementById(day + 8);
	t.addEventListener("click", function() {ButtonHandling(this, 8, dayNum)});
	btnArr.push(t);
	
	var t1 = document.getElementById(day + 10);
	t1.addEventListener("click", function() {ButtonHandling(this, 10, dayNum)});
	btnArr.push(t1);
	
	var t2 = document.getElementById(day + 12);
	t2.addEventListener("click", function() {ButtonHandling(this, 12, dayNum)});
	btnArr.push(t2);
	
	var t3 = document.getElementById(day + 14);
	t3.addEventListener("click", function() {ButtonHandling(this, 14, dayNum)});
	btnArr.push(t3);
	
	var t4 = document.getElementById(day + 16);
	t4.addEventListener("click", function() {ButtonHandling(this, 16, dayNum)});
	btnArr.push(t4);
	
	var t5 = document.getElementById(day + 18);
	t5.addEventListener("click", function() {ButtonHandling(this, 18, dayNum)});
	btnArr.push(t5);
	
	var t6 = document.getElementById(day + 20);
	t6.addEventListener("click", function() {ButtonHandling(this, 20, dayNum)});
	btnArr.push(t6);
	
	var t7 = document.getElementById(day + 22);
	t7.addEventListener("click", function() {ButtonHandling(this, 22, dayNum)});
	btnArr.push(t7);
}

//Converts from an int to a string more suitable for sql
function GetTime(time)
{
	switch (time)
	{
		case 8:
			return "08:00";
		case 10:
			return "10:00";
		case 12:
			return "12:00";
		case 14:
			return "14:00";
		case 16:
			return "16:00";
		case 18:
			return "18:00";
		case 20:
			return "20:00";
		case 22:
			return "22:00";
	}
	return false;
	document.getElementById("html").innerHTML = "time variable in function GetTime is out of range";
	alert(time);
}

function WeekDayString(day)
{
	var weekDay;
	if (day < 0 || day > 6)
	{
		document.getElementById("html").innerHTML = "Day variable in function BookingSetup is out of range";
		alert(day);
	}
	switch (day)
	{
		case 0:
			weekDay = "Söndag";
			break;
		case 1:
			weekDay = "Måndag";
			break;
		case 2:
			weekDay = "Tisdag";
			break;
		case 3:
			weekDay = "Onsdag";
			break;
		case 4:
			weekDay = "Torsdag";
			break;
		case 5:
			weekDay = "Fredag";
			break;
		case 6:
			weekDay = "Lördag";
			break;
	}
	return weekDay;
}


/*
pressedButton.onclick = function() {
  	 pressedButton.classList.remove("btn-success");
	 mon8.classList.add("btn-info");
	 mon8.value = "-----------";
	 document.getElementById("weekday").innerHTML = "<strong>Monday<strong>";
	 document.getElementById("date").innerHTML = "<strong>Date<strong>";
	 document.getElementById("time").innerHTML = "<strong>8 - 10<strong>";
	 bokingInfo.style.display = null;
	 pressedButton = mon8;
}
*/

avbrytKnapp.onclick = function() {

    if (bokingInfo.style.display === "none") {
        bokingInfo.style.display = null;
    } else {
        bokingInfo.style.display = "none";
    }
	}

//HJÄLP: HAR INGEN ANNIN HUR MAN FÅR DETTA ATT BLI VARIABLER
bokaKnapp.onclick = function() {
	pressedButton.classList.remove("btn-info");
	pressedButton.classList.add("btn-danger");
	pressedButton.value = "---Bokad---"
}
	
});
