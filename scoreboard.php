<?php
session_start();
if(!isset($_SESSION["data"]) && isset($_SESSION["user"]))
	header("location: /");
?>
<?php include("header.php"); ?>

 <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h1 class="mt-5">Pr(emed)onostiek tussenstand </h1>
            <table border=1 id="scoreboard">

              <?php 
                $data = file_get_contents("data/scoreboard.json");
                $json = json_decode($data, true);
              ?>  
             <tr>
                  <td colspan="6" class="header">Tussenstand</td>
              </tr>
               <tr>
               	<td class="subheader">&nbsp;</td>                
                <td class="subheader">Naam</td>
                <td class="subheader">Totaalscore</td>
                <td class="subheader">Score</td>
                <td class="subheader">Bonus</td>
                <td class="subheader"># correcte uitslagen</td>
            </tr>
            <?php $index = 1; ?>
             <?php foreach($json as $name => $score): ?>

             	<tr  class="vraag">
             		<td><?= $index; ?></td>
             		<td><?= $name ?></td>
             		<td><?= $score["punten"] ?></td>
             		<td><?= $score["score"] ?></td>
             		<td><?= $score["bonus"] ?></td>
             		<td><?= $score["corruitslagen"] ?></td>
             	</tr>
             	<?php $index++; ?>
            <?php endforeach; ?>
             

            </table>
        </div>
      </div>
    </div>

<?php include("footer.php"); ?>