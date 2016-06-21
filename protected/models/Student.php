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
            'name' => 'Name',
            'sex' => 'Sex',
            'age' => 'Age',
            'c_id' => 'C',
        );
    }

    public function relations(){
        return array(
            'room' => array(self::BELONGS_TO,'Room','c_id'),
        );
  }
}









