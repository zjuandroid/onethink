<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------
namespace Addons\SiteStat;
use Common\Controller\Addon;

/**
 * 系统环境信息插件
 * @author thinkphp
 */
class SiteStatAddon extends Addon{

    public $info = array(
        'name'=>'SiteStat',
        'title'=>'站点统计信息',
        'description'=>'统计站点的基础信息',
        'status'=>1,
        'author'=>'thinkphp',
        'version'=>'0.1'
    );

    public function install(){
        return true;
    }

    public function uninstall(){
        return true;
    }

    //实现的AdminIndex钩子方法
    public function AdminIndex($param){
        $config = $this->getConfig();
        $this->assign('addons_config', $config);
        if($config['display']){
            $model = M('user');
            $info['user']		=	$model->count();
            $today = strtotime(date('Y-m-d', time()));
            $info['newUser']	= $model->where('create_at>='.$today)->count();
//            $info['newFeedback']	=	M('feedback')->where('answered='.C('FEEDBACK_STATUS_NEW_MESSAGE'))->group('userid')->count();
//            $info['newFeedback']	=	M('feedback')->where('answered='.C('FEEDBACK_STATUS_NEW_MESSAGE'))->group('userid')->count()->count();
            $sql = 'select * from (select * from feedback order by feedback_time desc) t group by userid';
//            dump(M()->query($sql));
//            $sql = 'select * from('.$sql.') k where (answered='.C('FEEDBACK_STATUS_NEW_MESSAGE').')';
//            $info['newFeedback'] = count(M()->query($sql));

            $sql = 'select count(*) as num from('.$sql.') k where (answered='.C('FEEDBACK_STATUS_NEW_MESSAGE').')';
            $info['newFeedback'] =M()->query($sql)[0]['num'];

            $info['messageNum']	=	M('message')->count();
            $this->assign('info',$info);
            $this->display('info');
        }
    }
}