<?php

class CategoryService extends BaseService {

    public function getCategories( $userId ) {
        $result = array();
        $model = M( "Category" );
        $rows = $model->where( "user_id={$userId} AND status=0" )->order( "sort asc" )->select();
        foreach ( $rows as $row ) {
            $result[$row['category_id']] = $row;
        }
        return $result;
    }

    public function getCateByPkAndUserId( $cateId, $userId ) {
        $row = M( 'Category' )->where( "user_id={$userId} and category_id={$cateId}" )->find();
        return ( array ) $row;
    }

    public function getUpperAdnLowerCategoryId( $categoryId, $userId ) {
        $return = array( 'perv' => 0, 'next' => 0 );
        $categories = $this->getCategories( $userId );
        $pervCategory = $nextCategory = array();
        foreach ( $categories as $key => $value ) {
            if ( $categoryId == $value['category_id'] ) {
                $key - 1 > 0 && $pervCategory = $categories[$key - 1];
                $key + 1 < count( $categories ) && $nextCategory = $categories[$key + 1];
                break;
            }
        }
        !empty( $pervCategory ) && $return['perv'] = $pervCategory['category_id'];
        !empty( $nextCategory ) && $return['next'] = $nextCategory['category_id'];
        return $return;
    }

    public function editCategroy( $data ) {
        $result = false;
        $model = M( 'Category' );
        $row = $model->where( "category_id={$data['sort']}" )->find();
        $sortId = $row['sort'];
        $model->startTrans();
        $result = $model->data( array( 'sort' => $sortId, 'name' => $data['name'], 'number' => $data['number']
                ) )->where( "category_id={$data['cid']}" )->save();
        if ( $result > 0 ) {
            $result = $model->where( "category_id={$data['sort']}" )->setInc( "sort" );
            if ( $result > 0 ) {
                $model->commit();
            } else {
                $model->rollback();
            }
        } else {
            $model->rollback();
        }
        return $result;
    }

    public function delelteCategoryByPk( $cid, $userId ) {
        $result = false;
        $model = M( "Category" );
        $condition = "category_id={$cid} and user_id={$userId}";
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

}
