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
            array('name','length','min'=>2,'max'=>4,'tooShort'=>'名称最短2个字符','tooLong'=>'名称最长4个字符'),

        );
    }

    public function afterDelete()
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition("c_id = $this->id");
        $student = Student::model()->deleteAll($criteria);
     }

}









