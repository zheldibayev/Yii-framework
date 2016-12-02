<?php

/**
 * This is the model class for table "ki_product".
 *
 * The followings are the available columns in table 'ki_product':
 * @property integer $id
 * @property string $type
 * @property double $lat
 * @property double $lng
 * @property string $organization
 * @property string $activity
 * @property string $production
 * @property string $oblast
 * @property string $city
 * @property string $region
 * @property string $village
 * @property string $street
 * @property string $phone_code
 * @property string $phone_number
 * @property string $website
 * @property string $email
 * @property string $branches
 */
class Product extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Product the static model class
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
		return 'ki_product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lat, lng, organization, activity, production, oblast, city, region, village, street, phone_code, phone_number, website, email, branches', 'required'),
			array('lat, lng', 'numerical'),
			array('type', 'length', 'max'=>48),
			array('organization', 'length', 'max'=>400),
			array('oblast, city, region, village, street, website, email', 'length', 'max'=>200),
			array('phone_code, phone_number', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, lat, lng, organization, activity, production, oblast, city, region, village, street, phone_code, phone_number, website, email, branches', 'safe', 'on'=>'search'),
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
			'type' => 'Type',
			'lat' => 'Lat',
			'lng' => 'Lng',
			'organization' => 'Organization',
			'activity' => 'Activity',
			'production' => 'Production',
			'oblast' => 'Oblast',
			'city' => 'City',
			'region' => 'Region',
			'village' => 'Village',
			'street' => 'Street',
			'phone_code' => 'Phone Code',
			'phone_number' => 'Phone Number',
			'website' => 'Website',
			'email' => 'Email',
			'branches' => 'Branches',
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('lat',$this->lat);
		$criteria->compare('lng',$this->lng);
		$criteria->compare('organization',$this->organization,true);
		$criteria->compare('activity',$this->activity,true);
		$criteria->compare('production',$this->production,true);
		$criteria->compare('oblast',$this->oblast,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('region',$this->region,true);
		$criteria->compare('village',$this->village,true);
		$criteria->compare('street',$this->street,true);
		$criteria->compare('phone_code',$this->phone_code,true);
		$criteria->compare('phone_number',$this->phone_number,true);
		$criteria->compare('website',$this->website,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('branches',$this->branches,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	 /*
	  * Get Products List by Type
	  */
	 public function getProductsListByType($ProductType)
	 {
		if ($ProductType == 'All') {
			$sql = "SELECT * FROM ki_product";
		} else {
			$sql = "SELECT * FROM ki_product WHERE type = '" . $ProductType . "'";
		}
		$query = Yii::app()->db->createCommand($sql)->queryAll();
		
		return $query;
	 }
	 
	/*
	 * Get type attributes
	 */
	public function getTypeLabels()
	{
		return array(
			"coal" => 'Уголь',
			"gold" => 'Золото',
			"mineralwater" => 'Минеральные воды',
			"oil" => 'Нефть',
			"phosphorite" => 'Фосфориты',
			"uranium" => 'Уран',
			"vannadium" => 'Ваннадий',
		);
	}


}