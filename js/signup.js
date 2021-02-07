/**
 * /// signup.js ///
 * 
 * @author YuHima_03 <Twitter:@YuHima_03>
 * @copyright (C)2020 YuHima
 * @version 1.0.0 (2021-02-07)
 */

/// DOMツリー読み込み後 ///
$(function() {
    document.querySelector("#create_account > input[name='_NAME']").addEventListener("input", (e) => {
        let value = e.target.value;
        let chk_regexp = /^[A-Za-z0-9_]{4,32}$/;

        //正規表現で形式チェック
        if(chk_regexp.test(value)){
            //AjaxでIDチェッカーを叩く
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "account_name_checker.php");
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=utf8");
            xhr.send("name=" + value);

            xhr.onreadystatechange = (ev) => {
                if(xhr.readyState == 4 && xhr.status == 400){
                    //通信成功
                    let form = document.getElementById("create_account");
                    let _NAME = form.querySelector("input[name='_NAME']");

                    let msg_elem = document.createElement("span");
                    msg_elem.classList.add("used_name_msg");
                    msg_elem.textContent = "このIDは既に使用されています";

                    if(xhr.responseText == "false"){
                        //既に使われてる
                        _NAME.classList.add("used_name");
                        form.insertBefore(msg_elem, _NAME.nextSibling);
                    }
                    else{
                        //未使用
                        form._NAME.classList.remove("used_name");
                        form.querySelectorAll(".used_name_msg").forEach((elem) => {
                            elem.remove();
                        });
                    }

                    xhr.abort();
                }
            }
        }
        else{
            console.log("形式が違う！");
        }
    });
});