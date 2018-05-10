/**
 * dom扩展控件
 */
( function() {
    var plugin = {
        /**
         * iCheck插件封装
         * @param {jQuery对象} jq
         * @param {type} options
         * @returns {jQuery对象}
         */
        check: function( jq, options ) {
            options = options || {
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%'
            };
            return jq.iCheck( options );
        },
        /**
         * 封装kindEditor富文本编辑器插件
         * @param {type} selector
         * @param {type} options
         * @returns {kindEditor}
         */
        ueditor: function( id, options ) {
            var editor = window.UE.getEditor( id, dom.extend( {
                toolbars: [[
                    'source', //源代码
                    'undo', //撤销
                    'redo', //重做
                    'insertcode', //代码语言
                    'bold', //加粗
                    'indent', //首行缩进
                    'snapscreen', //截图
                    'italic', //斜体
                    'underline', //下划线
                    'strikethrough', //删除线
                    'subscript', //下标
                    'fontborder', //字符边框
                    'superscript', //上标
                    'formatmatch', //格式刷
                    'blockquote', //引用
                    'pasteplain', //纯文本粘贴模式
                    'selectall', //全选
                    'anchor', //锚点
                    'print', //打印
                    'preview', //预览
                    'horizontal', //分隔线
                    'removeformat', //清除格式
                    'time', //时间
                    'date', //日期
                    'unlink', //取消链接
                    'insertrow', //前插入行
                    'insertcol', //前插入列
                    'mergeright', //右合并单元格
                    'mergedown', //下合并单元格
                    'deleterow', //删除行
                    'deletecol', //删除列
                    'splittorows', //拆分成行
                    'splittocols', //拆分成列
                    'splittocells', //完全拆分单元格
                    'deletecaption', //删除表格标题
                    'inserttitle', //插入标题
                    'mergecells', //合并多个单元格
                    'deletetable', //删除表格
                    'cleardoc', //清空文档
                    'insertparagraphbeforetable', //"表格前插入行"
                    'fontfamily', //字体
                    'fontsize', //字号
                    'paragraph', //段落格式
                    'simpleupload', //单图上传
                    'insertimage', //多图上传
                    'edittable', //表格属性
                    'edittd', //单元格属性
                    'link', //超链接
                    'emotion', //表情
                    'spechars', //特殊字符
                    'searchreplace', //查询替换
                    'map', //Baidu地图
                    'gmap', //Google地图
                    'insertvideo', //视频
                    'help', //帮助
                    'justifyleft', //居左对齐
                    'justifyright', //居右对齐
                    'justifycenter', //居中对齐
                    'justifyjustify', //两端对齐
                    'forecolor', //字体颜色
                    'backcolor', //背景色
                    'insertorderedlist', //有序列表
                    'insertunorderedlist', //无序列表
                    'fullscreen', //全屏
                    'directionalityltr', //从左向右输入
                    'directionalityrtl', //从右向左输入
                    'rowspacingtop', //段前距
                    'rowspacingbottom', //段后距
                    'pagebreak', //分页
                    'insertframe', //插入Iframe
                    'imagenone', //默认
                    'imageleft', //左浮动
                    'imageright', //右浮动
                    'attachment', //附件
                    'imagecenter', //居中
                    'wordimage', //图片转存
                    'lineheight', //行间距
                    'edittip ', //编辑提示
                    'customstyle', //自定义标题
                    'autotypeset', //自动排版
                    'webapp', //百度应用
                    'touppercase', //字母大写
                    'tolowercase', //字母小写
                    'background', //背景
                    'template', //模板
                    'scrawl', //涂鸦
                    'music', //音乐
                    'inserttable', //插入表格
                    'drafts', // 从草稿箱加载
                    'charts', // 图表
                ]],
                elementPathEnabled: false,
                saveInterval: 300000,
                maximumWords: 5000,
                zIndex: 1
            }, options ) );
            return editor;
        },
        /**
         * 语法高亮
         * @param {type} jq
         * @returns {jq对象}
         */
        lighter: function( jq ) {
            return jq.highlightSyntax( );
        },
        /**
         * 复制到剪贴板
         * @param {type} jq
         * @param {type} text
         * @returns {undefined}
         */
        zclip: function( jq, text ) {
            jq.zclip( {
                path: '/Public/js/lib/zclip/ZeroClipboard.swf',
                copy: text,
                beforeCopy: function() {
                },
                afterCopy: function() {
                    window.alert( "内容已复制到剪贴板！" );
                }
            } );
        },
        /**
         * 异步上传文件
         * @param {type} options
         * @returns {Boolean}
         */
        ajaxFileUploader: function( options ) {
            return $.ajaxFileUpload( {
                url: options.url,
                secureuri: false,
                fileElementId: options.fileElementId,
                dataType: options.dataType || "json",
                data: options.data || {},
                success: function( data, status ) {
                    typeof options.success === "function" && options.success( data );
                },
                error: function( data, status, e ) {
                    typeof options.error === "function" && options.error( data );
                }
            } );
        },
        /*
         * 封装并调用artDialog弹出框控件
         * @param {object} options 配置
         * @returns {dialog}
         */
        dialog: function( options ) {
            return options ? window.dialog( options ) : window.dialog;
        },
        /**
         * 提示框
         * @param {type} msg
         * @param {type} ok
         * @returns {dialog}
         */
        alert: function( msg, ok ) {
            var d = window.dialog( {
                id: 'alertDialog',
                drag: true,
                width: dom.util.getDialogWidthByScreen(),
                height: 10,
                title: '<i style="font-size:20px;line-height:0;" class="icon-exclamation-circle"></i>',
                content: msg,
                ok: function() {
                    typeof ok === "function" && ok( this );
                },
                okValue: "确定",
                cancelValue: "取消"
            } );
            d.showModal();
            return d;
        },
        /**
         * 对话框
         * @param {type} msg
         * @param {type} ok
         * @param {type} cancel
         * @returns {dialog}
         */
        confirm: function( msg, ok, cancel ) {
            var d = window.dialog( {
                id: 'confirmDialog',
                width: dom.util.getDialogWidthByScreen(),
                height: 20,
                title: '<i style="font-size:20px;line-height:0;" class="icon-question-circle"></i>',
                content: msg,
                ok: function() {
                    typeof ok === "function" && ok( this );
                },
                okValue: "确定",
                cancel: function() {
                    typeof cancel === "function" && cancel( this );
                },
                cancelValue: "取消"
            } );
            d.showModal();
            return d;
        },
        /**
         * 提示信息
         * @param {type} msg
         * @param {type} color
         * @param {type} time
         * @returns {dialog}
         */
        tip: function( msg, color, time ) {
            var d = window.dialog( {id: 'tipDialog', width: dom.util.getDialogWidthByScreen()} );
            var content = "<font style='color: " + ( color || "#737373" ) + ";'>" +
                    "        <i class='icon-info-circle'></i>&nbsp;<b>" + msg + "</b>" +
                    "      <font>";
            d.content( content ).show();
            window.setTimeout( function() {
                d.close();
            }, time || 3000 );
            return d;
        },
        /**
         * 提示操作完成信息
         * @param {type} msg
         * @returns {dialog}
         */
        ok: function( msg ) {
            var d = window.dialog( {id: 'successDialog', width: dom.util.getDialogWidthByScreen()} );
            d.content( "<font style='color: #2f8912;'><i class='icon-check-circle'></i>&nbsp;<b>" + msg + "</b><font>" ).show();
            window.setTimeout( function() {
                d.close();
            }, 2500 );
            return d;
        },
        /**
         * 提示操作出错信息
         * @param {type} msg
         * @returns {dialog}
         */
        error: function( msg ) {
            var d = window.dialog( {id: 'errorDialog', width: dom.util.getDialogWidthByScreen()} );
            d.content( "<font style='color: #cc002e;'><i class='icon-times-circle'></i>&nbsp;<b>" + msg + "</b><font>" ).show();
            window.setTimeout( function() {
                d.close();
            }, 3500 );
            return d;
        },
        /**
         * loading遮盖层
         * @returns {dialog}
         */
        loading: function() {
            var d = window.dialog( {id: "waitDialog", width: 45, height: 45,
                    content: '<img src="/Public/images/ajax-loading.gif">'} ).showModal();
            return d;
        },
        /**
         * 关闭loading遮盖层
         * @returns {void}
         */
        finish: function() {
            var d = window.dialog.get( "waitDialog" );
            d && d.close();
        },
        /**
         * 时间选择插件-datetimepicker
         * @param {type} jq
         * @param {type} options doc：http://www.malot.fr/bootstrap-datetimepicker/
         * @returns {jq}]
         */
        datePicker: function( jq, options ) {
            return jq.datetimepicker( $.extend( {
                format: 'yyyy/mm/dd',
                weekStart: 1,
                autoclose: true,
                startView: 2,
                minView: 2,
                todayHighlight: true,
                language: 'zh-CN'
            }, options ) );
        },
        /**
         * 下拉框插件 - easyDropDown
         * @param {type} jq
         * @param {type} options
         * @returns {jq}
         */
        dropDown: function( jq, options ) {
            return jq.easyDropDown( $.extend( {}, options ) );
        }

    };
    dom.extend( dom, plugin );
} )( window );