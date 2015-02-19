var APP = APP || {};
APP.Adroid = {
    _pushNotification : null,
    setUp : function() {
        this.bindEvents();
    },
    bindEvents : function() {
        document.addEventListener('deviceready', APP.Adroid.onDeviceReady,
                false);
    },
    onDeviceReady : function() {
        APP.Adroid.receivedEvent('deviceready');
       
    },
   
    receivedEvent : function(id) {

        // appPG.getCurrentLang();
        // getCurrentPosition();

        console.log('Saudenacopa: receivedEvent');
        // cria o referencia do plugin do push
        this._pushNotification = window.plugins.pushNotification;
        this.setupPush();

        console.log('Received Event: ' + id);
    },
    setupPush : function() {
        console.log('Saudenacopa: setupPush');
        this._pushNotification.register(this.successHandler, this.errorHandler,
                {
                    "senderID" : "559878614256",
                    "ecb" : "onNotificationGCM"
                });
    },
    successHandler : function(result) {
    },
    errorHandler : function(error) {

    }
}

// Android
function onNotificationGCM(e) {
    switch (e.event) {
    case 'registered':
        if (e.regid.length > 0) {
            console.log("regID = " + e.regid);
            // enviar o id e idioma para o servidor
            $.post("http://www.gamfig.com/saudenacopa/inseregcm.php", {
                id : e.regid,
                idi : APP.Area.Apresentacao.Idioma.idiomaGravado()
            }).done(function(data) {

            });
        }
        break;

    case 'message':
        // if this flag is set, this notification happened while we were in the
        // foreground.
        // you might want to play a sound to get the user's attention, throw up
        // a dialog, etc.
        if (e.foreground) {

        } else { // otherwise we were launched because the user touched a
                    // notification in the notification tray.
            if (e.coldstart) {
                // COLDSTART NOTIFICATION
            } else {
                // BACKGROUND NOTIFICATION
            }
        }

        // e.payload.message
        // e.payload.msgcnt 
        break;

    case 'error':
        
        break;

    default:
        
        break;
    }
}