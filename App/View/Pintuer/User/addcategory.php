<!--<extend name="Layout:base"/>-->
<block name="title"><title>添加分类_ZYall博客</title></block>
<block name="content">
    <div class="xm8">
        <div class="form-group">
            <label class="label">分类名</label>
            <input type="text" class="input input-big" rel="name" methods="keyup" name="name" value="" placeholder="SQL"/>
        </div>
        <button type="button" rel="submit" methods="click" class="button bg-main">添加</button>&nbsp;
        <button class="button win-back icon-arrow-left"> 返回</button>
    </div>
</block>
<block name="js">
    <script>
        $( function() {
            var web = new dom.web( {
                event: {
                    nameKeyup: function( event ) {
                        if ( event.keyCode === 13 ) {
                            $( '[rel=submit]' ).trigger( 'click' );
                        }
                    },
                    submitClick: function() {
                        var name = $( "[rel=name]" ).val( );
                        if ( name ) {
                            dom.loading();
                            dom.ajax( {
                                url: "{:U('User/addcategory')}",
                                data: {ajax: 1, name: name},
                                success: function( result ) {
                                    dom.finish();
                                    if ( result.status > 0 ) {
                                        $( "#name" ).val( "" ).focus();
                                        dom.ok( "添加成功" );
                                    } else if ( result.status === -1 ) {
                                        $( "#name" ).select().focus();
                                        dom.tip( "分类已存在" );
                                    } else {
                                        dom.error( "添加失败，请重新添加！" );
                                    }
                                }
                            } );
                        } else {
                            dom.tip( "请输入分类名！" );
                        }
                    }
                }
            } );
        } );
    </script>
</block>