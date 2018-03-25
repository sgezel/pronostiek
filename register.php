<?php
	session_start();
	
	if(isset($_POST["username"]) && isset($_POST["password"]))
	{
		$username = $_POST["username"];
		$password = $_POST["password"];


		if($username !== "" && $password!=="")
		{
			$filename = "data/user/" .  $username . ".json";

			if(!file_exists($filename))
			{				
				$_POST["points"] = [];

				$fh = fopen($filename, 'w') or die("can't open file");
				$stringData = json_encode($_POST);
				fwrite($fh, $stringData);
				fclose($fh);

				$_SESSION["register"]["message"] = "Uw gebruikers account is aangemaakt. U kan nu inloggen.";
				
			}
			else
			{
				$_SESSION["register"]["errormessage"] = "Er bestaat reeds een account op die email adres.";
			}
		}
	}

	header("location: /");
?>