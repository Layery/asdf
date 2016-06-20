<?php 

class StudentController extends Controller{

    public function actionList()
    {
        $criteria = new CDbCriteria;
        $criteria->select='*';
        $criteria->order = 't.id desc';
        $criteria->with= 'room';
        // $criteria->addCondition("room.name ='四班'");   // 关联查询每个学生属于哪个班级
        $model=new CActiveDataProvider('Student',array(
            'criteria'=>$criteria,
            'pagination'=>array('pageSize'=>2),
        ));


        // $rs = $model->getData();  // 获取查询数据
        $this->render('index',array('dataProvider'=>$model));
    }

    

}






