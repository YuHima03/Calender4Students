/**
 * /// top.js ///
 * 
 * Javascript of the top-page
 * 
 * @author  YuHima <Twitter:@YuHima_03>
 * @version 1.0.0 (2021-01-30)
 */

$(function(){
    //ログイン無しで利用を押したとき
    document.querySelector("#mode_selecter > .selecter > input[name='without_login']").addEventListener("click", (E) => {
        let mode_selecter = document.querySelector("#mode_selecter");
        let tmp_form = createTmpForm("", "POST");

        //tmpっていうフォーム要素を追加
        mode_selecter.appendChild(tmp_form);

        console.log(PHP_DATA);

        //フォーム要素削除
        mode_selecter.removeChild(tmp_form);
    });
});