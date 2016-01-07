importScripts('scripts/cache-polyfill.js');

var CACHE_VERSION = '2016-01-07-15-15';
var CACHE_FILES = [
    './',
    '/scripts/scripts.min.js',
    '/css/styles.min.css',
    '/favicon.png',
    '/offline.html'
];

self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(CACHE_VERSION)
            .then(function (cache) {
                console.log('Opened cache');
                return cache.addAll(CACHE_FILES);
            })
    );
});

self.addEventListener('fetch', function (event) {
    event.respondWith(
        caches.match(event.request).then(function(res){
            if(res){
                console.log("Found match: ", res);
                return res;
            }
            return requestBackend(event);
        })
    )
});

function requestBackend(event){
    var request = event.request.clone();

    return fetch(request).then(function(res){
        console.log("Fetching: ", res);
        //if not a valid response send the error

        if(!res || res.status !== 200 || !(res.type === 'basic' || res.type === 'cors')){
            return res;
        }

        var response = res.clone();

        //Test url of it contains the word "admin", if so, do not cache
        if(response.url.match(/.*(admin).*/)){
            //Do nothing here!
            console.log("Admin content, no cache here: ", response);
        }else{
            caches.open(CACHE_VERSION).then(function(cache){
                cache.put(event.request, response);
            });
        }

        return res;
    }).catch(function() {
        // If both fail, show a generic fallback:
        return caches.match('/offline.html');
    })
}

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function(keys){
            return Promise.all(keys.map(function(key, i){
                if(key !== CACHE_VERSION){
                    return caches.delete(keys[i]);
                }
            }))
        })
    )
});