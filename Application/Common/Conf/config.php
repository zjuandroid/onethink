<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 系统配文件
 * 所有系统级别的配置
 */
return array(
    /* 模块相关配置 */
    'AUTOLOAD_NAMESPACE' => array('Addons' => ONETHINK_ADDON_PATH), //扩展模块列表
    'DEFAULT_MODULE'     => 'Home',
    'MODULE_DENY_LIST'   => array('Common','User','Admin','Install'),
    //'MODULE_ALLOW_LIST'  => array('Home','Admin'),

    /* 系统数据加密设置 */
    'DATA_AUTH_KEY' => 'dJm;Y3VXve2:N,<OCn@|L(%iu$g~Wx6^>qfT`4!0', //默认数据加密KEY

    /* 用户相关设置 */
    'USER_MAX_CACHE'     => 1000, //最大缓存用户数
    'USER_ADMINISTRATOR' => 1, //管理员用户ID

    /* URL配置 */
    'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'            => 3, //URL模式
    'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符

    /* 全局过滤配置 */
    'DEFAULT_FILTER' => '', //全局过滤函数

    /* 数据库配置 */
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => '127.0.0.1', // 服务器地址
    'DB_NAME'   => 'onethink', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '112233',  // 密码
    'DB_PORT'   => '3306', // 端口
    'DB_PREFIX' => '', // 数据库表前缀

    /* 文档模型配置 (文档模型核心配置，请勿更改) */
    'DOCUMENT_MODEL_TYPE' => array(2 => '主题', 1 => '目录', 3 => '段落'),


    /* 前台相关 */
    'SMS_API_KEY' => 'eb2323adc7bb33eab35a4d4f9843f425',
    'SMS_INTERFACE' => 'http://sms-api.luosimao.com/v1/send.json',
    'AVATAR_MAX_SIZE' => 1024*1024,
    'AVATAR_ROOT_PATH' => './Uploads/',
    'AVATAR_SAVE_PATH' => 'Avatar/',
    'AVATAR_FILE_EXT' => array('jpg', 'gif', 'png', 'jpeg'),
    'CITY_FILE_PATH' => './cities.json',

    'APP_KEY_ANDROID' => '575e335ae0f55a1141001196',
    'APP_MASTER_SECRET_ANDROID' => 'lbfmvyhrerw5iwjrnigakngyipuqrjdy',
    'APP_KEY_IOS' => '57614bca67e58e4799003aa0',
    'APP_MASTER_SECRET_IOS' => 'lev3ydtdsdprdbiuhektw3pimtae5joh',
    'MESSAGE_IMAGE_SAVE_PATH'  => 'Push/',
    'PAGE_COUNT' => 10,

    'LOG_RECORD' => true, // 开启日志记录
    'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR', // 只记录EMERG ALERT CRIT ERR 错误
    'LOG_TYPE'              =>  'File', // 日志记录类型 默认为文件方式
);
