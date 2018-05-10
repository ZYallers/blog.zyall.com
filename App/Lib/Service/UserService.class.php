<?php

class UserService extends BaseService {

    public function reg( $username, $pwd, $email ) {
        $result = false;
        $user = $this->getUserByUsername( $username );
        if ( !empty( $user ) ) {
            $user = null;
            return -1;
        }
        $user = $this->getUserByEmail( $email );
        if ( !empty( $user ) ) {
            $user = null;
            return -2;
        }
        if ( empty( $user ) ) {
            $salt = substr( md5( time() ), 0, 10 );
            $password = md5( $pwd . $salt . "B+" );
            $model = M( "User" );
            $model->startTrans();
            $result = $model->data( array(
                        "user_name" => $username, "password" => $password,
                        "salt" => $salt, "email" => $email, "create_time" => time(),
                        "update_time" => time() ) )->add();
            if ( $result > 0 ) {
                $template = file_get_contents( realpath( ROOT . TMPL_PATH . '/Mail/activate.php' ) );
                $activateUri = U( 'User/activate', array( 'u' => authCode( $username . '[@]' . (time() + 1800), 'EN' ) ), true, false, true );
                $body = str_replace( array( '[username]', '[time]', '[uri]' )
                        , array( $username, date( 'Y/m/d H:i:s' ), $activateUri ), $template );
                $result = sendMail( array( $email ), 'B+技术博客激活邮件', $body );
                if ( $result ) {
                    $basePath = __ROOT__ . "Upload/User/";
                    if ( creatDir( $basePath ) ) {
                        creatDir( $basePath . $result . "/Avatar/" );
                        creatDir( $basePath . $result . "/Blog/" );
                    }
                    $result > 0 ? $model->commit() : $model->rollback();
                }
            }
        }
        return ( int ) $result;
    }

    public function getUserByUsername( $username ) {
        $row = M( "User" )->where( "binary user_name='{$username}'" )->find();
        return empty( $row ) ? array() : ( array ) $row;
    }

    public function getUserByEmail( $email ) {
        $row = M( "User" )->where( "binary email='{$email}'" )->find();
        return empty( $row ) ? array() : ( array ) $row;
    }

    public function login( $account, $pwd ) {
        $result = false;
        $model = M( "User" );
        $user = $model->where( "binary user_name='{$account}' or binary email='{$account}' or phone='{$account}'" )->find();
        if ( !empty( $user ) ) {
            switch ( $user["status"] ) {
                case 0:
                    $result = -1;
                    break;
                case 1:
                    if ( md5( $pwd . $user['salt'] . "B+" ) == $user["password"] ) {
                        unset( $user['password'], $user['salt'] );
                        session( 'user', $user );
                        M( "Login" )->data( array( "user_id" => $user["user_id"], "ip" => getIP(), "login_time" => time() ) )->add();
                        $result = true;
                    }
                    break;
                case 2:
                    $result = -2;
                    break;
            }
        }
        return ( int ) $result;
    }

    public function zan( $blogId, $userId ) {
        $result = 0;
        $ip = getIP();
        $row = M( "Zan" )->where( "blog_id={$blogId} and ip='{$ip}'" )->order( "zan_time desc" )->limit( 1 )->find();
        $canZan = false;
        if ( empty( $row ) ) {
            $canZan = true;
        } else if ( !empty( $row ) && time() - $row["zan_time"] > 300 ) { //超过5分钟就属于第二次赞
            $canZan = true;
        }
        if ( $canZan ) {
            $model = M( "Zan" );
            $model->startTrans();
            $result = $model->data( array( "blog_id" => $blogId, "user_id" => $userId, 'ip' => $ip, "zan_time" => time() ) )->add();
            if ( $result > 0 ) {
                M( "Blog" )->where( "blog_id={$blogId}" )->setInc( "zan_times" );
                $model->commit();
            } else {
                $model->rollback();
            }
        } else {
            $result = -1;
        }
        return $result;
    }

    public function cai( $blogId, $userId ) {
        $result = 0;
        $ip = getIP();
        $row = M( "Cai" )->where( "blog_id={$blogId} and ip='{$ip}'" )->order( "cai_time desc" )->limit( 1 )->find();
        $canCai = false;
        if ( empty( $row ) ) {
            $canCai = true;
        } else if ( !empty( $row ) && time() - $row["cai_time"] > 300 ) { //超过5分钟就属于第二次赞
            $canCai = true;
        }
        if ( $canCai ) {
            $model = M( "Cai" );
            $model->startTrans();
            $result = $model->data( array( "blog_id" => $blogId, "user_id" => $userId, 'ip' => $ip, "cai_time" => time() ) )->add();
            if ( $result > 0 ) {
                M( "Blog" )->where( "blog_id={$blogId}" )->setInc( "cai_times" );
                $model->commit();
            } else {
                $model->rollback();
            }
        } else {
            $result = -1;
        }
        return $result;
    }

    public function collect( $blogId, $userId ) {
        $result = 0;
        $row = M( "Collect" )->where( "blog_id={$blogId} and user_id={$userId}" )->limit( 1 )->find();
        if ( empty( $row ) ) {
            $model = M( "Collect" );
            $model->startTrans();
            $result = $model->data( array( "blog_id" => $blogId, "user_id" => $userId, "collect_time" => time() ) )->add();
            if ( $result > 0 ) {
                M( "Blog" )->where( "blog_id={$blogId}" )->setInc( "collect_times" );
                $model->commit();
            } else {
                $model->rollback();
            }
        } else {
            $result = -1;
        }
        return $result;
    }

    public function getUserInfo( $userId ) {
        $row = M( "User" )->where( "user_id={$userId}" )->find();
        return empty( $row ) ? array() : ( array ) $row;
    }

    public function editUserInfo( $userId, $info ) {
        $result = false;
        if ( !empty( $info["avatar"]["name"] ) && $info["avatar"]["size"] <= 500 * 1024 ) {
            $path = __ROOT__ . "Upload/User/{$userId}/Avatar/";
            if ( creatDir( $path ) ) {
                $path .= createRandomCode() . "." . getExtension( $info["avatar"]["name"] );
                $result = move_uploaded_file( $info["avatar"]["tmp_name"], $path );
            }
        }
        if ( $result ) {
            $info["avatar"] = $path;
        } else {
            unset( $info["avatar"] );
        }
        $info['banner'] = json_encode( explode( '[_BANNER_]', $info['banner'] ) );
        $info['update_time'] = time();
        $model = M( "User" );
        $model->startTrans();
        $result = $model->where( "user_id={$userId}" )->data( $info )->save();
        $result ? $model->commit() : $model->rollback();
        return $result;
    }

    public function addCategory( $userId, $name ) {
        $result = false;
        $row = M( "Category" )->where( "user_id={$userId} and name='{$name}'" )->find();
        if ( empty( $row ) ) {
            $row = M( "Category" )->field( "max(sort) as maxid" )->where( "user_id={$userId}" )->find();
            $model = M( "Category" );
            $model->startTrans();
            $result = M( "Category" )->data( array(
                        "user_id" => $userId,
                        "name" => $name,
                        "sort" => ( int ) $row["maxid"] + 1,
                        'create_time' => time(),
                        'update_time' => time() ) )->add();
            $result ? $model->commit() : $model->rollback();
        } else {
            $result = -1;
        }
        return $result;
    }

    public function giveMeMsg( $nickname, $msg ) {
        $model = M( 'Msg' );
        $model->startTrans();
        $result = $model->data( array( 'nickname' => $nickname, 'msg' => $msg,
                    'ip' => getIP(), 'create_time' => time() ) )->add();
        $result ? $model->commit() : $model->rollback();
        return $result;
    }

    public function giveMeQue( $title, $content, $userId ) {
        $model = M( 'Question' );
        $model->startTrans();
        $result = $model->data( array( 'user_id' => $userId, 'title' => $title, 'content' => $content,
                    'create_time' => time() ) )->add();
        $result ? $model->commit() : $model->rollback();
        return $result;
    }

    public function activateAccount( $username ) {
        $result = false;
        $model = M( 'User' );
        $where = "user_name='{$username}'";
        $row = $model->where( $where )->find();
        if ( !empty( $row ) ) {
            if ( 0 == $row['status'] ) {
                $result = $model->data( array( 'status' => 1 ) )->where( $where )->save();
            } else if ( 1 == $row['status'] ) {
                $result = 2;
            } else {
                $result = -1;
            }
        }
        return $result;
    }

    public function findPassword( $account, $verify ) {
        $result = false;
        $a = session( 'zyall_verify' );
        $b = md5( $verify );
        if ( session( 'zyall_verify' ) != md5( $verify ) ) {
            return -2;
        }
        $row = M( 'User' )->where( "binary user_name='{$account}'" )->find();
        if ( empty( $row ) ) {
            return -1;
        } else {
            $template = file_get_contents( realpath( ROOT . TMPL_PATH . '/Mail/findpwd.php' ) );
            $uri = U( 'User/resetpwd', array( 'u' => authCode( $account . '[@]' . (time() + 1800), 'EN' ) ), true, false, true );
            $body = str_replace( array( '[username]', '[time]', '[uri]' )
                    , array( $account, date( 'Y/m/d H:i:s' ), $uri ), $template );
            $result = sendMail( array( $row['email'] ), 'B+技术博客找回密码邮件', $body );
        }
        return $result;
    }

    /**
     * 重置密码
     * @param type $username
     * @param type $password
     * @return type
     */
    public function resetPassword( $username, $password ) {
        $result = false;
        $model = M( 'User' );
        $condition = "user_name='{$username}'";
        $row = $model->where( $condition )->find();
        if ( !empty( $row ) ) {
            $model->startTrans();
            $salt = substr( md5( time() ), 0, 10 );
            $pwd = md5( $password . $salt . "B+" );
            $result = $model->data( array( 'password' => $pwd, 'salt' => $salt ) )
                            ->where( $condition )->save();
            $result > 0 ? $model->commit() : $model->rollback();
        }
        return $result;
    }

}
