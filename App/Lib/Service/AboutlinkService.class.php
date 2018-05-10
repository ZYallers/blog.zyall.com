<?php

class AboutlinkService extends BaseService {

    public function getAllAboutlink() {
        $rows = M( 'Aboutlink' )->field( 'title,url,ico' )->where( 'status=0' )->order( 'sort ASC' )->select();
        return empty( $rows ) ? array() : $rows;
    }

}
