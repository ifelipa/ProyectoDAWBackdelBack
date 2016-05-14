<?php //comprueba que la session este iniciada
if (session_status() != PHP_SESSION_ACTIVE) {session_start();}
//variable que ayuda a reconocer al usuario para direccionar el buscador controller
$user=false; if (isset($_SESSION['username'])){     $user=true;}
//variable que auuda a ubicar la imagen

if ($_SESSION['signUp']!=1){ $route="img/logo.png"; } else{$route="../img/logo.png";};
?>
<img alt="Brand" class="img-logo" src="<?php echo $route;?>"/>
<div class="search-wrapper">
    <div class="divisor"></div>
          <h1 class="home-heading">Search your favourite escape room</h1>
	<div class="col-md-6">
        <form  name="search" id="search" method="post" data-user="<?php echo $user; ?>">
              <input type="text" class="form-control" type="text" name="search" id="search-input" autocomplete="on" placeholder="Search for...">
        </form>
	</div>
</div>
<div class="divisor"></div>

<div id="result-wrapper" class="col-md-12">

</div>
