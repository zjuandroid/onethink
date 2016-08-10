<?php
/**
 * Created by PhpStorm.
 * User: chwang
 * Date: 2016/7/23
 * Time: 15:52
 */

namespace Admin\Controller;
use \Think\Log;

class RecommendController extends AdminController
{
    public function index(){
        $username       =   I('nickname');
        if($username) {
            $map['title'] = array('like', "%$username%");
            $map['content'] = array('like', "%$username%");
            $map['_logic'] = 'or';
        } else {
            $map = null;
        }

        $list   = $this->lists('message', $map);
        int_to_string($list);
        $this->assign('_list', $list);
        $this->meta_title = '推荐列表';
//        dump($this->getMenus());
        $this->display();
    }


    public function changeStatus($method=null){
        $id = array_unique((array)I('id',0));
//        Log::record('Recommend1----'.json_encode($id));
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['id'] =   array('in',$id);

        Log::record('Recommend2----'.json_encode($map));
        switch ( strtolower($method) ){
            case 'deleteuser':
//                $this->delete('message', $map );
                $flag = M('message')->where($map)->delete();
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

    public function add() {
        if (!IS_POST) {
            $this->meta_title = '新建推荐';
            $this->display();
        }
        else {
            $data['title'] = I('post.title');
            $data['content'] = I('post.content');
            $data['url'] = I('post.url');
            $condition['id'] = I('post.picture');

            $data['picture'] = M('picture')->where($condition)->getField('path');
//            Log::record('Recommend1----'.json_encode($data));
            $this->sendMessage($data);
        }
    }


    function sendMessage($data) {
        $model = D("message");
        if (!$model->create()) {
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            $this->error($model->getError());
            exit();
        } else {
            $flag = sendAndroidMessage($data);
            if(!$flag) {
                $this->error("消息推送失败");
            }

            $flag = sendIOSMessage($data);
            if(!$flag) {
                $this->error("IOS消息推送失败");
            }

            //type = 1, 表示新品推荐
            $data['type'] = 1;
            $data['create_time'] = time();
            if ($model->add($data)) {
                $flag = M('user')->execute('update __TABLE__ set has_new_message=1');

                $this->success("消息推送成功", U('Recommend/index'));
//                $this->success("消息推送成功", U('Recommend/index'));
//                $this->redirect('Push/index');
            } else {
                $this->error("消息已经送出，但是没有记录在本地服务器中");
            }
        }
    }

}