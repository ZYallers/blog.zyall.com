<?php

/**
 * 加密解密类，支持中文
 * @time 2014-09-01
 * @author zyb
 */
class Crypt {

    /**
     * 加密解密用的密钥
     * @var string 
     */
    private $key = '';

    /**
     * 构造函数
     * @param string $key 密钥
     */
    public function __construct( $key = '' ) {
        if ( !empty( $key ) ) {
            $this->key = $key;
        }
    }

    private function safe_b64encode( $string ) {
        $data = base64_encode( $string );
        $data = str_replace( array( '+', '/', '=' ), array( '-', '_', '' ), $data );
        return $data;
    }

    private function safe_b64decode( $string ) {
        $data = str_replace( array( '-', '_' ), array( '+', '/' ), $string );
        $mod4 = strlen( $data ) % 4;
        if ( $mod4 ) {
            $data .= substr( '====', $mod4 );
        }
        return base64_decode( $data );
    }

    /**
     * 设置密钥
     * @param string $key
     */
    public function setKey( $key ) {
        $this->key = $key;
    }

    /**
     * 获取密钥
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * 
     * @param type $txt
     * @param type $encryptKey
     * @return type
     */
    private function keyED( $txt, $encryptKey ) {
        $encryptKey = md5( $encryptKey );
        $ctr = 0;
        $return = "";
        for ( $i = 0; $i < strlen( $txt ); $i++ ) {
            if ( $ctr == strlen( $encryptKey ) ) {
                $ctr = 0;
            }
            $return .= substr( $txt, $i, 1 ) ^ substr( $encryptKey, $ctr, 1 );
            $ctr++;
        }
        return $return;
    }

    /**
     * 加密字符串
     * @param type $txt
     * @param type $key
     * @return type
     */
    public function encrypt( $txt, $key = '' ) {
        $key = empty( $key ) ? $this->key : $key;
        srand( ( double ) microtime() * 1000000 );
        $encryptKey = md5( rand( 0, 32000 ) );
        $ctr = 0;
        $str = '';
        for ( $i = 0; $i < strlen( $txt ); $i++ ) {
            if ( $ctr == strlen( $encryptKey ) ) {
                $ctr = 0;
            }
            $str .= substr( $encryptKey, $ctr, 1 ) . (substr( $txt, $i, 1 ) ^ substr( $encryptKey, $ctr, 1 ));
            $ctr++;
        }
        $return = $this->safe_b64encode( $this->keyED( $str, $key ) );
        $return = str_replace( '=', $this->char, $return );
        return $return;
    }

    /**
     * 解密字符串
     * @param type $txt
     * @param type $key
     * @return type
     */
    public function decrypt( $txt, $key = '' ) {
        $key = empty( $key ) ? $this->key : $key;
        $txt = $this->safe_b64decode( $txt );
        $txt = $this->keyED( $txt, $key );
        $return = '';
        for ( $i = 0; $i < strlen( $txt ); $i++ ) {
            $md5 = substr( $txt, $i, 1 );
            $i++;
            $return .= (substr( $txt, $i, 1 ) ^ $md5);
        }
        return $return;
    }

}
