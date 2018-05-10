$( function() {

    $( '#giveMeMsg' ).length > 0 && $( '#giveMeMsg' ).on( 'click', function() {
        var self = $( this );
        dom.dialog( {
            id: 'giveMeMsgDialog',
            width: dom.util.getDialogWidthByScreen(),
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
    } );

    $( '#giveMeQue' ).length > 0 && $( '#giveMeQue' ).on( 'click', function() {
        var self = $( this );
        dom.dialog( {
            id: 'giveMeQueDialog',
            width: dom.util.getDialogWidthByScreen(),
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
    } );

    $( '#stateZan' ).length > 0 && dom.ajax( {
        url: $( '#stateZan' ).data( 'uri' ),
        success: function( result ) {
            if ( result.status ) {
                var $zan = $( "#stateZan .list-link" ), list = '';
                if ( $.isEmptyObject( result.data ) ) {
                    list = '<a>暂无热门推荐</a>';
                } else {
                    var viewuri = $( '#stateZan' ).data( 'viewuri' );
                    for ( var key in result.data ) {
                        var blog = result.data[key];
                        var img = 1 == blog['type'] ? '<div class="txt txt-small radius-circle bg-main float-left">' + blog['img'] + '</div>' :
                                '<img src="' + blog['img'] + '" width="32" height="32" class="img-rounded radius-circle bg-main float-left"/>';
                        list += '<a href="' + viewuri + '?id=' + blog["blog_id"] + '" title="' + blog["title"] + '">' +
                                '  <div class="media media-x">' +
                                '    ' + img +
                                '    <div class="media-body padding-small-top overhide">' + blog["title"] + '</div>' +
                                '  </div>' +
                                '</a>\n';
                    }
                }
                $zan.slideUp( 'normal', function() {
                    $zan.html( list );
                    $zan.slideDown();
                } );
            }
        }
    } );

    $( '#stateRead' ).length > 0 && dom.ajax( {
        url: $( '#stateRead' ).data( 'uri' ),
        success: function( result ) {
            if ( result.status ) {
                var $read = $( "#stateRead .list-link" ), list = '';
                if ( $.isEmptyObject( result.data ) ) {
                    list = '<a>暂无热门阅读</a>';
                } else {
                    var viewuri = $( '#stateZan' ).data( 'viewuri' );
                    for ( var key in result.data ) {
                        var blog = result.data[key];
                        var img = 1 == blog['type'] ? '<div class="txt txt-small radius-circle bg-main float-left">' + blog['img'] + '</div>' :
                                '<img src="' + blog['img'] + '" width="32" height="32" class="img-rounded bg-main radius-circle float-left"/>';
                        list += '<a href="' + viewuri + '?id=' + blog["blog_id"] + '" title="' + blog["title"] + '">' +
                                '  <div class="media media-x">' +
                                '    ' + img +
                                '    <div class="media-body padding-small-top overhide">' + blog["title"] + '</div>' +
                                '  </div>' +
                                '</a>\n';
                    }
                }
                $read.slideUp( 'normal', function() {
                    $read.html( list );
                    $read.slideDown();
                } );
            }
        }
    } );

    $( '#archive' ).length > 0 && dom.ajax( {
        url: $( '#archive' ).data( 'uri' ),
        success: function( result ) {
            if ( result.status ) {
                var $archive = $( "#archive .panel-body" ), list = '';
                if ( $.isEmptyObject( result.data ) ) {
                    list = '<a>暂无文章归档</a>';
                } else {
                    var bgcolors = ['main','sub','dot','black','gray','red','yellow','blue','green'];
                    for ( var key in result.data ) {
                        var index = Math.floor( Math.random() * bgcolors.length ), bgcolor = bgcolors[index], row = result.data[key];
                        list += '<a class="button button-little icon-circle badge-corner margin-bottom margin-small-right border-'+bgcolor+'" href="/Index/index?ym=' + row.ym + '">' +
                                '&nbsp;' + row.ym + '<span class="badge bg-'+bgcolor+'">' + row.total + '</span>' +
                                '</a>\n';
                    }
                }
                $archive.slideUp( 'normal', function() {
                    $archive.html( list );
                    $archive.slideDown();
                } );
            }
        }
    } );

    $( '#stateVisit' ).length > 0 && dom.ajax( {
        url: $( '#stateVisit' ).data( 'uri' ),
        success: function( result ) {
            if ( result.status ) {
                var state = result.data;
                var $zan = $( "#stateVisit .list-group li" );
                $zan.eq( 0 ).children( "span" ).text( state["today"] );
                $zan.eq( 1 ).children( "span" ).text( state["yestoday"] );
                $zan.eq( 2 ).children( "span" ).text( state["week"] );
                $zan.eq( 3 ).children( "span" ).text( state["month"] );
                $zan.eq( 4 ).children( "span" ).text( state["all"] );
            }
        }
    } );


    $( '#aboutlink' ).length > 0 && dom.ajax( {
        url: $( '#aboutlink' ).data( 'uri' ),
        success: function( result ) {
            if ( result.status ) {
                var $links = $( "#aboutlink .panel-body" ), list = '';
                if ( $.isEmptyObject( result.data ) ) {
                    list = '<a>暂无相关链接</a>';
                } else {
                    for ( var key in result.data ) {
                        var link = result.data[key];
                        list += '<a class="margin-small-right" target="_blank" href="' + link.url + '" title="' + link.title + '">' +
                                '  <img src="' + link.ico + '" width="32" height="32" class="margin-bottom radius-circle"/>' +
                                '</a>\n';
                    }
                }
                $links.slideUp( 'normal', function() {
                    $links.html( list );
                    $links.slideDown();
                } );
            }
        }
    } );

} );