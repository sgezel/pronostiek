<?php
session_start();

$matchdata = json_decode(file_get_contents("data/data.json"), true);

$scoreboard = [];

$scores = [];


foreach ($_POST as $match => $score)
{
	if($match !== "antwoorden")
		$scores[$match] = $score;
}


for($i = 0; $i < count($matchdata); $i++)
{
	for($j=0; $j< count($matchdata[$i]["matches"]); $j++)
	{
		$matchdata[$i]["matches"][$j]["thuis_goals"] = current($scores);
		next($scores);
		$matchdata[$i]["matches"][$j]["uit_goals"] = current($scores);
		next($scores);

	}
}

$fh = fopen("data/data.json", 'w') or die("can't open file");
$stringData = json_encode($matchdata);
fwrite($fh, $stringData);
fclose($fh); 

$files = scandir('data/user/');
foreach($files as $file) {
	if ($file !== "." && $file !== ".."  && file_exists("data/user/" . $file))
	{
		$filename = "data/user/" . $file;
		$userdata =  json_decode(file_get_contents($filename), true);


		$userscores = $userdata["scores"];
		$userdata["points"] = [];

		$corruitslagen = 0;

		foreach($matchdata as $group)
		{
			foreach ($group["matches"] as $match)
			{
				 $punten = 0;

				 if(array_key_exists("scores", $userdata) &&  array_key_exists(str_replace(" ",  "_",$group["naam"]).'_'.str_replace(" ",  "_",$match['thuis']).'_' . str_replace(" ",  "_",$match['uit']).'_t', $userscores) &&  array_key_exists(str_replace(" ",  "_",$group["naam"]).'_'.str_replace(" ",  "_",$match['thuis']).'_' . str_replace(" ",  "_",$match['uit']).'_u', $userscores) )
				 {


				if( $userscores[str_replace(" ",  "_",$group["naam"]).'_'.str_replace(" ",  "_",$match['thuis']).'_' . str_replace(" ",  "_",$match['uit']).'_t'] !== "" && $userscores[str_replace(" ",  "_",$group["naam"]).'_'.str_replace(" ",  "_",$match['thuis']).'_' . str_replace(" ",  "_",$match['uit']).'_u'] !== "" )
				{

				$user_thuis = (int)$userscores[str_replace(" ",  "_",$group["naam"]).'_'.str_replace(" ",  "_",$match['thuis']).'_' . str_replace(" ",  "_",$match['uit']).'_t'];
	            $user_uit =  (int)$userscores[str_replace(" ",  "_",$group["naam"]).'_'.str_replace(" ",  "_",$match['thuis']).'_' . str_replace(" ",  "_",$match['uit']).'_u'];  

	            $official_thuis = (int)$match["thuis_goals"];
	            $official_uit= (int)$match["uit_goals"];

	           

	            if($match["thuis_goals"] !== "" && $match["uit_goals"]!== "" )
	            {
	            
		            if($user_thuis == $official_thuis)
		            	$punten++;

		            if($user_uit == $official_uit)
		            	$punten++;

		            if(abs($user_thuis - $user_uit) == abs($official_thuis - $official_uit))
		            	$punten++;

		            if(($official_thuis > $official_uit && $user_thuis > $user_uit) || ($official_thuis == $official_uit && $user_thuis == $user_uit)  || ($official_thuis < $official_uit && $user_thuis < $user_uit))
		            	$punten++;

		            if($punten == 4)
		            	$corruitslagen++;

	            
	            }
	            }
	            }

	            $userdata["points"][$group["naam"].'_'.str_replace(" ",  "_",$match['thuis']).'_' . str_replace(" ",  "_",$match['uit'])] = $punten;

			}
		}

		$total = 0;
		$bonus = 0;

		$userbonus = [];
		// print_r($_POST["antwoorden"]);

		if(isset($_POST["antwoorden"]))
			foreach($_POST["antwoorden"] as $user => $vraag)
				if($user == $userdata["username"])
					foreach ($vraag as $id => $correct) {
						if($correct == "on")
						{
							$bonus += 5;
							$userbonus [$id] = 5;
						}						
			}
	
		$userdata["bonus"] = $userbonus;

		foreach($userdata["points"] as $point)
		{
			$total = $total + (int)$point;
		}

		$scoreboard[$userdata["name"]]["punten"] = $total + $bonus;
		$scoreboard[$userdata["name"]]["score"] = $total;
		$scoreboard[$userdata["name"]]["bonus"] = $bonus;
		$scoreboard[$userdata["name"]]["corruitslagen"] = $corruitslagen;

		$fh = fopen($filename, 'w') or die("can't open file");
		$stringData = json_encode($userdata);
		fwrite($fh, $stringData);
		fclose($fh); 

	}
	
}

arsort($scoreboard);

$fh = fopen("data/scoreboard.json", 'w') or die("can't open file");
$stringData = json_encode($scoreboard);
fwrite($fh, $stringData);
fclose($fh); 

header("location: /");