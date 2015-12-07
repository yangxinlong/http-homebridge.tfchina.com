<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/5
 * Time: 16:59
 */
namespace app\modules\AppBase\base\appbase;
class Asyn
{
    const host="localhost/index.php?r=";
    public function  InitSchool($id)
    {
        $this->fs(self::host.'Catalogue/catalogue/initschool&id='.$id);
    }
    protected function  fs($hoststr)
    {
        $fp = fsockopen($hoststr, 80, $errno, $errstr, 1800);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
            (new BaseAnalyze())->writeToAnal($errstr ($errno));
        } else {
            $out = "GET /backend.php  / HTTP/1.1\r\n";
            $out .= "Host: www.example.com\r\n";
            $out .= "Connection: Close\r\n\r\n";
            fwrite($fp, $out);
            /*忽略执行结果
            while (!feof($fp)) {
                echo fgets($fp, 128);
            }*/
            fclose($fp);
        }
    }
}