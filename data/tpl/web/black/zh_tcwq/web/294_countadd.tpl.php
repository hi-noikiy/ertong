<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_tcwq/template/public/ygcss.css">
<style type="text/css">
    input[type="radio"] + label::before {
        content: "\a0"; /*不换行空格*/
        display: inline-block;
        vertical-align: middle;
        font-size: 16px;
        width: 1em;
        height: 1em;
        margin-right: .4em;
        border-radius: 50%;
        border: 2px solid #ddd;
        text-indent: .15em;
        line-height: 1; 
    }
    input[type="radio"]:checked + label::before {
        background-color: #44ABF7;
        background-clip: content-box;
        padding: .1em;
        border: 2px solid #44ABF7;
    }
    input[type="radio"] {
        position: absolute;
        clip: rect(0, 0, 0, 0);
    }
    .dis_in{
      display: inline-block;
      vertical-align: middle;
    }
</style>

<ul class="nav nav-tabs">    
    <span class="ygxian"></span>
    <div class="ygdangq">当前位置:</div>
    <li><a href="<?php  echo $this->createWebUrl('account')?>">账号管理</a></li>

    <li class="active"><a href="<?php  echo $this->createWebUrl('countadd')?>">添加/编辑账号</a></li>

</ul>

<div class="main">

    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">

        <!--<input type="hidden" name="parentid" value="<?php  echo $parent['id'];?>" />-->

        <div class="panel panel-default ygdefault">

            <div class="panel-heading wyheader">

                添加/编辑账号

            </div>

            <div class="panel-body">

                <div class="form-group">

                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">登录账号</label>

                    <div class="col-sm-10 col-lg-9">

                        <input id="" name="username" type="text" class="form-control" value="<?php  echo $users['username'];?>" />

                        <span class="help-block">*请输入用户名，用户名为 3 到 15 个字符组成，包括汉字，大小写字母（不区分大小写）</span>

                    </div>

                </div>

                <div class="form-group">

                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">登录密码</label>

                    <div class="col-sm-10 col-lg-9">

                        <input id="password" name="password" type="password" class="form-control" value="" autocomplete="off" />

                        <span class="help-block">请填写密码，最小长度为 8 个字符</span>

                    </div>

                </div>

                <div class="form-group">

                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">确认密码</label>

                    <div class="col-sm-10 col-lg-9">

                        <input id="repassword" type="password" class="form-control" value="" autocomplete="off" />

                        <span class="help-block">*重复输入密码，确认正确输入</span>

                    </div>

                </div>

                <div class="form-group">

                    <label class="col-sm-2 control-label">所属城市</label>
                    <div class="col-sm-10">
                        <select name="cityname" class="col-md-6" id="groupid">

                            <option value="0">请选择所属城市</option>

                            <?php  if(is_array($city)) { foreach($city as $row) { ?>

                            <option name="unopction" id="<?php  echo $row['id'];?>" value="<?php  echo $row['cityname'];?>" <?php  if($account['cityname']==$row['cityname']) { ?> selected<?php  } ?>><?php  echo $row['cityname'];?></option>

                            <?php  } } ?>

                        </select>

                        <!-- <span class="btn btn-sm ygbtn" data-toggle="modal" data-target="#myModal1">搜索城市</span> -->

                    </div>
                    <div class="help-block col-sm-push-2 col-sm-12">*如需添加城市，请在小程序端左上角切换城市</div>
                </div>

            
                <div class="form-group">

                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">状态</label>

                    <div class="col-sm-9">

                        <label class="radio-inline">

                            <input type="radio" id="emailwy1" name="status" value="2" <?php  if($users['status']==2 || empty($users)) { ?>checked<?php  } ?>>
                            <label for="emailwy1">启用</label>

                        </label>

                        <label class="radio-inline">

                            <input type="radio" id="emailwy2" name="status" value="1" <?php  if($users['status'] == 1) { ?>checked<?php  } ?>><label for="emailwy2">关闭</label>

                        </label>

                    </div>

                </div>

                <div class="form-group">

                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">备注</label>

                    <div class="col-sm-10 col-lg-9">

                        <textarea name="remark" style="height:80px;" class="form-control"><?php  echo $users['remark'];?></textarea>

                      
                    </div>

                </div>
                 <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">权限管理</label>
                    <div class="add_html dis_in" style="width:75%;" value="<?php  echo $account['city_qx'];?>">
            
                    </div>
                    <input style="opacity: 0" type="text" value="" name="form_array">
                </div>

            </div>

        </div>

        <div class="form-group">

            <input type="submit" name="submit" value="保存设置" class="btn yg5_btn col-lg-3" style="color: white;background-color: #44ABF7;" />

            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />

        </div>

  
        <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel" style="font-size: 20px;">提示</h4>
              </div>
              <div class="modal-body ygsearch" style="font-size: 20px">
                  <input type="text" id="ygsinput" placeholder="请输入城市">
                  <span class="btn btn-sm ygbtn">搜索</span>
                  <div class="searchname">
                    
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
              </div>
            </div>
        </div>
    </form>



</div>

<script type="text/javascript">
    $(function(){
        $("#frame-15").show();
        $("#yframe-15").addClass("wyactive");
        $(".searchname").hide();


        var authority = "<?php  echo $account['city_qx'];?>".split(',');
        console.log(authority)
        
        var mock = [
          {
            name:'tzdel',
            val:'帖子删除',
            y:'checked'
          },
          {
            name:'pcdel',
            val:'拼车删除',
            y:'checked'
          },
          {
            name:'hydel',
            val:'黄页删除',
            y:'checked'
          },
          {
            name:'zxdel',
            val:'资讯删除',
            y:'checked'
          }
        ]
        for(var i = 0;i< authority.length;i++){
          for(var j = 0;j< mock.length;j++){
            if(mock[j].name==authority[i]){
              console.log('相等')
              mock[j].y=true
            }
          }
        }
        console.log(authority)
        console.log(mock)
        for(let i in mock){
          if(mock[i].y==true){
          mock[i].info = ''
           mock[i].info += '<div class="col-sm-9 dis_in" style="width:20%;margin-bottom:10px;">'+'<label class="radio-inline">'+'<input type="checkbox" class="dis_in"'+'name='+'"'+mock[i].name+'"'+'value='+mock[i].name+' '+'checked='+'"'+mock[i].y+'"'+'>'+'<text style="margin-left:10px;" class="dis_in">'+mock[i].val+'</text>'+'</label>'+'</div>'
           $(".add_html").append(mock[i].info)
       }else{
        mock[i].info = ''
         mock[i].info += '<div class="col-sm-9 dis_in" style="width:20%;margin-bottom:10px;">'+'<label class="radio-inline">'+'<input type="checkbox" class="dis_in"'+'name='+'"'+mock[i].name+'"'+'value='+mock[i].name+'>'+'<text style="margin-left:10px;" class="dis_in">'+mock[i].val+'</text>'+'</label>'+'</div>'
         $(".add_html").append(mock[i].info)
       }
           
        }
        check()
    $('input[type=checkbox]').click(function(){
    check()
    })
    function check(){
        var checkInput = $('input[type=checkbox]:checked')
          var check = []
          for(let i in checkInput){
              if(checkInput[i].defaultValue!=null){
                  check.push(checkInput[i].defaultValue)
              }
          }   
          check = check.join(',')
          console.log(check)
          $("input[name=form_array]").val(check)
    }
       
    })
</script>