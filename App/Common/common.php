<?php

function debug( $args ) {
    header( "Content-type: text/html; charset=utf-8" );
    echo '<pre>';
    print_r( $args );
    echo '</pre>';
    exit;
}

/**
 * 生成随机码
 * @time 2014-05-16
 * @author zyb
 * @param integer $length 默认为8位
 * @return string
 */
function createRandomCode( $length = 8 ) {
    $randomCode = '';
    // 密码字符集，可任意添加你需要的字符
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    for ( $i = 0; $i < $length; $i++ ) {
        // 这里提供两种字符获取方式  
        // 第一种是使用 substr 截取$chars中的任意一位字符；  
        // 第二种是取字符数组 $chars 的任意元素  
        // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);  
        $randomCode .= $chars[mt_rand( 0, strlen( $chars ) - 1 )];
    }
    return $randomCode;
}

/**
 * 获取客户端IP地址
 * @return string
 */
function getIP() {
    if ( @$_SERVER["HTTP_X_FORWARDED_FOR"] )
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    else if ( @$_SERVER["HTTP_CLIENT_IP"] )
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    else if ( @$_SERVER["REMOTE_ADDR"] )
        $ip = $_SERVER["REMOTE_ADDR"];
    else if ( @getenv( "HTTP_X_FORWARDED_FOR" ) )
        $ip = getenv( "HTTP_X_FORWARDED_FOR" );
    else if ( @getenv( "HTTP_CLIENT_IP" ) )
        $ip = getenv( "HTTP_CLIENT_IP" );
    else if ( @getenv( "REMOTE_ADDR" ) )
        $ip = getenv( "REMOTE_ADDR" );
    else
        $ip = "Unknown";
    return $ip;
}

/**
 * 获取本周第一天/最后一天的时间戳 
 * @param string $type 
 * @return integer 
 */
function getWeekTime( $type = 'first' ) {
    /* 获取本周第一天/最后一天的时间戳 */
    $year = date( "Y" );
    $month = date( "m" );
    $day = date( 'w' );
    $nowMonthDay = date( "t" );
    if ( $type == 'first' ) {
        $firstday = date( 'd' ) - $day;
        if ( substr( $firstday, 0, 1 ) == "-" ) {
            $firstMonth = $month - 1;
            $lastMonthDay = date( "t", $firstMonth );
            $firstday = $lastMonthDay - substr( $firstday, 1 );
            $time_1 = strtotime( $year . "-" . $firstMonth . "-" . $firstday );
        } else {
            $time_1 = strtotime( $year . "-" . $month . "-" . $firstday );
        }
        return $time_1;
    } else {
        $lastday = date( 'd' ) + (7 - $day);
        if ( $lastday > $nowMonthDay ) {
            $lastday = $lastday - $nowMonthDay;
            $lastMonth = $month + 1;
            $time_2 = strtotime( $year . "-" . $lastMonth . "-" . $lastday );
        } else {
            $time_2 = strtotime( $year . "-" . $month . "-" . $lastday );
        }
        return $time_2;
    }
}

/**
 * 获取本月第一天/最后一天的时间戳 
 * @param string $type 
 * @return integer 
 */
function getMonthTime( $type = 'first' ) {
    /* 获取本月第一天/最后一天的时间戳 */
    $year = date( "Y" );
    $month = date( "m" );
    $allday = date( "t" );
    if ( $type == 'first' ) {
        $start_time = strtotime( $year . "-" . $month . "-1" );
        return $start_time;
    } else {
        $end_time = strtotime( $year . "-" . $month . "-" . $allday );
        return $end_time;
    }
}

/**
 * 获取文件后缀名
 * @param string $file
 * @return string
 */
function getExtension( $file ) {
    return pathinfo( $file, PATHINFO_EXTENSION );
}

/**
 * 自动创建多级文件夹
 * @param string $path "a/b/c/d/e/f"
 * @return boolean 
 */
function creatDir( $path ) {
    if ( !is_dir( $path ) ) {
        if ( creatDir( dirname( $path ) ) ) {
            mkdir( $path, 0777 );
            return true;
        }
    } else {
        return true;
    }
}

/**
 * 对字符串进行加密和解密
 * @param string $str
 * @param string $oper 操作 DE 解密 || EN加密 默认DE
 * @param string $key 密钥 默认blog.zyall.com
 * @return string
 * @author zyb
 */
function authCode( $str, $oper = 'DE', $key = 'blog.zyall.com' ) {
    vendor( 'Crypt.Crypt#class' );
    $crypt = new Crypt( $key );
    $return = $str;
    if ( 'DE' == $oper ) {
        $return = $crypt->decrypt( $str );
    } else if ( 'EN' == $oper ) {
        $return = $crypt->encrypt( $str );
    }
    return $return;
}

/**
 * 通过PHPMailer发送邮件
 * @param array $emails 接收邮箱
 * @param string $subject 邮件标题
 * @param string $content 邮件内容
 * @param array $set 具体配置
 * <pre>
 * array(
 *     'host' => 'smtp.163.com', 默认等于Yii::app()->params['mailer']['host']
 *     'from' => 'zyb_icanplay@163.com', 默认等于Yii::app()->params['mailer']['from']
 *     'replyto' => 'zyb_icanplay@163.com', // 接收回复的邮箱,默认等于Yii::app()->params['mailer']['replyto']
 *     'fromname' => 'Intexh' // 默认等于Yii::app()->params['mailer']['fromname'] 
 *     'username' => '用户名', // 默认等于Yii::app()->params['mailer']['username'] 
 *     'password' => '用户密码', // 默认等于Yii::app()->params['mailer']['password'] 
 *     'charset' => 'UTF-8', // 默认等于Yii::app()->params['mailer']['charset'] 
 *     'attachment'=>array(
 *                     '附件物理路径' => '附件别名',
 *                     ...
 *                  ); // 默认为空数组
 *      'cc' => array(), // 抄送给？
 *      'bcc' => array(), // 密送给？
 *      'debug' => false, // 默认等于Yii::app()->params['mailer']['debug'] 
 * );
 * </pre>
 * @return boolean 发送结果
 * @author zyb <zyb_icanplay@163.com>
 */
function sendMail( array $emails, $subject, $content, array $set = array() ) {
    if ( empty( $emails ) || empty( $subject ) || empty( $content ) ) {
        return false;
    }
    try {
        vendor( 'Mailer.EMailer' );
        $mailer = new EMailer();
        $mailer->IsSMTP();
        $mailer->SMTPAuth = true;
        $init = array(
            'host' => 'smtp.163.com',
            'from' => 'zyb_icanplay@163.com',
            'replyto' => 'zyb_icanplay@163.com',
            'fromname' => 'blog.zyall.com',
            'username' => 'zyb_icanplay',
            'password' => 'US9SIldiU2pfZwMsUmUCOABpUy5QdAUv',
            'charset' => 'UTF-8',
            'debug' => false,
        );
        $mailer->Host = isset( $set['host'] ) ? $set['host'] : $init['host'];
        $mailer->From = isset( $set['from'] ) ? $set['from'] : $init['from'];
        $mailer->AddReplyTo( isset( $set['replyto'] ) ? $set['replyto'] : $init['replyto']  );
        $mailer->FromName = isset( $set['fromname'] ) ? $set['fromname'] : $init['fromname'];
        $mailer->Username = isset( $set['username'] ) ? $set['username'] : $init['username'];
        $mailer->Password = authCode( isset( $set['password'] ) ? $set['password'] : $init['password']  );
        $mailer->CharSet = isset( $set['charset'] ) ? $set['charset'] : $init['charset'];
        $mailer->SMTPDebug = isset( $set['debug'] ) ? $set['debug'] : $init['debug'];
        foreach ( $emails as $to ) {
            $mailer->AddAddress( $to );
        }
        if ( isset( $set['cc'] ) && is_array( $set['cc'] ) && !empty( $set['cc'] ) ) {
            foreach ( $set['cc'] as $cc ) {
                $mailer->AddCC( $cc );
            }
        }
        if ( isset( $set['bcc'] ) && is_array( $set['bcc'] ) && !empty( $set['bcc'] ) ) {
            foreach ( $set['bcc'] as $bcc ) {
                $mailer->AddBCC( $bcc );
            }
        }
        $mailer->Subject = $subject;
        $mailer->MsgHTML( $content );
        if ( isset( $set['attachment'] ) && is_array( $set['attachment'] ) && !empty( $set['attachment'] ) ) {
            foreach ( $set['attachment'] as $path => $alias ) {
                if ( is_file( $path ) && file_exists( $path && is_readable( $path ) ) ) {
                    $mailer->AddAttachment( $path, $alias );
                }
            }
        }
        //$errorInfo = $mailer->ErrorInfo;
        return $mailer->Send() ? true : false;
    } catch ( Exception $e ) {
        //echo $e->getTraceAsString();
        return false;
    }
}

/**
 * 获取宽字符串长度函数
 * @param string $str 需要获取长度的字符串
 * @param string $charset 字符 默认为UTF-8
 * @return integer
 */
function strLength( $str, $charset = 'UTF-8' ) {
    if ( function_exists( 'mb_get_info' ) ) {
        return mb_strlen( $str, $charset );
    } else {
        return 'UTF-8' == strtoupper( $charset ) ? strlen( utf8_decode( $str ) ) : strlen( $str );
    }
}

/**
 * 宽字符串截字函数
 * @param string $str 需要截取的字符串
 * @param integer $start 开始截取的位置
 * @param integer $length 需要截取的长度
 * @param string $trim 截取后的截断标示符
 * @param string $charset 字符 默认为UTF-8
 * @return string
 */
function cutStr( $str, $start = 0, $length = 200, $trim = "...", $charset = 'UTF-8' ) {
    if ( !strlen( $str ) ) {
        return '';
    }
    $iLength = strLength( $str ) - $start;
    $tLength = $length < $iLength ? ($length - strLength( $trim )) : $length;
    if ( function_exists( 'mb_get_info' ) ) {
        $str = mb_substr( $str, $start, $tLength, $charset );
    } else {
        if ( 'UTF-8' == strtoupper( $charset ) ) {
            if ( preg_match_all( "/./u", $str, $matches ) ) {
                $str = implode( '', array_slice( $matches[0], $start, $tLength ) );
            }
        } else {
            $str = substr( $str, $start, $tLength );
        }
    }
    return $length < $iLength ? ($str . $trim) : $str;
}

/**
 * 判断是否为手机端
 * @return boolean
 */
function isMobile() {
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if ( isset( $_SERVER['HTTP_X_WAP_PROFILE'] ) ) {
        return true;
    }
    //此条摘自TPM智能切换模板引擎，适合TPM开发
    if ( isset( $_SERVER['HTTP_CLIENT'] ) && 'PhoneClient' == $_SERVER['HTTP_CLIENT'] ) {
        return true;
    }
    //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if ( isset( $_SERVER['HTTP_VIA'] ) ) {
        //找不到为flase,否则为true
        return stristr( $_SERVER['HTTP_VIA'], 'wap' ) ? true : false;
    }
    //判断手机发送的客户端标志,兼容性有待提高
    if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
        $clientkeywords = array( 'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-',
            'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront',
            'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile'
        );
        //从HTTP_USER_AGENT中查找手机浏览器的关键字
        if ( preg_match( "/(" . implode( '|', $clientkeywords ) . ")/i", strtolower( $_SERVER['HTTP_USER_AGENT'] ) ) ) {
            return true;
        }
    }
    //协议法，因为有可能不准确，放到最后判断
    if ( isset( $_SERVER['HTTP_ACCEPT'] ) ) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ( (strpos( $_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml' ) !== false) &&
                (strpos( $_SERVER['HTTP_ACCEPT'], 'text/html' ) === false ||
                (strpos( $_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml' ) < strpos( $_SERVER['HTTP_ACCEPT'], 'text/html' ))) ) {
            return true;
        }
    }
    return false;
}

/**
 * 正则获取字符串中img标签中src地址
 * @param string $string
 * @return array
 */
function getImgSrc( $string ) {
    preg_match_all('/<img.*?src=["|\'|\s]?(.*?)(?="|\'|\s)/',$string,$match);
    return $match[1];
}
