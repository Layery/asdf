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
        // return array(
        //     'id' => 'ID',
        //     'name' => 'Name',
        //     'sex' => 'Sex',
        //     'age' => 'Age',
        //     'c_id' => 'C',
        // );
    }

    public function relations(){
        return array(
            'room' => array(self::BELONGS_TO,'Room','c_id'),
        );
    }

    public function rules()
    {
        return array(
            array('name,sex,email', 'required','on'=>'create','message'=>'必填项不允许为空'),
            array('name,mobile,sex,email', 'required','on'=>'update','message'=>'必填项不允许为空'),
            array('name','MyValidator','min'=>2,'max'=>8,'tooShort'=>'姓名长度太短','tooLong'=>'姓名长度太长'),
            array('mobile','match','pattern' => '/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/','message' => '请输入正确的电话号码.'),
            array('email','email','message'=>'邮箱格式有误'),
            array('age','numerical','min'=>2,'max'=>200,'tooSmall'=>'年龄有误','tooBig'=>'年龄有误'),
            array('c_id,age,qq,mobile', 'safe'),
            // array('car_length,car_model', 'required','on'=>'create,update,OUpdate'),
        );
    }


    /**
     * 添加完学生后,修改该班级下的学生总数
     */
    public function afterSave()
    {   
        $room = Room::model();
        $c_id = $this->getAttributes()['c_id'];
        $sql = 'update {{room}} set s_number = s_number+1 where id = '.$c_id;
        Yii::app()->db->createCommand($sql)->query();
    }

    /**
     * 删除学生动作之后 ,更新班级表数据
     */
    public function afterDelete()
    {
        parent::afterDelete();
        $room = new Room;

        p($room);
    }

}









