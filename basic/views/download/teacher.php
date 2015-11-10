<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <title>教师端</title>
	<script language="JavaScript"> 
function display_tips(){
     document.getElementById('download_tips').style.display= 'block';
     document.getElementById('download_tips2').style.display= 'block';
}
</script>
    <style type="text/css">
	html {
    height: 100%;
}

body {
    margin: 0;
    padding: 0;
    font-family: Arial;
    font-size: 14px;
    height: 100%;
    -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
}

ul, li, dl, dt, dd {
    margin: 0;
    padding: 0;
    font-weight: normal;
    list-style: none;
}

.clearfix {
    overflow: hidden;
}

.main {
    width: 100%;
    height: 100%;
    position: relative;
}

.top {
    width: 100%;
    position: relative;
}

.head {
    background-color: #f74877;
    width: 100%;
    /*max-width: 720px ;*/
    height: 54px;
    min-height: 54px;
    padding-top: 4px;
    padding-bottom: 0px;
}

.info {
    width: 320px;
    height: 50px;
    margin: 0 auto;

}

.icon {
    width: 320px;
    height: 50px;
    margin: 0 auto;
}

.icon img {
    width: 320px;
    height: 50px;

}

.intros {
    width:100%;
    height: auto;
    z-index: -1;
}

.intros img {
    width: 100%;
    height: auto;
}
.btg{
    width: 100%;
    height: auto;
    position: relative;
}
.btg_jz{
    background-color: #28cacc;
    width: 100%;
    height: auto;
    padding-top: 1px;
    padding-bottom: 20px;
}

.btg_js{
    background-color: #ff99b3;
    width: 100%;
    height: auto;
    padding-top: 1px;
    padding-bottom: 20px;
}
.btg_yz{
    margin-top: 4px;
    background-color: #8e7de5;
    width: 100%;
    height: auto;
    padding-top: 1px;
    padding-bottom: 20px;
}

.bgm img {
    width:100%; height:auto;
    position: relative;
}

.butten {
    width: 240px;
    height: 50px;
    margin: 10px auto 10px auto;

}

.butten img {
    width: 240px;
    height: 50px;
}

li {
    padding: 15px 10px;
    line-height: 1.5em;
    border-bottom: #e8e8e8 1px solid;
    color: #333;
}

input {
    position: inherit;
    top: 10px;
    left: 20px;
}
.download_tips {
	background-color: #FFFFFF;
	position: absolute;
	left: 0px;
	top: 0px;
	right: 0px;
	width: 100%;
	padding-bottom: 1em;
	padding-left: 1em;
	display:none;
	 
}
.alpha_bg {
	 
	width: 100%;
	position: absolute;
	left: 0px;
	top: 0px;
	right: 0px;
	bottom: 0px;
}
*{margin:0;padding:0;list-style-type:none;}
a,img{border:0;}
body{font-family:"微软雅黑";}
.a_jia a{ color:#FFF; list-style-type:none; text-decoration:none;}
.width_load{ width:60%; /*max-width:701px; width:expression((document.documentElement.clientWidth||document.body.clientWidth)>701?"701px":""*/); margin-top:30px;}
@media (min-width: 640px){
	.tel{ font-size:30px;margin-right:15%;}
	.b_lianjie{font-size:16px;}
	.logo_left{margin-left:15%;float:left; display:inline;}
}


@media (min-width: 460px) and (max-width: 639px) {
.tel{ font-size:20px;margin-right:15%;}
.b_lianjie{font-size:13px;}	
.logo_left{margin-left:15%;float:left; display:inline;}
	}
@media (min-width: 330px) and (max-width: 459px) {
.tel{ font-size:12px;margin-right:15%;}	
.b_lianjie{font-size:10px;} 
.logo_left{margin-left:15%;float:left; display:inline;}
	}
@media (min-width: 200px) and (max-width: 329px) {
.tel{ font-size:12px;margin-right:5%;}	
.b_lianjie{font-size:10px;} 
.logo_left{margin-left:5%;float:left; display:inline;}
	}
    </style>
</head>
<body>
<Div class="download_tips" id="download_tips" style="z-index:1000;position:fixed; top:50;">
<div class="" style="float:left;width:70%;padding:1em 0 0 1em;">
如果没有跳转到下载页面，您可能使用了新版本的微信.<p>
点击右上角的跳转按钮，选择在浏览器中打开，然后即可下载
</div>
<div class="" style="float:right;"><img src="images/zhishi_xiazai.png" /></div>
<div class="" style="float:right;"><img src="images/open_in_web.png" /></div>
</Div>
 <Div class="alpha_bg" id="download_tips2" onClick="this.style.display='none';document.getElementById('download_tips').style.display= 'none';"></Div>
 <div style="width:100%; float:right; display:inline; position:fixed; top:60px; right:8px; z-index:50; text-align:right;"><a href="javascript:void(0);" onclick="javascript:document.getElementsByTagName('BODY')[0].scrollTop=document.getElementsByTagName('BODY')[0].scrollHeight;"><img style="width:50px;" src="images/down.png"/></a></div>
<div class="main">
    <div class="top">
        <div class="head">
            <div class="info clearfix">
                <div class="icon">
                    <a href="http://homebridge.tfchina.com/"><img src="images/img/icon.png" ></a>
                </div>
            </div>
            <div class="btg_js">
<div id="intros1" class="intros">
                    <img src="images/img/bbmjs.jpg">
                </div>
<div class="butten">
                   <a href="<?php if($if_wx){?>javascript:display_tips()<?php }else{ ?>/download/hbt.apk<?php }?>"> <img src="images/img/butten4.png" /> </a>
			    </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>