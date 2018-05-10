<div class="xl12 xm3">
    <div class="panel hidden-l">
        <div class="panel-head bg-main"><h2><i class="icon-user"></i>&nbsp;钟永标</h2></div>
        <div class="panel-body text-center">
            <a class="">
                <img class="img-responsive" src="/Public/images/avatar.png" alt="个人头像">
            </a>
            <?php if ( !$loggedUser ): ?>
                <button id="giveMeMsg" data-uri="{:U('Index/msg')}" class="button button-large bg-black button-block margin-small-top">给我留言</button>
                <div id="giveMeMsgHtml" style="display: none;">
                    <div class="form-group">
                        <label class="label">昵&nbsp;称</label>
                        <input type="text" class="input" name="nickname" placeholder="某总"/>
                    </div>
                    <div class="form-group">
                        <label class="label">留&nbsp;言</label>
                        <textarea class="input" name="msg" placeholder="哎哟，不错哦。" rows="7"></textarea>
                    </div>
                </div>
            <?php else: ?>
                <button id="giveMeQue" data-uri="{:U('User/question')}" class="button button-large bg-green button-block margin-small-top">我有疑问</button>
                <div id="giveMeQueHtml" style="display: none;">
                    <div class="form-group">
                        <label class="label">问题概要</label>
                        <input type="text" class="input" name="title" placeholder="为什么要这样呢？"/>
                    </div>
                    <div class="form-group">
                        <label class="label">细节描述</label>
                        <textarea class="input" name="content" placeholder="我按照某某步骤……" rows="7"></textarea>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <br/>
    <?php if ( $loggedUser ): ?>
        <div class="panel">
            <div class="panel-head bg-main"><h2><i class="icon-cog"></i>&nbsp;管理</h2></div>
            <div class="panel-body" style="padding: 0;">
                <div class="list-link" style="border: none;border-radius: 0;">
                    <a href="{:U('User/addcategory')}">添加分类</a>
                    <a href="{:U('Blog/catemanage')}">分类管理</a>
                    <a href="{:U('Blog/create')}">发布博客</a>
                    <a href="{:U('Blog/blogmanage')}">博客管理</a>
                    <!--<a href="#">我的收藏</a>-->
                    <!--<a href="#">草稿箱管理</a>-->
                    <!--<a href="#">回收站管理</a>-->
                </div>
            </div>
        </div>
        <br/>
    <?php endif; ?>
    <div class="panel">
        <div class="panel-head bg-main"><h2><i class="icon-list"></i>&nbsp;分类</h2></div>
        <div class="panel-body" style="padding: 0;">
            <div class="list-link" style="border: none;border-radius: 0;">
                <?php foreach ( $categories as $value ): ?>
                    <a href="/c<?php echo $value["category_id"]; ?>.html" class="<?php echo $value["category_id"] == $cid ? "active" : ""; ?>">
                        <span class="float-right badge bg-main"><?php echo $value["number"]; ?></span><?php echo $value["name"]; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <br/>
    <div class="panel" id="stateZan" data-uri="{:U('Blog/recommend')}" data-viewuri="{:U('Blog/view')}">
        <div class="panel-head bg-main"><h2><i class="icon-thumbs-up"></i>&nbsp;热门推荐</h2></div>
        <div class="panel-body" style="padding: 0">
            <div class="list-link" style="border: none;border-radius: 0;">
                <a><img src="/Public/images/loader.gif"/></a>
            </div>
        </div>
    </div>
    <br/>
    <div class="panel" id="stateRead" data-uri="{:U('Blog/read')}" data-viewuri="{:U('Blog/view')}">
        <div class="panel-head bg-main"><h2><i class="icon-eye"></i>&nbsp;热门阅读</h2></div>
        <div class="panel-body" style="padding: 0">
            <div class="list-link" style="border: none; border-radius: 0;">
                <a><img src="/Public/images/loader.gif"/></a>
            </div>
        </div>
    </div>
    <if condition="($Think.const.MODULE_NAME eq 'Index') AND ($Think.const.ACTION_NAME eq 'index')">
        <br/>
        <div class="panel" id="archive" data-uri="/Index/archive.html">
            <div class="panel-head bg-main"><h2><i class="icon-paperclip"></i>&nbsp;文章归档</h2></div>
            <div class="panel-body">
                <a><img class="padding-small-bottom" src="/Public/images/loader.gif"/></a>
            </div>
        </div>
        <br/>
        <div class="panel" id="stateVisit" data-uri="{:U('Blog/visit')}">
            <div class="panel-head bg-main"><h2><i class="icon-align-left"></i>&nbsp;阅读统计</h2></div>
            <div class="panel-body" style="padding: 0">
                <ul class="list-group">
                    <li><span class="float-right badge bg-main">0</span>今日</li>
                    <li><span class="float-right badge bg-main">0</span>昨日</li>
                    <li><span class="float-right badge bg-main">0</span>本周</li>
                    <li><span class="float-right badge bg-main">0</span>本月</li>
                    <li><span class="float-right badge bg-main">0</span>所有</li>
                </ul>
            </div>
        </div>
        <br/>
        <div class="panel" id="aboutlink" data-uri="{:U('Index/aboutlink')}">
            <div class="panel-head bg-main"><h2><i class="icon-link"></i>&nbsp;友情链接</h2></div>
            <div class="panel-body">
                <a><img class="padding-small-bottom" src="/Public/images/loader.gif"/></a>
            </div>
        </div>
    </if>
</div>