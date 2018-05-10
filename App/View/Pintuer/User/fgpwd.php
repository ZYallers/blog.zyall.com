<extend name="Layout:base"/>
<block name="title"><title>忘记密码_ZYall博客</title></block>
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
                <li class="active"><a href="#">忘记密码</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{:U('Index/index')}">返回首页</a></li>
            </ul>
        </div>
    </div>
    <form id="form" action="{:U('User/login')}" method="post" class="col-xs-5">
        <div class="form-group">
            <label>用户名：</label>
            <input type="text" class="form-control" rel="account" placeholder="请输入您要找回密码的用户名"/>
        </div>
        <div class="form-group">
            <label>验证码：</label>
            <div class="input-group">
                <input type="text" class="form-control" rel="verify" placeholder="验证码"/>
                <span class="input-group-addon" style="padding: 0; border: none;" title="点击切换"><img rel="changeVerify" methods="click" src="{:U('User/verify')}" alt="验证码"/></span>
            </div>
        </div>
        <button type="button" rel="submit" methods="click" class="btn col-xs-3 btn-primary">找回密码</button>
    </form>
</block>
<block name="js">
    <script>
        $( function() {
            var web = new dom.web( {
                event: {
                    changeVerifyClick: function( ) {
                        $( '[rel=changeVerify]' ).attr( 'src', "{:U('User/verify')}" );
                    },
                    submitClick: function() {
                        var account = $( '[rel=account]' ).val();
                        var verify = $( '[rel=verify]' ).val();
                        if ( account && verify ) {
                            dom.loading();
                            dom.ajax( {
                                url: "{:U('User/findpwd')}",
                                data: {account: account, verify: verify},
                                success: function( result ) {
                                    dom.finish();
                                    if ( result.status > 0 ) {
                                        web.event.changeVerifyClick();
                                        dom.ok( '修改密码链接以发送到你的注册邮箱，请登录邮箱完成找回密码' );
                                    } else if ( result.status === -1 ) {
                                        web.event.changeVerifyClick();
                                        dom.tip( '用户名【' + account + '】不存在' );
                                    } else if ( result.status === -2 ) {
                                        web.event.changeVerifyClick();
                                        dom.tip( '验证码验证错误' );
                                    } else {
                                        dom.error( '网络出错，请重试一次' );
                                    }
                                }
                            } );
                        } else {
                            dom.tip( '请输入用户名和验证码' );
                        }
                    }
                }
            } );
        } );
    </script>
</block>