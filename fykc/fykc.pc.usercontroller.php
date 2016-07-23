<?php
/**
 * Author: psw
 * Date: 2015/06/25
 */

class UserController extends PcController
{
    // 定义客户登录场景
    const SCHEMA_LOGIN = 'login'; 

    // 返回的数据
    public $response; 
  
    //用户注册
    public function actionRegister()
    {
        $data['type'] = 'frontRegister';
        $data['mobile'] = CommonUtil::post('mobile');
        $data['password']   = CommonUtil::post('password');
        $data['verifyCode'] = CommonUtil::post('verify_code');

    }


    //用户登陆
    public function actionLogin($mobile='', $password='',$autoLogin=0)
    {
        $params['mobile'] = $mobile ? $mobile : CommonUtil::post('mobile');
        $params['password']  = $password ? $password : CommonUtil::post('password');
        $params['schema'] = self::SCHEMA_LOGIN;
        $customer = new Customer;

        // 构造返回的假数据
        $temp = [];
        $temp['code'] = 1;
        $temp['msg'] = '登录成功';
        $temp['data'] = [
            'token' => 'adfqweasdfqwersdfgasdqweasf',
            'check_status' => 1,
            'is_quote' => 1,
            'id' => 23
        ];
        $temp = json_encode($temp);
        $this->response = $temp;    // $customer->customerLogin($params);
        $this->actionResponse();
        
        $this->response = $customer->customerLogin($params);


        // 日志记录类
        // $log = LogUtil::logFile('key',['k'=>'test'],'llf_test');


    }
    

    // 处理返回的数据
    public function actionResponse()
    {
        $this->response = json_decode($this->response,true);

        if(isset($this->response['code']) && $this->response['code'] ==1) {
            $return['status'] = 'Y';
        } else {
            $return['status'] = 'N';
        }
        $return['msg'] = $this->response['msg'];
        $return['data'] = $this->response['data'];
        G::pr($return);


        $this->setAjaxReturn($return);

    }

    // 用户登录失败次数
    public function actionLoginTimes($mobile)
    {
        $customer = new Customer;
    }




    //用户详情(个人中心)
    public function actionDetail()
    {

        $customerID = $this->customerCache['id'];
        $customer = new Customer;
        $params = [];
        $this->response = $customer->customerDetail($customerID,$params);
        $this->actionResponse();

    }



    //用户前台修改
    public function actionUpdate()
    {
        $customerID = $this->customerCache['id'];
        $Customer = Customer::model()->findByPk($customerID);
        $name = CommonUtil::post('name','string');
        if (!$name) $this->setAjaxReturn('N','姓名不可空');

        $Customer->setScenario('frontInfo');
        $Customer->name = $name;
        $Customer->gender = CommonUtil::post('gender');
        $Customer->password = CommonUtil::post('password');

        $vaFields = array('name','gender');
        if (!empty($Customer->password)) $vaFields[] = 'password';
        if ($Customer->save(true,$vaFields))
        {
            $cache = array();
            $cache['id'] = $return['id'] = $Customer->id;
            $cache['name'] =$Customer->name;
            $cache['mobile'] =$Customer->mobile;
            $cache['customerType'] =$Customer->type;
            $cache['accountType'] =array('id'=>$Customer->account_type,'text'=>Config::getConfig('customer','account_type',$Customer->account_type));
            $cache['company_id'] =$Customer->company_id;

            $Customer->addLoginTokenIntoRedis(Customer::REDIS_CUSTOMER_TOKEN.TOKEN,$cache,3600);
            $this->setAjaxReturn('Y',array('msg'=>'修改成功','info'=>$cache));
        }
        else
        {
            if($err = $Customer->getError('name')) $this->setAjaxReturn('N',$err);
            $this->setAjaxReturn('N','修改失败');
        }

    }

    //重设密码(账户安全)
    public function actionReset()
    {
//        $mobile = CommonUtil::post('mobile');
//        $password   = CommonUtil::post('password');
//        $verifyCode = CommonUtil::post('verify_code');
//        if (!$password) $this->setAjaxReturn('N','密码不可为空');
//        if (!$verifyCode) $this->setAjaxReturn('N','验证码不可为空');
//        if (!CommonUtil::isMobile($mobile)) $this->setAjaxReturn('N','请输入正确手机号');
//        $Customer = new Customer('frontForget');
//        $t = $Customer->findByAttributes(array('mobile'=>$mobile));
//        if (!$t) $this->setAjaxReturn('N','手机号码 "'.$mobile.'" 未申请加入');
//
//        // 判断审核状态
//        if($t['check_status'] == 0) $this->setAjaxReturn('N','您的申请正在审核中，请耐心等待');
//        if($t['check_status'] == -1) $this->setAjaxReturn('N','您的申请因"'.$t['check_stop_reason'].'"未通过，如有疑问，请拨打客服电话：400-8008-577');
//
//        $SmsCode = new SmsCode();
//        if (!$SmsCode->verifyCode('frontForget',$mobile,$verifyCode)) $this->setAjaxReturn('N','验证码错误');
//        $Customer->resetPassword($t['id'], $password);
//
//        $this->setAjaxReturn('Y','重设密码成功');
    }

    //发送验证码
    public function actionVerifyCode()
    {
        if(CommonUtil::get('type'))
        {
            $mobile = CommonUtil::get('mobile');
            $type   = CommonUtil::get('type');
            //voice or text
            $extend   = CommonUtil::get('extend','string','text');
        }
        else
        {
            $mobile = CommonUtil::post('mobile');
            $type   = CommonUtil::post('type');
            //voice or text
            $extend   = CommonUtil::post('extend','string','text');

            $code = strtolower(CommonUtil::post('code'));
            if(!$code) $this->setAjaxReturn('N','图片验证码不可为空');
            $img = CommonUtil::post('img');

            $key = PCustomer::CACHE_IMG_CODE.$img;
            if(!$cacheCode = G::fCache()->get($key)) $this->setAjaxReturn('N','图片验证码失效');
            G::fCache()->delete($key);
            if(strtolower($cacheCode) != $code) $this->setAjaxReturn('N','图片验证码错误');
        }

        if (!CommonUtil::isMobile($mobile)) $this->setAjaxReturn('N','请输入正确手机号');

        $typeMap = array(1=>'frontRegister',2=>'frontForget',3=>'frontBaidusem',4=>'frontForget');
        if (!isset($typeMap[$type])) $this->setAjaxReturn('N','type');
        $type = $typeMap[$type];

        //一分钟,同一号码同一场景,一分钟内只能发一次
        $SmsCode = new SmsCode();
        if (!$SmsCode->sendInMinute($type,$mobile,$extend)) $this->setAjaxReturn('N','请等一分钟后重新发送');

        $attrs = array(
            'type'=>$type,
            'code'=>StringUtil::getRandom(6,'NUMBER'),
            'mobile'=>$mobile,
            'extend'=>$extend,
        );
        $SmsCode->setAttributes($attrs,false);
        if (!$SmsCode->save())
        {
            if ($error = $SmsCode->getError('smsSendError'))
            {
                LogUtil::logActivity('error_app',array('rt'=>$error));
                $this->setAjaxReturn('N',$error);
            }
        }
        $this->setAjaxReturn('Y','验证码发送成功');
    }

    public function actionLogout()
    {
        $customer = new Customer();
        $customer->removeLoginTokenFromRedis(CommonUtil::post('token'));
        $this->setAjaxReturn('Y','退出成功');
    }

    /**
     * 用户留言
     */
    public function actionAdvise()
    {
        $customerID = $this->customerCache['id'];
        $content = htmlspecialchars(CommonUtil::post('content'));
        $code = strtolower(CommonUtil::post('code'));
        $img = CommonUtil::post('img');
        $key = PCustomer::CACHE_IMG_CODE.$img;
        if(!$cacheCode = G::fCache()->get($key)) $this->setAjaxReturn('N','图片验证码失效');
        G::fCache()->delete($key);
        if(strtolower($cacheCode) != $code) $this->setAjaxReturn('N','图片验证码错误');

        if(!$content || mb_strlen($content,'utf8')<10) $this->setAjaxReturn('N','反馈内容不得低于10个字');
        $advise = new Advise();
        $advise->setAttributes(array('user_id'=>$customerID,'content'=>$content,'dateline'=>time()));
        if($advise->save())
        {
            $this->setAjaxReturn('Y','谢谢您的建议，我们会不断改善我们的服务');
        }
        else
        {
            $this->setAjaxReturn('N','未知错误');
        }
    }

    /**
     * 常用联系人
     */
    public function actionContact()
    {
        $customerID = $this->customerCache['id'];
        $page = CommonUtil::post('page','int',1);
        $pagesize = CommonUtil::post('pagesize','int',10);
        $type = CommonUtil::post('type','int',1);
        $contact = new Contact();
        $rt = $contact->getContactList($customerID, $page,$pagesize,$type,$place);
        $list=array();
        if(!empty($rt['data']))
        {
            foreach($rt['data'] as $row)
            {
                $list[]=array(
                    'id'=>$row['id'],
                    'mobile'=>$row['mobile'],
                    'name'=>$row['name'],
                    'mobile2'=>$row['mobile2']
                );
            }
        }
        $this->setAjaxReturn('Y',array('data'=>array('total'=>$rt['total'],'list'=>$list)));
        $this->setAjaxReturn('N','未知错误');
    }

    /**
     * 常用地址
     */
    public function actionAddress()
    {
        $customerID = $this->customerCache['id'];
        $page = CommonUtil::post('page','int',1);
        $pagesize = CommonUtil::post('pagesize','int',10);
        $place = CommonUtil::post('place','string','');

        $address = new Contact();
        $rt = $address->getContactList($customerID, $page,$pagesize,4,$place);
        $list=array();
        if(!empty($rt['data']))
        {
            $r=new Region();
            foreach($rt['data'] as $row)
            {
                $list[]=array(
                    'id'=>$row['id'],
                    'place'=>$row['province'].($row['city']?(','.$row['city'].($row['district']?','.$row['district']:'')):''),
                    'place_text'=>$r->getPlace($row['province'],$row['city'],$row['district']),
                    'address'=>$row['address']
                );
            }
        }
        $this->setAjaxReturn('Y',array('data'=>array('total'=>$rt['total'],'list'=>$list)));
        $this->setAjaxReturn('N','未知错误');
    }

    /**
     * 删除常用信息
     */
    public function actionContactDelete()
    {
        $customerID = $this->customerCache['id'];
        $id = CommonUtil::post('id','int');
        if( !$id ) $this->setAjaxReturn('N','参数错误');
        $contact = Contact::model()->findByPk($id,'active=1');
        if( !$contact) $this->setAjaxReturn('Y','删除成功');
        if($contact->customer_id !=$customerID) $this->setAjaxReturn('N','无权限删除');
        $contact->active=0;
        if($contact->update(array('active')))$this->setAjaxReturn('Y','删除成功');
        $this->setAjaxReturn('N','删除失败');
    }

    /**
     * 我的代金券
     * @param page int 1
     * @param pagesize int 10
     * return array(data=>array(),status=Y/N total=>int>);
     **/
    public function actionMyCoupon()
    {
        $customerID = $this->customerCache['id'];
        $page=CommonUtil::post('page','int',1);
        $pagesize=CommonUtil::post('pagesize','int',10);

        $obj =new CouponDetail();
        $data=$obj->PCMyCoupon($customerID,$page,$pagesize);
        if($data)
        {
            $this->setAjaxReturn('Y',$data);
        }
        $this->setAjaxReturn('N','无代金券记录，请获取');
    }

    /*
     * 返回邀请码
     * */
    public function actionMyInvitation()
    {
        $customerID = $this->customerCache['id'];//customer_id =>　12008
        $invitationCode=new InvitationCode();
        $res=$invitationCode->find(array(
            'select'=>'code',
            'condition'=>'user_id='.$customerID,
        ));
        if($res)
        {
            $this->setAjaxReturn('Y',array('code'=>$res['code']));
        }
        else
        {
            $invitationCode->type=2;
            $invitationCode->user_id=$customerID;
            $invitationCode->save();
        }
        $ress=$invitationCode->find(array(
            'select'=>'code',
            'condition'=>'user_id='.$customerID,
        ));
        if($ress){$this->setAjaxReturn('Y',array('code'=>$ress['code']));}
        $this->setAjaxReturn('N','未发现邀请码');
    }

    /*
     * 返回用户邀请列表
     * */
    public function actionMyInvitationCustomer()
    {
        $customerID = $this->customerCache['id'];//customer_id =>　12008
        $page=CommonUtil::post('page','int',1);
        $pagesize=CommonUtil::post('pagesize','int',10);
        $obj =new CustomerInvitation();
        $data=$obj->PCMyInvitationCustomer($customerID,$page,$pagesize);
        if($data)
        {
            $this->setAjaxReturn('Y',$data);
        }
        $this->setAjaxReturn('N','无邀请记录');
    }
}