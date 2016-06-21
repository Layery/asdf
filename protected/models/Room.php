<?php

class Room extends CActiveRecord{

    static public function model($className = __ClASS__ )
    {
        return parent::model($className);
    }
    
    public function tableName()
    {
        return '{{room}}';
    }

    public function primaryKey()
    {
        return 'id';
    }

    public function relations()
    {
        return array(
            'students'=>array(self::HAS_MANY,'Student','c_id'),
        );
    }

    public function rules()
    {
        return array(
            array('name','required'),
            array('name','length','min'=>2,'max'=>4,'tooShort'=>'名称太短','tooLong'=>'名称过长'),

        );
    }

    public function afterDelete()
    {
        $student = Student::model();
        p($student);
    }

}









