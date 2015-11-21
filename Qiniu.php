<?php

namespace sillydong\qiniu;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

/**
 * Project: basic
 * User: chenzhidong
 * Date: 15/11/18
 * Time: 22:58
 */
class Qiniu extends \yii\base\Component{

    const EVENT_BEFORE_UPLOAD = "beforeUpload";
    const EVENT_AFTER_UPLOAD = "afterUpload";

    protected $url;
    protected $bucket;
    protected $ak;
    protected $sk;
    protected $auth;
    protected $uploadmgr;
    protected $policy = array(
        'returnBody' => '{"name": $(fname),"size": $(fsize),"w": $(imageInfo.width),"h": $(imageInfo.height),"hash": $(etag)}'
    );

    function __construct($bucket, $url, $ak, $sk, $config = []) {
        $this->url = $url;
        $this->bucket = $bucket;
        $this->ak = $ak;
        $this->sk = $sk;

        $this->auth = new Auth($this->ak, $this->sk);
        $this->uploadmgr = new UploadManager();

        parent::__construct($config);
    }

    protected function beforeUpload($filedata = []) {
        $event = new UploadEvent();
        $event->filedata = $filedata;
        $this->trigger(self::EVENT_BEFORE_UPLOAD, $event);

        return $event->continue;
    }

    protected function afterUpload($resultdata = []) {
        $event = new UploadEvent();
        $event->resultdata = $resultdata;

        $this->trigger(self::EVENT_AFTER_UPLOAD, $event);
    }

    public function upload($filename, $data, $mimetype) {
        if ($this->beforeUpload(func_get_args()))
        {
            $token = $this->auth->uploadToken($this->bucket, null, 3600, $this->policy);

            $result = $this->uploadmgr->put($token, $filename, $data, null, $mimetype);
        }
        else
        {
            $result = [];
        }
        $this->afterUpload($result);

        return $result;
    }

    public function uploadFile($filename, $path, $mimetype) {
        if ($this->beforeUpload(func_get_args()))
        {
            $token = $this->auth->uploadToken($this->bucket, null, 3600, $this->policy);

            $result = $this->uploadmgr->putFile($token, $filename, $path, null, $mimetype);
        }
        else
        {
            $result = [];
        }
        $this->afterUpload($result);

        return $result;
    }
}
