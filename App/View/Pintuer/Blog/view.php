<!--<extend name="Layout:base"/>-->
<block name="keywords">
    <meta name="keywords" content="<?php echo isset($blog['tags']) ? $blog['tags'] : '';?>"/>
</block>
<block name="description">
    <meta name="description" content="<?php echo $blog["title"]; ?>"/>
</block>
<block name="title"><?php echo $blog["title"]; ?>_ZYall博客</block>
<block name="css">
    <link rel="stylesheet" type="text/css" href="/Public/js/lib/ueditor/third-party/SyntaxHighlighter/shCoreDefault.css"/>
    <style>.bread .no-slash:after{content: none;}</style>
</block>
<block name="content">
    <div class="xl12 xm9">
        <ul class="bread padding bg-main bg-inverse">
            <li><a href="/" class="icon-home"></a></li>
            <li class="no-slash"><a href="/c<?php echo $blog["category_id"]; ?>.html"><?php echo $blog["category_name"]; ?></a></li>
            <li class="float-right no-slash"><i class="icon-globe"></i>&nbsp;<?php echo date('Y.m.d H:i', $blog["create_time"]); ?></li>
            <li class="float-right no-slash"><i class="icon-eye"></i>&nbsp;<?php echo $blog["read_times"]; ?></li>
        </ul>
        <div class="panel">
            <div class="panel-head">
                <h1 style="font-size: 16px;font-weight: bold;">
                    <?php echo $blog["title"]; ?>
                    <?php if ( $canEdit ): ?>
                        <a class="float-right" href="<?php echo U( 'Blog/edit', array( "id" => $blog["blog_id"] ) ); ?>"><span class="icon-pencil"></span></a>
                    <?php endif; ?>
                </h1>
            </div>
            <div id="blogBody" class="panel-body">
                <?php echo implode( '', explode('<hr/>', $blog["body"], 2) ); ?>
            </div>
            <div class="panel-foot">
                <div class="button-group button-group-justified">
                    <a href="javascript:void();" rel="zan" methods="click" class="button button-big bg-green bg-inverse ">
                        <i class="icon-thumbs-up"></i>&nbsp;<em><?php echo $blog["zan_times"]; ?></em>
                    </a>
                    <a href="javascript:void();" rel="cai" methods="click" class="button button-big bg-red bg-inverse">
                        <i class="icon-thumbs-down"></i>&nbsp;<em><?php echo $blog["cai_times"]; ?></em>
                    </a>
                </div>
                <div class="bdsharebuttonbox margin-small-top" style="border-top: 1px dotted #ddd;">
                    <i class="icon-share-alt float-left margin-right margin-small-top" style="font-size: 20px;"></i>
                    <a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a>
                    <a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
                    <a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a>
                    <a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a>
                    <a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a>
                    <a href="#" class="bds_more" data-cmd="more"></a>
                </div>
            </div>
        </div>
        <input type="hidden" id="bid" value="<?php echo $blog["blog_id"]; ?>"/>
        <input type="hidden" id="auth" value="<?php echo $loggedUser ? 1 : '' ?>"/>
        <input type="hidden" id="sharePic" value="<?php echo $loggedUser && !empty( $loggedUser["avatar"] ) ? $loggedUser["avatar"] : "Public/images/logo.png"; ?>"/>
        <br/>
    </div>
    <include file="Layout:right"/>
</block>
<block name="js">
    <script src="/Public/js/right.js"></script>
    <script src="/Public/js/lib/ueditor/third-party/SyntaxHighlighter/shCore.js"></script>
    <script>
        $( function( ) {
            SyntaxHighlighter.all();
            var bdPics = '';
            $( '#blogBody' ).find( 'img' ).each( function() {
                if ( $( this ).attr( 'src' ) ) {
                    var src = $( this ).attr( 'src' );
                    bdPics += src.substr( 0, 4 ) !== 'http' ? 'http://' + location.host + src + ';' : src + ';';
                }
            } );
            bdPics += 'http://blog.zyall.com/Public/images/logo.png';
            window._bd_share_config = {
                "common": {
                    "bdSnsKey": {},
                    "bdText": "<?php echo $blog["title"]; ?>_ZYall博客",
                    "bdMini": "2",
                    "bdMiniList": false,
                    "bdPic": bdPics,
                    "bdStyle": "1",
                    "bdSize": "24"},
                "share": {}
                /*,"image":{"viewList":["qzone","tsina","tqq","renren","weixin","sqq"],"viewText":"分享到:","viewSize":"24"}
                 ,"selectShare":{"bdContainerClass":null,"bdSelectMiniList":["qzone","tsina","tqq","renren","weixin","sqq"]}*/
            };
            with ( document )
                0[( getElementsByTagName( 'head' )[0] || body ).appendChild( createElement( 'script' ) ).src = 'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=' + ~( -new Date() / 36e5 )];

            var web = new dom.web( {
                event: {
                    collectClick: function() {
                        var bid = $( "#bid" ).val( );
                        if ( web.util.isLogin( ) && bid > 0 ) {
                            dom.loading( );
                            $.post( "{:U('User/collect')}", {bid: bid}, function( result ) {
                                dom.finish( );
                                if ( result.status > 0 ) {
                                    dom.ok( "收藏成功" );
                                } else if ( result.status === -1 ) {
                                    dom.tip( "您已收藏过了" );
                                } else {
                                    dom.error( "收藏失败" );
                                }
                            }, "json" );
                        }
                    },
                    zanClick: function() {
                        var that = $(this), bid = $( "#bid" ).val( );
                        if ( bid > 0 ) {
                            dom.loading( );
                            $.post( "{:U('User/zan')}", {bid: bid}, function( result ) {
                                dom.finish( );
                                if ( result.status > 0 ) {
                                    dom.ok( "赞成功" );
                                    var em = that.children('em'), old = parseInt(em.text());
                                    that.children('em').text(old+1);
                                } else if ( result.status === -1 ) {
                                    dom.tip( "您已赞过了" );
                                } else {
                                    dom.error( "赞失败了" );
                                }
                            }, "json" );
                        }
                    },
                    caiClick: function() {
                        var that = $(this), bid = $( "#bid" ).val( );
                        if ( bid > 0 ) {
                            dom.loading( );
                            $.post( "{:U('User/cai')}", {bid: bid}, function( result ) {
                                dom.finish( );
                                if ( result.status > 0 ) {
                                    dom.ok( "踩成功" );
                                    var em = that.children('em'), old = parseInt(em.text());
                                    that.children('em').text(old+1);
                                } else if ( result.status === -1 ) {
                                    dom.tip( "您已踩过了" );
                                } else {
                                    dom.error( "踩失败了" );
                                }
                            }, "json" );
                        }
                    }
                },
                util: {
                    isLogin: function( ) {
                        if ( $( "#auth" ).val( ) === "" ) {
                            dom.tip( "请先登录" );
                            return false;
                        }
                        return true;
                    }
                }
            } );
        } );
    </script>
</block>