<extend name="Layout:base"/>
<block name="title"><title>完善资料_ZYall博客</title></block>
<block name="content">
    <div class="navbar navbar-inverse">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-user"></span></a>
        </div>
        <div class="navbar-collapse collapse navbar-inverse-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">个人资料</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{:U('Index/index')}"><span class="glyphicon glyphicon-home"></span></a></li>
            </ul>
        </div>
    </div>
    <form id="editForm" enctype="multipart/form-data" action="{:U('User/editinfo')}" 
          method="post" class="col-xs-6" style="padding-bottom: 20px;">
        <div class="form-group">
            <label>真实姓名:</label>
            <input type="text" class="form-control" id="realname" name="realname" value="{$info.real_name}" placeholder="王老五"/>
        </div>
        <div class="form-group clearfix">
            <label>横幅格言(backgorund-color/title):</label>
            <div>
                <?php $banner = json_decode( $info['banner'], true ); ?>
                <input type="text" class="form-control" style="width: 22%;float: left;" id="banner1" value="<?php echo $banner[0]; ?>" placeholder="#2f8912"/>
                <input type="text" class="form-control" style="width: 78%;float: left;" id="banner2" value="<?php echo $banner[1]; ?>" placeholder="将来的你，一定会感激现在拼命的自己"/>
            </div>
        </div>
        <div class="form-group">
            <label>个人头像:</label>
            <div><img style="height: 130px; border: 1px #CCCCCC solid;" src="/{$info.avatar}" title="现在头像"/></div>
            <input type="file" id="avatar" name="avatar"/>
            <p class="help-block">格式：jpg|png；大小不超过500KB。</p>
        </div>
        <div class="form-group">
            <label>所在省份:</label>
            <input type="text" class="form-control" id="province" name="province" value="{$info.province}" placeholder="广东"/>
        </div>
        <div class="form-group">
            <label>所在城市:</label>
            <input type="text" class="form-control" id="city" name="city" value="{$info.city}" placeholder="广州"/>
        </div>
        <div class="form-group">
            <label>现居住址:</label>
            <input type="text" class="form-control" id="address" name="address" value="{$info.address}" placeholder="广东广州白云区棠景街100号"/>
        </div>
        <div class="form-group">
            <label>QQ:</label>
            <input type="text" class="form-control" id="qq" name="qq" value="{$info.qq}" placeholder="46754345"/>
        </div>
        <div class="form-group">
            <label>手机号码:</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{$info.phone}" placeholder="15684265841"/>
        </div>
        <button type="button" rel="submit" methods="click" class="btn col-xs-4 btn-primary">保存</button>
    </form>
</block>
<block name="js">
    <script src="/Public/js/lib/ajaxfileupload/ajaxfileupload.js"></script>
    <script>
        $( function( ) {
            var web = new dom.web( {
                event: {
                    submitClick: function() {
                        dom.loading();
                        var args = {
                            real_name: $( "#realname" ).val( ),
                            banner: $( '#banner1' ).val() + '[_BANNER_]' + $( '#banner2' ).val(),
                            province: $( "#province" ).val( ),
                            city: $( "#city" ).val( ),
                            address: $( "#address" ).val( ),
                            qq: $( "#qq" ).val( ),
                            phone: $( "#phone" ).val( )
                        };
                        var info = "";
                        for ( var i in args ) {
                            info += i + "=" + args[i] + "&";
                        }
                        var avatar = $( "#avatar" ).val();
                        if ( avatar ) {
                            var type = avatar.substr( avatar.lastIndexOf( "." ) + 1 );
                            if ( type !== "jpg" && type !== "png" ) {
                                dom.finish();
                                dom.tip( "头像文件格式不支持！" );
                                return false;
                            }
                        }
                        dom.ajaxFileUploader( {
                            url: "{:U('User/editinfo')}",
                            fileElementId: "avatar",
                            data: {ajax: 1, info: info.substr( 0, info.length - 1 )},
                            success: function( result ) {
                                dom.finish();
                                if ( result.status > 0 ) {
                                    dom.ok( '编辑成功' );
                                    window.setTimeout( function() {
                                        location.reload();
                                    }, 2000 );
                                } else {
                                    dom.error( '保存失败' );
                                }
                            }
                        } );
                    }
                }
            } );
        } );
    </script>
</block>