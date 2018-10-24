$(document).ready(function(){

//Här skapas variabler för alla knappar
//8-10
var mon8 = document.getElementById("mon8-10");
var tue8 = document.getElementById("tue8-10");
var wed8 = document.getElementById("wed8-10");
var thu8 = document.getElementById("thu8-10");
var fri8 = document.getElementById("fri8-10");
//10-12

//12-14

//14-16

//16-18

//18-20

//20-22

//22-24

//Övriga knappar
var avbrytKnapp = document.getElementById("avbryt");
var bokaKnapp = document.getElementById("boka");

var bokingInfo = document.getElementById("bookingDIV");
bokingInfo.style.display = "none";

//Här sätts alla knapp funktionerna
//8-10
mon8.addEventListener("click", function(){ ButtonHandling(mon8)});	

var date;
var weekDay;
var offset;

function ButtonHandling(pressedButton){
	 alert(pressedButton.id);
  	 pressedButton.classList.remove("btn-success");
	 pressedButton.classList.add("btn-info");
	 pressedButton.value = "-----------";
	 
	 //HJÄLP: HAR INGEN ANNING HUR MAN FÅR DETTA ATT BLI VARIABLER
	 document.getElementById("weekday").innerHTML = "<strong>Monday<strong>";
	 document.getElementById("date").innerHTML = "<strong>Date<strong>";
	 document.getElementById("time").innerHTML = "<strong>8 - 10<strong>";
	 
	 bokingInfo.style.display = null;
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
