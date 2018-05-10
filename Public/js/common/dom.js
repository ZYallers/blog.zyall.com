/**
 * DOM - 公共方法
 * @author zyb
 * @updated 2014.09.12
 * @depend jQuery
 */
( function( ) {
    /**
     * DOM对象
     * @type object
     */
    dom = {
        /**
         * 默认语言
         */
        language: 'zh-CN',
        /**
         * 基础路径
         */
        basePath: '/Public/js/common/',
        /**
         * 开启调试模式
         */
        debug: false,
        /**
         * 初始化
         * @returns {object}
         */
        init: function() {
            var dom = this;
            dom.loadJS( dom.basePath + 'dom.lang.' + dom.language + '.js', function( ) {
                dom.loadJS( dom.basePath + 'dom.util.js', function( ) {
                    dom.loadJS( dom.basePath + 'dom.plugin.js' );
                } );
            } );
            return dom;
        },
        /**
         * 加载js文件
         * @param {type} url
         * @param {type} success
         * @param {type} async
         * @param {type} cache
         * @returns {undefined}
         */
        loadJS: function( url, success, async, cache ) {
            var dom = this;
            dom.ajax( {
                type: 'GET',
                url: url,
                dataType: 'script',
                async: 'undefined' === typeof async ? false : async,
                cache: dom.debug ? false : ( 'undefined' === typeof cache ? true : cache ),
                success: function( result, status ) {
                    'success' === status && typeof success === 'function' && success( );
                }
            } );
        },
        /**
         * 类似jq的extend方法
         * @returns {target}
         */
        extend: function( ) {
            // copy reference to target object
            var target = arguments[0] || {}, i = 1, length = arguments.length, deep = false, options;
            // Handle a deep copy situation  /*如果第一个参数为boolean值，则取第二个参数为目标对象*/
            if ( target.constructor == Boolean ) {
                deep = target;
                target = arguments[1] || {};
                // skip the boolean and the target
                i = 2;
            }
            // Handle case when target is a string or something (possible in deep copy)
            /*如果目标参数不是object或者function，那么就有可能是深度copy,*/
            if ( typeof target != "object" && typeof target != "function" ) {
                target = {};
            }
            // extend jQuery itself if only one argument is passed 
            /*如果参数长度为1，则将参数表示的对象的属性和方法复制给this本身*/
            if ( length == i ) {
                target = this;
                --i;
            }
            for ( ; i < length; i++ ) {
                // Only deal with non-null/undefined values当参数都为非空时，
                if ( ( options = arguments[ i ] ) != null ) {
                    // Extend the base object
                    for ( var name in options ) {
                        var src = target[ name ], copy = options[ name ];
                        // Prevent never-ending loop /*防止死循环*/
                        if ( target === copy ) {
                            continue;
                        }
                        // Recurse if we're merging object values/*深度继承的实现*/
                        if ( deep && copy && typeof copy == "object" && !copy.nodeType ) {
                            target[ name ] = this.extend( deep,
                                    // Never move original objects, clone them
                                    src || ( copy.length != null ? [] : {} )
                                    , copy );
                            // Don't bring in undefined values  /*正常情况下的继承实现*/
                        } else if ( copy !== undefined ) {
                            target[ name ] = copy;
                        }
                    }
                }
            }
            // Return the modified object
            return target;
        },
        /**
         * 绑定事件
         * @param {object} event 绑定方法集
         * @param {jq} jq 绑定检索对象集
         * @param {string} attr 检索属性
         * @returns {void}
         */
        on: function( event, jq, attr ) {
            var node = jq || $( '[methods]' );
            attr = attr || 'rel';
            node.each( function( ) {
                var self = $( this ), methods = self.attr( 'methods' );
                if ( methods ) {
                    var _methods = methods.split( ',' );
                    for ( var step = 0, len = _methods.length; step < len; step++ ) {
                        var method = _methods[step];
                        if ( method ) {
                            var func = self.attr( attr ) + method.substring( 0, 1 ).toUpperCase( ) + method.substring( 1 );
                            if ( typeof event[func] === 'function' ) {
                                //console && console.log( func );
                                if ( typeof self.on === 'function' ) { //有些低版本jquery没有on方法
                                    self.on( method, event[func] );
                                } else {
                                    self.bind( method, event[func] );
                                }
                            }
                        }
                    }
                }
            } );
        },
        /**
         * jQuery.ajax方法封装
         * @param {object} options
         * @returns {void}
         */
        ajax: function( options ) {
            //console.log( new Date().getTime(), options );
            if ( '' == options.url || 'undefined' == typeof options.url ) {
                return false;
            }
            var dom = this;
            options = dom.extend( {
                type: 'POST',
                url: '',
                dataType: 'json',
                async: true,
                cache: dom.debug ? false : true,
                processData: true,
                complete: function( XMLHttpRequest, textStatus ) {
                    //this; //调用本次AJAX请求时传递的options参数
                },
                error: function( XMLHttpRequest, textStatus, errorThrown ) {
                    //通常 textStatus 和 errorThrown 之中只有一个会包含信息
                    //this; //调用本次AJAX请求时传递的options参数
                    throw Error( 'Request url:"' + this.url + '" ' + textStatus + ' Error: ' + errorThrown );
                },
                success: function( data, textStatus ) {
                    // data 可能是 xmlDoc, jsonObj, html, text, 等等...
                    //this; //调用本次AJAX请求时传递的options参数
                }
            }, options );
            $.ajax( options );
        },
        web: function( options ) {
            this.event = {};
            this.server = {};
            this.render = {};
            this.util = {};
            this.runDelay = 100;
            this.run = function( ) {
                dom.on( this.event );
            };
            dom.extend( true, this, options ); //深度copy
            if ( typeof this.run === 'function' ) {
                var self = this;
                window.setTimeout( function() {
                    self.run();
                }, this.runDelay );
            }
        }
    };
    dom.init();
} )( window );