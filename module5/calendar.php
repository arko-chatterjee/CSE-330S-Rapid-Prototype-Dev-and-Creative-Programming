<?php
ini_set("session.cookie_httponly", 1);
session_start();
if (!isset($_SESSION['loggedIn'])) {
  $_SESSION['loggedIn'] = false;
}
$logged_in = (bool)$_SESSION['loggedIn'];
?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <style>
    table {
      table-layout: fixed;
    }

    #calendarContent {
      height: 100%;
    }


    .table-hover-cell>tbody>tr>td:hover {
      color: black;
      background-color: lightblue;
    }

    .notCurrentMonth {
      color: rgb(79, 80, 83);
    }

    .cell-hover {
      background-color: gray;
    }

    #startTime,
    #endTime,
    #startDate,
    #endDate {
      width: 33%;
    }
  </style>
  <title>Calendar!</title>
</head>

<body class="bg-secondary">
  <div id='main'>

    <div class="static-top">
      <div class="collapse" id="navbarToggleExternalContent">
        <div class="bg-dark pl-sm-1 pb-sm-1">
          <h5 class="text-white h5">Register Here!</h5>
          <form class="form-inline">
            <div class="form-group">
              <input class="form-control input-sm mr-sm-3" id="regUser" type="text" placeholder="Username" aria-label="Username">
              <input class="form-control input-sm ml-sm-2" id="regPass" type="password" placeholder="Password" aria-label="Password">
              <input class="form-control input-sm ml-sm-1" id="regConfirm" type="password" placeholder="Confirm Password" aria-label="ConfirmPassword">
              <button class="btn btn-outline-success ml-sm-2" id="regBut" type="button" disabled>Register</button>
              <div id="regInfo" class="ml-lg-5 text-light"></div>
            </div>
          </form>
        </div>
      </div>
      <nav class="navbar navbar-dark bg-dark">
        <a class="navbar-brand col-5">Calendar!</a>
        <button id="registerToggler" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle Registration">
          Register!
        </button>
        <form id="logInForm" class="form-inline">
          <div class="form-group">
            <input id="logUser" class="form-control mr-sm-3" type="text" placeholder="Username" aria-label="Username">
            <input id="logPass" class="form-control mr-sm-2" type="password" placeholder="Password" aria-label="Password">
            <button id="loginButton" class="btn btn-outline-success my-2 my-sm-0" type="button">Login</button>
          </div>
        </form>
        <div id='loggedinNav'>
          <div id='loggedInText' class="d-inline text-light"></div>
          <button id="logoutButton" class='btn btn-outline-danger d-inline'>Logout</button>
        </div>
      </nav>
    </div>

    <div id="calendarContent" class="container-fluid body-content">
      <div id="calBar" class="row w-100">
        <h2 id="calTitle" class="h2 text-white pt-0 col-5"></h2>
        <div class="pt-sm-1 col-3 text-cetner">
          <button id="createEvent" class="btn btn-primary text-white" data-toggle="modal" data-target="#exampleModal" disabled>Create Event</button>
          <!-- Modal -->
          <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Create an Event</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form id="eventForm">
                    <div class="form-group">
                      <input id="eventTitle" class="form-control" type="text" placeholder="Event Title" aria-label="Event Title">
                      <textarea id="eventDescription" class="form-control" placeholder="Event Description" aria-label="Event Description" rows="3"></textarea>
                      Start Date <input type="date" id="startDate" value="2020-11-01"> End Date <input type="date" id="endDate" value="2020-11-01"><br>
                      Start Time <input type="time" id="startTime" value="12:00"> End Time <input type="time" id="endTime" value="12:00"><br>
                      <input id="location" class="form-control" type="text" placeholder="Location (Optional)" aria-label="Event Location">
                        <label>Tag: &nbsp;</label>
                        <input type="radio" name="tags" value="work">Work&nbsp;
                        <input type="radio" name="tags" value="home">Home&nbsp;
                        <input type="radio" name="tags" value="school">School&nbsp;
                        <input type="radio" name="tags" value="exercise">Exercise&nbsp;
                        <input type="radio" name="tags" value="task">Task
                    </div>
                  </form>
                </div>
                <div id="eventFooter" class="modal-footer">
                  <button id="cancelEvent" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <script>
                    document.getElementById("createEvent").addEventListener("click", function(){
                      document.getElementById("eventTitle").value = "";
                      document.getElementById("eventDescription").value = "";
                      document.getElementById("startTime").value = "";
                      document.getElementById("endTime").value = "";
                      document.getElementById("startDate").value = "";
                      document.getElementById("endDate").value = "";
                    });
                    document.getElementById("startDate").addEventListener("change",function(){
                      if(document.getElementById("startDate").value > document.getElementById("endDate").value){
                        document.getElementById("endDate").value = document.getElementById("startDate").value;
                      }
                    } ,false);
                    document.getElementById("endDate").addEventListener("change",function(){
                      if(document.getElementById("endDate").value < document.getElementById("startDate").value){
                        document.getElementById("startDate").value = document.getElementById("endDate").value;
                      }
                    } , false);
                  </script>
                  <button id="makeEvent" type="button" class="btn btn-primary">Make Event</button>
                  <div id="eventSInfo">

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class='pt-sm-1 col-4 text-right'>
          <button id="backward" class="btn btn-outline-light">
            &lt; Previous Month</button> <button id="forward" class='btn btn-outline-light'>Next Month >
          </button></div>
      </div>
      <table class="table table-hover-cell text-light bg-secondary my-sm-2 table-bordered">
        <thead class='thead-dark'>
          <tr>
            <th scope='col'>Sunday</th>
            <th scope='col'>Monday</th>
            <th scope='col'>Tuesday</th>
            <th scope='col'>Wednesday</th>
            <th scope='col'>Thursday</th>
            <th scope='col'>Friday</th>
            <th scope='col'>Saturday</th>
          </tr>
        </thead>
        <tbody id="calendarBody">
        </tbody>
      </table>
    </div>

    
    <div id="eventView" class="container-fluid d-none">
      <h2 class="h3">Event Viewer</h2>
      <div id="eventInfoView" class="w-50 text-light">
        
      </div>
    </div>
    

    <script>
      (function() {
        Date.prototype.deltaDays = function(c) {
          return new Date(this.getFullYear(), this.getMonth(), this.getDate() + c)
        };
        Date.prototype.getSunday = function() {
          return this.deltaDays(-1 * this.getDay())
        }
      })();

      function Week(c) {
        this.sunday = c.getSunday();
        this.nextWeek = function() {
          return new Week(this.sunday.deltaDays(7))
        };
        this.prevWeek = function() {
          return new Week(this.sunday.deltaDays(-7))
        };
        this.contains = function(b) {
          return this.sunday.valueOf() === b.getSunday().valueOf()
        };
        this.getDates = function() {
          for (var b = [], a = 0; 7 > a; a++) b.push(this.sunday.deltaDays(a));
          return b
        }
      }

      function Month(c, b) {
        this.year = c;
        this.month = b;
        this.nextMonth = function() {
          return new Month(c + Math.floor((b + 1) / 12), (b + 1) % 12)
        };
        this.prevMonth = function() {
          return new Month(c + Math.floor((b - 1) / 12), (b + 11) % 12)
        };
        this.getDateObject = function(a) {
          return new Date(this.year, this.month, a)
        };
        this.getWeeks = function() {
          var a = this.getDateObject(1),
            b = this.nextMonth().getDateObject(0),
            c = [],
            a = new Week(a);
          for (c.push(a); !a.contains(b);) a = a.nextWeek(), c.push(a);
          return c
        }
      };
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script>
      let logged_in = Boolean("<?php echo $logged_in; ?>");
      let current_user = "<?php echo isset($_SESSION['username']) ? (string)$_SESSION['username'] : NULL; ?>";
      let current_id = "<?php echo isset($_SESSION['user_id']) ? (string)$_SESSION['user_id'] : NULL; ?>";
      let current_token = "<?php echo isset($_SESSION['token']) ? (string)$_SESSION['token'] : NULL; ?>";
      const today = new Date();
      const curMonth = today.getMonth();
      const curYear = today.getFullYear();
      let monthObject = new Month(curYear, curMonth);
      const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

      function updateHeader() {
        if (logged_in) {
          document.getElementById("registerToggler").style.display = "none";
          document.getElementById("registerToggler").disabled = true;
          document.getElementById("logInForm").style.display = "none";
          document.getElementById("loginButton").disabled = true;
          document.getElementById("createEvent").style.display = "block";
          document.getElementById("createEvent").disabled = false;
          document.getElementById("loggedinNav").style.display = "block";
          document.getElementById("logoutButton").setAttribute("class", "btn btn-outline-danger d-inline");
          document.getElementById("loggedInText").setAttribute("class", "d-inline text-light");
          document.getElementById("loggedInText").textContent = "Logged in as user " + current_user;
        } else {
          document.getElementById("registerToggler").style.display = "block";
          document.getElementById("registerToggler").disabled = false;
          document.getElementById("logInForm").style.display = "block";
          document.getElementById("loginButton").disabled = false;
          document.getElementById("createEvent").style.display = "none";
          document.getElementById("createEvent").disabled = true;
          document.getElementById("loggedinNav").style.display = "none";
          document.getElementById("logoutButton").setAttribute("class", "btn btn-outline-danger d-none");
          document.getElementById("loggedInText").setAttribute("class", "d-none text-light");
        }
      }

      //function to create the calendar and populate it with days
      function buildCalendar(reset = false) {
        const calenBody = document.getElementById("calendarBody");
        while (calenBody.firstChild) {
          calenBody.removeChild(calenBody.firstChild);
        }
        const buildMonth = monthObject;
        const cTable = document.getElementById("calendarBody");
        const weeks = buildMonth.getWeeks();
        document.getElementById("calTitle").textContent = monthNames[buildMonth.month] + " " + buildMonth.year;

        for (let i = 0; i < weeks.length; i++) {
          const days = weeks[i].getDates();
          const newWeek = document.createElement("tr");
          document.getElementById("calendarBody").appendChild(newWeek);
          newWeek.setAttribute("id", "week" + i)
          for (let j = 0; j < days.length; j++) {
            const newDay = document.createElement("td");
            newDay.setAttribute("class", "bg-secondary pt-2 cell-hover");
            newDay.setAttribute("id", "day" + days[j].getDate());
            const dayHeader = document.createElement("h4");
            dayHeader.setAttribute("class", "card-title pb-0");
            dayHeader.textContent = days[j].getDate();
            const dayContent = document.createElement("div");
            dayContent.setAttribute("id", monthNames[days[j].getMonth()] + "" + days[j].getDate());
            dayContent.setAttribute("class", "card-body pb-sm-1 pl-0 pr-0 ml-0 mr-0 w-100");
            if (monthNames[days[j].getMonth()] !== monthNames[buildMonth.month]) {
              dayHeader.setAttribute("class", "card-title notCurrentMonth");
              dayContent.setAttribute("class", "card-body pb-1 text-dark")
            }

            newDay.appendChild(dayHeader);
            newDay.appendChild(dayContent);
            document.getElementById("week" + i).appendChild(newDay);
          }
        }

        //verifies user is logged in and gets events
        if(logged_in){
          const data = {
            "userid" : current_id,
            "token":current_token
          };
          fetch("getEvents.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
              'content-type': 'application/json'
            }
          })
          .then(response => response.json())
          .then(data => {
            console.log(data.success ? "Events Received" : `Events not received ${data.message}`);
            for (let i = 0; i < data.titles.length; i++) {
              
              const multi = data.multis[i] == 'Y' ? true:false;
              let date = new Date(data.dates[i]);
              const evtitle = data.titles[i];
              const evdesc = data.descs[i];
              date.setDate(date.getDate()+1);
              const mbegin = monthObject.getWeeks()[0].sunday;
              const mend = monthObject.getWeeks()[monthObject.getWeeks().length-1].nextWeek().sunday;
              if (multi){ //multi is to check if the event is multi-day or not
                const endDate = new Date(data.ends[i]);
                
                endDate.setDate(endDate.getDate()+2);
                let count = 0;
                const eview = document.getElementById("eventInfoView");
                while(date.getTime() !== endDate.getTime()){
                  if(date.getTime() >= mbegin.getTime() && date.getTime() <= mend.getTime()){
                    const eventCal = document.createElement("button");
                    eventCal.setAttribute("class", "btn btn-success w-100");
                    eventCal.setAttribute("value", data.titles[i]);
                    eventCal.setAttribute("id",data.eventids[i] +"eventClick" +  "-" + count);
                    eventCal.textContent=data.titles[i];
                    document.getElementById(monthNames[date.getMonth()] + date.getDate()).appendChild(eventCal);
                    
                    document.getElementById(data.eventids[i] +"eventClick" +  "-" + count).addEventListener("click", function(){
                      clearEventView();
                      const sd = new Date(data.starts[i]);
                      sd.setDate(sd.getDate()+1);
                      const ed = new Date(data.ends[i]);
                      ed.setDate(ed.getDate()+1);
                      const form = document.createElement("form");
                      form.setAttribute("id", "replaceMe");
                      const divGroup = document.createElement("div");
                      divGroup.setAttribute("class", "form-group");
                      const eTitle = document.createElement("input");
                      eTitle.setAttribute("class", "form-control text-dark");
                      eTitle.setAttribute("type", "text");
                      eTitle.setAttribute("id", "eTitle");
                      eTitle.setAttribute("value", evtitle);
                      eTitle.readOnly = true;
                      const eDesc = document.createElement("textarea");
                      eDesc.setAttribute("class", "form-control");
                      eDesc.setAttribute("id", "eDesc");
                      eDesc.textContent = evdesc;
                      eDesc.value = evdesc;
                      eDesc.readOnly=true;
                      const sDate = document.createElement("input");
                      sDate.setAttribute("type", "date");
                      sDate.setAttribute("id" , "sDate");
                      sDate.setAttribute("value", sd.getFullYear()+"-"+(sd.getMonth()<=8? '0'+sd.getMonth()+1 : sd.getMonth()+1)+"-"+(sd.getDate() <= 9 ? '0' + sd.getDate() : sd.getDate()));
                      sDate.readOnly = true;
                      const eDate = document.createElement("input");
                      eDate.setAttribute("type", "date");
                      eDate.setAttribute("id" , "eDate");
                      eDate.setAttribute("value", ed.getFullYear()+"-"+(ed.getMonth()<=8? '0'+ed.getMonth()+1 : ed.getMonth()+1)+"-"+(ed.getDate() <= 9 ? '0' + ed.getDate() : ed.getDate()));
                      eDate.readOnly = true;
                      const sTime = document.createElement("input");
                      sTime.setAttribute("type", "time");
                      sTime.setAttribute("id" , "sTime");
                      sTime.setAttribute("value", "12:00");
                      sTime.readOnly = true;
                      const eTime = document.createElement("input");
                      eTime.setAttribute("type", "time");
                      eTime.setAttribute("id" , "eTime");
                      eTime.setAttribute("value", "12:00");
                      eTime.readOnly = true;
                      const eloc = document.createElement("input");
                      eloc.setAttribute("class", "form-control");
                      eloc.setAttribute("type", "text");
                      eloc.setAttribute("id", "eloc");
                      eloc.setAttribute("value", data.locs[i]);
                      eloc.readOnly = true;
                      const elabel = document.createElement("label");
                      elabel.textContent = "Tags: ";
                      const erad1 = document.createElement("input");
                      erad1.setAttribute("type", "radio");
                      erad1.setAttribute("name", "etags");
                      erad1.setAttribute("value", "work");
                      erad1.checked = data.tags[i].includes("work");
                      erad1.disabled = true;
                      const erad2 = document.createElement("input");
                      erad2.setAttribute("type", "radio");
                      erad2.setAttribute("name", "etags");
                      erad2.setAttribute("value", "home");
                      erad2.checked = data.tags[i].includes("home");
                      erad2.disabled = true;
                      const erad3 = document.createElement("input");
                      erad3.setAttribute("type", "radio");
                      erad3.setAttribute("name", "etags");
                      erad3.setAttribute("value", "school");
                      erad3.checked = data.tags[i].includes("school");
                      erad3.disabled = true;
                      const erad4 = document.createElement("input");
                      erad4.setAttribute("type", "radio");
                      erad4.setAttribute("name", "etags");
                      erad4.setAttribute("value", "exercise");
                      erad4.checked = data.tags[i].includes("exercise");
                      erad4.disabled = true;
                      const erad5 = document.createElement("input");
                      erad5.setAttribute("type", "radio");
                      erad5.setAttribute("name", "etags");
                      erad5.setAttribute("value", "task");
                      erad5.checked = data.tags[i].includes("task");
                      erad5.disabled = true;
                      
                      
                      divGroup.appendChild(eTitle);
                      divGroup.appendChild(eDesc);
                      divGroup.innerHTML+="Start Date: ";
                      divGroup.appendChild(sDate);
                      divGroup.innerHTML+="  End Date: ";
                      divGroup.appendChild(eDate);
                      divGroup.appendChild(document.createElement("br"));
                      divGroup.innerHTML+="Start Time: ";
                      divGroup.appendChild(sTime);
                      divGroup.innerHTML+="   End Time";
                      divGroup.appendChild(eTime);
                      divGroup.appendChild(eloc);
                      if(data.locs[i].length > 1){
                        const golink = document.createElement("a");
                        golink.setAttribute("href", `https://www.google.com/maps/search/${data.locs[i]}`);
                        golink.setAttribute("target", "blank");
                        golink.setAttribute("class", "text-light");
                        golink.textContent = "Click here to go to " +data.locs[i];
                        divGroup.append(golink);
                      }
                      divGroup.appendChild(document.createElement("br"));
                      divGroup.appendChild(elabel);
                      divGroup.appendChild(erad1);
                      divGroup.innerHTML+="Work ";
                      divGroup.appendChild(erad2);
                      divGroup.innerHTML+="Home ";
                      divGroup.appendChild(erad3);
                      divGroup.innerHTML+="School ";
                      divGroup.appendChild(erad4);
                      divGroup.innerHTML+="Exercise ";
                      divGroup.appendChild(erad5);
                      divGroup.innerHTML+="Task ";
                      if(data.tags[i].length>3) divGroup.innerHTML += " &nbsp;&nbsp;   Current: " + data.tags[i];

                      const btnedit = document.createElement("button");
                      btnedit.setAttribute("class", "btn btn-primary");
                      btnedit.setAttribute("id","btnedit");
                      btnedit.setAttribute("value", "Edit Event");
                      btnedit.setAttribute("type", "button");
                      btnedit.textContent="Edit Event";
                      const btndelete = document.createElement("button");
                      btndelete.setAttribute("class", "btn btn-danger");
                      btndelete.setAttribute("id","btndelete");
                      btndelete.setAttribute("value", "Delete Event");
                      btndelete.setAttribute("type","button");
                      btndelete.textContent="Delete Event";

                      form.appendChild(divGroup);
                      form.appendChild(btndelete);
                      form.innerHTML+="&nbsp;&nbsp;&nbsp;";
                      form.appendChild(btnedit);
                      eview.appendChild(form);
                      document.getElementById("btnedit").addEventListener("click",function(){
                        editEvent(data.eventids[i]);
                      },false);
                      document.getElementById("btndelete").addEventListener("click",function(){
                        deleteEvent(data.eventids[i]);
                      },false);
                      document.getElementById("eventView").setAttribute("class", "d-block")
                    }, false);
                  }
                  
                  date.setDate(date.getDate()+1);
                  count++;
                }
              } else{
                if(date.getTime() >= mbegin.getTime() && date.getTime() <= mend.getTime()){
                  const eventCal = document.createElement("button");
                  const eview = document.getElementById("eventInfoView");
                  eventCal.setAttribute("class", "btn btn-success w-100");
                  eventCal.setAttribute("value", data.titles[i]);
                  eventCal.setAttribute("id",data.eventids[i] + "eventClick");
                  eventCal.textContent=data.titles[i];
                  document.getElementById(monthNames[date.getMonth()] + date.getDate()).appendChild(eventCal);
                  document.getElementById(data.eventids[i] + "eventClick").addEventListener("click", function(){
                    clearEventView();
                      const sd = data.starts[i];
                      const ed = data.ends[i];
                      const odate = new Date(data.dates[i]);
                      odate.setDate(odate.getDate()+1)
                      const form = document.createElement("form");
                      form.setAttribute("id", "replaceMe");
                      const divGroup = document.createElement("div");
                      divGroup.setAttribute("class", "form-group");
                      const eTitle = document.createElement("input");
                      eTitle.setAttribute("class", "form-control text-dark");
                      eTitle.setAttribute("type", "text");
                      eTitle.setAttribute("id", "eTitle");
                      eTitle.setAttribute("value", evtitle);
                      eTitle.readOnly = true;
                      const eDesc = document.createElement("textarea");
                      eDesc.setAttribute("class", "form-control");
                      eDesc.setAttribute("id", "eDesc");
                      eDesc.textContent = evdesc;
                      eDesc.value = evdesc;
                      eDesc.readOnly=true;
                      const sDate = document.createElement("input");
                      sDate.setAttribute("type", "date");
                      sDate.setAttribute("id" , "sDate");
                      sDate.setAttribute("value", odate.getFullYear()+"-"+(odate.getMonth()<=8? '0'+odate.getMonth()+1 : odate.getMonth()+1)+"-"+(odate.getDate() <= 9 ? '0' + odate.getDate() : odate.getDate()));
                      sDate.readOnly = true;
                      const eDate = document.createElement("input");
                      eDate.setAttribute("type", "date");
                      eDate.setAttribute("id" , "eDate");
                      eDate.setAttribute("value", odate.getFullYear()+"-"+(odate.getMonth()<=8? '0'+odate.getMonth()+1 : odate.getMonth()+1)+"-"+(odate.getDate() <= 9 ? '0' + odate.getDate() : odate.getDate()));                      eDate.readOnly = true;
                      const sTime = document.createElement("input");
                      sTime.setAttribute("type", "time");
                      sTime.setAttribute("id" , "sTime");
                      sTime.setAttribute("value", sd);
                      sTime.readOnly = true;
                      const eTime = document.createElement("input");
                      eTime.setAttribute("type", "time");
                      eTime.setAttribute("id" , "eTime");
                      eTime.setAttribute("value", ed);
                      eTime.readOnly = true;
                      const eloc = document.createElement("input");
                      eloc.setAttribute("class", "form-control");
                      eloc.setAttribute("type", "text");
                      eloc.setAttribute("id", "eloc");
                      eloc.setAttribute("value", data.locs[i]);
                      eloc.readOnly = true;
                      const elabel = document.createElement("label");
                      elabel.textContent = "Tags: ";
                      const erad1 = document.createElement("input");
                      erad1.setAttribute("type", "radio");
                      erad1.setAttribute("name", "etags");
                      erad1.setAttribute("value", "work");
                      erad1.checked = data.tags[i] == "work";
                      erad1.disabled = true;
                      const erad2 = document.createElement("input");
                      erad2.setAttribute("type", "radio");
                      erad2.setAttribute("name", "etags");
                      erad2.setAttribute("value", "home");
                      erad2.checked = data.tags[i] == "home";
                      erad2.disabled = true;
                      const erad3 = document.createElement("input");
                      erad3.setAttribute("type", "radio");
                      erad3.setAttribute("name", "etags");
                      erad3.setAttribute("value", "school");
                      erad3.checked = data.tags[i] == "school";
                      erad3.disabled = true;
                      const erad4 = document.createElement("input");
                      erad4.setAttribute("type", "radio");
                      erad4.setAttribute("name", "etags");
                      erad4.setAttribute("value", "exercise");
                      erad4.checked = data.tags[i] == "exercise";
                      erad4.disabled = true;
                      const erad5 = document.createElement("input");
                      erad5.setAttribute("type", "radio");
                      erad5.setAttribute("name", "etags");
                      erad5.setAttribute("value", "task");
                      erad5.checked = data.tags[i] == "task";
                      erad5.disabled = true;
                      
                      
                      divGroup.appendChild(eTitle);
                      divGroup.appendChild(eDesc);
                      divGroup.innerHTML+="Start Date: ";
                      divGroup.appendChild(sDate);
                      divGroup.innerHTML+="  End Date: ";
                      divGroup.appendChild(eDate);
                      divGroup.appendChild(document.createElement("br"));
                      divGroup.innerHTML+="Start Time: ";
                      divGroup.appendChild(sTime);
                      divGroup.innerHTML+="   End Time";
                      divGroup.appendChild(eTime);
                      divGroup.appendChild(eloc);
                      if(data.locs[i].length > 1){
                        const golink = document.createElement("a");
                        golink.setAttribute("href", `https://www.google.com/maps/search/${data.locs[i]}`);
                        golink.setAttribute("target", "blank");
                        golink.textContent = data.locs[i];
                        divGroup.append(golink);
                      }
                      divGroup.appendChild(document.createElement("br"));
                      divGroup.appendChild(elabel);
                      divGroup.appendChild(erad1);
                      divGroup.innerHTML+="Work ";
                      divGroup.appendChild(erad2);
                      divGroup.innerHTML+="Home ";
                      divGroup.appendChild(erad3);
                      divGroup.innerHTML+="School ";
                      divGroup.appendChild(erad4);
                      divGroup.innerHTML+="Exercise ";
                      divGroup.appendChild(erad5);
                      divGroup.innerHTML+="Task ";
                      if(data.tags[i].length>3) divGroup.innerHTML += " &nbsp;&nbsp;   Current: " + data.tags[i];

                      const btnedit = document.createElement("button");
                      btnedit.setAttribute("class", "btn btn-primary");
                      btnedit.setAttribute("id","btnedit");
                      btnedit.setAttribute("value", "Edit Event");
                      btnedit.setAttribute("type","button");
                      btnedit.textContent="Edit Event";
                      const btndelete = document.createElement("button");
                      btndelete.setAttribute("class", "btn btn-danger");
                      btndelete.setAttribute("id","btndelete");
                      btndelete.setAttribute("value", "Delete Event");
                      btndelete.setAttribute("type","button");
                      btndelete.textContent="Delete Event";

                      form.appendChild(divGroup);
                      form.appendChild(btndelete);
                      form.innerHTML+="&nbsp;&nbsp;&nbsp;";
                      form.appendChild(btnedit);
                      eview.appendChild(form);

                      document.getElementById("btnedit").addEventListener("click",function(){
                        editEvent(data.eventids[i]);
                      },false);
                      document.getElementById("btndelete").addEventListener("click",function(){
                        deleteEvent(data.eventids[i]);
                      },false);
                      document.getElementById("eventView").setAttribute("class", "d-block")
                  }, false);
                }
              }

            }
          })
          .catch(err => console.error(err));
        }

      }

      //this is used to reset the event view
      function clearEventView(hide=false){
        const viewer = document.getElementById("eventInfoView");
        while(viewer.firstChild){
          viewer.removeChild(viewer.firstChild);
        }
        if(hide){
          document.getElementById("eventView").setAttribute("class", "container-fluid d-none");
        }
      }


      //function for editng events, changes every field to editable and uses those updated values in updating the event
      function editEvent(id){
        const title = document.getElementById("eTitle");
        title.readOnly=false;
        const desc = document.getElementById("eDesc");
        desc.readOnly=false;
        const startTime = document.getElementById("sTime");
        startTime.readOnly=false;
        const endTime = document.getElementById("eTime");
        endTime.readOnly=false;
        const startDate = document.getElementById("sDate");
        startDate.readOnly=false;
        const endDate = document.getElementById("eDate");
        endDate.readOnly=false;
        const location = document.getElementById("eloc");
        location.readOnly=false;
        const tags = document.getElementsByName("etags");
        for(let i=0;i<tags.length;i++){
          tags[i].disabled = false;
        }
        document.getElementById("sDate").addEventListener("change",function(){
          if(document.getElementById("sDate").value > document.getElementById("eDate").value){
            document.getElementById("eDate").value = document.getElementById("sDate").value;
          }
        } ,false);
        document.getElementById("eDate").addEventListener("change",function(){
          if(document.getElementById("eDate").value < document.getElementById("sDate").value){
            document.getElementById("sDate").value = document.getElementById("eDate").value;
          }
        } , false);

        const form = document.getElementById("replaceMe");
        form.removeChild(document.getElementById("btnedit"));
        form.removeChild(document.getElementById("btndelete"));
        const canceledit = document.createElement("button");
        canceledit.setAttribute("id","canceledit");
        canceledit.setAttribute("class", "btn btn-danger");
        canceledit.setAttribute("type","button");
        canceledit.setAttribute("value", "Cancel Edit");
        canceledit.textContent="Cancel Edit";
        const saveedit = document.createElement("button");
        saveedit.setAttribute("id","saveedit");
        saveedit.setAttribute("class", "btn btn-info");
        saveedit.setAttribute("type","button");
        saveedit.setAttribute("value", "Save Edit");
        saveedit.textContent="Save Edit";
        form.appendChild(canceledit);
        form.appendChild(saveedit);

        document.getElementById("canceledit").addEventListener("click", function(){
          document.getElementById("eTitle").value = title.value;
          document.getElementById("eDesc").value = desc.value;
          document.getElementById("sTime").value = startTime.value;
          document.getElementById("eTime").value = endTime.value;
          document.getElementById("sDate").value = startDate.value;
          document.getElementById("eDate").value = endDate.value;
          document.getElementById("eloc").value = location.value;
          document.getElementById("eTitle").readOnly=true;
          document.getElementById("eDesc").readOnly=true;
          document.getElementById("sTime").readOnly=true;
          document.getElementById("eTime").readOnly=true;
          document.getElementById("sDate").readOnly=true;
          document.getElementById("eDate").readOnly=true;
          document.getElementById("eloc").readOnly=true;
          for(let j=0; j<document.getElementsByName("etags").length; j++){
            if(document.getElementsByName("etags")[j].value === tag){
              document.getElementsByName("etags")[j].checked = true;
            } else {
              document.getElementsByName("etags")[j].checked = false;
            }
            document.getElementsByName("etags")[j].disabled=true;
          }
          form.removeChild(document.getElementById("saveedit"));
          form.removeChild(document.getElementById("canceledit"));
          const btnedit = document.createElement("button");
          btnedit.setAttribute("class", "btn btn-primary");
          btnedit.setAttribute("id","btnedit");
          btnedit.setAttribute("value", "Edit Event");
          btnedit.setAttribute("type","button");
          btnedit.textContent="Edit Event";
          const btndelete = document.createElement("button");
          btndelete.setAttribute("class", "btn btn-danger");
          btndelete.setAttribute("id","btndelete");
          btndelete.setAttribute("value", "Delete Event");
          btndelete.setAttribute("type","button");
          btndelete.textContent="Delete Event";
          form.appendChild(btndelete);
          form.innerHTML+="&nbsp;&nbsp;&nbsp;";
          form.appendChild(btnedit);

          document.getElementById("btnedit").addEventListener("click",function(){
              editEvent(id);
            },false);
          document.getElementById("btndelete").addEventListener("click",function(){
              deleteEvent(id);
            },false);

        }, false);
        document.getElementById("saveedit").addEventListener("click", function(){ //updates after saving edit
          const stitle = document.getElementById("eTitle");
          const sdesc = document.getElementById("eDesc");
          const sstartTime = document.getElementById("sTime");
          const sendTime = document.getElementById("eTime");
          const sstartDate = document.getElementById("sDate");
          const sendDate = document.getElementById("eDate");
          const slocation = document.getElementById("eloc");
          const stags = document.getElementsByName("etags");
          let tag = null;
          for (let i = 0; i < stags.length; i++) {
            if(stags[i].checked){
              tag = stags[i].value;
              break;
            }
          }
          data = {
            "eventid":id,
            "userid":current_id,
            "token":current_token,
            "title" : stitle.value,
            "desc":sdesc.value,
            "startDate":sstartDate.value,
            "endDate":sendDate.value,
            "startTime":sstartTime.value,
            "endTime":sendTime.value,
            "location":slocation.value,
            "tags": tag
          };
          fetch("editEvent.php",{
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
              'content-type': 'application/json'
            }
          })
          .then(response => response.json())
          .then(data =>{
            console.log(data.success ? "Event Edit!" : `Event NOT edited ${data.message}`);
            if(data.success){
              document.getElementById("eTitle").readOnly=true;
              document.getElementById("eDesc").readOnly=true;
              document.getElementById("sTime").readOnly=true;
              document.getElementById("eTime").readOnly=true;
              document.getElementById("sDate").readOnly=true;
              document.getElementById("eDate").readOnly=true;
              document.getElementById("eloc").readOnly=true;
              for(let j=0; j<stags.length; j++){
                if(stags[j].value == tag){
                  stags[j].checked = true;
                }
                stags[j].disabled=true;
              }
              form.removeChild(document.getElementById("saveedit"));
              form.removeChild(document.getElementById("canceledit"));
              const btnedit = document.createElement("button");
              btnedit.setAttribute("class", "btn btn-primary");
              btnedit.setAttribute("id","btnedit");
              btnedit.setAttribute("value", "Edit Event");
              btnedit.setAttribute("type","button");
              btnedit.textContent="Edit Event";
              const btndelete = document.createElement("button");
              btndelete.setAttribute("class", "btn btn-danger");
              btndelete.setAttribute("id","btndelete");
              btndelete.setAttribute("value", "Delete Event");
              btndelete.setAttribute("type","button");
              btndelete.textContent="Delete Event";
              form.appendChild(btndelete);
              form.innerHTML+="&nbsp;&nbsp;&nbsp;";
              form.appendChild(btnedit);
              buildCalendar();
            }
          })
          .catch(err => console.error(err));
        }, false);
        buildCalendar();
      }

      function deleteEvent(id){//function for deleting events
        const data =  {
          "eventid":id,
          "userid":current_id,
          "token" : current_token
        }
        fetch("deleteEvent.php", {
          method: 'POST',
          body: JSON.stringify(data),
          headers: {
            'content-type': 'application/json'
          }
        })
        .then(response => response.json())
        .then(data => {
          console.log(data.success ? "Event Delete!" : `event NOT delete ${data.message}`);
          // if(data.success){
            buildCalendar();
            clearEventView(true);

          // }
        })
        .catch(err => console.error(err));
      }

      function loginAjax(event) {
        const username = document.getElementById("logUser").value; // Get the username from the form
        const password = document.getElementById("logPass").value; // Get the password from the form

        // Make a URL-encoded string for passing POST data:
        const data = {
          'username': username,
          'password': password
        };

        fetch("login_ajax.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
              'content-type': 'application/json'
            }
          })
          .then(response => response.json())
          .then(data => {
            console.log(data.success ? "You've been logged in!" : `You were not logged in ${data.message}`);
            current_user = data.username;
            logged_in = data.success;
            if (!logged_in) {
              $("#loginButton").removeClass("btn-outline-success");
              $("#loginButton").addClass("btn-outline-danger");
              setTimeout(function() {
                $("#loginButton").removeClass("btn-outline-danger");
                $("#loginButton").addClass("btn-outline-success");
              }, 500);
            }
            current_id = data.id;
            current_token = data.token;
            buildCalendar();
            updateHeader();
          })
          .catch(err => console.error(err));
      }

      function registerAjax(event) {//registers users based on their username/password
        const username = document.getElementById("regUser").value;
        const password = document.getElementById("regPass").value;

        const data = {
          'username': username,
          'password': password
        };

        fetch("registerAjax.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
              'content-type': 'application/json'
            }
          })
          .then(response => response.json())
          .then(data => {
            console.log(data.success ? "You've been logged in!" : `You were not logged in ${data.message}`);
            document.getElementById("regInfo").textContent = data.message;
          })
          .catch(err => console.error(err));
      }

      function eventCreate(event){ //used to send events to the database and create them
        const title = document.getElementById("eventTitle").value;
        const desc = document.getElementById("eventDescription").value;
        const startTime = document.getElementById("startTime").value;
        const endTime = document.getElementById("endTime").value;
        const startDate = document.getElementById("startDate").value;
        const endDate = document.getElementById("endDate").value;
        const location = document.getElementById("location").value;
        const tags = document.getElementsByName("tags");
        let tag = null;
        for (let i = 0; i < tags.length; i++) {
          if(tags[i].checked){
            tag = tags[i].value;
            break;
          }
        }
        const data = {
          'username' : current_user,
          'userid':current_id,
          'title':title,
          'desc':desc,
          'startDate':startDate,
          'endDate':endDate,
          'startTime':startTime,
          'endTime':endTime,
          'location':location,
          'token':current_token,
          'tags':tag
        }
        fetch("createEvent.php", {
          method: 'POST',
            body: JSON.stringify(data),
            headers: {
            'content-type': 'application/json'
            }
          })
          .then(response => response.json())
          .then(data => {
            console.log(data.success ? "Event successfully created" : `Event not created: ${data.message}`);
            document.getElementById("eventSInfo").textContent = data.success ? "Event successfully created" : `Event not created: ${data.message}`;
            buildCalendar();
          })
          .catch(err => console.error(err));
      }

      function logout(event) { //destroys all cookies and reset global variables that were in use
        fetch("logout.php", {
            method: "POST"
          })
          .then(response => response.json())
          .then(data => {
            console.log(data.success ? "Logged out sucessfully" : "Not Logged out")
            logged_in = false;
            current_user = null;
            current_id = null;
            current_token = null;
            buildCalendar();
            updateHeader();
            clearEventView(true);
          })
          .catch(err => console.error(err));
      }

      function regCheck() {
        //makes sure that the entered username and password in the register form are valid
        const pwd = document.getElementById("regPass").value;
        const pwdConf = document.getElementById("regConfirm").value;
        const usern = document.getElementById("regUser").value;
        const regexer = /^[A-Za-z0-9_\@\.\/\#\&\+\-]*$/;
        const regInfo = document.getElementById("regInfo");
        console.log(pwd);
        if (pwd !== pwdConf) { //checks if btoh the password and confirm password are the same
          document.getElementById("regBut").disabled = true;
          regInfo.textContent = "Passwords do not match";
        } else if (pwd.length < 2 || pwdConf.length < 2 || usern.length < 2 || !regexer.test(usern) || !regexer.test(pwd)) { //checks if username and password are valid for putting into database
          document.getElementById("regBut").disabled = true;
          regInfo.textContent = "Invalid Username or Password";
        } else {
          document.getElementById("regBut").disabled = false;
          regInfo.textContent = "";
        }
      }
      document.getElementById("makeEvent").addEventListener("click", eventCreate , false);

      document.getElementById("logoutButton").addEventListener("click", logout, false);

      document.getElementById("loginButton").addEventListener("click", loginAjax, false); // Bind the AJAX call to button click\

      document.getElementById("regBut").addEventListener("click", registerAjax, false);

      document.addEventListener("DOMContentLoaded", function() {
        buildCalendar();
        updateHeader();
      }, false);
      document.getElementById("forward").addEventListener("click", function() {
        monthObject = monthObject.nextMonth();
        buildCalendar(true);
      }, false);
      document.getElementById("backward").addEventListener("click", function() {
        monthObject = monthObject.prevMonth();
        buildCalendar(true);
      }, false);
      document.getElementById("regPass").addEventListener("input", regCheck, false);
      document.getElementById("regConfirm").addEventListener("input", regCheck, false);
      document.getElementById("regUser").addEventListener("input", regCheck, false);
    </script>
  </div>
</body>

</html>