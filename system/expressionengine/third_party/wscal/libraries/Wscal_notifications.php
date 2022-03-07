<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * notification functions
 * @package     ExpressionEngine
 * @category    Module
 * @author      Greg Crane
 * @link        http://ghcrane.com
 */

class Wscal_notifications {

    /**
    * address where admin notifications are sent
    * @var string
    */
    private $_admin_email = 'greg@creativearc.com';
    private $_reply_to = 'greg@creativearc.com';
    
    public function __construct(){
    }

    /**
    * send notification email
    * @param $to string
    * @param $from string
    * @param $subject string
    * @param $message string
    * @param $name string used for "from" name and "replyto" name
    */
    public function send_notification_email($to, $from, $subject, $message, $name = '') {
        ee()->email->wordwrap = true;
        ee()->email->mailtype = 'html';
        ee()->email->from($from, $name);
        ee()->email->reply_to($from, $name);
        ee()->email->to($to);
        ee()->email->subject($subject);
        ee()->email->message(entities_to_ascii($message));
        
        if(!ee()->email->Send()){
            ee()->email->clear();
            return FALSE;
        }
        return TRUE;
    }
    
    /**
    * send admin email
    * @param $subject string
    * @param $message string
    */
    public function send_admin_email($subject, $message) {
        ee()->email->wordwrap = true;
        ee()->email->mailtype = 'html';
        ee()->email->from($this->_reply_to, $this->_reply_to);
        ee()->email->reply_to($this->_reply_to, $this->_reply_to);
        ee()->email->to($this->_admin_email);
        ee()->email->subject($subject);
        ee()->email->message(entities_to_ascii($message));

        if(!ee()->email->Send()){
            ee()->email->clear();
            return FALSE;
        }
        return TRUE;
    }

}