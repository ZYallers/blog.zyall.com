<div id="blog-nav" class="layout">
    <div class="container padding-top padding-bottom">
        <div class="xl12 xs2 xm2 xb2">
            <button class="button icon-navicon float-right" data-target="#blog-nav-header"></button>
            <a href="http://blog.zyall.com" title="ZYall博客于2014年07月由一枚80后名叫zyall的码农爱装逼时候创建，主要是为了记录分享生活学习工作中遇到的认为有价值有用的技术文章。丰富阅历，开阔视野，记录成长过程点滴。">
                <h1 class="margin-small-top">ZYall博客</h1>
                <!--<img class="ring-hover margin-small-top" src="/Public/images/logo-title.png" alt=""/>-->
            </a>
        </div>
        <div class=" xl12 xs10 xm10 xb10 padding-top nav-navicon" id="blog-nav-header">
            <div class="xs8 xm8 xb5">
                <ul class="nav nav-menu nav-inline nav-big">
                    <li class="active"><a href="/">首页</a></li>
                    <li class="hidden-l">
                        <a href="#">分类<span class="arrow"></span></a>
                        <ul class="drop-menu">
                            <?php if ( $hideCate !== true && !empty( $categories ) ): ?>
                                <?php foreach ( $categories as $value ): ?>
                                    <li class="<?php echo $value["category_id"] == $cid ? "active" : ""; ?>">
                                        <a href="/c<?php echo $value["category_id"]; ?>.html"><?php echo $value["name"]; ?></a>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li class=""><a href="#" title="关于ZYall博客">关于</a></li>
                    <li class=""><a target="_blank" href="http://echo.zyall.com" title="ZYall农庄">农庄</a></li>
                </ul>
            </div>
            <div class="xs4 xm4 xb4">
                <form rel="searchBlogForm" action="{:U('Index/index',array('cid'=>$cid))}" method="get">
                    <div class="input-group padding-little-top">
                        <input type="text" class="input border-main" name="search" value="<?php echo $search; ?>" placeholder="搜索博客"/>
                        <span class="addbtn">
                            <button type="submit" class="button bg-main"><i class="icon-search"></i></button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="hidden-s hidden-l hidden-m xb2 xb1-move">
                <div class="xl6 xb12">
                    <i class='icon-qq text-main'></i>&nbsp;<a target="_blank" title="QQ在线交流" href="http://wpa.qq.com/msgrd?v=3&uin=1308565859&site=blog.zyall.com&menu=yes">1308565859</a>
                </div>
                <div class="xl6 xb12 text-small">
                    <i class="icon-home text-main"></i>&nbsp;<a href="#" class="win-homepage">设为首页</a>&nbsp;
                    <i class="icon-star text-main"></i>&nbsp;<a href="#" class="win-favorite">加入收藏</a>
                </div>
            </div>
        </div>
    </div>
</div>