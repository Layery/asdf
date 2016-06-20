<?php 

class Student extends CActiveRecord{

    // 每个控制器都要有一个静态model方法. 
      public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    // 设置控制器操控的表
    public function tableName(){
        return '{{student}}';
    }

    // 设置表的主键
    public function primaryKey(){
        return 'id';
    }

    // 设置这个模型的属性列表
  public function attributeLabels()
    {
        return array(
            // 'id' => 'ID',
            // 'name' => 'Name',
            // 'sex' => 'Sex',
            // 'age' => 'Age',
            // 'c_id' => 'C',
        );
    }
  public function relations(){
     return array(
        'room'=>array(self::BELONGS_TO,'Room','c_id'),

    );
  }
}









