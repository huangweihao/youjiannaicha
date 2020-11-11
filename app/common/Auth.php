<?php
/**
 * 权限相关类
 * Class Auth
 * @package app\common
 */
namespace app\common;

class Auth{
    /**
     * 系统栏目权限配置表
     * @remark 1-查看 2-添加 3-编辑 4-删除 5-特殊
     */
    public function menuInit(){
        return [
            'p_manager'=>[
                'name'=>'权限管理','mark'=>'a','power'=>[1],'icon'=>'icon-guanliyuan','child'=>[
                    'role'=>[
                        'name'=>'权限管理','mark'=>'a1','power'=>[1,2,3,4],'show'=>true
                    ],
                    'manager'=>[
                        'name'=>'用户管理','mark'=>'a2','power'=>[1,2,3,4,5],'show'=>true
                    ],
                    'managerworklog'=>[
                        'name'=>'操作日志','mark'=>'a3','power'=>[1],'show'=>true
                    ],
                    'video'=>[
                        'name'=>'上传视频','mark'=>'a4','power'=>[1,2,3,4,5],'show'=>false
                    ],
                ]
            ],
            'p_volunteer'=>[
                'name'=>'志愿者管理','mark'=>'b','power'=>[1],'icon'=>'icon-user','child'=>[
                    'volunteer'=>[
                        'name'=>'志愿者列表','mark'=>'b1','power'=>[1,2,3,4,5],'show'=>true
                    ],
                    'organize'=>[
                        'name'=>'组织管理','mark'=>'b2','power'=>[1,2,3,4,5],'show'=>true
                    ],
                    'schedule'=>[
                        'name'=>'排班列表','mark'=>'b3','power'=>[1,2,3,4,5],'show'=>true
                    ],
                    'station'=>[
                        'name'=>'岗位统计','mark'=>'b4','power'=>[1,2,3,4,5],'show'=>true
                    ],
                    'train'=>[
                        'name'=>'培训管理','mark'=>'b5','power'=>[1,2,3,4,5],'show'=>true
                    ],
                    'volunteeractivity'=>[
                        'name'=>'招募活动','mark'=>'b6','power'=>[1,2,3,4,5],'show'=>true
                    ],
                    'info'=>[
                        'name'=>'信息管理','mark'=>'b7','power'=>[1,2,3,4,5],'show'=>true
                    ],
                    'level'=>[
                        'name'=>'志愿者等级','mark'=>'b8','power'=>[1,2,3,4,5],'show'=>true
                    ],
                    'reward'=>[
                        'name'=>'志愿者奖励','mark'=>'b9','power'=>[1,2,3,4,5],'show'=>true
                    ],
                ]
            ],
            'p_substance'=>[
                'name'=>'内容管理','mark'=>'c','power'=>[1],'icon'=>'am-icon-book','child'=>[
                    'article'=>[
                        'name'=>'内容列表','mark'=>'c1','power'=>[1,2,3,4,5],'show'=>true
                    ],
                    'articletype'=>[
                        'name'=>'栏目管理','mark'=>'c2','power'=>[1,2,3,4,5],'show'=>true
                    ],
                    'advert'=>[
                        'name'=>'广告管理','mark'=>'c3','power'=>[1,2,3,4,5],'show'=>true
                    ],
                    'adverttype'=>[
                        'name'=>'广告类型','mark'=>'c4','power'=>[1,2,3,4,5],'show'=>true
                    ]
                ]
            ],
            'p_construct'=>[
                'name'=>'三方共建','mark'=>'d','power'=>[1],'icon'=>'icon-shop','child'=>[
                    'third'=>[
                        'name'=>'第三方列表','mark'=>'d1','power'=>[1,2,3,4,5],'show'=>true
                    ],
                    'thirdactivity'=>[
                        'name'=>'活动管理','mark'=>'d2','power'=>[1,3,5],'show'=>true
                    ],
                    'thirdpartyuser'=>[
                        'name'=>'账号管理','mark'=>'d3','power'=>[1,3,5],'show'=>true
                    ],
                ]
            ],
            'p_running'=>[
                'name'=>'运营管理','mark'=>'e','power'=>[1],'icon'=>'am-icon-laptop','child'=>[
                    'message'=>[
                        'name'=>'社区留言管理','mark'=>'e1','power'=>[1,3,4,5],'show'=>true
                    ],
                    'comment'=>[
                        'name'=>'社区评论管理','mark'=>'e1','power'=>[1,3,4,5],'show'=>true
                    ],
                    'count'=>[
                        'name'=>'统计管理','mark'=>'e1','power'=>[1,2,3,4,5],'show'=>true
                    ],
                    'user'=>[
                        'name'=>'用户管理','mark'=>'e3','power'=>[1,2,3,4,5],'show'=>true
                    ],
                ]
            ],
            'p_course' => [
                'name'=>'课程管理','mark'=>'f','power'=>[1],'icon'=>'am-icon-leanpub','child'=>[
                    'lesson'=>[
                        'name'=>'课程列表','mark'=>'f1','power'=>[1,2,3,4,5],'show'=>true
                    ]
                ]
            ],
            'p_setting'=>[
                'name'=>'系统设置','mark'=>'j','power'=>[1],'icon'=>'am-icon-gear','child'=>[
                    'clean'=>[
                        'name'=>'缓存清理','mark'=>'j1','power'=>[1,2,3,4,5],'show'=>true
                    ],
                    'fil'=>[
                        'name'=>'文件归档','mark'=>'j2','power'=>[1,2,3,4,5],'show'=>true
                    ],
                ]
            ],

        ];
    }
    /**
     * 验证当前用户所在控制器的权限
     * @param string controller  当前控制器
     * @param string method   当前方法
     * @param array  power 当前的权限
     * @return array [isHasPower=>boolean,power=>[]]
     */
    public function checkPower($controller,$method,&$power){
        $menuData = $this->menuInit();
        $name = '';
        $mark = '';
        $curMethod = '';
        $initPower = '';
        $isHasPower = false;
        $curPower = ['look'=>false,'insert'=>false,'update'=>false,'delete'=>false,'other'=>false];
        foreach($menuData as $parentKey=>$parentValue){
            if(!empty($parentValue['child'])){
                if(!empty($parentValue['child'][$controller])){
                    $name = $parentValue['child'][$controller]['name'];
                    $mark = $parentValue['child'][$controller]['mark'];
                    $initPower =  $parentValue['child'][$controller]['power'];
                    break;
                }
            }
        }
        if(!empty($mark)){
            foreach($initPower as $value){
                $curMark = $mark.'w'.$value;
                switch ($value){
                    case 1:
                        $curMethod = 'look';
                        break;
                    case 2:
                        $curMethod = 'insert';
                        break;
                    case 3:
                        $curMethod = 'update';
                        break;
                    case 4:
                        $curMethod = 'delete';
                        break;
                    case 5:
                        $curMethod = 'other';
                        break;
                }
                if(in_array($curMark,$power)){
                    $curPower[$curMethod] = true;
                }
            }
            switch ($method){
                case 'record':
                case 'getlist':
                case 'show':
                case 'choose':
                case 'main':
                    $number = 1;
                    break;
                case 'code':
                    $number = 1;
                    break;
                case 'i':
                case 'doi':
                    $number = 2;
                    break;
                case 'c':
                case 'e':
                case 'doe':
                case 'dou':
                case 'doc':
                    $number = 3;
                    break;
                case 'd':
                case 'dod':
                    $number = 4;
                    break;
                default:
                    $number = 5;
                    break;
            }
            $curMark = $mark.'w'.$number;
            if(in_array($curMark,$power)){
                $isHasPower = true;
            }
        }
        return ['isHasPower'=>$isHasPower,'columnName'=>$name,'columnPower'=>$curPower];
    }

}