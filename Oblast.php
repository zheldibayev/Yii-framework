<?php

/**
 * This is the model class for table "ki_oblast".
 *
 * The followings are the available columns in table 'ki_oblast':
 * @property integer $id
 * @property string $lang
 * @property string $name
 * @property string $bounds
 */
class Oblast extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Oblast the static model class
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
		return 'ki_oblast';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, name, bounds', 'required'),
			array('id', 'numerical', 'integerOnly'=>true),
			array('lang', 'length', 'max'=>5),
			array('name', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, lang, name, bounds', 'safe', 'on'=>'search'),
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
			'lang' => 'Lang',
			'name' => 'Name',
			'bounds' => 'Bounds',
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
		$criteria->compare('lang',$this->lang,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('bounds',$this->bounds,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * DropDownList for oblasts by lang
	 */
	static function toDropDownList()
	{
		$oblasts = Yii::app()->db->createCommand()
						->select('id, name')
						->from('ki_oblast')
						->where('lang=:lang and id>1', array(':lang' => Yii::app()->params['lang']))
						->order('name ASC')
						->queryAll();
		
		//die(print_r($oblasts));
		$dropDownList = array();
		foreach ($oblasts as $oblast)
		{
			$dropDownList[$oblast['id']] = $oblast['name'];
		}
		
		return $dropDownList;
	}
}