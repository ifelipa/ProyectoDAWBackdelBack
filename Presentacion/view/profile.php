<?php  //comprueba que la session este iniciada
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
//verifica si el usuario esta logueado
if ($_SESSION['signUp']!=1){ header("Location: ../"); }; ?>
	<!DOCTYPE html>
<html lang="en" spellcheck="true">

<head>
	<meta charset="utf-8">
	<title>The Escape</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" type="image/x-icon" href="../img/logo.ico" />
	<?php include 'head.php'; ?>
</head>

<body>
    <?php  	/*MENU*/
        ini_set('display_errors', 'on');
        require_once '../../Logica/functions.php';
        include_once 'menu.php';
	 ?>

	<div class="container" id="admin-page">
	 		<h1>PROFILE</h1>
		<div class="col-md-2">
			<div class="list-btn-profile user-img">
				<img src="../img/250x250.png" class="img-rounded img-responsive" />
			</div>
			<div id="text_profile">
				Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex
				ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat.
			</div>
		</div>

       <?php
       /**AQUI VA TODO EL CONTENIDO DE ADMIN **/
            if ($_SESSION['isAdmin']){
            ?>
              <div class="col-md-8 info-reservation">
               <h4>BOOKINGS MADE IN THE ESCAPE ROOMS THAT YOU MANAGED</h4>
               <div class="list-booking">
                   <?php
                   $dataB = bookingsERAdmin($_SESSION['username']) ;
                   if ($dataB != null) {
                       foreach ($dataB as $value) {
                           $dataBooking = explode("-", $value);
                           echo "<div> DATE:  ".$dataBooking[2]."</div>";
                           echo "<div> HOUR:  ".$dataBooking[3]."</div>";

                            $dataER = returnDataER($dataBooking[1]);
                            foreach ($dataER as $val){
                            $dataEscape = explode("-", $val);
                              echo "<div> ESCAPE ROOM:  ".$dataEscape[0]."</div>";
                              echo "<div> ADDRESS:  ".$dataEscape[1]."</div>";
                            } 
                       }
                   }
                   ?>
               </div>
           </div>

          <?php
            }else{
                /**AQUI VA TODO EL CONTENIDO DE USER **/
        ?>

           <div class="col-md-8 info-reservation">
               <h4>BOOKINGS MADE BY THE USER</h4>
               <div class="list-booking">
                   <?php
                   $dataB = hasBooking($_SESSION['username']) ;
                   if ($dataB != null) {
                       foreach ($dataB as $value) {
                           $dataBooking = explode("-", $value);
                           echo "<div> DATE:  ".$dataBooking[2]."</div>";
                           echo "<div> HOUR:  ".$dataBooking[3]."</div>";

                            $dataER = returnDataER($dataBooking[1]);
                            foreach ($dataER as $val){
                            $dataEscape = explode("-", $val);
                              echo "<div> ESCAPE ROOM:  ".$dataEscape[0]."</div>";
                              echo "<div> ADDRESS:  ".$dataEscape[1]."</div>";
                            }
                       }
                   }
                   ?>
               </div>
           </div>
       <?php }?>
     </div>
	<!--FOOTER-->
	<footer class="footer center">
		<?php include 'footer.php'; ?>
	</footer>
</body>
</html>
