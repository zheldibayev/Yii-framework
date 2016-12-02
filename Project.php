<?php

/**
 * This is the model class for table "ki_project".
 *
 * The followings are the available columns in table 'ki_project':
 * @property integer $id
 * @property string $oblast_id
 * @property string $type
 * @property string $company
 * @property string $name
 * @property string $contacts
 * @property string $place
 * @property double $price
 * @property integer $date_begin
 * @property string $product
 * @property string $product_qntt
 * @property double $product_price
 * @property double $lat
 * @property double $lng
 */
class Project  extends CActiveRecord
{
	const TYPE_MINERALS = "ipMinerals";
	const TYPE_CHEMISTRY = "ipChemistry";
	const TYPE_BUILDING = "ipBuilding";
	const TYPE_PHARMACEUTICS = "ipPharmaceutics";
	const TYPE_ELECTROENERGY = "ipElectroEnergy";
	const TYPE_LIGHTINDUSTRY = "ipLightIndustry";
	const TYPE_APK = "ipApk";
	const TYPE_AUTOCLUSTER= "ipAutoCluster";
	const TYPE_TRANSPORT = "ipTransport";
       const TYPE_PTICE = "ipPtice";
	
	
	
	
	
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Project the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ki_project';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('oblast_id, type, company, name, place, product', 'length', 'max'=>200),
			array('contacts', 'length', 'max'=>400)
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'oblast_id' => 'Район',
			'type' => 'Тип проекта',
			'company' => 'Организация',
			'name' => 'Название проекта',
			'contacts' => 'Контакты',
			'place' => 'Расположение',
			'price' => 'Стоимость проекта',
			'date_begin' => 'Год образования',
			'product' => 'Продукция',
			'product_qntt' => 'Количество продукции в год',
			'product_price' => 'Стоимость выпускаемой продукции',
			'lat' => 'Lat',
			'lng' => 'Lng',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('oblast_id',$this->oblast_id,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('contacts',$this->contacts,true);
		$criteria->compare('place',$this->place,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('date_begin',$this->date_begin);
		$criteria->compare('product',$this->product,true);
		$criteria->compare('product_qntt',$this->product_qntt,true);
		$criteria->compare('product_price',$this->product_price);
		$criteria->compare('lat',$this->lat);
		$criteria->compare('lng',$this->lng);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function count($type)
	{
		$qty = Yii::app()->db->createCommand()->select('COUNT(*) as count')->from($this->tableName())
					->where('type=:type', array(':type' => $type))->queryAll();
		return (int) $qty[0]['count'];
	}
	
	/*
	 * Get type attributes
	 */
	public function getTypeLabels()
	{
		return array(
			self::TYPE_APK => 'АПК',
                         self::TYPE_PTICE => 'Птицеводство',
			self::TYPE_AUTOCLUSTER => 'Машиностроение',
			self::TYPE_BUILDING => 'Строительство',
			self::TYPE_CHEMISTRY => 'Химическая отрасль',
			self::TYPE_ELECTROENERGY => 'Электроэнергетика',
			self::TYPE_LIGHTINDUSTRY => 'Легкая промышленность',
			self::TYPE_MINERALS => 'Минерально-сырьевой комплекс',
			self::TYPE_PHARMACEUTICS => 'Фармацевтика',
			self::TYPE_TRANSPORT => 'Транспортная инфраструктура'
		);
	}
	
	/*
	 * Search Projects in DB
	 */
	 public function getProjectsList($searchstring)
	 {
		$sql = "SELECT * FROM ki_project WHERE name like '%".$searchstring."%' or company like '%".$searchstring."%'";
		$query = Yii::app()->db->createCommand($sql)->queryAll();
		
		return $query;
	 }	

	 /*
	  * Get Projects List by Type
	  */
	 public function getProjectsListByType($PrijectType)
	 {
		if ($PrijectType == 'All') {
			$sql = "SELECT * FROM ki_project";
		} else {
			$sql = "SELECT * FROM ki_project WHERE type = '" . $PrijectType . "'";
		}
		$query = Yii::app()->db->createCommand($sql)->queryAll();
		
		return $query;
	 }

}