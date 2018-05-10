<!--<extend name="Layout:base"/>-->
<block name="keywords">
    <meta name="keywords" content="<?php echo $blog['title']; ?>,ZYall博客>"/>
</block>
<block name="description">
    <meta name="description" content="<?php echo $blog['title']; ?>_ZYall博客"/>
</block>
<block name="title">编辑_<?php echo $blog['title']; ?>_ZYall博客</block>
<block name="css">
</block>
<block name="content">
    <form id="editForm" action="{:U('Blog/saveedit')}" method="post">
        <div class="panel">
            <div class="panel-head bg-main">
                <h3><a href="javascript:void();" class="bg-inverse win-back icon-arrow-left"></a> 编辑-<?php echo $blog['title']; ?></h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="label"><font color="red"><b>*&nbsp;</b></font>所属分类</label>
                    <select class="input input-big" style="width: 40%" name="category" rel="category">
                        <?php foreach ( $categories as $item ): ?>
                            <option <?php echo $blog['category_id'] == $item["category_id"] ? 'selected' : ''; ?>
                                value="<?php echo $item["category_id"]; ?>" ><?php echo $item["name"]; ?></option>
                            <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="label"><font color="red"><b>*&nbsp;</b></font>博客标题</label>
                    <input type="text" id="title" value="<?php echo $blog['title']; ?>" name="title" class="input input-big" style="width: 60%;" placeholder="博客标题"/>
                </div>
                <div class="form-group">
                    <label class="label">博客标签</label>
                    <input type="text" id="tags" value="<?php echo $blog['tags']; ?>" name="tags" class="input input-big" style="width: 60%;" placeholder="标签以英文逗号分隔"/>
                </div>
                <div class="form-group">
                    <label class="label"><font color="red"><b>*&nbsp;</b></font>博客内容</label>
                    <script type="text/plain" id="body" name="body" style="width:100%;height:500px;"><?php echo $blog["body"]; ?></script>
                </div>
            </div>
            <div class="panel-foot text-center">
                <button type="button" rel="submit" methods="click" class="button bg-main">保存</button>&nbsp;
                <?php if ( 1 == $blog['status'] ): ?>
                    <button type="button" rel="draft" methods="click" class="button">存草稿</button>&nbsp;
                <?php endif; ?>
                <button type="button" rel="giveup" methods="click" class="button bg-red">放弃</button>
            </div>
            <input type="hidden" id="bid" value="<?php echo $blog['blog_id']; ?>"/>
        </div>
    </form>
</div>
</block>
<block name="js">
    <script src="/Public/js/lib/ueditor/ueditor.config.js"></script>
    <script src="/Public/js/lib/ueditor/ueditor.all.min.js"></script>
    <script src="/Public/js/lib/ueditor/lang/zh-cn/zh-cn.js"></script>
    <script>
        $( function() {
            var web = new dom.web( {
                bodyEditor: null,
                run: function() {
                    $('#blog-nav,#blog-banner').hide();
                    web.bodyEditor = dom.ueditor( 'body' );
                    dom.on( web.event );
                },
                event: {
                    submitClick: function() {
                        web.util.saveBlog( 0 );
                    },
                    draftClick: function() {
                        web.util.saveBlog( 1 );
                    },
                    giveupClick: function() {
                        dom.confirm( '确定放弃吗？', function() {
                            location.href = "{:U('Blog/blogmanage')}";
                        } );
                    }
                },
                util: {
                    saveBlog: function( status ) {
                        var blogId = $( '#bid' ).val();
                        if ( !blogId > 0 ) {
                            return false;
                        }
                        var category = $( "[rel=category]" ).val();
                        var title = $( "#title" ).val();
                        var tags = $( "#tags" ).val();
                        //var summary = $( "#summary" ).val();
                        var body = web.bodyEditor.getContent();
                        if ( category && title && body ) {
                            dom.loading();
                            var data = {blog_id: blogId, category_id: category, title: title, tags: tags, summary: '', body: body, status: status};
                            var $form = $( "#editForm" );
                            $.post( $form.attr( "action" ), {blog: data}, function( result ) {
                                dom.finish();
                                if ( result.status ) {
                                    location.href = "/b" + blogId + ".html";
                                } else {
                                    dom.error( "保存失败，请多尝试保存几次" );
                                }
                            }, "json" );
                        } else {
                            dom.tip( "有必填项未填写！" );
                            return false;
                        }
                    }
                }
            } );
        } );
    </script>
</block>