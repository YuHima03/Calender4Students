/**
 * home.js
 * 
 * @author YuHima <Twitter:@YuHima_03>
 * @copyright (C)2021 YuHima
 * @version 1.0.0 (2021-02-14)
 */

/**
 * 月間カレンダーの要素を新規で作る
 * @param {Number} year 
 * @param {Number} month 
 * @returns {Boolean|HTMLTableElement} 要素の作成に成功した場合は月間カレンダーのtable要素を返す(失敗するとfalse)
 */
function createMonthlyCalender(year, month){
    let calenderElem = document.getElementById("calender");

    //年
    let yearElem = calenderElem.querySelector(`div[data-year="${year}"]`);
    if(!isset(yearElem)){
        //その年の要素が存在しない場合は要素の作成
        yearElem = document.createElement("div");
        yearElem.classList.add("year");
        yearElem.dataset["year"] = year;
        newYearElemFlag = true;
    }

    //月
    if(isset(yearElem.querySelector(`table[data-month="${month}"`))){
        //既に月が存在する場合は処理やらない
        return false;
    }
    else{
        //変数いろいろ
        let now = new Date();
        let fDay = getFirstDay(year, month); //初日の曜日
        let dNum = getFinalDate(year, month); //最終日(その月の日数)

        //月の要素の新規作成
        let monthElem = document.createElement("table");
        monthElem.classList.add("month");
        monthElem.dataset["month"] = month;

        //週(0~5)(week+1週目)
        for(let week = 0; week < 6; week++){
            let weekElem = document.createElement("tr");
            weekElem.classList.add("week");
            weekElem.dataset["week"] = week;

            //日(0~6)(day+1曜日)(week*7+day+fDay)
            for(let day = 0; day < 7; day++){
                let dateElem = document.createElement("td");
                let dateShowElem = document.createElement("div"); //日付表示
                dateElem.classList.add("date", "out_of_month");

                let date = week * 7 + day - (fDay - 1);
                //日付がその月の範囲を超えたとき
                if(date <= 0)           date += getFinalDate(year, month-1);  //先月
                else if(date > dNum)    date -= dNum;                               //来月
                else                    dateElem.classList.remove("out_of_month");  //今月(クラス名out_of_monthを消す)

                //日付セット
                dateElem.dataset["date"] = date;
                dateShowElem.textContent = date;

                dateElem.appendChild(dateShowElem);
                weekElem.appendChild(dateElem);
            }

            monthElem.appendChild(weekElem);
        }

        //月を追加
        let monthElements = yearElem.querySelectorAll("table.month");
        if(!monthElements.length || Number(monthElements[monthElements.length-1].dataset["month"]) <= month){
            //その月が一番新しい場合
            yearElem.appendChild(monthElem);
        }
        else{
            //一番新しい年以外の場合
            for(let i = 0; i < monthElements.length; i++){
                if(month < Number(monthElements[i].dataset["month"])){
                    yearElem.insertBefore(monthElem, monthElements[i]);
                    break;
                }
            }
        }

        //年を追加
        let yearElements = calenderElem.querySelectorAll("div.year");
        if(!yearElements.length || Number(yearElements[yearElements.length-1].dataset["year"]) <= year){
            //その年が一番新しい場合
            calenderElem.appendChild(yearElem);
        }
        else{
            //一番新しい年以外の場合
            for(let i = 0; i < yearElements.length; i++){
                if(year < Number(yearElements[i].dataset["year"])){
                    calenderElem.insertBefore(yearElem, yearElements[i]);
                    break;
                }
            }
        }

        return monthElem;
    }
}

/**
 * 再読み込み含め月間カレンダーを読み込む
 * @param {Number} year 
 * @param {Number} month 
 */
function loadMonthlyCalender(year, month){

}

/// DOMツリー読み込み後 ///
$(function() {
    /// カレンダー追加 ///
    let calender_elem = document.getElementById("calender");
    let now = new Date();
    let now_month = now.getMonth();

    //まずは前後1か月を合わせた3か月分読み込み
    for(let m = now_month; m <= now_month + 2; m++){ //month
        createMonthlyCalender(now.getFullYear(), m);
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