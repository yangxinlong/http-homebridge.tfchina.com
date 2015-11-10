// JavaScript Document
//��������Y���ϵĹ�������

function getScrollTop(){
����var scrollTop = 0, bodyScrollTop = 0, documentScrollTop = 0;
����if(document.body){
��������bodyScrollTop = document.body.scrollTop;
����}
����if(document.documentElement){
��������documentScrollTop = document.documentElement.scrollTop;
����}
����scrollTop = (bodyScrollTop - documentScrollTop > 0) ? bodyScrollTop : documentScrollTop;
����return scrollTop;
}

//�ĵ����ܸ߶�

function getScrollHeight(){
����var scrollHeight = 0, bodyScrollHeight = 0, documentScrollHeight = 0;
����if(document.body){
��������bodyScrollHeight = document.body.scrollHeight;
����}
����if(document.documentElement){
��������documentScrollHeight = document.documentElement.scrollHeight;
����}
����scrollHeight = (bodyScrollHeight - documentScrollHeight > 0) ? bodyScrollHeight : documentScrollHeight;
����return scrollHeight;
}

//������ӿڵĸ߶�

function getWindowHeight(){
����var windowHeight = 0;
����if(document.compatMode == "CSS1Compat"){
��������windowHeight = document.documentElement.clientHeight;
����}else{
��������windowHeight = document.body.clientHeight;
����}
����return windowHeight;
}

window.onscroll = function(){
����if(getScrollTop() + getWindowHeight() == getScrollHeight()){
��������alert("you are in the bottom!");
����}
����if(getScrollTop() ==0){
��������alert("you are in the top!");
����}
function topadd()
{
    var aWidth = window.screen.availWidth; //   ��ȡ�ͻ��˷ֱ��ʵĿ��
    var aHeight = window.screen.availHeight; //   ��ȡ�ͻ��˷ֱ��ʵĸ߶�

    var sWidth = document.body.scrollWidth; //    ��ȡ������Ŀ��
    var sHeight = document.body.scrollHeight; //    ��ȡ������ĸ߶�

    var tWidth = sWidth - aWidth;
    var tHeight = sHeight - aHeight;

    window.scrollTo((tWidth / 2), (tHeight / 5));
}
function bottomadd()
{
    var aWidth = window.screen.availWidth; //   ��ȡ�ͻ��˷ֱ��ʵĿ��
    var aHeight = window.screen.availHeight; //   ��ȡ�ͻ��˷ֱ��ʵĸ߶�

    var sWidth = document.body.scrollWidth; //    ��ȡ������Ŀ��
    var sHeight = document.body.scrollHeight; //    ��ȡ������ĸ߶�

    var tWidth = sWidth - aWidth;
    var tHeight = sHeight - aHeight;

    window.scrollTo((tWidth / 2), (tHeight / 2));
}

};