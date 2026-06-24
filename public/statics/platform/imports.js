var _0x98d5 = ["", "getAttribute", "sort_", "indexOf", "substr", "baidu_sl", "baidu_checksl_pc", "baidu_checksl_wap", "baidu_qz_zz", "baidu_qz_zz_pc", "baidu_qz_zz_wap", "baidu_qz_ai", "baidu_qz_ai_pc", "baidu_qz_ai_wap", "baidu_backlink", "baidu_backlink1", "baidu_backlink7", "baidu_sl1", "baidu_sl7", "baidu_news_sl", "baidu_news_checksl", "so360_sl", "so360_checksl", "so360_qz_zz", "so360_qz_zz_pc", "so360_qz_zz_wap", "so360_news_sl", "so360_news_checksl", "sogou_sl", "sogou_pr", "sogou_checksl", "sogou_news_sl", "sogou_news_checksl", "sm_sl", "sm_qz_zz", "sm_checksl", "createdtime", "checklink", "pr", "length", "dom", "num", "value", "-1", "push", "sort", "icp", "baidu_aq", "so360_aq", "qqaq", "daochu", "99999999", "alexa", "baidu_kz", "so360_kz", "date", "/", "replace", "1970/1/1", "sogou_kz", "ip", "char", "9", "title", "selected", "hasClass", ".checkbox", "find", "removeClass", "checked", "attr", "#result #result_table .checkbox input[type=checkbox]", "#result #result_table .checkbox", "hightlight", "#result #result_table tr:gt(0)", "empty", "#selected_url_ol", "addClass", "#result #result_table tr td .checkbox", "selected_url_ol", "", "li", "createElement", "parentNode", "createTextNode", "appendChild", "selectall", "toggleClass", "className", "checkbox", "", "checkbox selected", "antiselectall", "firstChild", "result_table", "rows", "cells", ".", "childNodes", "removeChild", "getElementsByClassName", "*", "getElementsByTagName", "(^|\s)", "(\s|$)", "test", "undefined", "失败:", "a", "href", "#", "setAttribute", "重查", "onclick", "match", "toLowerCase", "exec", "click", "#result table td a[href=#]", "/multi.php", "百度收录量 ↓", "百度权重(爱站) ↓", "百度权重PC(爱站) ↓", "百度权重WAP(爱站) ↓", "百度权重(站长) ↓", "百度权重PC(站长) ↓", "百度权重WAP(站长) ↓", "百度是否收录PC", "百度是否收录WAP", "百度反链 ↓", "百度1天反链 ↓", "百度7天反链 ↓", "百度1天收录 ↓", "百度7天收录 ↓", "百度安全", "百度快照日期", "百度新闻量 ↓", "百度新闻检测 ↓", "360综合权重 ↓", "360权重PC ↓", "360权重WAP ↓", "360收录量 ↓", "360是否收录", "360安全", "360快照日期", "360新闻量 ↓", "360新闻检测 ↓", "搜狗收录量 ↓", "搜狗权重 ↓", "搜狗快照日期", "搜狗是否收录", "搜狗新闻量 ↓", "搜狗新闻检测 ↓", "神马收录量 ↓", "神马是否收录", "神马权重 ↓", "友链检测", "网站IP", "导出链接 ↑", "网站标题", "建站时间 ↓", "ALEXA排名 ↑", "QQ安全", "Google PR ↓", "ICP备案", "点击可排序 →", "result_title", "result_title_word", "result", "selected_title", "selected_url", "&", "split", "=", "func", "|", "websites", "", "val", "#ip_websites", "checklink_type", "mysite", "#mysite", "input[type=radio][name=checklink_type]", "parent", "input[type=radio][name=checklink_type][value=", "]", "pics", "div", "class", "查询结果", "insertBefore", "table", "cellPadding", "0", "cellSpacing", "border", "insertRow", "th", " ", "span", "sort_websites", "result_select_all", "全选", "br", "反选", "onmouseover", "backgroundColor", "style", "#e3ffc9", "onmouseout", "insertCell", "sequence", "http://", "target", "_blank", "outlink", "/get.php?func=", "&site=", "&checklink_type=", "&mysite=", "input", "type", "input[type=checkbox]", "remove", "#selected_url ol li:contains('", "')", "被选中网址", "ol", "start", "abort", "onreadystatechange", "POST", "open", "cache-control", "no-cache", "setRequestHeader", "Content-Type", "application/x-www-form-urlencoded", "send", "cookie", "preference", "each", ".func_select input[type=checkbox]", ".func_select .checkbox", "red", ".func_select", "#chk_", "parents", "btn_more", "1", "show", "#func_more", "收起︽", "html", ".btn_more", "btn_more_highlight", "hide", "更多︾", ";", "substring", "site", "domain", "table2CSV", "#result_table", "form", "display", "none", "method", "action", "/getExcel.php", "hidden", "name", "text_csv", "float_box", "submit", "fn", ",", "extend", "header", "css", ":visible", "filter", "td", "tr", "join", "separator", "\,", "random", "+", "host", "location", "charCodeAt", "getTime", "fromCharCode", "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/", "charAt", "==", "abc", "900150983cd24fb0d6963f7d28e17f72", "concat", "0123456789ABCDEF", "0123456789abcdef"];
function arrayUnique(arr) {
    var temp = {}, len = arr[_0x98d5[39]];
    for (var i = 0; i < len; i++) {
        if (typeof temp[arr[i]] == _0x98d5[106]) {
            temp[arr[i]] = 1
        }
    };
    arr[_0x98d5[39]] = 0;
    var len = 0;
    for (var i in temp) {
        arr[len++] = i
    };
    return arr
}
function filter(websites) {
    var pattern =
        /(?:(?:https|http|ftp|rtsp|mms):\/\/|\s|^|[^a-zA-Z])((?:[a-zA-Z0-9](?:[a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,8})(\/[a-zA-Z0-9#%&\'\(\)\*\+,\.\/:\<\=\>\?@\[\]\^_`\|\\~\-]+)?/ig;
    var slashending = /\/+$/;
    var deletequery = /#.*$/;
    var result;
    var i = 0;
    var list_num = new Array;
    var websitesArray = new Array;
    while (result = pattern[_0x98d5[116]](websites)) {
        if (result[1][_0x98d5[114]](
            /\.(js|htm|html|css|asp|php|gif|jpeg|jpg|png|bmp|doc|xls|txt|rar|zip|exe|pdf|avi|iso|dll)$/i)) {
            continue
        };
        if (result[2] === undefined) {
            result[2] = _0x98d5[90]
        };
        var filter_url = result[1][_0x98d5[115]]() + result[2];
        filter_url = filter_url[_0x98d5[57]](deletequery, _0x98d5[90])[_0x98d5[57]](slashending, _0x98d5[56]);
        filter_url = encodeURIComponent(filter_url);
        websitesArray[_0x98d5[44]](filter_url);
        list_num[i] = websitesArray[i][_0x98d5[114]](/^([\d]{1,4})(?:\.[a-zA-Z0-9\-]{0,61}){2,}/);
        if (list_num[i] != undefined) {
            list_num[i] = list_num[i][1];
            if (i > 0) {
                if (list_num[i - 1] == list_num[i] - 1) {
                    if (i > 1) {
                        websitesArray[i] = websitesArray[i][_0x98d5[57]](/^\d+\./, _0x98d5[90])
                    } else {
                        websitesArray[i] = websitesArray[i][_0x98d5[57]](/^\d+\./, _0x98d5[90]);
                        websitesArray[i - 1] = websitesArray[i - 1][_0x98d5[57]](/^\d+\./, _0x98d5[90])
                    }
                }
            }
        };
        i++
    };
    arrayUnique(websitesArray);
    return websitesArray
}