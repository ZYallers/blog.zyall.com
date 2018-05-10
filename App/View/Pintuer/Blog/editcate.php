<!--<extend name="Layout:base"/>-->
<block name="title">编辑分类[<?php echo $cate['name']; ?>]_ZYall博客</block>
<block name="css">

</block>
<block name="content">
    <div class="xm8">
        <div class="form-group">
            <label class="label">分类名</label>
            <input type="text" class="input input-big" id="name" value="<?php echo $cate['name']; ?>"/>
        </div>
        <div class="form-group">
            <label class="label">博客数量</label>
            <input type="text" class="input input-big" id="number" value="<?php echo $cate['number']; ?>"/>
        </div>
        <div class="form-group">
            <label class="label">排序</label>
            <select class="input input-big tips" data-toggle="hover" data-place="right" title="提示：排在所选分类之前" data-style="bg-green border-green" id="sort">
                <?php foreach ( $categories as $value ): ?>
                    <?php if ( $value['category_id'] != $cate['category_id'] ): ?>
                        <option value="<?php echo $value["category_id"]; ?>" <?php if ( $value["category_id"] == $nextCategories['next'] ): ?>selected="true"<?php endif; ?>
                                ><?php echo $value["name"]; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <input type="hidden" rel="cid" value="<?php echo $cate['category_id']; ?>"/>
            <button type="button" rel="submit" methods="click" class="button bg-main">提交</button>&nbsp;
            <button class="button win-back icon-arrow-left"> 返回</button>
        </div>
    </div>
</block>
<block name="js">
    <script type="text/javascript">
        $( function( ) {
            var web = new dom.web( {
                run: function() {
                    dom.dropDown( $( '#sort' ) );
                    $( '.dropdown' ).css( 'min-width', '374px' );
                    dom.on( web.event );
                },
                event: {
                    submitClick: function() {
                        var catename = $( '#name' ).val();
                        var number = parseInt( $( '#number' ).val() );
                        var sort = $( '#sort' ).val();
                        if ( catename ) {
                            dom.loading();
                            dom.ajax( {
                                url: "{:U('Blog/editcate')}",
                                data: {cid: $( '[rel=cid]' ).val(), catename: catename, number: number, sort: sort},
                                success: function( result ) {
                                    dom.finish();
                                    if ( result.status ) {
                                        dom.ok( '编辑成功' );
                                    } else {
                                        dom.error( '编辑失败' );
                                    }
                                }
                            } );
                        } else {
                            dom.tip( '请填写分类名' );
                        }
                    }
                }
            } );
        } );
    </script>
</block>