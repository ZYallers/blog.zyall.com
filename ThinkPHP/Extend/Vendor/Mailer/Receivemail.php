<?php

error_reporting( E_ERROR );

class ReceiveMail {

    private $server = '';
    private $username = '';
    private $password = '';
    private $marubox = '';
    private $email = '';

    /**
     * Constructure
     * @param type $username
     * @param type $password
     * @param type $EmailAddress
     * @param type $mailserver
     * @param type $servertype
     * @param string $port
     * @param type $ssl
     */
    public function ReceiveMail( $username, $password, $EmailAddress, $mailserver = 'localhost', $servertype = 'pop', $port = '110', $ssl = false ) {
        if ( $servertype == 'imap' ) {
            if ( $port == '' ) {
                $port = '143';
            }
            $strConnect = '{' . $mailserver . ':' . $port . '}INBOX';
        } else {
            $strConnect = '{' . $mailserver . ':' . $port . '/pop3' . ($ssl ? "/ssl" : "") . '}INBOX';
        }
        $this->server = $strConnect;
        $this->username = $username;
        $this->password = $password;
        $this->email = $EmailAddress;
    }

    /**
     * Connect To the Mail Box
     * @return boolean
     */
    public function connect() {
        $contected = true;
        $this->marubox = @imap_open( $this->server, $this->username, $this->password );
        if ( !$this->marubox ) {
            $contected = false;
        }
        return $contected;
    }

    /**
     * 获取邮件列表数据
     * @param $total - 邮件总条数
     * @param $page - 第几页
     * @param $pageSize - 每页显示多少封邮件
     * @param $getBody - 是否获取邮件主体内容
     * @return Array 
     */
    public function getListData( $total, $page = 1, $pageSize = 5, $getBody = false ) {
        if ( $page * $pageSize < $total ) {
            $start = $total - ($page * $pageSize) + 1;
            $end = $start + $pageSize - 1;
        } else {
            $start = 1;
            $end = $start + (($page * $pageSize) - $total);
        }
        $result = imap_fetch_overview( $this->marubox, "$start:$end", 0 );
        foreach ( $result as $k => $r ) {
            $result[$k]->subject = $this->_imap_utf8( $r->subject );
            $result[$k]->from = $this->_imap_utf8( $r->from );
            $result[$k]->to = $this->_imap_utf8( $r->to );
            if ( $getBody ) {
                $result[$k]->body = $this->getBody( $r->msgno );
            }
        }
        return array_reverse( $result );
    }

    /**
     * 获取指定一封邮件的头部信息
     * @param type $msgno
     * @param $getBody - 是否获取邮件主体内容
     * @return array | null
     */
    public function getMailHeader( $msgno, $getBody = false ) {
        $result = array();
        if ( $this->marubox ) {
            $result = imap_fetch_overview( $this->marubox, $msgno, 0 );
        }
        foreach ( $result as $k => $r ) {
            $result[$k]->subject = $this->_imap_utf8( $r->subject );
            $result[$k]->from = $this->_imap_utf8( $r->from );
            $result[$k]->to = $this->_imap_utf8( $r->to );
            if ( $getBody ) {
                $result[$k]->body = $this->getBody( $r->msgno );
            }
        }
        return !empty( $result ) ? $result[0] : null;
    }

    /**
     * 获取总邮件数目
     * @return type
     */
    public function getNumMsg() {
        $result = false;
        if ( $this->marubox ) {
            $result = imap_num_msg( $this->marubox );
        }
        return $result;
    }

    /**
     * Get Header info
     * @param type $mid
     * @return boolean
     */
    public function getHeaders( $mid ) {
        if ( !$this->marubox ) {
            return false;
        }
        $mail_header = imap_header( $this->marubox, $mid );
        $sender = $mail_header->from[0];
        $sender_replyto = $mail_header->reply_to[0];
        if ( strtolower( $sender->mailbox ) != 'mailer-daemon' && strtolower( $sender->mailbox ) != 'postmaster' ) {
            $mail_details = array(
                'from' => strtolower( $sender->mailbox ) . '@' . $sender->host,
                'fromName' => $sender->personal,
                'toOth' => strtolower( $sender_replyto->mailbox ) . '@' . $sender_replyto->host,
                'toNameOth' => $sender_replyto->personal,
                'subject' => $mail_header->subject,
                'to' => strtolower( $mail_header->toaddress )
            );
        }
        return $mail_details;
    }

    /**
     * Get Mime type Internal Private Use
     * @param type $structure
     * @return string
     */
    public function get_mime_type( &$structure ) {
        $primary_mime_type = array( "TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER" );
        if ( $structure->subtype ) {
            return $primary_mime_type[( int ) $structure->type] . '/' . $structure->subtype;
        }
        return "TEXT/PLAIN";
    }

    /**
     * Get Part Of Message Internal Private Use
     * @param type $stream
     * @param type $msg_number
     * @param type $mime_type
     * @param type $structure
     * @param string $part_number
     * @return boolean
     */
    public function get_part( $stream, $msg_number, $mime_type, $structure = false, $part_number = false ) {
        if ( !$structure ) {
            $structure = imap_fetchstructure( $stream, $msg_number );
        }
        if ( $structure ) {
            if ( $mime_type == $this->get_mime_type( $structure ) ) {
                if ( !$part_number ) {
                    $part_number = "1";
                }
                $text = imap_fetchbody( $stream, $msg_number, $part_number );
                if ( $structure->encoding == 3 ) {
                    return imap_base64( $text );
                } else if ( $structure->encoding == 4 ) {
                    return imap_qprint( $text );
                } else {
                    return $text;
                }
            }
            if ( $structure->type == 1 ) /* multipart */ {
                while ( list($index, $sub_structure) = each( $structure->parts ) ) {
                    if ( $part_number ) {
                        $prefix = $part_number . '.';
                    }
                    $data = $this->get_part( $stream, $msg_number, $mime_type, $sub_structure, $prefix . ($index + 1) );
                    if ( $data ) {
                        return $data;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Get Total Number off Unread Email In Mailbox
     * @return boolean
     */
    public function getTotalMails() {
        if ( !$this->marubox ) {
            return false;
        }
        $headers = imap_headers( $this->marubox );
        return count( $headers );
    }

    /**
     * Get Atteced File from Mail
     * @param type $mid
     * @param type $path
     * @return boolean
     */
    public function GetAttach( $mid, $path ) {
        if ( !$this->marubox ) {
            return false;
        }
        $struckture = imap_fetchstructure( $this->marubox, $mid );
        $ar = "";
        if ( $struckture->parts ) {
            foreach ( $struckture->parts as $key => $value ) {
                $enc = $struckture->parts[$key]->encoding;
                if ( $struckture->parts[$key]->ifdparameters ) {
                    $name = $struckture->parts[$key]->dparameters[0]->value;
                    $message = imap_fetchbody( $this->marubox, $mid, $key + 1 );
                    switch ( $enc ) {
                        case 0:
                            $message = imap_8bit( $message );
                            break;
                        case 1:
                            $message = imap_8bit( $message );
                            break;
                        case 2:
                            $message = imap_binary( $message );
                            break;
                        case 3:
                            $message = imap_base64( $message );
                            break;
                        case 4:
                            $message = quoted_printable_decode( $message );
                            break;
                        case 5:
                            $message = $message;
                            break;
                    }
                    $fp = fopen( $path . $name, "w" );
                    fwrite( $fp, $message );
                    fclose( $fp );
                    $ar = $ar . $name . ",";
                }
                // Support for embedded attachments starts here
                if ( $struckture->parts[$key]->parts ) {
                    foreach ( $struckture->parts[$key]->parts as $keyb => $valueb ) {
                        $enc = $struckture->parts[$key]->parts[$keyb]->encoding;
                        if ( $struckture->parts[$key]->parts[$keyb]->ifdparameters ) {
                            $name = $struckture->parts[$key]->parts[$keyb]->dparameters[0]->value;
                            $partnro = ($key + 1) . "." . ($keyb + 1);
                            $message = imap_fetchbody( $this->marubox, $mid, $partnro );
                            switch ( $enc ) {
                                case 0:
                                    $message = imap_8bit( $message );
                                    break;
                                case 1:
                                    $message = imap_8bit( $message );
                                    break;
                                case 2:
                                    $message = imap_binary( $message );
                                    break;
                                case 3:
                                    $message = imap_base64( $message );
                                    break;
                                case 4:
                                    $message = quoted_printable_decode( $message );
                                    break;
                                case 5:
                                    $message = $message;
                                    break;
                            }
                            $fp = fopen( $path . $name, "w" );
                            fwrite( $fp, $message );
                            fclose( $fp );
                            $ar = $ar . $name . ",";
                        }
                    }
                }
            }
        }
        $ar = substr( $ar, 0, (strlen( $ar ) - 1 ) );
        return $ar;
    }

    /**
     * Get Message Body
     * @param type $mid
     * @return boolean|string
     */
    public function getBody( $mid ) {
        if ( !$this->marubox ) {
            return false;
        }
        $body = $this->get_part( $this->marubox, $mid, "TEXT/HTML" );
        if ( $body == "" ) {
            $body = $this->get_part( $this->marubox, $mid, "TEXT/PLAIN" );
        }
        if ( $body == "" ) {
            return "";
        }
        return $this->_iconv_utf8( $body );
    }

    /**
     * Delete That Mail
     * @param type $mid
     * @return boolean
     */
    public function deleteMails( $mid ) {
        if ( !$this->marubox ) {
            return false;
        }
        return imap_delete( $this->marubox, $mid );
    }

    /**
     * Close Mail Box
     * @return boolean
     */
    public function close_mailbox() {
        if ( !$this->marubox ) {
            return false;
        }
        return imap_close( $this->marubox, CL_EXPUNGE );
    }

    /**
     * 
     * @param type $text
     * @return type
     */
    private function _imap_utf8( $text ) {
        if ( preg_match( '/=\?([a-zA-z0-9\-]+)\?B\?(.*)\?=/i', $text, $match ) ) {
            //$textImap = imap_utf8( $match[2] );
            $textImap = iconv( $match[1], 'utf-8', base64_decode( $match[2] ) );
//			if ( $textImap == $text ) {
//				$textImap = iconv( $match[1], 'utf-8', base64_decode( $match[2] ) );
//			}
            return $textImap;
        }
        return $this->_iconv_utf8( $text );
    }

    /**
     * 
     * @param type $text
     * @return type
     */
    private function _iconv_utf8( $text ) {
        $s1 = iconv( 'gbk', 'utf-8', $text );
        $s0 = iconv( 'utf-8', 'gbk', $s1 );
        if ( $s0 == $text ) {
            return $s1;
        } else {
            return $text;
        }
    }

}
