<?php
class MatchDataDatabaseModel extends DatabaseModel
{
	public function getScores($match)
	{
		$query = self::$conn->prepare('SELECT regional.eventcode FROM regional');
		if(!$query->execute()) return false;
		$eventCode = $query->fetch(PDO::FETCH_ASSOC);
		$config = $GLOBALS['config'];
		$auth = "Authorization: Basic " . base64_encode($config->get('api_key') . ':' . $config->get('api_password')); 
		$opts = array(
			'http'=>array(
			'method'=>"GET",
			'header'=> $auth . "\r\n" . "Accept: application/json"
			)
		);
		$context = stream_context_create($opts);
		$url = "https://frc-api.firstinspires.org/v2.0/2018/matches/" . $eventCode['eventcode'] . "?tournamentLevel=qual&matchNumber=" . $match;
		$response = file_get_contents($url, false, $context);
		$json = json_decode($response, true);

		foreach($json as $schedule)
		{
			foreach($schedule as $m)
			{
				$scores = array("red" => $m['scoreRedFinal'], "redAuto" => $m['scoreRedAuto'], "blue" => $m['scoreBlueFinal'], "blueAuto" => $m['scoreBlueAuto']);
				return $scores;
			}
		}
	}

	public function getAllMatchData()
	{
		$query = self::$conn->prepare("SELECT * FROM match_data");
		$query->execute();

		$results = $query->fetchAll(PDO::FETCH_ASSOC);
		if($results === false)
			return false;
			
		$matchData = array();
		
		foreach($results as $result)
		{
			$matchData[] = MatchData::databaseDecode($result);
		}
		return $matchData;
	}
	
	public function getMatchData($match_number, $team_number)
	{
		$query = self::$conn->prepare("SELECT * FROM match_data " .
				"WHERE match_number=:match_number AND team_number=:team_number LIMIT 1");
		$query->bindValue(":match_number", $match_number);
		$query->bindValue(":team_number", $team_number);
		$query->execute();

		$result = $query->fetch(PDO::FETCH_ASSOC);
		if($result !== false)
			return MatchData::databaseDecode($result);
		return false;
	}
	
	public function deleteData($match_number, $team_number)
	{
		$query = self::$conn->prepare('DELETE FROM match_data ' .
				'WHERE match_number=:match_number AND team_number=:team_number LIMIT 1');
		$query->bindValue(':match_number', $match_number);
		$query->bindValue(':team_number', $team_number);
		return $query->execute();
	}
	
	public function getTeamMatchData($team_number)
	{
		$query = self::$conn->prepare("SELECT * FROM match_data " .
				"WHERE team_number=:team_number");
		$query->bindValue(":team_number", $team_number);
		$query->execute();

		$results = $query->fetchAll(PDO::FETCH_ASSOC);
		if($results === false)
			return false;
			
		$matchData = array();
		
		foreach($results as $result)
		{
			$matchData[] = MatchData::databaseDecode($result);
		}
		return $matchData;
	} 
	
	public function getScoutMatchData($scout)
	{
		$query = self::$conn->prepare("SELECT * FROM match_data " .
				"WHERE scout=:scout");
		$query->bindValue(":scout", $scout);
		$query->execute();

		$results = $query->fetchAll(PDO::FETCH_ASSOC);
		if($result === false)
			return false;
			
		$matchData = array();
		
		foreach($results as $result)
		{
			$matchData[] = MatchData::databaseDecode($result);
		}
		return $matchData;
	}
	
	public function enterData($matchData)
	{
		$data = array();
		$dataDefinitions = (new DataDefinitionsDatabaseModel())->getDataDefinitions();
		
		foreach($matchData->data as $key => $val)
		{
			$definition;
			foreach($dataDefinitions as $def)
			{
				if($def["title"] == $key)
				{
					$definition = $def;
					break;
				}
			}
			$index = (MatchData::getSection($definition["section"])) . "_" . (MatchData::getDataType($definition["data_type"])) . "_" . $definition["number"];
			$data += array($index => $val);
		}
		
		$queryString = "INSERT INTO match_data SET ";
		$queryString .= "team_number='" . $matchData->team_number . "', ";
		$queryString .= "match_number='" . $matchData->match_number . "', ";
		$queryString .= "scout='" . $matchData->scout . "', ";
		$queryString .= "dead='" . $matchData->dead . "', ";
		$queryString .= "dead_shortly='" . $matchData->dead_shortly . "', ";
		foreach($data as $index => $val)
		{
			$queryString .= $index . "='" . $val . "', ";
		}
		$queryString = substr($queryString, 0, strlen($queryString) - 2);
		$query = self::$conn->prepare($queryString);
		if(!$query->execute()) return false;
	}
}
?>
