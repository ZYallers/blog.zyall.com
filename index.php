<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 应用入口文件
define( 'APP_DEBUG', false );
// 根目录
define( 'ROOT', dirname( __FILE__ ) . '/' );
// 定义应用目录
define( 'APP_PATH', './App/' );
// 定义运行时目录
define( 'RUNTIME_PATH', './Runtime/' );
// 定义项目模板目录
define( 'TMPL_PATH', APP_PATH . 'View/' );
//如果你的环境足够安全，不希望生成目录安全文件，可以在入口文件里面关闭目录安全文件的生成
//define( 'BUILD_DIR_SECURE', false );
// 绑定Admin模块到当前入口文件，就可以生成Admin模块目录
//define( 'BIND_MODULE', 'Admin' );
//define( 'BUILD_CONTROLLER_LIST', 'Index,User' );
//define( 'BUILD_MODEL_LIST', 'User' );
// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';
// 亲^_^ 后面不需要任何代码了 就是如此简单
