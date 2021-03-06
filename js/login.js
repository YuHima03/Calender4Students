/**
 * login.js
 * 
 * @author YuHima <Twitter:@YuHima03>
 * @copyright (C)2021 YuHima
 * @version 1.0.0 (2021-03-05)
 */


let Messages = PHP_DATA["lang"];


/**
 * エラー表示
 * @param {String} errorMsg 
 * @returns {Void}
 */
function errorBox(errorMsg){
    let msgElem = document.getElementById("errmsg");
    removeAllChildElements(msgElem);

    let pElem = document.createElement("p");
    pElem.textContent = errorMsg;

    msgElem.appendChild(pElem);
    msgElem.style.display = "block";
    msgElem.classList.add("show");

    return;
}

//PHPから渡された中にエラーが入ってたら処理する
let loginError = PHP_DATA.login_error;
if(loginError.length){
    loginError.forEach(value => {
        switch(value){
            case("WRONG_ID_OR_PASSWORD"):
                errorBox(Messages.error.incorrect_id_or_password);
                break;
            case("UNKNOWN_ERROR_OCCURED"):
                errorBox(Messages.error.unknown_error_occured);
                break;
        }
    });
}

//////////////////////////////////////////////////
// フォーム関係

/**
 * 入力欄フォーカス時
 * @param {Event} event 
 */
function formInputOnFocus(event){
    event.target.parentElement.classList.add("focus");
    event.target.parentElement.classList.remove("error");
}

/**
 * 入力欄のフォーカスが外れた時
 * @param {Event} event 
 */
function formInputOnBlur(event){
    event.target.parentElement.classList.remove("focus");
    event.target.parentElement.classList.remove("error");

    //内容のチェック
    if(event.target.value == ""){
        event.target.parentElement.classList.add("error");
    }
}

/**
 * 入力欄に空欄がないかチェック
 */
function inputCheck(){
    let result = false;

    ["account_id", "pass"].forEach(v => {
        let elem = document.querySelector(`#container form > label > input[name='${v}']`);
        elem.dispatchEvent(new Event("blur"));
        result = elem.parentElement.classList.contains("error");
    });

    return !result;
}

//入力欄のフォーカス変化時
["account_id", "pass"].forEach(v => {
    let elem = document.querySelector(`#container form > label > input[name='${v}']`);

    elem.addEventListener("focus", formInputOnFocus);
    elem.addEventListener("blur", formInputOnBlur);
});

//ログインボタンが押されたとき
document.querySelector("#container form > input[type='submit']").addEventListener("click", () => {
    if(inputCheck()){
        document.querySelector("#container form").submit();
    }
    else{
        return 0;
    }
});


