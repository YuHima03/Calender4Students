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
 * @param {Element} Element Target Element (Parent)
 */
function removeAllChildElements(Element){
    while(Element.firstChild){
        Element.removeChild(Element.firstChild);
    }

    return !isset(Element.firstChild);
}

/**
 * ```targetElement```内のすべての要素を取得(どれだけ階層が下でも兎に角全部)
 * @param {Element} targetElement 
 */
function getAllChildren(targetElement){
    let result = Array();
    let childElements = targetElement.children;

    for(let i = 0; i < childElements.length; i++){
        let elem = childElements[i];
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
 * ```targetElement```に関わる全ての親要素の取得(htmlまでいく)
 * @param {Element} targetElement 
 * @returns {Array} 添え字0の値は```HTMLElement```になってるはず
 */
function getAllParents(targetElement){
    let parentElement = targetElement.parentElement;

    if(isset(parentElement)){
        return [...getAllParents(parentElement), parentElement];
    }
    else{
        //もうこれ以上親要素がない
        return Array();
    }
}

/**
 * ```targetElement```にCSSで付けられた値を取得 (恐ろしいほどに処理が多いので何度も実行すると重くなる可能性アリ)
 * @param {Element} targetElement 
 * @param {Boolean} getInheritedStyle 継承された値も取得
 * @returns {Object}
 */
function getStyleDeclarations(targetElement, getInheritedStyle = false, getFromCSSStyleSheet = true){
    let CSS = document.styleSheets;
    let result = Object();

    if(getInheritedStyle){
        [...getAllParents(targetElement), targetElement].forEach(element => {
            let style = getStyleDeclarations(element, false, getFromCSSStyleSheet);
            Object.keys(style).forEach(key => {
                result[key] = style[key];
            });
        });
    }
    else if(getFromCSSStyleSheet){
        //スタイルシートにちゃんと書いてあるやつ
        [...CSS].forEach(value => { //読み込んだCSSごとに
            [...value.cssRules].forEach(rule => { //CSSの一つの纏まり`{...}`ごとに
                //セレクターの文字列で検索して合致するかチェックし、合致したらそのスタイルの値を記録していく
                let elements = document.querySelectorAll(rule.selectorText);

                [...elements].forEach(element => {
                    if(element === targetElement){
                        let style = rule.style;
                        [...style].forEach(styleValue => {
                            result[styleValue] = style[styleValue];
                        });
                    }
                });
            });
        });
    }

    //直書きされてるやつ
    let style = targetElement.style;
    [...style].forEach(value => {
        result[value] = style[value];
    });

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

/**
 * スタイルの値を分割して
 * @param {String} styleValue 
 * @returns {Array}
 */
function sliceStyleValue(styleValue) {
    let result = [];
    [...styleValue.split(/,/)].forEach(value => {
        let splitValue = value.match(/\S+/g);
        result.push([...splitValue]);
    });

    return result;
}

/**
 * `styleValue` の `index` 番目の値を `replaceValue` に変える
 * @param {String} styleValue 
 * @param {Number|Array} index 1次配列の場合は`,`で区切った[0]番目のなかの[1]番目の値、2次配列の場合は1次配列を複数
 * @param {String} replaceValue 
 * @returns {String}
 */
function replaceStyleValue(styleValue, index, replaceValue){
    let result = sliceStyleValue(styleValue);

    let err_index_type = new TypeError("`index` must be Number (or Array)");
    let err_out_of_range = new RangeError("`index` out of range");

    if(typeof(index) == "number"){
        result.forEach((arr, i1) => {
            if(isset(result[i1][index])){
                result[i1][index] = replaceValue;
            }
            else {
                throw err_out_of_range;
            }
        });
    }
    else if(index instanceof Array){
        /**
         * index が配列の場合...
         * 以下の3通りの記述が可能
         * 
         * ・index[0], index[1] が共に整数 * 
         * ・index[0], ..., index[n] が全て配列 かつ
         *      ・index[n][0], index[n][1] が共に整数 *
         *      ・index[n][0] が整数で index[n][1] が配列 かつ
         *          ・index[n][1][0], ..., index[n][1][m] が全て整数 *
         */

        index.forEach(v => { // index[n]<Array> (ただしn = i)
            if(v.length == 2 && typeof(v[0]) == "number"){
                //result[index[i][0]][index[i][1]] を置き換え(v = index[i])
                if(v[0] < result.length){
                    if(typeof(v[1]) == "number"){
                        if(v[1] < result[v[0]].length){
                            result[v[0]][v[1]] = replaceValue;
                        }
                        else{
                            throw err_out_of_range;
                        }
                    }
                    else if(v[1] instanceof Array){
                        v[1].forEach(v2 => {
                            if(typeof(v2) == "number"){
                                if(v2 < result[v[0]].length){
                                    result[v[0]][v2] = replaceValue;
                                }
                                else{
                                    throw err_out_of_range;
                                }
                            }
                            else{
                                throw err_index_type;
                            }
                        });
                    }
                    else{
                        throw err_index_type;
                    }
                }
                else{
                    throw err_out_of_range;
                }
            }
            else{
                throw err_index_type;
            }
        });
    }
    else{
        throw err_index_type;
    }

    //配列から文字列に変換
    let resultStr = String();
    
    result.forEach(v1 => {
        let tmp = String();
        v1.forEach(v2 => {
            tmp += ("\x20" + v2);
        });
        resultStr += ("," + tmp);
    });

    return resultStr.slice(2);
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