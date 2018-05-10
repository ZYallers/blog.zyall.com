/**
 * dom工具扩展
 */
( function( ) {
    dom.util = {
        /**
         * 是否为IE浏览器
         * @return {Boolean}
         */
        isIE: function( ) {
            return ( document.all && window.ActiveXObject && !window.opera ) ? true : false;
        },
        /**
         * 判断值是否未定义， 未定义时返回true
         * @param {Any} any  
         * @return {Boolean}
         */
        isUndefined: function( obj ) {
            return 'undefined' === typeof obj;
        },
        /**
         * 获取url中参数段
         * @param  {String} url地址 [url=window.location.href]
         * @return {Object} 参数集
         */
        getUrlParam: function( url ) {
            var result = {}, index, str;
            url = url || window.location.href;
            index = url.indexOf( "?" );
            str = url.substr( index + 1 );
            $.each( str.split( "&" ), function( index, s ) {
                var kv = s.split( "=" );
                result[kv[0]] = kv[1];
            } );
            return result;
        },
        /**
         * 将url解析为键值对
         * @param  {String} url url地址
         * @return {Object} 参数键值对
         */
        url2Obj: function( url ) {
            var params, data = {};
            if ( url ) {
                params = url.split( "&" );
                for ( var i = 0, len = params.length; i < len; i++ ) {
                    var kv = params[i].split( "=" );
                    data[kv[0]] = kv[1];
                }
            }
            return data;
        },
        /**
         * 用于将经过Jquery系列化的表单数组转化为对象
         * @param  {Array} array  系列化后得到的数组
         * @return {Object}       解析后的对象
         */
        serialized2Object: function( array ) {
            var data = {};
            for ( var i = 0; i < array.length; i++ ) {
                data[array[i].name] = array[i].value;
            }
            return data;
        },
        /**
         * 首字母大写
         * @param {string} word
         * @return {string}
         */
        upFirstLetter: function( word ) {
            return word.toLowerCase( ).replace( /\b(\w)|\s(\w)/g, function( m ) {
                return m.toUpperCase( );
            } );
        },
        /**
         * 类似PHP的trim方法
         * @param {string} str
         * @param {string} find
         * @return {string}
         */
        trim: function( str, find ) {
            find = find || " ";
            var top = new RegExp( "^[" + find + "]*", "g" ), last = new RegExp( "[" + find + "]*$", "g" );
            return str.replace( top, '' ).replace( last, '' );
        },
        /**
         * 字符串格式化方法
         * @params null
         * @example '我是{0}'.format('CoderQ') => '我是CoderQ'; '我是{name}'.format({name:'CoderQ'}) => '我是CoderQ';
         * @depends null
         * @return string
         */
        format: function( ) {
            var args = ( arguments[0] instanceof Array || 'object' === typeof arguments[0] ) ? arguments[0] : arguments;
            return args.replace( /\{(\w+)(\|([^}]+))?\}/g, function( $, $1, $2, $3 ) {
                return undefined === args[$1] ? $3 : args[$1];
            } );
        },
        /**
         * 停止默认的行为
         * @param {wiondow.Event} e
         * @return {Boolean}
         */
        stopDefault: function( e ) {
            if ( e && e.preventDefault ) {
                e.preventDefault( );
            } else {
                window.event.returnValue = false;
            }
            return false;
        },
        /**
         * 获取浏览器当前窗口大小
         * @return {object}
         * @url http://www.cnblogs.com/quanhai/archive/2010/04/16/1713124.html
         */
        getWindowSize: function( ) {
            var winWidth = 0, winHeight = 0;
            //获取窗口宽度
            if ( window.innerWidth ) {
                winWidth = window.innerWidth;
            } else if ( document.body && document.body.clientWidth ) {
                winWidth = document.body.clientWidth;
            }
            //获取窗口高度
            if ( window.innerHeight ) {
                winHeight = window.innerHeight;
            } else if ( document.body && document.body.clientHeight ) {
                winHeight = document.body.clientHeight;
            }
            //通过深入Document内部对body进行检测，获取窗口大小
            if ( document.documentElement && document.documentElement.clientHeight && document.documentElement.clientWidth ) {
                winHeight = document.documentElement.clientHeight;
                winWidth = document.documentElement.clientWidth;
            }
            return {width: winWidth, height: winHeight};
        },
        /**
         * 过滤HTML标签以及&nbsp;
         * @param {type} str
         * @returns {unresolved}
         */
        removeHtmlTag: function( str ) {
            str = str.replace( /<\/?[^>]*>/g, '' ); //去除HTML tag
            str = str.replace( /[ | ]*\n/g, '\n' ); //去除行尾空白
            //str = str.replace(/\n[\s| | ]*\r/g,'\n'); //去除多余空行
            str = str.replace( /&nbsp;/ig, '' ); //去掉&nbsp;
            return str;
        },
        /**
         * 序列化对象元素
         * @example
         * <code>
         * serialize( {name:'xiaoli',age:12} );//return 'name=xiaoli&age=12'
         * </code>
         * @param {type} obj
         * @returns {String}
         */
        serialize: function( obj ) {
            var _tmp = [];
            for ( var _o in obj ) {
                switch ( typeof obj[_o] ) {
                    case 'object':
                        if ( obj[_o] instanceof Array ) {
                            _tmp.push( _o + '=[' + obj[_o].join( ',' ) + ']' );
                        }
                    case 'string':
                    case 'number':
                    default:
                        _tmp.push( _o + '=' + obj[_o] );
                        break;
                }
            }
            return _tmp.join( '&' );
        },
        /**
         * 下划线+小写字母转为大写字母
         * @param {string} str
         * @example <code>'_coder_q'.toUpperFirstLetter() => 'CoderQ'</code>
         * @returns {string}
         */
        underline2upperCase: function( str ) {
            return str.replace( /\_([a-z])/g, function( $, $1 ) {
                return $1.toUpperCase( );
            } );
        },
        /**
         * 大写字母转为下划线+小写字母
         * @param {string} str
         * @example '_coder_q'.toUpperFirstLetter() => 'CoderQ'
         * @returns {string}
         */
        upperCase2underline: function( str ) {
            return str.replace( /[A-Z]/g, function( $, $1 ) {
                return '_' + $1.toLowerCase( );
            } );
        },
        /**
         * 16进制转2进制
         * @params {string} hex
         * @example hex2bin(0xa1) => "¡"
         * @return string
         */
        hex2bin: function( hex ) {
            var _ary = hex.split( '' ), _bin = '';
            for ( var i = 0, l = _ary.length; i < l; i += 2 ) {
                _bin += String.fromCharCode( '0x' + _ary[i] + _ary[i + 1] );
            }
            return _bin;
        },
        /**
         * 判断数组中是否存在特定元素
         * @params {string} search 特定元素
         * @params {array} array 数组
         * @example in_array(1，[1, 2]) => true;
         * @return boolean
         */
        in_array: function( search, array ) {
            var i = 0, len = array.length;
            while ( i < len && search !== array[i] ) {
                i++;
            }
            return i < len;
        },
        /**
         * 根据日期获取日历
         * @param {number} timestamp 时间戳
         * @example getCalendar()
         * @return object
         */
        getCalendar: function( timestamp ) {
            var calendar = {}, d = new Date( timestamp || new Date().getTime() ),
                    year = d.getFullYear( ), month = d.getMonth( ) + 1, date = d.getDate( );
            var leapYear = ( ( year % 4 == 0 && year % 100 != 0 ) || ( year % 400 == 0 ) ) ? true : false;
            var monthDayCount = [31, leapYear ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
            var day = d.getDay( );
            var firstDay = ( 8 - ( date - day ) % 7 ) % 7;
            var lastDay = monthDayCount[month - 1] + firstDay - 1;
            calendar.year = year;
            calendar.month = month;
            calendar.date = date;
            calendar.calendar = [];
            for ( var i = 0; i < firstDay; i++ ) {
                var _m = month === 1 ? 12 : month - 1;
                calendar.calendar.push( {
                    year: _m === 12 ? year - 1 : year,
                    month: _m,
                    date: monthDayCount[_m - 1] - firstDay + i + 1
                } );
            }
            for ( var i = firstDay; i <= lastDay; i++ ) {
                calendar.calendar.push( {
                    year: year,
                    month: month,
                    date: i - firstDay + 1
                } );
            }
            for ( var i = lastDay + 1; i < 42; i++ ) {
                var _m = month === 12 ? 1 : month + 1;
                calendar.calendar.push( {
                    year: _m === 1 ? year + 1 : year,
                    month: _m,
                    date: i - lastDay
                } );
            }
            return calendar;
        },
        /**
         * 时间格式化函数
         * @param {number} timestamp 时间戳 默认是当前时间
         * @params {string} format 格式
         * @params {string} language 语言 默认是zh
         * @example new Date().format('Y-m-d H:i:s')
         * @depends toString
         * @return string
         */
        dateFormat: function( timestamp, format, lang ) {
            var d = new Date( timestamp || new Date( ).getTime( ) ), _format = format || '', _lang = lang || 'zh';
            var months = {
                'zh': ['一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '十一', '十二'],
                'en': ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            };
            var days = {
                'zh': ['日', '一', '二', '三', '四', '五', '六'],
                'en': ['Sun', 'Mon', 'Tues', 'Wed', 'Thur', 'Fri', 'Sat']
            };
            var year = d.getFullYear( ), month = d.getMonth( ) + 1, date = d.getDate( ),
                    day = d.getDay( ), hour = d.getHours( ), minute = d.getMinutes( ), second = d.getSeconds( );
            return _format.replace( /Y/g, year )
                    .replace( /y/g, year.toString( ).substr( 2 ) )	//如果不想用toString，也可以直接将_year.toString()替换为(''+_year)
                    .replace( /M/g, months[_lang][month - 1] )
                    .replace( /m/g, month > 9 ? month : '0' + month )
                    .replace( /n/g, month )
                    .replace( /D/g, days[_lang][day] )
                    .replace( /d/g, date > 9 ? date : '0' + date )
                    .replace( /j/g, date )
                    .replace( /H/g, hour > 9 ? hour : '0' + hour )
                    .replace( /G/g, hour )
                    .replace( /h/g, ( hour % 12 ) > 9 ? ( hour % 12 ) : '0' + ( hour % 12 ) )
                    .replace( /g/g, hour % 12 )
                    .replace( /i/g, minute > 9 ? minute : '0' + minute )
                    .replace( /s/g, second > 9 ? second : '0' + second );
        },
        /**
         * 获取href参数
         * @param {type} key
         * @returns {dom.util.getQueries.item}
         */
        getQueries: function( key ) {
            var href = window.location.href, queries = null, query = href.split( '?' )[1];
            if ( undefined !== query ) {
                return null;
            }
            queries = query.split( '&' );
            switch ( typeof key ) {
                case 'string':
                    for ( var i = 0; i < queries.length; i++ ) {
                        var item = queries[i].split( '=' );
                        if ( key === item[0] ) {
                            return item[1];
                        }
                    }
                    return null;
                    break;
                case 'array':
                    var ret_ary = {};
                    for ( var i = 0; i < queries.length; i++ ) {
                        var item = queries[i].split( '=' );
                        if ( key === item[0] ) {
                            ret_ary[item[0]] = item[1];
                        }
                    }
                    return ret_ary;
                    break;
                default:
                    var ret_ary = {};
                    for ( var i = 0; i < queries.length; i++ ) {
                        var item = queries[i].split( '=' );
                        ret_ary[item[0]] = item[1];
                    }
                    return ret_ary;
            }
        },
        /**
         * 获取浏览器userAgent信息
         * @returns {string}
         */
        getBrower: function( ) {
            return navigator.userAgent;
        },
        /**
         * 验证邮箱是否合法
         * @param {type} email
         * @returns {Boolean}
         */
        valiEmail: function( email ) {
            //var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
            var reg = /^[A-Za-zd]+([-_.][A-Za-zd]+)*@([A-Za-zd]+[-.])+[A-Za-zd]{2,5}$/;
            return reg.test( email ) ? true : false;
        },
        /**
         * 验证用户名，只能是英文、数字和下划线
         * @param {type} username
         * @returns {Boolean}
         */
        vailUsername: function( username ) {
            var reg = /^[a-zA-Z0-9_]{1,}$/;
            return reg.test( username ) ? true : false;
        },
        /**
         * 根据屏幕尺寸调整获取弹出框的宽度并返回
         * @returns {integer}
         */
        getDialogWidthByScreen: function() {
            var wh = this.getWindowSize(), wd = wh.width * 0.7;
            if ( wh.width > 1201 ) {
                wd = wh.width * 0.4;
            } else if ( wh.width >= 1001 && wh.width <= 1200 ) {
                wd = wh.width * 0.5;
            } else if ( wh.width >= 761 && wh.width <= 1000 ) {
                wd = wh.width * 0.6;
            } else if ( wh.width <= 760 ) {
                wd = wh.width * 0.8;
            }
            return wd;
        }

    };
} )( window );


