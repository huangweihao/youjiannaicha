<?php
// 应用公共文件
use app\common\Message;
/**
 * 响应输出
 * @param int $code HTTP编码
 * @param string $msg 描述内容
 * @param array $data 返回的数据
 * @param string $headerType 头文件类型
 * @return mixed
 */
function doResponse($code=200,$msg='',$data=[],$headerType=''){
    $header = [];
    switch ($headerType){
        case 'json':
            $header['contentType'] = 'application/json';
            break;
    }
    $backCode = $code<300 ? 200 : $code;
    return json(['code'=>$code,'msg'=>(new Message())->get($code).$msg,'result'=>$data])->code($backCode)->header($header);
}
/**
 * 页面跳转
 * @param string url 页面跳转地址
 */
function doRedirect($url=''){
    header('location:'.$url);
    die();
}
/**
 * 格式化货币元到分
 * @param int $yuan 元
 * @return mixed
 */
function formatYuanToFen($yuan=0){
    $yuan = $yuan*100;
    return intval(strval($yuan));
}
/**
 * 格式化货币分到元
 * @param int $fen 分
 * @return 元
 */
function formatFenToYuan($fen=0){
    $fen = $fen/100;
    return sprintf("%.2f",$fen);
}
/**
 * 图片路径处理
 * @param string $url
 * @param string $type 处理方式in/out，in入库，out显示
 * @param string $img 图片地址，in为完整地址，out为图片名称
 * @return string 图片路径
 */
function reformImgPath($type,$img,$url=''){
    $imgName = '';
    switch ($type){
        case 'in':
            $imgName = str_replace(config('user.qcloud.base.domain'),'',$img);
            $imgName = ltrim($imgName,"/");
            break;
        case 'out':
            if(empty($url))$url = config('user.qcloud.base.domain');
            $imgName = $url.$img;
            break;
    }
    return $imgName;
}
/**
 * 微信LOGO处理
 * @param string $type 处理方式in/out，in入库，out显示
 * @param string $img 图片地址，in为完整地址，out为图片名称
 * * @param string $url
 * @return string 图片路径
 */
function reformWxPhotoPath($type,$img,$url=''){
    $imgName = '';
    $logoUrl = config('user.wx.logo_domain');
    switch ($type){
        case 'out':
            $httpsStr = substr($img,0,8);
            if($httpsStr == 'https://'){
                $imgName = $img;
            }else{
                $imgName = $logoUrl.$img;
            }
            break;
        case 'in':
            $imgName = str_replace($logoUrl,'',$img);
            break;
    }
    return $imgName;
}
/**
 * 格式化IP地址
 * @param int $ip
 * @return mixed
 */
function formatIp2Long($ip=0){
    return sprintf("%u",ip2long($ip));
}
/**
 * 记录日志
 * @param string $errName
 * @param array $logData
 * @param string $errPart
 */
function doRecordLog($errName,&$logData,$errPart=''){
    switch ($errPart){
        case 'login':
            $errPart = 'login-error';
            break;
        case 'cache':
            $errPart = 'cache-error';
            break;
        case 'sql':
            $errPart = 'sql-error';
            break;
        case 'cache':
            $errPart = 'cache-error';
            break;
        case 'lucky':
            $errPart = 'lucky-error';
            break;
        case 'program':
            $errPart = 'program-error';
            break;
    }
    \think\facade\Log::record('['.$errName.']'.@json_encode($logData, JSON_UNESCAPED_UNICODE), $errPart);
}
/**
 * 生成用户的组合id，表后缀加表ID
 * @param string $type
 * @param string $withSuffixId
 * @param string $userId
 * @return mixed
 */
function userUnionId($type,$withSuffixId='',$userId=''){
    switch ($type){
        case 'getSuffix':
            return substr($withSuffixId,-2);
            break;
        case 'setUnionId':
            $withSuffixId = strlen($withSuffixId)==1 ? '0'.$withSuffixId : $withSuffixId;
            return $userId.$withSuffixId;
            break;
        case 'splitUnionId':
            $tmpLength = strlen($withSuffixId);
            $userId = (int)substr($withSuffixId,0,$tmpLength-2);
            $suffixId = (int)substr($withSuffixId,-2);
            return ['suffix'=>$suffixId,'userId'=>$userId];
            break;
        case 'setRedisKey':
            return (int)$withSuffixId.'_'.$userId;
            break;
    }
}
/**
 * 列表页的操作按钮
 * @param array $power
 * @param string $updateUrl
 * @param string $deleteUrl
 * @param boolean $isUpdate
 * @param boolean $isDel
 * @return string 按钮html
 */
function getWorkBtn($power,$updateUrl='',$deleteUrl='',$isUpdate=true,$isDel=true){
    $btnStr = '';
    if(!empty($power['update']) || !empty($power['delete'])){
        $btnStr .= '<button type="button" class="am-btn am-btn-default" onclick="TB_Common._setChkAll(\'chk\');">全选</button>';
        $btnStr .= '<button type="button" class="am-btn am-btn-default" onclick="TB_Common._setChkOut(\'chk\');">反选</button>';
        if($isUpdate && !empty($power['update'])){
            $btnStr .= '<button type="button" class="am-btn am-btn-success" onclick="TF_List._listUpdate(\'normal\',\''.$updateUrl.'\');"><span class="am-icon-unlock"></span> 打开</button>';
            $btnStr .= '<button type="button" class="am-btn am-btn-warning" onclick="TF_List._listUpdate(\'lock\',\''.$updateUrl.'\');"><span class="am-icon-lock"></span> 锁定</button>';
        }
        if($isDel && !empty($power['delete'])){
            $btnStr .= '<button type="button" class="am-btn am-btn-danger" onclick="TF_List._listDel(\''.$deleteUrl.'\');"><span class="am-icon-minus"></span> 删除</button>';
        }
    }
    return $btnStr;
}
/**
 * 下拉选择HTML封装
 * @param string selectName select dom的名称
 * @param array data 下拉的数据
 * @param integer selected 选中的值
 * @param string initName 默认的名称
 * @return string
 */
function htmlSelect($selectName,$data=[],$selected=0,$initName=''){
    $str = '<div class="am-form-group am-fl"><select style="display: none" name="'.$selectName.'" data-am-selected="{searchBox: 1, btnSize: \'sm\',placeholder:\''.$initName.'\', maxHeight: 400}>';
    $str .= '<option value=""></option>';
    foreach($data as $key=>$value){
        if(is_array($value)){
            $str .= '<option value="'.$key.'"';
            if($selected == $key)$str .= ' selected';
            $str .= '>'.$value['name'].'</option>';
            if(!empty($value['child'])){
                foreach($value['child'] as $childKey=>$childValue){
                    $str .= '<option value="'.$childKey.'"';
                    if($selected == $childKey)$str .= ' selected';
                    $str .= '>----'.$childValue.'</option>';
                }
            }
        }else{
            $str .= '<option value="'.$key.'"';
            if($selected == $key)$str .= ' selected';
            $str .= '>'.$value.'</option>';
        }
    }
    $str .= '</select></div>';
    return $str;
}
/**
 * 弹窗选择HTML封装
 * @param string type 处理方式add/del
 * @param array data
 * @return string
 */
function htmlChoose($type,$data){
    $str = '';
    switch ($type){
        case 'user':
            foreach($data as $key=>$value){
                $showName = $value['username']??'';
                $showName .= !empty($value['mobile']) ? '('.$value['mobile'].')' : '';
                if(!empty($showName)){
                    $str .= '<li class="choose-'.$value['id'].'"><span title="'.$showName.'" >'.$showName.'</span> <i class="am-icon-plus" onclick="TB_Choose._chooseAdd(\''.$value['id'].'\',\''.$value['username'].'\')"></i></li>';
                }
            }
            break;
        case 'del':
            foreach($data as $key=>$value){
                $str .= '<li class="choosed-'.$value['id'].'"><span>\''.$value['name'].'\'</span> <i class="am-icon-minus" onclick="TB_Choose._chooseDel(this,\''.$value['id'].'\')"></i></li>';
            }
            break;
    }
    return $str;
}
/**
 * choose数据json处理
 * @param string $type 处理方式in/out，in入库，out显示
 * @param string $json json格式
 * @return string
 */
function reformJson($type,$json){
    $backData = '';
    switch ($type){
        case 'split':
            $json = @json_decode($json,true);
            if(!empty($json)){
                $backData = ['id'=>array_keys($json)[0],'name'=>array_values($json)[0]];
            }
            break;
        case 'reunion':
            $backData = json_encode($json,JSON_UNESCAPED_UNICODE);
            break;
        case 'in':
            $json = @json_decode($json,true);
            if(!empty($json)){
                $backData = array_keys($json)[0];
            }
        case 'in_arr':
            $json = @json_decode($json, true);
            if(!empty($json) and is_array($json)){
                $backData = [];
                foreach ($json as $key => $value) {
                    $backData[] = $key;
                }
            }
            break;
        case 'sec':
            $json = @json_decode($json,true);
            if(!empty($json)){
                $backData = array_values($json)[0];
            }
            break;
        case 'out':
            $json = @json_decode($json,true);
            if(!empty($json)){
                foreach($json as $key=>$value){
                    $backData .= '<div class="file-item am-text-sm">'.$value.'</div>';
                }
            }
            break;
    }
    return $backData;
}

/**
 * 格式化
 * @param $text
 * @return mixed
 */
function br2nl($text){
    return preg_replace('/<br\\s*?\/??>/i', '', $text);
}
/**
 * 根据ip获取地区
 * @param $ip
 * @return bool|string
 */
function getAreaByIp($ip){

    $backData=true;
    $res = file_get_contents("http://api.map.baidu.com/location/ip?ak=ybgZWuR5q3lmSBTjPS4On6RA&ip=".$ip);
    $array = json_decode($res,true);
    if (!empty($array)) {
        if (!empty($array['address'])) {
            if (strpos($array['address'], '上海')===false){
                $backData=false;
            }
        }
    }
    return $backData;
}