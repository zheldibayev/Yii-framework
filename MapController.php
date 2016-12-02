<?php

class MapController extends Controller
{
	
	
	public $pageTitle = "Investment map";
	public $layout = "map";
	
	public $filterView = "_projectsFilter";
	public $counts = array();

	public $map_id;
	/**
	 * Index page
	 */
	public function actionIndex()
	{
		$map = new Map;
		$map->drawSkoBounds();
		$map->drawOblastsBounds();
		
	   /*    $projects = Yii::app()->db->createCommand()->select('*')->from('ki_project')->queryAll();
		foreach ($projects as $project)
		{
		$map->displayProject($project);
		}
            */

             
        

   
		$map_id = $map->getId();
		$map->renderClient();
				
		$this->render('index', array(
			'mapId' => $map->getId(),
			'map' => $map,
			'mapType' => "project"
		));
	}
	
	/**
	 * Products page
	 */
	 public function actionProducts()
	 {
	 	$map = new Map;
		$map->drawSkoBounds();
		$map->drawOblastsBounds();
		
		/*$products = Yii::app()->db->createCommand()->select('*')->from('ki_product')->queryAll();
		foreach ($products as $product)
		{
			$map->displayProduct($product);
		}*/
		
		$map->renderClient();
		$this->filterView = "_productsFilter";
		$this->render('index', array(
			'mapId' => $map->getId(),
			'map' => $map,			
			'mapType' => "product"
		));
	 }
	 
	 /**
	 * Resources page
	 */
	public function actionResources()
	{
	 	$map = new Map;
		$map->drawSkoBounds();
		$map->drawOblastsBounds();
		
		/*$resources = Yii::app()->db->createCommand()->select('*')->from('ki_resource')->queryAll();
		foreach ($resources as $resource)
		{
			$map->displayResource($resource);
		}*/
		
		$map->renderClient();
		//die ($map->getMarkersJs());
		
		$this->filterView = "_resourcesFilter";
		$this->render('index', array(
			'mapId' => $map->getId(),
			'map' => $map,
			'mapType' => "resource"
		));
	}
	 
	public function actionDatabase()
	{
		return;
		
	 	Yii::app()->db->update('ki_oblast');
		
		echo 'OK';
	}
	 
	 /**
	  * Admin panel
	  */
	public function actionAdmin($type = "project")
	{
	    if(Yii::app()->user->isGuest) {
		$this->redirect(array('site/login'));
		 } else {
		$types = array("project", "resource", "product", "product_region");
		if (!in_array($type, $types)) $type = "project";
		
		$map = new Map;
		$map->drawSkoBounds();
		$map->drawOblastsBounds();
		$map->renderAdmin();
		
		if ($type == "project")
		{
			$model = new Project;
						
			$oblastsDB = Yii::app()->db->createCommand()->select('id, name')->from('ki_oblast')
									->where('id > 1')->order('name ASC')->queryAll();
			$projects = Yii::app()->db->createCommand()->select('id, oblast_id, company, name')->from('ki_project')
									->where('lang=:lang', array(':lang' => 'ru_ru'))->queryAll();
			die(var_dump($projects));
			
			$oblasts = array();
			foreach ($oblastsDB  as $oblast)
			{
				$oblasts[$oblast['id']] = array(
					'id' => $oblast['id'],
					'name' => $oblast['name'],
					'projects' => array()
				);
				
				foreach ($projects as $project)
				{
					if ($oblast['id'] == $project['oblast_id'])
					{
						$oblasts[$oblast['id']]['projects'][] = $project;
					}
				}
			}
			
			var_dump($oblasts);
			
		 	$this->layout = "admin";
		 	$this->render('admin', array(
				'oblasts' => $oblasts,
				'model' => $model,
				'map' => $map
			));
		}
		else if ($type == "resource")
		{
			$model = new Resource;
						
			$oblastsDB = Yii::app()->db->createCommand()->select('id, name')->from('ki_oblast')
									->where('id > 1')->order('name ASC')->queryAll();
			$resources = Yii::app()->db->createCommand()->select('id, oblast_id, type, name')->from('ki_resource')
									->where('lang=:lang', array(':lang' => Yii::app()->params['lang']))->queryAll();
			
			$oblasts = array();
			foreach ($oblastsDB as $oblast)
			{
				$oblasts[$oblast['id']] = array(
					'id' => $oblast['id'],
					'name' => $oblast['name'],
					'resources' => array()
				);
				
				foreach ($resources as $resource)
				{
					if ($oblast['id'] == $resource['oblast_id'])
					{
						$oblasts[$oblast['id']]['resources'][] = $resource;
					}
				}
			}
			
		 	$this->layout = "admin";
		 	$this->render('admin_resources', array(
				'oblasts' => $oblasts,
				'model' => $model,
				'map' => $map
			));
		}
		else if ($type == "product")
		{
			$model = new Product;
						
			$oblastsDB = Yii::app()->db->createCommand()->select('id, name')->from('ki_oblast')
									->where('id > 1')->order('name ASC')->queryAll();
			$products = Yii::app()->db->createCommand()->select('id, oblast_id, type')->from('ki_product')
									->where('lang=:lang', array(':lang' => Yii::app()->params['lang']))->queryAll();
			
			$oblasts = array();
			foreach ($oblastsDB as $oblast)
			{
				$oblasts[$oblast['id']] = array(
					'id' => $oblast['id'],
					'name' => $oblast['name'],
					'products' => array()
				);
				
				foreach ($products as $product)
				{
					if ($oblast['id'] == $product['oblast_id'])
					{
						$oblasts[$oblast['id']]['products'][] = $product;
					}
				}
			}
			
		 	$this->layout = "admin";
		 	$this->render('admin_products', array(
				'oblasts' => $oblasts,
				'model' => $model,
				'map' => $map
			));
		}
	}
	}
	 /**
	  * Ajax : get projects
	  */
	public function actionGetProjects()
	{
	 	$projects = Yii::app()->db->createCommand()->select('*')->from('ki_project')
								->where('lang=:lang AND oblast_id=:oblast_id', array(':lang' => 'ru_ru', ':oblast_id' => $_POST['oblastId']))
								->queryAll();
		
		echo json_encode($projects);
	}	
	
	/**
	 * Ajax : get project
	 */
	public function actionGetProject()
	{
		$project = Yii::app()->db->createCommand()->select('*')->from('ki_project')
								->where('lang=:lang AND id=:id', array(':lang' => 'ru_ru', ':id' => $_POST['projectId']))
								->queryRow();
		
		//die(print_r($projects));						
		
		echo json_encode($project);
	}
		
	/**
	 * Ajax : save project
	 */
	public function actionSaveProject()
	{
		//die(var_dump($_POST['Project']));
		$model=new Project;
		if (isset($_POST['Project']))
		{
			$_POST['Project']['oblast_id'] = intval($_POST['Project']['oblast_id']);
			$_POST['Project']['date_begin'] = intval($_POST['Project']['date_begin']);
			$_POST['Project']['price'] = floatval($_POST['Project']['price']);
			$_POST['Project']['product_price'] = floatval($_POST['Project']['product_price']);
			$_POST['Project']['lat'] = doubleval($_POST['Project']['lat']);
			$_POST['Project']['lng'] = doubleval($_POST['Project']['lng']);
			
			$model->attributes=$_POST['Project'];
			if ($model->validate())
			{
				if ($_POST['method'] == "save")
				{
					Yii::app()->db->createCommand()->insert('ki_project', array(
					    'oblast_id' => $_POST['Project']['oblast_id'],
					    'type' => $_POST['Project']['type'],
					    'company' => $_POST['Project']['company'],
					    'name' => $_POST['Project']['name'],
					    'contacts' => $_POST['Project']['contacts'],
					    'place' => $_POST['Project']['place'],
					    'price' => $_POST['Project']['price'],
					    'date_begin' => $_POST['Project']['date_begin'],
					    'product' => $_POST['Project']['product'],
					    'product_qntt' => $_POST['Project']['product_qntt'],
					    'product_price' => $_POST['Project']['product_price'],
					    'lat' => $_POST['Project']['lat'],
					    'lng' => $_POST['Project']['lng']
					));					
				}
				else if ($_POST['method'] == "update")
				{
					Yii::app()->db->createCommand()->update('ki_project', array(
					    'oblast_id' => $_POST['Project']['oblast_id'],
					    'type' => $_POST['Project']['type'],
					    'company' => $_POST['Project']['company'],
					    'name' => $_POST['Project']['name'],
					    'contacts' => $_POST['Project']['contacts'],
					    'place' => $_POST['Project']['place'],
					    'price' => $_POST['Project']['price'],
					    'date_begin' => $_POST['Project']['date_begin'],
					    'product' => $_POST['Project']['product'],
					    'product_qntt' => $_POST['Project']['product_qntt'],
					    'product_price' => $_POST['Project']['product_price'],
					    'lat' => $_POST['Project']['lat'],
					    'lng' => $_POST['Project']['lng']
					), 'id=:id', array(':id' => $_POST['id']));
				}
				echo "true";
			}
			else 
			{
				echo "Not Valid\n";
				print_r($_POST['Project']);
			}
		}
		else
			throw new CHttpException(400, 'Invalid request');
	}

	/**
	  * Ajax : get resources
	  */
	public function actionGetResources()
	{	 		
	 	$resources = Yii::app()->db->createCommand()->select('*')->from('ki_resource')
								->where('lang=:lang AND oblast_id=:oblast_id', array(':lang' => 'ru_ru', ':oblast_id' => $_POST['oblastId']))
								->queryAll();
		
		echo json_encode($resources);
	}

	/**
	 * Ajax : get resources by type
	 */
	public function actionGetResourcesByType()
	{	 		
	 	$resources = Yii::app()->db->createCommand()->select('*')->from('ki_resource')
								->where('lang=:lang', array(':lang' => 'ru_ru'))
								->andWhere('type=:type', array(':type' => $_POST['type']))
								->queryAll();
		
		echo json_encode($resources);
	}
	
	/**
	 * Ajax : get object by type
	 */
	public function actionGetObjectsByType()
	{
	 	$objects = Yii::app()->db->createCommand()->select('*')->from('ki_' . $_POST['mapType'])
								->where('lang=:lang', array(':lang' => 'ru_ru'))
								->andWhere('type=:type', array(':type' => $_POST['type']))
								->queryAll();
		
		echo json_encode($objects);
	}

	/**
	 * Ajax : get resource
	 */
	public function actionGetResource()
	{
		$resource = Yii::app()->db->createCommand()->select('*')->from('ki_resource')
								->where('lang=:lang AND id=:id', array(':lang' => 'ru_ru', ':id' => $_POST['id']))
								->queryRow();
		
		echo json_encode($resource);
	}

	/**
	 * Ajax : save resource
	 */
	public function actionSaveResource()
	{
		$model=new Resource;
		if (isset($_POST['Resource']))
		{
			$_POST['Resource']['oblast_id'] = intval($_POST['Resource']['oblast_id']);
			$_POST['Resource']['lat'] = doubleval($_POST['Resource']['lat']);
			$_POST['Resource']['lng'] = doubleval($_POST['Resource']['lng']);
			
			$model->attributes=$_POST['Resource'];
			if ($model->validate())
			{
				if ($_POST['method'] == "save")
				{
					Yii::app()->db->createCommand()->insert('ki_resource', array(
					    'oblast_id' => $_POST['Resource']['oblast_id'],
					    'type' => $_POST['Resource']['type'],					    
					    'name' => $_POST['Resource']['name'],					   
					    'lat' => $_POST['Resource']['lat'],
					    'lng' => $_POST['Resource']['lng']
					));					
				}
				else if ($_POST['method'] == "update")
				{
					Yii::app()->db->createCommand()->update('ki_resource', array(
					    'oblast_id' => $_POST['Resource']['oblast_id'],
					    'type' => $_POST['Resource']['type'],					    
					    'name' => $_POST['Resource']['name'],					   
					    'lat' => $_POST['Resource']['lat'],
					    'lng' => $_POST['Resource']['lng']
					), 'id=:id', array(':id' => $_POST['id']));
				}
				echo "true";
			}
			else 
			{
				echo "Not Valid\n";
				print_r($_POST['Resource']);
			}
		}
		else
			throw new CHttpException(400, 'Invalid request');
	}

	/**
	 * Ajax : get product
	 */
	public function actionGetProduct()
	{
		$product = Yii::app()->db->createCommand()->select('*')->from('ki_product')
								->where('lang=:lang AND id=:id', array(':lang' => 'ru_ru', ':id' => $_POST['id']))
								->queryRow();
		
		echo json_encode($product);
	}
	
	/**
	 * Ajax: info windows for product
	 */
	public function actionGetInfoProduct()
	{
		//die (print_r($_POST));
		$product = Yii::app()->db->createCommand()->select('*')->from('ki_product')
								->where('lang=:lang AND id=:id', array(':lang' => 'ru_ru', ':id' => $_POST['id']))
								->queryRow();
		
		echo $this->renderPartial('_productInfo', array('product' => $product), true);
	}
	
	/**
	 * Ajax: info windows for project
	 */
	public function actionGetInfoProject()
	{
		//die (print_r($_POST));
		$project = Yii::app()->db->createCommand()->select('*')->from('ki_project')
								->where('lang=:lang AND id=:id', array(':lang' => 'ru_ru', ':id' => $_POST['id']))
								->queryRow();
		
		echo $this->renderPartial('_projectInfo', array('project' => $project), true);
	}
	
	/**
	 * Ajax: info windows for project
	 */
	public function actionGetProjectInfo()
	{
		$project = Yii::app()->db->createCommand()->select('*')->from('ki_project')
								->where('lang=:lang AND id=:id', array(':lang' => 'ru_ru', ':id' => $_POST['id']))
								->queryRow();
		
		echo $this->renderPartial('_projectInfo2', array('project' => $project), true);
	}

	/**
	 * Ajax: info windows for resource
	 */
	public function actionGetInfoResource()
	{
		//die (print_r($_POST));
		$resource = Yii::app()->db->createCommand()->select('*')->from('ki_resource')
								->where('lang=:lang AND id=:id', array(':lang' => 'ru_ru', ':id' => $_POST['id']))
								->queryRow();
		
		echo $this->renderPartial('_resourceInfo', array('resource' => $resource), true);
	}

	/**
	 * Ajax: info windows for region info
	 */
	public function actionGetRegionInfo()
	{
		$region = Yii::app()->db->createCommand()->select('*')->from('ki_oblast')
							->where('lang=:lang AND id=:id', array(':lang' => 'ru_ru', ':id' => 
							$_POST['id']))
							->queryRow();
		echo $this->renderPartial('_regionInfo', array('region' => $region), true);
	}

	
	/*
	 * Get Search Projects
	 */
	public function actionGetSearchProjects()
	{
		$model=new Project;
		
		$searchStr = $_POST['q'];
		
		$project = $model->getProjectsList($searchStr);
		
		echo $this->renderPartial('_searchproject', array('project' => $project), true);
	}
	
	public function getMapId()
	{
		$map = new Map;
		
		return $map->getJsName();
	}

	/*
	 * AJAX: Get Get Projects By Type
	 */
	public function actionGetProjectsByType()
	{
		$model = new Project;
		
		$pro_type = $_POST['type'];
		
		$projects = $model->getProjectsListByType($pro_type);
		
		echo $this->renderPartial('_projectslist', array('project' => $projects), true);
	}

	/*
	 * AJAX: Get Get Products By Type
	 */
	public function actionGetProductsByType()
	{
		$model = new Product;
		
		$pro_type = $_POST['type'];
		
		$products = $model->getProductsListByType($pro_type);
		
		echo $this->renderPartial('_productslist', array('product' => $products), true);
	}
}
