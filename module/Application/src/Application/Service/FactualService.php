<?php
namespace Application\Service;

class FactualService implements APIServiceInterface{
	
	private $factual;
	
	public function __construct(){
		$this->factual = $this->getConnection();
	}
	
	public function getConnection(){
		$factual = new \Factual('3pjEV3xag2GUkyHYc6VSJV7WZLaSknKQR0y6w0Rm','FCg36esMf2KCadOcZpt6G7DOlOk5oeq68leXgggY');
		return $factual;
	}
	
	public function getRemoteCategories() {
	    $query = new \FactualQuery;  
	    $query->only('category_labels, category_ids');
	    $res = $this->factual->fetch('places', $query);
	    
	    $resultArray = array();
		foreach($res->getData() as $key => $category){
			$resultArray[$key]['externalId'] = array_shift($category['category_ids']);
			$resultArray[$key]['categoryName'] = array_shift($category['category_labels'][0]);
		}
		
		return $resultArray;
	}
	
	public function getRemoteTraits() {
		$query = new \FactualQuery;
		$query->only('traits');
		$res = $this->factual->fetch('places', $query);
		 
		return array(
				array("externalId" => 1, "traitName" => 'delivery'),
				array("externalId" => 2, "traitName" => 'take out'),
				array("externalId" => 3, "traitName" => 'catering'),
				array("externalId" => 4, "traitName" => 'vegetarian'),
				array("externalId" => 5, "traitName" => 'vegan'),
				array("externalId" => 6, "traitName" => 'gluten'),
				//include here cousine types U fetch - if posisble fetch remote id if not assign by own and do hard code
		);
	}
	
	public function getRemoteData($offset = 0, $count = 50) {
		
		$query = new \FactualQuery;
		$query->offset($offset);
		$query->limit($count);
		$query->includeRowCount();
		$res = $this->factual->fetch('restaurants-us', $query);

		$records = array();
		foreach(json_decode($res->getDataAsJSON()) as $key => $data){
			$records[$key] = array(
						'externalId' => property_exists($data, 'factual_id') ? $data->factual_id : '',
						'longitude' => property_exists($data, 'longitude') ? $data->longitude : '',
						'latitude' => property_exists($data, 'latitude') ? $data->latitude : '',
						'addressState' => property_exists($data, 'addressState') ? $data->addressState : '',
						'addressCounty' => property_exists($data, 'addressCounty') ? $data->addressState : '',
						'addressCity' => property_exists($data, 'addressCity') ? $data->addressState : '',
						'name' => property_exists($data, 'name') ? $data->name : '',
						'imageUrl' => property_exists($data, 'imageUrl') ? $data->imageUrl : '',
						'categories' => property_exists($data, 'category_ids') && property_exists($data, 'category_labels') ? array_fill_keys($data->category_ids, array_shift($data->category_labels)) : '', //matching ids from getRemoteCategories
						'traits' => property_exists($data, 'cuisine') ? $data->cuisine : '',
						'customerRating' => property_exists($data, 'customerRating') ? $data->customerRating : '',
						'priceRating' => property_exists($data, 'priceRating') ? $data->priceRating : '',
						'websiteUrl' => property_exists($data, 'website') ? $data->website : '',
						'internationalPhoneNumber' => property_exists($data, 'tel') ? $data->tel : '',
						'hoursOfOperation' => property_exists($data, 'hours') ? $data->hours : ''
					);
			
// 					$currentDay = 0;
// 					foreach($data->hours as $day){
// 						var_dump($data->hours ); die;
// 						$records[$key]['hoursOfOperation'][] = array(
// 								"open" => array(
// 										"day" => $currentDay,
// 										"time" => !empty($day[0][1]) ? $day[0][1] : ''
// 								),
// 								"close" => array(
// 										"day" => $currentDay,
// 										"time" => !empty($day[1][1]) ? $day[1][1] : ''
// 								)
// 						);
// 						$currentDay++;
// 					}
		}
		
		return array(
				'total' => $res->getTotalRowCount(),
				'records' => $records
				);
	}
	
}