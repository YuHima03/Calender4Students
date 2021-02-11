/**
 * sw.js
 * 
 * ServiseWorker Main
 * 
 * @author YuHima <Twitter:@YuHima_03>
 * @copyright (C)2021 YuHima
 * @version 1.0.0 (2021-02-11)
 */

const cache_urls = [
    "/app/icon.png",
    "/app/err_icon.png"
];

//インストール後に発火
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open("test").then((cache) => {
            //キャッシュに保存
            return cache.addAll(cache_urls);
        })
    );
    event.waitUntil(self.skipWaiting());
});

//通信を検知
self.addEventListener('fetch', (event) => {
    console.info("fetch", event.request);
});