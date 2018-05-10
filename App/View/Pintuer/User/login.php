<!--<extend name="Layout:base"/>-->
<block name="title">登录_ZYall博客</block>
<block name="content">
    <form id="loginForm" class="xm8" action="{:U('User/login')}" method="post">
        <div class="form-group">
            <label class="label">账&nbsp;号</label>
            <input type="text" class="input input-big" rel="account" methods="keyup" name="account" placeholder="用户名/邮箱/手机号码"/>
        </div>
        <div class="form-group">
            <label class="label">密&nbsp;码</label>
            <input type="password" class="input input-big" rel="password" methods="keyup" name="password" placeholder="密码"/>
        </div>
        <div class="form-group">
            <button type="button" class="button bg-main" rel="submit" methods="click">登录</button>&nbsp;
            <a href="{:U('User/reg')}">没账号?</a>&nbsp;
            <a href="{:U('User/fgpwd')}">忘记密码?</a>
        </div>
    </form>
</block>
<block name="js">
    <script>
        $( function() {
            var web = new dom.web( {
                event: {
                    accountKeyup: function( event ) {
                        if ( event.keyCode === 13 ) {
                            $( '[rel=submit]' ).trigger( 'click' );
                        }
                    },
                    passwordKeyup: function( event ) {
                        if ( event.keyCode === 13 ) {
                            $( '[rel=submit]' ).trigger( 'click' );
                        }
                    },
                    submitClick: function() {
                        var account = $( "[rel=account]" ).val();
                        var pwd = $( "[rel=password]" ).val();
                        if ( account && pwd ) {
                            dom.loading();
                            dom.ajax( {
                                url: $( "#loginForm" ).attr( "action" ),
                                data: {account: account, pwd: pwd},
                                success: function( result ) {
                                    dom.finish();
                                    if ( result.status > 0 ) {
                                        dom.ok( '登录成功，正在进入……' );
                                        setTimeout( function() {
                                            location.href = "{:U('Index/index')}";
                                        }, 1000 );
                                    } else if ( result.status === -1 ) {
                                        dom.tip( account + "的用户账号未激活" );
                                    } else {
                                        dom.error( "账号或密码错误" );
                                    }
                                }
                            } );
                        } else {
                            dom.tip( "请输入必填项" );
                        }
                    }
                }
            } );
        } );
    </script>
</block>