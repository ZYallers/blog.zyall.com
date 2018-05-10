<extend name="Layout:base"/>
<block name="title"><title>激活账号_ZYall博客</title></block>
<block name="content">
    <div class="navbar navbar-inverse">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">
                <span class="glyphicon glyphicon-lock"></span>
            </a>
        </div>
        <div class="navbar-collapse collapse navbar-inverse-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">激活账号</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{:U('Index/index')}">返回首页</a></li>
            </ul>
        </div>
    </div>
    <?php if ( 1 == $status ): ?>
        <div class="alert alert-success">恭喜您，<b><?php echo $username; ?></b>，您的账号已经成功激活。现<a style="color: white;" href="{:U('User/login')}">马上登录</a>。</div>
    <?php elseif ( 2 == $status ): ?>
        <div class="alert alert-info"><b><?php echo $username; ?></b>，您的账号已经激活过了，无需再次激活。现<a style="color: white;" href="{:U('User/login')}">马上登录</a>。</div>
    <?php elseif ( -1 == $status ): ?>
        <div class="alert alert-warning"><b><?php echo $username; ?></b>账号已被禁用或删除。去<a style="color: white;" href="{:U('User/reg')}">重新注册</a>。</div>
    <?php else: ?>
        <div class="alert alert-danger">抱歉，由于网络故障，激活<b><?php echo $username; ?></b>账号失败了，请马上发邮件到（<a style="color: white;" href="mailto://zyb_icanplay@163.com">zyb_icanplay@163.com</a>）联系管理员请求帮助。</div>
    <?php endif; ?>
</block>