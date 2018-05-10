<?php

class BlogService extends BaseService {

    public function addBlog( $userId, $data ) {
        $model = M( "Blog" );
        $field = $data;
        $field["user_id"] = $userId;
        $field["create_time"] = $field["update_time"] = time();
        $model->startTrans();
        $result = $model->data( $field )->add();
        if ( $result > 0 ) {
            $model->commit();
            if ( $field['status'] == '0' ) {
                M( "Category" )->where( "category_id={$field["category_id"]}" )->setInc( "number" );
            }
        } else {
            $model->rollback();
        }
        return $result;
    }

    public function editBlog( $userId, $data ) {
        $result = false;
        if ( empty( $data['blog_id'] ) ) {
            return $result;
        }
        $model = M( "Blog" );
        $condition = "blog_id={$data['blog_id']} and user_id={$userId} and status!=2";
        $row = $model->where( $condition )->find();
        if ( empty( $row ) ) {
            return $result;
        }
        $field = $data;
        unset( $field['blog_id'] );
        $field["update_time"] = time();
        $model->startTrans();
        $result = $model->data( $field )->where( $condition )->save();
        if ( $result > 0 ) {
            $model->commit();
            if ( 0 == $row['status'] ) {
                if ( $field['category_id'] != $row['category_id'] ) {
                    M( "Category" )->where( "category_id={$field["category_id"]}" )->setInc( "number" );
                    M( "Category" )->where( "category_id={$row["category_id"]}" )->setDec( "number" );
                }
            } else if ( 1 == $row['status'] ) {
                if ( 0 == $field['field'] ) {
                    M( "Category" )->where( "category_id={$field["category_id"]}" )->setInc( "number" );
                }
            }
        } else {
            $model->rollback();
        }
        return $result;
    }

    private function postCheck($post){     
        if (!get_magic_quotes_gpc()){ // 判断magic_quotes_gpc是否为打开     
            $post = addslashes($post); // 进行magic_quotes_gpc没有打开的情况对提交数据的过滤     
        }
        $post = str_replace("_", "\_", $post); // 把 '_'过滤掉     
        $post = str_replace("%", "\%", $post); // 把' % '过滤掉     
        $post = nl2br($post); // 回车转换     
        $post= htmlspecialchars($post); // html标记转换        
        return $post;     
    }

    /** 
      * 获取指定日期所在月的开始日期与结束日期 
      */  
     private function getMonthRange( $date, $back = 'all', $timestamp = true ) {  
         $timestamp = strtotime( $date );
         switch ( $back ) {
             case 'all':
                 $return = array();
                 $monthFirstDay = date( 'Y-m-1 00:00:00', $timestamp );  
                 $return['first'] = $timestamp ? strtotime($monthFirstDay) : $monthFirstDay;
                 $monthLastDay = date( 'Y-m-' . date( 't', $timestamp ) . ' 23:59:59', $timestamp ); 
                 $return['last'] = $timestamp ? strtotime($monthLastDay) : $monthLastDay;
                 return $return;
                 break;
             case 'first':
                 $monthFirstDay = date( 'Y-m-1 00:00:00', $timestamp );  
                 return $timestamp ? strtotime($monthFirstDay) : $monthFirstDay;
                 break;
             case 'last':
                 $monthLastDay = date( 'Y-m-' . date( 't', $timestamp ) . ' 23:59:59', $timestamp ); 
                 return $timestamp ? strtotime($monthLastDay) : $monthLastDay;
                 break;
             default:
                 return '';
                 break;
         }
     }

     public function filterNT($str){  
      //$str = trim($str);  
      //$str = strip_tags($str,"");  
      //$str = ereg_replace("\t","",$str);  
      $str = str_replace(PHP_EOL, '', $str); 
      //$str = ereg_replace(" "," ",$str);  
      return $str;
    }

    public function listBlog( $where, $page, $limit = 10 ) {

        $model = M( "Blog" );
        $query = array();
        $condition = "b.`user_id`={$where['uid']} AND b.`status`=0";
        $search = trim( $this->postCheck($where['search']) );
        if( !empty( $search ) ){
            $condition .= " AND b.`title` LIKE '%{$search}%'";
            $query['search'] = $search;
        }
        if( $where['cid'] > 0 ){
            $condition .= " AND b.`category_id`={$where['cid']}";
            $query['cid'] = $where['cid'];
        }
        if( !empty($where['ym']) ) {
            $arr = $this->getMonthRange( $where['ym'] );
            $condition .= " AND b.`create_time`>={$arr['first']} AND b.`create_time`<={$arr['last']}";
            $query['ym'] = $where['ym'];
        }
        if( $where['new'] > 0 ){
            $order = "b.`create_time` DESC";
            $query['new'] = 1;
        } else if ( $where['zan'] > 0  ){
            $order = "b.`zan_times` DESC";
            $query['zan'] = 1;
        } else if ( $where['cai'] > 0 ){
            $order = "b.`cai_times` DESC";
            $query['cai'] = 1;
        }
        $field = 'SQL_CALC_FOUND_ROWS b.`blog_id`,b.`title`,b.`body`,b.`create_time`,b.`read_times`,b.`zan_times`,b.`cai_times`,c.`name` AS `catename`';
        $sql = "SELECT {$field} FROM `__PREFIX__blog` b INNER JOIN `__PREFIX__category` c ON c.`category_id`=b.`category_id` "
                ."WHERE {$condition} ORDER BY {$order} LIMIT " . abs( $page - 1 ) * $limit . ",{$limit}";
        $rows = $this->query($sql);
        $total = parent::getFoundRows();
        foreach ( $rows as $key => $row ) {
            if( !empty( $search ) ){
                $rows[$key]['title'] = str_ireplace( $search, '<font style="color:red;">' . $search . '</font>', $row['title'] );
            }
            $srcs = getImgSrc( $row['body'] );
            $rows[$key]['type'] = empty( $srcs ) ? 1 : 2; //1:没图片；2：有图片
            $rows[$key]['img'] = empty( $srcs ) ? '' : $srcs[0]; //cutStr( $row['title'], 0, 1, '' )
            $sepPos = strpos( $row['body'], '<hr/>' );
            if ( false !== $sepPos ) {
                $rows[$key]['body'] = substr( $row['body'], 0, $sepPos );
            }
        }
        $pager = $this->getPager( $total, $rows, $page, $limit, 'Index/index', $query );
        return $pager;
    }

    public function getBlogByPk( $blogId ) {
        $row = M( "Blog" )->where( "blog_id={$blogId}" )->find();
        if ( !empty( $row ) ) {
            $category = M( "Category" )->where( "category_id={$row["category_id"]}" )->find();
            $row["category_name"] = empty( $category ) ? "" : $category["name"];
        }
        return ( array ) $row;
    }

    /**
     * 记录阅读
     */
    public function recordRead( $blogId, $ip, $userId = 0 ) {
        $row = M( "Read" )->where( "blog_id={$blogId} and ip='{$ip}'" )->order( "read_time desc" )->limit( 1 )->find();
        $needRead = false;
        if ( empty( $row ) ) {
            $needRead = true;
        } else if ( !empty( $row ) && time() - $row["read_time"] > 300 ) { //超过5分钟就属于第二次阅读
            $needRead = true;
        }
        if ( $needRead ) {
            M( "Read" )->data( array( "blog_id" => $blogId, "user_id" => $userId, "ip" => $ip, "read_time" => time() ) )->add();
            M( "Blog" )->where( "blog_id={$blogId}" )->setInc( "read_times" );
        }
    }

    public function listRecommend( $userId ) {
        $sql = "SELECT b.`blog_id`,b.`title`,b.`body`,c.`name` AS `catename` FROM `__PREFIX__blog` b ".
               "INNER JOIN `__PREFIX__category` c ON c.`category_id`=b.`category_id` ".
               "WHERE b.`user_id`={$userId} AND b.`status`=0 ORDER BY b.`zan_times` DESC,b.`create_time` DESC LIMIT 7";
        $rows = $this->query($sql);
        foreach ( $rows as $key => $row ) {
            $srcs = getImgSrc( $row['body'] );
            $rows[$key]['type'] = empty( $srcs ) ? 1 : 2;
            $rows[$key]['img'] = empty( $srcs ) ? $row['catename'] : $srcs[0];
            unset( $rows[$key]['body'] );
        }
        return empty( $rows ) ? array() : $rows;
    }

    public function listRead( $userId ) {
        $sql = "SELECT b.`blog_id`,b.`title`,b.`body`,c.`name` AS `catename` FROM `__PREFIX__blog` b ".
               "INNER JOIN `__PREFIX__category` c ON c.`category_id`=b.`category_id` ".
               "WHERE b.`user_id`={$userId} AND b.`status`=0 ORDER BY b.`read_times` DESC,b.`create_time` DESC LIMIT 7";
        $rows = $this->query($sql);
        foreach ( $rows as $key => $row ) {
            $srcs = getImgSrc( $row['body'] );
            $rows[$key]['type'] = empty( $srcs ) ? 1 : 2;
            $rows[$key]['img'] = empty( $srcs ) ? $row['catename'] : $srcs[0];
            unset( $rows[$key]['body'] );
        }
        return empty( $rows ) ? array() : $rows;
    }

    public function visitState( $userId ) {
        $result = array();
        $readModel = M( "Read" );
        $blogModel = M( 'blog' );
        $beginToday = strtotime( date( "Y-m-d 00:00:00" ) );
        $endToday = strtotime( date( "Y-m-d 23:59:59" ) );
        $field = "count(read_id) as total";
        $condition = "blog_id IN (SELECT blog_id FROM " . $blogModel->tablePrefix . "blog WHERE user_id={$userId})";
        $row = $readModel->field( $field )->where( $condition . " AND read_time BETWEEN {$beginToday} AND {$endToday}" )->find();
        $result["today"] = ( int ) $row["total"];

        $beginYestoday = $beginToday - 86400;
        $endYestoday = $beginToday - 1;
        $row = $readModel->field( $field )->where( $condition . " AND read_time BETWEEN {$beginYestoday} AND {$endYestoday}" )->find();
        $result["yestoday"] = ( int ) $row["total"];

        $beginWeek = getWeekTime();
        $endWeek = getWeekTime( "last" );
        $row = $readModel->field( $field )->where( $condition . " AND read_time BETWEEN {$beginWeek} AND {$endWeek}" )->find();
        $result["week"] = ( int ) $row["total"];

        $beginMonth = getMonthTime();
        $endMonth = getMonthTime( "last" );
        $row = $readModel->field( $field )->where( $condition . " AND read_time BETWEEN {$beginMonth} AND {$endMonth}" )->find();
        $result["month"] = ( int ) $row["total"];

        $row = $readModel->field( $field )->where( $condition )->find();
        $result["all"] = ( int ) $row["total"];

        return $result;
    }

    public function listblogManage( $args, $page, $limit = 10 ) {
        $blogModel = M( "Blog" );
        $cateModel = M( 'category' );
        $condition = "b.user_id={$args['user_id']} and b.status!=2";
        $query = array();
        if ( !empty( $args['title'] ) ) {
            $condition .= " and b.title like '%{$args['title']}%'";
            $query['t'] = $args['title'];
        }
        if ( $args['category_id'] > 0 ) {
            $condition .= " and b.category_id={$args['category_id']}";
            $query['cid'] = $args['category_id'];
        }
        if ( !empty( $args['create_time'] ) ) {
            $condition .= " and b.create_time>=" . strtotime( $args['create_time'] );
            $query['ct'] = $args['create_time'];
        }
        $rows = $blogModel->field( 'SQL_CALC_FOUND_ROWS b.*,c.name as catename' )
                        ->table( $blogModel->tablePrefix . 'blog b' )
                        ->join( $cateModel->tablePrefix . "category c on c.category_id=b.category_id" )
                        ->where( $condition )->limit( abs( $page - 1 ) * $limit, $limit )
                        ->order( "b.create_time desc" )->select();
        $total = parent::getFoundRows();
        $pager = parent::getPager( $total, $rows, $page, $limit, 'Blog/blogmanage', $query );
        return $pager;
    }

    public function deleteByPk( $blogId, $userId ) {
        $result = false;
        $model = M( "Blog" );
        $condition = "blog_id={$blogId} and user_id={$userId}";
        $row = $model->where( $condition )->find();
        if ( empty( $row ) ) {
            return $result;
        }
        $model->startTrans();
        $result = $model->where( $condition )->delete();
        if ( $result > 0 ) {
            $model->commit();
        } else {
            $model->rollback();
        }
        return $result;
    }

    public function getBlogArchive( $userId ){
        $sql = "SELECT FROM_UNIXTIME(`create_time`,'%Y-%m') AS `ym`, COUNT(`blog_id`) AS `total`FROM `__PREFIX__blog` ".
                "WHERE `user_id`={$userId} AND `status`=0 GROUP BY `ym` ORDER BY `create_time` DESC";
        $rows = $this->query($sql);
        return (array)$rows;
    }

}
