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

/** 一時的に使うフォーム要素を返す*/
function createTmpForm(action, method){
    let tmp_form = document.createElement("form");
    tmp_form.action = action;
    tmp_form.name = "tmp";
    tmp_form.method = method;
    tmp_form.style.display = "none";

    return tmp_form;
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
                    location.href(go_to[0]);
                }
            }
        });
    });
});