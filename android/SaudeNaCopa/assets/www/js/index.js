/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
var pushNotification;
var idioma;

var appPG = {
    // Application Constructor
    initialize : function() {
        this.bindEvents();
    },
    // Bind Event Listeners
    //
    // Bind any events that are required on startup. Common events are:
    // 'load', 'deviceready', 'offline', and 'online'.
    bindEvents : function() {
        document.addEventListener('deviceready', this.onDeviceReady, false);
    },
    // deviceready Event Handler
    //
    // The scope of 'this' is the event. In order to call the 'receivedEvent'
    // function, we must explicity call 'app.receivedEvent(...);'
    onDeviceReady : function() {
        appPG.receivedEvent('deviceready');
    },
    // Update DOM on a Received Event
    receivedEvent : function(id) {

       // appPG.getCurrentLang();
       // getCurrentPosition();

        // get plugin reference
        pushNotification = window.plugins.pushNotification;
        setupPush();

        console.log('Received Event: ' + id);
    },
    getCurrentLang : function() {
        navigator.globalization.getPreferredLanguage(function(language) {
           // $("#cur_idioma").html(language.value);
            idioma=language.value;
        }, function() {
            alert('Error getting language\n');
        });
    }
};

var setupPush = function() {
    
        pushNotification.register(successHandler, errorHandler, {
            "senderID" : "559878614256",
            "ecb" : "onNotificationGCM"
        });
 
}
//result contains any message sent from the plugin call
function successHandler (result) {
   // alert('result = ' + result);
}
//result contains any error description text returned from the plugin call
function errorHandler (error) {
    alert('error = ' + error);
}

//Android
function onNotificationGCM(e) {
  //  $("#app-status-ul").append('<li>EVENT -> RECEIVED:' + e.event + '</li>');

    switch( e.event )
    {
    case 'registered':
        if ( e.regid.length > 0 )
        {
          //  $("#app-status-ul").append('<li>REGISTERED -> REGID:' + e.regid + "</li>");
            // Your GCM push server needs to know the regID before it can push to this device
            // here is where you might want to send it the regID for later use.
            console.log("regID = " + e.regid);
            //enviar o id para o servidor o id e o idioma
            $.post( "http://www.gamfig.com/saudenacopa/inseregcm.php", { id: e.regid, idi: idioma } );
        }
    break;

    case 'message':
        // if this flag is set, this notification happened while we were in the foreground.
        // you might want to play a sound to get the user's attention, throw up a dialog, etc.
        if ( e.foreground )
        {
          //  $("#app-status-ul").append('<li>--INLINE NOTIFICATION--' + '</li>');

            // if the notification contains a soundname, play it.
          //  var my_media = new Media("/android_asset/www/"+e.soundname);
           // my_media.play();
        }
        else
        {  // otherwise we were launched because the user touched a notification in the notification tray.
            if ( e.coldstart )
            {
                $("#app-status-ul").append('<li>--COLDSTART NOTIFICATION--' + '</li>');
            }
            else
            {
                $("#app-status-ul").append('<li>--BACKGROUND NOTIFICATION--' + '</li>');
            }
        }

     //   $("#app-status-ul").append('<li>MESSAGE -> MSG: ' + e.payload.message + '</li>');
      //  $("#app-status-ul").append('<li>MESSAGE -> MSGCNT: ' + e.payload.msgcnt + '</li>');
    break;

    case 'error':
        $("#app-status-ul").append('<li>ERROR -> MSG:' + e.msg + '</li>');
    break;

    default:
        $("#app-status-ul").append('<li>EVENT -> Unknown, an event was received and we do not know what it is</li>');
    break;
  }
}

var getCurrentPosition = function() {
    var success = function(pos) {
        var text = "<div>Latitude: " + pos.coords.latitude + "<br/>"
                + "Longitude: " + pos.coords.longitude + "<br/>" + "Accuracy: "
                + pos.coords.accuracy + "m<br/>" + "</div>";
        $("#cur_position").html(text);
        console.log(text);

        var mapwidth = parseInt($('#map').css("width"), 10); // remove 'px'
        // from width
        // value
        var mapheight = parseInt($('#map').css("height"), 10);
        $('#map').css('visibility', 'visible');
        $('#map').attr(
                'src',
                "http://maps.googleapis.com/maps/api/staticmap?center="
                        + pos.coords.latitude + "," + pos.coords.longitude
                        + "&zoom=13&size=20" + mapwidth + "x20" + mapheight
                        + "&maptype=roadmap&markers=color:green%7C"
                        + pos.coords.latitude + "," + pos.coords.longitude
                        + "&sensor=false");

    };
    var fail = function(error) {
        $("#cur_position").html("Error getting geolocation: " + error.code);
        console.log("Error getting geolocation: code=" + error.code
                + " message=" + error.message);
    };

    $('#map').css('visibility', 'hidden');
    $("#cur_position").html("Getting geolocation . . .");
    console.log("Getting geolocation . . .");
    navigator.geolocation.getCurrentPosition(success, fail);
};