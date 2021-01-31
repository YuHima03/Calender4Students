/**
 * /// top.js ///
 * 
 * Javascript of the top-page
 * 
 * @author  YuHima <Twitter:@YuHima_03>
 * @version 1.0.0 (2021-01-30)
 */

$(function(){
    //アカウント無しで利用を押したとき
    document.querySelector("#mode_selecter > .selecter > input[name='no_signup']").addEventListener("click", (E) => {
        let mode_selecter = document.querySelector("#mode_selecter");
        //一時的なフォーム生成&送信
        let tmp_form = new createTmpForm("no_signup/index.php", "POST");
        tmp_form.addInputElement("form_token", "hidden", PHP_DATA.form_token);
        tmp_form.submit(mode_selecter);
    });
});