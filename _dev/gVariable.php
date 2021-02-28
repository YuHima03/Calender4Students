<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Variable List</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0 auto;
        }

        main > ul {
            margin: .5em 1em;
            padding: 0;
            list-style: none;
        }

            main > ul > li {
                display: inline-block;

                font-family: "Yu Gothic UI";
                font-size: 18px;
                font-weight: 600;
                line-height: 1.5em;

                color: #0f0f0f;
                padding: 0 .5em;
                border-radius: 5px;

                transition: ease-out 150ms background-color, ease-out 100ms transform;
            }

                main > ul > li:hover {
                    cursor: pointer;
                    background-color: whitesmoke;
                    transform: scale(1.02);
                }

                main > ul > li:active {
                    background-color: gainsboro;
                    transform: scale(0.98);
                }

                main > ul > li.slc {
                    background-color: #3f3f3f;
                    color: white;
                }

        main > div {
            display: none;
            margin-left: 1em;
        }

            main > div > table {
                font-family: consolas;
                font-size: 14px;
                font-weight: normal;
                line-height: 1.3em;

                display: inline-block;
                width: calc(100% - 5em);
                height: auto;

                margin: .5em 2em;
                padding: 0.5em;
                overflow: scroll;

                background: whitesmoke;
                color: #0f0f0f;
                border-left: solid 5px gainsboro;

                border-collapse: collapse;
            }

                main > div > table tr > td {
                    padding: .25em;
                }

                main > div > table > tbody > tr:not(:first-child) > td:not(:first-child) {
                    border-left: solid 2px whitesmoke;
                }

                main > div > table > tbody > tr:first-child > td {
                    position: sticky;
                    top: 0;
                    font-size: 15px;
                    line-height: 1.25em;
                    background: dimgray;
                    color: white;
                }

                main > div > table > tbody > tr:not(:first-child):nth-child(2n + 1) {
                    background: #0f0f0f0f;
                }

                main > div > table::-webkit-scrollbar {
                    display: block;
                    width: 10px;
                    height: 10px;
                }

                main > div > table::-webkit-scrollbar-track {
                    background: rgba(0, 0, 0, 0);
                }

                main > div > table::-webkit-scrollbar-button {
                    display: none;
                }

                main > div > table::-webkit-scrollbar-corner {
                    background-color: transparent;
                }

                main > div > table::-webkit-scrollbar-thumb {
                    background-color: rgba(176, 176, 176, 0.5);
                    border-radius: 10px;
                }

                    main > div > table::-webkit-scrollbar-thumb:hover {
                        background-color: rgba(176, 176, 176, 0.75);
                    }

                    main > div > table::-webkit-scrollbar-thumb:active {
                        background-color: rgba(176, 176, 176, 1);
                    }
    </style>
    <script>
        window.addEventListener("load", () => {
            //PHPからもらったデータの保存
            let DATA = [];
            DATA["SERVER"] = <?=json_encode($_SERVER)?>;
            DATA["REQUEST"] = <?=json_encode($_REQUEST)?>;
            DATA["ENV"] = <?=json_encode($_ENV)?>;
            DATA["COOKIE"] = <?=json_encode($_COOKIE)?>;
            DATA["SESSION"] = <?=json_encode($_SESSION)?>;

            //高さ揃え
            function setHeight(element){
                element.style.height = "calc(" + (window.innerHeight - element.offsetTop) + "px - 1em)";
            }

            //table
            document.querySelectorAll("main > div > table").forEach(element => {
                //PHPからもらったデータぶち込む
                let obj = DATA[element.dataset["name"]];
                let tbody = element.getElementsByTagName("tbody")[0];

                Object.keys(obj).forEach(key => {
                    let tr = document.createElement("tr");
                    tr.innerHTML = `<td>${key}</td>`
                    + `<td>${typeof(obj[key])}(${String(obj[key]).length})</td>`
                    + `<td>${(typeof(obj[key]) === "string") ? "\"" + obj[key] + "\"" : obj[key]}</td>`;
                    
                    tbody.appendChild(tr);
                });
                
                //高さ設定
                setHeight(element);

                //selectの下準備
                let counter = 0;
                document.querySelectorAll("main > ul > li").forEach(element => {
                    element.dataset["index"] = counter;
                    counter++;
                });
            });

            //select
            document.querySelectorAll("main > ul > li").forEach(element => {
                element.addEventListener("click", event => {
                    if(!element.classList.contains("slc")){
                        let before = element.parentElement.querySelector(".slc");
                        before.classList.remove("slc");
                        element.classList.add("slc");

                        let mainDiv = document.querySelectorAll("main > div");
                        mainDiv[before.dataset["index"]].style.display = "none";
                        mainDiv[Number(element.dataset["index"])].style.display = "block";

                        setHeight(mainDiv[Number(element.dataset["index"])].querySelector("table"));
                    }
                });
            });
        });
    </script>
</head>
<body>
    <main>
        <ul>
            <li class="slc">$_SERVER</li>
            <li>$_SESSION</li>
            <li>$_COOKIE</li>
            <li>$_ENV</li>
            <li>$_REQUEST</li>
        </ul>
        <!--SERVER-->
        <div style="display: block;">
            <table data-name="SERVER">
                <tbody>
                    <tr>
                        <td>Key</td>
                        <td>Type(len)</td>
                        <td>Value</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--SESSION-->
        <div>
            <table data-name="SESSION">
                <tbody>
                    <tr>
                        <td>Key</td>
                        <td>Type(len)</td>
                        <td>Value</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--COOKIE-->
        <div>
            <table data-name="COOKIE">
                <tbody>
                    <tr>
                        <td>Key</td>
                        <td>Type(len)</td>
                        <td>Value</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--ENV-->
        <div>
            <table data-name="REQUEST">
                <tbody>
                    <tr>
                        <td>Key</td>
                        <td>Type(len)</td>
                        <td>Value</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--REQUSET-->
        <div>
            <table data-name="REQUEST">
                <tbody>
                    <tr>
                        <td>Key</td>
                        <td>Type(len)</td>
                        <td>Value</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>