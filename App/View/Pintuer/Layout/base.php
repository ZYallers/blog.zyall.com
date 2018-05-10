<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <title><block name="title"></block></title>
        <block name="keywords"></block>
        <block name="description"></block>
        <if condition="($Think.const.MODULE_NAME neq 'Blog') AND ($Think.const.ACTION_NAME neq 'view')">
            <meta name="keywords" content="ZYall博客,80后,zyall,码农,装逼,记录,分享,生活,学习,工作,有价值,有用,技术文章,丰富阅历,开阔视野,记录成长过程点滴"/>
            <meta name="description" content="ZYall博客于2014年07月由一枚80后名叫zyall的码农爱装逼时候创建，主要是为了记录分享生活学习工作中遇到的认为有价值有用的技术文章。丰富阅历，开阔视野，记录成长过程点滴。"/>
        </if>
        <include file="Layout:head"/>
        <link href="/Public/js/lib/artDialog/css/ui-dialog.css" rel="stylesheet"/>
        <link href="/Public/css/lib/pintuer/pintuer.css" rel="stylesheet"/>
        <link href="/Public/css/common/dom.pintuer.css" rel="stylesheet"/>
        <block name="css"></block>
    </head>
    <body>
        <include file="Layout:header"/>
        <include file="Layout:banner"/>
        <div id="content" class="layout padding-big-top padding-big-bottom">
            <div class="container">
                <div class="line-big">
                    <block name="content"></block>
                </div>
            </div>
        </div>
        <include file="Layout:footer"/>
        <script src="/Public/js/lib/jquery/jquery-1.11.0.min.js"></script>
        <script src="/Public/js/lib/pintuer/pintuer.js"></script>
        <script src="/Public/js/lib/respond/respond-1.4.2.min.js"></script>
        <script src="/Public/js/lib/artDialog/js/dialog-plus.min.js"></script>
        <script src="/Public/js/common/dom.js"></script>
        <block name="js"></block>
        <script src="/Public/js/end.js"></script>
    </body>
</html>