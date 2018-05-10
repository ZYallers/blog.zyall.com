$( function() {
    var $aboutlink = $( '[rel=aboutlink]' );
    dom.ajax( {
        url: $aboutlink.data( 'uri' ),
        success: function( result ) {
            if ( result.status ) {
                var list = '';
                if ( $.isEmptyObject( result.data ) ) {
                    list = '<li><a style="text-align: center">暂无友情链接</a></li>';
                } else {
                    for ( var key in result.data ) {
                        var link = result.data[key];
                        list += '<a class="button tips" href="' + link.url + '" data-toggle="hover" data-place="top" data-style="bg-main" content="'+link.title+'">RunJS</a>';
                    }
                }
                $aboutlink.slideUp( 'normal', function() {
                    $aboutlink.html( list );
                    $aboutlink.slideDown();
                } );
            }
        }
    } );
} );