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
   *  @param what
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
   * Supply an entity and an object within it (e.g. a phone number), and retrieve a URL that allows the user to report an issue with that object
   *
   *  @param entity_id - The unique Entity ID e.g. 379236608286720
   *  @param gen_id - A Unique ID for the object you wish to report, E.g. Phone number e.g. 379236608299008
   *  @param language
   *  @return - the data from the api
  */
  public function getEntityReport( $entity_id, $gen_id, $language) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    $params['language'] = $language;
    return CentralIndex::doCurl("GET","/entity/report",$params);
  }


  /**
   * Allows us to identify the user, entity and element from an encoded endpoint URL's token
   *
   *  @param token
   *  @return - the data from the api
  */
  public function getToolsDecodereport( $token) {
    $params = array();
    $params['token'] = $token;
    return CentralIndex::doCurl("GET","/tools/decodereport",$params);
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
   *  @param category_name
   *  @return - the data from the api
  */
  public function putBusiness( $name, $address1, $address2, $address3, $district, $town, $county, $postcode, $country, $latitude, $longitude, $timezone, $telephone_number, $email, $website, $category_id, $category_name) {
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
    $params['category_name'] = $category_name;
    return CentralIndex::doCurl("PUT","/business",$params);
  }


  /**
   * Provides a personalised URL to redirect a user to add an entity to Central Index
   *
   *  @param language - The language to use to render the add path e.g. en
   *  @param portal_name - The name of the website that data is to be added on e.g. YourLocal
   *  @return - the data from the api
  */
  public function getEntityAdd( $language, $portal_name) {
    $params = array();
    $params['language'] = $language;
    $params['portal_name'] = $portal_name;
    return CentralIndex::doCurl("GET","/entity/add",$params);
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
   * With a known entity id, an category object can be added.
   *
   *  @param entity_id
   *  @param category_id
   *  @param category_name
   *  @return - the data from the api
  */
  public function postEntityCategory( $entity_id, $category_id, $category_name) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['category_id'] = $category_id;
    $params['category_name'] = $category_name;
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
   *  @param name_strictness
   *  @param location_strictness
   *  @return - the data from the api
  */
  public function getMatchByphone( $phone, $company_name, $latitude, $longitude, $name_strictness, $location_strictness) {
    $params = array();
    $params['phone'] = $phone;
    $params['company_name'] = $company_name;
    $params['latitude'] = $latitude;
    $params['longitude'] = $longitude;
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
   *  @return - the data from the api
  */
  public function getToolsSpider( $url) {
    $params = array();
    $params['url'] = $url;
    return CentralIndex::doCurl("GET","/tools/spider",$params);
  }


  /**
   * Supply an address to geocode - returns lat/lon and accuracy
   *
   *  @param address
   *  @return - the data from the api
  */
  public function getToolsGeocode( $address) {
    $params = array();
    $params['address'] = $address;
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
   *  @return - the data from the api
  */
  public function postEntityPostal_address( $entity_id, $address1, $address2, $address3, $district, $town, $county, $postcode, $address_type) {
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
    return CentralIndex::doCurl("POST","/entity/postal_address",$params);
  }


  /**
   * With a known entity id, a advertiser is added
   *
   *  @param entity_id
   *  @param tags
   *  @param locations
   *  @param expiry
   *  @param is_national
   *  @param language
   *  @return - the data from the api
  */
  public function postEntityAdvertiser( $entity_id, $tags, $locations, $expiry, $is_national, $language) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['tags'] = $tags;
    $params['locations'] = $locations;
    $params['expiry'] = $expiry;
    $params['is_national'] = $is_national;
    $params['language'] = $language;
    return CentralIndex::doCurl("POST","/entity/advertiser",$params);
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
   *  @return - the data from the api
  */
  public function postLocation( $location_id, $name, $formal_name, $latitude, $longitude, $resolution, $country, $population, $description, $timezone, $is_duplicate, $is_default) {
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
   * With a known entity id, avideo object can be added.
   *
   *  @param entity_id
   *  @param title
   *  @param description
   *  @param thumbnail
   *  @param embed_code
   *  @return - the data from the api
  */
  public function postEntityVideo( $entity_id, $title, $description, $thumbnail, $embed_code) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['title'] = $title;
    $params['description'] = $description;
    $params['thumbnail'] = $thumbnail;
    $params['embed_code'] = $embed_code;
    return CentralIndex::doCurl("POST","/entity/video",$params);
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
   *  @return - the data from the api
  */
  public function postUser( $email, $first_name, $last_name, $active, $trust, $creation_date, $user_type, $social_network, $social_network_id) {
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
   * The search matches a category name or synonym on a given string and language.
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
   *  @return - the data from the api
  */
  public function postEntityClaim( $entity_id, $claimed_user_id, $claimed_date) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['claimed_user_id'] = $claimed_user_id;
    $params['claimed_date'] = $claimed_date;
    return CentralIndex::doCurl("POST","/entity/claim",$params);
  }



}

?>
