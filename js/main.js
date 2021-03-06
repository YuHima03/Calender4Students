/**
 * /// main.js ///
 * 
 * Main javascript of the website
 * 
 * @author  YuHima <Twitter:@YuHima_03>
 * @version 1.0.0 (2021-01-30)
 */

/// いろいろな関数 ///
/**PHPでいうところのisset */
function isset(d){
    return (d !== "" && d !== null && d !== undefined);
}

/**setAttributeを複数回一度に実行 
 * @param E Element
 * @param value {name:value, ...}
 */
function setAttrs(E, value){
    let arr = Object.entries(value);
    arr.forEach((v) => {
        E.setAttribute(arr[0], arr[1]);
    });
}

/** 一時的に使うフォーム要素を作成*/
class createTmpForm{
    constructor(action, method){
        this.tmp_form = document.createElement("form");
        this.tmp_form.action = action;
        this.tmp_form.name = "tmp";
        this.tmp_form.method = method;
        this.tmp_form.style.display = "none";
    }

    addInputElement(name, type, value = ""){
        let tmp_input = document.createElement("input");
        tmp_input.name = name;
        tmp_input.type = type;
        tmp_input.value = value;

        this.tmp_form.appendChild(tmp_input);

        return true;
    }

    getElement(){
        return this.tmp_form;
    }

    submit(parentElement){
        parentElement.appendChild(this.tmp_form);
        this.tmp_form.submit();
    }
}

/**ランダムな文字列を返す (lenの長さの文字列) */
function rand_text(len = 64){
    let char = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
    let ret = "";
    for(i = 0; i < len; i++){
        ret += char[Math.floor(Math.random() * char.length)];
    }
    return ret;
}

/**
 * Dateオブジェクトを連想配列にする
 * @param {Object} DateObject 
 * @param {Boolean} UTC UTC時間を返す
 * @param {Boolean} noMonthZero //月を1~12にする
 * @returns {Object} 連想配列
 */
function dateToAssociativeArray(DateObject = undefined, UTC = false, noMonthZero = true){
    let now = (isset(DateObject)) ? DateObject : new Date();
    return (UTC) ? {
        year            :   now.getUTCFullYear(),
        month           :   now.getUTCMonth() + Number(noMonthZero),
        date            :   now.getUTCDate(),
        day             :   now.getUTCDay(),
        hours           :   now.getUTCHours(),
        minutes         :   now.getUTCMinutes(),
        seconds         :   now.getUTCSeconds(),
        milliseconds    :   now.getUTCMilliseconds()
    }
    : {
        year            :   now.getFullYear(),
        month           :   now.getMonth() + Number(noMonthZero),
        date            :   now.getDate(),
        day             :   now.getDay(),
        hours           :   now.getHours(),
        minutes         :   now.getMinutes(),
        seconds         :   now.getSeconds(),
        milliseconds    :   now.getMilliseconds()
    };
}

/**
 * Dateオブジェクトで取得した時間を文字列にする
 * @param {Object} DatetimeObj Dateオブジェクト
 * @param {String} format 書式(PHPとおんなじ)
 * @param {Boolean} UTC UTC時間を使う
 * @returns {String}
 */
function dateToString(DateObject = undefined, format = "", UTC = false){
    let now = (isset(DateObject)) ? DateObject : new Date();
    let arr = dateToAssociativeArray(now, UTC, true);

    //フルスペルの曜日
    function getFullSpellDay(day_num){
        switch(day_num){
            case(0):    return "Sunday";
            case(1):    return "Monday";
            case(2):    return "Tuesday";
            case(3):    return "Wednesday";
            case(4):    return "Thursday";
            case(5):    return "Friday";
            case(6):    return "Saturday";
        }
    }
    //フルスペルの月
    function getFullSpellMonth(month_num){ //1~12
        switch(month_num){
            case(1):    return "January";
            case(2):    return "February";
            case(3):    return "March";
            case(4):    return "April";
            case(5):    return "May";
            case(6):    return "June";
            case(7):    return "July";
            case(8):    return "August";
            case(9):    return "September";
            case(10):   return "October";
            case(11):   return "November";
            case(12):   return "December";
        }
    }
    
    let result = format.replace(/Y/g, arr.year) //年(4桁)
    .replace(/y/g, str_split(String(arr.year), -2, -1)) //年(2桁)
    .replace(/n/g, arr.month) //月(1~2桁)
    .replace(/m/g, ((!Math.floor(arr.month / 10)) ? "0" : "") + String(arr.month)) //月(2桁)
    .replace(/w/g, arr.day) //曜日(0[日]~6[土])
    .replace(/N/g, (arr.day > 0) ? arr.day : 7) //ISO-8601形式の曜日(1[月]~7[日])
    .replace(/d/g, ((!Math.floor(arr.date / 10)) ? "0" : "") + String(arr.date)) //日(2桁)
    .replace(/g/g, arr.hours - ((arr.hours <= 12) ? 0 : 12)) //時(12時間単位)(1~2桁)
    .replace(/h/g, ((valueBetween(arr.hours, 10, 12) || valueBetween(arr.hours, 22, 24)) ? "" : "0") + String(arr.hours - ((arr.hours <= 12) ? 0 : 12))) //時(12時間単位)(2桁)
    .replace(/G/g, arr.hours) //時(24時間単位)(1~2桁)
    .replace(/H/g, ((!Math.floor(arr.hours / 10)) ? "0" : "") + String(arr.hours)) //時(24時間単位)(2桁)
    .replace(/i/g, ((!Math.floor(arr.minutes / 10)) ? "0" : "") + String(arr.minutes)) //分(2桁)
    .replace(/s/g, ((!Math.floor(arr.seconds / 10)) ? "0" : "") + String(arr.seconds)) //秒(2桁)
    .replace(/v|u/g, arr.milliseconds) //ミリ秒
    .replace(/(?<=j)S/g, () => { //日(1st,2nd,3rd,4th...)
        switch(arr.date % 10){
            case(1):    return "st";
            case(2):    return "nd";
            case(3):    return "rd";
            default:    return "th";
        }
    })
    .replace(/j/g, arr.date) //日(1~2桁)
    .replace(/a/g, (arr.hours < 12) ? "am" : "pm") //午前or午後(小文字)
    .replace(/A/g, (arr.hours < 12) ? "AM" : "PM") //午前or午後(大文字)
    .replace(/F/g, getFullSpellMonth(arr.month)) //月(フルスペル)
    .replace(/M/g, str_split(getFullSpellMonth(arr.month), 0, 2)) //月(3文字)
    .replace(/l/g, getFullSpellDay(arr.day)) //曜日(フルスペル)
    .replace(/D/g, str_split(getFullSpellDay(arr.day), 0, 2))//曜日(3文字)

    return result;
}

/**
 * その月の初日の曜日を取得
 * @param {Number} year 
 * @param {Number} month 
 */
function getFirstDay(year, month){
    let date = new Date(year, month-1, 1);
    return date.getDay();
}

/**
 * その月の最終日の日付を取得
 * @param {Number} year 
 * @param {Number} month 
 * @param {Boolean} day 日付じゃなくて曜日を取得
 */
function getFinalDate(year, month, day = false){
    let date = new Date(year, month, 0);
    return (day) ? date.getDay() : date.getDate();
}

/**
 * UTC時間からホストシステム側の時間に変換
 * @param {Object} DateObject Dateオブジェクト
 * @param {Number} timezoneOffset UTC時間からの差(UTC+9だったら9)
 * @returns {Object} Dateオブジェクト
 */
function UTCToClientTimezone(DateObject = undefined, timezoneOffset = undefined){
    let now = (isset(DateObject)) ? DateObject : new Date();
    let tzOffset = (isset(timezoneOffset)) ? timezoneOffset : -now.getTimezoneOffset();

    now.setUTCMinutes(now.getUTCMinutes() + tzOffset);

    return now;
}

/**
 * 文字切り出し
 * @param {string} str
 * @param {number} from
 * @param {number} to
 * @returns {string}
 * */
function str_split(str, from, to){
    let len = str.length;
    let result = "";

    from += (from < 0) ? len : 0;
    to += (to < 0) ? len : 0;

    if(from <= to){
        for(let i = from; i <= to; i++){
            result += str.charAt(i);
        }
    }
    else{
        for(let i = from; i >= to; i--){
            result += str.charAt(i);
        }
    }

    return result;
}

/**
 * 値がfromとtoの間にあるかを検証
 * @param {Any} value 
 * @param {Any} from 
 * @param {Any} to 
 * @param {Boolean} [includeEqual=true]
 * @return {Boolean}
 */
function valueBetween(value, from, to, includeEqual = true){
    if(from < value && value < to){
        return true;
    }
    else if (includeEqual && (value == from || value == to)){
        return true;
    }
    return false;
}

/**
 * Element の中の全要素削除
 * @param {HTMLElement} Element Target Element (Parent)
 */
function removeAllChildElements(Element){
    while(Element.firstChild){
        Element.removeChild(Element.firstChild);
    }

    return !isset(Element.firstChild);
}

/**
 * ```targetElement```内のすべての要素を取得(どれだけ階層が下でも兎に角全部)
 * @param {HTMLElement} targetElement 
 */
function getAllChildren(targetElement){
    let result = Array();
    let childrenElements = targetElement.children;

    for(let i = 0; i < childrenElements.length; i++){
        let elem = childrenElements[i];
        result.push(elem);
        
        if(elem.children.length > 0){
            getAllChildren(elem).forEach(v => {
                result.push(v);
            });
        }
    }

    return result;
}

/**
 * ```a≡k (mod b)```の```k(余り)```を返す(正の数)
 * @param {Number} a 
 * @param {Number} b 
 */
function mod(a, b){
    return (
        (a % b >= 0)
        ? (a % b)
        : (a % b + b)
    );
}

/// DOMツリー読み込み後実行 ///
$(function(){
    //a要素のセキュリティ対策
    document.querySelectorAll("a[target='_blank']").forEach((E) => {
        E.setAttribute('rel', "noopener noreferrer");
    });

    //ボタン要素にdata-gotoがある場合はそこに移動するようにする
    document.querySelectorAll("input[type='button']").forEach((E) => {
        E.addEventListener("click", (E) => {
            let go_to = E.target.dataset.goto;
            if(isset(go_to)){
                go_to = go_to.split(/,/);

                if(go_to[1] == "_blank"){
                    window.open(go_to[0], "_blank", "noopener noreferrer");
                }
                else{
                    window.location.href = go_to[0];
                }
            }
        });
    });

    //inputタグにtypeとnameに応じたクラス名をそれぞれつける
    [...document.getElementsByTagName("input")].forEach(element => {
        if(isset(element.type)){
            element.classList.add(`input_${element.type}`);
        }
        if(isset(element.name)){
            element.classList.add(`name_${element.name}`);
        }
    });

    //クラス名判定
    getAllChildren(document.body).forEach(element => {
        element.classList.forEach(v => {
            if(v.match(/-weight-\d{3}/)){
                element.style.fontWeight =  String(v.match(/\d{3}$/)[0]);
            }
        });
    });
});