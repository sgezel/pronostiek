<?php
session_start();
if(!isset($_SESSION["data"]["admin"]) && $_SESSION["data"]["admin"]!=true)
	header("location: /");
?>

<?php include("header.php"); ?>

     <?php if ($logged_in): ?>
    <!-- Page Content -->
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h1 class="mt-5">Pr(emed)onostiek Admin</h1>
          <form method="post" action="saveadmin.php">
            <table border=1 id="pronostiek">

              <?php 
                $data = file_get_contents("data/data.json");
                $json = json_decode($data, true);
              ?>  
           
             <?php foreach($json as $group): ?>
              <tr>
                  <td colspan="6" class="header"><?= $group["naam"]; ?></td>
              </tr>
              <tr>
                <td class="subheader">datum + tijd</td>
                <td class="subheader" colspan=3>Score</td>
            </tr>
              <?php foreach($group["matches"] as $match): ?>
                    <?php 
                    $thuis = $group["naam"].'_'.str_replace(" ",  "_",$match['thuis']).'_' . str_replace(" ",  "_",$match['uit']).'_t';
                    $uit = $group["naam"].'_'.str_replace(" ",  "_",$match['thuis']).'_' . str_replace(" ",  "_",$match['uit']).'_u';  


                    ?>
                    <tr class="vraag">

                      <td> <?= $match["datum"]; ?></td>
                      <td class="nowrap"> <img  class="right" src="vlaggen/<?= $match["thuis"]; ?>.png"/><?= $match["thuis"]; ?></td>
                      <td class="nowrap">
                          <input type="number" name="<?= $thuis ?>" value="<?= $match["thuis_goals"]; ?>"/> 
                          - 
                          <input type="number" name="<?= $uit ?>" value="<?= $match["uit_goals"]; ?>"/>
                      </td>
                      <td class="nowrap"><?= $match["uit"]; ?> <img class="left" src="vlaggen/<?= $match["uit"]; ?>.png"/></td>
                    </tr>


               <?php endforeach; ?>
             <?php endforeach; ?>
              <tr>
                <td colspan="6"><input type="submit" /></td>
              </tr>
            </table>

            <?php
            	$files = scandir('data/user/');
            	$vragen = [];

            	$bonusvragenjuist = [];

            	foreach($files as $file) {
					if ($file !== "." && $file !== ".."  && file_exists("data/user/" . $file))
					{
						$filename = "data/user/" . $file;
						$userdata =  json_decode(file_get_contents($filename), true);

						if(array_key_exists("vragen", $userdata))
						{
							$vragen[$userdata["name"]]["username"] = $userdata["username"];
						foreach ($userdata["vragen"] as $vraag => $antwoord) {
							$vragen[$userdata["name"]]["vragen"][$vraag] = $antwoord;
						}
						}	

						if(array_key_exists("bonus", $userdata))
						{
							foreach($userdata["bonus"] as $id => $punten)
							{
								$bonusvragenjuist[$userdata["username"]][$id] = true; 
							}
							
						}

						

					}
				}

				$data = file_get_contents("data/vragen.json");
                $vraagdata = json_decode($data, true);

                $vraagzinnen = [];

                foreach($vraagdata as $vraaggroep)
                {
                	foreach($vraaggroep["vragen"] as $vrdata)
	                {
	                	$vraagzinnen["vraag_" .$vrdata["id"]] = $vrdata["Vraag"];
	                }
                }               				

            ?>

            <table border=1>
            	<tr>
            			<td>Gebruiker</td>
            			<td>Vraag</td>
            			<td>Antwoord</td>
            			<td>Correct?</td>
            	</tr>
	            <?php foreach($vragen as $user => $data): ?>
	            	<?php foreach($data["vragen"] as $vraagid => $antwoord): ?>
	            	<tr>
	            			<td><?= $user; ?></td>
	            			<td><?= $vraagzinnen[$vraagid]; ?></td>
	            			<td><?= $antwoord; ?></td>
	            			<td><input type="checkbox" name="antwoorden[<?= $data["username"]; ?>][<?= $vraagid; ?>]" <?=  isset($bonusvragenjuist[$data["username"]][$vraagid]) ? "checked" : "" ?> /></td>
	            	</tr>
	            		<?php endforeach; ?>

	            	</tr>
	            <?php endforeach; ?>
        	</table>

          </form>
        </div>
      </div>
    </div>
  <?php endif; ?>
<?php include("footer.php"); ?>