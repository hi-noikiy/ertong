<?php

defined('IN_IA') or exit('Access Denied');

class Xc_trainModuleSite extends WeModuleSite {
    /**
     * 网站配置
     */
    public function doWebWeb(){
        $ops=array('edit','savemodel','ad','savead','map','savemap','nav','savenav','sms','savesms','print','saveprint','printtest','theme','savetheme','open_ad','saveopen_ad','smstest','service_poster','saveposter');
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'edit';
        $tablename="xc_train_config";
        switch($op){
            case 'edit':
                $xtitle='网站配置';
                $xkey='web';
                $list=pdo_get($tablename,array('xkey'=>$xkey,'uniacid'=>$uniacid));
                if($list){
                    $list['content']=json_decode($list['content'],true);
                    if(!empty($list['content']['cut_bimg'])){
                        $list['content']['cut_bimg']=explode(",",$list['content']['cut_bimg']);
                    }
                }
                if(!empty($list['content']['footer'])){
                    foreach($list['content']['footer'] as $key=>$x){
                        if(empty($x['status'])){
                            if($key==4){
                                $list['key']['status']=-1;
                            }else{
                                $list['key']['status']=1;
                            }
                            $list['key']['status']=1;
                        }
                    }
                }else{
                    $list['content']['footer']=array();
                    for($i=0;$i<5;$i++){
                        $list['content']['footer'][$i]=array("icon"=>'','select'=>'','text'=>'','link'=>'','status'=>1);
                    }
                    $list['content']['footer'][4]['status']=-1;
                }
                $request=pdo_getall("");
                include $this->template("Web/edit");
                break;
            case 'savemodel':
                $condition=array();
                $condition['uniacid']=$uniacid;
                $condition['xkey']='web';
                $condition['name']=$_GPC['name'];
                $footer=$_GPC['footer'];
                if(!empty($footer) && is_array($footer)){
                    foreach($footer as &$x){
                        if(empty($x['status'])){
                            $x['status']=-1;
                        }
                    }
                }
                $content=array('title'=>$_GPC['title'],'copyright'=>$_GPC['copyright'],'footer'=>$footer,'template_id'=>$_GPC['template_id'],'password'=>$_GPC['password'],'online_simg'=>$_GPC['online_simg'],'sign_bimg'=>$_GPC['sign_bimg'],'g_class'=>$_GPC['g_class'],'x_class'=>$_GPC['x_class'],'online_limit'=>$_GPC['online_limit'],'cut_bimg'=>implode(",",$_GPC['cut_bimg']),'cut_share'=>$_GPC['cut_share']);
                if(!empty($_GPC['map_status'])){
                    $content['map_status']=$_GPC['map_status'];
                }else{
                    $content['map_status']=-1;
                }
                if(!empty($_GPC['online_status'])){
                    $content['online_status']=$_GPC['online_status'];
                }else{
                    $content['online_status']=-1;
                }
                if(!empty($_GPC['sign_status'])){
                    $content['sign_status']=$_GPC['sign_status'];
                }else{
                    $content['sign_status']=-1;
                }
                if(!empty($_GPC['store_status'])){
                    $content['store_status']=$_GPC['store_status'];
                }else{
                    $content['store_status']=-1;
                }
                $condition['content']=json_encode($content);
                $list=pdo_get($tablename,array("xkey"=>'web','uniacid'=>$uniacid));
                if($list){
                    $request=pdo_update($tablename,$condition,array('id'=>$list['id'],'uniacid'=>$uniacid));
                }else{
                    $request=pdo_insert($tablename,$condition);
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>-1,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'ad':
                $xtitle='网站配置';
                $xkey='ad';
                $list=pdo_get($tablename,array('xkey'=>$xkey,'uniacid'=>$uniacid));
                if($list){
                    $list['content']=json_decode($list['content'],true);
                }
                include $this->template("Web/ad");
                break;
            case 'savead':
                $condition=array();
                $condition['uniacid']=$uniacid;
                $condition['xkey']='ad';
                $condition['name']=$_GPC['name'];
                $content=array("list"=>$_GPC['list']);
                if(!empty($_GPC['status'])){
                    $content['status']=$_GPC['status'];
                }else{
                    $content['status']=-1;
                }
                $condition['content']=json_encode($content);
                $list=pdo_get($tablename,array("xkey"=>$condition['xkey'],'uniacid'=>$uniacid));
                if($list){
                    $request=pdo_update($tablename,$condition,array('id'=>$list['id'],'uniacid'=>$uniacid));
                }else{
                    $request=pdo_insert($tablename,$condition);
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>-1,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'map':
                $xtitle='网站配置';
                $xkey='map';
                $list=pdo_get($tablename,array('xkey'=>$xkey,'uniacid'=>$uniacid));
                if($list){
                    $list['content']=json_decode($list['content'],true);
                }
                include $this->template("Web/map");
                break;
            case 'savemap':
                $condition=array();
                $condition['uniacid']=$uniacid;
                $condition['xkey']='map';
                $condition['name']=$_GPC['name'];
                $content=array('mobile'=>$_GPC['mobile'],'address'=>$_GPC['address'],'longitude'=>$_GPC['longitude'],'latitude'=>$_GPC['latitude'],'service'=>$_GPC['service'],'bimg'=>$_GPC['bimg'],'weixin'=>$_GPC['weixin'],'mail'=>$_GPC['mail']);
                $condition['content']=json_encode($content);
                $list=pdo_get($tablename,array("xkey"=>'map','uniacid'=>$uniacid));
                if($list){
                    $request=pdo_update($tablename,$condition,array('id'=>$list['id'],'uniacid'=>$uniacid));
                }else{
                    $request=pdo_insert($tablename,$condition);
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>-1,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'nav':
                $xtitle='网站配置';
                $xkey='nav';
                $list=pdo_get($tablename,array('xkey'=>$xkey,'uniacid'=>$uniacid));
                if($list){
                    $list['content']=json_decode($list['content'],true);
                }
                include $this->template("Web/nav");
                break;
            case 'savenav':
                $condition=array();
                $condition['uniacid']=$uniacid;
                $condition['xkey']='nav';
                $condition['name']=$_GPC['name'];
                $content=array('about_icon'=>$_GPC['about_icon'],'about_text'=>$_GPC['about_text'],'about_link'=>$_GPC['about_link'],'news_icon'=>$_GPC['news_icon'],'news_text'=>$_GPC['news_text'],'contact_icon'=>$_GPC['contact_icon'],'contact_text'=>$_GPC['contact_text'],'teacher_icon'=>$_GPC['teacher_icon'],'teacher_text'=>$_GPC['teacher_text'],'service_icon'=>$_GPC['service_icon'],'service_text'=>$_GPC['service_text'],'sign_icon'=>$_GPC['sign_icon'],'sign_text'=>$_GPC['sign_text'],'search_icon'=>$_GPC['search_icon'],'search_text'=>$_GPC['search_text'],'week_icon'=>$_GPC['week_icon'],'week_text'=>$_GPC['week_text'],'week_link'=>$_GPC['week_link']);
                $condition['content']=json_encode($content);
                $list=pdo_get($tablename,array("xkey"=>'nav','uniacid'=>$uniacid));
                if($list){
                    $request=pdo_update($tablename,$condition,array('id'=>$list['id'],'uniacid'=>$uniacid));
                }else{
                    $request=pdo_insert($tablename,$condition);
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>-1,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'sms':
                $xtitle='网站配置';
                $xkey='sms';
                $list=pdo_get($tablename,array('xkey'=>$xkey,'uniacid'=>$uniacid));
                if($list){
                    $list['content']=json_decode($list['content'],true);
                }
                if(empty($list['content']['type'])){
                    $list['content']['type']=1;
                }
                include $this->template("Web/sms");
                break;
            case 'savesms':
                $condition=array();
                $condition['uniacid']=$uniacid;
                $condition['xkey']='sms';
                $condition['name']=$_GPC['name'];
                $content=array('AccessKeyId'=>$_GPC['AccessKeyId'],'AccessKeySecret'=>$_GPC['AccessKeySecret'],'sign'=>$_GPC['sign'],'code'=>$_GPC['code'],'mobile'=>$_GPC['mobile'],'type'=>$_GPC['type'],"key"=>$_GPC['key'],'tpl_id'=>$_GPC['tpl_id'],'url'=>$_GPC['url'],'customize'=>json_decode(htmlspecialchars_decode($_GPC['customize']),true),'post'=>json_decode(htmlspecialchars_decode($_GPC['post']),true),'get'=>json_decode(htmlspecialchars_decode($_GPC['get']),true));
                if(!empty($_GPC['status'])){
                    $content['status']=$_GPC['status'];
                }else{
                    $content['status']=-1;
                }
                $condition['content']=json_encode($content);
                $list=pdo_get($tablename,array("xkey"=>'sms','uniacid'=>$uniacid));
                if($list){
                    $request=pdo_update($tablename,$condition,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                }else{
                    $request=pdo_insert($tablename,$condition);
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>-1,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'theme':
                $xtitle='网站配置';
                $xkey='theme';
                $list=pdo_get($tablename,array('xkey'=>$xkey,'uniacid'=>$uniacid));
                if($list){
                    $list['content']=json_decode($list['content'],true);
                }else{
                    $list['content']['theme']=1;
                }
                include $this->template("Web/theme");
                break;
            case 'savetheme':
                $condition=array();
                $condition['uniacid']=$uniacid;
                $condition['xkey']='theme';
                $condition['name']=$_GPC['name'];
                $content=array('theme'=>$_GPC['theme']);
                if($_GPC['theme']==2){
                    if(empty($_GPC['color'])){
                        $content['color']='#5fcceb';
                    }else{
                        $content['color']=$_GPC['color'];
                    }
                    $content['icon']=$_GPC['icon'];
                }
                $condition['content']=json_encode($content);
                $list=pdo_get($tablename,array("xkey"=>'theme','uniacid'=>$uniacid));
                if($list){
                    $request=pdo_update($tablename,$condition,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                }else{
                    $request=pdo_insert($tablename,$condition);
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>-1,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'smstest':
                //发送短信消息
                if($_GPC['type']==1) {
                    require_once IA_ROOT.'/addons/xc_train/resource/sms/sendSms.php';
                    set_time_limit(0);
                    header('Content-Type: text/plain; charset=utf-8');
                    $templateParam = array("webnamex"=>"测试",'trade'=>'101100000017','amount'=>'199元','namex'=>'张三','phonex'=>'18888888888','service'=>'买蛋糕');
                    $send=new sms();
                    $result =$send->sendSms($_GPC['AccessKeyId'], $_GPC['AccessKeySecret'], $_GPC['sign'], $_GPC['code'], $_GPC['mobile'], $templateParam);
                    echo json_encode($result);
                }else if($_GPC['type']==2){
                    header('content-type:text/html;charset=utf-8');
                    $sendUrl = 'http://v.juhe.cn/sms/send'; //短信接口的URL
                    $smsConf = array(
                        'key'   => $_GPC['key'], //您申请的APPKEY
                        'mobile'    => $_GPC['mobile'], //接受短信的用户手机号码
                        'tpl_id'    => $_GPC['tpl_id'], //您申请的短信模板ID，根据实际情况修改
                        'tpl_value' =>'#webnamex#=美容&#trade#=1220171127101100000017&#amount#=199元&#namex#=张三&#phonex#=18888888888&#addrx#=中国北京&#datex#='.date("Y-m-d H:i")//您设置的模板变量，根据实际情况修改
                    );
                    $content = juhecurl($sendUrl,$smsConf,1); //请求发送短信
                    if($content){
                        $result = json_decode($content,true);
                        $error_code = $result['error_code'];
                        echo json_encode($result);
                    }else{
                        //返回内容异常，以下可根据业务逻辑自行修改
                        echo json_encode("请求发送短信失败");
                    }
                }
                break;
            case 'print':
                $xtitle='网站配置';
                $xkey='print';
                $list=pdo_get($tablename,array('xkey'=>$xkey,'uniacid'=>$uniacid));
                if($list){
                    $list['content']=json_decode($list['content'],true);
                }
                include $this->template("Web/print");
                break;
            case 'saveprint':
                $condition=array();
                $condition['uniacid']=$uniacid;
                $condition['xkey']='print';
                $condition['name']=$_GPC['name'];
                $content=array('apikey'=>$_GPC['apikey'],'machine_code'=>$_GPC['machine_code'],'msign'=>$_GPC['msign'],'partner'=>$_GPC['partner'],'type'=>$_GPC['type'],'user'=>$_GPC['user'],'ukey'=>$_GPC['ukey'],'sn'=>$_GPC['sn']);
                if(!empty($_GPC['status'])){
                    $content['status']=$_GPC['status'];
                }else{
                    $content['status']=-1;
                }
                $condition['content']=json_encode($content);
                $list=pdo_get($tablename,array("xkey"=>'print','uniacid'=>$uniacid));
                if($list){
                    $request=pdo_update($tablename,$condition,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                }else{
                    $request=pdo_insert($tablename,$condition);
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>-1,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'printtest':
                if($_GPC['type']==1){
                    $partner = $_GPC['partner'];//用户ID
                    $apikey=$_GPC['apikey'];  //API密钥
                    $machine_code=$_GPC['machine_code'];//打印机终端号
                    $msign=$_GPC['msign'];//打印机密钥
                    $time=time();//当前时间戳
                    $content = '';                          //打印内容
                    $content .= '<table>';
                    $content .= '<tr><td>订单号：1111111111</td></tr>';
                    $content .= '</table>';
                    $sign=strtoupper(md5($_GPC['apikey'].'machine_code'.$_GPC['machine_code'].'partner'.$_GPC['partner'].'time'.$time.$msign));//生成的32位签名
                    $requestUrl = 'http://open.10ss.net:8888';
                    $requestAll = [
                        'partner' => $partner,
                        'machine_code' => $machine_code,
                        'time' => $time,
                        'content' => $content,
                        'sign' => $sign,
                    ];
                    $requestInfo = http_build_query($requestAll);
                    $request=push($requestInfo,$requestUrl);
                    echo $request;
                }else if($_GPC['type']==2){
                    include IA_ROOT.'/addons/xc_train/resource/HttpClient.class.php';
                    define('USER', $_GPC['user']);	//*必填*：飞鹅云后台注册账号
                    define('UKEY', $_GPC['ukey']);	//*必填*: 飞鹅云注册账号后生成的UKEY
                    define('SN', $_GPC['sn']);	    //*必填*：打印机编号，必须要在管理后台里添加打印机或调用API接口添加之后，才能调用API
                    //以下参数不需要修改
                    define('IP','api.feieyun.cn');			//接口IP或域名
                    define('PORT',80);						//接口IP端口
                    define('PATH','/Api/Open/');		//接口路径
                    define('STIME', time());			    //公共参数，请求时间
                    define('SIG', sha1(USER.UKEY.STIME));   //公共参数，请求公钥
                    $orderInfo = '<CB>测试打印</CB><BR>';
                    $orderInfo .= '名称　　　　　 单价  数量 金额<BR>';
                    $orderInfo .= '--------------------------------<BR>';
                    $orderInfo .= '饭　　　　　 　10.0   10  10.0<BR>';
                    $orderInfo .= '炒饭　　　　　 10.0   10  10.0<BR>';
                    $orderInfo .= '蛋炒饭　　　　 10.0   100 100.0<BR>';
                    $orderInfo .= '鸡蛋炒饭　　　 100.0  100 100.0<BR>';
                    $orderInfo .= '西红柿炒饭　　 1000.0 1   100.0<BR>';
                    $orderInfo .= '西红柿蛋炒饭　 100.0  100 100.0<BR>';
                    $orderInfo .= '西红柿鸡蛋炒饭 15.0   1   15.0<BR>';
                    $orderInfo .= '备注：加辣<BR>';
                    $orderInfo .= '--------------------------------<BR>';
                    $orderInfo .= '合计：xx.0元<BR>';
                    $orderInfo .= '送货地点：广州市南沙区xx路xx号<BR>';
                    $orderInfo .= '联系电话：13888888888888<BR>';
                    $orderInfo .= '订餐时间：2014-08-08 08:08:08<BR>';
                    $orderInfo .= '<QR>http://www.dzist.com</QR>';//把二维码字符串用标签套上即可自动生成二维码
                    //打开注释可测试
                    $request=wp_print(SN,$orderInfo,1);
                    echo $request;
                }
                break;
            case 'open_ad':
                $xtitle='网站配置';
                $xkey='open_ad';
                $list=pdo_get($tablename,array('xkey'=>$xkey,'uniacid'=>$uniacid));
                if($list){
                    $list['content']=json_decode($list['content'],true);
                }
                include $this->template("Web/open_ad");
                break;
            case 'saveopen_ad':
                $condition=array();
                $condition['uniacid']=$uniacid;
                $condition['xkey']='open_ad';
                $condition['name']=$_GPC['name'];
                $content['bimg']=$_GPC['bimg'];
                $content['link']=$_GPC['link'];
                $content['type']=$_GPC['type'];
                if(!empty($_GPC['status'])){
                    $content['status']=$_GPC['status'];
                }else{
                    $content['status']=-1;
                }
                $condition['content']=json_encode($content);
                $list=pdo_get($tablename,array("xkey"=>$condition['xkey'],'uniacid'=>$uniacid));
                if($list){
                    $request=pdo_update($tablename,$condition,array('id'=>$list['id'],'uniacid'=>$uniacid));
                }else{
                    $request=pdo_insert($tablename,$condition);
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>-1,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'service_poster':
                $xtitle='网站配置';
                $xkey='service_poster';
                $list=pdo_get($tablename,array('xkey'=>$xkey,'uniacid'=>$uniacid));
                if($list){
                    $xc=json_decode($list['content'],true);
                    $xc['content']=htmlspecialchars_decode($xc['content']);
                }
                include $this->template("Web/service_poster");
                break;
            case 'saveposter':
                $data=$_GPC;
                if(empty($data['status'])){
                    $data['status']=-1;
                }
                $condition['xkey']=$data['xkey'];
                $condition['uniacid']=$uniacid;
                $condition['content']=json_encode($data);
                $list=pdo_get($tablename,array("xkey"=>$condition['xkey'],'uniacid'=>$uniacid));
                if($list){
                    $request=pdo_update($tablename,$condition,array('id'=>$list['id'],'uniacid'=>$uniacid));
                }else{
                    $request=pdo_insert($tablename,$condition);
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>-1,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
        }
    }
    /**
     * 轮播图
     */
    public function doWebBanner(){
        $ops=array('list','edit','savemodel','delete','statuschange','article');
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename="xc_train_banner";
        switch($op){
            case 'list':
                $version_id=$_GPC['version_id'];
                $condition=array();
                $condition['uniacid']=$uniacid;
                if(!empty($_GPC['xname'])){
                    $xname=$_GPC['xname'];
                    $condition['name LIKE']='%'.$_GPC['xname']."%";
                }
                $request=pdo_getall($tablename,$condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list=pdo_getall($tablename,$condition,array() , '' , "sort DESC,createtime DESC" , array($pageindex,$pagesize));
                include $this->template("Banner/list");
                break;
            case 'edit':
                if(!empty($_GPC['id'])){
                    $list=pdo_get($tablename,array('id'=>$_GPC['id']));
                }else{
                    $list['status']=1;
                }
                include $this->template("Banner/edit");
                break;
            case 'savemodel':
                $condition=array();
                $condition['uniacid']=$uniacid;
                $condition['name']=$_GPC['name'];
                $condition['bimg']=$_GPC['bimg'];
                $condition['link']=$_GPC['link'];
                if(empty($_GPC['status'])){
                    $condition['status']=-1;
                }else{
                    $condition['status']=$_GPC['status'];
                }
                if(empty($_GPC['sort'])){
                    $condition['sort']=0;
                }else{
                    $condition['sort']=$_GPC['sort'];
                }
                if(empty($_GPC['id'])){
                    $request=pdo_insert($tablename,$condition);
                }else{
                    $request=pdo_update($tablename,$condition,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request=pdo_update($tablename,array("status"=>$_GPC['status']),array("id"=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request=pdo_delete($tablename,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'article':
                $version_id=$_GPC['version_id'];
                $condition=array();
                $condition['uniacid']=$uniacid;
                if(!empty($_GPC['title'])){
                    $title=$_GPC['title'];
                    $condition['title LIKE']='%'.$_GPC['title']."%";
                }
                $request=pdo_getall('xc_train_article',$condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 6;
                $pager = pagination($total, $pageindex, $pagesize);
                $list=pdo_getall('xc_train_article',$condition,array() , '' , "createtime DESC" , array($pageindex,$pagesize));
                $url='https://'.$_SERVER['HTTP_HOST'].'/app/index.php?i='.$uniacid.'&c=entry&do=index&m='.$_GPC['m'];
                if($list){
                    foreach($list as &$x){
                        $x['url']=$url;
                    }
                }
                include $this->template("Banner/article");
                break;
        }
    }
    /**
     * 文章
     */
    public function doWebArticle(){
        $ops=array('list','edit','savemodel','delete','statuschange','sort_service');
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename="xc_train_article";
        switch($op){
            case 'list':
                $version_id=$_GPC['version_id'];
                $condition=array();
                $condition['uniacid']=$uniacid;
                if(!empty($_GPC['title'])){
                    $title=$_GPC['title'];
                    $condition['title LIKE']='%'.$_GPC['title']."%";
                }
                $request=pdo_getall($tablename,$condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list=pdo_getall($tablename,$condition,array() , '' , "createtime DESC" , array($pageindex,$pagesize));

                include $this->template("Article/list");
                break;
            case 'edit':
                if(!empty($_GPC['id'])){
                    $list=pdo_get($tablename,array('id'=>$_GPC['id']));
                }else{
                    $list['status']=1;
                }
                include $this->template("Article/edit");
                break;
            case 'savemodel':
                $condition=array();
                $condition['uniacid']=$uniacid;
                $condition['title']=$_GPC['title'];
                $condition['content']=$_GPC['content'];
                $condition['type']=$_GPC['type'];
                $condition['link']=$_GPC['link'];
                $condition['btn']=$_GPC['btn'];
                $condition['link_type']=$_GPC['link_type'];
                if(empty($_GPC['id'])){
                    $request=pdo_insert($tablename,$condition);
                }else{
                    $request=pdo_update($tablename,$condition,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request=pdo_update($tablename,array("status"=>$_GPC['status']),array("id"=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request=pdo_delete($tablename,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'sort_service':
                $condition=array();
                $condition['uniacid']=$uniacid;
                if(!empty($_GPC['xname'])){
                    $xname=$_GPC['xname'];
                    $condition['name LIKE']='%'.$_GPC['xname']."%";
                }
                if(!empty($_GPC['cid'])){
                    $cid=$_GPC['cid'];
                    $condition['cid']=$_GPC['cid'];
                }
                $request=pdo_getall('xc_beauty_service',$condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 6;
                $pager = pagination($total, $pageindex, $pagesize);
                $list=pdo_getall('xc_beauty_service',$condition,array() , '' , "sort DESC,createtime DESC" , array($pageindex,$pagesize));
                $class=pdo_getall("xc_beauty_service_class",array("status"=>1,'uniacid'=>$uniacid),array(),"","sort DESC,createtime DESC");
                $datalist=array();
                if($class){
                    foreach($class as $x){
                        $datalist[$x['id']]=$x['name'];
                    }
                }
                if($list){
                    foreach($list as &$y){
                        $y['cidname']=$datalist[$y['cid']];
                    }
                }
                include $this->template("Article/sort_service");
                break;
        }
    }
    /**
     * 用户列表
     */
    public function doWebUser(){
        $ops=array('list','statuschange','service','shop');

        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];

        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename="xc_train_userinfo";
        switch($op){
            case 'list':
                $condition=array();
                $condition['uniacid']=$uniacid;
                if(!empty($_GPC['nick'])){
                    $nick=$_GPC['nick'];
                    $condition['nick LIKE']='%'.base64_encode($_GPC['nick'])."%";
                }
                if(!empty($_GPC['openid'])){
                    $openid=$_GPC['openid'];
                    $condition['openid LIKE']='%'.$_GPC['openid']."%";
                }
                $request=pdo_getall($tablename,$condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list=pdo_getall($tablename,$condition,array() , '' , "createtime DESC" , array($pageindex,$pagesize));
                if($list){
                    $store=pdo_getall("xc_train_school",array("status"=>1,'uniacid'=>$uniacid));
                    $datalist=array();
                    if($store){
                        foreach($store as $s){
                            $datalist[$s['id']]=$s;
                        }
                    }
                    foreach($list as &$x){
                        $x['nick']=base64_decode($x['nick']);
                        $x['card_id']=$x['createtime'];
                        if($x['shop']==-1){
                            $x['shop_name']='无权限';
                        }else if($x['shop']==1){
                            $x['shop_name']='管理员';
                        }else if($x['shop']==2){
                            if(!empty($datalist[$x['shop_id']])){
                                $x['shop_name']=$datalist[$x['shop_id']]['name'];
                            }else{
                                $x['shop_name']='校区不存在';
                            }
                        }
                    }
                }
                include $this->template("User/list");
                break;
            case 'statuschange':
                $request=pdo_update($tablename,array("shop"=>$_GPC['status']),array("id"=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'service':
                $tablename='xc_train_school';
                $version_id=$_GPC['version_id'];
                $condition=array();
                $condition['uniacid']=$uniacid;
                if(!empty($_GPC['xname'])){
                    $xname=$_GPC['xname'];
                    $condition['name LIKE']='%'.$_GPC['xname']."%";
                }
                $request=pdo_getall($tablename,$condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 6;
                $pager = pagination($total, $pageindex, $pagesize);
                $list=pdo_getall($tablename,$condition,array() , '' , "sort desc,createtime DESC" , array($pageindex,$pagesize));
                include $this->template("User/service");
                break;
            //商家中心
            case 'shop':
                $condition['shop']=$_GPC['shop'];
                if(!empty($_GPC['shop_id'])){
                    $condition['shop_id']=$_GPC['shop_id'];
                }else{
                    $condition['shop_id']=null;
                }
                $request=pdo_update($tablename,$condition,array("id"=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
        }
    }
    /**
     * 新闻
     */
    public function doWebNew(){
        $ops=array('list','edit','savemodel','delete','statuschange');
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename="xc_train_news";
        switch($op){
            case 'list':
                $version_id=$_GPC['version_id'];
                $condition=array();
                $condition['uniacid']=$uniacid;
                if(!empty($_GPC['title'])){
                    $title=$_GPC['title'];
                    $condition['title LIKE']='%'.$_GPC['title']."%";
                }
                $request=pdo_getall($tablename,$condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list=pdo_getall($tablename,$condition,array() , '' , "sort DESC,createtime DESC" , array($pageindex,$pagesize));
                include $this->template("New/list");
                break;
            case 'edit':
                if(!empty($_GPC['id'])){
                    $list=pdo_get($tablename,array('id'=>$_GPC['id']));
                }else{
                    $list['status']=1;
                }
                include $this->template("New/edit");
                break;
            case 'savemodel':
                $condition=array();
                $condition['uniacid']=$uniacid;
                $condition['title']=$_GPC['title'];
                $condition['simg']=$_GPC['simg'];
                $condition['short_info']=$_GPC['short_info'];
                $condition['link']=$_GPC['link'];
                if(empty($_GPC['status'])){
                    $condition['status']=-1;
                }else{
                    $condition['status']=$_GPC['status'];
                }
                if(empty($_GPC['sort'])){
                    $condition['sort']=0;
                }else{
                    $condition['sort']=$_GPC['sort'];
                }
                if(empty($_GPC['id'])){
                    $request=pdo_insert($tablename,$condition);
                }else{
                    $request=pdo_update($tablename,$condition,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request=pdo_update($tablename,array("status"=>$_GPC['status']),array("id"=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request=pdo_delete($tablename,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
        }
    }
    /**
     * 分类
     */
    public function doWebService_class()
    {
        $ops = array('list', 'edit', 'savemodel', 'delete', 'statuschange');
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = "xc_train_service_class";
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . "%";
                }
                $request = pdo_getall($tablename, $condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', "sort DESC,createtime DESC", array($pageindex, $pagesize));

                include $this->template("Service_class/list");
                break;
            case 'edit':
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array('id' => $_GPC['id']));
                } else {
                    $list['status'] = 1;
                    $list['type']=1;
                }
                include $this->template("Service_class/edit");
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['name'] = $_GPC['name'];
                $condition['type']=$_GPC['type'];
                if (empty($_GPC['status'])) {
                    $condition['status'] = -1;
                } else {
                    $condition['status'] = $_GPC['status'];
                }
                if (empty($_GPC['sort'])) {
                    $condition['sort'] = 0;
                } else {
                    $condition['sort'] = $_GPC['sort'];
                }
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array('id' => $_GPC['id'], 'uniacid' => $uniacid));
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array($_GPC['name'] => $_GPC['status']), array("id" => $_GPC['id'], 'uniacid' => $uniacid));
                if ($request) {
                    $json = array('status' => 1, 'msg' => '操作成功');
                    echo json_encode($json);
                } else {
                    $json = array('status' => 0, 'msg' => '操作失败');
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array('id' => $_GPC['id'], 'uniacid' => $uniacid));
                if ($request) {
                    $json = array('status' => 1, 'msg' => '操作成功');
                    echo json_encode($json);
                } else {
                    $json = array('status' => 0, 'msg' => '操作失败');
                    echo json_encode($json);
                }
                break;
        }
    }
    /**
     * 列表
     */
    public function doWebService(){
        $ops=array('list','edit','savemodel','delete','statuschange','teacher');
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename="xc_train_service";
        switch($op){
            case 'list':
                $version_id=$_GPC['version_id'];
                $condition=array();
                $condition['uniacid']=$uniacid;
                $condition['cid !=']=-1;
                if(!empty($_GPC['xname'])){
                    $xname=$_GPC['xname'];
                    $condition['name LIKE']='%'.$_GPC['xname']."%";
                }
                if(!empty($_GPC['cid'])){
                    $cid=$_GPC['cid'];
                    $condition['cid']=$_GPC['cid'];
                }
                $request=pdo_getall($tablename,$condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list=pdo_getall($tablename,$condition,array() , '' , "sort DESC,createtime DESC" , array($pageindex,$pagesize));
                $class=pdo_getall("xc_train_service_class",array("status"=>1,'uniacid'=>$uniacid,'type'=>1),array(),"","sort DESC,createtime DESC");
                $datalist=array();
                if($class){
                    foreach($class as $x){
                        $datalist[$x['id']]=$x['name'];
                    }
                }
                if($list){
                    foreach($list as &$y){
                        $y['cidname']=$datalist[$y['cid']];
                    }
                }
                include $this->template("Service/list");
                break;
            case 'edit':
                $class=pdo_getall("xc_train_service_class",array("status"=>1,'uniacid'=>$uniacid,'type'=>1),array(),"","sort DESC,createtime DESC");
                if(!empty($_GPC['id'])){
                    $list=pdo_get($tablename,array('id'=>$_GPC['id']));
                    if($list){
                        if(!empty($list['teacher'])){
                            $list['teacher']=json_decode($list['teacher'],true);
                        }
                    }
                }else{
                    $list['status']=1;
                    $list['content_type']=1;
                }
                include $this->template("Service/edit");
                break;
            case 'savemodel':
                $condition=array();
                $condition['uniacid']=$uniacid;
                $condition['name']=$_GPC['name'];
                $condition['cid']=$_GPC['cid'];
                $condition['bimg']=$_GPC['bimg'];
                $condition['xueqi']=$_GPC['xueqi'];
                $condition['keshi']=$_GPC['keshi'];
                $condition['price']=$_GPC['price'];
                $condition['content']=$_GPC['content'];
                $condition['content_type']=$_GPC['content_type'];
                $condition['content2']=$_GPC['content2'];
                $condition['can_use']=$_GPC['can_use'];
                $condition['zan']=$_GPC['zan'];
                $condition['click']=$_GPC['click'];
                if(!empty($_GPC['teacher'])){
                    $condition['teacher']=htmlspecialchars_decode($_GPC['teacher']);
                }
                if(empty($_GPC['status'])){
                    $condition['status']=-1;
                }else{
                    $condition['status']=$_GPC['status'];
                }
                if(empty($_GPC['index'])){
                    $condition['index']=-1;
                }else{
                    $condition['index']=$_GPC['index'];
                }
                if(empty($_GPC['tui'])){
                    $condition['tui']=-1;
                }else{
                    $condition['tui']=$_GPC['tui'];
                }
                if(empty($_GPC['sort'])){
                    $condition['sort']=0;
                }else{
                    $condition['sort']=$_GPC['sort'];
                }
                if(empty($_GPC['id'])){
                    $request=pdo_insert($tablename,$condition);
                }else{
                    $request=pdo_update($tablename,$condition,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request=pdo_update($tablename,array($_GPC['name']=>$_GPC['status']),array("id"=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request=pdo_delete($tablename,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'teacher':
                $tablename="xc_train_teacher";
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . "%";
                }
                $request = pdo_getall($tablename, $condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize =6;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', "sort DESC,createtime DESC", array($pageindex, $pagesize));

                include $this->template("Service/teacher");
                break;
        }
    }
    /**
     * 名师
     */
    public function doWebTeacher()
    {
        $ops = array('list', 'edit', 'savemodel', 'delete', 'statuschange');
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = "xc_train_teacher";
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . "%";
                }
                $request = pdo_getall($tablename, $condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', "sort DESC,createtime DESC", array($pageindex, $pagesize));
                $class=pdo_getall("xc_train_service_class",array("status"=>1,'uniacid'=>$uniacid,'type'=>2),array(),"","sort DESC,createtime DESC");
                $datalist=array();
                if($class) {
                    foreach ($class as $x) {
                        $datalist[$x['id']] = $x['name'];
                    }
                }
                if($list){
                    foreach($list as &$y){
                        $y['cidname']=$datalist[$y['cid']];
                    }
                }
                include $this->template("Teacher/list");
                break;
            case 'edit':
                $class=pdo_getall("xc_train_service_class",array("status"=>1,'uniacid'=>$uniacid,'type'=>2),array(),"","sort DESC,createtime DESC");
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array('id' => $_GPC['id']));
                } else {
                    $list['status'] = 1;
                    $list['content_type']=1;
                }
                include $this->template("Teacher/edit");
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['name'] = $_GPC['name'];
                $condition['simg']=$_GPC['simg'];
                $condition['task']=$_GPC['task'];
                $condition['short_info']=$_GPC['short_info'];
                $condition['pclass']=$_GPC['pclass'];
                $condition['content_type']=$_GPC['content_type'];
                $condition['content2']=$_GPC['content2'];
                $condition['cid']=$_GPC['cid'];
                if (empty($_GPC['status'])) {
                    $condition['status'] = -1;
                } else {
                    $condition['status'] = $_GPC['status'];
                }
                if (empty($_GPC['sort'])) {
                    $condition['sort'] = 0;
                } else {
                    $condition['sort'] = $_GPC['sort'];
                }
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array('id' => $_GPC['id'], 'uniacid' => $uniacid));
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array($_GPC['name'] => $_GPC['status']), array("id" => $_GPC['id'], 'uniacid' => $uniacid));
                if ($request) {
                    $json = array('status' => 1, 'msg' => '操作成功');
                    echo json_encode($json);
                } else {
                    $json = array('status' => 0, 'msg' => '操作失败');
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array('id' => $_GPC['id'], 'uniacid' => $uniacid));
                if ($request) {
                    $json = array('status' => 1, 'msg' => '操作成功');
                    echo json_encode($json);
                } else {
                    $json = array('status' => 0, 'msg' => '操作失败');
                    echo json_encode($json);
                }
                break;
        }
    }
    /**
     * 开课
     */
    public function doWebService_team(){
        $ops = array('list', 'edit', 'savemodel', 'delete', 'statuschange');
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = "xc_train_service_team";
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['mark'])) {
                    $mark = $_GPC['mark'];
                    $condition['mark LIKE'] = '%' . $_GPC['mark'] . "%";
                }
                if (!empty($_GPC['pid'])) {
                    $pid = $_GPC['pid'];
                    $condition['pid'] = $_GPC['pid'];
                }
                if (!empty($_GPC['type'])) {
                    $type= $_GPC['type'];
                    $condition['type'] = $_GPC['type'];
                }
                $request = pdo_getall($tablename, $condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', "createtime DESC", array($pageindex, $pagesize));
                $class=pdo_getall("xc_train_service",array("uniacid"=>$uniacid),array(),"","sort DESC,createtime DESC");
                if($list){
                    $datalist=array();
                    if($class){
                        foreach($class as $c){
                            $datalist[$c['id']]=$c;
                        }
                    }
                    foreach($list as &$x){
                        $x['name']=$datalist[$x['pid']]['name'];
                    }
                }
                include $this->template("Service_team/list");
                break;
            case 'edit':
                $class=pdo_getall("xc_train_service",array("status"=>1,'uniacid'=>$uniacid),array(),"","sort DESC,createtime DESC");
                if($class){
                    foreach($class as &$x){
                        if(!empty($x['price'])){
                            $x['fee']=1;
                        }else{
                            $x['fee']=-1;
                        }
                    }
                }
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array('id' => $_GPC['id']));
                } else {
                    $list['status'] = 1;
                    $list['type']=1;
                }
                include $this->template("Service_team/edit");
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['pid'] = $_GPC['pid'];
                $condition['mark']=$_GPC['mark'];
                $condition['type']=$_GPC['type'];
                $condition['start_time']=$_GPC['start_time'];
                $condition['end_time']=$_GPC['end_time'];
                $condition['least_member']=$_GPC['least_member'];
                $condition['more_member']=$_GPC['more_member'];
                if (empty($_GPC['status'])) {
                    $condition['status'] = -1;
                } else {
                    $condition['status'] = $_GPC['status'];
                }
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array('id' => $_GPC['id'], 'uniacid' => $uniacid));
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array($_GPC['name'] => $_GPC['status']), array("id" => $_GPC['id'], 'uniacid' => $uniacid));
                if ($request) {
                    $json = array('status' => 1, 'msg' => '操作成功');
                    echo json_encode($json);
                } else {
                    $json = array('status' => 0, 'msg' => '操作失败');
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array('id' => $_GPC['id'], 'uniacid' => $uniacid));
                if ($request) {
                    $json = array('status' => 1, 'msg' => '操作成功');
                    echo json_encode($json);
                } else {
                    $json = array('status' => 0, 'msg' => '操作失败');
                    echo json_encode($json);
                }
                break;
        }
    }
    /**
     * 商城
     */
    public function doWebMall()
    {
        $ops = array('list', 'edit', 'savemodel', 'delete', 'statuschange');
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = "xc_train_mall";
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . "%";
                }
                $request = pdo_getall($tablename, $condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', "sort DESC,createtime DESC", array($pageindex, $pagesize));
                $class=pdo_getall("xc_train_service_class",array("status"=>1,'uniacid'=>$uniacid,'type'=>3),array(),"","sort DESC,createtime DESC");
                $datalist=array();
                if($class){
                    foreach($class as $x){
                        $datalist[$x['id']]=$x['name'];
                    }
                }
                if($list){
                    foreach($list as &$y){
                        $y['cid_name']=$datalist[$y['cid']];
                    }
                }
                include $this->template("Mall/list");
                break;
            case 'edit':
                $class=pdo_getall("xc_train_service_class",array("status"=>1,'uniacid'=>$uniacid,'type'=>3),array(),"","sort DESC,createtime DESC");
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array('id' => $_GPC['id']));
                    if($list){
                        if(!empty($list['bimg'])){
                            $list['bimg']=explode(",",$list['bimg']);
                        }
                        $list['format']=json_decode($list['format'],true);
                    }
                } else {
                    $list['status'] = 1;
                }
                include $this->template("Mall/edit");
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['name'] = $_GPC['name'];
                $condition['title']=$_GPC['title'];
                $condition['cid']=$_GPC['cid'];
                $condition['simg']=$_GPC['simg'];
                if(!empty($_GPC['bimg'])){
                    $condition['bimg']=implode(",",$_GPC['bimg']);
                }else{
                    $condition['bimg']='';
                }
                $condition['sold']=$_GPC['sold'];
                $condition['price']=$_GPC['price'];
                $condition['format']=htmlspecialchars_decode($_GPC['format']);
                $condition['content']=$_GPC['content'];
                if (empty($_GPC['status'])) {
                    $condition['status'] = -1;
                } else {
                    $condition['status'] = $_GPC['status'];
                }
                if (empty($_GPC['sort'])) {
                    $condition['sort'] = 0;
                } else {
                    $condition['sort'] = $_GPC['sort'];
                }
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array('id' => $_GPC['id'], 'uniacid' => $uniacid));
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array($_GPC['name'] => $_GPC['status']), array("id" => $_GPC['id'], 'uniacid' => $uniacid));
                if ($request) {
                    $json = array('status' => 1, 'msg' => '操作成功');
                    echo json_encode($json);
                } else {
                    $json = array('status' => 0, 'msg' => '操作失败');
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array('id' => $_GPC['id'], 'uniacid' => $uniacid));
                if ($request) {
                    $json = array('status' => 1, 'msg' => '操作成功');
                    echo json_encode($json);
                } else {
                    $json = array('status' => 0, 'msg' => '操作失败');
                    echo json_encode($json);
                }
                break;
        }
    }
    /**
     * 视频分类
     */
    public function doWebVideo_class()
    {
        $ops = array('list', 'edit', 'savemodel', 'delete', 'statuschange');
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = "xc_train_video_class";
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . "%";
                }
                $request = pdo_getall($tablename, $condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', "sort DESC,createtime DESC", array($pageindex, $pagesize));

                include $this->template("Video_class/list");
                break;
            case 'edit':
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array('id' => $_GPC['id']));
                } else {
                    $list['status'] = 1;
                }
                include $this->template("Video_class/edit");
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['name'] = $_GPC['name'];
                if (empty($_GPC['status'])) {
                    $condition['status'] = -1;
                } else {
                    $condition['status'] = $_GPC['status'];
                }
                if (empty($_GPC['sort'])) {
                    $condition['sort'] = 0;
                } else {
                    $condition['sort'] = $_GPC['sort'];
                }
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array('id' => $_GPC['id'], 'uniacid' => $uniacid));
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array($_GPC['name'] => $_GPC['status']), array("id" => $_GPC['id'], 'uniacid' => $uniacid));
                if ($request) {
                    $json = array('status' => 1, 'msg' => '操作成功');
                    echo json_encode($json);
                } else {
                    $json = array('status' => 0, 'msg' => '操作失败');
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array('id' => $_GPC['id'], 'uniacid' => $uniacid));
                if ($request) {
                    $json = array('status' => 1, 'msg' => '操作成功');
                    echo json_encode($json);
                } else {
                    $json = array('status' => 0, 'msg' => '操作失败');
                    echo json_encode($json);
                }
                break;
        }
    }
    /**
     * 视频列表
     */
    public function doWebVideo(){
        $ops=array('list','edit','savemodel','delete','statuschange','teacher','record','video_change');
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename="xc_train_video";
        switch($op){
            case 'list':
                $version_id=$_GPC['version_id'];
                $condition=array();
                $condition['uniacid']=$uniacid;
                $condition['cid !=']=-1;
                if(!empty($_GPC['xname'])){
                    $xname=$_GPC['xname'];
                    $condition['name LIKE']='%'.$_GPC['xname']."%";
                }
                if(!empty($_GPC['cid'])){
                    $cid=$_GPC['cid'];
                    $condition['cid']=$_GPC['cid'];
                }
                $request=pdo_getall($tablename,$condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list=pdo_getall($tablename,$condition,array() , '' , "sort DESC,createtime DESC" , array($pageindex,$pagesize));
                $class=pdo_getall("xc_train_video_class",array('uniacid'=>$uniacid),array(),"","sort DESC,createtime DESC");
                $datalist=array();
                if($class){
                    foreach($class as $x){
                        $datalist[$x['id']]=$x['name'];
                    }
                }
                $service=pdo_getall("xc_train_service",array("uniacid"=>$uniacid),array(),"","sort DESC,createtime DESC");
                $service_list=array();
                if($service){
                    foreach($service as $s){
                        $service_list[$s['id']]=$s;
                    }
                }
                if($list){
                    foreach($list as &$y){
                        $y['cidname']=$datalist[$y['cid']];
                        $y['service_name']=$service_list[$y['pid']]['name'];
                    }
                }
                include $this->template("Video/list");
                break;
            case 'edit':
                $class=pdo_getall("xc_train_video_class",array("status"=>1,'uniacid'=>$uniacid),array(),"","sort DESC,createtime DESC");
                $service=pdo_getall("xc_train_service",array("status"=>1,'uniacid'=>$uniacid),array(),"","sort DESC,createtime DESC");
                if(!empty($_GPC['id'])){
                    $list=pdo_get($tablename,array('id'=>$_GPC['id']));
                }else{
                    $list['status']=1;
                    $list['type']=1;
                }
                include $this->template("Video/edit");
                break;
            case 'savemodel':
                $condition=array();
                $condition['uniacid']=$uniacid;
                $condition['name']=$_GPC['name'];
                $condition['cid']=$_GPC['cid'];
                $condition['pid']=$_GPC['pid'];
                $condition['type']=$_GPC['type'];
                $condition['video']=$_GPC['video'];
                $condition['vid']=$_GPC['vid'];
                $condition['link']=$_GPC['link'];
                $condition['bimg']=$_GPC['bimg'];
                $condition['price']=$_GPC['price'];
                $condition['teacher_id']=$_GPC['teacher_id'];
                $condition['teacher_name']=$_GPC['teacher_name'];
                if(empty($_GPC['status'])){
                    $condition['status']=-1;
                }else{
                    $condition['status']=$_GPC['status'];
                }
                if(empty($_GPC['sort'])){
                    $condition['sort']=0;
                }else{
                    $condition['sort']=$_GPC['sort'];
                }
                if(empty($_GPC['id'])){
                    $request=pdo_insert($tablename,$condition);
                }else{
                    $request=pdo_update($tablename,$condition,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request=pdo_update($tablename,array($_GPC['name']=>$_GPC['status']),array("id"=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request=pdo_delete($tablename,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'teacher':
                $tablename="xc_train_teacher";
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . "%";
                }
                $request = pdo_getall($tablename, $condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 6;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', "sort DESC,createtime DESC", array($pageindex, $pagesize));

                include $this->template("Video/teacher");
                break;
            case 'record':
                $tablename='xc_train_order';
                $version_id=$_GPC['version_id'];
                $condition=array();
                $condition['uniacid']=$uniacid;
                $condition['status']=1;
                $condition['order_type']=3;
                if(!empty($_GPC['out_trade_no'])){
                    $out_trade_no=$_GPC['out_trade_no'];
                    $condition['out_trade_no LIKE']='%'.$_GPC['out_trade_no']."%";
                }
                if(!empty($_GPC['openid'])){
                    $openid=$_GPC['openid'];
                    $condition['openid LIKE']='%'.$_GPC['openid']."%";
                }
                $request=pdo_getall($tablename,$condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list=pdo_getall($tablename,$condition,array() , '' , "createtime DESC" , array($pageindex,$pagesize));
                include $this->template("Video/record");
                break;
            case 'video_change':
                $link=xc_txvideoUrl($_GPC['link'],1);
                $json=array('status'=>1,'msg'=>'操作成功','data'=>array("link"=>$link));
                echo json_encode($json);
                break;
        }
    }
    /**
     * 分校管理
     */
    public function doWebSchool(){
        $ops=array('list','edit','savemodel','delete','statuschange');
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename="xc_train_school";
        switch($op){
            case 'list':
                $version_id=$_GPC['version_id'];
                $condition=array();
                $condition['uniacid']=$uniacid;
                if(!empty($_GPC['xname'])){
                    $xname=$_GPC['xname'];
                    $condition['name LIKE']='%'.$_GPC['xname']."%";
                }
                if(!empty($_GPC['cid'])){
                    $cid=$_GPC['cid'];
                    $condition['cid']=$_GPC['cid'];
                }
                $request=pdo_getall($tablename,$condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list=pdo_getall($tablename,$condition,array() , '' , "sort DESC,createtime DESC" , array($pageindex,$pagesize));
                include $this->template("School/list");
                break;
            case 'edit':
                if(!empty($_GPC['id'])){
                    $list=pdo_get($tablename,array('id'=>$_GPC['id']));
                    if($list){
                        if(!empty($list['teacher'])){
                            $list['teacher']=json_decode($list['teacher'],true);
                        }
                        $list['content']=json_decode($list['content'],true);
                    }
                }else{
                    $list['status']=1;
                }
                include $this->template("School/edit");
                break;
            case 'savemodel':
                $condition=array();
                $condition['uniacid']=$uniacid;
                $condition['name']=$_GPC['name'];
                $condition['simg']=$_GPC['simg'];
                $condition['mobile']=$_GPC['mobile'];
                $condition['address']=$_GPC['address'];
                $condition['longitude']=$_GPC['longitude'];
                $condition['latitude']=$_GPC['latitude'];
                $condition['plan_date']=$_GPC['plan_date'];
                $condition['sms']=$_GPC['sms'];
                $condition['content']=htmlspecialchars_decode($_GPC['content']);
                if(!empty($_GPC['teacher'])){
                    $condition['teacher']=htmlspecialchars_decode($_GPC['teacher']);
                }
                if(empty($_GPC['status'])){
                    $condition['status']=-1;
                }else{
                    $condition['status']=$_GPC['status'];
                }
                if(empty($_GPC['sort'])){
                    $condition['sort']=0;
                }else{
                    $condition['sort']=$_GPC['sort'];
                }
                if(empty($_GPC['id'])){
                    $request=pdo_insert($tablename,$condition);
                }else{
                    $request=pdo_update($tablename,$condition,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request=pdo_update($tablename,array($_GPC['name']=>$_GPC['status']),array("id"=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request=pdo_delete($tablename,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
        }
    }
    /**
     * 优惠券
     */
    public function doWebCoupon(){
        $ops=array('list','edit','savemodel','delete','statuschange');

        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];

        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename="xc_train_coupon";
        switch($op){
            case 'list':
                $condition=array();
                $condition['uniacid']=$uniacid;
                if(!empty($_GPC['xname'])){
                    $xname=$_GPC['xname'];
                    $condition['name LIKE']='%'.$_GPC['xname']."%";
                }
                $request=pdo_getall($tablename,$condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list=pdo_getall($tablename,$condition,array() , '' , "sort DESC,createtime DESC" , array($pageindex,$pagesize));
                if($list){
                    foreach($list as &$x){
                        $x['times']=json_decode($x['times'],true);
                    }
                }
                include $this->template("Coupon/list");
                break;
            case 'edit':
                if(!empty($_GPC['id'])){
                    $list=pdo_get($tablename,array('id'=>$_GPC['id']));
                    $list['times']=json_decode($list['times'],true);
                }else{
                    $list['status']=1;
                    $list['total']=-1;
                }
                include $this->template("Coupon/edit");
                break;
            case 'savemodel':
                $condition=array();
                $condition['uniacid']=$uniacid;
                $condition['name']=$_GPC['name'];
                $condition['condition']=$_GPC['condition'];
                $condition['times']=json_encode($_GPC['times']);
                if(!empty($_GPC['istotal'])){
                    $condition['total']=$_GPC['total'];
                }else{
                    $condition['total']=-1;
                }
                if(!empty($_GPC['status'])){
                    $condition['status']=$_GPC['status'];
                }else{
                    $condition['status']=-1;
                }
                if(empty($_GPC['sort'])){
                    $condition['sort']=0;
                }else{
                    $condition['sort']=$_GPC['sort'];
                }
                if(empty($_GPC['id'])){
                    $request=pdo_insert($tablename,$condition);
                }else{
                    $request=pdo_update($tablename,$condition,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request=pdo_update($tablename,array("status"=>$_GPC['status']),array("id"=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request=pdo_delete($tablename,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
        }
    }
    /**
     * 优惠活动
     */
    public function doWebActive()
    {
        $ops = array('list', 'edit', 'savemodel', 'delete', 'statuschange','article');
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = "xc_train_active";
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . "%";
                }
                $request = pdo_getall($tablename, $condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', "sort DESC,createtime DESC", array($pageindex, $pagesize));
                if($list){
                    $prize=pdo_getall("xc_train_gua",array("uniacid"=>$uniacid));
                    $datalist=array();
                    if($prize){
                        foreach($prize as $p){
                            $datalist[$p['id']]=$p;
                        }
                    }
                    foreach($list as &$x){
                        if($x['type']==2){
                            $x['list']=json_decode($x['list'],true);
                            $x['bimg']=$datalist[$x['list'][0]['id']]['bimg'];
                        }
                    }
                }
                include $this->template("Active/list");
                break;
            case 'edit':
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array('id' => $_GPC['id']));
                    if($list){
                        $list['times']=array("start"=>$list['start_time'],'end'=>$list['end_time']);
                        $list['list']=json_decode($list['list'],true);
                        $list['share_type']=2;
                    }
                } else {
                    $list['status'] = 1;
                    $list['share_type']=2;
                    $list['type']=1;
                }
                include $this->template("Active/edit");
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['name'] = $_GPC['name'];
                $condition['type']=$_GPC['type'];
                $condition['simg']=$_GPC['simg'];
                $condition['bimg']=$_GPC['bimg'];
                $condition['prize']=$_GPC['prize'];
                if(intval($_GPC['share'])<2){
                    $condition['share']=2;
                }else{
                    $condition['share']=$_GPC['share'];
                }
                if($_GPC['type']==2){
                    if(intval($_GPC['share2'])<2){
                        $condition['share']=2;
                    }else{
                        $condition['share']=$_GPC['share2'];
                    }
                }
                if(!empty($_GPC['list'])){
                    $condition['list']=htmlspecialchars_decode($_GPC['list']);
                }
                $condition['gua_img']=$_GPC['gua_img'];
                $condition['content']=$_GPC['content'];
                $condition['link']=$_GPC['link'];
                $condition['start_time']=$_GPC['times']['start'];
                $condition['end_time']=$_GPC['times']['end'];
                $condition['total']=$_GPC['total'];
                $condition['share_img']=$_GPC['share_img'];
                $condition['share_type']=$_GPC['share_type'];
                if (empty($_GPC['status'])) {
                    $condition['status'] = -1;
                } else {
                    $condition['status'] = $_GPC['status'];
                }
                if (empty($_GPC['sort'])) {
                    $condition['sort'] = 0;
                } else {
                    $condition['sort'] = $_GPC['sort'];
                }
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array('id' => $_GPC['id'], 'uniacid' => $uniacid));
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array($_GPC['name'] => $_GPC['status']), array("id" => $_GPC['id'], 'uniacid' => $uniacid));
                if ($request) {
                    $json = array('status' => 1, 'msg' => '操作成功');
                    echo json_encode($json);
                } else {
                    $json = array('status' => 0, 'msg' => '操作失败');
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array('id' => $_GPC['id'], 'uniacid' => $uniacid));
                if ($request) {
                    $json = array('status' => 1, 'msg' => '操作成功');
                    echo json_encode($json);
                } else {
                    $json = array('status' => 0, 'msg' => '操作失败');
                    echo json_encode($json);
                }
                break;
            case 'article':
                $version_id=$_GPC['version_id'];
                $condition=array();
                $condition['status']=1;
                $condition['uniacid']=$uniacid;
                if(!empty($_GPC['xname'])){
                    $xname=$_GPC['xname'];
                    $condition['name LIKE']='%'.$_GPC['xname']."%";
                }
                $request=pdo_getall('xc_train_gua',$condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 6;
                $pager = pagination($total, $pageindex, $pagesize);
                $list=pdo_getall('xc_train_gua',$condition,array() , '' , "createtime DESC" , array($pageindex,$pagesize));
                include $this->template("Active/article");
                break;
        }
    }
    /**
     * 评论
     */
    public function doWebDiscuss(){
        $ops=array('list','statuschange','delete');

        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];

        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename="xc_train_discuss";
        switch($op){
            case 'list':
                $condition=array();
                $condition['uniacid']=$uniacid;
                if(!empty($_GPC['openid'])){
                    $openid=$_GPC['openid'];
                    $condition['openid LIKE']='%'.$_GPC['openid']."%";
                }
                $request=pdo_getall($tablename,$condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list=pdo_getall($tablename,$condition,array() , '' , "createtime DESC" , array($pageindex,$pagesize));
                $service=pdo_getall("xc_train_service",array("uniacid"=>$uniacid));
                $datalist=array();
                if($service){
                    foreach($service as $x){
                        $datalist[$x['id']]=$x;
                    }
                }
                foreach($list as &$y){
                    $y['pname']=$datalist[$y['pid']]['name'];
                }
                include $this->template("Discuss/list");
                break;
            case 'statuschange':
                $request=pdo_update($tablename,array("status"=>$_GPC['status']),array("id"=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $discuss=pdo_get($tablename,array("id"=>$_GPC['id'],'uniacid'=>$uniacid));
                $request=pdo_delete($tablename,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $service=pdo_get("xc_beauty_service",array("status"=>1,'id'=>$discuss['pid']));
                    if($service){
                        $data['discuss_total']=$service['discuss_total']-1;
                        if($discuss['score']==1){
                            $data['good_total']=$service['good_total']-1;
                        }else if($discuss['score']==2){
                            $data['middle_total']=$service['middle_total']-1;
                        }else if($discuss['score']==3){
                            $data['bad_total']=$service['bad_total']-1;
                        }
                        pdo_update("xc_beauty_service",$data,array("status"=>1,'id'=>$discuss['pid']));
                    }
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
        }
    }
    /**
     * 奖品
     */
    public function doWebPrize2(){
        $ops=array('list','edit','savemodel','delete','statuschange');
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename="xc_train_gua";
        switch($op){
            case 'list':
                $version_id=$_GPC['version_id'];
                $condition=array();
                $condition['uniacid']=$uniacid;
                if(!empty($_GPC['xname'])){
                    $xname=$_GPC['xname'];
                    $condition['name LIKE']='%'.$_GPC['xname']."%";
                }
                $request=pdo_getall($tablename,$condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list=pdo_getall($tablename,$condition,array() , '' , "createtime DESC" , array($pageindex,$pagesize));
                include $this->template("Prize2/list");
                break;
            case 'edit':
                if(!empty($_GPC['id'])){
                    $list=pdo_get($tablename,array('id'=>$_GPC['id']));
                }else{
                    $list['status']=1;
                }
                include $this->template("Prize2/edit");
                break;
            case 'savemodel':
                $condition=array();
                $condition['uniacid']=$uniacid;
                $condition['name']=$_GPC['name'];
                $condition['bimg']=$_GPC['bimg'];
                $condition['times']=$_GPC['times'];
                if(empty($_GPC['status'])){
                    $condition['status']=-1;
                }else{
                    $condition['status']=$_GPC['status'];
                }
                if(empty($_GPC['id'])){
                    $request=pdo_insert($tablename,$condition);
                }else{
                    $request=pdo_update($tablename,$condition,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request=pdo_update($tablename,array("status"=>$_GPC['status']),array("id"=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request=pdo_delete($tablename,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
        }
    }
    /**
     * 获奖记录
     */
    public function doWebPrize(){
        $ops=array('list','statuschange','add_orders');
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename="xc_train_prize";
        switch($op){
            case 'list':
                $condition=array();
                $condition['status']=1;
                $condition['uniacid']=$uniacid;
                if(!empty($_GPC['openid'])){
                    $openid=$_GPC['openid'];
                    $condition['openid LIKE']='%'.$_GPC['openid']."%";
                }
                if(!empty($_GPC['use'])){
                    $use=$_GPC['use'];
                    $condition['use']=$_GPC['use'];
                }
                $request=pdo_getall($tablename,$condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list=pdo_getall($tablename,$condition,array() , '' , "createtime DESC" , array($pageindex,$pagesize));
                include $this->template("Prize/list");
                break;
            case 'statuschange':
                $request=pdo_update($tablename,array("use"=>1,'usetime'=>date("Y-m-d H:i:s")),array("status"=>1,'uniacid'=>$uniacid,'id'=>$_GPC['id']));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request=pdo_delete($tablename,array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
                if($request){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
        }
    }
    /**
     * 导航
     */
    public function doWebNav()
    {
        $ops = array('list', 'edit', 'savemodel', 'delete', 'statuschange','article','pclass');
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = "xc_train_nav";
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . "%";
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', "sort DESC,createtime DESC", array($pageindex, $pagesize));
                include $this->template("Nav/list");
                break;
            case 'edit':
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array('id' => $_GPC['id']));
                } else {
                    $list['status'] = 1;
                }
                include $this->template("Nav/edit");
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['name'] = $_GPC['name'];
                $condition['simg']=$_GPC['simg'];
                $condition['link']=$_GPC['link'];
                if (empty($_GPC['status'])) {
                    $condition['status'] = -1;
                } else {
                    $condition['status'] = $_GPC['status'];
                }
                if (empty($_GPC['sort'])) {
                    $condition['sort'] = 0;
                } else {
                    $condition['sort'] = $_GPC['sort'];
                }
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array('id' => $_GPC['id'], 'uniacid' => $uniacid));
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array($_GPC['name'] => $_GPC['status']), array("id" => $_GPC['id'], 'uniacid' => $uniacid));
                if ($request) {
                    $json = array('status' => 1, 'msg' => '操作成功');
                    echo json_encode($json);
                } else {
                    $json = array('status' => 0, 'msg' => '操作失败');
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array('id' => $_GPC['id'], 'uniacid' => $uniacid));
                if ($request) {
                    $json = array('status' => 1, 'msg' => '操作成功');
                    echo json_encode($json);
                } else {
                    $json = array('status' => 0, 'msg' => '操作失败');
                    echo json_encode($json);
                }
                break;
            case 'article':
                $version_id=$_GPC['version_id'];
                $condition=array();
                $condition['uniacid']=$uniacid;
                if(!empty($_GPC['title'])){
                    $title=$_GPC['title'];
                    $condition['title LIKE']='%'.$_GPC['title']."%";
                }
                $request=pdo_getall('xc_train_article',$condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 6;
                $pager = pagination($total, $pageindex, $pagesize);
                $list=pdo_getall('xc_train_article',$condition,array() , '' , "createtime DESC" , array($pageindex,$pagesize));
                $url='https://'.$_SERVER['HTTP_HOST'].'/app/index.php?i='.$uniacid.'&c=entry&do=index&m='.$_GPC['m'];
                if($list){
                    foreach($list as &$x){
                        $x['url']=$url;
                    }
                }
                include $this->template("Nav/article");
                break;
            case 'pclass':
                $version_id=$_GPC['version_id'];
                $condition=array();
                $condition['uniacid']=$uniacid;
                if(!empty($_GPC['xname'])){
                    $xname=$_GPC['xname'];
                    $condition['name LIKE']='%'.$_GPC['xname']."%";
                }
                $request=pdo_getall('xc_train_service_class',$condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 6;
                $pager = pagination($total, $pageindex, $pagesize);
                $list=pdo_getall('xc_train_service_class',$condition,array() , '' , "sort DESC,createtime DESC" , array($pageindex,$pagesize));
                include $this->template("Nav/pclass");
                break;
        }
    }
    /**
     * 分类
     */
    public function doWebCut()
    {
        $ops = array('list', 'edit', 'savemodel', 'delete', 'statuschange');
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = "xc_train_cut";
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . "%";
                }
                $request = pdo_getall($tablename, $condition);

                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', "sort DESC,createtime DESC", array($pageindex, $pagesize));
                $class=pdo_getall("xc_train_service",array("uniacid"=>$uniacid),array(),"","sort DESC,createtime DESC");
                if($list){
                    $datalist=array();
                    if($class){
                        foreach($class as $c){
                            $datalist[$c['id']]=$c;
                        }
                    }
                    foreach($list as &$x){
                        $x['name']=$datalist[$x['pid']]['name'];
                    }
                }
                include $this->template("Cut/list");
                break;
            case 'edit':
                $class=pdo_getall("xc_train_service",array("status"=>1,'uniacid'=>$uniacid),array(),"","sort DESC,createtime DESC");
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array('id' => $_GPC['id']));
                } else {
                    $list['status'] = 1;
                }
                include $this->template("Cut/edit");
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['pid']=$_GPC['pid'];
                $condition['mark']=$_GPC['mark'];
                $condition['end_time']=$_GPC['end_time'];
                $condition['member']=$_GPC['member'];
                $condition['price']=$_GPC['price'];
                $condition['cut_price']=$_GPC['cut_price'];
                $condition['min_price']=$_GPC['min_price'];
                $condition['max_price']=$_GPC['max_price'];
                $condition['join_member']=$_GPC['join_member'];
                if (empty($_GPC['status'])) {
                    $condition['status'] = -1;
                } else {
                    $condition['status'] = $_GPC['status'];
                }
                if (empty($_GPC['sort'])) {
                    $condition['sort'] = 0;
                } else {
                    $condition['sort'] = $_GPC['sort'];
                }
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array('id' => $_GPC['id'], 'uniacid' => $uniacid));
                }
                if(!empty($request)){
                    $json=array('status'=>1,'msg'=>'操作成功');
                    echo json_encode($json);
                }else{
                    $json=array('status'=>0,'msg'=>'操作失败');
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array($_GPC['name'] => $_GPC['status']), array("id" => $_GPC['id'], 'uniacid' => $uniacid));
                if ($request) {
                    $json = array('status' => 1, 'msg' => '操作成功');
                    echo json_encode($json);
                } else {
                    $json = array('status' => 0, 'msg' => '操作失败');
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array('id' => $_GPC['id'], 'uniacid' => $uniacid));
                if ($request) {
                    $json = array('status' => 1, 'msg' => '操作成功');
                    echo json_encode($json);
                } else {
                    $json = array('status' => 0, 'msg' => '操作失败');
                    echo json_encode($json);
                }
                break;
        }
    }
    /**
     * 导出excel
     */
    public function doWebExport(){
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $order=pdo_getall("xc_train_order",array("status"=>1,'uniacid'=>$uniacid),array(),"","createtime DESC");
        $store=pdo_getall("xc_train_school",array("uniacid"=>$uniacid));
        $store_list=array();
        if($store){
            foreach($store as $s){
                $store_list[$s['id']]=$s;
            }
        }
        if($order){
            header("Content-type: application/vnd.ms-excel; charset=utf8");
            header("Content-Disposition: attachment; filename=order.xls");
            $data='<tr>';
            if($_GPC['store']==1){
                $data.='<th>校区</th>';
            }
            if($_GPC['title']==1){
                $data.='<th>服务项目</th>';
            }
            if($_GPC['total']==1){
                $data.='<th>人数</th>';
            }
            if($_GPC['out_trade_no']==1){
                $data.='<th>订单号</th>';
            }
            if($_GPC['wx_out_trade_no']==1){
                $data.='<th>微信订单号</th>';
            }
            if($_GPC['amount']==1){
                $data.='<th>应付款</th>';
            }
            if($_GPC['o_amount']==1){
                $data.='<th>实付款</th>';
            }
            if($_GPC['openid']==1){
                $data.='<th>用户id</th>';
            }
            if($_GPC['name']==1){
                $data.='<th>姓名</th>';
            }
            if($_GPC['mobile']==1){
                $data.='<th>手机号</th>';
            }
            if($_GPC['status']==1){
                $data.='<th>状态</th>';
            }
            if($_GPC['createtime']==1){
                $data.='<th>添加时间</th>';
            }
            $data.='</tr>';
            foreach($order as $v){
                $data=$data."<tr>";
                if($_GPC['store']==1){
                    $data.='<td>'.$store_list[$v['store']]['name'].'</td>';
                }
                if($_GPC['title']==1){
                    $data.='<td>'.$v['title'].'</td>';
                }
                if($_GPC['total']==1){
                    $data.='<td>'.$v['total'].'</td>';
                }
                if($_GPC['out_trade_no']==1){
                    $data.="<td style='vnd.ms-excel.numberformat:@'>".$v['out_trade_no']."</td>";
                }
                if($_GPC['wx_out_trade_no']==1){
                    $data.="<td style='vnd.ms-excel.numberformat:@'>".$v['wx_out_trade_no']."</td>";
                }
                if($_GPC['amount']==1){
                    if(!empty($v['amount'])){
                        $data.='<td>'.$v['amount'].'</td>';
                    }else{
                        $data.='<td>免费</td>';
                    }
                }
                if($_GPC['o_amount']==1){
                    if(!empty($v['amount'])){
                        $data.='<td>'.$v['o_amount'].'</td>';
                    }else{
                        $data.='<td>免费</td>';
                    }
                }
                if($_GPC['openid']==1){
                    $data.='<td>'.$v['openid'].'</td>';
                }
                if($_GPC['name']==1){
                    $data.='<td>'.$v['name'].'</td>';
                }
                if($_GPC['mobile']==1){
                    $data.='<td>'.$v['mobile'].'</td>';
                }
                if($_GPC['status']==1){
                    if($v['use']==1){
                        $data.='<td>已使用</td>';
                    }else{
                        $data.='<td>未使用</td>';
                    }
                }
                if($_GPC['createtime']==1){
                    $data.='<td>'.$v['createtime'].'</td>';
                }
                $data=$data."</tr>";
            }
            $data="<table border='1'>".$data."</table>";
            echo $data. "\t";
            exit();
        }
    }
    public function doWebExport2(){
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $group_id=array();
        $group=pdo_getall("xc_beauty_group",array("status"=>1,'uniacid'=>$uniacid));
        if($group){
            foreach($group as $g){
                $group_id[]=$g['id'];
            }
        }
        $order=pdo_getall("xc_beauty_order",array("status"=>1,'uniacid'=>$uniacid,'order_type'=>3,'group IN'=>$group_id),array(),"","createtime DESC");
        if($order){
            $service=pdo_getall("xc_beauty_service",array("uniacid"=>$uniacid));
            $datalist=array();
            if($service){
                foreach($service as $x2){
                    $datalist[$x2['id']]=$x2;
                }
            }
            foreach($order as &$x){
                $x['service']=$datalist[$x['pid']]['name'];
                $x['userinfo']=json_decode($x['userinfo'],true);
            }
            header("Content-type: application/vnd.ms-excel; charset=utf8");
            header("Content-Disposition: attachment; filename=order.xls");
            $data='<tr><th>订单号</th><th>订单支付方式</th><th>服务项目</th><th>优惠（元）</th><th>应付款（元）</th><th>实付款（元）</th><th>总件数</th><th>姓名</th><th>手机号</th><th>地址</th><th>日期</th><th>备注</th></tr>';
            foreach($order as $v){
                if($v['pay_type']==1){
                    $v['pay_name']='余额支付';
                }else if($v['pay_type']==2){
                    $v['pay_name']='微信支付';
                }
                $data=$data."<tr>";
                $data=$data."<td style='vnd.ms-excel.numberformat:@'>".$v['out_trade_no']."</td>";
                $data=$data."<td style='vnd.ms-excel.numberformat:@'>".$v['pay_name']."</td>";
                $data=$data."<td>".$v['service']."</td>";
                $data=$data."<td>".$v['couyupon_price']."</td>";
                $data=$data."<td>".$v['amount']."</td>";
                $data=$data."<td>".$v['o_amount']."</td>";
                $data=$data."<td>".$v['total']."</td>";
                $data=$data."<td>".$v['userinfo']['name']."</td>";
                $data=$data."<td>".$v['userinfo']['mobile']."</td>";
                $data=$data."<td>".$v['userinfo']['address']."</td>";
                $data=$data."<td>".$v['plan_date']."</td>";
                $data=$data."<td>".$v['content']."</td>";
                $data=$data."</tr>";
            }
            $data="<table border='1'>".$data."</table>";
            echo $data. "\t";
        }
    }
    public function doWebPost(){
        global $_GPC, $_W;
        load()->func('communication');
        $url=$_GPC['url'];
        $sms=pdo_get("xc_beauty_config",array("xkey"=>'sms'));
        if($sms){
            $sms['content']=json_decode($sms['content'],true);
            $customize=$sms['content']['customize'];
            $post=$sms['content']['post'];
            if(is_array($post) && !empty($post)){
                $post=json_encode($post);
                if(is_array($customize)){
                    foreach($customize as $x){
                        $post=str_replace("{{".$x['attr']."}}",$x['value'],$post);
                    }
                }
                $post=str_replace("{{webnamex}}","美容",$post);
                $post=str_replace("{{trade}}","1220171127101100000017",$post);
                $post=str_replace("{{amount}}","199元",$post);
                $post=str_replace("{{namex}}","张三",$post);
                $post=str_replace("{{phonex}}","18888888888",$post);
                $post=str_replace("{{addrx}}","中国北京",$post);
                $post=str_replace("{{datex}}",date("Y-m-d H:i"),$post);
                $post=json_decode($post,true);
                $data=array();
                foreach($post as $x2){
                    $data[$x2['attr']]=$x2['value'];
                }
                $request_post=ihttp_post($url,$data);
            }
            $get=$sms['content']['get'];
            if(is_array($get) && !empty($get)){
                $get=json_encode($get);
                if(is_array($customize)){
                    foreach($customize as $x){
                        $get=str_replace("{{".$x['attr']."}}",$x['value'],$get);
                    }
                }
                $get=str_replace("{{webnamex}}","美容",$get);
                $get=str_replace("{{trade}}","1220171127101100000017",$get);
                $get=str_replace("{{amount}}","199元",$get);
                $get=str_replace("{{namex}}","张三",$get);
                $get=str_replace("{{phonex}}","18888888888",$get);
                $get=str_replace("{{addrx}}","中国北京",$get);
                $get=str_replace("{{datex}}",date("Y-m-d H:i"),$get);
                $get=json_decode($get,true);
                $url_data="";
                foreach($get as $x3){
                    if(empty($url_data)){
                        $url_data=urlencode($x3['attr'])."=".urlencode($x3['value']);
                    }else{
                        $url_data=$url_data."&".urlencode($x3['attr'])."=".urlencode($x3['value']);
                    }
                }
                if(strpos($url,'?')!==false){
                    $url=$url.$url_data;
                }else{
                    $url=$url."?".$url_data;
                }
                $request_get=ihttp_get($url);
                echo $request_get['content'];
            }
        }
    }



    public function doWebPage(){
        $page=array("status"=>'1233',);
    }

    public function doWebOrderRefund(){
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $order=pdo_get("xc_beauty_order",array("id"=>$_GPC['id'],'uniacid'=>$uniacid));
        if($order){
            $userinfo=pdo_get("xc_beauty_userinfo",array("status"=>1,'openid'=>$order['openid'],'uniacid'=>$uniacid));
            if(!empty($order['score'])){
                $score=$userinfo['score']-$order['score'];
                pdo_update("xc_beauty_userinfo",array("score"=>$score),array("status"=>1,'openid'=>$order['openid'],'uniacid'=>$uniacid));
            }
            if(floatval($order['canpay'])!=0){
                $money=round(floatval($userinfo['money'])+floatval($order['canpay']),2);
                $request=pdo_update("xc_beauty_userinfo",array("money"=>$money),array("status"=>1,'openid'=>$order['openid'],'uniacid'=>$uniacid));
            }
            if(floatval($order['wxpay'])!=0){
                $config=pdo_get("uni_settings",array("uniacid"=>$uniacid));
                $cert=pdo_get("xc_beauty_config",array("uniacid"=>$uniacid,'xkey'=>'refund'));
                if($config && $cert){
                    $cert['content']=json_decode($cert['content'],true);
                    if(!empty($cert['content']['cert']) && !empty($cert['content']['key'])){
                        $config['payment']=unserialize($config['payment']);
                        $appid=$_W['account']['key'];
                        $transaction_id =$order['wx_out_trade_no'];
                        $total_fee = floatval($order['wxpay'])*100;
                        $refund_fee = floatval($order['wxpay'])*100;
                        $url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
                        $ref= strtoupper(md5("appid=".$appid."&mch_id=".$config['payment']['wechat']['mchid']."&nonce_str=123456"
                            . "&out_refund_no=".$transaction_id."&out_trade_no=".$transaction_id."&refund_fee=".$refund_fee."&total_fee=".$total_fee
                            . "&key=".$config['payment']['wechat']['signkey']));//sign加密MD5
                        $refund=array(
                            'appid'=>$appid,//应用ID，固定
                            'mch_id'=>$config['payment']['wechat']['mchid'],//商户号，固定
                            'nonce_str'=>'123456',//随机字符串
                            'out_refund_no'=>$transaction_id,//商户内部唯一退款单号
                            'out_trade_no'=>$transaction_id,//商户订单号,pay_sn码 1.1二选一,微信生成的订单号，在支付通知中有返回
                            // 'transaction_id'=>'1',//微信订单号 1.2二选一,商户侧传给微信的订单号
                            'refund_fee'=>$refund_fee,//退款金额
                            'total_fee'=>$total_fee,//总金额
                            'sign'=>$ref//签名
                        );
                        $xml=arrayToXml($refund);
                        $ch=curl_init();
                        curl_setopt($ch,CURLOPT_URL,$url);
                        curl_setopt($ch,CURLOPT_HEADER,0);
                        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
                        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);//证书检查
                        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'pem');
                        //要创建的两个文件
                        $cert_file = '../addons/'.$_GPC['m'].'/resource/'.rand(100000,999999).'.pem';
                        if (($TxtRes = fopen($cert_file, "w+")) === FALSE) {
                            echo("创建可写文件：" . $cert_file . "失败");
                            exit();
                        }
                        $StrConents = $cert['content']['cert'];//要 写进文件的内容
                        if (!fwrite($TxtRes, $StrConents)) { //将信息写入文件
                            echo("尝试向文件" . $cert_file . "写入" . $StrConents . "失败！");
                            fclose($TxtRes);
                            exit();
                        }
                        fclose($TxtRes); //关闭指针
                        curl_setopt($ch,CURLOPT_SSLCERT,$cert_file);
                        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'pem');
                        //要创建的两个文件
                        $key_file = '../addons/'.$_GPC['m'].'/resource/'.rand(100000,999999).'.pem';
                        if (($TxtRes = fopen($key_file, "w+")) === FALSE) {
                            echo("创建可写文件：" . $key_file . "失败");
                            exit();
                        }
                        $StrConents = $cert['content']['key'];//要 写进文件的内容
                        if (!fwrite($TxtRes, $StrConents)) { //将信息写入文件
                            echo("尝试向文件" . $key_file . "写入" . $StrConents . "失败！");
                            fclose($TxtRes);
                            exit();
                        }
                        fclose($TxtRes); //关闭指针
                        curl_setopt($ch,CURLOPT_SSLKEY,$key_file);
                        curl_setopt($ch,CURLOPT_POST,1);
                        curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);

                        $data=curl_exec($ch);
                        unlink($cert_file);
                        unlink($key_file);
                        if($data){ //返回来的是xml格式需要转换成数组再提取值，用来做更新
                            curl_close($ch);
                            $data=xmlToArray($data);
                            if($data['return_code']=='SUCCESS'){
                                if($data['result_code']=='SUCCESS'){
//                                    pdo_update("xc_beauty_order",array("refund_status"=>1,'status'=>2),array("id"=>$_GPC['id'],'uniacid'=>$uniacid));
//                                    $json=array('status'=>1,'msg'=>'操作成功');
//                                    echo json_encode($json);
                                }else{
//                                    $json=array('status'=>-1,'msg'=>$data['err_code_des']);
//                                    echo json_encode($json);
                                }
                            }else{
//                                $json=array('status'=>-1,'msg'=>'操作失败');
//                                echo json_encode($json);
                            }
                        }else{
                            $error=curl_errno($ch);
                            //echo "curl出错，错误代码：$error"."<br/>";
                            //echo "<a href='http://curl.haxx.se/libcurl/c/libcurs.html'>;错误原因查询</a><br/>";
                            curl_close($ch);
//                            $json=array('status'=>-1,'msg'=>'操作失败');
//                            echo json_encode($json);
                        }
                    }else{
//                        $json=array('status'=>-1,'msg'=>'操作失败');
//                        echo json_encode($json);
                    }
                }
            }
            $share=pdo_get("xc_beauty_share",array("status"=>1,'uniacid'=>$uniacid,'openid'=>$order['openid'],'out_trade_no'=>$order['out_trade_no']));
            if($share){
                $share_o_amount=round(floatval($userinfo['share_o_amount'])-floatval($share['amount']),2);
                $share_empty=round(floatval($userinfo['share_empty'])+floatval($share['amount']));
                pdo_update("xc_beauty_userinfo",array("share_o_amount"=>$share_o_amount,'share_empty'=>$share_empty),array("status"=>1,'openid'=>$order['openid'],'uniacid'=>$uniacid));
                pdo_update("xc_beauty_share",array("status"=>2),array("status"=>1,'uniacid'=>$uniacid,'openid'=>$order['openid'],'out_trade_no'=>$order['out_trade_no']));
            }
            $request=pdo_update("xc_beauty_order",array("refund_status"=>1,'status'=>2),array("id"=>$_GPC['id'],'uniacid'=>$uniacid));
            if($request){
                $json=array('status'=>1,'msg'=>'操作成功');
                echo json_encode($json);
            }else{
                $json=array('status'=>-1,'msg'=>'操作失败');
                echo json_encode($json);
            }
        }else{
            $json=array('status'=>-1,'msg'=>'操作失败');
            echo json_encode($json);
        }
    }


    /**
     * 一键更新
     */
    public function doWebUpSql(){
        include_once '../addons/xc_train/upsql.php';
        upsql();
    }
}


function arrayToXml($arr){
    $xml = "<root>";
    foreach ($arr as $key=>$val){
        if(is_array($val)){
            $xml.="<".$key.">".arrayToXml($val)."</".$key.">";
        }else{
            $xml.="<".$key.">".$val."</".$key.">";
        }
    }
    $xml.="</root>";
    return $xml ;
}
//将XML转为array
function xmlToArray($xml)
{
    //禁止引用外部xml实体
    libxml_disable_entity_loader(true);
    $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $values;
}


/**
 * 请求接口返回内容
 * @param  string $url [请求的URL地址]
 * @param  string $params [请求的参数]
 * @param  int $ipost [是否采用POST形式]
 * @return  string
 */
function juhecurl($url,$params=false,$ispost=0){
    $httpInfo = array();
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
    curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    if( $ispost )
    {
        curl_setopt( $ch , CURLOPT_POST , true );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
        curl_setopt( $ch , CURLOPT_URL , $url );
    }
    else
    {
        if($params){
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
        }else{
            curl_setopt( $ch , CURLOPT_URL , $url);
        }
    }
    $response = curl_exec( $ch );
    if ($response === FALSE) {
        //echo "cURL Error: " . curl_error($ch);
        return false;
    }
    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
    curl_close( $ch );
    return $response;
}

function push($requestInfo,$url)
{
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检测
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Expect:'
    )); // 解决数据包大不能提交
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $requestInfo); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
        echo 'Errno' . curl_error($curl);
    }
    curl_close($curl); // 关键CURL会话
    return $tmpInfo; // 返回数据
}


/*
 *  方法1
	拼凑订单内容时可参考如下格式
	根据打印纸张的宽度，自行调整内容的格式，可参考下面的样例格式
*/
function wp_print($printer_sn,$orderInfo,$times){

    $content = array(
        'user'=>USER,
        'stime'=>STIME,
        'sig'=>SIG,
        'apiname'=>'Open_printMsg',

        'sn'=>$printer_sn,
        'content'=>$orderInfo,
        'times'=>$times//打印次数
    );

    $client = new HttpClient(IP,PORT);
    if(!$client->post(PATH,$content)){
        echo 'error';
    }
    else{
        //服务器返回的JSON字符串，建议要当做日志记录起来
        echo $client->getContent();
    }

}

/**抓取腾讯视频地址信息
 * @param  string $url 视频地址或者是vid
 * @param  int $type 1 标清; 2 高清
 * @return  string
 * */
function xc_txvideoUrl($url, $type = 1)
{
    if (strpos($url, 'http') !==false) {
        $vid = substr($url, 24, 11);
    } else {
        $vid = $url;
    }
    load()->func('communication');
    $response = ihttp_request('http://vv.video.qq.com/getinfo?vids=' . $vid . '&platform=101001&charge=0&otype=json');

    $response = strstr($response['content'], '{');
    $response = substr($response, 0, strlen($response) - 1);
    $response = json_decode($response, true);

    $url = $response['vl']['vi'][0]['ul']['ui'][0]['url'];

    if ($type == 1) {
        $fn = $response['vl']['vi'][0]['fn'];
        $vkey = $response['vl']['vi'][0]['fvkey'];
        $trueurl = $url . $fn . '?vkey=' . $vkey;
        return $trueurl;
    } else if ($type == 2) {
        $response_2 = ihttp_request("http://vv.video.qq.com/getkey?format=2&otype=json&vt=150&vid=" . $vid . "&ran=0%2E9477521511726081&charge=0&filename=" . $vid . ".mp4&platform=11");
        $response_2 = strstr($response_2['content'], '{');
        $response_2 = substr($response_2, 0, strlen($response_2) - 1);
        $response_2 = json_decode($response_2, true);
        $vkey_2 = $response_2['key'];
        $fn_2 = $response_2['filename'];
        $trueurl = $url . $fn_2 . '?vkey=' . $vkey_2;
        return $trueurl;
    } else {
        return $url;
    }
}
