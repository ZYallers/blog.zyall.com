<!--<extend name="Layout:base"/>-->
<block name="title">分类管理_ZYall博客</block>
<block name="content">
    <div class="panel">
        <div class="panel-head bg-main"><h3><a href="javascript:void();" class="bg-inverse win-back icon-arrow-left"></a> 分类管理</h3></div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <tbody>
                    <tr>
                        <th>分类名</th>
                        <th>创建时间</th>
                        <th>更新时间</th>
                        <th>博客数量</th>
                        <th>操&nbsp;&nbsp;作</th>
                    </tr>
                    <?php if ( !empty( $categories ) ): ?>
                        <?php foreach ( $categories as $cate ): ?>
                            <tr>
                                <td><?php echo $cate['name']; ?></td>
                                <td><?php echo date( 'm/d H:i', $cate['create_time'] ); ?></td>
                                <td><?php echo date( 'm/d H:i', $cate['update_time'] ); ?></td>
                                <td>
                                    <a href="<?php echo U( 'Index/index', array( "cid" => $cate["category_id"] ) ); ?>">
                                        <span rel="cnumber"><?php echo $cate['number']; ?></span>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?php echo U( 'Blog/editcate', array( "cid" => $cate["category_id"] ) ); ?>" title="编辑"><i class="icon-pencil"></i></a>
                                    &nbsp;
                                    <a href="javascript:;" rel="delete" methods="click" title="删除" data-cid="<?php echo $cate["category_id"]; ?>"><i class="icon-trash-o"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align: center;">暂无分类</td></tr>   
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</block>
<block name="js">
    <script>
        $( function( ) {
            var web = new dom.web( {
                event: {
                    deleteClick: function() {
                        var self = $( this ), cateId = self.data( 'cid' );
                        if ( cateId ) {
                            var cnumber = self.parent().parent().find( '[rel=cnumber]' ).text();
                            if ( cnumber > 0 ) {
                                dom.tip( '该分类下还有博客，不能删除。' );
                                return false;
                            } else {
                                dom.confirm( '您确定要删除吗？', function( ) {
                                    dom.loading();
                                    $.post( "{:U('Blog/delcate')}", {cid: cateId}, function( result ) {
                                        dom.finish();
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
                }
            } );
        } );
    </script>
</block>