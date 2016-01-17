var onlineStatus = true;
var timeout;
var setOnlineStatus = function(status){
    if(status != onlineStatus){
        //Status changed

        var id = status ? 'network_online' : 'network_offline';

        //If there's still a status message, we'll remove it so there aren't two opposing messages at the same time!
        $('.network-flash-message').remove();

        var text = status ? 'Du ser ut att vara online igen!' : "Det verkar som att du är offline</p><p>Det betyder att du kan se innehåll du tidigare kollat på, men nytt innehåll är inte tillgängligt";

        $('body').prepend('<div id="' + id + '" class="network-flash-message"><p class="title">' + text + '</p><p class="discrete">(Klicka på meddelandet för att dölja det)</p></div>');

        //We'll offset the real status change since cached data on load will trigger "online". The offset is sufficient to adjust this.
        setTimeout(function(){
            onlineStatus = status;
        }, 100);
    }
};

$(document).ready(function() {
    $('body').on('click', '.network-flash-message', function () {
        $(this).remove();
    });


    //make sure that Service Workers are supported.
    if (navigator.serviceWorker) {
        navigator.serviceWorker.register('ServiceWorker', {scope: './'})
            .then(function (registration) {
                console.log(registration);
            }).catch(function (e) {
            console.error(e);
        });

        navigator.serviceWorker.addEventListener('message', function (event) {
            var online = event.data.online;
            setOnlineStatus(online);
        });

    } else {
        console.log('Service Worker is not supported in this browser.');
    }
});