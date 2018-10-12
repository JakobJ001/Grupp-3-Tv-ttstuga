$(document).ready(function(){

var b1 = document.getElementById("mon8-10");
var b2 = document.getElementById("tue8-10");
var b3 = document.getElementById("wed8-10");
var b4 = document.getElementById("thu8-10");
var b5 = document.getElementById("fri8-10");

b1.onclick = function() {
     b1.classList.remove("btn-success")
	 b1.classList.add("btn-danger")
}
	
b2.onclick = function() {
     b1.style.background = "";
     b2.style.background = "green";   
}

b3.onclick = function() {
     b1.style.background = "";
     b2.style.background = "green";   
}
b4.onclick = function() {
     b1.style.background = "";
     b2.style.background = "green";   
}
b5.onclick = function() {
     b1.style.background = "";
     b2.style.background = "green";   
}

var date = new Date();

var currDay = date.prototype.getDay();






});