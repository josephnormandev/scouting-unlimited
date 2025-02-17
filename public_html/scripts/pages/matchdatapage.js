var matchDataPage = "";

function loadOffline()
{
	offline = !navigator.onLine;
	setInterval(function() {
		checkOffline();
	}, 500);

	loadOfflineMatchData();
	loadOfflinePitNotes();

	if(!offline)
	{
		loadMatchData();
		loadSchedule();
		loadDataDefinitions();

		loadPage();
		loadErrorPage();
	}
	else
	{
		loadMatchDataOffline();
		loadScheduleOffline();
		loadDataDefinitionsOffline();

		loadPageOffline();
		loadErrorPageOffline();
		
		completeAjax();
	}
}

function loadPage() 
{
	request = $.ajax({
			url: "/?p=dataentry&do=displaymatchdata",
			type: "get"
	});
	request.done(function(response, textStatus, jqXHR) {
		if(response == "NOT LOGGED IN")
		{
			window.location.replace("/?p=login");
		}
		else
		{
			matchDataPage = response;
			localStorage.setItem("matchDataPage", matchDataPage);
		}
	});
}

function loadPageOffline()
{
	matchDataPage = localStorage.getItem("matchDataPage");
}

async function pastePage()
{
	var url = new URL("http://localhost" + window.location.href);
	matchnumber = url.searchParams.get("match");

	document.title = "Match " + matchnumber + " - CRyptonite Robotics";
	$('.index-content').empty();
	$('.index-content').append(matchDataPage);
	
	$('.page-section-head').text("Match " + matchnumber);
	
	var match = getMatch(matchnumber);
	$('#red1').text(match.red_1).attr('href', "/?p=teamdata&team=" + match.red_1);
	$('#red2').text(match.red_2).attr('href', "/?p=teamdata&team=" + match.red_2);
	$('#red3').text(match.red_3).attr('href', "/?p=teamdata&team=" + match.red_3);
	$('#blue1').text(match.blue_1).attr('href', "/?p=teamdata&team=" + match.blue_1);
	$('#blue2').text(match.blue_2).attr('href', "/?p=teamdata&team=" + match.blue_2);
	$('#blue3').text(match.blue_3).attr('href', "/?p=teamdata&team=" + match.blue_3);
	
	for(var index in match.getMatchData())
	{
		var matchData = match.getMatchData()[index];
		if(matchData != null)
		{
			if(matchData.dead == 1)
			{
				$('#' + index + "_dead").text("True");
				continue;
			}
			else
			{
				$('#' + index + "_dead").text("False");
			}
			if(matchData.dead_shortly == 1)
			{
				$('#' + index + "_dead_shortly").text("True");
			}
			else
			{
				$('#' + index + "_dead_shortly").text("False");
			}
			for(var i in matchData.data)
			{
				var id = index + "_" + i.replace(/ /g, "-");
				$('#' + id + '').text(definitionDisplay(i, matchData.data[i]));
			}
		}
	}
}
