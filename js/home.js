/**
 * home.js
 * 
 * @author YuHima <Twitter:@YuHima_03>
 * @copyright (C)2021 YuHima
 * @version 1.0.0 (2021-02-14)
 */

/// DOMツリー読み込み後 ///
$(function() {
    let calender_elem = document.getElementById("calender");
    //カレンダー追加
    let now = new Date();
    console.log(dateToAssociativeArray(undefined, false, true));

    let clock_elem = document.getElementById("clock");
    var new_elem = document.createElement("p");
    clock_elem.appendChild(new_elem);
    setInterval(() => {
        clock_elem.querySelector("p").textContent = dateToString(UTCToClientTimezone(), "現在時刻 Y/m/d H:i:s");
    }, 100);
});