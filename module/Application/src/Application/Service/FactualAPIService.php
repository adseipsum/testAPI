<?php
namespace Application\Service;

use \Factual;
use \FactualQuery;

/**
 * 
 * @author Oleksiy Perepelytsya
 *
 */
class FactualAPIService implements APIServiceInterface{
	
	/**
	 * @var \Factual
	 */
	private static $factual;
	
	/**
	 * {@inheritDoc}
	 * @see \Application\Service\APIServiceInterface::initConnection()
	 * 
	 *  @return \Factual
	 */
	public function initConnection(){
		if(static::$factual === null){
			static::$factual = new Factual('3pjEV3xag2GUkyHYc6VSJV7WZLaSknKQR0y6w0Rm', 'FCg36esMf2KCadOcZpt6G7DOlOk5oeq68leXgggY');
		}
		return static::$factual;
	}
	
	/**
	 * @return array[]
	 */
	public function getRemoteCategories() {
		$categories = json_decode(file_get_contents('https://raw.githubusercontent.com/Factual/places/master/categories/factual_taxonomy.json'));
		$categoriesArray = array();
		$counter = 0;
		
		if($categories) foreach($categories as $key => $category){
			$categoriesArray[$counter]['externalId'] = $key;
			$categoriesArray[$counter]['categoryName'] = $category->labels->en;
			$counter++;
		}

		return $categoriesArray;
	}
	
	/**
	 * @return array[]
	 */
	public function getRemoteTraits() {
		$responce = json_decode(file_get_contents('https://raw.githubusercontent.com/Factual/places/master/restaurants/factual_cuisines.json'));
		$cuisineArray = array();
		$counter = 0;
		
		if($responce->cuisine) foreach($responce->cuisine as $key => $cuisine){
			$cuisineArray[$counter]['externalId'] = $key;
			$cuisineArray[$counter]['traitName'] = $cuisine;
			$counter++;
		}

		return $cuisineArray;
	}
	
	/**
	 * 
	 * @param number $offset
	 * @param number $count
	 * 
	 * @return array
	 */
	public function getRemoteData($offset = 0, $count = 50) {
		
		$query = new FactualQuery;
		$query->offset($offset);
		$query->limit($count);
		$query->includeRowCount();
		$res = static::$factual->fetch('restaurants-us', $query);

		$records = array();
		//iterating data as object rather than array to make code looks clean
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
					);

					foreach($data->hours as $dayName => $day){
						$dayNumber = date('N', strtotime($dayName));
						$records[$key]['hoursOfOperation'][] = array(
								"open" => array(
										"day" => $dayNumber,
										"time" => !empty($day[0][0]) ? $day[0][0] : ''
								),
								"close" => array(
										"day" => $dayNumber,
										"time" => !empty($day[0][1]) ? $day[0][1] : ''
								)
						);
					}
		}
		
		return array(
				'total' => $res->getTotalRowCount(),
				'records' => $records
				);
	}
	
}