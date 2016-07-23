<?php

class SiteController extends Controller
{
	// 设置默认访问的action , 
    public $defaultAction = 'List';


	/**
	 * 展示某班级下的学生列表
	 */
	public function actionView()
	{
	    $id = (int)$_GET['id'];  
		$criteria = new CDbCriteria();
		$criteria->with = 'students';
		$criteria->addCondition("t.id=$id");  
		$students = new CActiveDataProvider('Room',array(
			'criteria'=>$criteria, 
		));
		p($students);
	    $this->render('view',array('students'=>$students));
	}

    /**
     * 展示班级列表
     */
	public function actionList()
	{
		// $student = Student::model()->findByPk(7);
		// $room = Room::model()->findByPk(1);
		// $getRelated = $room->getRelated('students');
		// $getRelated = $student->getRelated('room');



		// $getDbCriteria = $room->getDbCriteria();
		// $defaultScope = $room->defaultScope('Room');  
		// $attributeNames = $room->getMetaData();  // 返回数据库表字段, 

		// $getActiveRelation = $room->getActiveRelation('students'); 
		// $getActiveRelation = $student->getActiveRelation('room');  // 返回当前对象关联的relation信息.

		// $hasAttribute = $room->hasAttribute('HAHA'); // 判断当前对象是否具有某个属性.
		

		// $getAttribute = $room->getAttribute('uuu'); // 获取当前对象的某个属性的值,没有返回null;

	 	
	 // 	$getTableSchema = $room->getTableSchema(); // 获取数据库表信息.

	 // 	$getCommandBuilder = $room->getCommandBuilder();
        
	 // 	$room->setIsNewRecord = 1;
	 // 	// $insert = $room->insert(); // insert方法不会走验证器 , 

	 // 	// $saveAttributes = $room->saveAttributes(array('name'=>9));
	 // 	// $room->save();

	 // 	// $rs = $room->setAttribute('name',array('order'=>3,'area'=>30,'detail'=>'asdf'));


	 // 	$refresh = $room->refresh();   // 防止重复提交, 更新/重定向



	 // 	$with = $room->with(array('students'=>array('select'=>'id, name,age')))->findAll();
	   
	 // 	$_isnew = $room->_isnew;
         

	 // 	// $populateRecords = $room->populateRecords(array('1','哈哈'));

	 // 	$offsetExists = $room->offsetExists();


	 // 	p($populateRecords);



	 // 	p($with);




	 // 	p($refresh);



	 // 	p($room->getAttributes());
	 // 	p($saveAttributes);

	 // 	p($insert);




	 // 	p($getTableSchema);

        


		// p($getAttribute);


		// p($hasAttribute);


		// p($getActiveRelation);


		// p($attributeNames);



		// $room = Yii::app()->db->createCommand();
		// $room->select('id,name')
		// 	 ->from('{{student}}')
		// 	 ->order('id desc')
		// 	 ->queryAll();


		// p($room);





		$sql = 'select * from {{room}}';
		$conn = Yii::app()->db->createCommand($sql);
		$classList = $conn->queryAll($sql);
	    $this->render('index',array('classList'=>$classList));
	}

	/**
	 * 删除指定的班级条目
	 */
	public function actionDelete()
	{
		$id = (int)$_GET['id'];
		$room = Room::model()->findByPk($id);
		$room->delete();
		$this->redirect(array('list'));		
	}
    
    /**
     * 新增班级条目
     */
	public function actionCreate()
	{
		$model = new Room;
		if (isset($_POST['Room'])) 
		{
			$model->attributes = $_POST['Room'];
			if ($model->save()) {
			    $this->redirect(array('List'));
			}
		}
		$this->render('create',array('model'=>$model));
	}

    /**
     * 更新指定班级信息
     * @param  [type] $id 班级id
     * @return [type]     [description]
     */
    public function actionUpdate($id)
	{
		$rs = Room::model()->findByPk($id);
		if (empty($rs)) {
			exit('参数非法');
		}
		if ($_POST) {
			$criteria = new CDbCriteria();
			$criteria->addCondition("id = $id");
			$rs->attributes = $_POST;
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


	/**
	 * 登录
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if (isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if (isset($_POST['LoginForm']))
		{	
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if ($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}


    /**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if ($error=Yii::app()->errorHandler->error)
		{
			if (Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}


	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if (isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if ($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}
	

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}




	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}


}