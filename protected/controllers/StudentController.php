<?php 

class StudentController extends Controller
{

    public $defaultAction = 'List';
    
    /**
     * 学生列表
     */
    public function actionList()
    {   
        $criteria = new CDbCriteria;
        $params = [];        
        if (!empty($_POST['studentName'])) { //拼接查询条件
            $criteria->addCondition("t.name = :studentName");
            $params[':studentName'] = $_POST['studentName'];
        } 
        if (!empty($_POST['roomName'])) {
            $criteria->addCondition("room.name = :roomName");
            $params[':roomName'] = $_POST['roomName'];
        }

        $criteria->params = $params;
        $criteria->order = 't.id desc';
        $criteria->with = 'room';

        $model = Student::model()->findAll($criteria);
        foreach ($model as $v) {
            $students[] = $v->getAttributes();
        }

        $this->render('index',array('students'=>$model));
    }

    /**
     * 新增学生
     */
    public function actionCreate()
    {
        $sql = 'select id,name from {{room}}';
        $room = Room::model()->findAllBySql($sql);
        if (!empty($room)) {
            foreach ($room as $v) {
                $class[] = $v->attributes;
            }
        } else {
            $class = array();
        }

        $model = new Student('create');
        if ($_POST) {  
            $model->attributes = $_POST['Student'];
            if($model->validate()) {
               $model->save();
               $this->redirect(array('list'));
            } 
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
        $student = Student::model()->findByPk($id);
        if (empty($student)) {
            echo "<script>alert('参数有误');</script>";
            echo "<script>history.go(-1);</script>";
            exit;
        }
        if ($_POST) {
            $student->scenario = 'update';  // 设置场景为update
            $student->attributes = $_POST['Student'];
            if (!$student->validate()) {
                $errors = $student->getErrors();
                foreach ($errors as $v) {
                    echo "<script>alert('".$v[0]."');</script>";
                }
                echo "<script>history.go(-1);</script>";
                exit;
            }
            $student = $student->save();
           $this->redirect(array('list'));
        }
        $this->render('item',array('item'=>$student));
    }

    /**
     * 删除学生
     * @param  [type] $id 学生id
     */
    public function actionDelete($id)
    {
        $id = (int)$_GET['id'];
        $student = Student::model()->findByPk($id);
        $rs = $student->delete();
        if (!$rs) {
            $error = $student->getErrors();
            echo '<script>alert("'.$error['room'][0].'");</script>';
            echo '<script>history.go(-1);</script>';
            exit;
        }

        $this->redirect(array('list'));     
    }



}

    






