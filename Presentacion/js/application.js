$(function() {
	
	/**VALIDATOR ADD NEW USER*/
	$('#addNewUser').find(':input').focusout(function(){
		var values = $(this).val();
		var name = $(this).attr('name');
		var myID='#'+$(this).attr('id');

		if (name == 'name' || name == 'surname'){
			if (valText(values) == 1 || valText(values) == 2) {
				resaltarErr(myID);
			} else if (valText(values) == 3) {
				resaltarErr(myID);
			} else {
				resaltarBien(myID);
			}
		}else if (name=='username') {
			if (valText(values) == 1 || valText(values) == 2) {
				resaltarErr(myID);
			}else {
				resaltarBien(myID);
			}
		}else if (name=='password') {
			if (valText(values) == 1 || valText(values) == 2) {
				resaltarErr(myID);
			}else {
				resaltarBien(myID);
			}

		}else if (name=='email') {
			if (valText(values) == 1 || valText(values) == 2) {
				resaltarErr(myID);
			} else if (!valEMail(values)) {
				resaltarErr(myID);
			} else {
				resaltarBien(myID);
			}
		}else if (name=='phone') {
		
			if (valText(values) == 1 || valText(values) == 2) {
				resaltarErr(myID);
			} else if (!valTelefono(values)) {
				resaltarErr(myID);
			} else {
				resaltarBien(myID);
			}
		}else if (name=='zip_code') {
			if (valText(values) == 1 || valText(values) == 2) {
				resaltarErr(myID);
			} else if (!valCP(values)) {
				resaltarErr(myID);
			} else {
				resaltarBien(myID);
			}
		}

	})


	/****ERUSER*****/
		var use_parking = $('#listER_reservations option:selected').val();
		$('select#listER_reservations').change(function(){
			use_parking=$('#listER_reservations option:selected').val();
						console.log(use_parking);
		});

	  $("#hasparking").click(function() { 

        if($("#hasparking").is(':checked')) {  
            $('#park-available').css('display','block');
				 $.ajax({
					type: 'POST',
					url: '../../Logica/controller.php',
					data: ('dataParking='+use_parking),
					success: function(resp){
						if(resp!=""){
							$('#listParking').html(resp);
						}
					}
				})
        } else {
            $('#park-available').css('display','none');
        }  
    });


      /*MAP USER */
	$('select#selectPosi').change(function(){
		$('select option:selected').each(function(){
			$('.prueba').text("hola");
		})
	});
	
	$('#push_file').on('change', function() {
		fileSelected();
	});



});//end jquery

/****LOGIN FACEBOOK***/
// This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
        // Logged into your app and Facebook.
        testAPI();
    } else if (response.status === 'not_authorized') {
        // The person is logged into Facebook, but not your app.
        document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
        // The person is not logged into Facebook, so we're not sure if
        // they are logged into this app or not.
        document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
}

// This function is called when someone finishes with the Login
// Button.  See the onlogin handler attached to it in the sample
// code below.
function checkLoginState() {
    FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
    });
}

window.fbAsyncInit = function() {
    FB.init({
        appId      : '{your-app-id}',
        cookie     : true,  // enable cookies to allow the server to access
                            // the session
        xfbml      : true,  // parse social plugins on this page
        version    : 'v2.2' // use version 2.2
    });

    // Now that we've initialized the JavaScript SDK, we call
    // FB.getLoginStatus().  This function gets the state of the
    // person visiting this page and can return one of three states to
    // the callback you provide.  They can be:
    //
    // 1. Logged into your app ('connected')
    // 2. Logged into Facebook, but not your app ('not_authorized')
    // 3. Not logged into Facebook and can't tell if they are logged into
    //    your app or not.
    //
    // These three cases are handled in the callback function.

    FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
    });

};

// Load the SDK asynchronously
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

// Here we run a very simple test of the Graph API after login is
// successful.  See statusChangeCallback() for when this call is made.
function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
        console.log('Successful login for: ' + response.name);
        document.getElementById('status').innerHTML =
            'Thanks for logging in, ' + response.name + '!';
    });
}



/*
	Funcion que Carga la Foto
 */
function fileSelected() {
	var img = "";
	var input = $('#push_file')[0];
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			img = e.target.result;
			$('#img-profile').attr("src",img);
			console.log(img);
		}
		
		reader.readAsDataURL(input.files[0]);
	}
	
}

function uploadFile() {
	var fd = new FormData();
	var count = $('#push_file')[0].files['length'];
	for (var index = 0; index < count; index ++) {
		var file = $('#push_file')[0].files[index];
		fd.append('myFile', file);
	}
	var xhr = new XMLHttpRequest();
	xhr.upload.addEventListener("progress", uploadProgress, false);
	xhr.addEventListener("load", uploadComplete, false);
	xhr.addEventListener("error", uploadFailed, false);
	xhr.addEventListener("abort", uploadCanceled, false);
	xhr.open("POST", "savetofile.php");
	xhr.send(fd);
}


/**FUNCIONES QUE VALIDAN LOS CAMPOS*/

function resaltarErr(obj) {
	$(obj).css("border", "2px solid red");	
	$(obj).focus();
}

function resaltarBien(obj) {
	$(obj).css("border", "2px solid #F0AD4E");
}

function valFecha(value) {
	return (/^\d{2}\/\d{2}\/\d{4}$/.test(value));
}

function valCP(value) {
	return (/(^([0-9]{5,5})|^)$/.test(value));
}

function valTelefono(value) {
	return ((/[0-9]{9}/).test(value));
}

function valEMail(value) {
	return (/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/.test(value))
}

function valText(value) {
	if (value.length < 0) {
		return 1;
	} else if (/^\s+$/.test(value)) {
		return 2;
	} else if (!isNaN(value)) {
		return 3;
	};
	return 0;
}

   