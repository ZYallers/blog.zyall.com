<!--<extend name="Layout:base"/>-->
<block name="title">
    <?php if($cid > 0): ?>
        分类“<?php echo $categories[$cid]['name'];?>”的博客_ZYall博客
    <?php elseif(!empty($search)): ?>
        包含“<?php echo $search; ?>”的博客_ZYall博客
    <?php elseif(!empty($ym)): ?>
        <?php if(strpos($ym, '-') !== false): ?>
            <?php $_arr = explode('-', $ym, 2); ?>
            <?php echo $_arr[0].'年'.$_arr[1].'月'; ?>的博客_ZYall博客
        <?php else: ?>
            <?php echo $ym; ?>的博客_ZYall博客
        <?php endif; ?>
    <?php else: ?>
        ZYall博客
    <?php endif; ?>
</block>
<block name="css">
    <!-- <link rel="stylesheet" type="text/css" href="/Public/js/lib/ueditor/third-party/SyntaxHighlighter/shCoreDefault.css"/> -->
    <style type="text/css">
      .blog-list-title{margin-top: 5px;padding-bottom: 5px;overflow: auto;zoom: 1;}
    </style>
</block>
<block name="content">
    <?php foreach ( $blogs as $key => $value ): ?><?php $$key = $value; ?><?php endforeach; ?>
    <div class="xl12 xm9">
        <div class="padding-bottom">
            <div class="navbar bg-main bg-inverse radius">
                <div class="navbar-head">
                    <button class="button bg icon-navicon" data-target="#navbar-bg3"></button>
                    <?php if( $cid > 0 ): ?>
                        <span style="font-size: 20px;fpnt-weight: bold;vertical-align: text-top; padding: 0px 5px;"><?php echo $categories[$cid]['name'];?></span>
                    <?php elseif(!empty($ym)): ?>
                        <span style="font-size: 20px;fpnt-weight: bold;vertical-align: text-top; padding: 0px 5px;"><?php echo $ym;?></span>
                    <?php else: ?>
                        <span class="icon-list" style="font-size: 20px;vertical-align: text-top; padding: 0px 5px;"></span>
                    <?php endif; ?>
                </div>
                <div class="navbar-body nav-navicon" id="navbar-bg3">
                    <ul class="nav nav-inline nav-menu nav-big">
                        <li class="<?php echo $new ? 'active' : ''; ?>"><a href="{:U('Index/index', array('cid'=>$cid,'search'=>$search,'ym'=>$ym,'new'=>1))}">最新</a></li>
                        <li class="<?php echo $zan ? 'active' : ''; ?>"><a href="{:U('Index/index', array('cid'=>$cid,'search'=>$search,'ym'=>$ym,'zan'=>1))}">最赞</a></li>
                        <li class="<?php echo $cai ? 'active' : ''; ?>"><a href="{:U('Index/index', array('cid'=>$cid,'search'=>$search,'ym'=>$ym,'cai'=>1))}">最烂</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <?php if ( !empty( $search ) ): ?>
            <blockquote style="padding: 10px;"><p class="text-green">搜索<b>“<?php echo $search; ?>”</b>：有<b><?php echo $total; ?></b>条相关博客。</p></blockquote>
        <?php endif; ?>
        <?php if ( !empty( $list ) ): ?>
            <div id="blog-list">
                <ul class="list-media list-underline">
                    <?php foreach ( $list as $key => $blog ): ?>
                        <li class="blog-list-li">
                            <div class="media media-x">
                            	<div class="blog-list-title">
	                                <a class="float-left" href="/b<?php echo $blog["blog_id"]; ?>.html">
	                                    <?php if ( 1 == $blog['type'] ): ?>
	                                        <div class="txt txt-big radius-circle bg-gray"><?php echo $blog['catename']; ?></div>
	                                    <?php else: ?>
	                                        <img src="<?php echo $blog['img']; ?>" width="64" height="64" class="radius-circle"/>
	                                    <?php endif; ?>
	                                </a>
	                                <div class="media-body padding-small-top">
	                                    <a href="/b<?php echo $blog["blog_id"]; ?>.html">
	                                        <h3><strong><?php echo $blog["title"]; ?></strong></h3>
	                                    </a>
	                                    <div class="margin-small-top">
			                                <span class="badge bg-main margin-small-right"><i class="icon-eye"></i>&nbsp;<?php echo $blog["read_times"]; ?></span>
			                                <span class="badge bg-green margin-small-right"><i class="icon-thumbs-up"></i>&nbsp;<?php echo $blog["zan_times"]; ?></span>
			                                <span class="badge bg-red margin-small-right"><i class="icon-thumbs-down"></i>&nbsp;<?php echo $blog["cai_times"]; ?></span>
			                                <span class="badge margin-small-right"><i class="icon-globe"></i>&nbsp;<?php echo date( "Y.m.d", $blog["create_time"] ); ?></span>
			                            </div>
	                                </div>
                                </div>
                                <div><?php echo strip_tags($blog["body"], '<br/><a>'); ?></div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div id="blog-list-pager" class="text-center padding">
                    <include file="Layout:pager"/>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-green">
                <!--<span class="close rotate-hover"></span>-->
                <strong><i class='icon-info-circle'></i></strong>
                还没有相关博客。
            </div>
        <?php endif; ?>
        <br/>
    </div>
    <include file="Layout:right"/>
</block>

<block name="js">
    <script src="/Public/js/right.js"></script>
    <!--<script src="/Public/js/lib/ueditor/third-party/SyntaxHighlighter/shCore.js"></script>
    <script>$( function() {SyntaxHighlighter.all();} );</script>-->
</block>