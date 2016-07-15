<?php
namespace Application\Service;

class FactualService implements APIServiceInterface{
	
	public function __construct(){
		$this->factual = $this->getConnection();
	}
	
	public function getConnection(){
		$factual = new \Factual("3pjEV3xag2GUkyHYc6VSJV7WZLaSknKQR0y6w0Rm","FCg36esMf2KCadOcZpt6G7DOlOk5oeq68leXgggY");
		return $factual;
	}
	
	public function getRemoteCategories() {
		$query = new \FactualQuery;
		$query->limit(10);
		$query->only("id,category");
		$res = $factual->fetch("places", $query);

		return $res->getData();
		
		return array(
				array('externalId' => 'unique id from source', 'categoryName' => 'name from source'),
				array('externalId' => 'unique id from source', 'categoryName' => 'name from source'),
		);
	}
	
	public function getRemoteTraits() {
	
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
	
	public function getRemoteData($offset = 0, $count = 100) {
		return array(
				'total' => 'count of all records',
				'records' => array(
						array(
								'externalId' => '',
								'longitude' => '',
								'latitude' => '',
								'addressState' => '',
								'addressCounty' => '',
								'addressCity' => '',
								'name' => '',
								'imageUrl' => '',
								'categories' => array(), //matching ids from getRemoteCategories
								'traits' => array(), //matching ids from getRemoteTraits
								'customerRating' => '',
								'priceRating' => '',
								'websiteUrl' => '',
		    					'internationalPhoneNumber' => '',
                    'hoursOfOperation' => array(
                        array(
                            "open" => array(
                                "day" => '0',
                                "time" => 'hhmm'
                            ),
                            "close" => array(
                                "day" => '0',
                                "time" => 'hhmm'
                            )
                        ),
                        array(
                            "open" => array(
                                "day" => '1',
                                "time" => 'hhmm'
                            ),
                            "close" => array(
                                "day" => '1',
                                "time" => 'hhmm'
                            )
                        ),
                        array(
                            "open" => array(
                                "day" => '2',
                                "time" => 'hhmm'
                            ),
                            "close" => array(
                                "day" => '2',
                                "time" => 'hhmm'
                            )
                        )
                    ),
                )
            )
        );
    }
	
	
}