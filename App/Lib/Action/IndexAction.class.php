<?php

class IndexAction extends Action {

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

    public function index() {
        $params = $where = array(
            'ym'=>I('get.ym','', 'htmlspecialchars'),
            'search'=>I('get.search', '', 'htmlspecialchars'),
            'new' => I( 'get.new', 0, 'intval'),
            'zan' => I( 'get.zan', 0, 'intval' ),
            'cai' => I( 'get.cai', 0, 'intval' ),
            'cid' => I( 'get.cid', 0, 'intval' )
        );
        if($where['zan'] == 0 && $where['cai'] == 0){
            $params['new'] = $where['new'] = 1;
        }
        $page = I( 'get.page', 1 );
        if ( $this->userId > 0 ) {
            $where['uid'] = $this->userId;
            $params["blogs"] = D( "Blog" )->listBlog( $where, $page, 20 );
            $params["categories"] = D( "Category" )->getCategories( $this->userId );
        } else {
            $where['uid'] = 1;
            $params["blogs"] = D( "Blog" )->listBlog( $where, $page, 20 );
            $params["categories"] = D( "Category" )->getCategories( 1 );
        }
        $this->assign( $params );
        $this->display();
    }

    public function getBanner() {
        $result = array();
        if ( $this->isAjax() ) {
            $maxim = D( 'Maxim' )->getAllMaxim();
            $this->ajaxBack( true, $maxim );
        }
        $this->ajaxBack();
    }

    public function msg() {
        $nickname = I( 'post.nickname' );
        $msg = I( 'post.msg' );
        $result = false;
        if ( !empty( $nickname ) && !empty( $msg ) ) {
            $result = D( 'User' )->giveMeMsg( $nickname, $msg );
        }
        $this->ajaxBack( $result );
    }

    public function aboutlink() {
        $result = array();
        if ( $this->isAjax() ) {
            $result = D( 'Aboutlink' )->getAllAboutlink();
            $this->ajaxBack( true, $result );
        }
        $this->ajaxBack();
    }

    public function archive() {
        $result = array();
        if ( $this->isAjax() ) {
            $userId = $this->userId > 0 ? $this->userId : 1;
            $result = D( 'Blog' )->getBlogArchive( $userId );
            $this->ajaxBack( true, $result );
        }
        $this->ajaxBack();
    }

}
