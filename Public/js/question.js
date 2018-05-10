$( function() {
    var web = {
        event: {
            giveMeMsgClick: function() {
                var self = $( this );
                dom.dialog( {
                    id: 'giveMeMsgDialog',
                    width: 550,
                    title: '我的留言',
                    content: $( '#giveMeMsgHtml' ).html(),
                    ok: function() {
                        var container = $( window.dialog.get( "giveMeMsgDialog" ).node );
                        var nickname = container.find( 'input[name=nickname]' ).val();
                        var msg = container.find( 'textarea[name=msg]' ).val();
                        if ( nickname && msg ) {
                            dom.loading();
                            $.post( self.data( 'uri' ), {nickname: nickname, msg: msg}, function( result ) {
                                dom.finish();
                                if ( result.status > 0 ) {
                                    dom.ok( '提交成功，感谢您的宝贵留言' );
                                    return true;
                                } else {
                                    dom.error( '提交失败，请重新提交' );
                                    return false;
                                }
                            }, 'json' );
                        } else {
                            dom.tip( '请输入昵称和留言' );
                            return false;
                        }
                    },
                    okValue: "提交",
                    cancel: function() {
                    },
                    cancelValue: "取消"
                } ).showModal();
            },
            giveMeQueClick: function() {
                var self = $( this );
                dom.dialog( {
                    id: 'giveMeQueDialog',
                    width: 550,
                    title: '我的疑问',
                    content: $( '#giveMeQueHtml' ).html(),
                    ok: function() {
                        var container = $( window.dialog.get( "giveMeQueDialog" ).node );
                        var title = container.find( 'input[name=title]' ).val();
                        var content = container.find( 'textarea[name=content]' ).val();
                        if ( title ) {
                            dom.loading();
                            $.post( self.data( 'uri' ), {title: title, content: content}, function( result ) {
                                dom.finish();
                                if ( result.status > 0 ) {
                                    dom.ok( '提交问题成功' );
                                    return true;
                                } else {
                                    dom.error( '提交失败，请重新提交' );
                                    return false;
                                }
                            }, 'json' );
                        } else {
                            dom.tip( '请至少输入问题概要' );
                            return false;
                        }
                    },
                    okValue: "提交",
                    cancel: function() {
                    },
                    cancelValue: "取消"
                } ).showModal();
            }
        }
    };
    var web = new dom.web( web );
} ); 