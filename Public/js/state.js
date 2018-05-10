$( function() {
    dom.ajax( {
        url: $( '#stateZan' ).data( 'uri' ),
        success: function( result ) {
            if ( result.status ) {
                var $zan = $( "#stateZan .list-group" ), list = '';
                if ( $.isEmptyObject( result.data ) ) {
                    list = '<a class="list-group-item" style="text-align:center;padding-left: 10px;"><span>暂无推荐</span></a>';
                } else {
                    var viewuri = $( '#stateZan' ).data( 'viewuri' );
                    for ( var key in result.data ) {
                        var blog = result.data[key];
                        list += '<a href="' + viewuri + '?id=' + blog["blog_id"] + '" class="list-group-item" title="' + blog["title"] + '">' +
                                '  <span>' + blog["title"] + '</span>' +
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

    dom.ajax( {
        url: $( '#stateRead' ).data( 'uri' ),
        success: function( result ) {
            if ( result.status ) {
                var $zan = $( "#stateRead .list-group" ), list = '';
                if ( $.isEmptyObject( result.data ) ) {
                    list = '<a class="list-group-item" style="text-align:center;padding-left: 10px;"><span>暂无阅读</span></a>';
                } else {
                    var viewuri = $( '#stateZan' ).data( 'viewuri' );
                    for ( var key in result.data ) {
                        var blog = result.data[key];
                        list += '<a href="' + viewuri + '?id=' + blog["blog_id"] + '" class="list-group-item" title="' + blog["title"] + '">' +
                                '  <span>' + blog["title"] + '</span>' +
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

} );