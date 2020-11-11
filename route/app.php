<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('login$','Login/show');
Route::get('login/logout$','Login/doLoginOut');
Route::post('login$','Login/doLogin')->token();
Route::get('msg$','Msg/show');
Route::get('refreshToken$','Msg/getToken');


Route::group(function () {
    Route::get('frame$','Frame/show');
    Route::get('main$','Main/show');
    Route::post('pageSet$','Msg/pageSet');
    Route::get('map$','Map/show');
    Route::get('file/fileList','File/fileList');
    Route::post('file/deleteFiles','File/deleteFiles');
    Route::post('upload/image','Upload/image');
    Route::post('video$','Upload/upVideo');
    Route::get('pwd','Pwd/e');
    Route::post('pwd/doe$','Pwd/doe')->token();

    Route::get('role$','Role/getList');
    Route::get('role/i$','Role/i');
    Route::get('role/e$','Role/e');
    Route::post('role/doi$','Role/doi')->token();
    Route::post('role/doe$','Role/doe')->token();
    Route::post('role/dou$','Role/dou')->token();
    Route::post('role/dod$','Role/dod')->token();

    Route::get('manager$','Manager/getList');
    Route::get('manager/i$','Manager/i');
    Route::get('manager/e$','Manager/e');
    Route::post('manager/doi$','Manager/doi')->token();
    Route::post('manager/doe$','Manager/doe')->token();
    Route::post('manager/dou$','Manager/dou');
    Route::post('manager/dod$','Manager/dod');

    Route::get('managerworklog$','ManagerWorkLog/getList');
    Route::post('managerworklog/dod$','ManagerWorkLog/dod');

    Route::get('log$','LoginLog/getList');

    #志愿者模块
    Route::get('volunteer$', 'Volunteer/list');#志愿者列表
    Route::get('volunteer/e$', 'Volunteer/e');#志愿者编辑
    Route::post('volunteer/doe$', 'Volunteer/doe')->token();#志愿者编辑
    Route::post('volunteer/dou$','Volunteer/dou');#更新状态
    Route::post('volunteer/dod$','Volunteer/dod');#删除
    Route::get('volunteer/examine$','Volunteer/examine');#考核
    Route::get('info$','Volunteer/info');#信息管理
    Route::get('volunteer/rewardup$','Volunteer/rewardUp');#颁发奖励

    #志愿者组织模块
    Route::get('organize$', 'Organize/list');#组织列表
    Route::get('organize/e$', 'Organize/e');#组织编辑
    Route::get('organize/i$', 'Organize/i');#组织编辑
    Route::post('organize/doi$', 'Organize/doi');#组织添加
    Route::post('organize/doe$', 'Organize/doe')->token();#志愿者编辑
    Route::post('organize/dou$','Organize/dou');#组织更新状态
    Route::post('organize/dod$','Organize/dod');#组织删除

    #志愿者培训模块
    Route::get('train$', 'Train/list');#培训列表
    Route::get('train/e$', 'Train/e');#培训编辑入口
    Route::get('train/i$', 'Train/i');#培训添加入口
    Route::post('train/doi$', 'Train/doi');#编辑培训
    Route::post('train/doe$', 'Train/doe')->token();#培训编辑
    Route::post('train/dou$','Train/dou');#更新培训状态
    Route::post('train/dod$','Train/dod');#删除培训

    #志愿活动
    Route::get('volunteeractivity$', 'VolunteerActivity/list');#志愿活动列表
    Route::get('volunteeractivity/e$', 'VolunteerActivity/e');#志愿活动编辑入口
    Route::get('volunteeractivity/schedule$', 'VolunteerActivity/schedule');#志愿活动排班入口
    Route::get('volunteeractivity/i$', 'VolunteerActivity/i');#志愿活动添加入口
    Route::post('volunteeractivity/doi$', 'VolunteerActivity/doi');#编辑志愿活动
    Route::post('volunteeractivity/doe$', 'VolunteerActivity/doe')->token();#志愿活动编辑
    Route::post('volunteeractivity/dou$','VolunteerActivity/dou');#更新志愿活动状态
    Route::post('volunteeractivity/dod$','VolunteerActivity/dod');#删除志愿活动
    Route::get('volunteeractivity/show$','VolunteerActivity/show');#活动已排班列表

    #排班
    Route::get('schedule$', 'Schedule/list');#排班列表
    Route::get('schedule/e$', 'Schedule/e');#排班编辑入口
    Route::get('schedule/i$', 'Schedule/i');#排班添加入口
    Route::post('schedule/doi$', 'Schedule/doi');#编辑排班
    Route::post('schedule/doe$', 'Schedule/doe')->token();#排班编辑
    Route::post('schedule/dou$','Schedule/dou');#更新排班
    Route::post('schedule/dod$','Schedule/dod');#删除排班

    #志愿者等级
    Route::get('level$', 'Level/list');#等级列表
    Route::get('level/e$', 'Level/e');#等级编辑入口
    Route::get('level/i$', 'Level/i');#等级添加入口
    Route::post('level/doi$', 'Level/doi');#编辑等级
    Route::post('level/doe$', 'Level/doe')->token();#等级编辑
    Route::post('level/dou$','Level/dou');#更新等级
    Route::post('level/dod$','Level/dod');#删除等级

    #志愿者奖励
    Route::get('reward$', 'Reward/list');#奖励列表
    Route::get('reward/e$', 'Reward/e');#奖励编辑入口
    Route::get('reward/i$', 'Reward/i');#奖励添加入口
    Route::post('reward/doi$', 'Reward/doi');#编辑奖励
    Route::post('reward/doe$', 'Reward/doe')->token();#奖励编辑
    Route::post('reward/dou$','Reward/dou');#更新奖励
    Route::post('reward/dod$','Reward/dod');#删除奖励

    #岗位统计
    Route::get('station$', 'Station/list');#统计列表
    Route::get('excel$', 'Station/excel');#统计列表

    #系统设置
    Route::get('clean$', 'Clean/doCache');#缓存清楚列表
    Route::get('fil$', 'FileManage/list');#上传文件列表

    #内容类型
    Route::get('articletype$', 'ArticleType/list');#文章类型列表
    Route::get('articletype/e$', 'ArticleType/e');#文章类型编辑入口
    Route::get('articletype/i$', 'ArticleType/i');#文章类型添加入口
    Route::post('articletype/doi$', 'ArticleType/doi');#编辑文章类型
    Route::post('articletype/doe$', 'ArticleType/doe')->token();#文章类型编辑
    Route::post('articletype/dou$','ArticleType/dou');#更新文章类型
    Route::post('articletype/dod$','ArticleType/dod');#删除文章类型

    #内容管理
    Route::get('article$', 'Article/list');#文章列表
    Route::get('article/e$', 'Article/e');#文章编辑入口
    Route::get('article/i$', 'Article/i');#文章添加入口
    Route::post('article/doi$', 'Article/doi');#编辑文章
    Route::post('article/doe$', 'Article/doe')->token();#文章编辑
    Route::post('article/dou$','Article/dou');#更新文章
    Route::post('article/dod$','Article/dod');#删除文章

    #广告管理
    Route::get('advert$', 'Advert/list');#广告列表
    Route::get('advert/e$', 'Advert/e');#广告编辑入口
    Route::get('advert/i$', 'Advert/i');#广告添加入口
    Route::post('advert/doi$', 'Advert/doi');#编辑广告
    Route::post('advert/doe$', 'Advert/doe')->token();#广告编辑
    Route::post('advert/dou$','Advert/dou');#更新广告
    Route::post('advert/dod$','Advert/dod');#删除广告

    #广告类型
    Route::get('adverttype$', 'AdvertType/list');#广告类型列表
    Route::get('adverttype/e$', 'AdvertType/e');#广告类型编辑入口
    Route::get('adverttype/i$', 'AdvertType/i');#广告类型添加入口
    Route::post('adverttype/doi$', 'AdvertType/doi');#编辑广告类型
    Route::post('adverttype/doe$', 'AdvertType/doe')->token();#广告类型编辑
    Route::post('adverttype/dou$','AdvertType/dou');#更新广告类型
    Route::post('adverttype/dod$','AdvertType/dod');#删除广告类型

    #用户模块
    Route::get('user/choose$', 'User/choose');#用户筛选列表
    Route::post('user/choose$', 'User/choose');#用户筛选列表

    #课程管理模块
    Route::get('lesson$', 'Lesson/list');#课程列表
    Route::get('lesson/e$', 'Lesson/e');#课程编辑入口
    Route::get('lesson/i$', 'Lesson/i');#课程添加入口
    Route::post('lesson/doi$', 'Lesson/doi');#编辑课程
    Route::post('lesson/doe$', 'Lesson/doe')->token();#课程编辑
    Route::post('lesson/dou$','Lesson/dou');#更新课程状态
    Route::post('lesson/dod$','Lesson/dod');#删除课程

    #社区留言模块
    Route::get('message$', 'Message/list');#留言列表
    Route::get('message/e$', 'Message/e');#留言编辑入口
    Route::get('message/i$', 'Message/i');#留言添加入口
    Route::post('message/doi$', 'Message/doi');#编辑留言
    Route::post('message/doe$', 'Message/doe')->token();#留言编辑
    Route::post('message/dou$','Message/dou');#更新留言状态
    Route::post('message/dod$','Message/dod');#删除留言

    #社区评论模块thirdparty_user
    Route::get('comment$', 'Comment/list');#评论列表
    Route::get('comment/e$', 'Comment/e');#评论编辑入口
    Route::get('comment/i$', 'Comment/i');#评论添加入口
    Route::post('comment/doi$', 'Comment/doi');#编辑评论
    Route::post('comment/doe$', 'Comment/doe')->token();#评论编辑
    Route::post('comment/dou$','Comment/dou');#更新评论状态
    Route::post('comment/dod$','Comment/dod');#删除评论

    #第三方账号模块
    Route::get('thirdpartyuser$', 'ThirdpartyUser/list');#第三方共建列表
    Route::get('thirdpartyuser/e$', 'ThirdpartyUser/e');#第三方共建编辑入口
    Route::post('thirdpartyuser/doe$', 'ThirdpartyUser/doe')->token();#第三方共建编辑
    Route::post('thirdpartyuser/dou$','ThirdpartyUser/dou');#更新第三方共建状态
    #第三方活动审核模块
    Route::get('thirdactivity$', 'thirdActivity/list');#第三方共建活动列表
    Route::get('thirdactivity/e$', 'thirdActivity/e');#第三方共建活动编辑入口
    Route::post('thirdactivity/doe$', 'thirdActivity/doe')->token();#第三方共建活动编辑
    Route::post('thirdactivity/dou$','thirdActivity/dou');#更新第三方共建活动状态
})->middleware(['authUser']);
