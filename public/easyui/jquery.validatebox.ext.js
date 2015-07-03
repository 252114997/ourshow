$.extend($.fn.validatebox.defaults.rules, {
    CHS: {
        validator: function (value, param) {
            return /^[\u0391-\uFFE5]+$/.test(value);
        },
        message: '请输入汉字'
    },
    ZIP: {
        validator: function (value, param) {
            return /^[1-9]\d{5}$/.test(value);
        },
        message: '邮政编码不存在'
    },
    QQ: {
        validator: function (value, param) {
            return /^[1-9]\d{4,10}$/.test(value);
        },
        message: 'QQ号码不正确'
    },
    mobile: {
        validator: function (value, param) {
            return /^((\(\d{2,3}\))|(\d{3}\-))?13\d{9}$/.test(value);
        },
        message: '手机号码不正确'
    },
    loginName: {
        validator: function (value, param) {
            return /^[\u0391-\uFFE5\w]+$/.test(value);
        },
        message: '名称只允许汉字、英文字母、数字及下划线。'
    },
    safepass: {
        validator: function (value, param) {
            return commonPassword(value);
        },
        message: '密码由字母和数字组成，至少6位'
    },
    equalTo: {
        validator: function (value, param) {
            return value == $(param[0]).val();
        },
        message: '两次输入的字符不一致'
    },
    number: {
        validator: function (value, param) {
            return /^\d+$/.test(value);
        },
        message: '请输入数字'
    },
    idcard: {
        validator: function (value, param) {
            return idCard(value);
        },
        message:'请输入正确的身份证号码'
    },
    macAddr: {
        validator: function (value, param) {
            return singleMAC(value);
        },
        message: '请输入以冒号分隔的十六进制网卡硬件地址，例如（01:02:03:04:05:06）'
    },
    ipAddr: {
        validator: function (value, param) {
            return singleIP(value);
        },
        message: '请输入点分十进制的IP地址，例如（192.168.1.3）'
    },
    ipMaskAddr : {
        validator: function (value, param) {
            return singleIPMask(value);
        },
        message: '请输入点分十进制的IP地址与掩码地址，例如（192.168.1.3/255.255.255.0）'
    },
    multiSnmpServerAddr : {
        validator: function (value, param) {
            return multiSnmpServer(value);
        },
        message: '请输入IP/Oid/Community<br/>'
                + ' 支持以下格式： <br/>'
                + '   192.168.2.100/.1.3.6.1.2.1.4.22.1.2/public <br/>'
                + '<br/>'
                + 'Oid一般为 .1.3.6.1.2.1.4.22.1.2和.1.3.6.1.2.1.3.1.1.2 <br/>'
                + '支持同时输入多行'
    },
    multiRangeNumber : {
        validator: function (value, param) {
            return multiRangeNum(value);
        },
        message: '请输入整数<br/>'
                + ' 支持以下格式： <br/>'
                + '   1234 <br/>'
                + '   100-200 <br/>'
                + '支持同时输入多行'
    },
    multiIPAddr: {
        validator: function (value, param) {
            return multiIP(value);
        },
        message: '请输入点分十进制的IP地址<br/>'
                + ' 支持以下格式： <br/>'
               // + '   #192.168.1.2 <br/>'
                + '   192.168.1.99 <br/>'
                + '   192.168.1.5-192.168.1.9 <br/>'
                + '   192.168.1.10/255.255.255.0 <br/>'
               // + '   192.168.1.13/24 <br/>'
                + '支持同时输入多行'
    },
    multiIPAddrSimple: {
        validator: function (value, param) {
            return multiIPSimple(value);
        },
        message: '请输入点分十进制的IP地址<br/>'
                + ' 支持以下格式： <br/>'
                + '   192.168.1.99 <br/>'
                + '支持同时输入多行'
    },
    multiIPMacAddr : {
        validator: function (value, param) {
            return multiIPMac(value);
        },
        message:  '请输入IP地址与网卡硬件地址，例如（192.168.1.3/01:02:03:04:05:06）<br/>'
                + '支持同时输入多行'
    },
    multiIPOrMacAddr: {
        validator: function (value, param) {
            return multiIPOrMac(value);
        },
        message:  '请输入点分十进制的IP地址，例如（192.168.1.3）<br/>'
                + '或者，以冒号分隔的十六进制网卡硬件地址，例如（01:02:03:04:05:06）<br/>'
                + '支持同时输入多行'
    },
    /**
     * @brief 自定义一个验证组件用于过滤数据
     */
    minLength : {
            validator : function (value, param) {
                if (param[0] =='0') {
                    return param[0] >= 0;
                }
                return param[0] >= parseInt(value);
            },
            message : '请输入不大于{0}的值',
    }
});

/**
 * @brief 密码由字母和数字组成，至少6位
 */
var safePassword = function (value) {
    return !(/^(([A-Z]*|[a-z]*|\d*|[-_\~!@#\$%\^&\*\.\(\)\[\]\{\}<>\?\\\/\'\"]*)|.{0,5})$|\s/.test(value));
};

/**
 * @brief 只要大于六位即可
 */
var commonPassword = function (value) {
    return !(/^(.{0,5})$|\s/.test(value)); 
}

/**
 * @brief 匹配多行 SNMP服务器列表
 * 
 * 192.168.10.254/.1.3.6.1.2.1.3.1.1.2/public
 */
var multiSnmpServer = function (value) {
    var str = 
        '^('
            +'('
                +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\\.'
                +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\\.'
                +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\\.'
                +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])'
            +')'               // 192.168.10.254
            +'[\\/]'           // /
            +'[\\.\\d]+'       // .1.3.6.1.2.1.3.1.1.2
            +'[\\/]'           // /
            +'[\\w]+'           // public
            +'[\\n]?'
        +')*$'
    ;
    var regular = new RegExp(str);
    return (regular.test(value));
};

/**
 * @brief 匹配多行 number
 * 
 * 支持以下格式： 
 *   1234
 *   100-200
 */
var multiRangeNum = function (value) {
    var str = 
        '^('
            +'('
                + '\\d'
            +')'
            +'('
                + '-'
                + '\\d'
            +')?'
            +'[\\n]?'
        +')*$'
    ;
    var regular = new RegExp(str);
    return (regular.test(value));
};

/**
 * @brief 匹配多行IP (简易版，仅支持简单的IP地址格式)
 * 
 * 支持以下格式： 
 *   192.168.1.99 
 */
var multiIPSimple = function (value) {
    var str = 
        '^('
            +'('
                +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\\.'
                +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\\.'
                +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\\.'
                +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])'
            +')'
            +'[\\n]?'
        +')*$'
    ;
    var regular = new RegExp(str);
    return (regular.test(value));
};

/**
 * @brief 匹配多行IP
 * 
 * 支持以下格式： 
 *   #192.168.1.2 
 *   192.168.1.99 
 *   192.168.1.5-192.168.1.9 
 *   192.168.1.10/255.255.255.0 
 *   192.168.1.13/24 
 */
var multiIP = function (value) {
    var str = 
        '^('
            /* 注释这里，因为暂时不支持带#的注释 "#192.168.1.1" */
            // +'[#]?'

            +'('

                +'('
                    +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\\.'
                    +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\\.'
                    +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\\.'
                    +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])'
                +')'

                +'('
                    +'('
                        +'[-\\/]'
                        +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\\.'
                        +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\\.'
                        +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\\.'
                        +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])'
                    +')'
                    /* 注释这里，因为暂时不支持 "192.168.1.1/24" 这样的格式*/
                    // +'|'
                    // +'('
                    //     +'[\\/]'
                    //     +'(3[0-2]|[0-2]?[0-9])'
                    // +')'
                +')?'

            +')'
            +'[\\n]*'
        +')*$'
    ;
    var regular = new RegExp(str);
    return (regular.test(value));
};

/**
 * @brief 匹配单行IP/mask 
 * 
 * 支持以下格式：
 *   192.168.1.10/255.255.255.0 
 *   192.168.1.13/24 
 */
var singleIPMask = function (value) { 
    var str = 
        '^'
            +'('

                +'('
                    +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\\.'
                    +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\\.'
                    +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\\.'
                    +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])'
                +')'

                +'('
                    +'('
                        +'[-\\/]'
                        +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\\.'
                        +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\\.'
                        +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\\.'
                        +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])'
                    +')'

                    +'|'
                    +'('
                        +'[\\/]'
                        +'(3[0-2]|[0-2]?[0-9])'
                    +')'
                +')'

            +')'
        +'$'
    ;
    var regular = new RegExp(str);
    return (regular.test(value));
};

var singleIP = function (value) {
    var regular = /^(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])$/;
    return (regular.test(value));
};

var singleMAC = function (value) {
    var regular = /^[A-Fa-f\d]{2}:[A-Fa-f\d]{2}:[A-Fa-f\d]{2}:[A-Fa-f\d]{2}:[A-Fa-f\d]{2}:[A-Fa-f\d]{2}$/;
    return (regular.test(value));
};

var idCard = function (value) {
    if (value.length == 18 && 18 != value.length) return false;
    var number = value.toLowerCase();
    var d, sum = 0, v = '10x98765432', w = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2], a = '11,12,13,14,15,21,22,23,31,32,33,34,35,36,37,41,42,43,44,45,46,50,51,52,53,54,61,62,63,64,65,71,81,82,91';
    var re = number.match(/^(\d{2})\d{4}(((\d{2})(\d{2})(\d{2})(\d{3}))|((\d{4})(\d{2})(\d{2})(\d{3}[x\d])))$/);
    if (re == null || a.indexOf(re[1]) < 0) return false;
    if (re[2].length == 9) {
        number = number.substr(0, 6) + '19' + number.substr(6);
        d = ['19' + re[4], re[5], re[6]].join('-');
    } else d = [re[9], re[10], re[11]].join('-');
    if (!isDateTime.call(d, 'yyyy-MM-dd')) return false;
    for (var i = 0; i < 17; i++) sum += number.charAt(i) * w[i];
    return (re[2].length == 9 || number.charAt(17) == v.charAt(sum % 11));
};

var isDateTime = function (format, reObj) {
    format = format || 'yyyy-MM-dd';
    var input = this, o = {}, d = new Date();
    var f1 = format.split(/[^a-z]+/gi), f2 = input.split(/\D+/g), f3 = format.split(/[a-z]+/gi), f4 = input.split(/\d+/g);
    var len = f1.length, len1 = f3.length;
    if (len != f2.length || len1 != f4.length) return false;
    for (var i = 0; i < len1; i++) if (f3[i] != f4[i]) return false;
    for (var i = 0; i < len; i++) o[f1[i]] = f2[i];
    o.yyyy = s(o.yyyy, o.yy, d.getFullYear(), 9999, 4);
    o.MM = s(o.MM, o.M, d.getMonth() + 1, 12);
    o.dd = s(o.dd, o.d, d.getDate(), 31);
    o.hh = s(o.hh, o.h, d.getHours(), 24);
    o.mm = s(o.mm, o.m, d.getMinutes());
    o.ss = s(o.ss, o.s, d.getSeconds());
    o.ms = s(o.ms, o.ms, d.getMilliseconds(), 999, 3);
    if (o.yyyy + o.MM + o.dd + o.hh + o.mm + o.ss + o.ms < 0) return false;
    if (o.yyyy < 100) o.yyyy += (o.yyyy > 30 ? 1900 : 2000);
    d = new Date(o.yyyy, o.MM - 1, o.dd, o.hh, o.mm, o.ss, o.ms);
    var reVal = d.getFullYear() == o.yyyy && d.getMonth() + 1 == o.MM && d.getDate() == o.dd && d.getHours() == o.hh && d.getMinutes() == o.mm && d.getSeconds() == o.ss && d.getMilliseconds() == o.ms;
    return reVal && reObj ? d : reVal;
    function s(s1, s2, s3, s4, s5) {
        s4 = s4 || 60, s5 = s5 || 2;
        var reVal = s3;
        if (s1 != undefined && s1 != '' || !isNaN(s1)) reVal = s1 * 1;
        if (s2 != undefined && s2 != '' && !isNaN(s2)) reVal = s2 * 1;
        return (reVal == s1 && s1.length != s5 || reVal > s4) ? -10000 : reVal;
    }
};

/**
 * @brief 匹配多行IP 或者 MAC 
 * 
 * 支持以下格式：
 *   192.168.1.10
 *   21:02:03:04:05:06
 */
var multiIPOrMac = function (value) { 
    var str = 
        '^('
            +'('

                +'('
                    +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\\.'
                    +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\\.'
                    +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\\.'
                    +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])'
                +')'
                +'|'
                +'('
                    + '[A-Fa-f\\d]{2}:[A-Fa-f\\d]{2}:[A-Fa-f\\d]{2}:[A-Fa-f\\d]{2}:[A-Fa-f\\d]{2}:[A-Fa-f\\d]{2}'
                +')'

            +')'
            +'[\\n]?'
        +')*$'
    ;
    var regular = new RegExp(str);
    return (regular.test(value));
};

/**
 * @brief 匹配多行IP/MAC 
 * 
 * 支持以下格式：
 *   192.168.1.10/01:02:03:04:05:06
 *   192.168.1.20/21:02:03:04:05:06
 */
var multiIPMac = function (value) { 
    var str = 
        '^('
            +'('

                +'('
                    +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\\.'
                    +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\\.'
                    +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\\.'
                    +'(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])'
                +')'
                +'[\\/]'
                +'('
                    + '[A-Fa-f\\d]{2}:[A-Fa-f\\d]{2}:[A-Fa-f\\d]{2}:[A-Fa-f\\d]{2}:[A-Fa-f\\d]{2}:[A-Fa-f\\d]{2}'
                +')'

            +')'
            +'[\\n]?'
        +')*$'
    ;
    var regular = new RegExp(str);
    return (regular.test(value));
};
