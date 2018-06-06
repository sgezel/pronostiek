<?php
	session_set_cookie_params(604800);
	session_start();

	$_SESSION["login"]["message"] = "Gebruikersnaam of wachtwoord incorrect.";

	if(isset($_POST["username"]) && isset($_POST["password"]))
	{
		$username = $_POST["username"];
		$password = $_POST["password"];


		if($username !== "" && $password!=="")
		{
			$filename = "data/user/" .  $username . ".json";

			if(file_exists($filename))
			{
				$userdata =  json_decode(file_get_contents($filename), true);

				if($userdata["password"] == $password)
				{
					$_SESSION["user"] = $userdata["username"];
					$_SESSION["login"]["message"] = "";
					$_SESSION["data"] = $userdata;
				}	
				
			}			
				
		}
	}
	header("location: /");
?>