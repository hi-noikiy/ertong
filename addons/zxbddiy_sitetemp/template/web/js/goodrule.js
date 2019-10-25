
	var app = angular.module('myyapp',[]);
	app.controller('goodrule',['$scope',function($scope){
		$scope.rule = rule;
		if(rule.length == 0) $scope.rule = [{pro:{id:1,title:'请编辑名称'},data:[]}];
		$scope.map = map;
		$scope.table = [];
		
		//规格组合数组
		$scope.getRuleData = function(){
			$scope.ruledata = [];
			for(var i = 0 ; i < $scope.rule.length ; i++){
				if($scope.rule[i].data.length > 0) $scope.ruledata.push($scope.rule[i].data);
			}
		};
		
		$scope.doExchange = function(doubleArrays) {
			var len = doubleArrays.length; //计算长度
			if (len >= 2) { //到维数组计算组合
				var len1 = doubleArrays[0].length;
				var len2 = doubleArrays[1].length;
			
				var newlen = len1 * len2;
				var temp = [];
				var index = 0;
				for (var i = 0; i < len1; i++) {
					for (var j = 0; j < len2; j++) {
						var id = doubleArrays[0][i].id + ":" + doubleArrays[1][j].id;
						var name = doubleArrays[0][i].name + ":" + doubleArrays[1][j].name;
						temp.push({id:id,name:name});
						index++;
					}
				}
				//组合已组合和剩余的数组
				var newArray = new Array(len- 1);
				newArray[0] = temp;
				if (len > 2) {
				   var _count = 1;
				   for(var i=2;i<len;i++){
					   newArray[_count] = doubleArrays[i];
					   _count ++;
				   }
				}
				return $scope.doExchange(newArray);
				
			}else if(len == 1){ //一维数组直接返回结果
				if(typeof $scope.map != 'undefined'){
					for(var i = 0 ; i < doubleArrays[0].length ; i ++){
						for(var j = 0 ; j < $scope.map.length ; j ++){
							if(doubleArrays[0][i].id == $scope.map[j].id){
								doubleArrays[0][i].nowprice = $scope.map[j].nowprice;
								doubleArrays[0][i].groupprice = $scope.map[j].groupprice;
								doubleArrays[0][i].stock = $scope.map[j].stock;
							}
						}
					}
				}
				return doubleArrays[0];
			}
		};
		
		//初始和重置参数
		$scope.init = function(){
			$scope.getRuleData();
			$scope.table  = $scope.doExchange($scope.ruledata);
			$scope.map = $scope.table;
			$('input[name=rule]').val(angular.toJson($scope.rule));
			$('input[name=rulemap]').val(angular.toJson($scope.table));
		}
		
		//删除规格
		$scope.deleteRule = function(id){
			if(confirm('确定要删除此规格吗？')){
				angular.forEach($scope.rule, function(a,index) {
					if(a.pro.id == id){
						$scope.rule.splice(index,1);
						$scope.init();
					}					
				});				
			}
		};
		
		//获取唯一id type 传pro 或者 对应的规格id
		$scope.getOnlyId = function(type){
			var idarr = [0];
			if(type == 'pro'){
				for(var i = 0 ; i < $scope.rule.length ; i++){
					idarr.push($scope.rule[i].pro.id*1);
				}
				return Math.max.apply(0,idarr) + 1;
			}else{
				for(var i = 0 ; i < $scope.rule.length ; i++){
					if($scope.rule[i].pro.id == type){
						for(var j = 0 ; j < $scope.rule[i].data.length;j++){
							idarr.push($scope.rule[i].data[j].id*1)
						}
					}
				}
				if(Math.max.apply(0,idarr) <= 0){
					var newid = type*1000;
				}else{
					var newid = Math.max.apply(0,idarr) + 1;
				}
				return newid;
			}
		}
		
		//添加规格
		$scope.addRule = function(){
			var newid = $scope.getOnlyId('pro');
			var newpro = {pro:{id:newid,title:'请编辑名称'},data:[]};
			$scope.rule.push(newpro);
			$scope.init();
		}
		
		//编辑规格名称
		$scope.editRuleTitle = function(id,el){
			var thisclass = $('.editvalue_'+id);
			var value = thisclass.val();
			if(value == ''){
				alert('请先在左边编辑框里输入名称');return false;
			} 
			angular.forEach($scope.rule, function(a,index) {
				if(a.pro.id == id){
					a.pro.title = value;
					$scope.init();
					thisclass.val('').focus();
				}					
			});
		}
		
		//添加规格标题
		$scope.editRuleTitle = function(id,el){
			var thisclass = $('.editvalue_'+id);
			var value = thisclass.val();
			if(value == ''){
				alert('请先在左边编辑框里输入名称');return false;
			} 
			angular.forEach($scope.rule, function(a,index) {
				if(a.pro.id == id){
					a.pro.title = value;
					$scope.init();
					thisclass.val('').focus();
				}					
			});
		}		
		
		//添加属性
		$scope.addRuleData = function(id){
			var thisclass = $('.editvalue_'+id);
			var value = thisclass.val();
			if(value == ''){
				alert('请先在左边编辑框里输入名称');return false;
			}
			if(value.indexOf(':') >= 0 || value.indexOf('：') >= 0){
				alert('属性中不能出现:字符');return false;
			}
			var newid = $scope.getOnlyId(id);

			angular.forEach($scope.rule, function(a,index) {
				if(a.pro.id == id){
					var newarr = {id:newid,name:value};
					a.data.push(newarr);
					$scope.init();
					thisclass.val('').focus();
				}					
			});

		};
		
		//删除属性
		$scope.deleteData = function(proid,dataid){
			if(confirm('确定要删除此属性吗？')){
				angular.forEach($scope.rule, function(a,index1) {
					if(a.pro.id == proid){
						angular.forEach(a.data, function(b,index2) {
							if(b.id == dataid){
								$scope.rule[index1].data.splice(index2,1);
								$scope.init();
							}
						})
					}					
				});
			}
			return false;
		};
		
		//批量设置参数
		$scope.setAllParams = function(){
			var nowprice = $('input[name=setallnowprice]').val(),
				groupprice = $('input[name=setallgroupprice]').val(),
				stock = $('input[name=setallstock]').val();
			if(nowprice == '' && groupprice == '' && stock == ''){
				alert('请先在左边输入批量设置的值，只能输入数字');return false;
			}
			for(var j = 0 ; j < $scope.map.length ; j ++){
				if(nowprice != '') $scope.map[j].nowprice = nowprice;
				if(groupprice != '') $scope.map[j].groupprice = groupprice;
				if(stock != '') $scope.map[j].stock = stock;
			}
			$scope.table = $scope.map;
			$scope.init();
		};
		
		
		$scope.changePrice = function(){
			$scope.map = $scope.table;
			$('input[name=rulemap]').val(angular.toJson($scope.table));
		}
		
		
		$scope.init();
	}]);