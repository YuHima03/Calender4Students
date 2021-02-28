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

        body > div {
            margin-left: 1em;
        }

            body > div > h3 {
                display: inline-block;
                margin: .5em 0;
            }

            body > div > table {
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

                body > div > table tr > td {
                    padding: .25em;
                }

                body > div > table > tbody > tr:not(:first-child) > td:not(:first-child) {
                    border-left: solid 2px whitesmoke;
                }

                body > div > table > tbody > tr:first-child > td {
                    position: sticky;
                    top: 0;
                    font-size: 15px;
                    line-height: 1.25em;
                    background: dimgray;
                    color: white;
                }

                body > div > table > tbody > tr:not(:first-child):nth-child(2n + 1) {
                    background: #0f0f0f0f;
                }

                body > div > table::-webkit-scrollbar {
                    display: block;
                    width: 10px;
                    height: 10px;
                }

                body > div > table::-webkit-scrollbar-track {
                    background: rgba(0, 0, 0, 0);
                }

                body > div > table::-webkit-scrollbar-button {
                    display: none;
                }

                body > div > table::-webkit-scrollbar-corner {
                    background-color: transparent;
                }

                body > div > table::-webkit-scrollbar-thumb {
                    background-color: rgba(176, 176, 176, 0.5);
                    border-radius: 10px;
                }

                    body > div > table::-webkit-scrollbar-thumb:hover {
                        background-color: rgba(176, 176, 176, 0.75);
                    }

                    body > div > table::-webkit-scrollbar-thumb:active {
                        background-color: rgba(176, 176, 176, 1);
                    }
    </style>
    <script>
        window.addEventListener("load", () => {
            //PHPからもらったデータの保存
            let DATA = [];
            DATA["SERVER"] = <?=json_encode($_SERVER)?>;

            //table
            document.querySelectorAll("body > div > table").forEach(element => {
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
                
                //高さ揃え
                element.style.height = "calc(" + (window.innerHeight - element.offsetTop) + "px - 1em)";
            });
        });
    </script>
</head>
<body>
    <div>
        <h3>$_SERVER</h3>
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
</body>
</html>