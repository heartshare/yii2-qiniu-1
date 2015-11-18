<?php
/**
 * Project: basic
 * User: chenzhidong
 * Date: 15/11/18
 * Time: 23:11
 */
namespace sillydong\qiniu;

use yii\base\Event;

class UploadEvent extends Event{
    public $continue = true;
    public $filedata = [];
    public $resultdata = [];
}
