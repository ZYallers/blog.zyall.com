$( function() {
    // banner雪花效果
    if ( !$( '#blog-banner' ).is( ':hidden' ) && 'function' === typeof requestAnimationFrame ) {
        var width, height, canvas, ctx, circles;
        function runCanvas() {
            var banner = document.getElementById( 'blog-banner' );
            width = banner.clientWidth;
            height = banner.clientHeight;

            canvas = document.getElementById( 'canvas' );
            canvas.width = width;
            canvas.height = height;
            ctx = canvas.getContext( '2d' );

            // create particles
            circles = [];
            for ( var x = 0; x < width * 0.5; x++ ) {
                var c = new Circle();
                circles.push( c );
            }
            animate();
        }

        function animate() {
            ctx.clearRect( 0, 0, width, height );
            for ( var i in circles ) {
                circles[i].draw();
            }
            requestAnimationFrame( animate );
        }

        function Circle() {
            var _this = this;
            // constructor
            ( function() {
                _this.pos = {};
                init();
            } )();
            function init() {
                _this.pos.x = Math.random() * width;
                _this.pos.y = height + Math.random() * 100;
                _this.alpha = 0.1 + Math.random() * 0.3;
                _this.scale = 0.1 + Math.random() * 0.3;
                _this.velocity = Math.random();
            }
            this.draw = function() {
                if ( _this.alpha <= 0 ) {
                    init();
                }
                _this.pos.y -= _this.velocity;
                _this.alpha -= 0.0005;
                ctx.beginPath();
                ctx.arc( _this.pos.x, _this.pos.y, _this.scale * 10, 0, 2 * Math.PI, false );
                ctx.fillStyle = 'rgba(255,255,255,' + _this.alpha + ')';
                ctx.fill();
            };
        }
        runCanvas();
    }

    //定时替换banner
    var bannerData = [];
    var switchBannerTitle = function() {
        var container = $( '#blog-banner .container' );
        container.removeClass( 'fadein-bottom' ).addClass( 'fadeout-top' );
        if( !bannerData.length > 0 ){
            dom.ajax( {
                url: $( '#blog-footer' ).data( 'banner' ),
                success: function( result ) {
                    if ( result.status ) {
                        for (var i in result.data) {
                            bannerData.push(result.data[i]['content']);
                        };
                        var b1 = $( '#b1' ), b2 = $( '#b2' ), 
                            index = Math.floor( Math.random() * bannerData.length ), 
                            banner = bannerData[index];
                        if ( banner ) {
                            var arr = banner.split('，');
                            b1.html( arr[0] + '，' );
                            b2.html( arr[1] );
                            container.removeClass( 'fadeout-top' );
                            container.addClass( 'fadein-bottom' );
                        }
                    }
                }
            } );
        }else{
            var b1 = $( '#b1' ), b2 = $( '#b2' ), 
                index = Math.floor( Math.random() * bannerData.length ), 
                banner = bannerData[index];
            if ( banner ) {
                var arr = banner.split('，');
                window.setTimeout(function(){
                    b1.html( arr[0] + '，' );
                    b2.html( arr[1] );
                    container.removeClass( 'fadeout-top' ).addClass( 'fadein-bottom' );
                },1000);
            }
        }
    };
    !$( '#blog-footer' ).data( 'loged' ) && window.setInterval( function() {
        switchBannerTitle();
    }, 10000 );

    //百度统计
    var _hmt = _hmt || [];
    ( function() {
        var hm = document.createElement( "script" );
        hm.src = "//hm.baidu.com/hm.js?aa18d0d57f08cb1076422a66da9de76e";
        var s = document.getElementsByTagName( "script" )[0];
        s.parentNode.insertBefore( hm, s );
    } )();

} );