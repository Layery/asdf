<?php 

class StudentController extends Controller
{

    public $defaultAction = 'List';

    public function actionList()
    {
        $criteria = new CDbCriteria;
        $criteria->select = '*';
        $criteria->order = 't.id desc';
        $criteria->with = 'room';
        $model = new CActiveDataProvider('Student',array(
            'criteria'=>$criteria,
            'pagination'=>array('pageSize'=>2), 
        ));
        $this->render('index',array('dataProvider'=>$model));
    }
}

    






