<?php

class Room extends CActiveRecord{

    static public function model($className = __ClASS__ ){
        return parent::model($className);
    }
    
    public function tableName(){
        return '{{room}}';
    }

    public function primaryKey(){
        return 'id';
    }

    public function relations()
    {
        return array(
            'students'=>array(self::HAS_MANY,'Student','c_id'),
        );
    }
    public function rules(){
        return array(
            array('name','required'),

        );
    }


}









