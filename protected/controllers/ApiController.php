
<?php

class ApiController extends Controller
{

    /**
     * @var string
     */
    public $layout = 'column2';

    /**
     * @return array
     */
    public function accessRules()
    {
        return array(
            array(
                'allow',
                'users' => array('*'),
            ),
        );
    }

    /**
     * @return void
     */
    public function actionList(){

		if (!$this->login()){
			return;
		}

		$data = array();
		foreach (Entry::model()->findAllByAttributes(array('userId' => Yii::app()->user->id)) as $model) {

				$data[] = array(
					'id' => $model->id,
					'name' => $model->name,
					'url' => $model->url,
					'comment' => $model->comment,
					'tags' => $model->tagList,
					'username' => $model->username,
					'password' => $model->password,
				);
			}

		echo json_encode($data);
    }
	
	public function actionAdd(){
	
		if (!$this->login()){
			return;
		}

		$model = new Entry('create');
		
		if (!isset($_POST["name"]) || !isset($_POST["username"]) || !isset($_POST["password"]) || !isset($_POST["url"])){
			$data[] = array("error_code" => "100", "error" => "name, username, password and url are mandatory");
			echo json_encode($data);
			return;
		}else{
			$data["name"] = $_POST["name"];
			$data["username"] = $_POST["username"];
			$data["password"] = $_POST["password"];
			$data["url"] = $_POST["url"];
		}
		
		if(isset($_POST["tagList"])){
			$data["tagList"] = $_POST["tagList"];
		}

		if(isset($_POST["comment"])){
			$data["comment"] = $_POST["comment"];
		}
	
        $model->attributes = $data;
		
        if ($model->save()) {
            $model->resaveTags();
		}
		
		$model = Entry::model()->findbyPk($model->id);
		if ($model === null) {
            $ret[] = array("error_code" => "105", "error" => "entry not saved");
			echo json_encode($ret);
			return;
        } elseif ($model->userId != Yii::app()->user->id) {
            $ret[] = array("error_code" => "105", "error" => "entry not saved");
			echo json_encode($ret);
			return;
        } else{
			$ret[] = array("status_code" => "0", "message" => "entry saved");
			echo json_encode($ret);
		}
				
    }
	
	public function actionDelete(){
		
		if (!$this->login()){
			return;
		}
		
		$id = 0;
		if(isset($_POST["id"]) && is_numeric($_POST["id"])){
			$id = $_POST["id"];
		}else{
			$ret[] = array("error_code" => "200", "error" => "id not provided or wrong format");
			echo json_encode($ret);
			return;
		}
		
		$model = Entry::model()->findbyPk($id);

        if ($model === null) {
            $ret[] = array("error_code" => "200", "error" => "entry not found");
			echo json_encode($ret);
			return;
        } elseif ($model->userId != Yii::app()->user->id) {
            $ret[] = array("error_code" => "205", "error" => "insufficient permissions");
			echo json_encode($ret);
			return;
        }
		
		$model->delete();
		$ret[] = array("success" => "0", "message" => "entry deleted");
		echo json_encode($ret);
    }
	
	public function login(){
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST'){
			if (!isset($_POST["login_username"]) || !isset($_POST["login_password"])){
				$data[] = array("error_code" => "105", "error" => "wrong parameters");
				echo json_encode($data);
				return false;
			}else{
				$webUser = Yii::app()->user;
				$identity = new UserIdentity($_POST["login_username"], $_POST["login_password"]);
				$identity->authenticate();

				switch($identity->errorCode){
					case UserIdentity::ERROR_NONE:
						$webUser->login($identity);
						break;
					case UserIdentity::ERROR_USERNAME_INVALID:
						$data[] = array("error_code" => "101", "error" => "wrong username");
						echo json_encode($data);
						return false;
					case UserIdentity::ERROR_PASSWORD_INVALID:
						$data[] = array("error_code" => "102", "error" => "wrong password");
						echo json_encode($data);
						return false;
					default:
						$data[] = array("error_code" => "100", "error" => "wrong username or password");
						echo json_encode($data);
						return false;
					}

			}
		}else{
			$data[] = array("error_code" => "110", "error" => "wrong method please use POST");
			echo json_encode($data);
			return false;
		}
		
		return true;
	}

    /**
     * @return array
     */
    public function filters()
    {
        return array_merge(array(
            'accessControl',
        ), parent::filters());
    }
}
