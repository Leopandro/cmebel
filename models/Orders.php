<?php

namespace app\models;

use Yii;
use \yii\helpers\ArrayHelper;

/**
 * This is the model class for table "orders".
 *
 * @property string $id
 * @property string $order_opencart_id
 * @property integer $version
 * @property integer $last_version
 * @property string $client_id
 * @property integer $status_id
 * @property string $total
 * @property string $comment
 * @property string $payment_method
 * @property string $payment_address_1
 * @property string $payment_address_2
 * @property string $payment_city
 * @property string $payment_postcode
 * @property string $payment_country
 * @property string $payment_zone
 * @property string $date_added
 * @property string $date_modified
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	private $_products;

    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_opencart_id', 'client_id', 'total', 'date_added', 'date_modified', 'version_name', 'version_date'], 'required'],
            [['order_opencart_id', 'last_version', 'version', 'client_id', 'status_id'], 'integer'],
            [['total'], 'number'],
	        ['total', 'default', 'value' => 0],
            [['date_added', 'date_modified','products','files'], 'safe'],
	        [['date_added', 'date_modified'], 'default', 'value' => date('Y-m-d', strtotime( 'now' ))],
	        [['order_opencart_id', 'version'], 'unique', 'targetAttribute' => ['order_opencart_id', 'version'], 'message' => 'Номер заказа из opencart в этой версии уже существет и не может быть дублирован.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'номер',
            'order_opencart_id' => 'номер заказа',
            'comment' => 'комментарий',
            'version' => 'версия',
			'version_name' => 'имя версии',
            'client_id' => 'клиент',
            'status_id' => 'статус',
            'total' => 'итого',
            'date_added' => 'дата добавления',
            'date_modified' => 'дата изменения',
        ];
    }

	public function beforeSave($insert)
	{

		$date = Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s');
		$this->date_modified = $date;
		$total = 0;

		if ($insert) {
			Orders::updateAll(['last_version' => 0], 'order_opencart_id=:order_opencart_id',
				['order_opencart_id'=>$this->order_opencart_id]);
			$this->last_version = 1;
		}
		else {
			$query = "
		        SELECT sp.price, op.quantity
		        FROM order_product op
		        LEFT JOIN shop_products sp ON sp.id=op.product_id
		        WHERE op.order_id = ".$this->id."
		        "; //echo $query;

			$products = \Yii::$app->db->createCommand($query)
				->queryAll();

			foreach ($products as $k => $product) {
				$total += $product['price']*$product['quantity'];
			}
		}

		$this->total = $total;

		return parent::beforeSave($insert);
	}

	public function getVersions()
	{
		$versions = Orders::find()->select('version,version_name,version_date')->where('order_opencart_id=:order_opencart_id',[':order_opencart_id'=>$this->order_opencart_id])->orderBy('version')->asArray()->all();
		foreach ($versions as $k => $version) {
			if((strtotime($version['version_date']) == false) OR(strtotime($version['version_date']) < 0))
				$version['version_date'] = '';
			else
				$version['version_date'] = date('d-m-Y', strtotime($version['version_date']));
			$return[$version['version']] = $version['version'].' '.$version['version_name'].' '.$version['version_date'];
		}
		return $return;
	}
	public function getVersionsId()
	{
		$versions = Orders::find()->select('id,version')->where('order_opencart_id=:order_opencart_id',[':order_opencart_id'=>$this->order_opencart_id])->orderBy('version')->asArray()->all();
		foreach ($versions as $k => $version) {
			$return[$version['id']] = $version['version'];
		}
		return $return;
	}

	public function updateVersion() {
		$versionLastModel = Orders::find()->where('order_opencart_id=:order_opencart_id',
			[':order_opencart_id'=>$this->order_opencart_id])
			->orderBy('version DESC')->limit(1)->one();
		$this->last_version =  ($versionLastModel->version==$this->version) ? 1 : 0;
		$this->updateAttributes(['last_version']);
	}

	public function getClient()
	{
		return $this->hasOne(Clients::className(), ['id' => 'client_id']);
	}

	public function getStatus()
	{
		return $this->hasOne(OrderStatus::className(), ['id' => 'status_id']);
	}

	public function getOrderProducts()
	{
		return $this->hasMany(OrderProduct::className(), ['order_id' => 'id']);
	}

	public function getProducts()
	{
		return $this->hasMany(ShopProducts::className(), ['id' => 'product_id'])
			->via('orderProducts');
	}

	public function setProducts($_products)
	{
		$oldProducts = OrderProduct::find()->where('order_id=:order_id',[':order_id'=>$this->id])->all();
		foreach ($oldProducts as $k => $oldProduct) {
			$delete = true;
			foreach ($_products as $id => $v) {
				if ($oldProduct->id==$id) $delete = false;
			}
			if ($delete) {
				OrderProduct::deleteAll('id=:id',[':id'=>$oldProduct->id]);
			}
		}

		$saved = true;
		foreach ($_products as $id => $v) {
			if (!$saved) return $saved;
			if ($id=='new' OR $_POST['new_order']==1) {
				$orderProduct = new OrderProduct();
				foreach ($v as $productId => $v1) {
					$orderProduct->order_id = $this->id;
					$orderProduct->product_id = $productId;
					$orderProduct->quantity = $v1['quantity'];
				}
			} else {
				$orderProduct = OrderProduct::findOne($id);
				foreach ($v as $productId => $v1) {
					$orderProduct->quantity = $v1['quantity'];
				}
			}
			$saved = $orderProduct->save();
		}
		return $saved;
	}

	public function getFiles()
	{
		return $this->hasMany(OrderFiles::className(), ['order_id' => 'id']);
	}

	public function setFiles($_files)
	{
		unset($_files['empty']);
		if ($_POST['new_order']==1) {
			$pathId = $_files['pathId'];
			unset($_files['pathId']);
			$oldPath = Yii::getAlias('@webroot/files/orders').'/'.$pathId.'/';
			$tempPath = Yii::getAlias('@app/runtime').'/order_files/'.$pathId.'/';
			$newPath = Yii::getAlias('@webroot/files/orders').'/'.$this->id.'/';
			if (!is_dir($newPath)) mkdir($newPath, 0777, true);
			foreach ($_files as $file) {
				//создать записи в бд в таблице order_files
				$orderFile = new OrderFiles();
				$orderFile->order_id = $this->id;
				$orderFile->name = $file;
				$orderFile->file = $file;
				$orderFile->save();
				//скопировать файлы в новую папку
				if (is_file($oldPath.$file)) copy($oldPath.$file,$newPath.$file);
				if (is_file($tempPath.$file)) rename($tempPath.$file,$newPath.$file);
			}

			return true;
		}

		$pathTemp = Yii::getAlias('@app/runtime').'/order_files/'.$this->id.'/';
		$path = Yii::getAlias('@webroot/files/orders').'/'.$this->id.'/';

		if (!is_dir($path)) mkdir($path);

		$oldFiles = OrderFiles::find()->where('order_id=:order_id',[':order_id'=>$this->id])->all();
		foreach ($oldFiles as $k => $oldFile) {
			$delete = true;
			foreach ($_files as $id => $v) {
				if ($oldFile->file==$v AND (!is_file($pathTemp.$v))) $delete = false;
			}
			if ($delete) {
				OrderFiles::deleteAll('id=:id',[':id'=>$oldFile->id]);
				if (is_file($path.$oldFile->file)) unlink($path.$oldFile->file);
			}
		}
		
		$saved = true;
		foreach ($_files as $file) {
			if (!$saved) return $saved;
			if (!is_file($pathTemp.$file)) continue;
			$orderFile = new OrderFiles();
			$orderFile->order_id = $this->id;
			$orderFile->name = $file;
			$orderFile->file = $file;
			if ($orderFile->save()) {
				if (!rename($pathTemp.$file, $path.$file)) $saved = false;
			} else {
				$saved = false;
			}
			if (is_file($pathTemp.$file)) unlink($pathTemp.$file);
		}
		return $saved;
	}

	//преобразование кирилицы в транслит
	static public function trunslit($str){
		$str = Orders::strtolower_utf8(trim(strip_tags($str)));
		$str = str_replace(
			array('ä','ö','ü','а','б','в','г','д','е','ё','ж', 'з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц', 'ч',  'ш',   'щ','ь','ы','ъ','э','ю','я','і','ї','є'),
			array('a','o','u','a','b','v','g','d','e','e','zh','z','i','i','k','l','m','n','o','p','r','s','t','u','f','h','ts','ch','sch','shch','','y','','e','yu','ya','i','yi','e'),
			$str);
		$str = preg_replace('~[^-a-z0-9_.]+~u', '_', $str);	//удаление лишних символов
		$str = preg_replace('~[-]+~u','-',$str);			//удаление лишних -
		$str = trim($str,'-');								//обрезка по краям -
		$str = trim($str,'_');								//обрезка по краям -
		$str = trim($str,'.');
		return $str;
	}

	//зaмена функции strtolower
	static public function strtolower_utf8($str){
		$large = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','Є');
		$small = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ð','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я','є');
		return str_replace($large,$small,$str);
	}


    static public function getFullInfoOrderItemsForDropDownList() {

        $result = array();

        $orders = Orders::find()->with('client','status')->orderBy('order_opencart_id')->all();

        foreach($orders as $cur_order)
            $result[$cur_order->order_opencart_id] = implode(array("Заказ №".$cur_order->order_opencart_id,"Клиент:".$cur_order->client->name,"Статус:".$cur_order->status->name,"Дата создания:".date("d.m.Y H:i",strtotime($cur_order->date_added)),"Сумма:".$cur_order->total),", ");

        return $result;

    }

}
