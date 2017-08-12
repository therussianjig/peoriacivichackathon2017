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
          <li class="active"><a href="home.php">Planning</a></li>
          <li><a href="participate.php">Participate</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
               Relevent Links
              <b class="caret"></b>
              </a>
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
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th><label for="startYear">Start Year:</label></th>
                <th><label for="endYear">End Year:</label></th>
                <th><label for="startMonth">Start Month:</label></th>
                <th><label for="endMonth">End Month:</label></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>            
                  <select class="form-control" id="startYear">
                    <option>2016</option>
                    <option>2017</option>
                    <option>2018</option>
                    <option>2019</option>
                    <option>2020</option>
                  </select>
                </td>
                <td>
                  <select class="form-control" id="endYear">
                    <option>2016</option>
                    <option>2017</option>
                    <option>2018</option>
                    <option>2019</option>
                    <option>2020</option>
                  </select>
                </td>
                <td>
                  <select class="form-control" id="startMonth">
                    <option>Jan</option>
                    <option>Feb</option>
                    <option>Mar</option>
                    <option>April</option>
                    <option>May</option>
                    <option>June</option>
                    <option>July</option>
                    <option>Aug</option>
                    <option>Sept</option>
                    <option>Oct</option>
                    <option>Nov</option>
                    <option>Dec</option>
                  </select>
                </td>
                <td>
                    <select class="form-control" id="endMonth">
                      <option>Jan</option>
                      <option>Feb</option>
                      <option>Mar</option>
                      <option>April</option>
                      <option>May</option>
                      <option>June</option>
                      <option>July</option>
                      <option>Aug</option>
                      <option>Sept</option>
                      <option>Oct</option>
                      <option>Nov</option>
                      <option>Dec</option>
                    </select>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <button onclick="filterCallback()">Filter By Year</button>
        <hr>
        <div class="input-group">
          <span class="input-group-addon">Address</span> <input id="msg_1" type="text" class="form-control">
        </div>
        <div class="btn-group">
          <!-- I Couldnt get this button to work -->
          <!-- <button type="button" class="btn btn-primary" onclick="addPin()">Add Pin</button>  -->
          <button type="button" class="btn btn-info" onclick="getCurrentLocation()">Current Location</button>
          <button type="button" class="btn btn-danger"  onclick="clearPins()">Clear Pins</button>    
        </div>
    </div>
    <div class="col-sm-2 sidenav">
      <hr>
    </div>
  </div>
  <hr>
  <div class = "row">
    <div class="col-sm-10">
      <div class="embed-responsive embed-responsive-16by9">
        <div class="embed-responsive-item" id="map"></div>
      </div>
    </div>
    <div class="col-sm-2 sidenav">
        <div class="checkbox text-left"><label><input type="checkbox" id="Construction" value="">Construction</label></div>
        <div class="checkbox text-left"><label><input type="checkbox" id="BusRoutes" value="">Bus Routes</label></div>
        <div class="checkbox text-left"><label><input type="checkbox" id="BikeRoutes" value="">Bike Routes</label></div>
    </div>
</div>
  <footer class="container-fluid text-center">
    <p>Footer Text</p>
  </footer>

  <script>
  var map;
  var currentPosition;
  var markers = [];

    function filterCallback()
    {
          var name;
          var year; 
          startYear = document.getElementById('startYear').value;
          startMonth = document.getElementById('startMonth').value;
          endYear = document.getElementById('endYear').value;
          endMonth = document.getElementById('endMonth').value;

          map.data.forEach(function (feature) {
            // name = feature.getProperty('TypeId');
            // year = feature.getProperty('Year');
            // //alert(name);
            // if ((name === 'Pavement2017') || (name === 'ResidentialRecon')
            // || (name === 'SidewalkConstruction')
            // || (name === 'ArterialRecon')) {
              
            //   if ((year >= startYear) && (year <= endYear))
            //   {

            //   }

            //   else{
            //     console.log(year + " ");
                map.data.remove(feature);
              // }
            // }
          });
    }

    function filterByYear()
      {
        map.data.loadGeoJson('jsondata/ArterialRecon.geojson', null,
        map.data.loadGeoJson('jsondata/ResidentialRecon.geojson', null,
        map.data.loadGeoJson('jsondata/Pavement2017.geojson', null,
        map.data.loadGeoJson('jsondata/SidewalkConstruction.geojson', null,filterCallback())))
        );

      
      }


    function getCurrentLocation()
    {
        // Try HTML5 geolocation.
        if (navigator.geolocation) 
        {
          navigator.geolocation.getCurrentPosition(function(position) 
          {
            currentPosition = {lat: position.coords.latitude,lng: position.coords.longitude};
          }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
          });

          var geocoder = new google.maps.Geocoder;
          geocoder.geocode({'location': currentPosition}, function(results, status) {
            if (status === 'OK') {
              if (results[0]) {
                document.getElementById('msg_1').value = results[0].formatted_address;
              } else {
                window.alert('No results found');
              }
            } else {
              window.alert('Geocoder failed due to: ' + status);
            }
          });

        } 
        else 
        {
          console.log("Can't find location");
        }
  }

    
    function initAutocomplete() 
    {
      var geocoder = new google.maps.Geocoder;

      map = new google.maps.Map(document.getElementById('map'), 
        {
          center: {
            lat: 40.693649,
            lng: -89.588986
          },
          zoom: 13,
          mapTypeId: 'roadmap'
        }
        );

        // Try HTML5 geolocation.
        if (navigator.geolocation) 
        {
          navigator.geolocation.getCurrentPosition(
          function(position) 
          {
            currentPosition = {lat: position.coords.latitude,lng: position.coords.longitude};
            map.setCenter(currentPosition);

            var geocoder = new google.maps.Geocoder;
            geocoder.geocode({'location': currentPosition}, 
              function(results, status) 
              {
                if (status === 'OK') {
                  if (results[0]) {
                    document.getElementById('msg_1').placeholder = results[0].formatted_address;
                  } else {
                    window.alert('No results found');
                  }
                } else {
                  window.alert('Geocoder failed due to: ' + status);
                }
             });
          }, 
          function(){});
        } 
        else 
        {
          console.log("Can't find location");
        }

      // Create the search box and link it to the UI element.
      var input = document.getElementById('msg_1');
      var searchBox = new google.maps.places.SearchBox(input);
      //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

      // Bias the SearchBox results towards current map's viewport.
      map.addListener('bounds_changed', function () {
        searchBox.setBounds(map.getBounds());
      });

      // Listen for the event fired when the user selects a prediction and retrieve
      // more details for that place.
      searchBox.addListener('places_changed', function () {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
          return;
        }

        

        // For each place, get the icon, name and location.
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function (place) {
          if (!place.geometry) {
            console.log("Returned place contains no geometry");
            return;
          }
          var icon = {
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(25, 25)
          };

           var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
          // Create a marker for each place.
          markers.push(new google.maps.Marker({
            map: map,
            icon: iconBase + 'library_maps.png',
            title: place.name,
            position: place.geometry.location
          }));

          if (place.geometry.viewport) {
            // Only geocodes have viewport.
            bounds.union(place.geometry.viewport);
          } else {
            bounds.extend(place.geometry.location);
          }
        });
        map.fitBounds(bounds);
      });

//Hover



var infowindow = new google.maps.InfoWindow({
          content: "test",
          pixelOffset: new google.maps.Size(0, -30)
        });

      map.data.addListener('mouseover', function(event) {
        var infoText; 

        map.data.revertStyle();
        

        var anchor = new google.maps.MVCObject();
        anchor.setValues({ //position of the point
            position: event.latLng,
            anchorPoint: new google.maps.Point(0, 0)
        });
        
        if (event.feature.getProperty('TypeId') === 'ArterialRecon')
        {
          infoText = "<p><strong>Construction Type: </strong>" + "Arterial Reconstruction" + "</p>";
          infoText += "<p><strong>Project Street: </strong>" + event.feature.getProperty('ProjStreet') + "</p>";
          infoText += "<p><strong>Start Year: </strong>" + event.feature.getProperty('Year') + "</p>";
          infoText += "<p><strong>Start Month: </strong>" + replaceNullWithUndefined(event.feature.getProperty('EstStart')) + "</p>";
          infoText += "<p><strong>End Date: </strong>" + replaceNullWithUndefined(event.feature.getProperty('EstEnd')) + "</p>";
          map.data.overrideStyle(event.feature, {strokeWeight: 2});
        }

        else if (event.feature.getProperty('TypeId') === 'SidewalkConstruction')
        {
          infoText = "<p><strong>Construction Type: </strong>" + "Sidewalk Reconstruction" + "</p>";
          infoText += "<p><strong>Project Street: </strong>" + event.feature.getProperty('Road') + "</p>";
          map.data.overrideStyle(event.feature, {strokeWeight: 2});
        }
        else if (event.feature.getProperty('TypeId') === 'ResidentialRecon')
        {
          infoText = "<p><strong>Construction Type: </strong>" + "Residential Reconstruction" + "</p>";
          infoText += "<p><strong>Project Street: </strong>" + event.feature.getProperty('ProjStreet') + "</p>";
          infoText += "<p><strong>Start Year: </strong>" + event.feature.getProperty('Year') + "</p>";
          infoText += "<p><strong>Start Month: </strong>" + replaceNullWithUndefined(event.feature.getProperty('EstStart')) + "</p>";
          infoText += "<p><strong>End Date: </strong>" + replaceNullWithUndefined(event.feature.getProperty('EstEnd')) + "</p>";
          map.data.overrideStyle(event.feature, {strokeWeight: 2});
        }

        else if (event.feature.getProperty('TypeId') === 'Pavement2017')
        {
          infoText = "<p><strong>Construction Type: </strong>" + "Pavement Repair" + "</p>";
          infoText += "<p><strong>Project Street: </strong>" + event.feature.getProperty('BranchName') + "</p>";
          infoText += "<p><strong>Start Year: </strong> 2017 </p>";
          infoText += "<p><strong>Start Month: </strong>" + replaceNullWithUndefined(event.feature.getProperty('EstStart')) + "</p>";
          infoText += "<p><strong>End Date: </strong>" + replaceNullWithUndefined(event.feature.getProperty('EstEnd')) + "</p>";
          map.data.overrideStyle(event.feature, {strokeWeight: 2});
        }

        else if (event.feature.getProperty('TypeId')==='BikeTrails')
        {
          infoText = "<p><strong>Off Road Bike Trail: </strong></p>";
          infoText += "<p><strong>Distance: </strong>" + replaceNullWithUndefined(event.feature.getProperty('LengthMi')) + " Mi</p>";
          map.data.overrideStyle(event.feature, {strokeWeight: 6});
        }

        else if (event.feature.getProperty('TypeId')==='OnRoadBikeTrails')
        {
          infoText = "<p><strong>On Road Bike Trail: </strong>" + event.feature.getProperty('Name')+"</p>";
          map.data.overrideStyle(event.feature, {strokeWeight: 6});
        }

        else if (event.feature.getProperty('TypeId')==='BusRoutes')
        {
          infoText = "<p><strong>Route Name: </strong>" + event.feature.getProperty('RouteName')+"</p>";
          infoText += "<p><strong>Distance: </strong>" + replaceNullWithUndefined(event.feature.getProperty('Distance')) + " Mi</p>";
          map.data.overrideStyle(event.feature, {strokeWeight: 6});
        }


        else if (event.feature.getProperty('TypeId')==='BusStopShelters')
        {
          infoText = "<p><strong>Route Name: </strong>" + event.feature.getProperty('BusRoute')+"</p>";
          infoText += "<p><strong>City: </strong>" + event.feature.getProperty('City') + "</p>";
          infoText += "<p><strong>Has Trash Receptical: </strong>" +  event.feature.getProperty('TrashCan') + "</p>";
          map.data.overrideStyle(event.feature, {strokeWeight: 6});
        }
        infowindow.setContent(infoText);
        infowindow.open(map, anchor);
        });
    



      map.data.setStyle(function (feature) {
        var name = feature.getProperty('TypeId');
        //alert(name);
        if ((name === 'Pavement2017') || (name === 'ResidentialRecon')
          || (name === 'SidewalkConstruction')
          || (name === 'ArterialRecon')) {
          innerColor = 'red';
          strokeColor = 'black';
          weight = 1;
          }
        else if (name === 'BusRoutes')
        {
          weight = 3;
          innerColor = 'black';
          strokeColor = 'blue';
        }

        else if (name ==='BusStopShelters')
        {
          //alert('shelter');
          weight = 6;
          innerColor = 'black';
          strokeColor = 'black';
        }

        else if (name === 'BikeTrails') 
        {
          weight = 3;
          innerColor = 'black'
          strokeColor = 'lime';
        }
        else if (name === 'OnRoadBikeTrails')
        {
          weight = 3;
          innerColor = 'black'
          strokeColor = 'green';
        }

        return {
          fillColor: innerColor,
          strokeWeight: weight,
          strokeColor: strokeColor
        };
      });
    }


      

    $('input#Construction').change(function () {
      if ($('input#Construction').is(':checked')) {
        map.data.loadGeoJson('jsondata/ArterialRecon.geojson');
        map.data.loadGeoJson('jsondata/ResidentialRecon.geojson');
        map.data.loadGeoJson('jsondata/Pavement2017.geojson');
        //map.data.loadGeoJson('jsondata/RampConstruction.geojson');
        map.data.loadGeoJson('jsondata/SidewalkConstruction.geojson');
      } else {
        map.data.forEach(function (feature) {
          var name = feature.getProperty('TypeId');
          //alert(name);
          if ((name === 'Pavement2017') || (name === 'ResidentialRecon')
          || (name === 'SidewalkConstruction')
          || (name === 'ArterialRecon')) {
            map.data.remove(feature);
          }

        });
      }
    });

    $('input#BusRoutes').change(function () {
      if ($('input#BusRoutes').is(':checked')) {
        map.data.loadGeoJson('jsondata/BusRoutes.geojson');
        map.data.loadGeoJson('jsondata/BusStopShelters.geojson');

      } else {
        map.data.forEach(function (feature) {
          var name = feature.getProperty('TypeId');
          if ((name === 'BusRoutes') || (name === 'BusStopShelters')){
            map.data.remove(feature);
          }

        });
      }
    });

    $('input#BikeRoutes').change(function () {
      if ($('input#BikeRoutes').is(':checked')) {
        map.data.loadGeoJson('jsondata/BikeTrails.geojson');
        map.data.loadGeoJson('jsondata/OnRoadBikeTrails.geojson')
        
      } else {
        map.data.forEach(function (feature) {
          var name = feature.getProperty('TypeId');
          //alert(name);
          if ((name === 'BikeTrails') || (name === 'OnRoadBikeTrails')){
            map.data.remove(feature);
          }

        });
      }
    });

    function replaceNullWithUndefined(input)
    {
      if (input === null)
      {
        return "TBD"
      }

      else{
        return input;
      }
    }

    function addPin()
    {
        var input = document.getElementById('msg_1');
        var searchBox = new google.maps.places.SearchBox(input);
        var places = searchBox.getPlaces();

        if (places.length == 0) {
          return;
        }
        // For each place, get the icon, name and location.
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function (place) {
          if (!place.geometry) {
            console.log("Returned place contains no geometry");
            return;
          }
          var icon = {
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(25, 25)
          };

           var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
          // Create a marker for each place.
          markers.push(new google.maps.Marker({
            map: map,
            icon: iconBase + 'library_maps.png',
            title: place.name,
            position: place.geometry.location
          }));

          if (place.geometry.viewport) {
            // Only geocodes have viewport.
            bounds.union(place.geometry.viewport);
          } else {
            bounds.extend(place.geometry.location);
          }
        });
        //map.fitBounds(bounds);
    }

 function clearPins() 
 { 
   markers.forEach(function (marker) 
   {
      marker.setMap(null); 
   });       
  }

  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJ9Mp4q9ApzFom7zGpCplPzoVnm6t88MY&libraries=places&callback=initAutocomplete"
    async defer>
  </script>
</body>

</html>