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
use \Think\Log;

/**
 * 后台用户控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class FeedbackController extends AdminController {

    /**
     * 用户管理首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        $search       =   I('nickname');
//dump($search);
        if($search){
            $map['username'] = array('like',"%$search%");
            $map['content'] = array('like',"%$search%");
            $map['_logic'] = 'or';
        }
        else {
            $map = null;
        }

//        $model = M('feedback')->join('user ON feedback.userid = user.id')->select();
//        Log::record('XXXXXX--->'.print_r($model));
//        $model = M('feedback')->alias('f')->join('LEFT JOIN user u on f.userid=u.id')->field('f.id, content, feedback_time, answered, username')->order('feedback_time desc')->select();
//        $model = $model->group('username')->select();
        $sql = 'select * from (select f.id, userid,content, feedback_time, answered, username from feedback as f left join user as u on f.userid=u.id order by feedback_time desc) t group by username';
        if($search) {
            $sql = 'select * from('.$sql.') k where (username like "%'.$search.'%" or content like "%'.$search.'%") order by answered asc, id desc';
        }
        else {
            $sql = 'select * from('.$sql.') k order by answered asc, id desc';
        }
        $model = M()->query($sql);

        $total = count($model);
        $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        $page = new \Think\Page($total, $listRows);
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');
        $this->assign('_total',$total);

        $model = array_slice($model,  $page->firstRow, $page->listRows);

        $this->assign('_list', $model);
        $this->meta_title = '用户意见';

        $this->display();
    }

    public function answer() {
        if(!IS_POST) {
            $id = I('id');
            if (!$id) {
                $this->error('查看失败');
            }

            $dao = M('feedback');
            $userid = $dao->where('id='.$id)->getField('userid');
            $map['userid'] = array('eq',$userid);
            $map['_string'] = 'answered = 6';
            $history = $dao->where($map)->order('id')->select();

//            $map['id'] = array('egt', $id);
            $map['_string'] = 'answered = 0 or answered = 3';
            $current = $dao->where($map)->order('id')->select();
//            dump($map);
//            dump($current);

            $this->assign('_history', $history);
            $this->assign('_current', $current);
            $this->assign('_userid', $userid);
            $this->assign('_feedbackId', $id);

            $map['_string'] = 'answered = 0';
            $data['answered'] = 3;
            $flag = $dao->where($map)->save($data);
            if($flag === false) {
                $this->error("查看失败");
            }

            $this->display();
        }
        else {
            $id = I('post.id');
            $userid = I('post.userid');
            $answer = I('post.answer');

            Log::record("feedback1---".$id);
            Log::record("feedback1---".$userid);
            Log::record("feedback1---".$answer);

            if(!$answer) {
                $this->error("回复内容不能为空！");
            }

            $model = M('feedback');
            $status = $model->where('id='.$id)->getField('answered');
            if($status == 6) {
                $data['userid'] = $userid;
                $data['answer'] = $answer;
                $data['answer_time'] = time();
                $data['answered'] = 6;
                $data['isread'] = 0;

                $flag = $model->add($data);
            }
            else {
                $data['answer'] = $answer;
                $data['answer_time'] = time();
                $data['answered'] = 6;
                $data['isread'] = 0;

                $flag = $model->where('id='.$id)->save($data);
            }

            if($flag === false) {
                $this->error('回复失败！');
            }
            else {
                $this->success("回复成功", U("Feedback/index"));
            }
        }


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
        $map['uid'] =   array('in',$id);
        switch ( strtolower($method) ){
            case 'forbiduser':
                $this->forbid('Member', $map );
                break;
            case 'resumeuser':
                $this->resume('Member', $map );
                break;
            case 'deleteuser':
                $this->delete('Member', $map );
                break;
            default:
                $this->error('参数非法');
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

}