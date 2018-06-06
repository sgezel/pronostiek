<?php 
  session_set_cookie_params(604800);
  session_start();
?>
   <?php include("header.php"); ?>

   <?php if (!$logged_in): ?>
       <div class="container">
	<br/>
      <?php if( isset($_SESSION["register"]["message"]) && $_SESSION["register"]["message"] !== ""): ?>
        <div class="alert alert-success">
         <?= $_SESSION["register"]["message"]; ?>
        </div>
        <?php unset($_SESSION["register"]["message"]); ?>
      <?php endif; ?>

      <?php if( isset($_SESSION["login"]["message"]) && $_SESSION["login"]["message"] !== ""): ?>
        <div class="alert alert-danger">
         <?= $_SESSION["login"]["message"]; ?>
        </div>
        <?php unset($_SESSION["login"]["message"]); ?>
      <?php endif; ?>

      <?php if( isset($_SESSION["register"]["errormessage"]) && $_SESSION["register"]["errormessage"] !== ""): ?>
        <div class="alert alert-danger">
         <?= $_SESSION["register"]["errormessage"]; ?>
        </div>
        <?php unset($_SESSION["register"]["errormessage"]); ?>
      <?php endif; ?>

      <div class="row wit">
        <?php if($config["enable_login"]): ?>
       <form class="login-form" action="login.php" method="post">
         <h1>Inloggen</h1>
        <div class="form-group">
          <label for="username">Email</label>
          <input type="email" class="form-control" name="username" id="username" aria-describedby="emailHelp" placeholder="emailadres">
        </div>
        <div class="form-group">
          <label for="password">Wachtwoord</label>
          <input type="password" class="form-control" name="password" id="password" placeholder="Wachtwoord">
        </div>        
        <button type="submit" class="btn btn-primary">Inloggen</button>
      </form>
      <?php endif; ?>
      <?php if($config["enable_registrations"]): ?>
       <form class="login-form" action="register.php" method="post">
       <h1>Registreren</h1>
        <div class="form-group">
          <label for="username">Email</label>
          <input type="email" class="form-control" name="username" id="username" aria-describedby="emailHelp" placeholder="emailadres">
        </div>
        <div class="form-group">
          <label for="name">Naam</label>
          <input type="text" class="form-control" name="name" id="name" aria-describedby="nameHelp" placeholder="Naam">
        </div>
        <div class="form-group">
          <label for="password">Wachtwoord</label>
          <input type="password" class="form-control" name="password" id="password" placeholder="Wachtwoord">
        </div>        
        <button type="submit" class="btn btn-primary">Registreren</button>
      </form>
       <?php endif; ?>
      </div>
    </div>
     <?php endif; ?>

    <?php if ($logged_in): ?>
    <!-- Page Content -->
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h1 class="mt-5">Pr(emed)onostiek </h1>
          <form method="post" action="save.php">
            <?php 
                $data = file_get_contents("data/vragen.json");
                $json = json_decode($data, true);

                $datetime1 =  DateTime::createFromFormat('Y-m-d H:i:s', $config["question_close_time"]); //new DateTime('13/06/2018 17:00');
                
                $datetime2 = new DateTime('NOW');


                $vragenlocked  = ( $datetime1 < $datetime2);


              ?>  

             <table border=1 id="bonus">
               <?php foreach($json as $vragen): ?>
                <tr>
                  <td colspan="6" class="header"><?= $vragen["naam"]; ?></td>
                </tr>

                <tr>
                  <td class="subheader" colspan="3">vraag</td>
                  <td class="subheader">antwoord</td>
                  <td class="subheader">score</td>
                </tr>

                <?php foreach($vragen["vragen"] as $vraag): ?>
                  <tr class="vraag">
                      <td  colspan="3">
                          <?= $vraag["Vraag"]; ?>
                      </td>
                      <td>                          
                          <input type="text" name="vraag_<?= $vraag['id']; ?>" value="<?= isset($_SESSION["data"]["vragen"]["vraag_".$vraag['id']]) ? $_SESSION["data"]["vragen"]["vraag_".$vraag['id']] : ""  ?>" <?= $vragenlocked ? "readonly" : "" ?>  class="<?= $vragenlocked ? "disabled" : "" ?>" />
                      </td>
                      <td>
                        <?=  (isset($_SESSION["data"]["bonus"]) && isset($_SESSION["data"]["bonus"]["vraag_".$vraag['id']])) ? $_SESSION["data"]["bonus"]["vraag_".$vraag['id']] : "0"; ?>
                      </td>
                  </tr>
                <?php endforeach; ?>           
                <?php endforeach; ?>
             </table>



            <table border=1 id="pronostiek">

              <?php 
                $data = file_get_contents("data/data.json");
                $json = json_decode($data, true);
              ?>  
           
             <?php foreach($json as $group): ?>
              <tr>
                  <td colspan="8" class="header"><?= $group["naam"]; ?></td>
              </tr>
              <tr>
                <td class="subheader">datum + tijd</td>
                <td colspan=5 class="subheader">pronostiek</td>
                <td class="subheader">eindstand</td>
                <td class="subheader">score</td>
            </tr>
              <?php foreach($group["matches"] as $match): ?>
                    <?php 
                    $thuis = str_replace(" ",  "_",$group["naam"]).'_'.str_replace(" ",  "_",$match['thuis']).'_' . str_replace(" ",  "_",$match['uit']).'_t';
                    $uit = str_replace(" ",  "_",$group["naam"]).'_'.str_replace(" ",  "_",$match['thuis']).'_' . str_replace(" ",  "_",$match['uit']).'_u';  

                    $vlag_thuis = file_exists("vlaggen/" . $match["thuis"] . ".png") ? "vlaggen/" . $match["thuis"] . ".png" : "vlaggen/default.png";
                    $vlag_uit =  file_exists("vlaggen/" . $match["uit"] . ".png") ? "vlaggen/" . $match["uit"] . ".png" : "vlaggen/default.png";

                    $punten =  $group["naam"].'_'.str_replace(" ",  "_",$match['thuis']).'_' . str_replace(" ",  "_",$match['uit']);
                    ?>
                    <tr class="vraag">

                      <td> <?= $match["datum"]; ?></td>
                      <td class="nowrap"><?= $match["thuis"]; ?></td>
                      <td> <img class="" src="<?= $vlag_thuis; ?>"/></td>
                      <td class="nowrap">
                        <?php 
                            $pronolocked = (DateTime::createFromFormat('d/m/Y H:i', $match["datum"]) < (new DateTime('NOW'))->add(new DateInterval('PT'. $config["close_answer_before_match"] . 'H')));
                          ?>
                          <input type="number" name="<?= $thuis ?>" value="<?= isset($_SESSION["data"]["scores"][$thuis]) ? $_SESSION["data"]["scores"][$thuis] : ""  ?>" <?= $pronolocked ? "readonly" : "" ?>  class="<?= $pronolocked ? "disabled" : "" ?>"  /> 
                          - 
                          <input type="number" name="<?= $uit ?>" value="<?= isset($_SESSION["data"]["scores"][$uit]) ? $_SESSION["data"]["scores"][$uit] : ""  ?>" <?= $pronolocked ? "readonly" : "" ?>  class="<?= $pronolocked ? "disabled" : "" ?>" />
                      </td>
                       <td> <img class="" src="<?= $vlag_uit; ?>"/></td>
                     <td class="nowrap"> <?= $match["uit"]; ?> </td>
                      <td><?= $match["thuis_goals"]; ?> - <?= $match["uit_goals"]; ?></td>
                      <td><?= isset($_SESSION["data"]["points"][$punten]) ? $_SESSION["data"]["points"][$punten] : "" ?></td>
                    </tr>


               <?php endforeach; ?>
             <?php endforeach; ?>
              <tr>
                <td colspan="8" class="subheader"><input type="submit" value="Opslaan"  class="btn btn-success" /></td>
              </tr>
            </table>
          </form>
        </div>
      </div>
    </div>
  <?php endif; ?>
<?php include("footer.php"); ?>