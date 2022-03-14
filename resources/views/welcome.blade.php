
<!DOCTYPE html>
<html>
  <head>
    <title>Simple Markers</title>
    <style>
      body {font-family: Arial, Helvetica, sans-serif;}
    </style>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css"
    />
  </head>
  <body>
    <div id="map" style="height: 100%; width: 100%; position: absolute; top: 0px; left: 0px; background-color: rgb(229, 227, 223);"></div>
    
  </body>
</html>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>

 <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
 <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADc2i-pCscNaRMzma-1VxdGDoQE1AWu0I&callback=initMap&v=weekly&channel=2"
      async
    ></script>
    <script>
      // prompted by your browser. If you see the error "The Geolocation service
      // failed.", it means you probably did not give permission for the browser to
      // locate you.
      let map, infoWindow;
      debugger
      function initMap() {
        function clickmarker(chatId,caption){
            $.ajax({
              type: 'GET', //THIS NEEDS TO BE GET
              url: '/images/' + chatId  ,
              success: function (data) {
                var gallery = []
                  for(let img of data){
                    gallery.push({
                      src: img,
                      thumb: img,
                      caption
                    })
                  }
                
              debugger
              Fancybox.show(gallery);
                  
              },
              error: function() { 
                  console.log(data);
              }
          })
        }
        map = new google.maps.Map(document.getElementById("map"), {
          center: { lat: -34.397, lng: 150.644 },
          zoom: 6,
        });
        infoWindow = new google.maps.InfoWindow();

          // Try HTML5 geolocation.
          if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
              (position) => {
                const pos = {
                  lat: position.coords.latitude,
                  lng: position.coords.longitude,
                };

                infoWindow.setPosition(pos);
                // infoWindow.open(map);
                map.setCenter(pos);
                var marker
                <?php
                    foreach($points as $point){ ?>
                        marker  = new google.maps.Marker({
                        position: {
                          lat: <?php $lat = $point['lat'];  echo "$lat"; ?>,
                          lng: <?php $lng = $point['lng']; echo "$lng "; ?>,
                        },
                        map,
                        title: <?php $comment = $point['comment']; echo "'$comment'"; ?>,
                      });

                      
                      marker.addListener("click", function(e){
                        debugger
                        map.setCenter({
                          lat: <?php $lat = $point['lat'];  echo "$lat"; ?>,
                          lng: <?php $lng = $point['lng']; echo "$lng "; ?>,
                        });
                        map.setZoom(12)
                        clickmarker(<?php $chatId = $point['chatId']; echo "'$chatId'"; ?>,<?php $comment = $point['comment']; echo "'$comment'"; ?>)
                      });                    
                      
                  <?php }
                ?>
              },
              () => {
                handleLocationError(true, infoWindow, map.getCenter());
              }
            );
          } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, map.getCenter());
          }
      }

      function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(
          browserHasGeolocation
            ? "Error: The Geolocation service failed."
            : "Error: Your browser doesn't support geolocation."
        );
        infoWindow.open(map);
      }

    </script>

