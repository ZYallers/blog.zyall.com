/**
 * 中文语言包
 */
( function() {
    dom.lang = {
        replace: function( lang, data ) {
            var resource = this[lang];
            for ( var key in data ) {
                resource = resource.replace( '{' + key + '}', data[key] );
            }
            return resource;
        },
        tip: '提示',
        sure: '确定',
        close: "关闭",
        cancel: "取消",
        saveFailed: '保存失败',
        saveOk: '保存成功',
        ok: '成功',
        failed: '失败',
        share: '分享',
        edit: '编辑',
        del: '删除'
    };
} )( window );


