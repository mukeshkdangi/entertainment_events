<!DOCTYPE html><html><head>
    <style type="text/css">
        .bold {
                font-weight: bolder;
                margin-top: 6px;
        }
        .heading{
            font-weight: bolder;
        }
        .buyTktURL{
            text-decoration: none;
            color: black;
        }
        .eventHeadingTitle{
            margin: auto auto;
            width: 500px;
            font-weight: bolder;
        }
        .floatLeft {
            float: left;
        }
        .marginTop {
                margin-top: 5px;
        }
    </style>
<script type="text/javascript">  
    var lat, lon, geoPoint;
    function updateParams(){
            getCurrentLoc();
    }

    function getCurrentLoc(){
       
       var locationDescription =  document.eventForm.locationDescription.value;
       <?php 
        
        $option ='KZFzniwnSyZfZ7v7nE';
          if (isset($_POST["category"])) {
            echo $_POST["category"];
                $option = $_POST["category"]; 
            }
        ?>

        if(!locationDescription){
            var httpReq = new XMLHttpRequest();
            httpReq.open('GET','http://ip-api.com/json',false);
            httpReq.send();
            var json = JSON.parse(httpReq.responseText);
                lat = json.lat;
                lon = json.lon;
                console.log(json);
        } else {
            httpReq = new XMLHttpRequest();
            httpReq.open('GET','https://maps.googleapis.com/maps/api/geocode/json?address='+locationDescription+'&key=',false);
            httpReq.send();
            var json = JSON.parse(httpReq.responseText);
                lat = json.results[0].geometry.location.lat;
                lon = json.results[0].geometry.location.lng;

        }
        document.eventForm.lat.value= lat;
        document.eventForm.lon.value= lon;  
    }


</script>
</head>

<body>
     <?php 
        
      ?>
<form name ="eventForm"  method="post" style="margin: 0 auto;width: 618px;border: 3px solid #b9b3b3;background: whitesmoke;margin-top: 45px;">
  <div style = "text-align: center;font-size: xx-large;font-family: inherit;
        font-style: italic;">
        <div>Event Search</div>
        <hr/>
        
    </div>

    <div class ="bold">Keyword: <input type="text" name="keyword" required
             value='<?php echo isset($_POST["keyword"]) ? $_POST["keyword"] : "" ?>'>
            <br>
    </div>

    <div class ="bold">Category: <select name ="category">
            <option value="KZFzniwnSyZfZ7v7nE"  >default</option>
            <option value="KZFzniwnSyZfZ7v7nJ"  
            <?php if(isset($option) && $option=='KZFzniwnSyZfZ7v7nJ') echo 'selected' ?> >Music</option>
            
            <option value="KZFzniwnSyZfZ7v7nE"
            <?php if(isset($option) && $option=='KZFzniwnSyZfZ7v7nE') echo 'selected'?> >Sports</option>
            
            <option value="KZFzniwnSyZfZ7v7na"
            <?php if(isset($option) && $option=='KZFzniwnSyZfZ7v7na') echo 'selected' ?> >Arts & Theatre</option>
            <option value="KZFzniwnSyZfZ7v7nn"
            <?php if(isset($option) && $option=='KZFzniwnSyZfZ7v7nn') echo 'selected' ?> >Film</option>
            <option value="KZFzniwnSyZfZ7v7n1"
            <?php if(isset($option) && $option=='KZFzniwnSyZfZ7v7n1') echo 'selected' ?> >Miscellaneous</option>
        </select>
    </div>


    <div class ="bold">Distance(miles):
    
    <input type="number" name="radius" value="<?php echo isset($_POST["radius"]) ? $_POST["radius"] : '' ?>" required>
    
    <input type="radio" name="distanceFlag"  value="Yes"  <?php 
    if(isset($_POST['distanceFlag']) && $_POST['distanceFlag'] == 'Yes')  echo ' checked="checked"';?> checked>Here<br>
    <input type="radio" name="distanceFlag" value="No" <?php 
    if(isset($_POST['distanceFlag']) && $_POST['distanceFlag'] == 'No')  echo ' checked="checked"';?> style="margin-left: 252px;">
    

    <input type="text" name="locationDescription" placeholder="location" 
    value='<?php echo isset($_POST["locationDescription"]) ? $_POST["locationDescription"] : '' ?>'>​​​​​​​​​​​​​​​​​​​​​​​​​​
    <br><br>
    
    <input type="hidden" name="lat" value="<?php echo isset($_POST["lat"]) ? $_POST["lat"] : '' ?>">
    <input type="hidden" name="lon" value="<?php echo isset($_POST["lon"]) ? $_POST["lon"] : '' ?>">
     <input type="hidden" name="geoPoint" value="<?php echo isset($_POST["geoPoint"]) ? $_POST["geoPoint"] : '' ?>">
     </div>
    
    <input type="submit" name ="submit" value="Search" onclick="updateParams()">
    <button type="reset" value="Reset">Reset</button>

 
</form>
</div>




    <?php 
            
            include_once("geoHash.php");
        ?>

  <?php

  if(isset($_GET['eventId'])){
    
    $eventId = $_GET['eventId'];

    $event_url = 'https://app.ticketmaster.com/discovery/v2/events/'.$eventId.'?apikey=';
    
    echo $event_url;

    $event_detail_response = file_get_contents($event_url);
    $event_response = json_decode($event_detail_response,true);
    
    $name = $event_response['name'];
    $date = $event_response['dates']['start']['localDate'];
    $time = $event_response['dates']['start']['localTime'];
    $dateTime  = $date.' '.$time;

    $status = $event_response['dates']['status']['code'];
    $genre = $event_response['classifications'][0]['genre']['name'];
    $subGenre = $event_response['classifications'][0]['subGenre']['name'];
    $type = $event_response['classifications'][0]['type']['name'];
    $subType = $event_response['classifications'][0]['subType']['name'];
    $segment = $event_response['classifications'][0]['segment']['name'];

    $totalGenre = $subGenre.' | '.$genre.' | '.$segment.' | '.$subType.' | '.$type;

     
    $seatmap = (isset($event_response['seatmap']) && isset($event_response['seatmap']['staticUrl'])) ?$event_response['seatmap']['staticUrl'] : '' ;
    $minPrice = $event_response['priceRanges'][0]['min'];
    $maxPrice = $event_response['priceRanges'][0]['max'];
    $currencyCode = $event_response['priceRanges'][0]['currency'];

    $priceDisplay = $minPrice .' - '.$maxPrice.' '.$currencyCode;

    $buyTicketAt = $event_response['url'];

    $team1 = $event_response['_embedded']['attractions'][0]['name'];
    $team2 = $event_response['_embedded']['attractions'][1]['name'];

    $venue = $event_response['_embedded']['venues'][0]['name'];

    $teamName = $team1 .' | '.$team2;

    echo '<div style="margin-left: 200px;height:100pc;"> 
        <div class="eventHeadingTitle">'.$name.'</div>

    <div class="floatLeft"> 
            <div class = "heading marginTop">Date</div>
            <div class = "value">'.$dateTime.'</div>

            <div class = "heading marginTop">Artist/Team</div>
            <div class = "value">'.$teamName.'</div>

            <div class = "heading marginTop">Venue</div>
            <div class = "value">'.$venue.'</div>

            <div class = "heading marginTop">Genres</div>
            <div class = "value">'.$totalGenre.'</div>

            <div class = "heading marginTop">Price Range</div>
            <div class = "value">'.$priceDisplay.'</div>

            <div class = "heading marginTop">Ticket Status </div>
            <div class = "value">'.$status.'</div>

             <div class = "heading marginTop buyTktURL">Buy Ticket At </div>
            <div class = "value buyTktURL"><a href='.$buyTicketAt.' target="_blank" >Ticketmaster</a></div>
        </div>
        
        <div class = "value"><img src='.$seatmap.' height= "300";></div>

    </div>';

  }

  if(isset($_GET['venueId'])){
    
    $venueId = $_GET['venueId'];

    $venue_url = 'https://app.ticketmaster.com/discovery/v2/venues/'.$venueId.'?apikey=';
    
    echo $venue_url;

     $venue_detail_response = file_get_contents($venue_url);
     $venue_response = json_decode($venue_detail_response,true);

     $lat = $venue_response['location']['latitude'];
     $lon = $venue_response['location']['longitude'];

     $addes1 = isset($venue_response['address']['line1']) ? $venue_response['address']['line1'] :'' ; 
     $addes2 = isset($venue_response['address']['line2']) ? $venue_response['address']['line2'] : ''; 
     
     $postalCode = $venue_response['postalCode'];
     $city = $venue_response['city']['name'];
     $stateCode  = $venue_response['state']['stateCode'];
     
     $upcomingEnventsURL = isset($venue_response['url']) ? $venue_response['url'] :'';
     $name = $venue_response['name'];
     $upComingEvents = $upcomingEnventsURL.''.$name;

     echo '
     <table border="2" style ="border-collapse:collapse;margin:0 auto;">
     <tr>
        <th>Name</th>
        <td>'.$name.'</td>
     </tr>
     <tr>
        <th>Map</th>
        <td>'.$lat.' '.$lon.'</td>
     </tr>

     <tr>
        <th>Address</th>
        <td>'.$addes1.' '.$addes2.'</td>
     </tr>
     <tr>
        <th>Name</th>
        <td>'.$city.', '.$stateCode.'</td>
     </tr>

      <tr>
        <th>Postal Code</th>
        <td>'.$postalCode.'</td>
     </tr>
      <tr>
        <th>Upcoming Events</th>
        <td><a class ="buyTktURL" href='.$upcomingEnventsURL.' target="_blank">'.$name.' Tickets </a></td>
     </tr>

     </table>';


   }

  if(isset($_POST['submit'])){
           
            echo '<script type="text/javascript">',
            'updateParams();',
            '</script>';
            
            $category =   $_POST['category'];
            $keyword = $_POST['keyword'];
            $radius = $_POST['radius'];
          //$geoPoint =  $_POST['geoPoint'];
            $lat =             $_POST['lat'];
            $lon =             $_POST['lon'];
            $geoPoint = encode($lat,$lon);

          
          try{
           
           $url = 'https://app.ticketmaster.com/discovery/v2/events.json?apikey=&keyword='.$keyword.'&segmentId='.$category.'&radius='.$radius.'&geoPoint='.$geoPoint.'&unit=miles';
                echo $url;
              $tkt_master_response = file_get_contents($url);
                
                }catch (Exception $e){
                    echo "Ticket master API error.";
                }
                $tktResponse = json_decode($tkt_master_response,true);
                if(!isset($tktResponse['_embedded']) && !isset($tktResponse['_embedded']['events'])){
                    echo '<div style="background-color:grey;height:auto;">No Record Found</div>';
                    return;
                }
               $events= $tktResponse['_embedded']['events'];
                echo '<table border="2" style ="border-collapse:collapse;margin:0 auto;"><th>Date</th><th>Icon</th><th>Event</th><th>Genre</th><th>Venue</th>';
                foreach ($events as $event) {
                    $date = $event['dates']['start']['localDate'];
                    $name = $event['name'];
                    $id = $event['id'];
                    $venueId = $event['_embedded']['venues'][0]['id'];
                    $type = $event['classifications'][0]['segment']['name'];
                    $venue1 = isset($event['_embedded']['venues'][0]['address']['line1']) ? $event['_embedded']['venues'][0]['address']['line1'] : '';
                    $venue2 = isset($event['_embedded']['venues'][0]['address']['line2']) ? $event['_embedded']['venues'][0]['address']['line2'] : '';

                    $icon = $event['images'][0]['url'];

                    echo '<tr><td>'.$date.'</td>
                    <td><img width="40" height="40" src='.$icon.'></td>
                    <td class ="buyTktURL"><a class ="buyTktURL" href="?eventId='.$id.' ">'.$name.'
                        
                      </a>
                    </td>

                    <td>'.$type.'</td>
                    <td><a class ="buyTktURL" href="?venueId='.$venueId.' ">'.$venue1.' '.$venue2.'
                    </a>
                    </td></tr>';
                }
                echo '</table>';

        }


    ?>



</body>
</html>
