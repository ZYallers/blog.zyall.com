$( function() {
    //调整内容宽高
    var wh = dom.util.getWindowSize();
    if( wh.width >= 1902 ){
        var style = 'width:70%';
        $('#navBar').children('div').attr('style', style);
        $('#content').attr('style', style);
    }else if( wh.width > 1024 && wh.width <= 1440 ){
        var style = 'width:80%';
        $('#navBar').children('div').attr('style', style);
        $('#content').attr('style', style);
    }

    // banner雪花效果
    if ( 'function' === typeof requestAnimationFrame ) {
        var width, height, canvas, ctx, circles;
        function runCanvas() {
            var topBar = document.getElementById( 'topBar' );
            width = topBar.clientWidth;
            height = topBar.clientHeight;

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

    dom.ajax( {
        url: $( '#footer' ).data( 'banner' ),
        success: function( result ) {
            var $topBar = $( '#topBar' ), $banner = $( '#banner' );
            if ( result.status ) {
                var bg = result.data.bannerBackground;
                bg && $topBar.css( 'background-color', bg );
                var banner = result.data.banner;
                if ( 'string' === typeof banner ) {
                    $banner.find( 'b' ).remove();
                    $banner.find( 'small' ).html( banner );
                } else {
                    $banner.find( 'b' ).html( banner[0] );
                    $banner.find( 'small' ).html( banner[1] );
                }
                $banner.fadeIn();
            }
        }
    } );

    //搜索框
    $( '[rel=searchBlogShow]' ).on( 'click', function() {
        $( this ).fadeOut( 'fast', function() {
            $( '[rel=searchBlogForm]' ).fadeIn( 'normal', function() {
                $( '[rel=searchKeyword]' ).focus();
            } );
        } );
    } );
    $( '[rel=searchKeyword]' ).on( 'blur', function() {
        $( '[rel=searchBlogForm]' ).fadeOut( 'fast', function() {
            $( '[rel=searchBlogShow]' ).fadeIn();
        } );
    } );

    //百度统计
    var _hmt = _hmt || [];
    (function() {
      var hm = document.createElement("script");
      hm.src = "//hm.baidu.com/hm.js?aa18d0d57f08cb1076422a66da9de76e";
      var s = document.getElementsByTagName("script")[0]; 
      s.parentNode.insertBefore(hm, s);
    })();
    
} );