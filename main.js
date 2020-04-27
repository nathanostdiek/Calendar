let months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
let days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
let date=new Date();
let currentMonth = new Month(date.getFullYear(), date.getMonth()); //gets the current date
let currentUser = null;
let TOKEN = null;

//initial upload of the calendar
updateCalendar(currentMonth, date);

// Change the month when the "next" button is pressed
document.getElementById("next_month_btn").addEventListener("click", function(event){
	currentMonth = currentMonth.nextMonth(); // Previous month would be currentMonth.prevMonth()
	updateCalendar(currentMonth, date); // Whenever the month is updated, we'll need to re-render the calendar in HTML
	if(sessionStorage.getItem('status')=="loggedIn"){
		displayEvents();
	}
}, false);

document.getElementById("prev_month_btn").addEventListener("click", function(event){
	currentMonth = currentMonth.prevMonth(); // Previous month would be currentMonth.prevMonth()
	updateCalendar(currentMonth, date); // Whenever the month is updated, we'll need to re-render the calendar in HTML
	if(sessionStorage.getItem('status')=="loggedIn"){
		displayEvents();
	}
	//alert("The new month is "+currentMonth.month+" "+currentMonth.year);
}, false);

// updates the calendar to today's month
function today(){
	currentMonth = new Month(date.getFullYear(), date.getMonth());
	updateCalendar(currentMonth, date);
	displayEvents();
}

//calls the today function when the today button is pressed
document.getElementById("today-btn").addEventListener('click', today, false); 


//if there's a user logged in, then display all their events and edit the display to show their name and the nav-bar
if (sessionStorage.getItem('status')=="loggedIn"){
	displayEvents();
	update_user(sessionStorage.getItem('current_user'));
	hide_login();
	
}

//edits the html to hide the login area and display the navbar with the logout button
function hide_login(){
	document.getElementById("username-group").classList.toggle("dont-show");
	document.getElementById("password-group").classList.toggle("dont-show");
	document.getElementById("logout-btn").classList.toggle("dont-show");
	document.getElementById("nav").classList.toggle("dont-show");
}

//displays the calendar on the main page using bootstrap cards and rows
//uses some of the wiki code as a skeleton
function updateCalendar(thisMonth, today){
	let monthTitle = document.getElementById("current-month");
	monthTitle.innerHTML = "";
	let header = document.createElement('h1');
	let newTextNode = document.createTextNode(months[thisMonth.month]);
	header.setAttribute("id", 'current_month_h1');
	header.appendChild(newTextNode);
	monthTitle.appendChild(header);
	let monthElement = document.getElementById("month");
	monthElement.innerHTML="";

	let weeks = currentMonth.getWeeks();
	for(var w in weeks){
		let newWeekRow = document.createElement("div");
		newWeekRow.setAttribute("class", "row p-1 justify-content-center");
		var days = weeks[w].getDates();
		// days contains normal JavaScript Date objects.
        
		for(var d in days){
			
			let newDay = document.createElement("div");
			newDay.setAttribute("class", "p-1");
			if (days[d].getMonth() != thisMonth.month){
				//checks to see what month the day is in, if it was the previous or next month, the days become 
				//grey with CSS
				newDay.classList.toggle("WRONG-MONTH");
			}
			else if (days[d].getDate() == today.getDate() && days[d].getMonth() == today.getMonth()){
				//looks for today, outlines it in CSS
				newDay.classList.toggle("TODAY");
			}
			//this is the main day card
			let dayCard = document.createElement("div");
			dayCard.setAttribute("class", "card day p-1");
			let cardBody = document.createElement("div");
			dayCard.setAttribute("id", (days[d].getMonth() + 1)+"-"+days[d].getDate()+"-"+days[d].getFullYear())

			//this puts the number in the corner
			cardBody.setAttribute("class", "card-body pt-1");
			let dayNum = document.createElement("div");
			let pEl = document.createElement('p');
			let pText = document.createTextNode(days[d].getDate());
			dayNum.setAttribute("class", "row day-num justify-content-end");
			dayNum.innerHTML = "";
			pEl.appendChild(pText);
			dayNum.appendChild(pEl);

			//appends everything to the dom in the correct order
			dayCard.appendChild(cardBody);
			cardBody.appendChild(dayNum);
			newDay.appendChild(dayCard);
			newWeekRow.appendChild(newDay);
			
		}
		monthElement.appendChild(newWeekRow);
	}
}

//Nate's code :/
//day clicked and modal pops up
$(document.body).on('click','.card', function(event){
	if(!($(this).parent().attr('class')[4] == 'W')){
		let clicked_date = $(this).attr('id');
		document.getElementById("day-title").innerHTML = "";
		document.getElementById("day-title").appendChild(document.createTextNode(clicked_date));
		document.getElementById("add_event_h4").setAttribute("data", clicked_date);
		document.getElementById("display_events").innerHTML = "";
		
		$("#"+clicked_date).children(".displayed_event_cal").each(function() {
			let new_event = document.createElement("div");
			new_event.setAttribute("name", $(this).attr('name'));
			new_event.setAttribute("time", $(this).attr('time'));
			new_event.setAttribute("date", clicked_date);
			new_event.setAttribute("category", $(this).attr('category'));
			new_event.appendChild(document.createTextNode(displayTime($(this).attr('time')) + " - " + $(this).attr('name') + " - Category: " + $(this).attr('category')));
			let new_event_delete_button = document.createElement("button");
			new_event_delete_button.setAttribute("name", $(this).attr('name'));
			new_event_delete_button.setAttribute("class", "can_delete");
			new_event_delete_button.setAttribute("time", $(this).attr('time'));
			new_event_delete_button.setAttribute("date", clicked_date);
			new_event_delete_button.setAttribute("category", $(this).attr('category'));
			new_event_delete_button.appendChild(document.createTextNode("Delete"));
			new_event.appendChild(new_event_delete_button);
			let new_event_edit_button = document.createElement("button");
			new_event_edit_button.setAttribute("name", $(this).attr('name'));
			new_event_edit_button.setAttribute("class", "can_edit");
			new_event_edit_button.setAttribute("time", $(this).attr('time'));
			new_event_edit_button.setAttribute("date", clicked_date);
			new_event_edit_button.setAttribute("category", $(this).attr('category'));
			new_event_edit_button.appendChild(document.createTextNode("Edit"));
			new_event.appendChild(new_event_edit_button);
			document.getElementById("display_events").appendChild(new_event);
		})
		$('#myModal').modal({backdrop: 'static', keyboard: false});
	}
});

//add other users for group events
document.getElementById("get_other_users").addEventListener("click", getUsers);
document.getElementById("close_day").addEventListener("click", close_stuff);

function close_stuff(){
	if(document.getElementById("show-all-users").style.display == "inline-block"){
		document.getElementById("show-all-users").style.display = "none";
	}
}

//Gets users from database to display for group events
function getUsers(){
	event.preventDefault();
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "group-users.php",true);
    xmlhttp.addEventListener("load", groupUsers, false);
    xmlhttp.send(null);
}
//display users on modal for group adding
function groupUsers(event){
	let data = JSON.parse(event.target.response);
	document.getElementById("show-all-users").innerHTML = "";
	for(let a = 0; a < data.length; a++){
		let li = document.createElement("li");
		let user = document.createElement("div");
		user.setAttribute("class", "form-check row");
		let l = document.createElement("label");
		l.setAttribute("class", "form-check-label pl-3");
		l.setAttribute("for", data[a].id);
		let box = document.createElement("input");
		box.setAttribute("class", "form-check-input groupform");
		box.setAttribute("type", "checkbox");
		box.setAttribute("id", data[a].id);
		box.setAttribute("value", data[a].name);
		l.appendChild(box);
		l.appendChild(document.createTextNode(data[a].name));
		user.appendChild(l);
		//user.appendChild(box);
		li.appendChild(user);
		document.getElementById("show-all-users").appendChild(user);
		//document.getElementById("show-all-users").appendChild(document.createTextNode(data[a].name));
	}
	if(document.getElementById("show-all-users").style.display == "inline-block"){
		document.getElementById("show-all-users").style.display = "none";
	}
	else {
		document.getElementById("show-all-users").style.display = "inline-block";

	}
}

//add event button and form button
let add_event_button = document.getElementById("add_event");
let form = document.getElementById('add_form');
let delete_all_button = document.getElementById('delete-all-btn');

form.style.display = 'none';

//add event on day click
add_event_button.addEventListener("click", function(event){
	if (sessionStorage.getItem('status') == 'loggedIn'){
		document.getElementById("add_event_h4").innerHTML = "";
		document.getElementById("add_event_h4").appendChild(document.createTextNode("Adding Event"));
		document.getElementById("form_submit").value = "Submit";
		document.getElementById("form_cancel").style.display = "block";

		form.style.display = 'block';
	}
	else{
    	alert("Must be logged in to add an event.");
	}
	
});

//deletes all events for one user
delete_all_button.addEventListener("click", function(event){
	fetch('delete-all-events.php', {
		method: "POST",
		headers: {
			"Content-Type": "application/json"
		},
		body: JSON.stringify({'token': TOKEN})
		
	})
	.then(res => res.json())
	.then(data => {
		displayEvents();
	})
		.catch(error => console.log("Error: " + JSON.stringify(error)))
});

//Delete button for a specific event is clicked
$(document.body).on('click', ".can_delete", function(event){
	let ename = $(this).attr('name');
	let etime = $(this).attr('time');
	let ecategory = $(this).attr('category');
	let edate = document.getElementById("add_event_h4").getAttribute("data");
	deleteEvent({'title': ename, 'date': fixDate(edate,1), 'time': etime, 'category': ecategory, 'token': TOKEN});
	$("[name='" + ename + "'][time='" + etime + "'][category='" + ecategory + "']").remove();
	displayEvents();
});

//Edit button for specific event is clicked
$(document.body).on('click', ".can_edit", function(event){
	let ename = $(this).attr('name');
	let etime = $(this).attr('time');
	let ecategory = $(this).attr('category');
	let edate = document.getElementById("add_event_h4").getAttribute("data");
	editEvent({'title': ename, 'date': fixDate(edate,1), 'time': etime, 'category': ecategory, 'token':TOKEN});
	$("[name='" + ename + "'][time='" + etime + "'][date='" + edate + "'][category='" + ecategory + "']").remove();
});

//Fills form with old event data and deletes the old event
function editEvent(info) {
	form.style.display = 'block';
	document.getElementById("form_submit").value = "Update";
	document.getElementById("form_cancel").style.display = "none";
	add_event_button.disabled = true;
	document.getElementById("close_day").disabled = true;
	$(".can_edit").attr("disabled", "disabled");
	$(".can_delete").attr("disabled", "disabled");
	document.getElementById("add_event_h4").innerHTML = "";
	document.getElementById("add_event_h4").appendChild(document.createTextNode("Updating"));
	document.getElementById("event_name").value = info.title;
	document.getElementById("event_time").value = info.time;
	document.getElementById("category-select").value = info.category;
	deleteEvent(info);
};

//Delete event from database
function deleteEvent(info){
	fetch('delete-event.php', {
		method: "POST",
		headers: {
			"Content-Type": "application/json"
		},
		body: JSON.stringify(info)
	})
	.then(res => res.json())
	.then(response => console.log('Success:'+ JSON.stringify(response)))
  	.catch(error => console.log('Error:'+ JSON.stringify(error)))
}

//event form submitted, gets all data and displays on page
form.addEventListener('submit', function(event){
	let acheck = [];
	let checked_users = Array.from(document.querySelectorAll('input[type=checkbox]:checked.groupform'));
	for(let a = 0; a < checked_users.length; a++){
		acheck[a] = checked_users[a].id;
		checked_users[a].checked = false;
	}

	$(":button").removeAttr("disabled");
	event.preventDefault();
	//create event div
	let event_name = document.getElementById("event_name");
	let event_time = document.getElementById("event_time");
	let event_date = document.getElementById("add_event_h4").getAttribute("data");
	let event_category = document.getElementById("category-select");
	let new_event = document.createElement("div");
	new_event.setAttribute("name", event_name.value);
	new_event.setAttribute("time", event_time.value);
	new_event.setAttribute("date", event_date);
	new_event.setAttribute("category", event_category.value);
	let dtime = "";
	if(event_time.value.length == 8){
		dtime = displayTime(event_time.value);
	}
	else {
		dtime = displayTime(event_time.value + ":00");
	}
	new_event.appendChild(document.createTextNode(dtime + " - " + event_name.value + " - Category: " + event_category.value));
	//create buttons for each event to edit and delete
	let new_event_delete_button = document.createElement("button");
	new_event_delete_button.setAttribute("name", event_name.value);
	new_event_delete_button.setAttribute("class", "can_delete");
	new_event_delete_button.setAttribute("time", event_time.value);
	new_event_delete_button.setAttribute("date", event_date);
	new_event_delete_button.setAttribute("category", event_category.value);
	new_event_delete_button.appendChild(document.createTextNode("Delete"));
	new_event.appendChild(new_event_delete_button);
	let new_event_edit_button = document.createElement("button");
	new_event_edit_button.setAttribute("name", event_name.value);
	new_event_edit_button.setAttribute("class", "can_edit");
	new_event_edit_button.setAttribute("time", event_time.value);
	new_event_edit_button.setAttribute("date", event_date);
	new_event_edit_button.setAttribute("category", event_category.value);
	new_event_edit_button.appendChild(document.createTextNode("Edit"));
	new_event.appendChild(new_event_edit_button);

	
	sendEvent({'title': event_name.value, 'date': fixDate(event_date, 1), 'time': event_time.value, 'category' : event_category.value, 'token': TOKEN, 'groupusers': acheck});
	let count = 0;
	//display on screen
	if(document.getElementById("display_events").childElementCount == 0){
		document.getElementById("display_events").appendChild(new_event);
	}
	else{
		while(count < document.getElementById("display_events").childElementCount + 1){
			let compare = document.getElementById("display_events").children[count];
			if (event_time.value < compare.getAttribute("time")){
				$(new_event).insertBefore(compare);
	
				break;
			}
			if(count == document.getElementById("display_events").childElementCount - 1){
				$(new_event).insertAfter(compare);
				break;
			}
			count++;
		}
	}
	event_name.value = "";
	event_time.value = "";
	form.style.display = "none";
	if(document.getElementById("show-all-users").style.display == "inline-block"){
		document.getElementById("show-all-users").style.display = "none";
	}
	


});
//form closed
$("#form_cancel").click(function(event){
	let event_name = document.getElementById("event_name");
	let event_time = document.getElementById("event_time");
	let event_date = document.getElementById("add_event_h4").getAttribute("data");
	event.preventDefault();
	event_name.value = "";
	event_time.value = "";
	form.style.display = "none";
	if(document.getElementById("show-all-users").style.display == "inline-block"){
		document.getElementById("show-all-users").style.display = "none";
	}
});

//binds the category toggle button to the display events function, so the events are re-displayed with the correct filter
document.getElementById("toggle-category-btn").addEventListener("click", displayEvents, false);

//pulls the categories that were checked from the category toggle list and returns them as an array
function check_toggled_events(){
	let checked_categories = Array.from(document.querySelectorAll('input[type=checkbox]:checked'));
	checked_categories = checked_categories.map(x => x.value); //pulls just the value
	return checked_categories;
}

//gets events from database and displays on month
function displayEvents(){
	$('.displayed_event_cal').remove();
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "display-events.php",true);
    xmlhttp.addEventListener("load", addEvent_to_calendar, false);
    xmlhttp.send(null);
}

function remove_events(){
	let current_events = document.getElementsByClassName("displayed_event_cal");
	for (let i=0; i<current_events.length; i++){
		current_events[i].remove();
	}
}

//Gets events and shows them on month
function addEvent_to_calendar(event){
	let data = null;
	try{
		data = JSON.parse(event.target.response);
		TOKEN=data[0]['token'];
	} catch(error){
		return;
	}
	let div = document.getElementById("display_events");
	let cal_month = document.getElementById("current_month_h1").textContent;
	let checked_categories = check_toggled_events();
	
	$(".too-many").remove();
	//sorts through data and displays 
	for(let a = 0; a < data.length; a++){
		let c = data[a].category;
		if(checked_categories.includes(c) || checked_categories.includes("all")){

			let n = data[a].name;
			let full_date = fixDate(data[a].date, 2);
			let t = data[a].time;
			let event_month = months[(full_date.substr(0, full_date.indexOf('-')))-1];
			if(event_month == cal_month){
				let e = document.createElement("div");
				e.setAttribute("class", "displayed_event_cal " + c);
				e.setAttribute("name", n);
				e.setAttribute("time", t);
				e.setAttribute("date", full_date);
				e.setAttribute("category", c)
				e.appendChild(document.createTextNode(displayTime(t) + " - " + n.substr(0,6)));
				//don't show more than 4 events so it looks clean
				if(document.getElementById(full_date).childElementCount > 3){
					if(document.getElementById(full_date).childElementCount == 4){
						//either add the number element or remove more events thing
						let too_many = document.createElement("p");
						too_many.setAttribute("class", "too-many");
						too_many.appendChild(document.createTextNode("MORE EVENTS..."));
						document.getElementById(full_date).appendChild(too_many);
					}
					e.style.display = "none";
				}
				document.getElementById(full_date).appendChild(e);
			}
		}
		
	}

}

//converts date to correct form for database
function fixDate(d, type){
	let nstr = "";
	if(type == 1){
		let res = d.lastIndexOf('-');
		let year = d.substring(res+1, d.length);
		nstr = year + '-' + d.substring(0,res);
	}
	if(type == 2){
		let res = d.indexOf('-');
		let year = d.substring(0, res);
		nstr = d.substring(res+1,d.length) + '-' + year;
		if(nstr.substring(0,1) == '0'){
			nstr = nstr.substring(1, nstr.length);
		}
		if(nstr.substring(2,3) == '0'){
			nstr = nstr.substring(0,2) + nstr.substring(3,nstr.length);
		}
	}
	return nstr;
}

//Displays time in standard time with 'am' and 'pm'
function displayTime(t){
		nstr = t.substring(0, t.lastIndexOf(':'));
		if(parseInt(nstr.substring(0,2)) == 0){
			nstr = "12:" + nstr.substring(3, nstr.length) + "am";
		}
		else if(parseInt(nstr.substring(0,2)) == 12){
			nstr = "12:" + nstr.substring(3, nstr.length) + "pm";
		}
		else if(parseInt(nstr.substring(0,2)) < 12){
			if(nstr.substr(0,1) == 0){
				nstr = nstr.substring(1, nstr.length) + "am";
			}
			else {
				nstr = nstr.substring(0, nstr.length) + "am";
			}
		}
		else{
			nstr = (parseInt(nstr.substring(0,2)) - 12) + nstr.substring(nstr.indexOf(':'), nstr.length) + "pm";
		}

	return nstr;
}
//sends event data to database
function sendEvent(info){
	
	fetch('process-event.php', {
		method: "POST",
		headers: {
			"Content-Type": "application/json"
		},
		body: JSON.stringify(info)
	})
	.then(res => res.json())
	.then(data => {
		displayEvents();
	})
  	.catch(error => console.log("ERROR: " + JSON.stringify(error)))
}


//binds all the login buttons to their respective functions
document.getElementById("login-btn").addEventListener("click", process_login, false); // bind ajax to button
document.getElementById("submit-create-account").addEventListener("click", add_user, false);
document.getElementById("logout-btn").addEventListener("click", log_out, false);

//this fills the currently logged in user's name in its correct location (top of the page)
//also resets the div to blank if no name is passed (used during logout)
function update_user(user){
	let user_block = document.getElementById("current-user");
	if (user == null){
		user_block.innerHTML = '';
	}
	else {
		let userText = document.createTextNode(String(user)+"'s Calendar!");
		user_block.appendChild(userText);
	}
}

//logs the user in
function process_login(event){
    let username = document.getElementById("username").value; // Get the username from the form
	let password = document.getElementById("password").value; // Get the password from the form
	let errorMessage = document.getElementById("error-message"); //location of the error message if one needs to be set

	let data = { 'username': username, 'password': password}; //sets the data
	
	//sends the login data to the php file to be processed and checked
	fetch("process-login-ajax.php", {
		method: 'POST',
		body: JSON.stringify(data),
		headers: { 'content-type': 'application/json' }
	})
	.then(response => response.json())
	.then(data => {
		if(data.success){
			//display users events and set a session login
			displayEvents();
			sessionStorage.setItem('status','loggedIn');
			sessionStorage.setItem('current_user', username);
			TOKEN=data.token;
			document.getElementById("username").value=""; //resets the login info to blank
			document.getElementById("password").value=""; //resets the login info to blank
			errorMessage.innerHTML=""; //resets the error message in case one was set
			update_user(username); //displays the users name
			hide_login(); //resets the html
		}
		else {
			//displays error message and resets value
			errorMessage.innerHTML="";
			errorMessage.appendChild(document.createTextNode(data.message));
			document.getElementById("username").value="";
			document.getElementById("password").value="";
		}
	})
	.catch(err => console.error(err));
}

//adds a new user
function add_user(event){

	//grabs all the necessary values from the forms
	let new_user = document.getElementById("new_username").value;
	let new_password = document.getElementById("new_password").value;
	let new_cpassword = document.getElementById("new_cpassword").value;
	let errorMessage = document.getElementById("error-message");

	//sends the new user data to the php file to be checked
	fetch('process-create-account.php', {
		method: 'POST', 
		headers: { 'content-type': 'application/json' },
		body: JSON.stringify({
			username: new_user,
			password: new_password,
			cpassword: new_cpassword
			
		})
	})
	.then(response => response.json())
	.then(data => {
		//for debugging purposes
		if(data.success){
			errorMessage.innerHTML="";
			errorMessage.appendChild(document.createTextNode("Account Created."));
			document.getElementById("new_username").value = "";
			document.getElementById("new_password").value = "";
			document.getElementById("new_cpassword").value = "";
		}
		else{
			//displays the error message
			errorMessage.innerHTML = data.message
			document.getElementById("new_username").value = "";
			document.getElementById("new_password").value = "";
			document.getElementById("new_cpassword").value = "";
		}
	})
	.catch(err => console.log(err))

}

//logs out the user
function log_out(event){
	//calls the logout php file
	fetch('logout.php', {
		method: 'POST', 
		headers: { 'content-type': 'application/json' },
		body: JSON.stringify({
			username: username
		})
	})
	.then(response => response.json())
	.then(data => {
		if(data.success){
			//removes all events and sets all the status to null
			sessionStorage.setItem('status',null);
			$('.displayed_event_cal').remove();
			$(".too-many").remove();

			//updates html to display the login info
			update_user(null);
			hide_login();
			
		}
		else{
			console.log("something went wrong")
		}
	})
	.catch(err => console.log(err))
}



