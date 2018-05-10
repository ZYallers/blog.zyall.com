<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseService
 *
 * @author Administrator
 */
abstract class BaseService extends Model {

    protected function getPager( $count, $list, $page, $limit, $pageBaseUri, $query = array() ) {
        $pages = $count > 0 ? ceil( $count / $limit ) : 1;
        $pager = array( "total" => $count, "list" => $list, "page" => $page,
            "limit" => $limit, "pages" => $pages, 'pageBaseUri'=>$pageBaseUri,"query" => $query );
        return $pager;
    }

    protected function getTotal( $model, $condition = array() ) {
        $row = $model->field( 'count(*) as total' )->where( $condition )->find();
        return empty( $row ) ? 0 : ( int ) $row["total"];
    }

    protected function getFoundRows() {
        $row = $this->query( 'SELECT FOUND_ROWS() AS rows' );
        return empty( $row ) ? 0 : ( int ) $row[0]['rows'];
    }

}
