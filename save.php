<?php
session_set_cookie_params(604800);
session_start();

// print_r($_SESSION["data"]);

// print_r($_POST);
// die();

$scores = [];
$vragen = [];

foreach ($_POST as $match => $score)
{
	if(substr($match, 0, strlen("vraag_")) !== "vraag_")
		$scores[$match] = $score;
	else
		$vragen[$match] = $score; //in dit geval gaat het om een bonusvraag
}

$_SESSION["data"]["scores"] = $scores;
$_SESSION["data"]["vragen"] = $vragen;

if(isset($_SESSION["user"]) && !empty($_SESSION["user"]))
{
	$filename = "data/user/" .  $_SESSION["user"] . ".json";

	$fh = fopen($filename, 'w') or die("can't open file");
	$stringData = json_encode($_SESSION["data"]);
	fwrite($fh, $stringData);
	fclose($fh);
}
else
{
	$_SESSION["login"]["message"] = "Uw Sessie is verlopen. Gelieve opnieuw in te loggen.";
}

header("location: /");