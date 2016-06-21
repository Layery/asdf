<?php 

class StudentController extends Controller
{

    public $defaultAction = 'List';
    
    /**
     * 学生列表
     */
    public function actionList()
    {   
        $criteria = new CDbCriteria();
        $criteria->select = '*';
        $criteria->order = 't.id desc';
        $criteria->with = 'room';
        $model = Student::model()->findAll($criteria);
        foreach ($model as $v) {
            $students[] = $v->getAttributes();
        }
        $this->render('index',array('students'=>$model));


        // $criteria = new CDbCriteria;
        // $criteria->select = '*';
        // $criteria->order = 't.id desc';
        // $criteria->with = 'room';
        // $model = new CActiveDataProvider('Student',array(
        //     'criteria'=>$criteria,
        //     'pagination'=>array('pageSize'=>10), 
        // ));
        // $this->render('index',array('dataProvider'=>$model));
    }

    /**
     * 新增学生
     */
    public function actionCreate()
    {
        $model = new Student('create');
        if ($_POST) {
            if (!$_POST['Student']['c_id']) {
                exit('请选择班级');
            }
            $model->attributes = $_POST['Student'];
            if(!$model->validate()) {
                $errors = $model->getErrors();
                print_r($errors);
                foreach ($errors as $k => $v) {
                    echo $k.$v[0];
                }
                die;
            }
            if ($model->save()) {
                $this->redirect(array('List'));
            }
        }
        $sql = 'select id,name from {{room}}';
        $room = Room::model()->findAllBySql($sql);
        if (!empty($room)) {
            foreach ($room as $v) {
                $class[] = $v->attributes;
            }
        } else {
            $class = array();
        }

        $this->render('create',array('model'=>$model,'room'=>$class));
    }

    /**
     * 更新学生信息
     * @param  [type] $id 学生id
     * @return [type]     [description]
     */
    public function actionUpdate($id)
    {
        $id = (int)$_GET['id'];
        $rs = Student::model()->findByPk($id);
        if (empty($rs)) {
            echo "<script>alert('参数有误');</script>";
            echo "<script>history.go(-1);</script>";
            exit;
        }
        
        if ($_POST) {
            $rs->scenario = 'update';  // 设置场景为update
            $rs->attributes = $_POST['Student'];
            if (!$rs->validate()) {
                $errors = $rs->getErrors();
                foreach ($errors as $v) {
                    echo "<script>alert('".$v[0]."');</script>";
                }
                echo "<script>history.go(-1);</script>";
                exit;
            }
            $rs = $rs->save();
            $this->redirect(array('List'));
        }
        $this->render('item',array('item'=>$rs));
    }

    public function actionDelete($id)
    {
        $id = (int)$_GET['id'];
        $ls = Student::model()->deleteByPk($id);
        $this->redirect(array('List'));     
    }
}

    






