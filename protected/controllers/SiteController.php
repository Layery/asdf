<?php

class SiteController extends Controller
{
	/**
	 * 设置默认访问的action
	 * @var string
	 */
	public $defaultAction = 'List';

	

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
     * 展示该班级下的学生
     */
	public function actionView()
	{
		// $criteria = new CDbCriteria();
		// $criteria->select = 'name';
		// $model = Room::model()->with('students')->findByPk($id);
		$id = $_GET['id']+0;
		$criteria = new CDbCriteria();
		$criteria->with='students';
		$criteria->addCondition("t.id=$id");    // 默认是表别名是 t ? 
		$students = new CActiveDataProvider('Room',array(
			'criteria'=>$criteria,  //使用criteria条件
		));
	    $this->render('view',array('students'=>$students));
	}

 
	/**
	 * 展示班级列表
	 */
	public function actionList()
	{
		$sql = 'select * from {{room}}';
		$conn = Yii::app()->db->createCommand($sql);
		$classList = $conn->queryAll($sql);
	    $this->render('index',array('classList'=>$classList));
	}


	
	
	public function actionDelete()
	{
		$id = (int)$_GET['id'];
		$ls = Room::model()->deleteByPk($id);
		$this->redirect(array('List'));		
	}
    
  
    /**
     * 新增条目
     * @return [type] [description]
     */
	public function actionCreate()
	{
		$model = new Room;     // 只有新增数据的时候使用new Model的形式, 其余情况用Model()静态实例化一个类 . /why?????/  
		if (isset($_POST['Room']))
		{
			$model->attributes=$_POST['Room'];
			if ( $model->save())
				$this->redirect(array('List'));
		}

		$this->render('create',array(
			'model'=>$model,
		));

	}


 	public function beforeDelete(){

 	}

	public function actionUpdate($id)
	{
		$rs = Room::model()->findByPk($id);	    
		if ( empty($rs)) exit('参数非法');

		if ( $_POST){
			$criteria = new CDbCriteria();
			$criteria->addCondition("id=$id");
			$rs->attributes=$_POST;
			if ( !$rs->validate()){
				$errors = $rs->getErrors();
				foreach($errors as $v){
					echo $this->renderText($v[0]);
				}
				echo "<script>history.go(-1);</script>";
				exit;
			}
			$rs = $rs->save();
			$this->redirect(array('List'));
		}
		$this->render('item',array('item'=>$rs));
		
	}
	
	
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if ( isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if ( isset($_POST['LoginForm']))
		{	
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if ( $model->validate() && $model->login())
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
		if ( $error=Yii::app()->errorHandler->error)
		{
			if ( Yii::app()->request->isAjaxRequest)
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
		if ( isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if ( $model->validate())
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
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}






}