/**
 * login.js
 * 
 * @author YuHima <Twitter:@YuHima03>
 * @copyright (C)2021 YuHima
 * @version 1.0.0 (2021-03-05)
 */

["account_id", "pass"].forEach(v => {
    let elem = document.querySelector(`#container form > label > input[name='${v}'`);

    elem.addEventListener("focus", event => {
        //フォーカス時
        event.target.parentElement.classList.add("focus");
        event.target.parentElement.classList.remove("error");
    });

    elem.addEventListener("blur", event => {
        //フォーカスが外れた時
        event.target.parentElement.classList.remove("focus");

        //内容のチェック
        if(event.target.value == ""){
            event.target.parentElement.classList.add("error");
        }
    });
});