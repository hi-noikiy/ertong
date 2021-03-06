<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_jdgjb/template/public/ygcss.css">
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
    <li><a href="<?php  echo $this->createWebUrl('zhanghao')?>">账号管理</a></li>
    <li class="active"><a href="<?php  echo $this->createWebUrl('addzhanghao')?>">添加/编辑账号</a></li>
</ul>
<div class="main">
    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
        <div class="panel panel-default ygdefault">
            <div class="panel-heading wyheader"> 添加/编辑账号</div>
            <div class="panel-body panel">
            
                   <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">登录账号</label>
                    <div class="col-sm-10 col-lg-9">
                        <input id="" name="username" type="text" class="form-control" value="<?php  echo $users['username'];?>"/>
                        <span class="help-block">*请输入用户名，用户名为 3 到 15 个字符组成，包括汉字，大小写字母（不区分大小写）</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">登录密码</label>
                    <div class="col-sm-10 col-lg-9">
                        <input id="password" name="password" type="password" class="form-control" value="" autocomplete="off"/>
                        <span class="help-block">*请填写密码，最小长度为 8 个字符</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">确认密码</label>
                    <div class="col-sm-10 col-lg-9">
                        <input id="repassword" type="password" class="form-control" value="" autocomplete="off"/>
                        <span class="help-block">*重复输入密码，确认正确输入</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">状态</label>
                    <div class="col-sm-9">
                        <label class="radio-inline">
                            <input type="radio" id="qiyongc" name="status" value="2" <?php  if($users['status']==2 || empty($users)) { ?>checked<?php  } ?>>
                            <label for="qiyongc">启用</label> 
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="qiyongc2" name="status" value="1" <?php  if($users['status']== 1) { ?>checked<?php  } ?>>
                            <label for="qiyongc2">关闭</label>
                        </label>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">权限管理</label>
                    <div class="add_html dis_in" style="width:75%;" value="<?php  echo $account['authority'];?>">
						
                    </div>
                    <input style="opacity: 0" type="text" value="" name="form_array">
                </div>
               
            </div>
        </div>
        <div class="form-group">
            <input type="submit" name="submit" value="保存设置" class="btn col-lg-3" style="color: white;background-color: #44ABF7;"/>
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
        </div>
   
    </form>
</div>
<!-- <script type="text/javascript">
    $(function(){
        // $("#frame-0").addClass("in");
        $("#frame-8").show();
        $("#yframe-8").addClass("wyactive");
    })
</script> -->
<script type="text/javascript">
    $(function(){
        $("#frame-12").show();
        $("#yframe-12").addClass("wyactive");
        $(".searchname").hide();
        // console.log('')
        var authority = "<?php  echo $account['authority'];?>".split(',');
        console.log(authority)
        
        var mock = [
	        {
	        	name:'store',
	        	val:'商家管理',
	        	y:'checked'
	        },
	        {
	        	name:'information',
	        	val:'帖子管理',
	        	y:'checked'
	        },
	        {
	        	name:'carinfo',
	        	val:'拼车管理',
	        	y:'checked'
	        },
	        {
	        	name:'zx',
	        	val:'资讯管理',
	        	y:'checked'
	        },
	        {
	        	name:'video',
	        	val:'视频管理',
	        	y:'checked'
	        },
	        {
	        	name:'yellowstore',
	        	val:'黄页114',
	        	y:'checked'
	        },
	        {
	        	name:'activity',
	        	val:'活动管理',
	        	y:'checked'
	        },
	        {
	        	name:'ad',
	        	val:'广告管理',
	        	y:'checked'
	        },
	        {
	        	name:'goods',
	        	val:'商品管理',
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
        $(".ygbtn").on("click",function(){
            var ygsinput = $("#ygsinput").val();
            console.log(ygsinput)
            if(ygsinput.length==''){
              $(".searchname").html('');
            }else{
              $(".searchname").html('')  
              var keywords = $("#ygsinput").val()
              $.ajax({
                  type:"post",
                  url:"<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=SelectJd&m=zh_jdgjb",
                  dataType:"text",
                  data:{keywords:keywords},
                  success:function(data){                   
                      var data = eval('(' + data + ')')
                      console.log(data);
                      $(".searchname").show();
                      for(var i=0;i<data.length;i++){
                        $(".searchname").append('<div class="shnbox" data-dismiss="modal" id="'+data[i].id+'"><a href="javascript:void(0);"><p>'+data[i].name+'</p></a></div>')
                      }
                      $(".shnbox").each(function(){
                        $(this).click(function(){
                            // 获取选中的用户id
                            var thid = $(this).text()
                            // 获取选中的用户name
                            var user_id = $(this).attr("id")
                            // 根据选中的用户新增一个option
                            $("#username").append("<option value='"+user_id+"'>"+thid+"</option>").attr("selected", true);
                            // 点击之后让value等于user_id的options显示
                            $("#username").val(user_id);
                        })
                        
                      })
                      
                  }
              }) 
            }
            
        })

     
        
        
    })
</script>