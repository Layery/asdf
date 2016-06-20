<?php 

class StudentController extends Controller{

    public function actionIndex()
    {
        $criteria = new CDbCriteria;
        $criteria->select='*';
        $criteria->order = 'id desc';
        $model=new CActiveDataProvider('Student',array(
            'criteria'=>$criteria,
            'pagination'=>array('pageSize'=>2),
        ));



        // $rs = $model->getData();  // 获取查询数据
        $this->render('index',array('dataProvider'=>$model));
    }

    

}






