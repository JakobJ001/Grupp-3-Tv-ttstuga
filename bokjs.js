$(document).ready(function(){
/*

Vet icke vad du tänkte göra med de här

*/
var bokingInfo = document.getElementById("bookingDIV");
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

CheckValidDates();

var defWeek = document.getElementById("defWeek");
defWeek.addEventListener("click", function() {offset = 0; CheckValidDates()});

var back = document.getElementById("back");
back.addEventListener("click", function() {--offset; CheckValidDates()});

var forward = document.getElementById("forward");
forward.addEventListener("click", function() {++offset; CheckValidDates()});

//################################
//GLOBAL END GLOBAL END GLOBAL END
//################################
function CheckValidDates()
{
	for (var i = 0; i < 7; ++i)
	{
		CheckValidDay(i);
	}
}

//Checks if the date less occures before or after the date more
function IsEarlier(less, more)
{
	if (less.getFullYear() > more.getFullYear())
	{
		return false;
	}
	if (less.getMonth() > more.getMonth() && less.getFullYear() == more.getFullYear())
	{
		return false;
	}
	if (less.getDate() > more.getDate() && less.getMonth() == more.getMonth() && less.getFullYear() == more.getFullYear())
	{
		return false;
	}
	return true;
}
//Adds a zero to the start of the number if there isn't one
//This is needed for the 
function ConvertedNum(num)
{
	var sNum = num.toString();
	
	if (sNum.length == 1)
	{
		return "0" + sNum;
	}
	return sNum;
}

function CheckValidDay(day)
{
	var weekDay = ShortWeekDayString(day);
	var date = new Date();
	var dif = 7 * offset + (day - date.getDay());
	date.setDate(date.getDate() + dif);
    var dateString = ConvertedNum(date.getMonth() + 1) + "-" + ConvertedNum(date.getDate());
    

	var maxDate = new Date();
	maxDate.setDate(maxDate.getDate() + 31);
	date.setSeconds(0);
	date.setMinutes(0);
	
	var header = document.getElementById(weekDay);
	var longWeekDay = WeekDayString(day);
	header.innerHTML = longWeekDay + "-" + date.getDate();
	
	//Loops through all the different times of the day
	for (var i = 8; i != 24; i += 2)
    {
        
        date.setHours(i);
        var time = GetTime(i);
        var btn = document.getElementById(weekDay + i);
		//If the date has already occured
		if (IsEarlier(date, thisDate))
		{
			btn.classList.remove("btn-success");
			btn.classList.remove("btn-danger");
			btn.classList.remove("btn-info");
			btn.classList.add("btn-secondary");
			btn.value = "-------";
			continue;
		}
		//If the date is more than 31 days from now
		if (IsEarlier(maxDate, date))
		{
			btn.classList.remove("btn-success");
			btn.classList.remove("btn-danger");
			btn.classList.remove("btn-info");
			btn.classList.add("btn-secondary");
			btn.value = "-------";
			continue;
		}
		var fullDateString = date.getFullYear() + "-" + dateString + " " + time + ":00";

		//If the time is already booked
        if (booked.includes(fullDateString))
		{
			btn.classList.remove("btn-success");
			btn.classList.remove("btn-secondary");
			btn.classList.add("btn-danger");
			
            btn.value = "Bokad";
            continue;
		}
		//Standard
		btn.classList.remove("btn-danger");
		btn.classList.remove("btn-secondary");
		btn.classList.remove("btn-info");
		btn.classList.add("btn-success");
		btn.value = "Icke-bokad";
	}
}

//The function that's ran everytime someone presses one of the booking buttons
function ButtonHandling(pressedButton, time, day)
{
  	//
	if (pressedButton.value == "Bokad" || pressedButton.value == "-------")
	{
		return;
	}
	
	CheckValidDates();
	pressedButton.classList.remove("btn-success");
	pressedButton.classList.add("btn-info");
	pressedButton.value = "-----------"; 
	BookingSetup(time, day);
	
	bokingInfo.style.display = null; 
	var toHide = document.getElementById("bookingDIV2");
	toHide.style.display = "none";
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
	document.getElementById("year").innerHTML = "<strong>" + date.getFullYear() + "</strong>";
	document.getElementById("date").innerHTML = "<strong>" + dateString + "</strong>";
	document.getElementById("time").innerHTML = "<strong>" + TimeString(time) + "</strong>";
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
//This isn't done in a loop because something odd happend to the values when I tried it
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


function ShortWeekDayString(day)
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
			weekDay = "sun";
			break;
		case 1:
			weekDay = "mon";
			break;
		case 2:
			weekDay = "tue";
			break;
		case 3:
			weekDay = "wed";
			break;
		case 4:
			weekDay = "thu";
			break;
		case 5:
			weekDay = "fri";
			break;
		case 6:
			weekDay = "sat";
			break;
	}
	return weekDay;
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

	
});
