<?php

class UserAction extends Action {

    private $user = null;
    private $userId = 0;

    public function _initialize() {
        $user = session( "user" );
        if ( !empty( $user ) ) {
            $this->user = $user;
            $this->userId = $this->user["user_id"];
            $this->assign( 'loggedUser', $this->user );
        }
        $this->assign( 'mobile', isMobile() );
        $this->assign( 'language', defined('LANG_SET') ? LANG_SET : 'zh-cn' );
    }

    public function login() {
        if ( $this->isAjax() ) {
            $account = I( "post.account" );
            $pwd = I( "post.pwd" );
            if ( !empty( $account ) && !empty( $pwd ) ) {
                $result = D( "User" )->login( $account, $pwd );
                $this->ajaxReturn( array( "status" => $result ) );
            }
            $this->ajaxReturn( array( "status" => false ) );
        } else {
            empty( $this->user ) ? $this->display() : $this->redirect( "Index/index" );
        }
    }

    public function reg() {
        if ( $this->isAjax() ) {
            $username = I( "post.username" );
            $pwd = I( "post.pwd" );
            $email = I( "post.email" );
            if ( !empty( $username ) && !empty( $pwd ) && !empty( $email ) ) {
                $result = D( "User" )->reg( $username, $pwd, $email );
                $this->ajaxReturn( array( "status" => $result ) );
            }
            $this->ajaxReturn( array( "status" => false ) );
        } else {
            $this->display();
        }
    }

    public function quit() {
        session( "user", null );
        $this->redirect( "Index/index" );
    }

    public function zan() {
        if ( $this->isAjax() ) {
            $blogId = I( "post.bid" );
            if ( !empty( $blogId ) ) {
                $result = D( "User" )->zan( $blogId, $this->userId );
                $this->ajaxReturn( array( "status" => $result ) );
            }
        }
        $this->ajaxReturn( array( "status" => false ) );
    }

    public function cai() {
        if ( $this->isAjax() ) {
            $blogId = I( "post.bid" );
            if ( !empty( $blogId ) ) {
                $result = D( "User" )->cai( $blogId, $this->userId );
                $this->ajaxReturn( array( "status" => $result ) );
            }
        }
        $this->ajaxReturn( array( "status" => false ) );
    }

    public function collect() {
        if ( $this->isAjax() ) {
            $blogId = I( "post.bid" );
            if ( !empty( $blogId ) && $this->userId > 0 ) {
                $result = D( "User" )->collect( $blogId, $this->userId );
                $this->ajaxReturn( array( "status" => $result ) );
            }
        }
        $this->ajaxReturn( array( "status" => false ) );
    }

    public function editinfo() {
        if ( I( "post.ajax" ) ) {
            if ( $this->userId > 0 ) {
                parse_str( I( "post.info", "", false ), $info );
                $info["avatar"] = $_FILES["avatar"];
                $result = D( "User" )->editUserInfo( $this->userId, $info );
                if ( $result ) {
                    $userInfo = D( "User" )->getUserInfo( $this->userId );
                    unset( $userInfo['password'], $userInfo['salt'] );
                    session( 'user', $userInfo );
                }
                exit( json_encode( array( "status" => $result ) ) );
            }
            exit( json_encode( array( "status" => 0 ) ) );
        } else {
            if ( $this->userId > 0 ) {
                //var_dump( D( "User" )->getUserInfo( $this->userId ) );
                //exit;
                $this->assign( "info", D( "User" )->getUserInfo( $this->userId ) );
                $this->display();
            } else {
                $this->error( "清先登录", "User/login" );
            }
        }
    }

    public function addcategory() {
        if ( I( "post.ajax" ) ) {
            $result = false;
            if ( $this->userId > 0 ) {
                $name = I( "post.name" );
                if ( !empty( $name ) ) {
                    $result = D( "User" )->addCategory( $this->userId, $name );
                }
            }
            $this->ajaxBack( $result );
        } else {
            if ( $this->userId > 0 ) {
                $this->display();
            } else {
                $this->error( "清先登录", "User/login" );
            }
        }
    }

    public function question() {
        $title = I( 'post.title' );
        $result = false;
        if ( !empty( $title ) && $this->userId > 0 ) {
            $result = D( 'User' )->giveMeQue( $title, I( 'post.content', '' ), $this->userId );
        }
        $this->ajaxBack( $result );
    }

    /**
     * 激活注册用户
     */
    public function activate() {
        $u = I( 'get.u' );
        if ( !empty( $u ) ) {
            $data = authCode( $u );
            if ( false === strpos( $data, '[@]' ) ) {
                $this->error( '非法传参或参数错误', 'Index/index' );
            } else {
                $data = explode( '[@]', $data );
                if ( time() > $data[1] ) {
                    $this->error( '操作已超过有效时间', 'Index/index' );
                } else {
                    $result = D( 'User' )->activateAccount( $data[0] );
                    $this->assign( "status", $result );
                    $this->assign( "username", $data[0] );
                    $this->display();
                }
            }
        } else {
            $this->error( '非法传参或参数错误', 'Index/index' );
        }
    }

    /**
     * 忘记密码
     */
    public function fgpwd() {
        $this->display();
    }

    Public function verify() {
        import( 'ORG.Util.Image' );
        //buildImageVerify($length,$mode,$type,$width,$height,$verifyName)
        Image::buildImageVerify( 6, 1, 'png', 80, 43, 'zyall_verify' );
    }

    public function findpwd() {
        $account = I( 'post.account' );
        $verify = I( 'post.verify' );
        $result = false;
        if ( !empty( $account ) && !empty( $verify ) ) {
            $result = D( 'User' )->findPassword( $account, $verify );
        }
        $this->ajaxBack( $result );
    }

    public function resetpwd() {
        if ( $this->isAjax() ) {
            $username = I( 'post.username' );
            $password = I( 'post.pwd' );
            $result = false;
            if ( !empty( $username ) && !empty( $password ) ) {
                $result = D( 'User' )->resetPassword( $username, $password );
            }
            $this->ajaxBack( $result );
        } else {
            $u = I( 'get.u' );
            if ( !empty( $u ) ) {
                $data = authCode( $u );
                if ( false === strpos( $data, '[@]' ) ) {
                    $this->error( '非法传参或参数错误', 'Index/index' );
                } else {
                    $data = explode( '[@]', $data );
                    if ( time() > $data[1] ) {
                        $this->error( '操作已超过有效时间', 'Index/index' );
                    } else {
                        $this->assign( "username", $data[0] );
                        $this->display();
                    }
                }
            } else {
                $this->error( '非法传参或参数错误', 'Index/index' );
            }
        }
    }

}
