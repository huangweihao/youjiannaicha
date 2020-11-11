var TB_Pop = {
    _init:function(from,type,data,callback,callback2){
        var layInstance = '';
        switch (from) {
            case 'parent':
                layInstance = top.layer;
                break;
            default:
                layInstance = layer;
                break;
        }
        switch(type){
            case 'open':
                if(!data.color)data.color = '#000';
                if(!data.shift)data.shift = 1;
                if(!data.shade)data.shade = '0.4';
                if(!data.closeBtn)data.closeBtn = data.closeBtn == 0 ? 0 : 1;
                var index = layInstance.open({
                    type: data.type, //page层
                    area: [data.width, data.height],
                    title: data.title,
                    scrollbar: data.scroll,
                    closeBtn: data.closeBtn,
                    shade: [data.shade, data.color], //遮罩透明度
                    moveType: 1, //拖拽风格，0是默认，1是传统拖动
                    shift: data.shift, //0-6的动画形式，-1不开启
                    content: data.content
                });
                return index;
                break;
            case 'msg':
                if(data.icon){
                    layInstance.msg(data.content, {icon: data.icon});
                }else{
                    layInstance.msg(data.content);
                }
                break;
            case 'alert':
                if(data.icon){
                    layInstance.alert(data.content,{icon: data.icon,title:data.title,closeBtn:data.closeBtn},callback);
                }else{
                    layInstance.alert(data.content,callback);
                }
                break;
            case 'confirm':
                layInstance.confirm(data.content,{btn:data.btn,title:data.title},callback, callback2);
                break;
            case 'load':
                layInstance.load(data.type, {time: data.time*1000,shade: data.shade});
                break;
            case 'close':
                layInstance.closeAll();
                break;
        }
    }
};
var TB_Port = {
    _saveAjaxRequest:[],
    _ajax:function(url,postOrGet,sync,data,start,end,success,fail){
        var postData = data;
        if(postOrGet == 'get'){
            postData = postData ? postData+'&nocached='+new Date().getTime() : 'nocached='+new Date().getTime();
        }
        var ajaxInstance = $.ajax({
            timeout:30000,
            async:sync,
            type:postOrGet,
            url:url,
            data:postData,
            beforeSend:function(xhr){
                xhr.setRequestHeader("X-CSRF-TOKEN",$('meta[name="csrf-token"]').attr('content'));
                start && start();
            },
            complete:function(){},
            error:function(XMLHttpRequest, textStatus, errorThrown){
                end && end();
                try{
                    if(XMLHttpRequest['responseJSON'] && XMLHttpRequest['responseJSON']['msg']){
                        fail && fail('',XMLHttpRequest['responseJSON']['msg']);
                    }else{
                        fail && fail('','加载失败');
                    }
                }catch(e){
                    console.log(e);
                    fail && fail('','加载失败，请刷新重试');
                }
            },
            success:function(back){
                end && end();
                try{
                    switch(back['code']+''){
                        case '200':
                        case '201':
                            if (typeof back['result'] == 'string'){
                                if (back['result'] != ''){
                                    back['result'] = $.parseJSON(back.data);
                                }else
                                    back['result'] = {};
                            }
                            success && success(back['result'],back['msg']) ;
                            break;
                        case '401':
                            TB_Pop._init('parent','alert',{'content':'登录状态消失，请重新登录'},function () {
                                window.location = '/admin/login';
                            });
                            break;
                        default:
                            fail && fail(back['result'],back['msg'],back['code']);
                            break;
                    }
                }catch(e){
                    console.log(e);
                    fail && fail('','加载失败，请联系管理员');
                }
            }
        });
    }
};
var TB_Choose = {
    _init:function(inputDom){
        var val = top.$('#iframe').contents().find('#'+inputDom).val();
        var str = '';
        if(val){
            TB_Choose._setPool(val);
            val = $.parseJSON(val);
            $.each(val,function (key,value) {
                str = '<li class="choosed-'+key+'"><span>'+value+'</span> <i class="am-icon-minus" onclick="TB_Choose._chooseDel(this,\''+key+'\')"></i></li>';
                $('#choosedZone').append(str);
            });
        }
    },
    _search:function(url){
        var keyword = $.trim($('input[name="keyword"]').val());
        var postData = {'keyword':keyword};
        var zoneDom = $('#chooseZone');
        var btnDom = $('#btn-submit');
        TB_Port._ajax(url,'post',true,postData,function () {
                btnDom.attr('disabled',true);
                zoneDom.html('查询中，请稍等...');
            },function () {
                btnDom.attr('disabled',false);
                zoneDom.html('');
            },function (data,msg) {
                if(data && data['data']){
                    zoneDom.html(data['data']);
                }else{
                    zoneDom.html('未找到相关数据');
                }
            },function (data,msg,code) {
                TB_Pop._init('parent','msg',{'icon':5,'content':msg});
            }
        );
    },
    _confirm:function(type,showDom,inputDom,search){
        var str = '';
        var shop_name = '';
        var data = TB_Choose._getPool();
        switch (type) {
            case 'shop':
            case 'shopLucky':
            case 'shopPool':
            case 'user':
                if(data){
                    $.each(data,function (key,value) {
                        str += '<div style="margin-left: 10px;" class="am-btn am-btn-success">'+value+'</div>';
                        shop_name = value;
                    });
                }
                break;
        }
        top.$('#iframe').contents().find('#'+showDom).html(str);
        //top.$('#iframe').contents().find('#'+search).val(shop_name);
        top.$('#iframe').contents().find('#'+inputDom).val(JSON.stringify(data));

        TB_Pop._init('parent','close');
    },
    _getPool:function(){
        var val = $.trim($('#choosedPool').val());
        if(val != ''){
            val = $.parseJSON(val);
        }else{
            val = {};
        }
        return val;
    },
    _setPool:function(val){
        $('#choosedPool').val(val);
    },
    _getLimit:function(){
        return $('#chooseBtn').attr('data-l');
    },
    _chooseAdd:function(id,name){
        var val = TB_Choose._getPool();
        var curLength = 0;
        var limit = TB_Choose._getLimit();
        for(var i in val){
            curLength++;
        }
        if(curLength >= limit){
            alert('最多选择'+limit+'个');
        }else{
            if(!val[id]){
                val[id] = name;
                var str = '<li class="choosed-'+id+'"><span>'+name+'</span> <i class="am-icon-minus" onclick="TB_Choose._chooseDel(this,\''+id+'\')"></i></li>';
                $('#choosedZone').append(str);
            }
            val = JSON.stringify(val);
            TB_Choose._setPool(val);
        }
    },
    _chooseDel:function(dom,id){
        var val = TB_Choose._getPool();
        if(val[id]){
            delete val[id];
            val = JSON.stringify(val);
            TB_Choose._setPool(val);
        }
        $(dom).parent().remove();
    }
};
var TB_Common = {
    _setNewToken: function() {
        TB_Port._ajax("/refreshToken",'get',true,null,function () {},function () {},function (data,msg) {
                $('meta[name="csrf-token"]').attr('content',data['token']);
            },function (data,msg,code) {
                TB_Pop._init('parent','msg',{'icon':5,'content':msg});
            }
        )
    },
    _rank: function(type,sort){
        var rank = '';
        switch (sort){
            case 'desc':
                rank = type+'_desc';
                break;
            case 'asc':
                rank = type+'_asc';
                break;
            default:
                rank = type+'_desc';
                break;
        }
        $('input[name=srank]').val(rank);
        $('#sform').submit();
    },
    _OpenBig:function(dom,url,param){
        if(param)url=url+'?shop='+param;
        var addition =$(dom).attr('data-i');
        if(addition)url += '&key='+addition;
        TB_Common._openFrame('big',$(dom).attr('data-n'),url);
    },
    _openFrame:function(type,title,url){
        switch (type) {
            case 'big':
                TB_Pop._init('parent','open',{'type':2,'width':'800px','height':'600px','title':title,'scroll':false,'content':url});
                break;
        }
    },
    _urlGo:function (url) {
        window.location.href = url;
    },
    _setChkAll:function(checkbox){
        $("input[type='checkbox'][name='"+checkbox+"']").each(function(){
            $(this).prop("checked",true);
        });
    },
    _setChkOut:function(checkbox){
        $("input[type='checkbox'][name='"+checkbox+"']").each(function(){
            if($(this).prop("checked")==true){
                $(this).prop("checked",false);
            }else{
                $(this).prop("checked",true);
            }
        });
    },
    _getRadioVal:function(dom){
        var val = '';
        $("input:radio[name='"+dom+"']").each(function(){
            if($(this).prop("checked")==true){
                val = $(this).val();
            }
        });
        return val;
    },
    _getInputArrayVal:function(dom){
        var val = [];
        $("input:hidden[name='"+dom+"']").each(function(){
            val = $(this).val();
        });
        return val;
    },
    _getCheckboxVal:function(dom){
        var val = [];
        $("input:checkbox[name='"+dom+"']").each(function(){
            if($(this).prop("checked")==true){
                val.push($(this).val());
            }
        });
        return val.length>0 ? val.join(','):'';
    },
    _radioChoose:function(showDom,showOrHide){
        switch (showOrHide) {
            case 'show':
                $('#'+showDom).show();
                break;
            case 'hide':
                $('#'+showDom).hide();
                break;
        }
    }
};
var TF_List = {
    _postData:{'url':'','data':{}},
    _getListCheckbox:function(){
        var data = TB_Common._getCheckboxVal('chk');
        if(data){
            return data;
        }else{
            TB_Pop._init('parent','msg',{'icon':2,'content':'请先选择数据'});
        }
    },
    _listUpdate:function(work,url){
        var selected = TF_List._getListCheckbox();
        if(selected){
            TF_Ajax._ajaxList(url,{'work':work,'keys':selected});
        }
    },
    _listDel:function(url){
        var selected = TF_List._getListCheckbox();
        TF_List._postData['data'] = {'keys':selected};
        TF_List._postData['url'] = url;
        if(selected){
            TB_Pop._init('parent','confirm',{'content':'确认删除？','title':'操作提示','btn':['确定','取消']},function () {
                TF_Ajax._ajaxList(TF_List._postData['url'],TF_List._postData['data']);
            },function () {
                TB_Pop._init('parent','close');
            });
        }
    },
    _setPagesize:function (url) {
        var postData = {'size':$('#selectPageSize').val()};
        TB_Port._ajax(url,'post',true,postData,function () {},function () {},function (data,msg) {
                window.location.reload();
            },function (data,msg,code) {
                TB_Pop._init('parent','msg',{'icon':5,'content':msg});
            }
        )
    }
};
var TF_Ajax = {
    _ajax:function (url,postData,isSingle) {
        TB_Port._ajax(url,'post',true,postData,function () {
                $('#btn-submit').html('提交中,请稍等...').attr('disabled',true);
            },function () {
                $('#btn-submit').html('确认提交').attr('disabled',false);
            },function (data,msg) {
                if(isSingle){
                    TB_Pop._init('parent','confirm',{'content':msg,'title':'操作提示','btn':['确认']},function () {
                        TB_Pop._init('parent','close');
                    });
                }else{
                    var btnName = '重新编辑';
                    if(postData['key'] == 0){
                        btnName = '继续添加';
                    }
                    TB_Pop._init('parent','confirm',{'content':msg,'title':'操作提示','btn':[btnName,'返回列表']},function () {
                        TB_Pop._init('parent','close');
                        TB_Common._urlGo(data['workUrl']);
                    },function () {
                        TB_Pop._init('parent','close');
                        TB_Common._urlGo(data['backUrl']);
                    });
                }
            },function (data,msg,code) {
                TB_Common._setNewToken();
                TB_Pop._init('parent','msg',{'icon':5,'content':msg});
            }
        )
    },
    _ajaxList:function (url,postData) {
        TB_Port._ajax(url,'post',true,postData,function () {
                TB_Pop._init('parent','load',{'type':1,'time':3,'shade':0.6});
            },function () {
            },function (data,msg) {
                TB_Pop._init('parent','alert',{'content':msg},function () {
                    TB_Pop._init('parent','close');
                    var url = top.$('#iframe').contents().get(0).location.href;
                    top.$('#iframe').attr('src',url);
                });
            },function (data,msg,code) {
                TB_Pop._init('parent','msg',{'icon':5,'content':msg});
            }
        )
    }
};
var TF_Login = {
    _checkForm:function(){
        var username = $.trim($('input[name="username"]').val());
        var password = $.trim($('input[name="password"]').val());
        if(username == ''){
            TB_Pop._init('default','msg',{'icon':2,'content':'用户名不能为空'});
            return false;
        }
        if(username.length < 5 || username.length > 30){
            TB_Pop._init('default','msg',{'icon':2,'content':'用户名错误'});
            return false;
        }
        if(password == ''){
            TB_Pop._init('default','msg',{'icon':2,'content':'密码不能为空'});
            return false;
        }
        if(password.length < 6 || password.length > 30){
            TB_Pop._init('default','msg',{'icon':2,'content':'密码错误'});
            return false;
        }
        var postData = {};
        postData['username'] = username;
        postData['password'] = password;
        return postData;
    },
    _submit:function (url) {
        var postData = TF_Login._checkForm();
        if(!postData)return false;
        TB_Port._ajax(url,'post',true,postData,function () {
                $('#btn-submit').html('登录中,请稍等...').attr('disabled',true);
            },function () {
                $('#btn-submit').html('登录').attr('disabled',false);
            },function (data,msg) {
                window.location = data['url'];
            },function (data,msg,code) {
                TB_Common._setNewToken();
                TB_Pop._init('parent','msg',{'icon':5,'content':msg});
            }
        );
    }
};
var TF_Pwd = {
    _checkForm:function(){
        var oldPwd = $.trim($('input[name="old_pwd"]').val());
        var newPwd = $.trim($('input[name="new_pwd"]').val());
        var newPwdConfirm = $.trim($('input[name="new_pwd_confirm"]').val());
        if(oldPwd == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'原登录密码不能为空'});
            return false;
        }
        if(oldPwd.length < 6 || oldPwd.length > 30){
            TB_Pop._init('parent','msg',{'icon':2,'content':'原登录密码错误'});
            return false;
        }
        if(newPwd == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'新登录密码不能为空'});
            return false;
        }
        if(newPwd.length < 6 || newPwd.length > 30){
            TB_Pop._init('parent','msg',{'icon':2,'content':'新登录密码错误'});
            return false;
        }
        if(newPwd != newPwdConfirm){
            TB_Pop._init('parent','msg',{'icon':2,'content':'两次输入的密码不一致'});
            return false;
        }
        if(oldPwd == newPwd){
            TB_Pop._init('parent','msg',{'icon':2,'content':'新密码不能和老密码一样'});
            return false;
        }
        var postData = {};
        postData['old'] = oldPwd;
        postData['new'] = newPwd;
        return postData;
    },
    _clearForm:function(){
        $('input[name="old_pwd"]').val('');
        $('input[name="new_pwd"]').val('');
        $('input[name="new_pwd_confirm"]').val('');
    },
    _submit:function (url) {
        var postData = TF_Pwd._checkForm();
        if(!postData)return false;
        TB_Port._ajax(url,'post',true,postData,function () {
                $('#btn-submit').html('修改中,请稍等...').attr('disabled',true);
            },function () {
                $('#btn-submit').html('确认修改').attr('disabled',false);
            },function (data,msg) {
                TF_Pwd._clearForm();
                TB_Pop._init('parent','msg',{'icon':6,'content':msg});
            },function (data,msg,code) {
                TB_Pop._init('parent','msg',{'icon':5,'content':msg});
            }
        );
    }
};
var TF_Role = {
    _checkForm:function(){
        var name = $.trim($('input[name="name"]').val());
        var key = $.trim($('input[name="key"]').val());
        if(name == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'权限名称不能为空'});
            return false;
        }
        if(key+'' != '0'){
            if(isNaN(key)){
                TB_Pop._init('parent','msg',{'icon':2,'content':'当前数据不存在'});
                return false;
            }
        }
        var power = TB_Common._getCheckboxVal('power');
        var status = TB_Common._getRadioVal('status');
        var postData = {};
        postData['key'] = key;
        postData['name'] = name;
        postData['power'] = power;
        postData['status'] = status;
        return postData;
    },
    _submit:function (url) {
        var postData = TF_Role._checkForm();
        if(!postData)return false;
        TF_Ajax._ajax(url,postData);
    }
};
var TF_Manager = {
    _checkForm:function(){
        var username = $.trim($('input[name="username"]').val());
        var pwd = $.trim($('input[name="pwd"]').val());
        var role = $.trim($('select[name="role"]').val());
        var key = $.trim($('input[name="key"]').val());
        var status = TB_Common._getRadioVal('status');
        if(key+'' != '0'){
            if(isNaN(key)){
                TB_Pop._init('parent','msg',{'icon':2,'content':'当前数据不存在'});
                return false;
            }
        }
        if(!(role != '' && role+'' != '0' && !isNaN(role))){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请选择所属权限组'});
            return false;
        }
        if(username == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'管理员名称不能为空'});
            return false;
        }
        if(key==''){
            if(pwd.length < 6 || pwd.length > 30){
                TB_Pop._init('parent','msg',{'icon':2,'content':'登录密码错误'});
                return false;
            }
        }
        var postData = {};
        postData['username'] = username;
        postData['status'] = status;
        postData['pwd'] = pwd;
        postData['role'] = role;
        postData['key'] = key;
        return postData;
    },
    _submit:function (url) {
        var postData = TF_Manager._checkForm();
        if(!postData)return false;
        TF_Ajax._ajax(url,postData);
    }
};
var TF_ArticleType = {
    _checkForm:function(){
        var title = $.trim($('input[name="title"]').val());
        var status = $.trim($('input[name="status"]:checked').val());
        var key = $.trim($('input[name="key"]').val());
        if(title == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写类型名称'});
            return false;
        }
        if(status == '' || isNaN(status)){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请选择状态'});
            return false;
        }
        var postData = {};
        postData['title'] = title;
        postData['status'] = status;
        postData['key'] = key;
        return postData;
    },
    _submit:function (url) {
        var postData = TF_ArticleType._checkForm();
        if(!postData)return false;
        TF_Ajax._ajax(url,postData);
    }
};
var TF_User = {
    _checkForm:function(){
        var status = $.trim($('input[name="status"]:checked').val());
        var is_inner = $.trim($('input[name="is_inner"]:checked').val());
        var name_remark = $.trim($('input[name="name_remark"]').val());
        var key = $.trim($('input[name="key"]').val());
        if(status == '' || isNaN(status)){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请选择状态'});
            return false;
        }
        if(is_inner == '' || isNaN(is_inner)){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请选择内部状态'});
            return false;
        }
        if(key == '' || isNaN(key)){
            TB_Pop._init('parent','msg',{'icon':2,'content':'数据错误，请刷新重试'});
            return false;
        }
        var postData = {};
        postData['status'] = status;
        postData['is_inner'] = is_inner;
        postData['key'] = key;
        postData['name_remark'] = name_remark;
        return postData;
    },
    _submit:function (url) {
        var postData = TF_User._checkForm();
        if(!postData)return false;
        TF_Ajax._ajax(url,postData);
    }
};
var TF_Volunteer = {
    _checkForm:function(){
        var age = $.trim($('input[name="age"]').val());
        var username = $.trim($('input[name="username"]').val());
        var status = $.trim($('input[name="status"]:checked').val());
        var health = $.trim($('input[name="health"]:checked').val());
        var key = $.trim($('input[name="key"]').val());
        var level = $.trim($('select[name="level"]').val());
        var reward_id = $.trim($('select[name="reward_id"]').val());
        var postData = {};
        postData['age'] = age;
        postData['username'] = username;
        postData['level'] = level;
        postData['health'] = health;
        postData['reward_id'] = reward_id;
        postData['key'] = key;
        postData['status'] = status;
        return postData;
    },
    _submit:function (url) {
        var postData = TF_Volunteer._checkForm();
        if(!postData)return false;
        TF_Ajax._ajax(url,postData);
    }
};
var TF_VolunteerActivity = {
    _checkForm:function(){
        var title = $.trim($('input[name="title"]').val());
        var address = $.trim($('input[name="address"]').val());
        var number = $.trim($('input[name="number"]').val());
        var begin_time = $.trim($('input[name="begin_time"]').val());
        var end_time = $.trim($('input[name="end_time"]').val());
        var join_begin = $.trim($('input[name="join_begin"]').val());
        var join_end = $.trim($('input[name="join_end"]').val());
        var content = $.trim($('textarea[name="content"]').val());
        var status = TB_Common._getRadioVal('status');
        var cover = $.trim($('input[name="cover"]').val());
        var key = $.trim($('input[name="key"]').val());
        if(title == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'活动标题不能为空'});
            return false;
        }
        if(address == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'举办地址不能为空'});
            return false;
        }
        if(number == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写招募人数'});
            return false;
        }
        if(begin_time == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'开始时间不能为空'});
            return false;
        }
        if(end_time == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'结束时间不能为空'});
            return false;
        }
        if(join_begin == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'开始报名时间不能为空'});
            return false;
        }
        if(join_end == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'报名结束时间不能为空'});
            return false;
        }
        if(content == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'活动内容不能为空'});
            return false;
        }
        if(isNaN(key)){
            TB_Pop._init('parent','msg',{'icon':2,'content':'当前数据不存在'});
            return false;
        }
        var postData = {};
        postData['key'] = key;
        postData['title'] = title;
        postData['address'] = address;
        postData['begin_time'] = begin_time;
        postData['end_time'] = end_time;
        postData['join_begin'] = join_begin;
        postData['join_end'] = join_end;
        postData['content'] = content;
        postData['status'] = status;
        postData['cover'] = cover;
        postData['number'] = number;
        return postData;
    },
    _submit:function (url) {
        var postData = TF_VolunteerActivity._checkForm();
        if(!postData)return false;
        TF_Ajax._ajax(url,postData);
    }
};
var TF_Schedule = {
    _checkForm:function(){
        var title = $.trim($('input[name="schedule_title"]').val());
        var schedule_begin = $.trim($('input[name="schedule_begin"]').val());
        var schedule_end = $.trim($('input[name="schedule_end"]').val());
        var user_id = $.trim($('input[name="user_id"]').val());
        var activity_id = $.trim($('input[name="activity_id"]').val());
        var key = $.trim($('input[name="key"]').val());
        if(title == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写排班标题'});
            return false;
        }
        if(schedule_begin == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请选择排班开始时间'});
            return false;
        }
        if(schedule_end == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请选择排班结束时间'});
            return false;
        }
        if(user_id == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请选择值班用户'});
            return false;
        }
        var postData = {};
        postData['key'] = key;
        postData['title'] = title;
        postData['schedule_begin'] = schedule_begin;
        postData['schedule_end'] = schedule_end;
        postData['user_id'] = user_id;
        postData['activity_id'] = activity_id;
        return postData;
    },
    _submit:function (url) {
        var postData = TF_Schedule._checkForm();
        if(!postData)return false;
        TF_Ajax._ajax(url,postData);
    }
};
var TF_Train = {
    _checkForm:function(){
        var title = $.trim($('input[name="title"]').val());
        var desc = $.trim($('textarea[name="desc"]').val());
        var cover = $.trim($('input[name="cover"]').val());
        var content = $.trim($('textarea[name="content"]').val());
        var author = $.trim($('input[name="author"]').val());
        var key = $.trim($('input[name="key"]').val());
        if(title == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写培训标题'});
            return false;
        }
        if(cover == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请上传封面'});
            return false;
        }
        if(content == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写内容'});
            return false;
        }
        if(isNaN(key)){
            TB_Pop._init('parent','msg',{'icon':2,'content':'当前数据不存在'});
            return false;
        }
        var postData = {};
        postData['title'] = title;
        postData['key'] = key;
        postData['desc'] = desc;
        postData['cover'] = cover;
        postData['content'] = content;
        postData['author'] = author;
        return postData;
    },
    _submit:function (url) {
        var postData = TF_Train._checkForm();
        if(!postData)return false;
        TF_Ajax._ajax(url,postData);
    }
}
var TF_Lesson = {
    _checkForm:function(){
        var title = $.trim($('input[name="title"]').val());
        var desc = $.trim($('textarea[name="desc"]').val());
        var cover = $.trim($('input[name="cover"]').val());
        var content = $.trim($('textarea[name="content"]').val());
        var video = $.trim($('input[name="video"]').val());
        var key = $.trim($('input[name="key"]').val());
        var status = $.trim($("input[name='status']:checked").val());
        if(title == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写课程标题'});
            return false;
        }
        if(desc == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写描述'});
            return false;
        }
        if(cover == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请上传封面'});
            return false;
        }
        if(content == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写内容'});
            return false;
        }
        if(video == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请上传视频'});
            return false;
        }
        if(isNaN(key)){
            TB_Pop._init('parent','msg',{'icon':2,'content':'当前数据不存在'});
            return false;
        }
        var postData = {};
        postData['title'] = title;
        postData['key'] = key;
        postData['desc'] = desc;
        postData['cover'] = cover;
        postData['content'] = content;
        postData['video'] = video;
        postData['status'] = status;
        return postData;
    },
    _submit:function (url) {
        var postData = TF_Lesson._checkForm();
        if(!postData)return false;
        TF_Ajax._ajax(url,postData);
    }
}
var TF_ThirdpartyUser = {
    _checkForm:function(){
        var key = $.trim($('input[name="key"]').val());
        var name = $.trim($('input[name="name"]').val());
        var status = $.trim($("input[name='status']:checked").val());
        if(isNaN(key)){
            TB_Pop._init('parent','msg',{'icon':2,'content':'当前数据不存在'});
            return false;
        }
        var postData = {};
        postData['key'] = key;
        postData['status'] = status;
        postData['name'] = name;
        return postData;
    },
    _submit:function (url) {
        var postData = TF_ThirdpartyUser._checkForm();
        if(!postData)return false;
        TF_Ajax._ajax(url,postData);
    }
}
var TF_ThirdActivity = {
    _checkForm:function(){
        var key = $.trim($('input[name="key"]').val());
        var name = $.trim($('input[name="title"]').val());
        var status = $.trim($("input[name='status']:checked").val());
        if(isNaN(key)){
            TB_Pop._init('parent','msg',{'icon':2,'content':'当前数据不存在'});
            return false;
        }
        var postData = {};
        postData['key'] = key;
        postData['status'] = status;
        postData['title'] = name;
        return postData;
    },
    _submit:function (url) {
        var postData = TF_ThirdActivity._checkForm();
        if(!postData)return false;
        TF_Ajax._ajax(url,postData);
    }
}
var TF_Zone = {
    _checkForm:function(){
        var district = $.trim($('select[name="district"]').val());
        var name = $.trim($('input[name="name"]').val());
        var rank = $.trim($('input[name="rank"]').val());
        var status = $.trim($('input[name="status"]:checked').val());
        var key = $.trim($('input[name="key"]').val());
        if(isNaN(district)){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请选择所属区县'});
            return false;
        }
        if(name == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写商圈名称'});
            return false;
        }
        if(isNaN(rank)){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写排序等级'});
            return false;
        }
        if(isNaN(key)){
            TB_Pop._init('parent','msg',{'icon':2,'content':'当前数据不存在'});
            return false;
        }
        var postData = {};
        postData['status'] = status;
        postData['key'] = key;
        postData['district'] = district;
        postData['name'] = name;
        postData['rank'] = rank;
        return postData;
    },
    _submit:function (url) {
        var postData = TF_Zone._checkForm();
        if(!postData)return false;
        TF_Ajax._ajax(url,postData);
    }
}
var TF_Organize = {
    _checkForm:function(){
        var name = $.trim($('input[name="name"]').val());
        var status = $.trim($('input[name="status"]:checked').val());
        var key = $.trim($('input[name="key"]').val());
        if(name == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写分类名称'});
            return false;
        }
        if(isNaN(key)){
            TB_Pop._init('parent','msg',{'icon':2,'content':'当前数据不存在'});
            return false;
        }
        var postData = {};
        postData['status'] = status;
        postData['key'] = key;
        postData['name'] = name;
        return postData;
    },
    _submit:function (url) {
        var postData = TF_Organize._checkForm();
        if(!postData)return false;
        TF_Ajax._ajax(url,postData);
    }
}
var TF_Level = {
    _checkForm:function(){
        var title = $.trim($('input[name="title"]').val());
        var desc = $.trim($('textarea[name="desc"]').val());
        var key = $.trim($('input[name="key"]').val());
        if (title==''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写等级名称'});
            return false;
        }
        if(isNaN(key)){
            TB_Pop._init('parent','msg',{'icon':2,'content':'当前数据不存在'});
            return false;
        }
        var postData = {};
        postData['title'] = title;
        postData['desc'] = desc;
        postData['key'] = key;

        return postData;
    },
    _submit:function (url) {
        var postData = TF_Level._checkForm();
        if(!postData)return false;
        TF_Ajax._ajax(url,postData);
    }
}
var TF_Reward = {
    _checkForm:function(){
        var name = $.trim($('input[name="name"]').val());
        var stock = $.trim($('input[name="stock"]').val());
        var key = $.trim($('input[name="key"]').val());

        if(name == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写奖励名称'});
            return false;
        }
        if(stock == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写库存'});
            return false;
        }
        if(isNaN(key)){
            TB_Pop._init('parent','msg',{'icon':2,'content':'当前数据不存在'});
            return false;
        }
        var postData = {};
        postData['name'] = name;
        postData['stock'] = stock;
        postData['key'] = key;

        return postData;
    },
    _submit:function (url) {
        var postData = TF_Reward._checkForm();
        if(!postData)return false;
        TF_Ajax._ajax(url,postData);
    }
}
var TF_Article = {
    _checkForm:function(){
        var title = $.trim($('input[name="title"]').val());
        var desc = $.trim($('textarea[name="desc"]').val());
        var type = $.trim($('select[name="type"]').val());
        var content = $.trim($('textarea[name="content"]').val());
        var cover = $.trim($('input[name="cover"]').val());
        var status = TB_Common._getRadioVal('status');
        var key = $.trim($('input[name="key"]').val());
        if(title == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写文章标题'});
            return false;
        }
        if(type == '' || type==0){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请选择文章类型'});
            return false;
        }
        if(cover == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请上传封面'});
            return false;
        }
        if(content == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写文章内容'});
            return false;
        }

        if(isNaN(status)){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请选择状态'});
            return false;
        }
        if(isNaN(key)){
            TB_Pop._init('parent','msg',{'icon':2,'content':'当前数据不存在'});
            return false;
        }

        var postData = {};
        postData['status'] = status;
        postData['key'] = key;
        postData['title'] = title;
        postData['desc'] = desc;
        postData['type'] = type;
        postData['content'] = content;
        postData['cover'] = cover;
        return postData;
    },
    _submit:function (url) {
        var postData = TF_Article._checkForm();
        if(!postData)return false;
        TF_Ajax._ajax(url,postData);
    }
}
var TF_Advert = {
    _checkForm:function(){
        var name = $.trim($('input[name="name"]').val());
        var type = $.trim($('select[name="type"]').val());
        var url = $.trim($('input[name="url"]').val());
        var price = $.trim($('input[name="price"]').val());
        var publish_time = $.trim($('input[name="publish_time"]').val());
        var desc = $.trim($('textarea[name="desc"]').val());
        var content = $.trim($('textarea[name="content"]').val());
        var cover = $.trim($('input[name="cover"]').val());
        var status = TB_Common._getRadioVal('status');
        var key = $.trim($('input[name="key"]').val());
        if(name == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写广告标题'});
            return false;
        }
        if(type == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请选择广告类型'});
            return false;
        }
        if(url == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写广告地址'});
            return false;
        }
        if(price == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写价格'});
            return false;
        }
        if(publish_time == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写投放时间'});
            return false;
        }
        if(cover == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请上传封面'});
            return false;
        }
        if(content == ''){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请填写广告内容'});
            return false;
        }
        if(isNaN(status)){
            TB_Pop._init('parent','msg',{'icon':2,'content':'请选择状态'});
            return false;
        }
        if(isNaN(key)){
            TB_Pop._init('parent','msg',{'icon':2,'content':'当前数据不存在'});
            return false;
        }

        var postData = {};
        postData['status'] = status;
        postData['key'] = key;
        postData['name'] = name;
        postData['type'] = type;
        postData['url'] = url;
        postData['price'] = price;
        postData['publish_time'] = publish_time;
        postData['desc'] = desc;
        postData['content'] = content;
        postData['cover'] = cover;
        return postData;
    },
    _submit:function (url) {
        var postData = TF_Advert._checkForm();
        if(!postData)return false;
        TF_Ajax._ajax(url,postData);
    }
}

