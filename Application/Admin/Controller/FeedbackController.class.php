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
        $this->meta_title = '意见列表';

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

            $this->meta_title = '意见详情';
            $this->display();
        }
        else {
            $id = I('post.id');
            $userid = I('post.userid');
            $answer = I('post.answer');

//            Log::record("feedback1---".$id);
//            Log::record("feedback1---".$userid);
//            Log::record("feedback1---".$answer);

            if(!$answer) {
                $this->error("回复内容不能为空！");
            }

            $model = M('feedback');
            $status = $model->where('id='.$id)->getField('answered');
            if($status == C('FEEDBACK_STATUS_REPLIED')) {
                $data['userid'] = $userid;
                $data['answer'] = $answer;
                $data['answer_time'] = time();
                $data['answered'] = C('FEEDBACK_STATUS_REPLIED');
                $data['isread'] = 0;

                $flag = $model->add($data);
            }
            else {
                $data['answer'] = $answer;
                $data['answer_time'] = time();
                $data['answered'] = C('FEEDBACK_STATUS_REPLIED');
                $data['isread'] = 0;

                $flag = $model->where('id='.$id)->save($data);
            }

            if($flag === false) {
                $this->error('回复失败！');
            }
            else {
                //把同一批较早的用户意见状态置为已回复
                $update['answered'] = C('FEEDBACK_STATUS_REPLIED');
                $update['isread'] = 1;
                $flag = $model->where('userid='.$userid.' and id<'.$id.' and answered!='.C('FEEDBACK_STATUS_REPLIED'))->save($update);

                $this->sendNotice($userid);
                $this->success("回复成功", U("Feedback/index"));
            }
        }
    }

    private function sendNotice($userid) {
        $condition['userid'] =  $userid;
        $model = M('device_token');
        $deviceToken = $model->where($condition)->getField('device_token');
        $deviceType = $model->where($condition)->getField('device_type');

        Log::record(date("Y-m-d H:i:s").' device token'.$deviceToken);
        Log::record(date("Y-m-d H:i:s").' device type'.$deviceType);

        //没有登记设备信息
        if(!$deviceToken) {
            return;
        }

        if($deviceType == C('DEVICE_TYPE_ANDROID')) {
            $flag = sendAndroidUnicastMessage($deviceToken);
        }
        else if ($deviceType == C('DEVICE_TYPE_IOS')) {
            $flag = sendIOSUnicastMessage($deviceToken);
        }

        if(!$flag) {
            //即使提醒通知没发送成功，客服回复也视为成功。
        }
    }

    /**
     * 会员状态修改
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function changeStatus($method=null){
        $id = array_unique((array)I('id',0));
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['id'] =   array('in',$id);
        switch ( strtolower($method) ){
            case 'deleteuser':
                Log::record('feedback----'.json_encode($map));
                $flag = M('feedback')->where($map)->delete();
                if($flag === false) {
                    $this->error('删除失败！');
                }
                else {
                    $this->success('删除成功！');
                }
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