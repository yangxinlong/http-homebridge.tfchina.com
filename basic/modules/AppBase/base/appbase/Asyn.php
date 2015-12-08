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
    public function  InitSchool($id)
    {
        $this->fs("index.php?r=Catalogue/catalogue/initschool&id=$id");
    }
    function fs2($path, $data)
    {
        $path = '';
        $data = '';
        $host = 'home.local.com';
        $post = $data ? http_build_query($data) : '';
        $header = "POST / HTTP/1.1" . "\n";
        $header .= "User-Agent: Mozilla/4.0+(compatible;+MSIE+6.0;+Windows+NT+5.1;+SV1)" . "\n";
        $header .= "Host: $host" . "\n";
        $header .= "Accept: */*" . "\n";
        $header .= "Referer: http://$host/" . "\n";
        $header .= "Content-Length: " . strlen($post) . "\n";
        $header .= "Content-Type: application/x-www-form-urlencoded" . "\n";
        $header .= "\r\n";
        $ddd = $header . $post;
        $fp = stream_socket_client("tcp://$host:80", $errno, $errstr, 30, STREAM_CLIENT_ASYNC_CONNECT | STREAM_CLIENT_CONNECT);
        $response = '';
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            $content = fwrite($fp, $ddd, strlen($post));
            echo '|';
            echo $content;
            echo '|';
            var_dump($fp);
            while (!feof($fp)) {  //while die
                $r = fgets($fp, 1024);
                $response .= $r;
            }
        }
        fclose($fp);
        echo '|';
        var_dump($response);
        exit;
        return $response;
    }
    public function  fs($path)
    {
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
}