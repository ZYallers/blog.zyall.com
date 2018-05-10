<!--<extend name="Layout:base"/>-->
<block name="title">博客管理_ZYall博客</block>
<block name="css">
</block>
<block name="content">
    <div class="panel">
        <div class="panel-head bg-main"><h3><a href="javascript:void();" class="bg-inverse win-back icon-arrow-left"></a> 博客管理</h3></div>
        <div class="panel-body">
            <form id="blogManageSearchForm" action="{:U('Blog/blogmanage')}" class="form-inline" method="get">
                <div class="form-group">
                    <div class="field">
                        <div class="input-group">
                            <span class="addon">标题</span>
                            <input type="text" class="input" name="t" size="30" value="<?php echo $title; ?>" placeholder="标题"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="field">
                        <div class="input-group">
                            <span class="addon">分类</span>
                            <select class="input" name="cid">
                                <option value="">分类</option>
                                <?php foreach ( $categories as $value ): ?>
                                    <option <?php echo $category_id == $value["category_id"] ? 'selected' : ''; ?>
                                        value="<?php echo $value["category_id"]; ?>" ><?php echo $value["name"]; ?></option>
                                    <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <!--<div class="form-group">
                    <div class="field">
                        <div class="input-group">
                            <span class="addon">创建时间</span>
                            <input type="text" class="input" name="ct" rel="ct" placeholder="创建时间" value="<?php echo $create_time ? $create_time : ''; ?>"/>
                        </div>
                    </div>
                </div>-->
                <div class="form-group">
                    <button type="submit" class="button">搜索</button>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <tbody>
                    <tr>
                        <th>标题</th>
                        <th style="width: 6%;">状态</th>
                        <th>分类</th>
                        <th style="width: 10%;">创建时间</th>
                        <th style="width: 10%;">更新时间</th>
                        <th>收藏</th>
                        <th>阅读</th>
                        <th>赞数</th>
                        <th>踩数</th>
                        <th>操&nbsp;&nbsp;作</th>
                    </tr>
                    <?php foreach ( $blogs as $key => $value ): ?><?php $$key = $value; ?><?php endforeach; ?>
                    <?php if ( !empty( $list ) ): ?>
                        <?php foreach ( $list as $blog ): ?>
                            <tr>
                                <td>
                                    <a href="<?php echo U( 'Blog/view', array( "id" => $blog["blog_id"] ) ); ?>">
                                        <?php echo $blog['title']; ?>
                                    </a>
                                </td>
                                <td><?php echo 0 == $blog['status'] ? '已发布' : '存草稿'; ?></td>
                                <td><?php echo $blog['catename']; ?></td>
                                <td><?php echo date( 'm/d H:i', $blog['create_time'] ); ?></td>
                                <td><?php echo date( 'm/d H:i', $blog['update_time'] ); ?></td>
                                <td><?php echo $blog['collect_times']; ?></td>
                                <td><?php echo $blog['read_times']; ?></td>
                                <td><?php echo $blog['zan_times']; ?></td>
                                <td><?php echo $blog['cai_times']; ?></td>
                                <td>
                                    <a href="<?php echo U( 'Blog/edit', array( "id" => $blog["blog_id"] ) ); ?>"><span class="icon-pencil"></span></a>
                                    &nbsp;
                                    <a href="javascript:;" rel="delete" methods="click" title="删除" data-bid="<?php echo $blog["blog_id"]; ?>"><span class="icon-trash-o"></span></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="9" style="text-align: center;">暂无数据</td></tr>   
                    <?php endif; ?>
                </tbody>
            </table>
            <div id="blog-list-pager" class="text-center padding">
                <include file="Layout:pager"/>
            </div>
        </div>
    </div>
    <div id="pager" class="text-center">
        <?php echo $blogs['html']; ?>
    </div>
</block>
<block name="js">
    <script>
        $( function( ) {
            var web = new dom.web( {
                run: function() {
                    dom.on( this.event );
                },
                event: {
                    deleteClick: function() {
                        var self = $( this ), blogId = self.data( 'bid' );
                        if ( blogId ) {
                            dom.confirm( '删除后将不可再恢复，您确定要删除吗？', function( ) {
                                $.post( "{:U('Blog/delete')}", {bid: blogId}, function( result ) {
                                    if ( result.status > 0 ) {
                                        dom.ok( '删除成功' );
                                        self.parent().parent().remove();
                                    } else {
                                        dom.error( '删除失败' );
                                    }
                                }, 'json' );
                            } );
                        }
                    }
                }
            } );
        } );
    </script>
</block>