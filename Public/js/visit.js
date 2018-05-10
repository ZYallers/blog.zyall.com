$( function() {
    dom.ajax( {
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
} );