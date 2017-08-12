<?php
//Include GP config file && User class
include_once 'gpConfig.php';
include_once 'User.php';

if(isset($_GET['code'])){
	$gClient->authenticate($_GET['code']);
	$_SESSION['token'] = $gClient->getAccessToken();
	header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
	$gClient->setAccessToken($_SESSION['token']);
}

if ($gClient->getAccessToken()) {
	//Get user profile data from google
	$gpUserProfile = $google_oauthV2->userinfo->get();
	
	//Initialize User class
	$user = new User();
	
	//Insert or update user data to the database
    $gpUserData = array(
        'oauth_provider'=> 'google',
        'oauth_uid'     => $gpUserProfile['id'],
        'first_name'    => $gpUserProfile['given_name'],
        'last_name'     => $gpUserProfile['family_name'],
        'email'         => $gpUserProfile['email'],
        'gender'        => "m",
        'locale'        => $gpUserProfile['locale'],
        'picture'       => $gpUserProfile['picture'],
        'link'          => $gpUserProfile['link']
    );
    $userData = $user->checkUser($gpUserData);
	
	//Storing user data into session
	$_SESSION['userData'] = $userData;
	
	//Render facebook profile data
    if(!empty($userData)){
        $output = '<h1>Google+ Profile Details </h1>';
        $output .= '<img src="'.$userData['picture'].'" width="300" height="220">';
        $output .= '<br/>Google ID : ' . $userData['oauth_uid'];
        $output .= '<br/>Name : ' . $userData['first_name'].' '.$userData['last_name'];
        $output .= '<br/>Email : ' . $userData['email'];
        $output .= '<br/>Gender : ' . $userData['gender'];
        $output .= '<br/>Locale : ' . $userData['locale'];
        $output .= '<br/>Logged in with : Google';
        $output .= '<br/><a href="'.$userData['link'].'" target="_blank">Click to Visit Google+ Page</a>';
        $output .= '<br/>Logout from <a href="logout.php">Google</a>';
        $loginMessage = "Welcome " .$userData['first_name'];

        
    }else{
        $output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
    }
} else {
	$authUrl = $gClient->createAuthUrl();
    $loginMessage = '<a href="'.filter_var($authUrl, FILTER_SANITIZE_URL).'">Login</a>';

}


if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $conn = new mysqli('localhost', 'id2531118_therussianjig', 'Runi5V6Kg1GXUgoQYf1W', 'id2531118_civichackathon');
    if ($conn->connect_errno) {
        printf("Connect failed: %s\n", $conn->connect_error);
        exit();
    }


    $lat = $_POST['lat'];
    $lon = $_POST['lon'];

    $details = $_POST['details'];


    $query = "INSERT INTO roadIssue ('Lat', 'Lon', 'userID'), Values (" . $lat. ",".$lon."," . $userData['oauth_uid']. ")";
    $conn->query($query);
    echo "Thank you! Your issue at: ". $lat. " / " .$lon. "has been recorded";
}

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Peoria New Business Site</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <style>
            /* Remove the navbar's default margin-bottom and rounded borders */

            .navbar {
                margin-bottom: 0;
                border-radius: 0;
            }
            /* Set height of the grid so .sidenav can be 100% (adjust as needed) */

            .row.content {
                height: auto;
            }
            /* Set gray background color and 100% height */

            .sidenav {
                padding-top: 20px;
                background-color: #f1f1f1;
                height: 100%;
            }
            /* Set black background color, white text and some padding */

            footer {
                background-color: #555;
                color: white;
                padding: 15px;
            }
            /* On small screens, set height to 'auto' for sidenav and grid */

            @media screen and (max-width: 767px) {
                .sidenav {
                    height: auto;
                    padding: 15px;
                }
                .row.content {
                    height: auto;
                }
            }
        </style>
    </head>
    <!---

-->

    <body>
        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example-navbar-collapse">
                    <span class = "sr-only">Toggle navigation</span>
                    <span class = "icon-bar"></span>
                    <span class = "icon-bar"></span>
                    <span class = "icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Peoria Business</a>
                </div>
                <div class="collapse navbar-collapse" id="example-navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="home.php">Planning</a></li>
                        <li class="active"><a href="participate.php">Participate</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Relevent Links <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Ameren</a></li>
                                <li><a href="#">PDC</a></li>
                                <li><a href="#">Construction people</a></li>

                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li>

                                <li class="divider"></li>
                                <li><a href="#">One more separated link</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> <?php echo $loginMessage; ?></a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid text-center">
            <div class="row content">
                <div class="col-sm-10 text-left">
                    <hr>
                    <h4>Filter Construction by Date:</h4>
                    <div class="embed-responsive embed-responsive-16by9">
                        <div class="embed-responsive-item" id="map"></div>
                    </div>
                    <form method ="post" enctype="multipart/form-data">
<div class="form-group">

<INPUT TYPE = "Text" VALUE ="12"        name="lat" id = "lat">
<INPUT TYPE = "Text" VALUE ="13"        name="lon" id = "lon">
<textarea class="form-control" rows="5" name="details" id="details"></textarea>

</div>

<button type="submit" class="btn btn-default">Submit</button>
</form>

                </div>
            </div>
        </div>
        <script>
      function initMap() {
        var uluru = {lat: -25.363, lng: 131.044};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 4,
          center: uluru
        });
        google.maps.event.addListener(map, 'click', function(event) {
            //alert("Latitude: " + event.latLng.lat() + " " + ", longitude: " + event.latLng.lng());
            
            var Lat = event.latLng.lat();
            var Lon = event.latLng.lng();

            document.getElementById('lat').value = Lat;
            document.getElementById('lon').value = Lon;
          });
      }


    </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJ9Mp4q9ApzFom7zGpCplPzoVnm6t88MY&libraries=places&callback=initMap"
    async defer>
  </script>

    </body>