  <footer class="footer bg-dark">
      <div class="container">
      	<span class="left">
      		&copy; Sandor Gezel & Dieter Cams <?= date("Y"); ?>
          versie <?= $config["version"]; ?>
      	</span>

      	<span class="right">
      		
      		<a href="https://github.com/sgezel/pronostiek"><img class=footer-img" src="img/GitHub-Mark-Light-32px.png" /> </a>
      	</span>  
      </div>
    </footer>

 <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

   <script>
   $(document).ready(function(){
      setInterval(function(){ $.get("/keepalive.php"); }, 30000); });
   </script>

  </body>

</html>
