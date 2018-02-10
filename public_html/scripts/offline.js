$(document).ready(function() {
  var schedule = [];
  var teams = [];
  var matchData = [];
  var dataDefinitions = [];
  
  console.log("Online: " + navigator.onLine);
  if(navigator.onLine)
  {
    request = $.ajax({
        url: "/?p=offline&do=getSchedule",
        type: "get"
    });
    request.done(function (response, textStatus, jqXHR) {
      if(response == "NOT LOGGED IN")
      {
        window.location.replace("/?p=login");
      }
      else
      {
        var list = JSON.parse(response);
        list.forEach(function(item, index) {
          schedule.push(new Match(item['match_number'], item['time'], item['red_1'], item['red_2'], item['red_3'], item['blue_1'], item['blue_2'], item['blue_3']));
        });
        console.log("Schedule Updated from Database");
        localStorage.setItem("schedule", response);
      }
    });
    
    request = $.ajax({
        url: "/?p=offline&do=getTeams",
        type: "get"
    });
    
    request.done(function (response, textStatus, jqXHR) {
      if(response == "NOT LOGGED IN")
      {
        window.location.replace("/?p=login");
      }
      else
      {
        var list = JSON.parse(response);
        list.forEach(function(item, index) {
          teams.push(new Team(item['number'], item['name'], item['pit_notes'], item['averages']));
        });
        console.log("Teams Updated from Database");
        localStorage.setItem("teams", response);
      }
    });
  }
  else
  {
    response = localStorage.getItem("schedule");
    var list = JSON.parse(response);
    list.forEach(function(item, index) {
      schedule.push(new Match(item['match_number'], item['time'], item['red_1'], item['red_2'], item['red_3'], item['blue_1'], item['blue_2'], item['blue_3']));
    });
    console.log("Schedule Loaded Offline");
    
    response = localStorage.getItem("teams");
    var list = JSON.parse(response);
    list.forEach(function(item, index) {
      teams.push(new Team(item['number'], item['name'], item['pit_notes'], item['averages']));
    });
    console.log("Teams Loaded Offline");
  }
});
