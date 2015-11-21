<?php
/**
 * Project: basic
 * User: chenzhidong
 * Date: 15/11/18
 * Time: 23:11
 */
namespace sillydong\qiniu;

use yii\base\Event;

/**
 * Class UploadEvent
 * @package sillydong\qiniu
 */
class UploadEvent extends Event {
    public $continue = true;
    public $filedata = null;
    public $resultdata = null;

    /**
     * @return bool
     */
    public function ok() {
        return !empty($this->resultdata) && $this->resultdata[0] != null && $this->resultdata[1] == null;
    }

    /**
     * @return array
     */
    public function result() {
        return isset($this->resultdata[0]) ? $this->resultdata[0] : null;
    }

    /**
     * @return string
     */
    public function error() {
        return isset($this->resultdata[1]) ? $this->resultdata[1]->code() . ': ' . $this->resultdata[1]->message() : "null response";
    }
}
