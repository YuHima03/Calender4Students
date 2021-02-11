/**
 * app.js
 * 
 * ServiceWorker registration
 * 
 * @author YuHima <Twitter:@YuHima_03>
 * @copyright (C)2021 YuHima
 * @version 1.0.0 (2021-02-11)
 */

//登録
if('serviceWorker' in navigator){
    navigator.serviceWorker.register("/app/sw.js")
    .then((reg) => {
        console.info("[ServiceWorker]", "Scope is " + reg.scope);
    }).catch((err) => {
        console.error(err);
    });

    navigator.serviceWorker.addEventListener('message', (ev) => {
        console.log(ev);
    });
}