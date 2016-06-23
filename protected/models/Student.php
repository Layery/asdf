<?php 

class Student extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{student}}';
    }

    public function primaryKey()
    {
        return 'id';
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => '姓名',
            'sex' => '性别',
            'age' => '年龄',
            'c_id' => '班级',
            'email' => '邮箱',
            'mobile' => '手机',
            'qq' => 'QQ'
        );
    }

    public function relations(){
        return array(
            'room' => array(self::BELONGS_TO,'Room','c_id'),
        );
    }

    public function rules()
    {
        return array(
            array('name,sex,c_id', 'required','on'=>'create','message'=>'必填项不允许为空'),
            array('c_id','boolean','on'=>'update','message'=>'请选择班级'),
            array('name,mobile,email', 'required','on'=>'update','message'=>'必填项不允许为空'),
            array('name','MyValidator','min'=>2,'max'=>8,'tooShort'=>'姓名长度太短','tooLong'=>'姓名长度太长'),
            array('mobile','match','pattern' => '/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/','message' => '请输入正确的电话号码.'),
            array('email','email','message'=>'邮箱格式有误'),
            array('qq', 'match','pattern' => '/^[1-9]{1}[0-9]{4,11}$/','message' => '请输入正确的QQ号码.'),
            array('age','numerical','min'=>2,'max'=>200,'tooSmall'=>'年龄有误','tooBig'=>'年龄有误','message'=>'年龄有误'),
            array('c_id,age,qq,mobile', 'safe'),
            // array('car_length,car_model', 'required','on'=>'create,update,OUpdate'),
        );
    }


    /**
     * 添加完学生后,修改该班级下的学生总数
     */
    public function afterSave()
    {   
        if ($this->getIsNewRecord()) { //该方法能判断save是由新增传来,还是更新传来
            $room = Room::model()->findByPk($this->c_id);
            $room->s_number += 1;
            $room->save();
        }
    }

    /**
     * 删除学生动作之前 ,更新班级表数据
     */
    public function beforeDelete()
    {
        $room = Room::model()->findByPk($this->c_id);
        $room->s_number -= 1;
        if ($room->save()) {
            return true;      // 执行成功,一定要return true .
        } else {
            return false;
        }
    }
}









