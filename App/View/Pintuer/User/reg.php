<!--<extend name="Layout:base"/>-->
<block name="title"><title>注册_ZYall博客</title></block>
<block name="content">
    <form id="regForm" action="{:U('User/reg')}" method="post" class="xm8">
        <div class="form-group">
            <label class="label"><font color="red"><b>*&nbsp;</b></font>用户名</label>
            <input type="text" class="input input-big" id="username" name="username" placeholder="Anderson"/>
        </div>
        <div class="form-group">
            <label><font color="red"><b>*&nbsp;</b></font>密&nbsp;&nbsp;&nbsp;码</label>
            <input type="password" class="input input-big" id="password" name="password" placeholder="密码"/>
        </div>
        <div class="form-group">
            <label><font color="red"><b>*&nbsp;</b></font>邮&nbsp;&nbsp;&nbsp;箱</label>
            <input type="text" class="input input-big" id="email" name="email" placeholder="Anderson@126.com"/>
        </div>
        <div class="form-group">
            <button type="button" rel="submit" methods="click" class="button bg-main">提交</button>&nbsp;
            <a href="{:U('User/login')}">马上登录</a>
        </div>
    </form>
</block>
<block name="js">
    <script>
        $( function() {
            var web = new dom.web( {
                event: {
                    submitClick: function() {
                        var username = $( "#username" ).val();
                        var pwd = $( "#password" ).val();
                        var email = $( "#email" ).val();
                        if ( username && pwd && email ) {
                            if ( username.length < 5 ) {
                                dom.tip( '用户名最少6个字符好记点' );
                                return false;
                            }
                            if ( !dom.util.vailUsername( username ) ) {
                                dom.tip( '用户名只能由英文、数字和下划线组成' );
                                return false;
                            }
                            if ( pwd.length < 5 ) {
                                dom.tip( '密码最少6个字符安全点' );
                                return false;
                            }
                            if ( dom.util.valiEmail( email ) ) {
                                dom.tip( "邮箱不合法，请重新输入" );
                                return false;
                            }
                            dom.loading();
                            var $form = $( "#regForm" );
                            var data = {username: username, pwd: pwd, email: email};
                            $.post( $form.attr( "action" ), data, function( result ) {
                                dom.finish();
                                if ( result.status > 0 ) {
                                    dom.ok( "激活邮件已发送到您的邮箱，请登录您的邮箱完成注册" );
                                } else if ( result.status === -1 ) {
                                    dom.tip( "用户名已存在，请重新输入新的用户名" );
                                } else if ( result.status === -2 ) {
                                    dom.tip( "邮箱已被注册过，请重新输入新的邮箱" );
                                } else {
                                    dom.error( '注册失败，请确认信息后再重新提交' );
                                }
                            }, "json" );
                        } else {
                            dom.tip( "请填写必填项" );
                        }
                    }
                }
            } );
        } );
    </script>
</block>