<?php

class BlogAction extends Action {

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

    public function create() {
        if ( $this->userId > 0 ) {
            $this->assign( "categories", D( "Category" )->getCategories( $this->userId > 0 ? $this->userId : 1  ) );
            $this->assign( 'hideCate', true );
            $this->display();
        } else {
            $this->error( '请先登录' );
        }
    }

    public function save() {
        if ( $this->isAjax() ) {
            $data = I( 'post.blog' );
            if ( !empty( $data ) && $this->userId > 0 ) {
                $result = D( "Blog" )->addBlog( $this->userId, $data );
                $json = array( 'status' => ( bool ) $result, 'data' => array( 'blog_id' => ( int ) $result ) );
                $this->ajaxReturn( $json );
            }
        }
        $this->ajaxReturn( array( "status" => false ) );
    }

    public function view() {
        $blogId = I( 'get.id' );
        if ( $blogId > 0 ) {
            D( "Blog" )->recordRead( $blogId, getIP(), $this->userId );
            $blog = D( "Blog" )->getBlogByPk( $blogId );
            $this->assign( array(
                "blog" => $blog,
                'canEdit' => $this->userId == $blog['user_id'],
                "categories" => D( "Category" )->getCategories( $this->userId > 0 ? $this->userId : 1  )
            ) );
            $this->display();
        } else {
            $this->error( "非法参数！" );
        }
    }

    public function recommend() {
        if ( $this->isAjax() ) {
            $result = D( "Blog" )->listRecommend( 1 );
            $this->ajaxReturn( array( "status" => true, "data" => $result ) );
        }
        $this->ajaxReturn( array( "status" => false ) );
    }

    public function read() {
        if ( $this->isAjax() ) {
            $result = D( "Blog" )->listRead( 1 );
            $this->ajaxReturn( array( "status" => true, "data" => $result ) );
        }
        $this->ajaxReturn( array( "status" => false ) );
    }

    public function visit() {
        if ( $this->isAjax() ) {
            $result = D( "Blog" )->visitState( $this->userId ? $this->userId : 1  );
            $this->ajaxReturn( array( "status" => true, "data" => $result ) );
        }
        $this->ajaxReturn( array( "status" => false ) );
    }

    public function blogmanage() {
        if ( $this->userId > 0 ) {
            $params = array( 'user_id' => $this->userId, 'hideCate' => true );
            $params['title'] = I( 'get.t', '' );
            $params['category_id'] = I( 'get.cid', 0 );
            $params['create_time'] = I( 'get.ct', 0 );
            $params['page'] = I( 'get.page', 1 );
            $params['blogs'] = D( 'Blog' )->listblogManage( $params, $params['page'], 15 );
            $params["categories"] = D( "Category" )->getCategories( $this->userId );
            $this->assign( $params );
            $this->display();
        } else {
            $this->error( '请先登录', "User/login" );
        }
    }

    public function edit() {
        $blogId = I( 'get.id' );
        if ( $this->userId > 0 && $blogId > 0 ) {
            $blog = D( 'Blog' )->getBlogByPk( $blogId );
            $this->assign( 'blog', $blog );
            $this->assign( 'hideCate', true );
            $this->assign( "categories", D( "Category" )->getCategories( $this->userId > 0 ? $this->userId : 1  ) );
            $this->display();
        } else {
            $this->error( '博客不存在或您未登录' );
        }
    }

    public function saveedit() {
        $result = false;
        if ( $this->isAjax() ) {
            $data = I( 'post.blog' );
            if ( !empty( $data ) && $this->userId > 0 ) {
                $result = D( "Blog" )->editBlog( $this->userId, $data );
            }
        }
        $this->ajaxBack( $result );
    }

    public function delete() {
        $result = false;
        if ( $this->isAjax() ) {
            $blogId = I( 'post.bid' );
            if ( $this->userId > 0 && $blogId > 0 ) {
                $result = D( 'Blog' )->deleteByPk( $blogId, $this->userId );
            }
        }
        $this->ajaxBack( $result );
    }

    public function catemanage() {
        if ( $this->userId > 0 ) {
            $this->assign( 'hideCate', true );
            $this->assign( "categories", D( "Category" )->getCategories( $this->userId ) );
            $this->display();
        } else {
            $this->error( '请先登录', "User/login" );
        }
    }

    /**
     * 编辑分类
     */
    public function editcate() {
        if ( $this->isAjax() ) {
            $cid = I( 'post.cid' );
            $catename = I( 'post.catename' );
            $number = I( 'post.number' );
            $sort = I( 'post.sort' );
            $result = false;
            if ( !empty( $cid ) && !empty( $catename ) ) {
                $data = array( 'cid' => $cid, 'name' => $catename, 'number' => $number, 'sort' => $sort );
                $result = D( 'Category' )->editCategroy( $data );
            }
            $this->ajaxBack( $result );
        } else {
            if ( $this->userId > 0 ) {
                $cid = I( 'get.cid', 0 );
                if ( $cid > 0 ) {
                    $this->assign( 'hideCate', true );
                    $this->assign( 'cate', D( 'Category' )->getCateByPkAndUserId( $cid, $this->userId ) );
                    $this->assign( 'categories', D( 'Category' )->getCategories( $this->userId ) );
                    $this->assign( 'nextCategories', D( 'Category' )->getUpperAdnLowerCategoryId( $cid, $this->userId ) );
                    $this->display();
                } else {
                    $this->error( '参数错误' );
                }
            } else {
                $this->error( '请先登录', "User/login" );
            }
        }
    }

    public function delcate() {
        $result = false;
        if ( $this->isAjax() ) {
            $cid = I( 'post.cid' );
            if ( $cid > 0 && $this->userId > 0 ) {
                $result = D( "Category" )->delelteCategoryByPk( $cid, $this->userId );
            }
        }
        $this->ajaxBack( $result );
    }

}
