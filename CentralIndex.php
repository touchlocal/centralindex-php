<?php

  /**
    * Central Index
    *
    * The Central Index PHP Library
    *
    * @package 	CentralIndex
    * @author  	Glynn Bird
    * @link    	http://centralindex.com
    * @since   	Version 1.0
  */
  class CentralIndex {
    
    // the endpoint of the central index API
    const API_URL = "http://api.centralindex.com/v1";
    
    // store the user's API key and whether debuggin is required
    protected $apikey;
    protected $debugMode;
    
    /**
     * Constructor - store the api key in the class
     *
     *  @param apikey - the user's API key
     *  @param debugMode - whether to output debugging or not
     *  @return - the data from the api
    */
    public function __construct($apikey,$debugMode=false) {
      $this->apikey = $apikey;
      $this->debugMode = $debugMode;
    }   
    
    /**
     * Perform curl request
     *
     *  @param method - the HTTP method to do
     *  @param path - the relative path to visit
     *  @param data - an array of parameters to pass
     *  @return - the data from the api
    */
    private function doCurl($method, $path, $data) {
      
      $data['api_key'] = $this->apikey;
      
      $query = "";
      if($method == "GET") {
			 	$query.="?".http_build_query($data);
			} 
      
			$url = CentralIndex::API_URL.$path.$query;

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);

			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			if($method == "GET") {				
				curl_setopt($ch, CURLOPT_POST, false);
     	} else {
     	  if($method == "PUT") {
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: PUT'));
        }
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));			
			}
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			curl_close($ch);
			$return = false;
			$arr = json_decode($output,1);
			if(!$arr) {
			  $arr = $output;
			}


			if($this->debugMode){
        echo "METHOD = ".$method."\n";
        echo "CALL = ".$call."\n";
        echo "URL = ".$url."\n";
        print("Data:\n");
        print_r($data);
        print("Output:\n");        
        print_r($output);
			}

			return $arr;
    }

  /**
   * Confirms that the API is active, and returns the current version number
   *
   *  @return - the data from the api
  */
  public function getStatus() {
    $params = array();
    return CentralIndex::doCurl("GET","/status",$params);
  }


  /**
   * Fetch the project logo, the symbol of the Wolf
   *
   *  @param a
   *  @param b
   *  @param c
   *  @param d
   *  @return - the data from the api
  */
  public function getLogo( $a, $b, $c, $d) {
    $params = array();
    $params['a'] = $a;
    $params['b'] = $b;
    $params['c'] = $c;
    $params['d'] = $d;
    return CentralIndex::doCurl("GET","/logo",$params);
  }


  /**
   * Fetch the project logo, the symbol of the Wolf
   *
   *  @param a
   *  @return - the data from the api
  */
  public function putLogo( $a) {
    $params = array();
    $params['a'] = $a;
    return CentralIndex::doCurl("PUT","/logo",$params);
  }


  /**
   * Uploads a CSV file of known format and bulk inserts into DB
   *
   *  @param filedata
   *  @return - the data from the api
  */
  public function postEntityBulkCsv( $filedata) {
    $params = array();
    $params['filedata'] = $filedata;
    return CentralIndex::doCurl("POST","/entity/bulk/csv",$params);
  }


  /**
   * Uploads a JSON file of known format and bulk inserts into DB
   *
   *  @param data
   *  @return - the data from the api
  */
  public function postEntityBulkJson( $data) {
    $params = array();
    $params['data'] = $data;
    return CentralIndex::doCurl("POST","/entity/bulk/json",$params);
  }


  /**
   * Shows the current status of a bulk upload
   *
   *  @param upload_id
   *  @return - the data from the api
  */
  public function getEntityBulkCsvStatus( $upload_id) {
    $params = array();
    $params['upload_id'] = $upload_id;
    return CentralIndex::doCurl("GET","/entity/bulk/csv/status",$params);
  }


  /**
   * Shows the current status of a bulk JSON upload
   *
   *  @param upload_id
   *  @return - the data from the api
  */
  public function getEntityBulkJsonStatus( $upload_id) {
    $params = array();
    $params['upload_id'] = $upload_id;
    return CentralIndex::doCurl("GET","/entity/bulk/json/status",$params);
  }


  /**
   * This entity isn't really supported anymore. You probably want PUT /business. Only to be used for testing.
   *
   *  @param type
   *  @param scope
   *  @param country
   *  @param trust
   *  @param our_data
   *  @return - the data from the api
  */
  public function putEntity( $type, $scope, $country, $trust, $our_data) {
    $params = array();
    $params['type'] = $type;
    $params['scope'] = $scope;
    $params['country'] = $country;
    $params['trust'] = $trust;
    $params['our_data'] = $our_data;
    return CentralIndex::doCurl("PUT","/entity",$params);
  }


  /**
   * Fetches the documents that match the given masheryid and supplier_id
   *
   *  @param supplier_id - The Supplier ID
   *  @return - the data from the api
  */
  public function getEntityBy_supplier_id( $supplier_id) {
    $params = array();
    $params['supplier_id'] = $supplier_id;
    return CentralIndex::doCurl("GET","/entity/by_supplier_id",$params);
  }


  /**
   * Search for matching entities
   *
   *  @param what
   *  @param entity_name
   *  @param where
   *  @param per_page
   *  @param page
   *  @param longitude
   *  @param latitude
   *  @param country
   *  @param language
   *  @return - the data from the api
  */
  public function getEntitySearch( $what, $entity_name, $where, $per_page, $page, $longitude, $latitude, $country, $language) {
    $params = array();
    $params['what'] = $what;
    $params['entity_name'] = $entity_name;
    $params['where'] = $where;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    $params['longitude'] = $longitude;
    $params['latitude'] = $latitude;
    $params['country'] = $country;
    $params['language'] = $language;
    return CentralIndex::doCurl("GET","/entity/search",$params);
  }


  /**
   * Search for matching entities
   *
   *  @param what - What to get results for. E.g. Plumber e.g. plumber
   *  @param where - The location to get results for. E.g. Dublin e.g. Dublin
   *  @param per_page - Number of results returned per page
   *  @param page - Which page number to retrieve
   *  @param country - Which country to return results for. An ISO compatible country code, E.g. ie e.g. ie
   *  @param language - An ISO compatible language code, E.g. en
   *  @return - the data from the api
  */
  public function getEntitySearchWhatBylocation( $what, $where, $per_page, $page, $country, $language) {
    $params = array();
    $params['what'] = $what;
    $params['where'] = $where;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    $params['country'] = $country;
    $params['language'] = $language;
    return CentralIndex::doCurl("GET","/entity/search/what/bylocation",$params);
  }


  /**
   * Search for matching entities
   *
   *  @param what - What to get results for. E.g. Plumber e.g. plumber
   *  @param latitude_1 - Latitude of first point in bounding box e.g. 53.396842
   *  @param longitude_1 - Longitude of first point in bounding box e.g. -6.37619
   *  @param latitude_2 - Latitude of second point in bounding box e.g. 53.290463
   *  @param longitude_2 - Longitude of second point in bounding box e.g. -6.207275
   *  @param per_page
   *  @param page
   *  @param country - A valid ISO 3166 country code e.g. ie
   *  @param language
   *  @return - the data from the api
  */
  public function getEntitySearchWhatByboundingbox( $what, $latitude_1, $longitude_1, $latitude_2, $longitude_2, $per_page, $page, $country, $language) {
    $params = array();
    $params['what'] = $what;
    $params['latitude_1'] = $latitude_1;
    $params['longitude_1'] = $longitude_1;
    $params['latitude_2'] = $latitude_2;
    $params['longitude_2'] = $longitude_2;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    $params['country'] = $country;
    $params['language'] = $language;
    return CentralIndex::doCurl("GET","/entity/search/what/byboundingbox",$params);
  }


  /**
   * Search for matching entities
   *
   *  @param who
   *  @param latitude_1
   *  @param longitude_1
   *  @param latitude_2
   *  @param longitude_2
   *  @param per_page
   *  @param page
   *  @param country
   *  @return - the data from the api
  */
  public function getEntitySearchWhoByboundingbox( $who, $latitude_1, $longitude_1, $latitude_2, $longitude_2, $per_page, $page, $country) {
    $params = array();
    $params['who'] = $who;
    $params['latitude_1'] = $latitude_1;
    $params['longitude_1'] = $longitude_1;
    $params['latitude_2'] = $latitude_2;
    $params['longitude_2'] = $longitude_2;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    $params['country'] = $country;
    return CentralIndex::doCurl("GET","/entity/search/who/byboundingbox",$params);
  }


  /**
   * Search for matching entities
   *
   *  @param who - Company Name e.g. Starbucks
   *  @param where - The location to get results for. E.g. Dublin e.g. Dublin
   *  @param per_page - Number of results returned per page
   *  @param page - Which page number to retrieve
   *  @param country - Which country to return results for. An ISO compatible country code, E.g. ie e.g. ie
   *  @return - the data from the api
  */
  public function getEntitySearchWhoBylocation( $who, $where, $per_page, $page, $country) {
    $params = array();
    $params['who'] = $who;
    $params['where'] = $where;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    $params['country'] = $country;
    return CentralIndex::doCurl("GET","/entity/search/who/bylocation",$params);
  }


  /**
   * Search for matching entities
   *
   *  @param what - What to get results for. E.g. Plumber e.g. plumber
   *  @param per_page - Number of results returned per page
   *  @param page - The page number to retrieve
   *  @param country - Which country to return results for. An ISO compatible country code, E.g. ie e.g. ie
   *  @param language - An ISO compatible language code, E.g. en
   *  @return - the data from the api
  */
  public function getEntitySearchWhat( $what, $per_page, $page, $country, $language) {
    $params = array();
    $params['what'] = $what;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    $params['country'] = $country;
    $params['language'] = $language;
    return CentralIndex::doCurl("GET","/entity/search/what",$params);
  }


  /**
   * Search for matching entities
   *
   *  @param who - Company name e.g. Starbucks
   *  @param per_page - How many results per page
   *  @param page - What page number to retrieve
   *  @param country - Which country to return results for. An ISO compatible country code, E.g. ie e.g. ie
   *  @return - the data from the api
  */
  public function getEntitySearchWho( $who, $per_page, $page, $country) {
    $params = array();
    $params['who'] = $who;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    $params['country'] = $country;
    return CentralIndex::doCurl("GET","/entity/search/who",$params);
  }


  /**
   * Search for matching entities
   *
   *  @param where - Location to search for results. E.g. Dublin e.g. Dublin
   *  @param per_page - How many results per page
   *  @param page - What page number to retrieve
   *  @param country - Which country to return results for. An ISO compatible country code, E.g. ie
   *  @param language - An ISO compatible language code, E.g. en
   *  @return - the data from the api
  */
  public function getEntitySearchBylocation( $where, $per_page, $page, $country, $language) {
    $params = array();
    $params['where'] = $where;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    $params['country'] = $country;
    $params['language'] = $language;
    return CentralIndex::doCurl("GET","/entity/search/bylocation",$params);
  }


  /**
   * Search for matching entities
   *
   *  @param latitude_1
   *  @param longitude_1
   *  @param latitude_2
   *  @param longitude_2
   *  @param per_page
   *  @param page
   *  @param country
   *  @param language
   *  @return - the data from the api
  */
  public function getEntitySearchByboundingbox( $latitude_1, $longitude_1, $latitude_2, $longitude_2, $per_page, $page, $country, $language) {
    $params = array();
    $params['latitude_1'] = $latitude_1;
    $params['longitude_1'] = $longitude_1;
    $params['latitude_2'] = $latitude_2;
    $params['longitude_2'] = $longitude_2;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    $params['country'] = $country;
    $params['language'] = $language;
    return CentralIndex::doCurl("GET","/entity/search/byboundingbox",$params);
  }


  /**
   * Search for matching entities that are advertisers and return a random selection upto the limit requested
   *
   *  @param tag - The word or words the advertiser is to appear for in searches
   *  @param where - The location to get results for. E.g. Dublin
   *  @param limit - The number of advertisers that are to be returned
   *  @param country - Which country to return results for. An ISO compatible country code, E.g. ie e.g. ie
   *  @param language - An ISO compatible language code, E.g. en
   *  @return - the data from the api
  */
  public function getEntityAdvertisers( $tag, $where, $limit, $country, $language) {
    $params = array();
    $params['tag'] = $tag;
    $params['where'] = $where;
    $params['limit'] = $limit;
    $params['country'] = $country;
    $params['language'] = $language;
    return CentralIndex::doCurl("GET","/entity/advertisers",$params);
  }


  /**
   * Allows a whole entity to be pulled from the datastore by its unique id
   *
   *  @param entity_id - The unique entity ID e.g. 379236608286720
   *  @return - the data from the api
  */
  public function getEntity( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("GET","/entity",$params);
  }


  /**
   * Get all entiies claimed by a specific user
   *
   *  @param user_id - The unique user ID of the user with claimed entities e.g. 379236608286720
   *  @return - the data from the api
  */
  public function getEntityBy_user_id( $user_id) {
    $params = array();
    $params['user_id'] = $user_id;
    return CentralIndex::doCurl("GET","/entity/by_user_id",$params);
  }


  /**
   * Allows a list of available revisions to be returned by its entity id
   *
   *  @param entity_id
   *  @return - the data from the api
  */
  public function getEntityRevisions( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("GET","/entity/revisions",$params);
  }


  /**
   * Allows a specific revision of an entity to be returned by entity id and a revision number
   *
   *  @param entity_id
   *  @param revision_id
   *  @return - the data from the api
  */
  public function getEntityRevisionsByRevisionID( $entity_id, $revision_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['revision_id'] = $revision_id;
    return CentralIndex::doCurl("GET","/entity/revisions/byRevisionID",$params);
  }


  /**
   * Separates an entity into two distinct entities 
   *
   *  @param entity_id
   *  @param supplier_masheryid
   *  @param supplier_id
   *  @return - the data from the api
  */
  public function postEntityUnmerge( $entity_id, $supplier_masheryid, $supplier_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['supplier_masheryid'] = $supplier_masheryid;
    $params['supplier_id'] = $supplier_id;
    return CentralIndex::doCurl("POST","/entity/unmerge",$params);
  }


  /**
   * Fetches the changelog documents that match the given entity_id
   *
   *  @param entity_id
   *  @return - the data from the api
  */
  public function getEntityChangelog( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("GET","/entity/changelog",$params);
  }


  /**
   * Merge two entities into one
   *
   *  @param from
   *  @param to
   *  @return - the data from the api
  */
  public function postEntityMerge( $from, $to) {
    $params = array();
    $params['from'] = $from;
    $params['to'] = $to;
    return CentralIndex::doCurl("POST","/entity/merge",$params);
  }


  /**
   * Force refresh of search indexes
   *
   *  @return - the data from the api
  */
  public function getToolsReindex() {
    $params = array();
    return CentralIndex::doCurl("GET","/tools/reindex",$params);
  }


  /**
   * Update entities that use an old category ID to a new one
   *
   *  @param from
   *  @param to
   *  @param limit
   *  @return - the data from the api
  */
  public function postEntityMigrate_category( $from, $to, $limit) {
    $params = array();
    $params['from'] = $from;
    $params['to'] = $to;
    $params['limit'] = $limit;
    return CentralIndex::doCurl("POST","/entity/migrate_category",$params);
  }


  /**
   * Create a new business entity with all it's objects
   *
   *  @param name
   *  @param address1
   *  @param address2
   *  @param address3
   *  @param district
   *  @param town
   *  @param county
   *  @param postcode
   *  @param country
   *  @param latitude
   *  @param longitude
   *  @param timezone
   *  @param telephone_number
   *  @param email
   *  @param website
   *  @param category_id
   *  @param category_type
   *  @param do_not_display
   *  @return - the data from the api
  */
  public function putBusiness( $name, $address1, $address2, $address3, $district, $town, $county, $postcode, $country, $latitude, $longitude, $timezone, $telephone_number, $email, $website, $category_id, $category_type, $do_not_display) {
    $params = array();
    $params['name'] = $name;
    $params['address1'] = $address1;
    $params['address2'] = $address2;
    $params['address3'] = $address3;
    $params['district'] = $district;
    $params['town'] = $town;
    $params['county'] = $county;
    $params['postcode'] = $postcode;
    $params['country'] = $country;
    $params['latitude'] = $latitude;
    $params['longitude'] = $longitude;
    $params['timezone'] = $timezone;
    $params['telephone_number'] = $telephone_number;
    $params['email'] = $email;
    $params['website'] = $website;
    $params['category_id'] = $category_id;
    $params['category_type'] = $category_type;
    $params['do_not_display'] = $do_not_display;
    return CentralIndex::doCurl("PUT","/business",$params);
  }


  /**
   * Allows the removal or insertion of tags into an advertiser object
   *
   *  @param gen_id - The gen_id of this advertiser
   *  @param entity_id - The entity_id of the advertiser
   *  @param language - The tag language to alter
   *  @param tags_to_add - The tags to add
   *  @param tags_to_remove - The tags to remove
   *  @return - the data from the api
  */
  public function postEntityAdvertiserTag( $gen_id, $entity_id, $language, $tags_to_add, $tags_to_remove) {
    $params = array();
    $params['gen_id'] = $gen_id;
    $params['entity_id'] = $entity_id;
    $params['language'] = $language;
    $params['tags_to_add'] = $tags_to_add;
    $params['tags_to_remove'] = $tags_to_remove;
    return CentralIndex::doCurl("POST","/entity/advertiser/tag",$params);
  }


  /**
   * Find a location from cache or cloudant depending if it is in the cache
   *
   *  @param string
   *  @param country
   *  @return - the data from the api
  */
  public function getLookupLocation( $string, $country) {
    $params = array();
    $params['string'] = $string;
    $params['country'] = $country;
    return CentralIndex::doCurl("GET","/lookup/location",$params);
  }


  /**
   * Find a category from cache or cloudant depending if it is in the cache
   *
   *  @param string - A string to search against, E.g. Plumbers
   *  @param language - An ISO compatible language code, E.g. en
   *  @return - the data from the api
  */
  public function getLookupCategory( $string, $language) {
    $params = array();
    $params['string'] = $string;
    $params['language'] = $language;
    return CentralIndex::doCurl("GET","/lookup/category",$params);
  }


  /**
   * Find a category from a legacy ID or supplier (e.g. bill_moss)
   *
   *  @param id
   *  @param type
   *  @return - the data from the api
  */
  public function getLookupLegacyCategory( $id, $type) {
    $params = array();
    $params['id'] = $id;
    $params['type'] = $type;
    return CentralIndex::doCurl("GET","/lookup/legacy/category",$params);
  }


  /**
   * Find all the parents locations of the selected location
   *
   *  @param location_id
   *  @return - the data from the api
  */
  public function getLookupLocationParents( $location_id) {
    $params = array();
    $params['location_id'] = $location_id;
    return CentralIndex::doCurl("GET","/lookup/location/parents",$params);
  }


  /**
   * Find all the child locations of the selected location
   *
   *  @param location_id
   *  @param resolution
   *  @return - the data from the api
  */
  public function getLookupLocationChildren( $location_id, $resolution) {
    $params = array();
    $params['location_id'] = $location_id;
    $params['resolution'] = $resolution;
    return CentralIndex::doCurl("GET","/lookup/location/children",$params);
  }


  /**
   * With a known entity id, a name can be updated.
   *
   *  @param entity_id
   *  @param name
   *  @param formal_name
   *  @return - the data from the api
  */
  public function postEntityName( $entity_id, $name, $formal_name) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['name'] = $name;
    $params['formal_name'] = $formal_name;
    return CentralIndex::doCurl("POST","/entity/name",$params);
  }


  /**
   * With a known entity id, an background object can be added. There can however only be one background object.
   *
   *  @param entity_id
   *  @param number_of_employees
   *  @param turnover
   *  @param net_profit
   *  @param vat_number
   *  @param duns_number
   *  @param registered_company_number
   *  @return - the data from the api
  */
  public function postEntityBackground( $entity_id, $number_of_employees, $turnover, $net_profit, $vat_number, $duns_number, $registered_company_number) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['number_of_employees'] = $number_of_employees;
    $params['turnover'] = $turnover;
    $params['net_profit'] = $net_profit;
    $params['vat_number'] = $vat_number;
    $params['duns_number'] = $duns_number;
    $params['registered_company_number'] = $registered_company_number;
    return CentralIndex::doCurl("POST","/entity/background",$params);
  }


  /**
   * With a known entity id, an employee object can be added.
   *
   *  @param entity_id
   *  @param title
   *  @param forename
   *  @param surname
   *  @param job_title
   *  @param description
   *  @param email
   *  @param phone_number
   *  @return - the data from the api
  */
  public function postEntityEmployee( $entity_id, $title, $forename, $surname, $job_title, $description, $email, $phone_number) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['title'] = $title;
    $params['forename'] = $forename;
    $params['surname'] = $surname;
    $params['job_title'] = $job_title;
    $params['description'] = $description;
    $params['email'] = $email;
    $params['phone_number'] = $phone_number;
    return CentralIndex::doCurl("POST","/entity/employee",$params);
  }


  /**
   * Allows an employee object to be reduced in confidence
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntityEmployee( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/employee",$params);
  }


  /**
   * Allows a new phone object to be added to a specified entity. A new object id will be calculated and returned to you if successful.
   *
   *  @param entity_id
   *  @param number
   *  @param description
   *  @return - the data from the api
  */
  public function postEntityPhone( $entity_id, $number, $description) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['number'] = $number;
    $params['description'] = $description;
    return CentralIndex::doCurl("POST","/entity/phone",$params);
  }


  /**
   * Allows a phone object to be reduced in confidence
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntityPhone( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/phone",$params);
  }


  /**
   * With a known entity id, an fax object can be added.
   *
   *  @param entity_id
   *  @param number
   *  @param description
   *  @return - the data from the api
  */
  public function postEntityFax( $entity_id, $number, $description) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['number'] = $number;
    $params['description'] = $description;
    return CentralIndex::doCurl("POST","/entity/fax",$params);
  }


  /**
   * Allows a fax object to be reduced in confidence
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntityFax( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/fax",$params);
  }


  /**
   * With a known category id, an category object can be added.
   *
   *  @param category_id
   *  @param language
   *  @param name
   *  @return - the data from the api
  */
  public function putCategory( $category_id, $language, $name) {
    $params = array();
    $params['category_id'] = $category_id;
    $params['language'] = $language;
    $params['name'] = $name;
    return CentralIndex::doCurl("PUT","/category",$params);
  }


  /**
   * With a known category id, a mapping object can be added.
   *
   *  @param category_id
   *  @param type
   *  @param id
   *  @param name
   *  @return - the data from the api
  */
  public function postCategoryMappings( $category_id, $type, $id, $name) {
    $params = array();
    $params['category_id'] = $category_id;
    $params['type'] = $type;
    $params['id'] = $id;
    $params['name'] = $name;
    return CentralIndex::doCurl("POST","/category/mappings",$params);
  }


  /**
   * With a known category id, an synonym object can be added.
   *
   *  @param category_id
   *  @param synonym
   *  @param language
   *  @return - the data from the api
  */
  public function postCategorySynonym( $category_id, $synonym, $language) {
    $params = array();
    $params['category_id'] = $category_id;
    $params['synonym'] = $synonym;
    $params['language'] = $language;
    return CentralIndex::doCurl("POST","/category/synonym",$params);
  }


  /**
   * With a known category id, a synonyms object can be removed.
   *
   *  @param category_id
   *  @param synonym
   *  @param language
   *  @return - the data from the api
  */
  public function deleteCategorySynonym( $category_id, $synonym, $language) {
    $params = array();
    $params['category_id'] = $category_id;
    $params['synonym'] = $synonym;
    $params['language'] = $language;
    return CentralIndex::doCurl("DELETE","/category/synonym",$params);
  }


  /**
   * Allows a category object to merged with another
   *
   *  @param from
   *  @param to
   *  @return - the data from the api
  */
  public function postCategoryMerge( $from, $to) {
    $params = array();
    $params['from'] = $from;
    $params['to'] = $to;
    return CentralIndex::doCurl("POST","/category/merge",$params);
  }


  /**
   * Returns the supplied wolf category object by fetching the supplied category_id from our categories object.
   *
   *  @param category_id
   *  @return - the data from the api
  */
  public function getCategory( $category_id) {
    $params = array();
    $params['category_id'] = $category_id;
    return CentralIndex::doCurl("GET","/category",$params);
  }


  /**
   * With a known entity id, an category object can be added.
   *
   *  @param entity_id
   *  @param category_id
   *  @param category_type
   *  @return - the data from the api
  */
  public function postEntityCategory( $entity_id, $category_id, $category_type) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['category_id'] = $category_id;
    $params['category_type'] = $category_type;
    return CentralIndex::doCurl("POST","/entity/category",$params);
  }


  /**
   * Allows a category object to be reduced in confidence
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntityCategory( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/category",$params);
  }


  /**
   * With a known entity id, a geopoint can be updated.
   *
   *  @param entity_id
   *  @param longitude
   *  @param latitude
   *  @param accuracy
   *  @return - the data from the api
  */
  public function postEntityGeopoint( $entity_id, $longitude, $latitude, $accuracy) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['longitude'] = $longitude;
    $params['latitude'] = $latitude;
    $params['accuracy'] = $accuracy;
    return CentralIndex::doCurl("POST","/entity/geopoint",$params);
  }


  /**
   * Find all matches by phone number and then return all matches that also match company name and location. Default location_strictness is defined in Km and the default is set to 0.2 (200m)
   *
   *  @param phone
   *  @param company_name
   *  @param latitude
   *  @param longitude
   *  @param country
   *  @param name_strictness
   *  @param location_strictness
   *  @return - the data from the api
  */
  public function getMatchByphone( $phone, $company_name, $latitude, $longitude, $country, $name_strictness, $location_strictness) {
    $params = array();
    $params['phone'] = $phone;
    $params['company_name'] = $company_name;
    $params['latitude'] = $latitude;
    $params['longitude'] = $longitude;
    $params['country'] = $country;
    $params['name_strictness'] = $name_strictness;
    $params['location_strictness'] = $location_strictness;
    return CentralIndex::doCurl("GET","/match/byphone",$params);
  }


  /**
   * Find all matches by location and then return all matches that also match company name. Default location_strictness is set to 7, which equates to +/- 20m
   *
   *  @param company_name
   *  @param latitude
   *  @param longitude
   *  @param name_strictness
   *  @param location_strictness
   *  @return - the data from the api
  */
  public function getMatchBylocation( $company_name, $latitude, $longitude, $name_strictness, $location_strictness) {
    $params = array();
    $params['company_name'] = $company_name;
    $params['latitude'] = $latitude;
    $params['longitude'] = $longitude;
    $params['name_strictness'] = $name_strictness;
    $params['location_strictness'] = $location_strictness;
    return CentralIndex::doCurl("GET","/match/bylocation",$params);
  }


  /**
   * Removes stopwords from a string
   *
   *  @param text
   *  @return - the data from the api
  */
  public function getToolsStopwords( $text) {
    $params = array();
    $params['text'] = $text;
    return CentralIndex::doCurl("GET","/tools/stopwords",$params);
  }


  /**
   * Returns a stemmed version of a string
   *
   *  @param text
   *  @return - the data from the api
  */
  public function getToolsStem( $text) {
    $params = array();
    $params['text'] = $text;
    return CentralIndex::doCurl("GET","/tools/stem",$params);
  }


  /**
   * Return the phonetic representation of a string
   *
   *  @param text
   *  @return - the data from the api
  */
  public function getToolsPhonetic( $text) {
    $params = array();
    $params['text'] = $text;
    return CentralIndex::doCurl("GET","/tools/phonetic",$params);
  }


  /**
   * Fully process a string. This includes removing punctuation, stops words and stemming a string. Also returns the phonetic representation of this string.
   *
   *  @param text
   *  @return - the data from the api
  */
  public function getToolsProcess_string( $text) {
    $params = array();
    $params['text'] = $text;
    return CentralIndex::doCurl("GET","/tools/process_string",$params);
  }


  /**
   * Attempt to process a phone number, removing anything which is not a digit
   *
   *  @param number
   *  @return - the data from the api
  */
  public function getToolsProcess_phone( $number) {
    $params = array();
    $params['number'] = $number;
    return CentralIndex::doCurl("GET","/tools/process_phone",$params);
  }


  /**
   * Spider a single url looking for key facts
   *
   *  @param url
   *  @param pages
   *  @param country
   *  @return - the data from the api
  */
  public function getToolsSpider( $url, $pages, $country) {
    $params = array();
    $params['url'] = $url;
    $params['pages'] = $pages;
    $params['country'] = $country;
    return CentralIndex::doCurl("GET","/tools/spider",$params);
  }


  /**
   * Supply an address to geocode - returns lat/lon and accuracy
   *
   *  @param address1
   *  @param address2
   *  @param address3
   *  @param district
   *  @param town
   *  @param county
   *  @param postcode
   *  @param country
   *  @return - the data from the api
  */
  public function getToolsGeocode( $address1, $address2, $address3, $district, $town, $county, $postcode, $country) {
    $params = array();
    $params['address1'] = $address1;
    $params['address2'] = $address2;
    $params['address3'] = $address3;
    $params['district'] = $district;
    $params['town'] = $town;
    $params['county'] = $county;
    $params['postcode'] = $postcode;
    $params['country'] = $country;
    return CentralIndex::doCurl("GET","/tools/geocode",$params);
  }


  /**
   * Generate JSON in the format to generate Mashery's IODocs
   *
   *  @param mode - The HTTP method of the API call to document. e.g. GET
   *  @param path - The path of the API call to document e.g, /entity
   *  @param endpoint - The Mashery 'endpoint' to prefix to our API paths e.g. v1
   *  @param doctype - Mashery has two forms of JSON to describe API methods; one on github, the other on its customer dashboard
   *  @return - the data from the api
  */
  public function getToolsIodocs( $mode, $path, $endpoint, $doctype) {
    $params = array();
    $params['mode'] = $mode;
    $params['path'] = $path;
    $params['endpoint'] = $endpoint;
    $params['doctype'] = $doctype;
    return CentralIndex::doCurl("GET","/tools/iodocs",$params);
  }


  /**
   * Use this call to get information (in HTML or JSON) about the data structure of given entity object (e.g. a phone number or an address)
   *
   *  @param object - The API call documentation is required for
   *  @param format - The format of the returned data eg. JSON or HTML
   *  @return - the data from the api
  */
  public function getToolsDocs( $object, $format) {
    $params = array();
    $params['object'] = $object;
    $params['format'] = $format;
    return CentralIndex::doCurl("GET","/tools/docs",$params);
  }


  /**
   * Format a phone number according to the rules of the country supplied
   *
   *  @param number - The telephone number to format
   *  @param country - The country where the telephone number is based
   *  @return - the data from the api
  */
  public function getToolsFormatPhone( $number, $country) {
    $params = array();
    $params['number'] = $number;
    $params['country'] = $country;
    return CentralIndex::doCurl("GET","/tools/format/phone",$params);
  }


  /**
   * Format an address according to the rules of the country supplied
   *
   *  @param address - The address to format
   *  @param country - The country where the address is based
   *  @return - the data from the api
  */
  public function getToolsFormatAddress( $address, $country) {
    $params = array();
    $params['address'] = $address;
    $params['country'] = $country;
    return CentralIndex::doCurl("GET","/tools/format/address",$params);
  }


  /**
   * Check to see if a supplied email address is valid
   *
   *  @param email_address - The email address to validate
   *  @return - the data from the api
  */
  public function getToolsValidate_email( $email_address) {
    $params = array();
    $params['email_address'] = $email_address;
    return CentralIndex::doCurl("GET","/tools/validate_email",$params);
  }


  /**
   * compile the supplied less with the standard Bootstrap less into a CSS file
   *
   *  @param less - The LESS code to compile
   *  @return - the data from the api
  */
  public function getToolsLess( $less) {
    $params = array();
    $params['less'] = $less;
    return CentralIndex::doCurl("GET","/tools/less",$params);
  }


  /**
   * replace some text parameters with some entity details
   *
   *  @param entity_id - The entity to pull for replacements
   *  @param string - The string full of parameters
   *  @return - the data from the api
  */
  public function getToolsReplace( $entity_id, $string) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['string'] = $string;
    return CentralIndex::doCurl("GET","/tools/replace",$params);
  }


  /**
   * Check to see if a supplied email address is valid
   *
   *  @param from - The phone number from which the SMS orginates
   *  @param to - The phone number to which the SMS is to be sent
   *  @param message - The message to be sent in the SMS
   *  @return - the data from the api
  */
  public function getToolsSendsms( $from, $to, $message) {
    $params = array();
    $params['from'] = $from;
    $params['to'] = $to;
    $params['message'] = $message;
    return CentralIndex::doCurl("GET","/tools/sendsms",$params);
  }


  /**
   * Given a spreadsheet id add a row
   *
   *  @param spreadsheet_key - The key of the spreadsheet to edit
   *  @param data - A comma separated list to add as cells
   *  @return - the data from the api
  */
  public function postToolsGooglesheetAdd_row( $spreadsheet_key, $data) {
    $params = array();
    $params['spreadsheet_key'] = $spreadsheet_key;
    $params['data'] = $data;
    return CentralIndex::doCurl("POST","/tools/googlesheet/add_row",$params);
  }


  /**
   * With a known entity id, an invoice_address object can be updated.
   *
   *  @param entity_id
   *  @param address1
   *  @param address2
   *  @param address3
   *  @param district
   *  @param town
   *  @param county
   *  @param postcode
   *  @param address_type
   *  @return - the data from the api
  */
  public function postEntityInvoice_address( $entity_id, $address1, $address2, $address3, $district, $town, $county, $postcode, $address_type) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['address1'] = $address1;
    $params['address2'] = $address2;
    $params['address3'] = $address3;
    $params['district'] = $district;
    $params['town'] = $town;
    $params['county'] = $county;
    $params['postcode'] = $postcode;
    $params['address_type'] = $address_type;
    return CentralIndex::doCurl("POST","/entity/invoice_address",$params);
  }


  /**
   * With a known entity id and a known invoice_address ID, we can delete a specific invoice_address object from an enitity.
   *
   *  @param entity_id
   *  @return - the data from the api
  */
  public function deleteEntityInvoice_address( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("DELETE","/entity/invoice_address",$params);
  }


  /**
   * With a known entity id, an tag object can be added.
   *
   *  @param entity_id
   *  @param tag
   *  @param language
   *  @return - the data from the api
  */
  public function postEntityTag( $entity_id, $tag, $language) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['tag'] = $tag;
    $params['language'] = $language;
    return CentralIndex::doCurl("POST","/entity/tag",$params);
  }


  /**
   * Allows a tag object to be reduced in confidence
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntityTag( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/tag",$params);
  }


  /**
   * Create/Update a postal address
   *
   *  @param entity_id
   *  @param address1
   *  @param address2
   *  @param address3
   *  @param district
   *  @param town
   *  @param county
   *  @param postcode
   *  @param address_type
   *  @param do_not_display
   *  @return - the data from the api
  */
  public function postEntityPostal_address( $entity_id, $address1, $address2, $address3, $district, $town, $county, $postcode, $address_type, $do_not_display) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['address1'] = $address1;
    $params['address2'] = $address2;
    $params['address3'] = $address3;
    $params['district'] = $district;
    $params['town'] = $town;
    $params['county'] = $county;
    $params['postcode'] = $postcode;
    $params['address_type'] = $address_type;
    $params['do_not_display'] = $do_not_display;
    return CentralIndex::doCurl("POST","/entity/postal_address",$params);
  }


  /**
   * With a known entity id, a advertiser is added
   *
   *  @param entity_id
   *  @param tags
   *  @param locations
   *  @param max_tags
   *  @param max_locations
   *  @param expiry_date
   *  @param is_national
   *  @param language
   *  @param reseller_ref
   *  @param reseller_agent_id
   *  @param publisher_id
   *  @return - the data from the api
  */
  public function postEntityAdvertiserCreate( $entity_id, $tags, $locations, $max_tags, $max_locations, $expiry_date, $is_national, $language, $reseller_ref, $reseller_agent_id, $publisher_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['tags'] = $tags;
    $params['locations'] = $locations;
    $params['max_tags'] = $max_tags;
    $params['max_locations'] = $max_locations;
    $params['expiry_date'] = $expiry_date;
    $params['is_national'] = $is_national;
    $params['language'] = $language;
    $params['reseller_ref'] = $reseller_ref;
    $params['reseller_agent_id'] = $reseller_agent_id;
    $params['publisher_id'] = $publisher_id;
    return CentralIndex::doCurl("POST","/entity/advertiser/create",$params);
  }


  /**
   * With a known entity id, an advertiser is updated
   *
   *  @param entity_id
   *  @param tags
   *  @param locations
   *  @param extra_tags
   *  @param extra_locations
   *  @param is_national
   *  @param language
   *  @param reseller_ref
   *  @param reseller_agent_id
   *  @param publisher_id
   *  @return - the data from the api
  */
  public function postEntityAdvertiserUpsell( $entity_id, $tags, $locations, $extra_tags, $extra_locations, $is_national, $language, $reseller_ref, $reseller_agent_id, $publisher_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['tags'] = $tags;
    $params['locations'] = $locations;
    $params['extra_tags'] = $extra_tags;
    $params['extra_locations'] = $extra_locations;
    $params['is_national'] = $is_national;
    $params['language'] = $language;
    $params['reseller_ref'] = $reseller_ref;
    $params['reseller_agent_id'] = $reseller_agent_id;
    $params['publisher_id'] = $publisher_id;
    return CentralIndex::doCurl("POST","/entity/advertiser/upsell",$params);
  }


  /**
   * Expires an advertiser from and entity
   *
   *  @param entity_id
   *  @param publisher_id
   *  @param reseller_ref
   *  @param reseller_agent_id
   *  @return - the data from the api
  */
  public function postEntityAdvertiserCancel( $entity_id, $publisher_id, $reseller_ref, $reseller_agent_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['publisher_id'] = $publisher_id;
    $params['reseller_ref'] = $reseller_ref;
    $params['reseller_agent_id'] = $reseller_agent_id;
    return CentralIndex::doCurl("POST","/entity/advertiser/cancel",$params);
  }


  /**
   * Renews an advertiser from an entity
   *
   *  @param entity_id
   *  @param expiry_date
   *  @param publisher_id
   *  @param reseller_ref
   *  @param reseller_agent_id
   *  @return - the data from the api
  */
  public function postEntityAdvertiserRenew( $entity_id, $expiry_date, $publisher_id, $reseller_ref, $reseller_agent_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['expiry_date'] = $expiry_date;
    $params['publisher_id'] = $publisher_id;
    $params['reseller_ref'] = $reseller_ref;
    $params['reseller_agent_id'] = $reseller_agent_id;
    return CentralIndex::doCurl("POST","/entity/advertiser/renew",$params);
  }


  /**
   * Allows an advertiser object to be reduced in confidence
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntityAdvertiser( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/advertiser",$params);
  }


  /**
   * Adds/removes locations
   *
   *  @param entity_id
   *  @param gen_id
   *  @param locations_to_add
   *  @param locations_to_remove
   *  @return - the data from the api
  */
  public function postEntityAdvertiserLocation( $entity_id, $gen_id, $locations_to_add, $locations_to_remove) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    $params['locations_to_add'] = $locations_to_add;
    $params['locations_to_remove'] = $locations_to_remove;
    return CentralIndex::doCurl("POST","/entity/advertiser/location",$params);
  }


  /**
   * With a known entity id, an email address object can be added.
   *
   *  @param entity_id
   *  @param email_address
   *  @param email_description
   *  @return - the data from the api
  */
  public function postEntityEmail( $entity_id, $email_address, $email_description) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['email_address'] = $email_address;
    $params['email_description'] = $email_description;
    return CentralIndex::doCurl("POST","/entity/email",$params);
  }


  /**
   * Allows a email object to be reduced in confidence
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntityEmail( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/email",$params);
  }


  /**
   * With a known entity id, a website object can be added.
   *
   *  @param entity_id
   *  @param website_url
   *  @param display_url
   *  @param website_description
   *  @return - the data from the api
  */
  public function postEntityWebsite( $entity_id, $website_url, $display_url, $website_description) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['website_url'] = $website_url;
    $params['display_url'] = $display_url;
    $params['website_description'] = $website_description;
    return CentralIndex::doCurl("POST","/entity/website",$params);
  }


  /**
   * Allows a website object to be reduced in confidence
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntityWebsite( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/website",$params);
  }


  /**
   * With a known entity id, a image object can be added.
   *
   *  @param entity_id
   *  @param filedata
   *  @param image_name
   *  @return - the data from the api
  */
  public function postEntityImage( $entity_id, $filedata, $image_name) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['filedata'] = $filedata;
    $params['image_name'] = $image_name;
    return CentralIndex::doCurl("POST","/entity/image",$params);
  }


  /**
   * Allows a image object to be reduced in confidence
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntityImage( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/image",$params);
  }


  /**
   * Read a location with the supplied ID in the locations reference database.
   *
   *  @param location_id
   *  @return - the data from the api
  */
  public function getLocation( $location_id) {
    $params = array();
    $params['location_id'] = $location_id;
    return CentralIndex::doCurl("GET","/location",$params);
  }


  /**
   * Read multiple locations with the supplied ID in the locations reference database.
   *
   *  @param location_ids
   *  @return - the data from the api
  */
  public function getLocationMultiple( $location_ids) {
    $params = array();
    $params['location_ids'] = $location_ids;
    return CentralIndex::doCurl("GET","/location/multiple",$params);
  }


  /**
   * Create/update a new location entity with the supplied ID in the locations reference database.
   *
   *  @param location_id
   *  @param name
   *  @param formal_name
   *  @param latitude
   *  @param longitude
   *  @param resolution
   *  @param country
   *  @param population
   *  @param description
   *  @param timezone
   *  @param is_duplicate
   *  @param is_default
   *  @param parent_town
   *  @param parent_county
   *  @param parent_province
   *  @param parent_region
   *  @param parent_neighbourhood
   *  @param parent_district
   *  @param postalcode
   *  @return - the data from the api
  */
  public function postLocation( $location_id, $name, $formal_name, $latitude, $longitude, $resolution, $country, $population, $description, $timezone, $is_duplicate, $is_default, $parent_town, $parent_county, $parent_province, $parent_region, $parent_neighbourhood, $parent_district, $postalcode) {
    $params = array();
    $params['location_id'] = $location_id;
    $params['name'] = $name;
    $params['formal_name'] = $formal_name;
    $params['latitude'] = $latitude;
    $params['longitude'] = $longitude;
    $params['resolution'] = $resolution;
    $params['country'] = $country;
    $params['population'] = $population;
    $params['description'] = $description;
    $params['timezone'] = $timezone;
    $params['is_duplicate'] = $is_duplicate;
    $params['is_default'] = $is_default;
    $params['parent_town'] = $parent_town;
    $params['parent_county'] = $parent_county;
    $params['parent_province'] = $parent_province;
    $params['parent_region'] = $parent_region;
    $params['parent_neighbourhood'] = $parent_neighbourhood;
    $params['parent_district'] = $parent_district;
    $params['postalcode'] = $postalcode;
    return CentralIndex::doCurl("POST","/location",$params);
  }


  /**
   * Add a new synonym to a known location
   *
   *  @param location_id
   *  @param synonym
   *  @param language
   *  @return - the data from the api
  */
  public function postLocationSynonym( $location_id, $synonym, $language) {
    $params = array();
    $params['location_id'] = $location_id;
    $params['synonym'] = $synonym;
    $params['language'] = $language;
    return CentralIndex::doCurl("POST","/location/synonym",$params);
  }


  /**
   * Remove a new synonym from a known location
   *
   *  @param location_id
   *  @param synonym
   *  @param language
   *  @return - the data from the api
  */
  public function deleteLocationSynonym( $location_id, $synonym, $language) {
    $params = array();
    $params['location_id'] = $location_id;
    $params['synonym'] = $synonym;
    $params['language'] = $language;
    return CentralIndex::doCurl("DELETE","/location/synonym",$params);
  }


  /**
   * Add a new source to a known location
   *
   *  @param location_id
   *  @param type
   *  @param url
   *  @param ref
   *  @return - the data from the api
  */
  public function postLocationSource( $location_id, $type, $url, $ref) {
    $params = array();
    $params['location_id'] = $location_id;
    $params['type'] = $type;
    $params['url'] = $url;
    $params['ref'] = $ref;
    return CentralIndex::doCurl("POST","/location/source",$params);
  }


  /**
   * With a known entity id, a status object can be updated.
   *
   *  @param entity_id
   *  @param status
   *  @return - the data from the api
  */
  public function postEntityStatus( $entity_id, $status) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['status'] = $status;
    return CentralIndex::doCurl("POST","/entity/status",$params);
  }


  /**
   * With a known entity id, a logo object can be added.
   *
   *  @param entity_id
   *  @param filedata
   *  @param logo_name
   *  @return - the data from the api
  */
  public function postEntityLogo( $entity_id, $filedata, $logo_name) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['filedata'] = $filedata;
    $params['logo_name'] = $logo_name;
    return CentralIndex::doCurl("POST","/entity/logo",$params);
  }


  /**
   * Allows a phone object to be reduced in confidence
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntityLogo( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/logo",$params);
  }


  /**
   * With a known entity id, a YouTube video object can be added.
   *
   *  @param entity_id
   *  @param embed_code
   *  @return - the data from the api
  */
  public function postEntityVideoYoutube( $entity_id, $embed_code) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['embed_code'] = $embed_code;
    return CentralIndex::doCurl("POST","/entity/video/youtube",$params);
  }


  /**
   * Allows a video object to be reduced in confidence
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntityVideo( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/video",$params);
  }


  /**
   * With a known entity id, an affiliate link object can be added.
   *
   *  @param entity_id
   *  @param affiliate_name
   *  @param affiliate_link
   *  @param affiliate_message
   *  @param affiliate_logo
   *  @return - the data from the api
  */
  public function postEntityAffiliate_link( $entity_id, $affiliate_name, $affiliate_link, $affiliate_message, $affiliate_logo) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['affiliate_name'] = $affiliate_name;
    $params['affiliate_link'] = $affiliate_link;
    $params['affiliate_message'] = $affiliate_message;
    $params['affiliate_logo'] = $affiliate_logo;
    return CentralIndex::doCurl("POST","/entity/affiliate_link",$params);
  }


  /**
   * Allows an affiliate link object to be reduced in confidence
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntityAffiliate_link( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/affiliate_link",$params);
  }


  /**
   * With a known entity id, a description object can be added.
   *
   *  @param entity_id
   *  @param headline
   *  @param body
   *  @return - the data from the api
  */
  public function postEntityDescription( $entity_id, $headline, $body) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['headline'] = $headline;
    $params['body'] = $body;
    return CentralIndex::doCurl("POST","/entity/description",$params);
  }


  /**
   * Allows a description object to be reduced in confidence
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntityDescription( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/description",$params);
  }


  /**
   * With a known entity id, a list description object can be added.
   *
   *  @param entity_id
   *  @param headline
   *  @param body
   *  @return - the data from the api
  */
  public function postEntityList( $entity_id, $headline, $body) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['headline'] = $headline;
    $params['body'] = $body;
    return CentralIndex::doCurl("POST","/entity/list",$params);
  }


  /**
   * Allows a list description object to be reduced in confidence
   *
   *  @param gen_id
   *  @param entity_id
   *  @return - the data from the api
  */
  public function deleteEntityList( $gen_id, $entity_id) {
    $params = array();
    $params['gen_id'] = $gen_id;
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("DELETE","/entity/list",$params);
  }


  /**
   * With a known entity id, an document object can be added.
   *
   *  @param entity_id
   *  @param name
   *  @param filedata
   *  @return - the data from the api
  */
  public function postEntityDocument( $entity_id, $name, $filedata) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['name'] = $name;
    $params['filedata'] = $filedata;
    return CentralIndex::doCurl("POST","/entity/document",$params);
  }


  /**
   * Allows a phone object to be reduced in confidence
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntityDocument( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/document",$params);
  }


  /**
   * With a known entity id, a testimonial object can be added.
   *
   *  @param entity_id
   *  @param title
   *  @param text
   *  @param date
   *  @param testifier_name
   *  @return - the data from the api
  */
  public function postEntityTestimonial( $entity_id, $title, $text, $date, $testifier_name) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['title'] = $title;
    $params['text'] = $text;
    $params['date'] = $date;
    $params['testifier_name'] = $testifier_name;
    return CentralIndex::doCurl("POST","/entity/testimonial",$params);
  }


  /**
   * Allows a testimonial object to be reduced in confidence
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntityTestimonial( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/testimonial",$params);
  }


  /**
   * With a known entity id, a opening times object can be added. Each day can be either 'closed' to indicate that the entity is closed that day, '24hour' to indicate that the entity is open all day or single/split time ranges can be supplied in 4-digit 24-hour format, such as '09001730' or '09001200,13001700' to indicate hours of opening.
   *
   *  @param entity_id - The id of the entity to edit
   *  @param monday - e.g. 'closed', '24hour' , '09001730' , '09001200,13001700'
   *  @param tuesday - e.g. 'closed', '24hour' , '09001730' , '09001200,13001700'
   *  @param wednesday - e.g. 'closed', '24hour' , '09001730' , '09001200,13001700'
   *  @param thursday - e.g. 'closed', '24hour' , '09001730' , '09001200,13001700'
   *  @param friday - e.g. 'closed', '24hour' , '09001730' , '09001200,13001700'
   *  @param saturday - e.g. 'closed', '24hour' , '09001730' , '09001200,13001700'
   *  @param sunday - e.g. 'closed', '24hour' , '09001730' , '09001200,13001700'
   *  @param closed - a comma-separated list of dates that the entity is closed e.g. '2013-04-29,2013-05-02'
   *  @param closed_public_holidays - whether the entity is closed on public holidays
   *  @return - the data from the api
  */
  public function postEntityOpening_times( $entity_id, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday, $closed, $closed_public_holidays) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['monday'] = $monday;
    $params['tuesday'] = $tuesday;
    $params['wednesday'] = $wednesday;
    $params['thursday'] = $thursday;
    $params['friday'] = $friday;
    $params['saturday'] = $saturday;
    $params['sunday'] = $sunday;
    $params['closed'] = $closed;
    $params['closed_public_holidays'] = $closed_public_holidays;
    return CentralIndex::doCurl("POST","/entity/opening_times",$params);
  }


  /**
   * With a known entity id, a website object can be added.
   *
   *  @param entity_id
   *  @param title
   *  @param description
   *  @param terms
   *  @param start_date
   *  @param expiry_date
   *  @param url
   *  @param image_url
   *  @return - the data from the api
  */
  public function postEntitySpecial_offer( $entity_id, $title, $description, $terms, $start_date, $expiry_date, $url, $image_url) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['title'] = $title;
    $params['description'] = $description;
    $params['terms'] = $terms;
    $params['start_date'] = $start_date;
    $params['expiry_date'] = $expiry_date;
    $params['url'] = $url;
    $params['image_url'] = $image_url;
    return CentralIndex::doCurl("POST","/entity/special_offer",$params);
  }


  /**
   * Allows a special offer object to be reduced in confidence
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntitySpecial_offer( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/special_offer",$params);
  }


  /**
   * Update user based on email address or social_network/social_network_id
   *
   *  @param email
   *  @param first_name
   *  @param last_name
   *  @param active
   *  @param trust
   *  @param creation_date
   *  @param user_type
   *  @param social_network
   *  @param social_network_id
   *  @param reseller_admin_masheryid
   *  @return - the data from the api
  */
  public function postUser( $email, $first_name, $last_name, $active, $trust, $creation_date, $user_type, $social_network, $social_network_id, $reseller_admin_masheryid) {
    $params = array();
    $params['email'] = $email;
    $params['first_name'] = $first_name;
    $params['last_name'] = $last_name;
    $params['active'] = $active;
    $params['trust'] = $trust;
    $params['creation_date'] = $creation_date;
    $params['user_type'] = $user_type;
    $params['social_network'] = $social_network;
    $params['social_network_id'] = $social_network_id;
    $params['reseller_admin_masheryid'] = $reseller_admin_masheryid;
    return CentralIndex::doCurl("POST","/user",$params);
  }


  /**
   * With a unique email address an user can be retrieved
   *
   *  @param email
   *  @return - the data from the api
  */
  public function getUserBy_email( $email) {
    $params = array();
    $params['email'] = $email;
    return CentralIndex::doCurl("GET","/user/by_email",$params);
  }


  /**
   * With a unique ID address an user can be retrieved
   *
   *  @param user_id
   *  @return - the data from the api
  */
  public function getUser( $user_id) {
    $params = array();
    $params['user_id'] = $user_id;
    return CentralIndex::doCurl("GET","/user",$params);
  }


  /**
   * With a unique ID address an user can be retrieved
   *
   *  @param name
   *  @param id
   *  @return - the data from the api
  */
  public function getUserBy_social_media( $name, $id) {
    $params = array();
    $params['name'] = $name;
    $params['id'] = $id;
    return CentralIndex::doCurl("GET","/user/by_social_media",$params);
  }


  /**
   * Returns all the users that match the supplied reseller_admin_masheryid
   *
   *  @param reseller_admin_masheryid
   *  @return - the data from the api
  */
  public function getUserBy_reseller_admin_masheryid( $reseller_admin_masheryid) {
    $params = array();
    $params['reseller_admin_masheryid'] = $reseller_admin_masheryid;
    return CentralIndex::doCurl("GET","/user/by_reseller_admin_masheryid",$params);
  }


  /**
   * Removes reseller privileges from a specified user
   *
   *  @param user_id
   *  @return - the data from the api
  */
  public function postUserReseller_remove( $user_id) {
    $params = array();
    $params['user_id'] = $user_id;
    return CentralIndex::doCurl("POST","/user/reseller_remove",$params);
  }


  /**
   * The search matches a category name on a given string and language.
   *
   *  @param str - A string to search against, E.g. Plumbers e.g. but
   *  @param language - An ISO compatible language code, E.g. en e.g. en
   *  @return - the data from the api
  */
  public function getAutocompleteCategory( $str, $language) {
    $params = array();
    $params['str'] = $str;
    $params['language'] = $language;
    return CentralIndex::doCurl("GET","/autocomplete/category",$params);
  }


  /**
   * The search matches a category name or synonym on a given string and language.
   *
   *  @param str - A string to search against, E.g. Plumbers e.g. but
   *  @param language - An ISO compatible language code, E.g. en e.g. en
   *  @return - the data from the api
  */
  public function getAutocompleteKeyword( $str, $language) {
    $params = array();
    $params['str'] = $str;
    $params['language'] = $language;
    return CentralIndex::doCurl("GET","/autocomplete/keyword",$params);
  }


  /**
   * The search matches a location name or synonym on a given string and language.
   *
   *  @param str - A string to search against, E.g. Dub e.g. dub
   *  @param country - Which country to return results for. An ISO compatible country code, E.g. ie e.g. ie
   *  @return - the data from the api
  */
  public function getAutocompleteLocation( $str, $country) {
    $params = array();
    $params['str'] = $str;
    $params['country'] = $country;
    return CentralIndex::doCurl("GET","/autocomplete/location",$params);
  }


  /**
   * The search matches a postcode to the supplied string
   *
   *  @param str - A string to search against, E.g. W1 e.g. W1
   *  @param country - Which country to return results for. An ISO compatible country code, E.g. gb e.g. gb
   *  @return - the data from the api
  */
  public function getAutocompletePostcode( $str, $country) {
    $params = array();
    $params['str'] = $str;
    $params['country'] = $country;
    return CentralIndex::doCurl("GET","/autocomplete/postcode",$params);
  }


  /**
   * Create a queue item
   *
   *  @param queue_name
   *  @param data
   *  @return - the data from the api
  */
  public function putQueue( $queue_name, $data) {
    $params = array();
    $params['queue_name'] = $queue_name;
    $params['data'] = $data;
    return CentralIndex::doCurl("PUT","/queue",$params);
  }


  /**
   * With a known queue id, a queue item can be removed.
   *
   *  @param queue_id
   *  @return - the data from the api
  */
  public function deleteQueue( $queue_id) {
    $params = array();
    $params['queue_id'] = $queue_id;
    return CentralIndex::doCurl("DELETE","/queue",$params);
  }


  /**
   * Retrieve queue items.
   *
   *  @param limit
   *  @param queue_name
   *  @return - the data from the api
  */
  public function getQueue( $limit, $queue_name) {
    $params = array();
    $params['limit'] = $limit;
    $params['queue_name'] = $queue_name;
    return CentralIndex::doCurl("GET","/queue",$params);
  }


  /**
   * Unlock queue items.
   *
   *  @param queue_name
   *  @param seconds
   *  @return - the data from the api
  */
  public function postQueueUnlock( $queue_name, $seconds) {
    $params = array();
    $params['queue_name'] = $queue_name;
    $params['seconds'] = $seconds;
    return CentralIndex::doCurl("POST","/queue/unlock",$params);
  }


  /**
   * Add an error to a queue item
   *
   *  @param queue_id
   *  @param error
   *  @return - the data from the api
  */
  public function postQueueError( $queue_id, $error) {
    $params = array();
    $params['queue_id'] = $queue_id;
    $params['error'] = $error;
    return CentralIndex::doCurl("POST","/queue/error",$params);
  }


  /**
   * Find a queue item by its type and id
   *
   *  @param type
   *  @param id
   *  @return - the data from the api
  */
  public function getQueueSearch( $type, $id) {
    $params = array();
    $params['type'] = $type;
    $params['id'] = $id;
    return CentralIndex::doCurl("GET","/queue/search",$params);
  }


  /**
   * Create a new transaction
   *
   *  @param entity_id
   *  @param user_id
   *  @param basket_total
   *  @param basket
   *  @param currency
   *  @param notes
   *  @return - the data from the api
  */
  public function putTransaction( $entity_id, $user_id, $basket_total, $basket, $currency, $notes) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['user_id'] = $user_id;
    $params['basket_total'] = $basket_total;
    $params['basket'] = $basket;
    $params['currency'] = $currency;
    $params['notes'] = $notes;
    return CentralIndex::doCurl("PUT","/transaction",$params);
  }


  /**
   * Set a transactions status to inprogess
   *
   *  @param transaction_id
   *  @param paypal_setexpresscheckout
   *  @return - the data from the api
  */
  public function postTransactionInprogress( $transaction_id, $paypal_setexpresscheckout) {
    $params = array();
    $params['transaction_id'] = $transaction_id;
    $params['paypal_setexpresscheckout'] = $paypal_setexpresscheckout;
    return CentralIndex::doCurl("POST","/transaction/inprogress",$params);
  }


  /**
   * Set a transactions status to authorised
   *
   *  @param transaction_id
   *  @param paypal_getexpresscheckoutdetails
   *  @return - the data from the api
  */
  public function postTransactionAuthorised( $transaction_id, $paypal_getexpresscheckoutdetails) {
    $params = array();
    $params['transaction_id'] = $transaction_id;
    $params['paypal_getexpresscheckoutdetails'] = $paypal_getexpresscheckoutdetails;
    return CentralIndex::doCurl("POST","/transaction/authorised",$params);
  }


  /**
   * Set a transactions status to complete
   *
   *  @param transaction_id
   *  @param paypal_doexpresscheckoutpayment
   *  @param user_id
   *  @param entity_id
   *  @return - the data from the api
  */
  public function postTransactionComplete( $transaction_id, $paypal_doexpresscheckoutpayment, $user_id, $entity_id) {
    $params = array();
    $params['transaction_id'] = $transaction_id;
    $params['paypal_doexpresscheckoutpayment'] = $paypal_doexpresscheckoutpayment;
    $params['user_id'] = $user_id;
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("POST","/transaction/complete",$params);
  }


  /**
   * Set a transactions status to cancelled
   *
   *  @param transaction_id
   *  @return - the data from the api
  */
  public function postTransactionCancelled( $transaction_id) {
    $params = array();
    $params['transaction_id'] = $transaction_id;
    return CentralIndex::doCurl("POST","/transaction/cancelled",$params);
  }


  /**
   * Given a transaction_id retrieve information on it
   *
   *  @param transaction_id
   *  @return - the data from the api
  */
  public function getTransaction( $transaction_id) {
    $params = array();
    $params['transaction_id'] = $transaction_id;
    return CentralIndex::doCurl("GET","/transaction",$params);
  }


  /**
   * Given a transaction_id retrieve information on it
   *
   *  @param paypal_transaction_id
   *  @return - the data from the api
  */
  public function getTransactionBy_paypal_transaction_id( $paypal_transaction_id) {
    $params = array();
    $params['paypal_transaction_id'] = $paypal_transaction_id;
    return CentralIndex::doCurl("GET","/transaction/by_paypal_transaction_id",$params);
  }


  /**
   * Allow an entity to be claimed by a valid user
   *
   *  @param entity_id
   *  @param claimed_user_id
   *  @param claimed_date
   *  @param claim_method
   *  @param phone_number
   *  @return - the data from the api
  */
  public function postEntityClaim( $entity_id, $claimed_user_id, $claimed_date, $claim_method, $phone_number) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['claimed_user_id'] = $claimed_user_id;
    $params['claimed_date'] = $claimed_date;
    $params['claim_method'] = $claim_method;
    $params['phone_number'] = $phone_number;
    return CentralIndex::doCurl("POST","/entity/claim",$params);
  }


  /**
   * Update/Add a publisher
   *
   *  @param publisher_id
   *  @param country
   *  @param name
   *  @param description
   *  @param active
   *  @return - the data from the api
  */
  public function postPublisher( $publisher_id, $country, $name, $description, $active) {
    $params = array();
    $params['publisher_id'] = $publisher_id;
    $params['country'] = $country;
    $params['name'] = $name;
    $params['description'] = $description;
    $params['active'] = $active;
    return CentralIndex::doCurl("POST","/publisher",$params);
  }


  /**
   * Delete a publisher with a specified publisher_id
   *
   *  @param publisher_id
   *  @return - the data from the api
  */
  public function deletePublisher( $publisher_id) {
    $params = array();
    $params['publisher_id'] = $publisher_id;
    return CentralIndex::doCurl("DELETE","/publisher",$params);
  }


  /**
   * Returns publisher that matches a given publisher id
   *
   *  @param publisher_id
   *  @return - the data from the api
  */
  public function getPublisher( $publisher_id) {
    $params = array();
    $params['publisher_id'] = $publisher_id;
    return CentralIndex::doCurl("GET","/publisher",$params);
  }


  /**
   * Returns publisher that matches a given publisher id
   *
   *  @param country
   *  @return - the data from the api
  */
  public function getPublisherByCountry( $country) {
    $params = array();
    $params['country'] = $country;
    return CentralIndex::doCurl("GET","/publisher/byCountry",$params);
  }


  /**
   * Returns publishers that are available for a given entity_id.
   *
   *  @param entity_id
   *  @return - the data from the api
  */
  public function getPublisherByEntityId( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("GET","/publisher/byEntityId",$params);
  }


  /**
   * Update/Add a country
   *
   *  @param country_id
   *  @param name
   *  @param synonyms
   *  @param continentName
   *  @param continent
   *  @param geonameId
   *  @param dbpediaURL
   *  @param freebaseURL
   *  @param population
   *  @param currencyCode
   *  @param languages
   *  @param areaInSqKm
   *  @param capital
   *  @param east
   *  @param west
   *  @param north
   *  @param south
   *  @param claimPrice
   *  @param claimMethods
   *  @return - the data from the api
  */
  public function postCountry( $country_id, $name, $synonyms, $continentName, $continent, $geonameId, $dbpediaURL, $freebaseURL, $population, $currencyCode, $languages, $areaInSqKm, $capital, $east, $west, $north, $south, $claimPrice, $claimMethods) {
    $params = array();
    $params['country_id'] = $country_id;
    $params['name'] = $name;
    $params['synonyms'] = $synonyms;
    $params['continentName'] = $continentName;
    $params['continent'] = $continent;
    $params['geonameId'] = $geonameId;
    $params['dbpediaURL'] = $dbpediaURL;
    $params['freebaseURL'] = $freebaseURL;
    $params['population'] = $population;
    $params['currencyCode'] = $currencyCode;
    $params['languages'] = $languages;
    $params['areaInSqKm'] = $areaInSqKm;
    $params['capital'] = $capital;
    $params['east'] = $east;
    $params['west'] = $west;
    $params['north'] = $north;
    $params['south'] = $south;
    $params['claimPrice'] = $claimPrice;
    $params['claimMethods'] = $claimMethods;
    return CentralIndex::doCurl("POST","/country",$params);
  }


  /**
   * Fetching a country
   *
   *  @param country_id
   *  @return - the data from the api
  */
  public function getCountry( $country_id) {
    $params = array();
    $params['country_id'] = $country_id;
    return CentralIndex::doCurl("GET","/country",$params);
  }


  /**
   * For insance, reporting a phone number as wrong
   *
   *  @param entity_id - A valid entity_id e.g. 379236608286720
   *  @param gen_id - The gen_id for the item being reported
   *  @param signal_type - The signal that is to be reported e.g. wrong
   *  @param data_type - The type of data being reported
   *  @return - the data from the api
  */
  public function postSignal( $entity_id, $gen_id, $signal_type, $data_type) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    $params['signal_type'] = $signal_type;
    $params['data_type'] = $data_type;
    return CentralIndex::doCurl("POST","/signal",$params);
  }


  /**
   * Get the number of times an entity has been served out as an advert or on serps/bdp pages
   *
   *  @param entity_id - A valid entity_id e.g. 379236608286720
   *  @param year - The year to report on
   *  @param month - The month to report on
   *  @return - the data from the api
  */
  public function getStatsEntityBy_date( $entity_id, $year, $month) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['year'] = $year;
    $params['month'] = $month;
    return CentralIndex::doCurl("GET","/stats/entity/by_date",$params);
  }


  /**
   * Update/Add a traction
   *
   *  @param traction_id
   *  @param trigger_type
   *  @param action_type
   *  @param country
   *  @param email_addresses
   *  @param title
   *  @param body
   *  @param api_method
   *  @param api_url
   *  @param api_params
   *  @param active
   *  @return - the data from the api
  */
  public function postTraction( $traction_id, $trigger_type, $action_type, $country, $email_addresses, $title, $body, $api_method, $api_url, $api_params, $active) {
    $params = array();
    $params['traction_id'] = $traction_id;
    $params['trigger_type'] = $trigger_type;
    $params['action_type'] = $action_type;
    $params['country'] = $country;
    $params['email_addresses'] = $email_addresses;
    $params['title'] = $title;
    $params['body'] = $body;
    $params['api_method'] = $api_method;
    $params['api_url'] = $api_url;
    $params['api_params'] = $api_params;
    $params['active'] = $active;
    return CentralIndex::doCurl("POST","/traction",$params);
  }


  /**
   * Fetching a traction
   *
   *  @param traction_id
   *  @return - the data from the api
  */
  public function getTraction( $traction_id) {
    $params = array();
    $params['traction_id'] = $traction_id;
    return CentralIndex::doCurl("GET","/traction",$params);
  }


  /**
   * Fetching active tractions
   *
   *  @return - the data from the api
  */
  public function getTractionActive() {
    $params = array();
    return CentralIndex::doCurl("GET","/traction/active",$params);
  }


  /**
   * Deleting a traction
   *
   *  @param traction_id
   *  @return - the data from the api
  */
  public function deleteTraction( $traction_id) {
    $params = array();
    $params['traction_id'] = $traction_id;
    return CentralIndex::doCurl("DELETE","/traction",$params);
  }


  /**
   * Update/Add a message
   *
   *  @param message_id - Message id to pull
   *  @param ses_id - Aamazon email id
   *  @param from_user_id - User sending the message
   *  @param from_email - Sent from email address
   *  @param to_entity_id - The id of the entity being sent the message
   *  @param to_email - Sent from email address
   *  @param subject - Subject for the message
   *  @param body - Body for the message
   *  @param bounced - If the message bounced
   *  @return - the data from the api
  */
  public function postMessage( $message_id, $ses_id, $from_user_id, $from_email, $to_entity_id, $to_email, $subject, $body, $bounced) {
    $params = array();
    $params['message_id'] = $message_id;
    $params['ses_id'] = $ses_id;
    $params['from_user_id'] = $from_user_id;
    $params['from_email'] = $from_email;
    $params['to_entity_id'] = $to_entity_id;
    $params['to_email'] = $to_email;
    $params['subject'] = $subject;
    $params['body'] = $body;
    $params['bounced'] = $bounced;
    return CentralIndex::doCurl("POST","/message",$params);
  }


  /**
   * Fetching a message
   *
   *  @param message_id - The message id to pull the message for
   *  @return - the data from the api
  */
  public function getMessage( $message_id) {
    $params = array();
    $params['message_id'] = $message_id;
    return CentralIndex::doCurl("GET","/message",$params);
  }


  /**
   * Fetching messages by ses_id
   *
   *  @param ses_id - The amazon id to pull the message for
   *  @return - the data from the api
  */
  public function getMessageBy_ses_id( $ses_id) {
    $params = array();
    $params['ses_id'] = $ses_id;
    return CentralIndex::doCurl("GET","/message/by_ses_id",$params);
  }


  /**
   * Update/Add a flatpack
   *
   *  @param flatpack_id - this record's unique, auto-generated id - if supplied, then this is an edit, otherwise it's an add
   *  @param domainName - the domain name to serve this flatpack site on (no leading http:// or anything please)
   *  @param flatpackName - the name of the Flat pack instance
   *  @param less - the LESS configuration to use to overrides the Bootstrap CSS
   *  @param language - the language in which to render the flatpack site
   *  @param country - the country to use for searches etc
   *  @param mapsType - the type of maps to use
   *  @param mapKey - the nokia map key to use to render maps
   *  @param analyticsHTML - the html to insert to record page views
   *  @param searchFormShowOn - list of pages to show the search form
   *  @param searchFormShowKeywordsBox - whether to display the keywords box on the search form
   *  @param searchFormShowLocationBox - whether to display the location box on search forms - not required
   *  @param searchFormKeywordsAutoComplete - whether to do auto-completion on the keywords box on the search form
   *  @param searchFormLocationsAutoComplete - whether to do auto-completion on the locations box on the search form
   *  @param searchFormDefaultLocation - the string to use as the default location for searches if no location is supplied
   *  @param searchFormPlaceholderKeywords - the string to show in the keyword box as placeholder text e.g. e.g. cafe
   *  @param searchFormPlaceholderLocation - the string to show in the location box as placeholder text e.g. e.g. Dublin
   *  @param searchFormKeywordsLabel - the string to show next to the keywords control e.g. I'm looking for
   *  @param searchFormLocationLabel - the string to show next to the location control e.g. Located in
   *  @param cannedLinksHeader - the string to show above canned searches
   *  @param homepageTitle - the page title of site's home page
   *  @param homepageDescription - the meta description of the home page
   *  @param homepageIntroTitle - the introductory title for the homepage
   *  @param homepageIntroText - the introductory text for the homepage
   *  @param adblockHeader - the html (JS) to render an advert
   *  @param adblock728x90 - the html (JS) to render a 728x90 advert
   *  @param adblock468x60 - the html (JS) to render a 468x60 advert
   *  @param header_menu - the JSON that describes a navigation at the top of the page
   *  @param footer_menu - the JSON that describes a navigation at the bottom of the page
   *  @param bdpTitle - The page title of the entity business profile pages
   *  @param bdpDescription - The meta description of entity business profile pages
   *  @param bdpAds - The block of HTML/JS that renders Ads on BDPs
   *  @param serpTitle - The page title of the serps
   *  @param serpDescription - The meta description of serps
   *  @param serpNumberResults - The number of results per search page
   *  @param serpNumberAdverts - The number of adverts to show on the first search page
   *  @param serpAds - The block of HTML/JS that renders Ads on Serps
   *  @param cookiePolicyUrl - The cookie policy url of the flatpack
   *  @param cookiePolicyNotice - Whether to show the cookie policy on this flatpack
   *  @param addBusinessButtonText - The text used in the 'Add your business' button
   *  @param twitterUrl - Twitter link
   *  @param facebookUrl - Facebook link
   *  @return - the data from the api
  */
  public function postFlatpack( $flatpack_id, $domainName, $flatpackName, $less, $language, $country, $mapsType, $mapKey, $analyticsHTML, $searchFormShowOn, $searchFormShowKeywordsBox, $searchFormShowLocationBox, $searchFormKeywordsAutoComplete, $searchFormLocationsAutoComplete, $searchFormDefaultLocation, $searchFormPlaceholderKeywords, $searchFormPlaceholderLocation, $searchFormKeywordsLabel, $searchFormLocationLabel, $cannedLinksHeader, $homepageTitle, $homepageDescription, $homepageIntroTitle, $homepageIntroText, $adblockHeader, $adblock728x90, $adblock468x60, $header_menu, $footer_menu, $bdpTitle, $bdpDescription, $bdpAds, $serpTitle, $serpDescription, $serpNumberResults, $serpNumberAdverts, $serpAds, $cookiePolicyUrl, $cookiePolicyNotice, $addBusinessButtonText, $twitterUrl, $facebookUrl) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    $params['domainName'] = $domainName;
    $params['flatpackName'] = $flatpackName;
    $params['less'] = $less;
    $params['language'] = $language;
    $params['country'] = $country;
    $params['mapsType'] = $mapsType;
    $params['mapKey'] = $mapKey;
    $params['analyticsHTML'] = $analyticsHTML;
    $params['searchFormShowOn'] = $searchFormShowOn;
    $params['searchFormShowKeywordsBox'] = $searchFormShowKeywordsBox;
    $params['searchFormShowLocationBox'] = $searchFormShowLocationBox;
    $params['searchFormKeywordsAutoComplete'] = $searchFormKeywordsAutoComplete;
    $params['searchFormLocationsAutoComplete'] = $searchFormLocationsAutoComplete;
    $params['searchFormDefaultLocation'] = $searchFormDefaultLocation;
    $params['searchFormPlaceholderKeywords'] = $searchFormPlaceholderKeywords;
    $params['searchFormPlaceholderLocation'] = $searchFormPlaceholderLocation;
    $params['searchFormKeywordsLabel'] = $searchFormKeywordsLabel;
    $params['searchFormLocationLabel'] = $searchFormLocationLabel;
    $params['cannedLinksHeader'] = $cannedLinksHeader;
    $params['homepageTitle'] = $homepageTitle;
    $params['homepageDescription'] = $homepageDescription;
    $params['homepageIntroTitle'] = $homepageIntroTitle;
    $params['homepageIntroText'] = $homepageIntroText;
    $params['adblockHeader'] = $adblockHeader;
    $params['adblock728x90'] = $adblock728x90;
    $params['adblock468x60'] = $adblock468x60;
    $params['header_menu'] = $header_menu;
    $params['footer_menu'] = $footer_menu;
    $params['bdpTitle'] = $bdpTitle;
    $params['bdpDescription'] = $bdpDescription;
    $params['bdpAds'] = $bdpAds;
    $params['serpTitle'] = $serpTitle;
    $params['serpDescription'] = $serpDescription;
    $params['serpNumberResults'] = $serpNumberResults;
    $params['serpNumberAdverts'] = $serpNumberAdverts;
    $params['serpAds'] = $serpAds;
    $params['cookiePolicyUrl'] = $cookiePolicyUrl;
    $params['cookiePolicyNotice'] = $cookiePolicyNotice;
    $params['addBusinessButtonText'] = $addBusinessButtonText;
    $params['twitterUrl'] = $twitterUrl;
    $params['facebookUrl'] = $facebookUrl;
    return CentralIndex::doCurl("POST","/flatpack",$params);
  }


  /**
   * Get a flatpack
   *
   *  @param flatpack_id - the unique id to search for
   *  @return - the data from the api
  */
  public function getFlatpack( $flatpack_id) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    return CentralIndex::doCurl("GET","/flatpack",$params);
  }


  /**
   * Get a flatpack using a domain name
   *
   *  @param domainName - the domain name to search for
   *  @return - the data from the api
  */
  public function getFlatpackBy_domain_name( $domainName) {
    $params = array();
    $params['domainName'] = $domainName;
    return CentralIndex::doCurl("GET","/flatpack/by_domain_name",$params);
  }


  /**
   * Remove a flatpack using a supplied flatpack_id
   *
   *  @param flatpack_id - the id of the flatpack to delete
   *  @return - the data from the api
  */
  public function deleteFlatpack( $flatpack_id) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    return CentralIndex::doCurl("DELETE","/flatpack",$params);
  }


  /**
   * Add a canned link to an existing flatpack site.
   *
   *  @param flatpack_id - the id of the flatpack to delete
   *  @param keywords - the keywords to use in the canned search
   *  @param location - the location to use in the canned search
   *  @param linkText - the link text to be used to in the canned search link
   *  @return - the data from the api
  */
  public function postFlatpackLink( $flatpack_id, $keywords, $location, $linkText) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    $params['keywords'] = $keywords;
    $params['location'] = $location;
    $params['linkText'] = $linkText;
    return CentralIndex::doCurl("POST","/flatpack/link",$params);
  }


  /**
   * Remove a canned link to an existing flatpack site.
   *
   *  @param flatpack_id - the id of the flatpack to delete
   *  @param gen_id - the id of the canned link to remove
   *  @return - the data from the api
  */
  public function deleteFlatpackLink( $flatpack_id, $gen_id) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/flatpack/link",$params);
  }


  /**
   * Upload a logo to serve out with this flatpack
   *
   *  @param flatpack_id - the id of the flatpack to update
   *  @param filedata
   *  @return - the data from the api
  */
  public function postFlatpackLogo( $flatpack_id, $filedata) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    $params['filedata'] = $filedata;
    return CentralIndex::doCurl("POST","/flatpack/logo",$params);
  }


  /**
   * Upload a file to our asset server and return the url
   *
   *  @param filedata
   *  @return - the data from the api
  */
  public function postFlatpackUpload( $filedata) {
    $params = array();
    $params['filedata'] = $filedata;
    return CentralIndex::doCurl("POST","/flatpack/upload",$params);
  }


  /**
   * Upload an icon to serve out with this flatpack
   *
   *  @param flatpack_id - the id of the flatpack to update
   *  @param filedata
   *  @return - the data from the api
  */
  public function postFlatpackIcon( $flatpack_id, $filedata) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    $params['filedata'] = $filedata;
    return CentralIndex::doCurl("POST","/flatpack/icon",$params);
  }


  /**
   * Allows us to identify the user, entity and element from an encoded endpoint URL's token
   *
   *  @param token
   *  @return - the data from the api
  */
  public function getTokenDecode( $token) {
    $params = array();
    $params['token'] = $token;
    return CentralIndex::doCurl("GET","/token/decode",$params);
  }


  /**
   * Provides a tokenised URL to redirect a user so they can add an entity to Central Index
   *
   *  @param language - The language to use to render the add path e.g. en
   *  @param portal_name - The name of the website that data is to be added on e.g. YourLocal
   *  @return - the data from the api
  */
  public function getTokenAdd( $language, $portal_name) {
    $params = array();
    $params['language'] = $language;
    $params['portal_name'] = $portal_name;
    return CentralIndex::doCurl("GET","/token/add",$params);
  }


  /**
   * Provides a tokenised URL to redirect a user to claim an entity on Central Index
   *
   *  @param entity_id - Entity ID to be claimed e.g. 380348266819584
   *  @param language - The language to use to render the claim path e.g. en
   *  @param portal_name - The name of the website that entity is being claimed on e.g. YourLocal
   *  @return - the data from the api
  */
  public function getTokenClaim( $entity_id, $language, $portal_name) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['language'] = $language;
    $params['portal_name'] = $portal_name;
    return CentralIndex::doCurl("GET","/token/claim",$params);
  }


  /**
   * Provides a tokenised URL that allows a user to report incorrect entity information
   *
   *  @param entity_id - The unique Entity ID e.g. 379236608286720
   *  @param portal_name - The name of the portal that the user is coming from e.g. YourLocal
   *  @param language - The language to use to render the report path
   *  @return - the data from the api
  */
  public function getTokenReport( $entity_id, $portal_name, $language) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['portal_name'] = $portal_name;
    $params['language'] = $language;
    return CentralIndex::doCurl("GET","/token/report",$params);
  }


  /**
   * Fetch token for messaging path
   *
   *  @param entity_id - The id of the entity being messaged
   *  @param portal_name - The name of the application that has initiated the email process, example: 'Your Local'
   *  @param language - The language for the app
   *  @return - the data from the api
  */
  public function getTokenMessage( $entity_id, $portal_name, $language) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['portal_name'] = $portal_name;
    $params['language'] = $language;
    return CentralIndex::doCurl("GET","/token/message",$params);
  }


  /**
   * Send an email via amazon
   *
   *  @param to_email_address - The email address to send the email too
   *  @param reply_email_address - The email address to add in the reply to field
   *  @param source_account - The source account to send the email from
   *  @param subject - The subject for the email
   *  @param body - The body for the email
   *  @param html_body - If the body of the email is html
   *  @return - the data from the api
  */
  public function postEmail( $to_email_address, $reply_email_address, $source_account, $subject, $body, $html_body) {
    $params = array();
    $params['to_email_address'] = $to_email_address;
    $params['reply_email_address'] = $reply_email_address;
    $params['source_account'] = $source_account;
    $params['subject'] = $subject;
    $params['body'] = $body;
    $params['html_body'] = $html_body;
    return CentralIndex::doCurl("POST","/email",$params);
  }


  /**
   * Log a sale
   *
   *  @param entity_id - The entity the sale was made against
   *  @param action_type - The type of action we are performing
   *  @param publisher_id - The publisher id that has made the sale
   *  @param mashery_id - The mashery id
   *  @param reseller_ref - The reference of the sale made by the seller
   *  @param reseller_agent_id - The id of the agent selling the product
   *  @param max_tags - The number of tags available to the entity
   *  @param max_locations - The number of locations available to the entity
   *  @param extra_tags - The extra number of tags
   *  @param extra_locations - The extra number of locations
   *  @param expiry_date - The date the product expires
   *  @return - the data from the api
  */
  public function postSales_log( $entity_id, $action_type, $publisher_id, $mashery_id, $reseller_ref, $reseller_agent_id, $max_tags, $max_locations, $extra_tags, $extra_locations, $expiry_date) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['action_type'] = $action_type;
    $params['publisher_id'] = $publisher_id;
    $params['mashery_id'] = $mashery_id;
    $params['reseller_ref'] = $reseller_ref;
    $params['reseller_agent_id'] = $reseller_agent_id;
    $params['max_tags'] = $max_tags;
    $params['max_locations'] = $max_locations;
    $params['extra_tags'] = $extra_tags;
    $params['extra_locations'] = $extra_locations;
    $params['expiry_date'] = $expiry_date;
    return CentralIndex::doCurl("POST","/sales_log",$params);
  }


  /**
   * Return a sales log by id
   *
   *  @param sales_log_id - The sales log id to pull
   *  @return - the data from the api
  */
  public function getSales_log( $sales_log_id) {
    $params = array();
    $params['sales_log_id'] = $sales_log_id;
    return CentralIndex::doCurl("GET","/sales_log",$params);
  }


  /**
   * With a known entity id, a social media object can be added.
   *
   *  @param entity_id
   *  @param type
   *  @param website_url
   *  @return - the data from the api
  */
  public function postEntitySocialmedia( $entity_id, $type, $website_url) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['type'] = $type;
    $params['website_url'] = $website_url;
    return CentralIndex::doCurl("POST","/entity/socialmedia",$params);
  }


  /**
   * Allows a social media object to be reduced in confidence
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntitySocialmedia( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/socialmedia",$params);
  }


  /**
   * With a known entity id, a private object can be added.
   *
   *  @param entity_id - The entity to associate the private object with
   *  @param data - The data to store
   *  @return - the data from the api
  */
  public function putPrivate_object( $entity_id, $data) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['data'] = $data;
    return CentralIndex::doCurl("PUT","/private_object",$params);
  }


  /**
   * Allows a private object to be removed
   *
   *  @param private_object_id - The id of the private object to remove
   *  @return - the data from the api
  */
  public function deletePrivate_object( $private_object_id) {
    $params = array();
    $params['private_object_id'] = $private_object_id;
    return CentralIndex::doCurl("DELETE","/private_object",$params);
  }


  /**
   * Allows a private object to be returned based on the entity_id and masheryid
   *
   *  @param entity_id - The entity associated with the private object
   *  @return - the data from the api
  */
  public function getPrivate_objectAll( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("GET","/private_object/all",$params);
  }


  /**
   * Update/Add a Group
   *
   *  @param group_id
   *  @param name
   *  @param description
   *  @param url
   *  @return - the data from the api
  */
  public function postGroup( $group_id, $name, $description, $url) {
    $params = array();
    $params['group_id'] = $group_id;
    $params['name'] = $name;
    $params['description'] = $description;
    $params['url'] = $url;
    return CentralIndex::doCurl("POST","/group",$params);
  }


  /**
   * Delete a group with a specified group_id
   *
   *  @param group_id
   *  @return - the data from the api
  */
  public function deleteGroup( $group_id) {
    $params = array();
    $params['group_id'] = $group_id;
    return CentralIndex::doCurl("DELETE","/group",$params);
  }


  /**
   * Returns group that matches a given group id
   *
   *  @param group_id
   *  @return - the data from the api
  */
  public function getGroup( $group_id) {
    $params = array();
    $params['group_id'] = $group_id;
    return CentralIndex::doCurl("GET","/group",$params);
  }


  /**
   * With a known entity id, a group  can be added to group members.
   *
   *  @param entity_id
   *  @param group_id
   *  @return - the data from the api
  */
  public function postEntityGroup( $entity_id, $group_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['group_id'] = $group_id;
    return CentralIndex::doCurl("POST","/entity/group",$params);
  }


  /**
   * Allows a group object to be removed from an entities group members
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntityGroup( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/group",$params);
  }



}

?>
