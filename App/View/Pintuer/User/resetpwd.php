<extend name="Layout:base"/>
<block name="title"><title>重置密码_ZYall博客</title></block>
<block name="content">
    <div class="navbar navbar-inverse">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-lock"></span></a>
        </div>
        <div class="navbar-collapse collapse navbar-inverse-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">重置密码</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{:U('Index/index')}">返回首页</a></li>
            </ul>
        </div>
    </div>
    <form rel="form" action="{:U('User/resetpwd')}" method="post" class="col-xs-5">
        <div class="form-group">
            <label>用户名：</label>
            <input type="text" readonly="true" class="form-control" rel="username" value="{$username}"/>
        </div>
        <div class="form-group">
            <label>新密码：</label>
            <input type="password" class="form-control" rel="password" placeholder="新密码"/>
        </div>
        <button type="button" rel="submit" methods="click" class="btn col-xs-3 btn-primary">提交</button>&nbsp;
        <a class="btn btn-default" href="{:U('User/login')}">马上登录</a>
    </form>
</block>
<block name="js">
    <script>
        $( function() {
            var web = new dom.web( {
                event: {
                    submitClick: function() {
                        var username = $( '[rel=username]' ).val();
                        var pwd = $( '[rel=password]' ).val();
                        if ( pwd ) {
                            if ( pwd.length < 5 ) {
                                dom.tip( '密码最少6个字符安全点' );
                                return false;
                            }
                            dom.loading();
                            dom.ajax( {
                                url: $( '[rel=form]' ).attr( "action" ),
                                data: {username: username, pwd: pwd},
                                success: function( result ) {
                                    dom.finish();
                                    if ( result.status > 0 ) {
                                        dom.ok( "重置密码成功" );
                                    } else {
                                        dom.error( '重置密码失败，请确认信息后再重新提交' );
                                    }
                                }
                            } );
                        } else {
                            dom.tip( "请填写新密码" );
                        }
                    }
                }
            } );
        } );
    </script>
</block>