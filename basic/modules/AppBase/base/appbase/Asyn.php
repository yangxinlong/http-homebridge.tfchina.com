<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/5
 * Time: 16:59
 */
namespace app\modules\AppBase\base\appbase;
use Yii;
class Asyn
{
    private $school_id = 0;
    /**
     * @param int $school_id
     */
    public function setSchoolId($school_id)
    {
        $this->school_id = $school_id;
    }
    public function  InitSchool($id)
    {
        $this->fs_get("index.php?r=Catalogue/catalogue/initschool&id=$id");
    }
    public function  arat_push_pass($user_id, $type, $id, $reward, $title)
    {
        $this->fs_get("index.php?r=Articles/arat/pushpass&user_id=$user_id&type=$type&id=$id&reward=$reward&title=$title");
    }
    public function  pushAuditByArid($id, $title)
    {
        $this->fs_get("index.php?r=Articles/articles/pushauditbyarid&id=$id&title=$title");
    }
    public function  pushaddnote($d)
    {
        $this->fs_post("index.php?r=Notes/notes/pushaddnote", $d);
    }
    public function  fs_get($path)
    {
        $path .= "&school_id=$this->school_id";
        $host = Yii::$app->request->getHostInfo();
        $host = substr($host, 7, strlen($host));
//        $path = "index.php?r=site/sqhz";
        $fp = stream_socket_client("tcp://$host:80", $errno, $errstr, 30);
        stream_set_blocking($fp, 0);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            fwrite($fp, "GET /$path HTTP/1.0\r\nHost: $host\r\nAccept: */*\r\n\r\n");
//            while (!feof($fp)) {
//                echo fgets($fp, 1024);
//            }
            fclose($fp);
        }
    }
    function fs_post($path, $data)
    {
        $data['school_id'] = $this->school_id;
        $host = Yii::$app->request->getHostInfo();
        $host = substr($host, 7, strlen($host));
        $post = $data ? http_build_query($data) : '';
        $header = "POST /$path HTTP/1.1" . "\n";
        $header .= "Host: $host" . "\n";
        $header .= "Accept: */*" . "\n";
        $header .= "Referer: http://$host/" . "\n";
        $header .= "Content-Length: " . strlen($post) . "\n";
        $header .= "Content-Type: application/x-www-form-urlencoded" . "\n";
        $header .= "\r\n";
        $ddd = $header . $post;
        $fp = stream_socket_client("tcp://$host:80", $errno, $errstr, 30);
        stream_set_blocking($fp, 0);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            fwrite($fp, $ddd);
//            while (!feof($fp)) {  //while die
//                echo fgets($fp, 1024);
//            }
        }
        fclose($fp);
    }
    function fs_post2($path, $data)
    {
        $data['school_id'] = $this->school_id;
        (new BaseAnalyze())->writeToAnal(json_encode($data));
        $host = Yii::$app->request->getHostInfo();
        $host = substr($host, 7, strlen($host));
        $post = $data ? http_build_query($data) : '';
        $header = "POST /$path HTTP/1.1" . "\n";
        $header .= "User-Agent: Mozilla/4.0+(compatible;+MSIE+6.0;+Windows+NT+5.1;+SV1)" . "\n";
        $header .= "Host: $host" . "\n";
        $header .= "Accept: */*" . "\n";
        $header .= "Referer: http://$host/" . "\n";
        $header .= "Content-Length: " . strlen($post) . "\n";
        $header .= "Content-Type: application/x-www-form-urlencoded" . "\n";
        $header .= "\r\n";
        $ddd = $header . $post;
        $fp = stream_socket_client("tcp://$host:80", $errno, $errstr, 30, STREAM_CLIENT_ASYNC_CONNECT | STREAM_CLIENT_CONNECT);
        stream_set_blocking($fp, 0);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            $content = fwrite($fp, $ddd, strlen($post));
            echo '|';
            echo $content;
            echo '|';
            var_dump($fp);
            while (!feof($fp)) {  //while die
                echo fgets($fp, 1024);
            }
        }
        fclose($fp);
    }
}