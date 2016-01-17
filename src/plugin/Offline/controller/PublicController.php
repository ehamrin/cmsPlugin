<?php


namespace plugin\Offline\controller;


class PublicController
{
    public function ServiceWorker(){
        header("Content-Type: text/javascript");

        return <<<JS
importScripts('scripts/cache-polyfill.js');

var CACHE_VERSION = '2016-01-14-18-14';
var CACHE_FILES = [
    './',
    '/scripts/scripts.min.js',
    '/css/styles.min.css',
    '/favicon.png',
    '/offline'
];

self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(CACHE_VERSION)
            .then(function (cache) {
                console.log('[ServiceWorker] Opened cache');
                return cache.addAll(CACHE_FILES);
            }).then(function(){
                console.log('[ServiceWorker] Skip waiting on install');
                return self.skipWaiting();
            })
    )
});


self.addEventListener('fetch', function (event) {
    event.respondWith(
        caches.match(event.request).then(function(res){
            if(res){
                //We found a match, so let's request a newer version for next time!
                requestBackend(event, true);
                return res;
            }
            return requestBackend(event, false);
        })
    )
});

function requestBackend(event, refresh){
    var request = event.request.clone();

    return fetch(request).then(function(res){

        //if not a valid response send the error
        if(!res || res.status !== 200 || !(res.type === 'basic' || res.type === 'cors')){
            if(res.url != ""){
                //Notify user that it appears to be offline
                postMessage({online: false});
            }

            return res;
        }

        //Notify user that it appears to be online
        postMessage({online: true});

        var response = res.clone();

        //Test url of it contains the word "admin", if so, do not cache
        if(response.url.match(/.*(admin).*/)){
            //Do nothing here!
        }else{
            caches.open(CACHE_VERSION).then(function(cache){
                cache.put(event.request, response);
            });
        }

        return res;
    }).catch(function() {
        // If both fail,

        //Notify user that it appears to be offline
        postMessage({online: false});

        if(!refresh){
            //show a generic fallback, but only if user requested text/html and we're not updating cache in background
            if (request.headers.get('accept').includes('text/html')) {
                return caches.match('/offline.html');
            }
        }
    })
}

var postMessage = function(message){
    self.clients.matchAll()
    .then(function(clientList) {
        clientList.forEach(function(client) {
            client.postMessage(message);
        });
    });
};

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function(keys){
            return Promise.all(keys.map(function(key, i){
                if(key !== CACHE_VERSION){
                    return caches.delete(keys[i]);
                }
            }))
        }).then(function() {
            console.log('[ServiceWorker] Claiming clients for version', CACHE_VERSION);
            return self.clients.claim();
        })
    )
});
JS;

    }
}