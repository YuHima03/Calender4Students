/**
 * home.js
 * 
 * @author YuHima <Twitter:@YuHima_03>
 * @copyright (C)2021 YuHima
 * @version 1.0.0 (2021-02-14)
 */

/// DOMツリー読み込み後 ///
$(function() {
    /// カレンダー追加 ///
    let calender_elem = document.getElementById("calender");
    let now = new Date();
    let now_month = now.getMonth();

    //まずは前後1か月を合わせた3か月分読み込み
    for(let m = now_month; m <= now_month + 2; m++){ //month
        //m月1日を作る
        let year = (m <= 0) ? now.getFullYear() -1 : now.getFullYear();
        let fst_day = getFirstDay(m, year); //m月初日
        let fnl_date = getFinalDate(m, year); //m月最終日

        var new_elem = [document.createElement("div"), document.createElement("table")];
        //tableに年月を記録
        new_elem[1].dataset["year"] = now.getFullYear();
        new_elem[1].dataset["month"] = m;

        //5*7のカレンダー
        for(let w = 0; w < 5; w++){ //week
            new_elem[2] = document.createElement("tr");
            new_elem[2].dataset["week"] = w; //w+1週目

            for(let d = 0; d < 7; d++){ //date(d曜日)
                //週に日を追加(w+1週目のd曜日ができる)
                let tmp_elem = document.createElement("tr");

                let date = 7 * w + d + 1 - fst_day;
                if(valueBetween(date, 1, fnl_date)){
                    tmp_elem.dataset["date"] = date;
                }
                else{
                    //前後の月の日になる場合
                    console.log(date);
                }


                new_elem[2].appendChild(tmp_elem);
            }

            //月に週を追加
            new_elem[1].appendChild(new_elem[2]);
            new_elem[2] = undefined;
        }

        //最後に要素どもを追加して無事終了
        new_elem[0].appendChild(new_elem[1]);
        calender_elem.appendChild(new_elem[0]);
    }

    //謎の時計機能(仮設)
    let clock_elem = document.getElementById("clock");
    var new_elem = document.createElement("p");
    clock_elem.appendChild(new_elem);
    setInterval(() => {
        now = new Date();
        clock_elem.querySelector("p").textContent = dateToString(now, "現在時刻 D F jS H:i:s");
    }, 100);
});