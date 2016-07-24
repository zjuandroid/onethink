<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use User\Api\UserApi;

/**
 * 后台用户控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class UsersController extends AdminController {

    /**
     * 用户管理首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        $username       =   I('nickname');
        $map['status']  =   array('egt',0);
        if($username){
            $map['nickname']    =   array('like', '%'.(string)$username.'%');
        }

        $list   = $this->lists('user', $map);
        int_to_string($list);
        $this->assign('_list', $list);
        $this->meta_title = '用户信息';
//        dump($this->getMenus());
        $this->display();
    }


    /**
     * 会员状态修改
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function changeStatus($method=null){
        $id = array_unique((array)I('id',0));
        if( in_array(C('USER_ADMINISTRATOR'), $id)){
            $this->error("不允许对超级管理员执行该操作!");
        }
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['id'] =   array('in',$id);
        switch ( strtolower($method) ){
            case 'forbiduser':
                $this->forbid('user', $map );
                break;
            case 'resumeuser':
                $this->resume('user', $map );
                break;
            case 'deleteuser':
                $this->delete('user', $map );
                break;
            default:
                $this->error('参数非法');
        }
    }

    public function add($username = '', $password = '', $repassword = '', $email = ''){
        if(IS_POST){
            /* 检测密码 */
            if($password != $repassword){
                $this->error('密码和重复密码不一致！');
//                $this->error($password);
            }

            $user = array('username' => $username, 'password' => md5($password));
            if(!M('user')->add($user)){
                $this->error('用户添加失败！');
            } else {
                $this->success('用户添加成功！',U('index'));
            }
        } else {
            $this->meta_title = '新增用户';
            $this->display();
        }
    }

    /**
     * 获取用户注册错误信息
     * @param  integer $code 错误编码
     * @return string        错误信息
     */
    private function showRegError($code = 0){
        switch ($code) {
            case -1:  $error = '用户名长度必须在16个字符以内！'; break;
            case -2:  $error = '用户名被禁止注册！'; break;
            case -3:  $error = '用户名被占用！'; break;
            case -4:  $error = '密码长度必须在6-30个字符之间！'; break;
            case -5:  $error = '邮箱格式不正确！'; break;
            case -6:  $error = '邮箱长度必须在1-32个字符之间！'; break;
            case -7:  $error = '邮箱被禁止注册！'; break;
            case -8:  $error = '邮箱被占用！'; break;
            case -9:  $error = '手机格式不正确！'; break;
            case -10: $error = '手机被禁止注册！'; break;
            case -11: $error = '手机号被占用！'; break;
            default:  $error = '未知错误';
        }
        return $error;
    }


    function detail() {
        $id = I('id');
        if(!$id) {
            $this->error('参数错误', U('Users/index'));
        }
        $user = M('user')->where('id='.$id)->select();
        $tankList = M('fishtank')->field('name,opendate,fishkinds,thermometer_list,light_list,socket')->where('userid='.$id)->select();

        for($i = 0; $i < count($tankList); $i++) {
            $str = $tankList[$i]['fishkinds'];
            if($str) {
                $tankList[$i]['fishkinds'] = getFishNameStr($str);
            }

            $str = $tankList[$i]['thermometer_list'];
            $tankList[$i]['thermometer_list'] = validateListStr($str) ? count(json_decode($str)):0;


            $str = $tankList[$i]['light_list'];
            $tankList[$i]['light_list'] = validateListStr($str) ? count(json_decode($str)):0;

            $str = $tankList[$i]['socket'];
            $tankList[$i]['socket'] = $str ? 1:0;
        }

        if($user) {
            $user = $user[0];
        }
        if($user['good_at']) {
            $user['good_at'] = getFishNameStr($user['good_at']);
        }
        $user['avatar'] = $user['avatar'] ? substr(C('AVATAR_ROOT_PATH'), 1).C('AVATAR_SAVE_PATH').$user['avatar'] : '';

        $this->assign('user', $user);
        $this->assign('tankList',$tankList);
        $this->display();
    }

}