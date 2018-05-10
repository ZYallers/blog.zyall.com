<?php

class MaximService extends BaseService {

    public function getRandomMaxim() {
        $model = M( 'Maxim' );
        $total = parent::getTotal( $model );
        $rand = mt_rand( 0, $total - 1 );
        $rows = $model->limit( $rand, 1 )->select();
        return $rows[0];
    }

    public function getAllMaxim() {
        $rows = M( 'Maxim' )->field( 'maxim_id,content' )->where( 'status=0' )->order( 'create_time DESC' )->select();
        return $rows;
    }

}
