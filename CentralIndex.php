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
   * With a 192 id get remote entity data
   *
   *  @param oneninetwo_id
   *  @return - the data from the api
  */
  public function get192Get( $oneninetwo_id) {
    $params = array();
    $params['oneninetwo_id'] = $oneninetwo_id;
    return CentralIndex::doCurl("GET","/192/get",$params);
  }


  /**
   * Get the activity from the collection
   *
   *  @param type - The activity type: add, claim, special offer, image, video, description, testimonial
   *  @param country - The country to filter by
   *  @param latitude_1 - The latitude_1 to filter by
   *  @param longitude_1 - The longitude_1 to filter by
   *  @param latitude_2 - The latitude_2 to filter by
   *  @param longitude_2 - The longitude_2 to filter by
   *  @param number_results - The number_results to filter by
   *  @param unique_action - Return only the most recent instance of this action?
   *  @return - the data from the api
  */
  public function getActivity_stream( $type, $country, $latitude_1, $longitude_1, $latitude_2, $longitude_2, $number_results, $unique_action) {
    $params = array();
    $params['type'] = $type;
    $params['country'] = $country;
    $params['latitude_1'] = $latitude_1;
    $params['longitude_1'] = $longitude_1;
    $params['latitude_2'] = $latitude_2;
    $params['longitude_2'] = $longitude_2;
    $params['number_results'] = $number_results;
    $params['unique_action'] = $unique_action;
    return CentralIndex::doCurl("GET","/activity_stream",$params);
  }


  /**
   * When we get some activity make a record of it
   *
   *  @param entity_id - The entity to pull
   *  @param entity_name - The entity name this entry refers to
   *  @param type - The activity type.
   *  @param country - The country for the activity
   *  @param longitude - The longitude for teh activity
   *  @param latitude - The latitude for teh activity
   *  @return - the data from the api
  */
  public function postActivity_stream( $entity_id, $entity_name, $type, $country, $longitude, $latitude) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['entity_name'] = $entity_name;
    $params['type'] = $type;
    $params['country'] = $country;
    $params['longitude'] = $longitude;
    $params['latitude'] = $latitude;
    return CentralIndex::doCurl("POST","/activity_stream",$params);
  }


  /**
   * Get all entities in which live ads have the matched reseller_masheryid.
   *
   *  @param country
   *  @param reseller_masheryid
   *  @param name_only - If true the query result contains entity name only; otherwise, the entity object.
   *  @param name_match - Filter the result in which the name contains the given text.
   *  @param skip
   *  @param take - Set 0 to get all result.
   *  @return - the data from the api
  */
  public function getAdvertiserBy_reseller_masheryid( $country, $reseller_masheryid, $name_only, $name_match, $skip, $take) {
    $params = array();
    $params['country'] = $country;
    $params['reseller_masheryid'] = $reseller_masheryid;
    $params['name_only'] = $name_only;
    $params['name_match'] = $name_match;
    $params['skip'] = $skip;
    $params['take'] = $take;
    return CentralIndex::doCurl("GET","/advertiser/by_reseller_masheryid",$params);
  }


  /**
   * Get all advertisers that have been updated from a give date for a given reseller
   *
   *  @param from_date
   *  @param country
   *  @return - the data from the api
  */
  public function getAdvertiserUpdated( $from_date, $country) {
    $params = array();
    $params['from_date'] = $from_date;
    $params['country'] = $country;
    return CentralIndex::doCurl("GET","/advertiser/updated",$params);
  }


  /**
   * Get all advertisers that have been updated from a give date for a given publisher
   *
   *  @param publisher_id
   *  @param from_date
   *  @param country
   *  @return - the data from the api
  */
  public function getAdvertiserUpdatedBy_publisher( $publisher_id, $from_date, $country) {
    $params = array();
    $params['publisher_id'] = $publisher_id;
    $params['from_date'] = $from_date;
    $params['country'] = $country;
    return CentralIndex::doCurl("GET","/advertiser/updated/by_publisher",$params);
  }


  /**
   * Check that the advertiser has a premium inventory
   *
   *  @param type
   *  @param category_id - The category of the advertiser
   *  @param location_id - The location of the advertiser
   *  @param publisher_id - The publisher of the advertiser
   *  @return - the data from the api
  */
  public function getAdvertisersPremiumInventorycheck( $type, $category_id, $location_id, $publisher_id) {
    $params = array();
    $params['type'] = $type;
    $params['category_id'] = $category_id;
    $params['location_id'] = $location_id;
    $params['publisher_id'] = $publisher_id;
    return CentralIndex::doCurl("GET","/advertisers/premium/inventorycheck",$params);
  }


  /**
   * Fetch an association
   *
   *  @param association_id
   *  @return - the data from the api
  */
  public function getAssociation( $association_id) {
    $params = array();
    $params['association_id'] = $association_id;
    return CentralIndex::doCurl("GET","/association",$params);
  }


  /**
   * Delete an association
   *
   *  @param association_id
   *  @return - the data from the api
  */
  public function deleteAssociation( $association_id) {
    $params = array();
    $params['association_id'] = $association_id;
    return CentralIndex::doCurl("DELETE","/association",$params);
  }


  /**
   * Will create a new association or update an existing one
   *
   *  @param association_id
   *  @param association_name
   *  @param association_url
   *  @param filedata
   *  @return - the data from the api
  */
  public function postAssociation( $association_id, $association_name, $association_url, $filedata) {
    $params = array();
    $params['association_id'] = $association_id;
    $params['association_name'] = $association_name;
    $params['association_url'] = $association_url;
    $params['filedata'] = $filedata;
    return CentralIndex::doCurl("POST","/association",$params);
  }


  /**
   * The search matches a category name on a given string and language.
   *
   *  @param str - A string to search against, E.g. Plumbers e.g. but
   *  @param language - An ISO compatible language code, E.g. en e.g. en
   *  @param mapped_to_partner - Only return CI categories that have a partner mapping
   *  @return - the data from the api
  */
  public function getAutocompleteCategory( $str, $language, $mapped_to_partner) {
    $params = array();
    $params['str'] = $str;
    $params['language'] = $language;
    $params['mapped_to_partner'] = $mapped_to_partner;
    return CentralIndex::doCurl("GET","/autocomplete/category",$params);
  }


  /**
   * The search matches a category name and ID on a given string and language.
   *
   *  @param str - A string to search against, E.g. Plumbers e.g. but
   *  @param language - An ISO compatible language code, E.g. en e.g. en
   *  @param mapped_to_partner - Only return CI categories that have a partner mapping
   *  @return - the data from the api
  */
  public function getAutocompleteCategoryId( $str, $language, $mapped_to_partner) {
    $params = array();
    $params['str'] = $str;
    $params['language'] = $language;
    $params['mapped_to_partner'] = $mapped_to_partner;
    return CentralIndex::doCurl("GET","/autocomplete/category/id",$params);
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
   *  @param language - An ISO compatible language code, E.g. en e.g. en
   *  @return - the data from the api
  */
  public function getAutocompleteLocation( $str, $country, $language) {
    $params = array();
    $params['str'] = $str;
    $params['country'] = $country;
    $params['language'] = $language;
    return CentralIndex::doCurl("GET","/autocomplete/location",$params);
  }


  /**
   * The search matches a location name or synonym on a given string and language.
   *
   *  @param str - A string to search against, E.g. Middle e.g. dub
   *  @param country - Which country to return results for. An ISO compatible country code, E.g. ie e.g. ie
   *  @param resolution
   *  @return - the data from the api
  */
  public function getAutocompleteLocationBy_resolution( $str, $country, $resolution) {
    $params = array();
    $params['str'] = $str;
    $params['country'] = $country;
    $params['resolution'] = $resolution;
    return CentralIndex::doCurl("GET","/autocomplete/location/by_resolution",$params);
  }


  /**
   * Create a new business entity with all it's objects
   *
   *  @param name
   *  @param building_number
   *  @param branch_name
   *  @param address1
   *  @param address2
   *  @param address3
   *  @param district
   *  @param town
   *  @param county
   *  @param province
   *  @param postcode
   *  @param country
   *  @param latitude
   *  @param longitude
   *  @param timezone
   *  @param telephone_number
   *  @param allow_no_phone
   *  @param additional_telephone_number
   *  @param email
   *  @param website
   *  @param category_id
   *  @param category_type
   *  @param do_not_display
   *  @param referrer_url
   *  @param referrer_name
   *  @param destructive
   *  @param delete_mode - The type of object contribution deletion
   *  @param master_entity_id - The entity you want this data to go to
   *  @return - the data from the api
  */
  public function putBusiness( $name, $building_number, $branch_name, $address1, $address2, $address3, $district, $town, $county, $province, $postcode, $country, $latitude, $longitude, $timezone, $telephone_number, $allow_no_phone, $additional_telephone_number, $email, $website, $category_id, $category_type, $do_not_display, $referrer_url, $referrer_name, $destructive, $delete_mode, $master_entity_id) {
    $params = array();
    $params['name'] = $name;
    $params['building_number'] = $building_number;
    $params['branch_name'] = $branch_name;
    $params['address1'] = $address1;
    $params['address2'] = $address2;
    $params['address3'] = $address3;
    $params['district'] = $district;
    $params['town'] = $town;
    $params['county'] = $county;
    $params['province'] = $province;
    $params['postcode'] = $postcode;
    $params['country'] = $country;
    $params['latitude'] = $latitude;
    $params['longitude'] = $longitude;
    $params['timezone'] = $timezone;
    $params['telephone_number'] = $telephone_number;
    $params['allow_no_phone'] = $allow_no_phone;
    $params['additional_telephone_number'] = $additional_telephone_number;
    $params['email'] = $email;
    $params['website'] = $website;
    $params['category_id'] = $category_id;
    $params['category_type'] = $category_type;
    $params['do_not_display'] = $do_not_display;
    $params['referrer_url'] = $referrer_url;
    $params['referrer_name'] = $referrer_name;
    $params['destructive'] = $destructive;
    $params['delete_mode'] = $delete_mode;
    $params['master_entity_id'] = $master_entity_id;
    return CentralIndex::doCurl("PUT","/business",$params);
  }


  /**
   * Create entity via JSON
   *
   *  @param json - Business JSON
   *  @param country - The country to fetch results for e.g. gb
   *  @param timezone
   *  @param master_entity_id - The entity you want this data to go to
   *  @param queue_priority
   *  @return - the data from the api
  */
  public function putBusinessJson( $json, $country, $timezone, $master_entity_id, $queue_priority) {
    $params = array();
    $params['json'] = $json;
    $params['country'] = $country;
    $params['timezone'] = $timezone;
    $params['master_entity_id'] = $master_entity_id;
    $params['queue_priority'] = $queue_priority;
    return CentralIndex::doCurl("PUT","/business/json",$params);
  }


  /**
   * Create entity via JSON
   *
   *  @param entity_id - The entity to add rich data too
   *  @param json - The rich data to add to the entity
   *  @return - the data from the api
  */
  public function postBusinessJsonProcess( $entity_id, $json) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['json'] = $json;
    return CentralIndex::doCurl("POST","/business/json/process",$params);
  }


  /**
   * Update/Add a Business Tool
   *
   *  @param tool_id
   *  @param country
   *  @param headline
   *  @param description
   *  @param link_url
   *  @param active
   *  @return - the data from the api
  */
  public function postBusiness_tool( $tool_id, $country, $headline, $description, $link_url, $active) {
    $params = array();
    $params['tool_id'] = $tool_id;
    $params['country'] = $country;
    $params['headline'] = $headline;
    $params['description'] = $description;
    $params['link_url'] = $link_url;
    $params['active'] = $active;
    return CentralIndex::doCurl("POST","/business_tool",$params);
  }


  /**
   * Delete a business tool with a specified tool_id
   *
   *  @param tool_id
   *  @return - the data from the api
  */
  public function deleteBusiness_tool( $tool_id) {
    $params = array();
    $params['tool_id'] = $tool_id;
    return CentralIndex::doCurl("DELETE","/business_tool",$params);
  }


  /**
   * Returns business tool that matches a given tool id
   *
   *  @param tool_id
   *  @return - the data from the api
  */
  public function getBusiness_tool( $tool_id) {
    $params = array();
    $params['tool_id'] = $tool_id;
    return CentralIndex::doCurl("GET","/business_tool",$params);
  }


  /**
   * Returns active business tools for a specific masheryid in a given country
   *
   *  @param country
   *  @return - the data from the api
  */
  public function getBusiness_toolBy_masheryid( $country) {
    $params = array();
    $params['country'] = $country;
    return CentralIndex::doCurl("GET","/business_tool/by_masheryid",$params);
  }


  /**
   * Assigns a Business Tool image
   *
   *  @param tool_id
   *  @param filedata
   *  @return - the data from the api
  */
  public function postBusiness_toolImage( $tool_id, $filedata) {
    $params = array();
    $params['tool_id'] = $tool_id;
    $params['filedata'] = $filedata;
    return CentralIndex::doCurl("POST","/business_tool/image",$params);
  }


  /**
   * With a known cache key get the data from cache
   *
   *  @param cache_key
   *  @param use_compression
   *  @return - the data from the api
  */
  public function getCache( $cache_key, $use_compression) {
    $params = array();
    $params['cache_key'] = $cache_key;
    $params['use_compression'] = $use_compression;
    return CentralIndex::doCurl("GET","/cache",$params);
  }


  /**
   * Add some data to the cache with a given expiry
   *
   *  @param cache_key
   *  @param expiry - The cache expiry in seconds
   *  @param data - The data to cache
   *  @param use_compression
   *  @return - the data from the api
  */
  public function postCache( $cache_key, $expiry, $data, $use_compression) {
    $params = array();
    $params['cache_key'] = $cache_key;
    $params['expiry'] = $expiry;
    $params['data'] = $data;
    $params['use_compression'] = $use_compression;
    return CentralIndex::doCurl("POST","/cache",$params);
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
   * Returns all Central Index categories and associated data
   *
   *  @return - the data from the api
  */
  public function getCategoryAll() {
    $params = array();
    return CentralIndex::doCurl("GET","/category/all",$params);
  }


  /**
   * With a known category id, a mapping object can be deleted.
   *
   *  @param category_id
   *  @param category_type
   *  @param mapped_id
   *  @return - the data from the api
  */
  public function deleteCategoryMappings( $category_id, $category_type, $mapped_id) {
    $params = array();
    $params['category_id'] = $category_id;
    $params['category_type'] = $category_type;
    $params['mapped_id'] = $mapped_id;
    return CentralIndex::doCurl("DELETE","/category/mappings",$params);
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
   * Get the contract from the ID supplied
   *
   *  @param contract_id
   *  @return - the data from the api
  */
  public function getContract( $contract_id) {
    $params = array();
    $params['contract_id'] = $contract_id;
    return CentralIndex::doCurl("GET","/contract",$params);
  }


  /**
   * Get a contract from the payment provider id supplied
   *
   *  @param payment_provider
   *  @param payment_provider_id
   *  @return - the data from the api
  */
  public function getContractBy_payment_provider_id( $payment_provider, $payment_provider_id) {
    $params = array();
    $params['payment_provider'] = $payment_provider;
    $params['payment_provider_id'] = $payment_provider_id;
    return CentralIndex::doCurl("GET","/contract/by_payment_provider_id",$params);
  }


  /**
   * Get the active contracts from the ID supplied
   *
   *  @param user_id
   *  @return - the data from the api
  */
  public function getContractBy_user_id( $user_id) {
    $params = array();
    $params['user_id'] = $user_id;
    return CentralIndex::doCurl("GET","/contract/by_user_id",$params);
  }


  /**
   * Cancels an existing contract for a given id
   *
   *  @param contract_id
   *  @return - the data from the api
  */
  public function postContractCancel( $contract_id) {
    $params = array();
    $params['contract_id'] = $contract_id;
    return CentralIndex::doCurl("POST","/contract/cancel",$params);
  }


  /**
   * Creates a new contract for a given entity
   *
   *  @param entity_id
   *  @param user_id
   *  @param payment_provider
   *  @param basket
   *  @param taxrate
   *  @param billing_period
   *  @param source
   *  @param channel
   *  @param campaign
   *  @param referrer_domain
   *  @param referrer_name
   *  @param flatpack_id
   *  @return - the data from the api
  */
  public function postContractCreate( $entity_id, $user_id, $payment_provider, $basket, $taxrate, $billing_period, $source, $channel, $campaign, $referrer_domain, $referrer_name, $flatpack_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['user_id'] = $user_id;
    $params['payment_provider'] = $payment_provider;
    $params['basket'] = $basket;
    $params['taxrate'] = $taxrate;
    $params['billing_period'] = $billing_period;
    $params['source'] = $source;
    $params['channel'] = $channel;
    $params['campaign'] = $campaign;
    $params['referrer_domain'] = $referrer_domain;
    $params['referrer_name'] = $referrer_name;
    $params['flatpack_id'] = $flatpack_id;
    return CentralIndex::doCurl("POST","/contract/create",$params);
  }


  /**
   * Activate a contract that is free
   *
   *  @param contract_id
   *  @param user_name
   *  @param user_surname
   *  @param user_email_address
   *  @return - the data from the api
  */
  public function postContractFreeactivate( $contract_id, $user_name, $user_surname, $user_email_address) {
    $params = array();
    $params['contract_id'] = $contract_id;
    $params['user_name'] = $user_name;
    $params['user_surname'] = $user_surname;
    $params['user_email_address'] = $user_email_address;
    return CentralIndex::doCurl("POST","/contract/freeactivate",$params);
  }


  /**
   * When we failed to receive money add the dates etc to the contract
   *
   *  @param contract_id
   *  @param failure_reason
   *  @param payment_date
   *  @param amount
   *  @param currency
   *  @param response
   *  @return - the data from the api
  */
  public function postContractPaymentFailure( $contract_id, $failure_reason, $payment_date, $amount, $currency, $response) {
    $params = array();
    $params['contract_id'] = $contract_id;
    $params['failure_reason'] = $failure_reason;
    $params['payment_date'] = $payment_date;
    $params['amount'] = $amount;
    $params['currency'] = $currency;
    $params['response'] = $response;
    return CentralIndex::doCurl("POST","/contract/payment/failure",$params);
  }


  /**
   * Adds payment details to a given contract_id
   *
   *  @param contract_id
   *  @param payment_provider_id
   *  @param payment_provider_profile
   *  @param user_name
   *  @param user_surname
   *  @param user_billing_address
   *  @param user_email_address
   *  @return - the data from the api
  */
  public function postContractPaymentSetup( $contract_id, $payment_provider_id, $payment_provider_profile, $user_name, $user_surname, $user_billing_address, $user_email_address) {
    $params = array();
    $params['contract_id'] = $contract_id;
    $params['payment_provider_id'] = $payment_provider_id;
    $params['payment_provider_profile'] = $payment_provider_profile;
    $params['user_name'] = $user_name;
    $params['user_surname'] = $user_surname;
    $params['user_billing_address'] = $user_billing_address;
    $params['user_email_address'] = $user_email_address;
    return CentralIndex::doCurl("POST","/contract/payment/setup",$params);
  }


  /**
   * When we receive money add the dates etc to the contract
   *
   *  @param contract_id
   *  @param payment_date
   *  @param amount
   *  @param currency
   *  @param response
   *  @return - the data from the api
  */
  public function postContractPaymentSuccess( $contract_id, $payment_date, $amount, $currency, $response) {
    $params = array();
    $params['contract_id'] = $contract_id;
    $params['payment_date'] = $payment_date;
    $params['amount'] = $amount;
    $params['currency'] = $currency;
    $params['response'] = $response;
    return CentralIndex::doCurl("POST","/contract/payment/success",$params);
  }


  /**
   * Go through all the products in a contract and provision them
   *
   *  @param contract_id
   *  @return - the data from the api
  */
  public function postContractProvision( $contract_id) {
    $params = array();
    $params['contract_id'] = $contract_id;
    return CentralIndex::doCurl("POST","/contract/provision",$params);
  }


  /**
   * Creates a new contract log for a given contract
   *
   *  @param contract_id
   *  @param date
   *  @param payment_provider
   *  @param response
   *  @param success
   *  @param amount
   *  @param currency
   *  @return - the data from the api
  */
  public function postContract_log( $contract_id, $date, $payment_provider, $response, $success, $amount, $currency) {
    $params = array();
    $params['contract_id'] = $contract_id;
    $params['date'] = $date;
    $params['payment_provider'] = $payment_provider;
    $params['response'] = $response;
    $params['success'] = $success;
    $params['amount'] = $amount;
    $params['currency'] = $currency;
    return CentralIndex::doCurl("POST","/contract_log",$params);
  }


  /**
   * Get the contract log from the ID supplied
   *
   *  @param contract_log_id
   *  @return - the data from the api
  */
  public function getContract_log( $contract_log_id) {
    $params = array();
    $params['contract_log_id'] = $contract_log_id;
    return CentralIndex::doCurl("GET","/contract_log",$params);
  }


  /**
   * Get the contract logs from the ID supplied
   *
   *  @param contract_id
   *  @param page
   *  @param per_page
   *  @return - the data from the api
  */
  public function getContract_logBy_contract_id( $contract_id, $page, $per_page) {
    $params = array();
    $params['contract_id'] = $contract_id;
    $params['page'] = $page;
    $params['per_page'] = $per_page;
    return CentralIndex::doCurl("GET","/contract_log/by_contract_id",$params);
  }


  /**
   * Get the contract logs from the payment_provider supplied
   *
   *  @param payment_provider
   *  @param page
   *  @param per_page
   *  @return - the data from the api
  */
  public function getContract_logBy_payment_provider( $payment_provider, $page, $per_page) {
    $params = array();
    $params['payment_provider'] = $payment_provider;
    $params['page'] = $page;
    $params['per_page'] = $per_page;
    return CentralIndex::doCurl("GET","/contract_log/by_payment_provider",$params);
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
   *  @param claimProductId
   *  @param claimMethods
   *  @param twilio_sms
   *  @param twilio_phone
   *  @param twilio_voice
   *  @param currency_symbol - the symbol of this country's currency
   *  @param currency_symbol_html - the html version of the symbol of this country's currency
   *  @param postcodeLookupActive - Whether the lookup is activated for this country
   *  @param addressFields - Whether fields are activated for this country
   *  @param addressMatching - The configurable matching algorithm
   *  @param dateFormat - The format of the date for this country
   *  @param iso_3166_alpha_3
   *  @param iso_3166_numeric
   *  @return - the data from the api
  */
  public function postCountry( $country_id, $name, $synonyms, $continentName, $continent, $geonameId, $dbpediaURL, $freebaseURL, $population, $currencyCode, $languages, $areaInSqKm, $capital, $east, $west, $north, $south, $claimProductId, $claimMethods, $twilio_sms, $twilio_phone, $twilio_voice, $currency_symbol, $currency_symbol_html, $postcodeLookupActive, $addressFields, $addressMatching, $dateFormat, $iso_3166_alpha_3, $iso_3166_numeric) {
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
    $params['claimProductId'] = $claimProductId;
    $params['claimMethods'] = $claimMethods;
    $params['twilio_sms'] = $twilio_sms;
    $params['twilio_phone'] = $twilio_phone;
    $params['twilio_voice'] = $twilio_voice;
    $params['currency_symbol'] = $currency_symbol;
    $params['currency_symbol_html'] = $currency_symbol_html;
    $params['postcodeLookupActive'] = $postcodeLookupActive;
    $params['addressFields'] = $addressFields;
    $params['addressMatching'] = $addressMatching;
    $params['dateFormat'] = $dateFormat;
    $params['iso_3166_alpha_3'] = $iso_3166_alpha_3;
    $params['iso_3166_numeric'] = $iso_3166_numeric;
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
   * An API call to fetch a crash report by its ID
   *
   *  @param crash_report_id - The crash report to pull
   *  @return - the data from the api
  */
  public function getCrash_report( $crash_report_id) {
    $params = array();
    $params['crash_report_id'] = $crash_report_id;
    return CentralIndex::doCurl("GET","/crash_report",$params);
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
   * Allows a whole entity to be pulled from the datastore by its unique id
   *
   *  @param entity_id - The unique entity ID e.g. 379236608286720
   *  @param domain
   *  @param path
   *  @param data_filter
   *  @param filter_by_confidence
   *  @return - the data from the api
  */
  public function getEntity( $entity_id, $domain, $path, $data_filter, $filter_by_confidence) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['domain'] = $domain;
    $params['path'] = $path;
    $params['data_filter'] = $data_filter;
    $params['filter_by_confidence'] = $filter_by_confidence;
    return CentralIndex::doCurl("GET","/entity",$params);
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
   * With a known entity id, an add can be updated.
   *
   *  @param entity_id
   *  @param add_referrer_url
   *  @param add_referrer_name
   *  @return - the data from the api
  */
  public function postEntityAdd( $entity_id, $add_referrer_url, $add_referrer_name) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['add_referrer_url'] = $add_referrer_url;
    $params['add_referrer_name'] = $add_referrer_name;
    return CentralIndex::doCurl("POST","/entity/add",$params);
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
   * With a known entity id, a premium advertiser is cancelled
   *
   *  @param entity_id
   *  @param publisher_id
   *  @param type
   *  @param category_id - The category of the advertiser
   *  @param location_id - The location of the advertiser
   *  @return - the data from the api
  */
  public function postEntityAdvertiserPremiumCancel( $entity_id, $publisher_id, $type, $category_id, $location_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['publisher_id'] = $publisher_id;
    $params['type'] = $type;
    $params['category_id'] = $category_id;
    $params['location_id'] = $location_id;
    return CentralIndex::doCurl("POST","/entity/advertiser/premium/cancel",$params);
  }


  /**
   * With a known entity id, a premium advertiser is added
   *
   *  @param entity_id
   *  @param type
   *  @param category_id - The category of the advertiser
   *  @param location_id - The location of the advertiser
   *  @param expiry_date
   *  @param reseller_ref
   *  @param reseller_agent_id
   *  @param publisher_id
   *  @return - the data from the api
  */
  public function postEntityAdvertiserPremiumCreate( $entity_id, $type, $category_id, $location_id, $expiry_date, $reseller_ref, $reseller_agent_id, $publisher_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['type'] = $type;
    $params['category_id'] = $category_id;
    $params['location_id'] = $location_id;
    $params['expiry_date'] = $expiry_date;
    $params['reseller_ref'] = $reseller_ref;
    $params['reseller_agent_id'] = $reseller_agent_id;
    $params['publisher_id'] = $publisher_id;
    return CentralIndex::doCurl("POST","/entity/advertiser/premium/create",$params);
  }


  /**
   * Renews an existing premium advertiser in an entity
   *
   *  @param entity_id
   *  @param type
   *  @param category_id - The category of the advertiser
   *  @param location_id - The location of the advertiser
   *  @param expiry_date
   *  @param reseller_ref
   *  @param reseller_agent_id
   *  @param publisher_id
   *  @return - the data from the api
  */
  public function postEntityAdvertiserPremiumRenew( $entity_id, $type, $category_id, $location_id, $expiry_date, $reseller_ref, $reseller_agent_id, $publisher_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['type'] = $type;
    $params['category_id'] = $category_id;
    $params['location_id'] = $location_id;
    $params['expiry_date'] = $expiry_date;
    $params['reseller_ref'] = $reseller_ref;
    $params['reseller_agent_id'] = $reseller_agent_id;
    $params['publisher_id'] = $publisher_id;
    return CentralIndex::doCurl("POST","/entity/advertiser/premium/renew",$params);
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
   * Search for matching entities that are advertisers and return a random selection upto the limit requested
   *
   *  @param tag - The word or words the advertiser is to appear for in searches
   *  @param where - The location to get results for. E.g. Dublin
   *  @param is_national
   *  @param limit - The number of advertisers that are to be returned
   *  @param country - Which country to return results for. An ISO compatible country code, E.g. ie e.g. ie
   *  @param language - An ISO compatible language code, E.g. en
   *  @param benchmark
   *  @return - the data from the api
  */
  public function getEntityAdvertisers( $tag, $where, $is_national, $limit, $country, $language, $benchmark) {
    $params = array();
    $params['tag'] = $tag;
    $params['where'] = $where;
    $params['is_national'] = $is_national;
    $params['limit'] = $limit;
    $params['country'] = $country;
    $params['language'] = $language;
    $params['benchmark'] = $benchmark;
    return CentralIndex::doCurl("GET","/entity/advertisers",$params);
  }


  /**
   * Search for matching entities in a specified location that are advertisers and return a random selection upto the limit requested
   *
   *  @param location - The location to get results for. E.g. Dublin
   *  @param is_national
   *  @param limit - The number of advertisers that are to be returned
   *  @param country - Which country to return results for. An ISO compatible country code, E.g. ie e.g. ie
   *  @param language - An ISO compatible language code, E.g. en
   *  @return - the data from the api
  */
  public function getEntityAdvertisersBy_location( $location, $is_national, $limit, $country, $language) {
    $params = array();
    $params['location'] = $location;
    $params['is_national'] = $is_national;
    $params['limit'] = $limit;
    $params['country'] = $country;
    $params['language'] = $language;
    return CentralIndex::doCurl("GET","/entity/advertisers/by_location",$params);
  }


  /**
   * Check if an entity has an advert from a specified publisher
   *
   *  @param entity_id
   *  @param publisher_id
   *  @return - the data from the api
  */
  public function getEntityAdvertisersInventorycheck( $entity_id, $publisher_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['publisher_id'] = $publisher_id;
    return CentralIndex::doCurl("GET","/entity/advertisers/inventorycheck",$params);
  }


  /**
   * Get advertisers premium
   *
   *  @param what
   *  @param where
   *  @param type
   *  @param country
   *  @param language
   *  @return - the data from the api
  */
  public function getEntityAdvertisersPremium( $what, $where, $type, $country, $language) {
    $params = array();
    $params['what'] = $what;
    $params['where'] = $where;
    $params['type'] = $type;
    $params['country'] = $country;
    $params['language'] = $language;
    return CentralIndex::doCurl("GET","/entity/advertisers/premium",$params);
  }


  /**
   * Adding an affiliate adblock to a known entity
   *
   *  @param entity_id
   *  @param adblock - Number of results returned per page
   *  @return - the data from the api
  */
  public function postEntityAffiliate_adblock( $entity_id, $adblock) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['adblock'] = $adblock;
    return CentralIndex::doCurl("POST","/entity/affiliate_adblock",$params);
  }


  /**
   * Deleteing an affiliate adblock from a known entity
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntityAffiliate_adblock( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/affiliate_adblock",$params);
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
   * With a known entity id, an affiliate link object can be added.
   *
   *  @param entity_id
   *  @param affiliate_name
   *  @param affiliate_link
   *  @param affiliate_message
   *  @param affiliate_logo
   *  @param affiliate_action
   *  @return - the data from the api
  */
  public function postEntityAffiliate_link( $entity_id, $affiliate_name, $affiliate_link, $affiliate_message, $affiliate_logo, $affiliate_action) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['affiliate_name'] = $affiliate_name;
    $params['affiliate_link'] = $affiliate_link;
    $params['affiliate_message'] = $affiliate_message;
    $params['affiliate_logo'] = $affiliate_logo;
    $params['affiliate_action'] = $affiliate_action;
    return CentralIndex::doCurl("POST","/entity/affiliate_link",$params);
  }


  /**
   * Remove an announcement object to an existing entity.
   *
   *  @param entity_id
   *  @param announcement_id
   *  @return - the data from the api
  */
  public function deleteEntityAnnouncement( $entity_id, $announcement_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['announcement_id'] = $announcement_id;
    return CentralIndex::doCurl("DELETE","/entity/announcement",$params);
  }


  /**
   * Fetch an announcement object from an existing entity.
   *
   *  @param entity_id
   *  @param announcement_id
   *  @return - the data from the api
  */
  public function getEntityAnnouncement( $entity_id, $announcement_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['announcement_id'] = $announcement_id;
    return CentralIndex::doCurl("GET","/entity/announcement",$params);
  }


  /**
   * Add an annoucement object to an existing entity.
   *
   *  @param entity_id
   *  @param headline
   *  @param body
   *  @param link_label
   *  @param link
   *  @param terms_link
   *  @param expiry_date
   *  @param media_type
   *  @param youtube_embed_code
   *  @param image_url
   *  @param filedata
   *  @param type - Type of announcement, which affects how it is displayed.
   *  @return - the data from the api
  */
  public function postEntityAnnouncement( $entity_id, $headline, $body, $link_label, $link, $terms_link, $expiry_date, $media_type, $youtube_embed_code, $image_url, $filedata, $type) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['headline'] = $headline;
    $params['body'] = $body;
    $params['link_label'] = $link_label;
    $params['link'] = $link;
    $params['terms_link'] = $terms_link;
    $params['expiry_date'] = $expiry_date;
    $params['media_type'] = $media_type;
    $params['youtube_embed_code'] = $youtube_embed_code;
    $params['image_url'] = $image_url;
    $params['filedata'] = $filedata;
    $params['type'] = $type;
    return CentralIndex::doCurl("POST","/entity/announcement",$params);
  }


  /**
   * Allows a association_membership object to be reduced in confidence
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntityAssociation_membership( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/association_membership",$params);
  }


  /**
   * Will create a new association_membership or update an existing one
   *
   *  @param entity_id
   *  @param association_id
   *  @param association_member_url
   *  @param association_member_id
   *  @return - the data from the api
  */
  public function postEntityAssociation_membership( $entity_id, $association_id, $association_member_url, $association_member_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['association_id'] = $association_id;
    $params['association_member_url'] = $association_member_url;
    $params['association_member_id'] = $association_member_id;
    return CentralIndex::doCurl("POST","/entity/association_membership",$params);
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
   * Uploads a JSON file of known format and bulk inserts into DB
   *
   *  @param data
   *  @param new_entities
   *  @return - the data from the api
  */
  public function postEntityBulkJson( $data, $new_entities) {
    $params = array();
    $params['data'] = $data;
    $params['new_entities'] = $new_entities;
    return CentralIndex::doCurl("POST","/entity/bulk/json",$params);
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
   * Fetches the document that matches the given data_source_type and external_id.
   *
   *  @param data_source_type - The data source type of the entity
   *  @param external_id - The external ID of the entity
   *  @return - the data from the api
  */
  public function getEntityBy_external_id( $data_source_type, $external_id) {
    $params = array();
    $params['data_source_type'] = $data_source_type;
    $params['external_id'] = $external_id;
    return CentralIndex::doCurl("GET","/entity/by_external_id",$params);
  }


  /**
   * Get all entities within a specified group
   *
   *  @param group_id
   *  @return - the data from the api
  */
  public function getEntityBy_groupid( $group_id) {
    $params = array();
    $params['group_id'] = $group_id;
    return CentralIndex::doCurl("GET","/entity/by_groupid",$params);
  }


  /**
   * Fetches the document that matches the given legacy_url
   *
   *  @param legacy_url - The URL of the entity in the directory it was imported from.
   *  @return - the data from the api
  */
  public function getEntityBy_legacy_url( $legacy_url) {
    $params = array();
    $params['legacy_url'] = $legacy_url;
    return CentralIndex::doCurl("GET","/entity/by_legacy_url",$params);
  }


  /**
   * uncontributes a given entities supplier content and makes the entity inactive if the entity is un-usable
   *
   *  @param entity_id - The entity to pull
   *  @param supplier_masheryid - The suppliers masheryid to match
   *  @param supplier_id - The supplier id to match
   *  @param supplier_user_id - The user id to match
   *  @return - the data from the api
  */
  public function deleteEntityBy_supplier( $entity_id, $supplier_masheryid, $supplier_id, $supplier_user_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['supplier_masheryid'] = $supplier_masheryid;
    $params['supplier_id'] = $supplier_id;
    $params['supplier_user_id'] = $supplier_user_id;
    return CentralIndex::doCurl("DELETE","/entity/by_supplier",$params);
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
   * Get all entiies added or claimed by a specific user
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
   * Allow an entity to be claimed by a valid user
   *
   *  @param entity_id
   *  @param claimed_user_id
   *  @param claimed_reseller_id
   *  @param expiry_date
   *  @param claimed_date
   *  @param claim_method
   *  @param phone_number
   *  @param referrer_url
   *  @param referrer_name
   *  @return - the data from the api
  */
  public function postEntityClaim( $entity_id, $claimed_user_id, $claimed_reseller_id, $expiry_date, $claimed_date, $claim_method, $phone_number, $referrer_url, $referrer_name) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['claimed_user_id'] = $claimed_user_id;
    $params['claimed_reseller_id'] = $claimed_reseller_id;
    $params['expiry_date'] = $expiry_date;
    $params['claimed_date'] = $claimed_date;
    $params['claim_method'] = $claim_method;
    $params['phone_number'] = $phone_number;
    $params['referrer_url'] = $referrer_url;
    $params['referrer_name'] = $referrer_name;
    return CentralIndex::doCurl("POST","/entity/claim",$params);
  }


  /**
   * Cancel a claim that is on the entity
   *
   *  @param entity_id
   *  @return - the data from the api
  */
  public function postEntityClaimCancel( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("POST","/entity/claim/cancel",$params);
  }


  /**
   * Allow an entity to be claimed by a valid user
   *
   *  @param entity_id
   *  @param claimed_user_id
   *  @param expiry_date
   *  @return - the data from the api
  */
  public function postEntityClaimRenew( $entity_id, $claimed_user_id, $expiry_date) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['claimed_user_id'] = $claimed_user_id;
    $params['expiry_date'] = $expiry_date;
    return CentralIndex::doCurl("POST","/entity/claim/renew",$params);
  }


  /**
   * Allow an entity to be claimed by a valid reseller
   *
   *  @param entity_id
   *  @return - the data from the api
  */
  public function postEntityClaimReseller( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("POST","/entity/claim/reseller",$params);
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
   * With a known entity id, a description object can be added.
   *
   *  @param entity_id
   *  @param headline
   *  @param body
   *  @param gen_id
   *  @return - the data from the api
  */
  public function postEntityDescription( $entity_id, $headline, $body, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['headline'] = $headline;
    $params['body'] = $body;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("POST","/entity/description",$params);
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
   * Upload a document to an entity
   *
   *  @param entity_id
   *  @param document
   *  @return - the data from the api
  */
  public function postEntityDocumentBy_url( $entity_id, $document) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['document'] = $document;
    return CentralIndex::doCurl("POST","/entity/document/by_url",$params);
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
   * With a known entity id, a featured message can be added
   *
   *  @param entity_id
   *  @param featured_text
   *  @param featured_url
   *  @return - the data from the api
  */
  public function postEntityFeatured_message( $entity_id, $featured_text, $featured_url) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['featured_text'] = $featured_text;
    $params['featured_url'] = $featured_url;
    return CentralIndex::doCurl("POST","/entity/featured_message",$params);
  }


  /**
   * Allows a featured message object to be removed
   *
   *  @param entity_id
   *  @return - the data from the api
  */
  public function deleteEntityFeatured_message( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("DELETE","/entity/featured_message",$params);
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
   * With a known entity id, a image can be retrieved from a url and added.
   *
   *  @param entity_id
   *  @param image_url
   *  @param image_name
   *  @return - the data from the api
  */
  public function postEntityImageBy_url( $entity_id, $image_url, $image_name) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['image_url'] = $image_url;
    $params['image_name'] = $image_name;
    return CentralIndex::doCurl("POST","/entity/image/by_url",$params);
  }


  /**
   * With a known entity id, an invoice_address object can be updated.
   *
   *  @param entity_id
   *  @param building_number
   *  @param address1
   *  @param address2
   *  @param address3
   *  @param district
   *  @param town
   *  @param county
   *  @param province
   *  @param postcode
   *  @param address_type
   *  @return - the data from the api
  */
  public function postEntityInvoice_address( $entity_id, $building_number, $address1, $address2, $address3, $district, $town, $county, $province, $postcode, $address_type) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['building_number'] = $building_number;
    $params['address1'] = $address1;
    $params['address2'] = $address2;
    $params['address3'] = $address3;
    $params['district'] = $district;
    $params['town'] = $town;
    $params['county'] = $county;
    $params['province'] = $province;
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
   * Find all entities in a group
   *
   *  @param group_id - A valid group_id
   *  @param per_page - Number of results returned per page
   *  @param page - Which page number to retrieve
   *  @return - the data from the api
  */
  public function getEntityList_by_group_id( $group_id, $per_page, $page) {
    $params = array();
    $params['group_id'] = $group_id;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    return CentralIndex::doCurl("GET","/entity/list_by_group_id",$params);
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
   * With a known entity id, a logo can be retrieved from a url and added.
   *
   *  @param entity_id
   *  @param logo_url
   *  @param logo_name
   *  @return - the data from the api
  */
  public function postEntityLogoBy_url( $entity_id, $logo_url, $logo_name) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['logo_url'] = $logo_url;
    $params['logo_name'] = $logo_name;
    return CentralIndex::doCurl("POST","/entity/logo/by_url",$params);
  }


  /**
   * Merge two entities into one
   *
   *  @param from
   *  @param to
   *  @param override_trust - Do you want to override the trust of the 'from' entity
   *  @param uncontribute_masheryid - Do we want to uncontribute any data for a masheryid?
   *  @param uncontribute_userid - Do we want to uncontribute any data for a user_id?
   *  @param uncontribute_supplierid - Do we want to uncontribute any data for a supplier_id?
   *  @param delete_mode - The type of object contribution deletion
   *  @return - the data from the api
  */
  public function postEntityMerge( $from, $to, $override_trust, $uncontribute_masheryid, $uncontribute_userid, $uncontribute_supplierid, $delete_mode) {
    $params = array();
    $params['from'] = $from;
    $params['to'] = $to;
    $params['override_trust'] = $override_trust;
    $params['uncontribute_masheryid'] = $uncontribute_masheryid;
    $params['uncontribute_userid'] = $uncontribute_userid;
    $params['uncontribute_supplierid'] = $uncontribute_supplierid;
    $params['delete_mode'] = $delete_mode;
    return CentralIndex::doCurl("POST","/entity/merge",$params);
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
   * With a known entity id, a name can be updated.
   *
   *  @param entity_id
   *  @param name
   *  @param formal_name
   *  @param branch_name
   *  @return - the data from the api
  */
  public function postEntityName( $entity_id, $name, $formal_name, $branch_name) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['name'] = $name;
    $params['formal_name'] = $formal_name;
    $params['branch_name'] = $branch_name;
    return CentralIndex::doCurl("POST","/entity/name",$params);
  }


  /**
   * With a known entity id, a opening times object can be removed.
   *
   *  @param entity_id - The id of the entity to edit
   *  @return - the data from the api
  */
  public function deleteEntityOpening_times( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("DELETE","/entity/opening_times",$params);
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
   * With a known entity id, a payment_type object can be added.
   *
   *  @param entity_id - the id of the entity to add the payment type to
   *  @param payment_type - the payment type to add to the entity
   *  @return - the data from the api
  */
  public function postEntityPayment_type( $entity_id, $payment_type) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['payment_type'] = $payment_type;
    return CentralIndex::doCurl("POST","/entity/payment_type",$params);
  }


  /**
   * Allows a payment_type object to be reduced in confidence
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntityPayment_type( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/payment_type",$params);
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
   * Allows a new phone object to be added to a specified entity. A new object id will be calculated and returned to you if successful.
   *
   *  @param entity_id
   *  @param number
   *  @param description
   *  @param trackable
   *  @return - the data from the api
  */
  public function postEntityPhone( $entity_id, $number, $description, $trackable) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['number'] = $number;
    $params['description'] = $description;
    $params['trackable'] = $trackable;
    return CentralIndex::doCurl("POST","/entity/phone",$params);
  }


  /**
   * Create/Update a postal address
   *
   *  @param entity_id
   *  @param building_number
   *  @param address1
   *  @param address2
   *  @param address3
   *  @param district
   *  @param town
   *  @param county
   *  @param province
   *  @param postcode
   *  @param address_type
   *  @param do_not_display
   *  @return - the data from the api
  */
  public function postEntityPostal_address( $entity_id, $building_number, $address1, $address2, $address3, $district, $town, $county, $province, $postcode, $address_type, $do_not_display) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['building_number'] = $building_number;
    $params['address1'] = $address1;
    $params['address2'] = $address2;
    $params['address3'] = $address3;
    $params['district'] = $district;
    $params['town'] = $town;
    $params['county'] = $county;
    $params['province'] = $province;
    $params['postcode'] = $postcode;
    $params['address_type'] = $address_type;
    $params['do_not_display'] = $do_not_display;
    return CentralIndex::doCurl("POST","/entity/postal_address",$params);
  }


  /**
   * Fetches the documents that match the given masheryid and supplier_id
   *
   *  @param supplier_id - The Supplier ID
   *  @return - the data from the api
  */
  public function getEntityProvisionalBy_supplier_id( $supplier_id) {
    $params = array();
    $params['supplier_id'] = $supplier_id;
    return CentralIndex::doCurl("GET","/entity/provisional/by_supplier_id",$params);
  }


  /**
   * removes a given entities supplier/masheryid/user_id content and makes the entity inactive if the entity is un-usable
   *
   *  @param entity_id - The entity to pull
   *  @param purge_masheryid - The purge masheryid to match
   *  @param purge_supplier_id - The purge supplier id to match
   *  @param purge_user_id - The purge user id to match
   *  @param exclude - List of entity fields that are excluded from the purge
   *  @param destructive
   *  @return - the data from the api
  */
  public function postEntityPurge( $entity_id, $purge_masheryid, $purge_supplier_id, $purge_user_id, $exclude, $destructive) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['purge_masheryid'] = $purge_masheryid;
    $params['purge_supplier_id'] = $purge_supplier_id;
    $params['purge_user_id'] = $purge_user_id;
    $params['exclude'] = $exclude;
    $params['destructive'] = $destructive;
    return CentralIndex::doCurl("POST","/entity/purge",$params);
  }


  /**
   * removes a portion of a given entity and makes the entity inactive if the resulting leftover entity is un-usable
   *
   *  @param entity_id - The entity to pull
   *  @param object
   *  @param gen_id - The gen_id of any multi-object being purged
   *  @param purge_masheryid - The purge masheryid to match
   *  @param purge_supplier_id - The purge supplier id to match
   *  @param purge_user_id - The purge user id to match
   *  @param destructive
   *  @return - the data from the api
  */
  public function postEntityPurgeBy_object( $entity_id, $object, $gen_id, $purge_masheryid, $purge_supplier_id, $purge_user_id, $destructive) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['object'] = $object;
    $params['gen_id'] = $gen_id;
    $params['purge_masheryid'] = $purge_masheryid;
    $params['purge_supplier_id'] = $purge_supplier_id;
    $params['purge_user_id'] = $purge_user_id;
    $params['destructive'] = $destructive;
    return CentralIndex::doCurl("POST","/entity/purge/by_object",$params);
  }


  /**
   * Appends a review to an entity
   *
   *  @param entity_id - the entity to append the review to
   *  @param reviewer_user_id - The user id
   *  @param review_id - The review id. If this is supplied will attempt to update an existing review
   *  @param title - The title of the review
   *  @param content - The full text content of the review
   *  @param star_rating - The rating of the review
   *  @param domain - The domain the review originates from
   *  @return - the data from the api
  */
  public function postEntityReview( $entity_id, $reviewer_user_id, $review_id, $title, $content, $star_rating, $domain) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['reviewer_user_id'] = $reviewer_user_id;
    $params['review_id'] = $review_id;
    $params['title'] = $title;
    $params['content'] = $content;
    $params['star_rating'] = $star_rating;
    $params['domain'] = $domain;
    return CentralIndex::doCurl("POST","/entity/review",$params);
  }


  /**
   * Deletes a specific review for an entity via Review API
   *
   *  @param entity_id - The entity with the review
   *  @param review_id - The review id
   *  @return - the data from the api
  */
  public function deleteEntityReview( $entity_id, $review_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['review_id'] = $review_id;
    return CentralIndex::doCurl("DELETE","/entity/review",$params);
  }


  /**
   * Gets a specific review  for an entity
   *
   *  @param entity_id - The entity with the review
   *  @param review_id - The review id
   *  @return - the data from the api
  */
  public function getEntityReview( $entity_id, $review_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['review_id'] = $review_id;
    return CentralIndex::doCurl("GET","/entity/review",$params);
  }


  /**
   * Gets all reviews for an entity
   *
   *  @param entity_id - The entity with the review
   *  @param per_page - The entity with the review
   *  @param cursor - The entity with the review
   *  @return - the data from the api
  */
  public function getEntityReviewList( $entity_id, $per_page, $cursor) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['per_page'] = $per_page;
    $params['cursor'] = $cursor;
    return CentralIndex::doCurl("GET","/entity/review/list",$params);
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
   *  @param domain
   *  @param path
   *  @param restrict_category_ids - Pipe delimited optional IDs to restrict matches to (optional)
   *  @return - the data from the api
  */
  public function getEntitySearchByboundingbox( $latitude_1, $longitude_1, $latitude_2, $longitude_2, $per_page, $page, $country, $language, $domain, $path, $restrict_category_ids) {
    $params = array();
    $params['latitude_1'] = $latitude_1;
    $params['longitude_1'] = $longitude_1;
    $params['latitude_2'] = $latitude_2;
    $params['longitude_2'] = $longitude_2;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    $params['country'] = $country;
    $params['language'] = $language;
    $params['domain'] = $domain;
    $params['path'] = $path;
    $params['restrict_category_ids'] = $restrict_category_ids;
    return CentralIndex::doCurl("GET","/entity/search/byboundingbox",$params);
  }


  /**
   * Search for matching entities
   *
   *  @param where - Location to search for results. E.g. Dublin e.g. Dublin
   *  @param per_page - How many results per page
   *  @param page - What page number to retrieve
   *  @param country - Which country to return results for. An ISO compatible country code, E.g. ie
   *  @param language - An ISO compatible language code, E.g. en
   *  @param latitude - The decimal latitude of the search context (optional)
   *  @param longitude - The decimal longitude of the search context (optional)
   *  @param domain
   *  @param path
   *  @param restrict_category_ids - Pipe delimited optional IDs to restrict matches to (optional)
   *  @param benchmark
   *  @return - the data from the api
  */
  public function getEntitySearchBylocation( $where, $per_page, $page, $country, $language, $latitude, $longitude, $domain, $path, $restrict_category_ids, $benchmark) {
    $params = array();
    $params['where'] = $where;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    $params['country'] = $country;
    $params['language'] = $language;
    $params['latitude'] = $latitude;
    $params['longitude'] = $longitude;
    $params['domain'] = $domain;
    $params['path'] = $path;
    $params['restrict_category_ids'] = $restrict_category_ids;
    $params['benchmark'] = $benchmark;
    return CentralIndex::doCurl("GET","/entity/search/bylocation",$params);
  }


  /**
   * Search for entities matching the supplied group_id, ordered by nearness
   *
   *  @param group_id - the group_id to search for
   *  @param country - The country to fetch results for e.g. gb
   *  @param per_page - Number of results returned per page
   *  @param page - Which page number to retrieve
   *  @param language - An ISO compatible language code, E.g. en
   *  @param latitude - The decimal latitude of the centre point of the search
   *  @param longitude - The decimal longitude of the centre point of the search
   *  @param where - The location to search for
   *  @param domain
   *  @param path
   *  @param unitOfDistance
   *  @param restrict_category_ids - Pipe delimited optional IDs to restrict matches to (optional)
   *  @return - the data from the api
  */
  public function getEntitySearchGroupBynearest( $group_id, $country, $per_page, $page, $language, $latitude, $longitude, $where, $domain, $path, $unitOfDistance, $restrict_category_ids) {
    $params = array();
    $params['group_id'] = $group_id;
    $params['country'] = $country;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    $params['language'] = $language;
    $params['latitude'] = $latitude;
    $params['longitude'] = $longitude;
    $params['where'] = $where;
    $params['domain'] = $domain;
    $params['path'] = $path;
    $params['unitOfDistance'] = $unitOfDistance;
    $params['restrict_category_ids'] = $restrict_category_ids;
    return CentralIndex::doCurl("GET","/entity/search/group/bynearest",$params);
  }


  /**
   * Search for entities matching the supplied 'who', ordered by nearness. NOTE if you want to see any advertisers then append MASHERYID (even if using API key) and include_ads=true to get your ads matching that keyword and the derived location.
   *
   *  @param keyword - What to get results for. E.g. cafe e.g. cafe
   *  @param country - The country to fetch results for e.g. gb
   *  @param per_page - Number of results returned per page
   *  @param page - Which page number to retrieve
   *  @param language - An ISO compatible language code, E.g. en
   *  @param latitude - The decimal latitude of the centre point of the search
   *  @param longitude - The decimal longitude of the centre point of the search
   *  @param domain
   *  @param path
   *  @param restrict_category_ids - Pipe delimited optional IDs to restrict matches to (optional)
   *  @param include_ads - Find nearby advertisers with tags that match the keyword
   *  @return - the data from the api
  */
  public function getEntitySearchKeywordBynearest( $keyword, $country, $per_page, $page, $language, $latitude, $longitude, $domain, $path, $restrict_category_ids, $include_ads) {
    $params = array();
    $params['keyword'] = $keyword;
    $params['country'] = $country;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    $params['language'] = $language;
    $params['latitude'] = $latitude;
    $params['longitude'] = $longitude;
    $params['domain'] = $domain;
    $params['path'] = $path;
    $params['restrict_category_ids'] = $restrict_category_ids;
    $params['include_ads'] = $include_ads;
    return CentralIndex::doCurl("GET","/entity/search/keyword/bynearest",$params);
  }


  /**
   * Search for matching entities
   *
   *  @param what - What to get results for. E.g. Plumber e.g. plumber
   *  @param per_page - Number of results returned per page
   *  @param page - The page number to retrieve
   *  @param country - Which country to return results for. An ISO compatible country code, E.g. ie e.g. ie
   *  @param language - An ISO compatible language code, E.g. en
   *  @param domain
   *  @param path
   *  @param restrict_category_ids - Pipe delimited optional IDs to restrict matches to (optional)
   *  @param benchmark
   *  @return - the data from the api
  */
  public function getEntitySearchWhat( $what, $per_page, $page, $country, $language, $domain, $path, $restrict_category_ids, $benchmark) {
    $params = array();
    $params['what'] = $what;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    $params['country'] = $country;
    $params['language'] = $language;
    $params['domain'] = $domain;
    $params['path'] = $path;
    $params['restrict_category_ids'] = $restrict_category_ids;
    $params['benchmark'] = $benchmark;
    return CentralIndex::doCurl("GET","/entity/search/what",$params);
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
   *  @param domain
   *  @param path
   *  @param restrict_category_ids - Pipe delimited optional IDs to restrict matches to (optional)
   *  @return - the data from the api
  */
  public function getEntitySearchWhatByboundingbox( $what, $latitude_1, $longitude_1, $latitude_2, $longitude_2, $per_page, $page, $country, $language, $domain, $path, $restrict_category_ids) {
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
    $params['domain'] = $domain;
    $params['path'] = $path;
    $params['restrict_category_ids'] = $restrict_category_ids;
    return CentralIndex::doCurl("GET","/entity/search/what/byboundingbox",$params);
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
   *  @param latitude - The decimal latitude of the search context (optional)
   *  @param longitude - The decimal longitude of the search context (optional)
   *  @param domain
   *  @param path
   *  @param restrict_category_ids - Pipe delimited optional IDs to restrict matches to (optional)
   *  @param benchmark
   *  @return - the data from the api
  */
  public function getEntitySearchWhatBylocation( $what, $where, $per_page, $page, $country, $language, $latitude, $longitude, $domain, $path, $restrict_category_ids, $benchmark) {
    $params = array();
    $params['what'] = $what;
    $params['where'] = $where;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    $params['country'] = $country;
    $params['language'] = $language;
    $params['latitude'] = $latitude;
    $params['longitude'] = $longitude;
    $params['domain'] = $domain;
    $params['path'] = $path;
    $params['restrict_category_ids'] = $restrict_category_ids;
    $params['benchmark'] = $benchmark;
    return CentralIndex::doCurl("GET","/entity/search/what/bylocation",$params);
  }


  /**
   * Search for matching entities, ordered by nearness
   *
   *  @param what - What to get results for. E.g. Plumber e.g. plumber
   *  @param country - The country to fetch results for e.g. gb
   *  @param per_page - Number of results returned per page
   *  @param page - Which page number to retrieve
   *  @param language - An ISO compatible language code, E.g. en
   *  @param latitude - The decimal latitude of the centre point of the search
   *  @param longitude - The decimal longitude of the centre point of the search
   *  @param domain
   *  @param path
   *  @param restrict_category_ids - Pipe delimited optional IDs to restrict matches to (optional)
   *  @return - the data from the api
  */
  public function getEntitySearchWhatBynearest( $what, $country, $per_page, $page, $language, $latitude, $longitude, $domain, $path, $restrict_category_ids) {
    $params = array();
    $params['what'] = $what;
    $params['country'] = $country;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    $params['language'] = $language;
    $params['latitude'] = $latitude;
    $params['longitude'] = $longitude;
    $params['domain'] = $domain;
    $params['path'] = $path;
    $params['restrict_category_ids'] = $restrict_category_ids;
    return CentralIndex::doCurl("GET","/entity/search/what/bynearest",$params);
  }


  /**
   * Search for matching entities
   *
   *  @param who - Company name e.g. Starbucks
   *  @param per_page - How many results per page
   *  @param page - What page number to retrieve
   *  @param country - Which country to return results for. An ISO compatible country code, E.g. ie e.g. ie
   *  @param language - An ISO compatible language code, E.g. en
   *  @param domain
   *  @param path
   *  @param restrict_category_ids - Pipe delimited optional IDs to restrict matches to (optional)
   *  @param benchmark
   *  @return - the data from the api
  */
  public function getEntitySearchWho( $who, $per_page, $page, $country, $language, $domain, $path, $restrict_category_ids, $benchmark) {
    $params = array();
    $params['who'] = $who;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    $params['country'] = $country;
    $params['language'] = $language;
    $params['domain'] = $domain;
    $params['path'] = $path;
    $params['restrict_category_ids'] = $restrict_category_ids;
    $params['benchmark'] = $benchmark;
    return CentralIndex::doCurl("GET","/entity/search/who",$params);
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
   *  @param language - An ISO compatible language code, E.g. en
   *  @param domain
   *  @param path
   *  @param restrict_category_ids - Pipe delimited optional IDs to restrict matches to (optional)
   *  @return - the data from the api
  */
  public function getEntitySearchWhoByboundingbox( $who, $latitude_1, $longitude_1, $latitude_2, $longitude_2, $per_page, $page, $country, $language, $domain, $path, $restrict_category_ids) {
    $params = array();
    $params['who'] = $who;
    $params['latitude_1'] = $latitude_1;
    $params['longitude_1'] = $longitude_1;
    $params['latitude_2'] = $latitude_2;
    $params['longitude_2'] = $longitude_2;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    $params['country'] = $country;
    $params['language'] = $language;
    $params['domain'] = $domain;
    $params['path'] = $path;
    $params['restrict_category_ids'] = $restrict_category_ids;
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
   *  @param latitude - The decimal latitude of the search context (optional)
   *  @param longitude - The decimal longitude of the search context (optional)
   *  @param language - An ISO compatible language code, E.g. en
   *  @param domain
   *  @param path
   *  @param restrict_category_ids - Pipe delimited optional IDs to restrict matches to (optional)
   *  @param benchmark
   *  @return - the data from the api
  */
  public function getEntitySearchWhoBylocation( $who, $where, $per_page, $page, $country, $latitude, $longitude, $language, $domain, $path, $restrict_category_ids, $benchmark) {
    $params = array();
    $params['who'] = $who;
    $params['where'] = $where;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    $params['country'] = $country;
    $params['latitude'] = $latitude;
    $params['longitude'] = $longitude;
    $params['language'] = $language;
    $params['domain'] = $domain;
    $params['path'] = $path;
    $params['restrict_category_ids'] = $restrict_category_ids;
    $params['benchmark'] = $benchmark;
    return CentralIndex::doCurl("GET","/entity/search/who/bylocation",$params);
  }


  /**
   * Search for entities matching the supplied 'who', ordered by nearness
   *
   *  @param who - What to get results for. E.g. Plumber e.g. plumber
   *  @param country - The country to fetch results for e.g. gb
   *  @param per_page - Number of results returned per page
   *  @param page - Which page number to retrieve
   *  @param language - An ISO compatible language code, E.g. en
   *  @param latitude - The decimal latitude of the centre point of the search
   *  @param longitude - The decimal longitude of the centre point of the search
   *  @param domain
   *  @param path
   *  @param restrict_category_ids - Pipe delimited optional IDs to restrict matches to (optional)
   *  @return - the data from the api
  */
  public function getEntitySearchWhoBynearest( $who, $country, $per_page, $page, $language, $latitude, $longitude, $domain, $path, $restrict_category_ids) {
    $params = array();
    $params['who'] = $who;
    $params['country'] = $country;
    $params['per_page'] = $per_page;
    $params['page'] = $page;
    $params['language'] = $language;
    $params['latitude'] = $latitude;
    $params['longitude'] = $longitude;
    $params['domain'] = $domain;
    $params['path'] = $path;
    $params['restrict_category_ids'] = $restrict_category_ids;
    return CentralIndex::doCurl("GET","/entity/search/who/bynearest",$params);
  }


  /**
   * Send an email to an email address specified in an entity
   *
   *  @param entity_id - The entity id of the entity you wish to contact
   *  @param gen_id - The gen_id of the email address you wish to send the message to
   *  @param from_email - The email of the person sending the message 
   *  @param subject - The subject for the email
   *  @param content - The content of the email
   *  @return - the data from the api
  */
  public function postEntitySend_email( $entity_id, $gen_id, $from_email, $subject, $content) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    $params['from_email'] = $from_email;
    $params['subject'] = $subject;
    $params['content'] = $content;
    return CentralIndex::doCurl("POST","/entity/send_email",$params);
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
   * With a known entity id, a website object can be added.
   *
   *  @param entity_id
   *  @param title
   *  @param description
   *  @param terms
   *  @param start_date
   *  @param expiry_date
   *  @param url
   *  @return - the data from the api
  */
  public function postEntitySpecial_offer( $entity_id, $title, $description, $terms, $start_date, $expiry_date, $url) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['title'] = $title;
    $params['description'] = $description;
    $params['terms'] = $terms;
    $params['start_date'] = $start_date;
    $params['expiry_date'] = $expiry_date;
    $params['url'] = $url;
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
   * With a known entity id, a status object can be updated.
   *
   *  @param entity_id
   *  @param status
   *  @param inactive_reason
   *  @param inactive_description
   *  @return - the data from the api
  */
  public function postEntityStatus( $entity_id, $status, $inactive_reason, $inactive_description) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['status'] = $status;
    $params['inactive_reason'] = $inactive_reason;
    $params['inactive_description'] = $inactive_description;
    return CentralIndex::doCurl("POST","/entity/status",$params);
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
   * Get the updates a uncontribute would perform
   *
   *  @param entity_id - The entity to pull
   *  @param object_name - The entity object to update
   *  @param supplier_id - The supplier_id to remove
   *  @param user_id - The user_id to remove
   *  @return - the data from the api
  */
  public function getEntityUncontribute( $entity_id, $object_name, $supplier_id, $user_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['object_name'] = $object_name;
    $params['supplier_id'] = $supplier_id;
    $params['user_id'] = $user_id;
    return CentralIndex::doCurl("GET","/entity/uncontribute",$params);
  }


  /**
   * Separates an entity into two distinct entities 
   *
   *  @param entity_id
   *  @param unmerge_masheryid
   *  @param unmerge_supplier_id
   *  @param unmerge_user_id
   *  @param destructive
   *  @return - the data from the api
  */
  public function postEntityUnmerge( $entity_id, $unmerge_masheryid, $unmerge_supplier_id, $unmerge_user_id, $destructive) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['unmerge_masheryid'] = $unmerge_masheryid;
    $params['unmerge_supplier_id'] = $unmerge_supplier_id;
    $params['unmerge_user_id'] = $unmerge_user_id;
    $params['destructive'] = $destructive;
    return CentralIndex::doCurl("POST","/entity/unmerge",$params);
  }


  /**
   * Find the provided user in all the sub objects and update the trust
   *
   *  @param entity_id - the entity_id to update
   *  @param user_id - the user to search for
   *  @param trust - The new trust for the user
   *  @return - the data from the api
  */
  public function postEntityUser_trust( $entity_id, $user_id, $trust) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['user_id'] = $user_id;
    $params['trust'] = $trust;
    return CentralIndex::doCurl("POST","/entity/user_trust",$params);
  }


  /**
   * Add a verified source object to an existing entity.
   *
   *  @param entity_id
   *  @param public_source
   *  @param source_name
   *  @param source_id
   *  @param source_url
   *  @param source_logo
   *  @param verified_date
   *  @return - the data from the api
  */
  public function postEntityVerified( $entity_id, $public_source, $source_name, $source_id, $source_url, $source_logo, $verified_date) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['public_source'] = $public_source;
    $params['source_name'] = $source_name;
    $params['source_id'] = $source_id;
    $params['source_url'] = $source_url;
    $params['source_logo'] = $source_logo;
    $params['verified_date'] = $verified_date;
    return CentralIndex::doCurl("POST","/entity/verified",$params);
  }


  /**
   * Remove a verified source object to an existing entity.
   *
   *  @param entity_id
   *  @return - the data from the api
  */
  public function deleteEntityVerified( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("DELETE","/entity/verified",$params);
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
   * With a known entity id, a website object can be added.
   *
   *  @param entity_id
   *  @param website_url
   *  @param display_url
   *  @param website_description
   *  @param gen_id
   *  @return - the data from the api
  */
  public function postEntityWebsite( $entity_id, $website_url, $display_url, $website_description, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['website_url'] = $website_url;
    $params['display_url'] = $display_url;
    $params['website_description'] = $website_description;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("POST","/entity/website",$params);
  }


  /**
   * With a known entity id, a yext list can be added
   *
   *  @param entity_id
   *  @param yext_list_id
   *  @param description
   *  @param name
   *  @param type
   *  @return - the data from the api
  */
  public function postEntityYext_list( $entity_id, $yext_list_id, $description, $name, $type) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['yext_list_id'] = $yext_list_id;
    $params['description'] = $description;
    $params['name'] = $name;
    $params['type'] = $type;
    return CentralIndex::doCurl("POST","/entity/yext_list",$params);
  }


  /**
   * Allows a yext list object to be removed
   *
   *  @param entity_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteEntityYext_list( $entity_id, $gen_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/entity/yext_list",$params);
  }


  /**
   * Add an entityserve document
   *
   *  @param entity_id - The ids of the entity/entities to create the entityserve event(s) for
   *  @param country - the ISO code of the country
   *  @param event_type - The event type being recorded
   *  @param domain
   *  @param path
   *  @return - the data from the api
  */
  public function putEntityserve( $entity_id, $country, $event_type, $domain, $path) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['country'] = $country;
    $params['event_type'] = $event_type;
    $params['domain'] = $domain;
    $params['path'] = $path;
    return CentralIndex::doCurl("PUT","/entityserve",$params);
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
   * Update/Add a flatpack
   *
   *  @param flatpack_id - this record's unique, auto-generated id - if supplied, then this is an edit, otherwise it's an add
   *  @param nodefaults - create an flatpack that's empty apart from provided values (used for child flatpacks)
   *  @param domainName - the domain name to serve this flatpack site on (no leading http:// or anything please)
   *  @param inherits - inherit from domain
   *  @param stub - the stub that is appended to the flatpack's url e.g. http://dev.localhost/stub
   *  @param flatpackName - the name of the Flat pack instance
   *  @param less - the LESS configuration to use to overrides the Bootstrap CSS
   *  @param language - the language in which to render the flatpack site
   *  @param country - the country to use for searches etc
   *  @param mapsType - the type of maps to use
   *  @param mapKey - the nokia map key to use to render maps
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
   *  @param head - payload to put in the head of the flatpack
   *  @param adblock - payload to put in the adblock of the flatpack
   *  @param bodyTop - the payload to put in the top of the body of a flatpack
   *  @param bodyBottom - the payload to put in the bottom of the body of a flatpack
   *  @param header_menu - the JSON that describes a navigation at the top of the page
   *  @param header_menu_bottom - the JSON that describes a navigation below the masthead
   *  @param footer_menu - the JSON that describes a navigation at the bottom of the page
   *  @param bdpTitle - The page title of the entity business profile pages
   *  @param bdpDescription - The meta description of entity business profile pages
   *  @param bdpAds - The block of HTML/JS that renders Ads on BDPs
   *  @param serpTitle - The page title of the serps
   *  @param serpDescription - The meta description of serps
   *  @param serpNumberResults - The number of results per search page
   *  @param serpNumberAdverts - The number of adverts to show on the first search page
   *  @param serpAds - The block of HTML/JS that renders Ads on Serps
   *  @param serpAdsBottom - The block of HTML/JS that renders Ads on Serps at the bottom
   *  @param serpTitleNoWhat - The text to display in the title for where only searches
   *  @param serpDescriptionNoWhat - The text to display in the description for where only searches
   *  @param cookiePolicyUrl - The cookie policy url of the flatpack
   *  @param cookiePolicyNotice - Whether to show the cookie policy on this flatpack
   *  @param addBusinessButtonText - The text used in the 'Add your business' button
   *  @param twitterUrl - Twitter link
   *  @param facebookUrl - Facebook link
   *  @param copyright - Copyright message
   *  @param phoneReveal - record phone number reveal
   *  @param loginLinkText - the link text for the Login link
   *  @param contextLocationId - The location ID to use as the context for searches on this flatpack
   *  @param addBusinessButtonPosition - The location ID to use as the context for searches on this flatpack
   *  @param denyIndexing - Whether to noindex a flatpack
   *  @param contextRadius - allows you to set a catchment area around the contextLocationId in miles for use when displaying the activity stream module
   *  @param activityStream - allows you to set the activity to be displayed in the activity stream
   *  @param activityStreamSize - Sets the number of items to show within the activity stream.
   *  @param products - A Collection of Central Index products the flatpack is allowed to sell
   *  @param linkToRoot - The root domain name to serve this flatpack site on (no leading http:// or anything please)
   *  @param termsLink - A URL for t's and c's specific to this partner
   *  @param serpNumberEmbedAdverts - The number of embed adverts per search
   *  @param serpEmbedTitle - Custom page title for emdedded searches
   *  @param adminLess - the LESS configuration to use to overrides the Bootstrap CSS for the admin on themed domains
   *  @param adminConfNoLocz - operate without recourse to verified location data (locz)
   *  @param adminConfNoSocialLogin - suppress social media login interface
   *  @param adminConfEasyClaim - captcha only for claim
   *  @param adminConfPaymentMode - payment gateway
   *  @param adminConfEnableProducts - show upgrade on claim
   *  @param adminConfSimpleadmin - render a template for the reduced functionality
   *  @return - the data from the api
  */
  public function postFlatpack( $flatpack_id, $nodefaults, $domainName, $inherits, $stub, $flatpackName, $less, $language, $country, $mapsType, $mapKey, $searchFormShowOn, $searchFormShowKeywordsBox, $searchFormShowLocationBox, $searchFormKeywordsAutoComplete, $searchFormLocationsAutoComplete, $searchFormDefaultLocation, $searchFormPlaceholderKeywords, $searchFormPlaceholderLocation, $searchFormKeywordsLabel, $searchFormLocationLabel, $cannedLinksHeader, $homepageTitle, $homepageDescription, $homepageIntroTitle, $homepageIntroText, $head, $adblock, $bodyTop, $bodyBottom, $header_menu, $header_menu_bottom, $footer_menu, $bdpTitle, $bdpDescription, $bdpAds, $serpTitle, $serpDescription, $serpNumberResults, $serpNumberAdverts, $serpAds, $serpAdsBottom, $serpTitleNoWhat, $serpDescriptionNoWhat, $cookiePolicyUrl, $cookiePolicyNotice, $addBusinessButtonText, $twitterUrl, $facebookUrl, $copyright, $phoneReveal, $loginLinkText, $contextLocationId, $addBusinessButtonPosition, $denyIndexing, $contextRadius, $activityStream, $activityStreamSize, $products, $linkToRoot, $termsLink, $serpNumberEmbedAdverts, $serpEmbedTitle, $adminLess, $adminConfNoLocz, $adminConfNoSocialLogin, $adminConfEasyClaim, $adminConfPaymentMode, $adminConfEnableProducts, $adminConfSimpleadmin) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    $params['nodefaults'] = $nodefaults;
    $params['domainName'] = $domainName;
    $params['inherits'] = $inherits;
    $params['stub'] = $stub;
    $params['flatpackName'] = $flatpackName;
    $params['less'] = $less;
    $params['language'] = $language;
    $params['country'] = $country;
    $params['mapsType'] = $mapsType;
    $params['mapKey'] = $mapKey;
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
    $params['head'] = $head;
    $params['adblock'] = $adblock;
    $params['bodyTop'] = $bodyTop;
    $params['bodyBottom'] = $bodyBottom;
    $params['header_menu'] = $header_menu;
    $params['header_menu_bottom'] = $header_menu_bottom;
    $params['footer_menu'] = $footer_menu;
    $params['bdpTitle'] = $bdpTitle;
    $params['bdpDescription'] = $bdpDescription;
    $params['bdpAds'] = $bdpAds;
    $params['serpTitle'] = $serpTitle;
    $params['serpDescription'] = $serpDescription;
    $params['serpNumberResults'] = $serpNumberResults;
    $params['serpNumberAdverts'] = $serpNumberAdverts;
    $params['serpAds'] = $serpAds;
    $params['serpAdsBottom'] = $serpAdsBottom;
    $params['serpTitleNoWhat'] = $serpTitleNoWhat;
    $params['serpDescriptionNoWhat'] = $serpDescriptionNoWhat;
    $params['cookiePolicyUrl'] = $cookiePolicyUrl;
    $params['cookiePolicyNotice'] = $cookiePolicyNotice;
    $params['addBusinessButtonText'] = $addBusinessButtonText;
    $params['twitterUrl'] = $twitterUrl;
    $params['facebookUrl'] = $facebookUrl;
    $params['copyright'] = $copyright;
    $params['phoneReveal'] = $phoneReveal;
    $params['loginLinkText'] = $loginLinkText;
    $params['contextLocationId'] = $contextLocationId;
    $params['addBusinessButtonPosition'] = $addBusinessButtonPosition;
    $params['denyIndexing'] = $denyIndexing;
    $params['contextRadius'] = $contextRadius;
    $params['activityStream'] = $activityStream;
    $params['activityStreamSize'] = $activityStreamSize;
    $params['products'] = $products;
    $params['linkToRoot'] = $linkToRoot;
    $params['termsLink'] = $termsLink;
    $params['serpNumberEmbedAdverts'] = $serpNumberEmbedAdverts;
    $params['serpEmbedTitle'] = $serpEmbedTitle;
    $params['adminLess'] = $adminLess;
    $params['adminConfNoLocz'] = $adminConfNoLocz;
    $params['adminConfNoSocialLogin'] = $adminConfNoSocialLogin;
    $params['adminConfEasyClaim'] = $adminConfEasyClaim;
    $params['adminConfPaymentMode'] = $adminConfPaymentMode;
    $params['adminConfEnableProducts'] = $adminConfEnableProducts;
    $params['adminConfSimpleadmin'] = $adminConfSimpleadmin;
    return CentralIndex::doCurl("POST","/flatpack",$params);
  }


  /**
   * Upload a CSS file for the Admin for this flatpack
   *
   *  @param flatpack_id - the id of the flatpack to update
   *  @param filedata
   *  @return - the data from the api
  */
  public function postFlatpackAdminCSS( $flatpack_id, $filedata) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    $params['filedata'] = $filedata;
    return CentralIndex::doCurl("POST","/flatpack/adminCSS",$params);
  }


  /**
   * Add a HD Admin logo to a flatpack domain
   *
   *  @param flatpack_id - the unique id to search for
   *  @param filedata
   *  @return - the data from the api
  */
  public function postFlatpackAdminHDLogo( $flatpack_id, $filedata) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    $params['filedata'] = $filedata;
    return CentralIndex::doCurl("POST","/flatpack/adminHDLogo",$params);
  }


  /**
   * Upload an image to serve out as the large logo in the Admin for this flatpack
   *
   *  @param flatpack_id - the id of the flatpack to update
   *  @param filedata
   *  @return - the data from the api
  */
  public function postFlatpackAdminLargeLogo( $flatpack_id, $filedata) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    $params['filedata'] = $filedata;
    return CentralIndex::doCurl("POST","/flatpack/adminLargeLogo",$params);
  }


  /**
   * Upload an image to serve out as the small logo in the Admin for this flatpack
   *
   *  @param flatpack_id - the id of the flatpack to update
   *  @param filedata
   *  @return - the data from the api
  */
  public function postFlatpackAdminSmallLogo( $flatpack_id, $filedata) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    $params['filedata'] = $filedata;
    return CentralIndex::doCurl("POST","/flatpack/adminSmallLogo",$params);
  }


  /**
   * Remove a flatpack using a supplied flatpack_id
   *
   *  @param flatpack_id - the unique id to search for
   *  @param adblock
   *  @param serpAds
   *  @param serpAdsBottom
   *  @param bdpAds
   *  @return - the data from the api
  */
  public function deleteFlatpackAds( $flatpack_id, $adblock, $serpAds, $serpAdsBottom, $bdpAds) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    $params['adblock'] = $adblock;
    $params['serpAds'] = $serpAds;
    $params['serpAdsBottom'] = $serpAdsBottom;
    $params['bdpAds'] = $bdpAds;
    return CentralIndex::doCurl("DELETE","/flatpack/ads",$params);
  }


  /**
   * Generate flatpacks based on the files passed in
   *
   *  @param json - The flatpack JSON to make replacements on
   *  @param filedata - a file that contains the replacements in the JSON
   *  @param slack_user
   *  @return - the data from the api
  */
  public function postFlatpackBulkJson( $json, $filedata, $slack_user) {
    $params = array();
    $params['json'] = $json;
    $params['filedata'] = $filedata;
    $params['slack_user'] = $slack_user;
    return CentralIndex::doCurl("POST","/flatpack/bulk/json",$params);
  }


  /**
   * Get flatpacks by country and location
   *
   *  @param country
   *  @param latitude
   *  @param longitude
   *  @return - the data from the api
  */
  public function getFlatpackBy_country( $country, $latitude, $longitude) {
    $params = array();
    $params['country'] = $country;
    $params['latitude'] = $latitude;
    $params['longitude'] = $longitude;
    return CentralIndex::doCurl("GET","/flatpack/by_country",$params);
  }


  /**
   * Get flatpacks by country in KML format
   *
   *  @param country
   *  @return - the data from the api
  */
  public function getFlatpackBy_countryKml( $country) {
    $params = array();
    $params['country'] = $country;
    return CentralIndex::doCurl("GET","/flatpack/by_country/kml",$params);
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
   * Get flatpacks that match the supplied masheryid
   *
   *  @return - the data from the api
  */
  public function getFlatpackBy_masheryid() {
    $params = array();
    return CentralIndex::doCurl("GET","/flatpack/by_masheryid",$params);
  }


  /**
   * Clone an existing flatpack
   *
   *  @param flatpack_id - the flatpack_id to clone
   *  @param domainName - the domain of the new flatpack site (no leading http:// or anything please)
   *  @return - the data from the api
  */
  public function getFlatpackClone( $flatpack_id, $domainName) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    $params['domainName'] = $domainName;
    return CentralIndex::doCurl("GET","/flatpack/clone",$params);
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
   * Get a flatpack using a domain name
   *
   *  @param flatpack_id - the id to search for
   *  @return - the data from the api
  */
  public function getFlatpackInherit( $flatpack_id) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    return CentralIndex::doCurl("GET","/flatpack/inherit",$params);
  }


  /**
   * returns the LESS theme from a flatpack
   *
   *  @param flatpack_id - the unique id to search for
   *  @return - the data from the api
  */
  public function getFlatpackLess( $flatpack_id) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    return CentralIndex::doCurl("GET","/flatpack/less",$params);
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
   * Remove all canned links from an existing flatpack.
   *
   *  @param flatpack_id - the id of the flatpack to remove links from
   *  @return - the data from the api
  */
  public function deleteFlatpackLinkAll( $flatpack_id) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    return CentralIndex::doCurl("DELETE","/flatpack/link/all",$params);
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
   * Add a hd logo to a flatpack domain
   *
   *  @param flatpack_id - the unique id to search for
   *  @param filedata
   *  @return - the data from the api
  */
  public function postFlatpackLogoHd( $flatpack_id, $filedata) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    $params['filedata'] = $filedata;
    return CentralIndex::doCurl("POST","/flatpack/logo/hd",$params);
  }


  /**
   * Add a Redirect link to a flatpack
   *
   *  @param flatpack_id - the unique id to search for
   *  @param redirectTo
   *  @return - the data from the api
  */
  public function postFlatpackRedirect( $flatpack_id, $redirectTo) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    $params['redirectTo'] = $redirectTo;
    return CentralIndex::doCurl("POST","/flatpack/redirect",$params);
  }


  /**
   * Delete a Redirect link from a flatpack
   *
   *  @param flatpack_id - the unique id to search for
   *  @return - the data from the api
  */
  public function deleteFlatpackRedirect( $flatpack_id) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    return CentralIndex::doCurl("DELETE","/flatpack/redirect",$params);
  }


  /**
   * Upload a TXT file to act as the sitemap for this flatpack
   *
   *  @param flatpack_id - the id of the flatpack to update
   *  @param filedata
   *  @return - the data from the api
  */
  public function postFlatpackSitemap( $flatpack_id, $filedata) {
    $params = array();
    $params['flatpack_id'] = $flatpack_id;
    $params['filedata'] = $filedata;
    return CentralIndex::doCurl("POST","/flatpack/sitemap",$params);
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
   * Update/Add a Group
   *
   *  @param group_id
   *  @param name
   *  @param description
   *  @param url
   *  @param stamp_user_id
   *  @param stamp_sql
   *  @return - the data from the api
  */
  public function postGroup( $group_id, $name, $description, $url, $stamp_user_id, $stamp_sql) {
    $params = array();
    $params['group_id'] = $group_id;
    $params['name'] = $name;
    $params['description'] = $description;
    $params['url'] = $url;
    $params['stamp_user_id'] = $stamp_user_id;
    $params['stamp_sql'] = $stamp_sql;
    return CentralIndex::doCurl("POST","/group",$params);
  }


  /**
   * Returns all groups
   *
   *  @return - the data from the api
  */
  public function getGroupAll() {
    $params = array();
    return CentralIndex::doCurl("GET","/group/all",$params);
  }


  /**
   * Bulk delete entities from a specified group
   *
   *  @param group_id
   *  @param filedata
   *  @return - the data from the api
  */
  public function postGroupBulk_delete( $group_id, $filedata) {
    $params = array();
    $params['group_id'] = $group_id;
    $params['filedata'] = $filedata;
    return CentralIndex::doCurl("POST","/group/bulk_delete",$params);
  }


  /**
   * Bulk ingest entities into a specified group
   *
   *  @param group_id
   *  @param filedata
   *  @param category_type
   *  @return - the data from the api
  */
  public function postGroupBulk_ingest( $group_id, $filedata, $category_type) {
    $params = array();
    $params['group_id'] = $group_id;
    $params['filedata'] = $filedata;
    $params['category_type'] = $category_type;
    return CentralIndex::doCurl("POST","/group/bulk_ingest",$params);
  }


  /**
   * Bulk update entities with a specified group
   *
   *  @param group_id
   *  @param data
   *  @return - the data from the api
  */
  public function postGroupBulk_update( $group_id, $data) {
    $params = array();
    $params['group_id'] = $group_id;
    $params['data'] = $data;
    return CentralIndex::doCurl("POST","/group/bulk_update",$params);
  }


  /**
   * Get number of claims today
   *
   *  @param from_date
   *  @param to_date
   *  @param country_id
   *  @return - the data from the api
  */
  public function getHeartbeatBy_date( $from_date, $to_date, $country_id) {
    $params = array();
    $params['from_date'] = $from_date;
    $params['to_date'] = $to_date;
    $params['country_id'] = $country_id;
    return CentralIndex::doCurl("GET","/heartbeat/by_date",$params);
  }


  /**
   * Get number of claims today
   *
   *  @param country
   *  @param claim_type
   *  @return - the data from the api
  */
  public function getHeartbeatTodayClaims( $country, $claim_type) {
    $params = array();
    $params['country'] = $country;
    $params['claim_type'] = $claim_type;
    return CentralIndex::doCurl("GET","/heartbeat/today/claims",$params);
  }


  /**
   * Process a bulk file
   *
   *  @param job_id
   *  @param filedata - A tab separated file for ingest
   *  @return - the data from the api
  */
  public function postIngest_file( $job_id, $filedata) {
    $params = array();
    $params['job_id'] = $job_id;
    $params['filedata'] = $filedata;
    return CentralIndex::doCurl("POST","/ingest_file",$params);
  }


  /**
   * Get an ingest job from the collection
   *
   *  @param job_id
   *  @return - the data from the api
  */
  public function getIngest_job( $job_id) {
    $params = array();
    $params['job_id'] = $job_id;
    return CentralIndex::doCurl("GET","/ingest_job",$params);
  }


  /**
   * Add a ingest job to the collection
   *
   *  @param description
   *  @param category_type
   *  @return - the data from the api
  */
  public function postIngest_job( $description, $category_type) {
    $params = array();
    $params['description'] = $description;
    $params['category_type'] = $category_type;
    return CentralIndex::doCurl("POST","/ingest_job",$params);
  }


  /**
   * Get an ingest log from the collection
   *
   *  @param job_id
   *  @param success
   *  @param errors
   *  @param limit
   *  @param skip
   *  @return - the data from the api
  */
  public function getIngest_logBy_job_id( $job_id, $success, $errors, $limit, $skip) {
    $params = array();
    $params['job_id'] = $job_id;
    $params['success'] = $success;
    $params['errors'] = $errors;
    $params['limit'] = $limit;
    $params['skip'] = $skip;
    return CentralIndex::doCurl("GET","/ingest_log/by_job_id",$params);
  }


  /**
   * Check the status of the Ingest queue, and potentially flush it
   *
   *  @param flush
   *  @return - the data from the api
  */
  public function getIngest_queue( $flush) {
    $params = array();
    $params['flush'] = $flush;
    return CentralIndex::doCurl("GET","/ingest_queue",$params);
  }


  /**
   * Returns entities that do not have claim or advertisers data
   *
   *  @param country_id - Which country to return results for. An ISO compatible country code, E.g. ie e.g. ie
   *  @param from_date
   *  @param to_date
   *  @param limit
   *  @param offset
   *  @return - the data from the api
  */
  public function getLeadsAdded( $country_id, $from_date, $to_date, $limit, $offset) {
    $params = array();
    $params['country_id'] = $country_id;
    $params['from_date'] = $from_date;
    $params['to_date'] = $to_date;
    $params['limit'] = $limit;
    $params['offset'] = $offset;
    return CentralIndex::doCurl("GET","/leads/added",$params);
  }


  /**
   * Returns entities that have advertisers data
   *
   *  @param country_id - Which country to return results for. An ISO compatible country code, E.g. ie e.g. ie
   *  @param from_date
   *  @param to_date
   *  @param limit
   *  @param offset
   *  @return - the data from the api
  */
  public function getLeadsAdvertisers( $country_id, $from_date, $to_date, $limit, $offset) {
    $params = array();
    $params['country_id'] = $country_id;
    $params['from_date'] = $from_date;
    $params['to_date'] = $to_date;
    $params['limit'] = $limit;
    $params['offset'] = $offset;
    return CentralIndex::doCurl("GET","/leads/advertisers",$params);
  }


  /**
   * Returns entities that have claim data
   *
   *  @param country_id - Which country to return results for. An ISO compatible country code, E.g. ie e.g. ie
   *  @param from_date
   *  @param to_date
   *  @param limit
   *  @param offset
   *  @return - the data from the api
  */
  public function getLeadsClaimed( $country_id, $from_date, $to_date, $limit, $offset) {
    $params = array();
    $params['country_id'] = $country_id;
    $params['from_date'] = $from_date;
    $params['to_date'] = $to_date;
    $params['limit'] = $limit;
    $params['offset'] = $offset;
    return CentralIndex::doCurl("GET","/leads/claimed",$params);
  }


  /**
   * Create/update a new locz document with the supplied ID in the locations reference database.
   *
   *  @param location_id
   *  @param type
   *  @param country
   *  @param language
   *  @param name
   *  @param formal_name
   *  @param resolution
   *  @param population
   *  @param description
   *  @param timezone
   *  @param latitude
   *  @param longitude
   *  @param parent_town
   *  @param parent_county
   *  @param parent_province
   *  @param parent_region
   *  @param parent_neighbourhood
   *  @param parent_district
   *  @param postalcode
   *  @param searchable_id
   *  @param searchable_ids
   *  @return - the data from the api
  */
  public function postLocation( $location_id, $type, $country, $language, $name, $formal_name, $resolution, $population, $description, $timezone, $latitude, $longitude, $parent_town, $parent_county, $parent_province, $parent_region, $parent_neighbourhood, $parent_district, $postalcode, $searchable_id, $searchable_ids) {
    $params = array();
    $params['location_id'] = $location_id;
    $params['type'] = $type;
    $params['country'] = $country;
    $params['language'] = $language;
    $params['name'] = $name;
    $params['formal_name'] = $formal_name;
    $params['resolution'] = $resolution;
    $params['population'] = $population;
    $params['description'] = $description;
    $params['timezone'] = $timezone;
    $params['latitude'] = $latitude;
    $params['longitude'] = $longitude;
    $params['parent_town'] = $parent_town;
    $params['parent_county'] = $parent_county;
    $params['parent_province'] = $parent_province;
    $params['parent_region'] = $parent_region;
    $params['parent_neighbourhood'] = $parent_neighbourhood;
    $params['parent_district'] = $parent_district;
    $params['postalcode'] = $postalcode;
    $params['searchable_id'] = $searchable_id;
    $params['searchable_ids'] = $searchable_ids;
    return CentralIndex::doCurl("POST","/location",$params);
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
   * Given a location_id or a lat/lon, find other locations within the radius
   *
   *  @param location_id
   *  @param latitude
   *  @param longitude
   *  @param radius - Radius in km
   *  @param resolution
   *  @param country
   *  @param num_results
   *  @return - the data from the api
  */
  public function getLocationContext( $location_id, $latitude, $longitude, $radius, $resolution, $country, $num_results) {
    $params = array();
    $params['location_id'] = $location_id;
    $params['latitude'] = $latitude;
    $params['longitude'] = $longitude;
    $params['radius'] = $radius;
    $params['resolution'] = $resolution;
    $params['country'] = $country;
    $params['num_results'] = $num_results;
    return CentralIndex::doCurl("GET","/location/context",$params);
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
   * Create/Update login details
   *
   *  @param login_id
   *  @param email
   *  @param password
   *  @return - the data from the api
  */
  public function postLogin( $login_id, $email, $password) {
    $params = array();
    $params['login_id'] = $login_id;
    $params['email'] = $email;
    $params['password'] = $password;
    return CentralIndex::doCurl("POST","/login",$params);
  }


  /**
   * With a unique login_id a login can be deleted
   *
   *  @param login_id
   *  @return - the data from the api
  */
  public function deleteLogin( $login_id) {
    $params = array();
    $params['login_id'] = $login_id;
    return CentralIndex::doCurl("DELETE","/login",$params);
  }


  /**
   * With a unique login_id a login can be retrieved
   *
   *  @param login_id
   *  @return - the data from the api
  */
  public function getLogin( $login_id) {
    $params = array();
    $params['login_id'] = $login_id;
    return CentralIndex::doCurl("GET","/login",$params);
  }


  /**
   * With a unique email address a login can be retrieved
   *
   *  @param email
   *  @return - the data from the api
  */
  public function getLoginBy_email( $email) {
    $params = array();
    $params['email'] = $email;
    return CentralIndex::doCurl("GET","/login/by_email",$params);
  }


  /**
   * Verify that a supplied email and password match a users saved login details
   *
   *  @param email
   *  @param password
   *  @return - the data from the api
  */
  public function getLoginVerify( $email, $password) {
    $params = array();
    $params['email'] = $email;
    $params['password'] = $password;
    return CentralIndex::doCurl("GET","/login/verify",$params);
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
   * Find a location from cache or cloudant depending if it is in the cache (locz)
   *
   *  @param string
   *  @param language
   *  @param country
   *  @param latitude
   *  @param longitude
   *  @return - the data from the api
  */
  public function getLookupLocation( $string, $language, $country, $latitude, $longitude) {
    $params = array();
    $params['string'] = $string;
    $params['language'] = $language;
    $params['country'] = $country;
    $params['latitude'] = $latitude;
    $params['longitude'] = $longitude;
    return CentralIndex::doCurl("GET","/lookup/location",$params);
  }


  /**
   * Find all matches by phone number, returning up to 10 matches
   *
   *  @param phone
   *  @param country
   *  @param exclude - Entity ID to exclude from the results
   *  @return - the data from the api
  */
  public function getMatchByphone( $phone, $country, $exclude) {
    $params = array();
    $params['phone'] = $phone;
    $params['country'] = $country;
    $params['exclude'] = $exclude;
    return CentralIndex::doCurl("GET","/match/byphone",$params);
  }


  /**
   * Perform a match on the two supplied entities, returning the outcome and showing our working
   *
   *  @param primary_entity_id
   *  @param secondary_entity_id
   *  @param return_entities - Should we return the entity documents
   *  @return - the data from the api
  */
  public function getMatchOftheday( $primary_entity_id, $secondary_entity_id, $return_entities) {
    $params = array();
    $params['primary_entity_id'] = $primary_entity_id;
    $params['secondary_entity_id'] = $secondary_entity_id;
    $params['return_entities'] = $return_entities;
    return CentralIndex::doCurl("GET","/match/oftheday",$params);
  }


  /**
   * Delete Matching instruction
   *
   *  @param entity_id
   *  @return - the data from the api
  */
  public function deleteMatching_instruction( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("DELETE","/matching_instruction",$params);
  }


  /**
   * Will create a new Matching Instruction or update an existing one
   *
   *  @param entity_id
   *  @param entity_name
   *  @return - the data from the api
  */
  public function postMatching_instruction( $entity_id, $entity_name) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['entity_name'] = $entity_name;
    return CentralIndex::doCurl("POST","/matching_instruction",$params);
  }


  /**
   * Fetch all available Matching instructions
   *
   *  @param limit
   *  @return - the data from the api
  */
  public function getMatching_instructionAll( $limit) {
    $params = array();
    $params['limit'] = $limit;
    return CentralIndex::doCurl("GET","/matching_instruction/all",$params);
  }


  /**
   * Create a matching log
   *
   *  @param primary_entity_id
   *  @param secondary_entity_id
   *  @param primary_name
   *  @param secondary_name
   *  @param address_score
   *  @param address_match
   *  @param name_score
   *  @param name_match
   *  @param distance
   *  @param phone_match
   *  @param category_match
   *  @param email_match
   *  @param website_match
   *  @param match
   *  @return - the data from the api
  */
  public function putMatching_log( $primary_entity_id, $secondary_entity_id, $primary_name, $secondary_name, $address_score, $address_match, $name_score, $name_match, $distance, $phone_match, $category_match, $email_match, $website_match, $match) {
    $params = array();
    $params['primary_entity_id'] = $primary_entity_id;
    $params['secondary_entity_id'] = $secondary_entity_id;
    $params['primary_name'] = $primary_name;
    $params['secondary_name'] = $secondary_name;
    $params['address_score'] = $address_score;
    $params['address_match'] = $address_match;
    $params['name_score'] = $name_score;
    $params['name_match'] = $name_match;
    $params['distance'] = $distance;
    $params['phone_match'] = $phone_match;
    $params['category_match'] = $category_match;
    $params['email_match'] = $email_match;
    $params['website_match'] = $website_match;
    $params['match'] = $match;
    return CentralIndex::doCurl("PUT","/matching_log",$params);
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
   * Update/Add a multipack
   *
   *  @param multipack_id - this record's unique, auto-generated id - if supplied, then this is an edit, otherwise it's an add
   *  @param group_id - the id of the group that this site serves
   *  @param domainName - the domain name to serve this multipack site on (no leading http:// or anything please)
   *  @param multipackName - the name of the Flat pack instance
   *  @param less - the LESS configuration to use to overrides the Bootstrap CSS
   *  @param country - the country to use for searches etc
   *  @param menuTop - the JSON that describes a navigation at the top of the page
   *  @param menuBottom - the JSON that describes a navigation below the masthead
   *  @param language - An ISO compatible language code, E.g. en e.g. en
   *  @param menuFooter - the JSON that describes a navigation at the bottom of the page
   *  @param searchNumberResults - the number of search results per page
   *  @param searchTitle - Title of serps page
   *  @param searchDescription - Description of serps page
   *  @param searchTitleNoWhere - Title when no where is specified
   *  @param searchDescriptionNoWhere - Description of serps page when no where is specified
   *  @param searchIntroHeader - Introductory header
   *  @param searchIntroText - Introductory text
   *  @param searchShowAll - display all search results on one page
   *  @param searchUnitOfDistance - the unit of distance to use for search
   *  @param cookiePolicyShow - whether to show cookie policy
   *  @param cookiePolicyUrl - url of cookie policy
   *  @param twitterUrl - url of twitter feed
   *  @param facebookUrl - url of facebook feed
   *  @return - the data from the api
  */
  public function postMultipack( $multipack_id, $group_id, $domainName, $multipackName, $less, $country, $menuTop, $menuBottom, $language, $menuFooter, $searchNumberResults, $searchTitle, $searchDescription, $searchTitleNoWhere, $searchDescriptionNoWhere, $searchIntroHeader, $searchIntroText, $searchShowAll, $searchUnitOfDistance, $cookiePolicyShow, $cookiePolicyUrl, $twitterUrl, $facebookUrl) {
    $params = array();
    $params['multipack_id'] = $multipack_id;
    $params['group_id'] = $group_id;
    $params['domainName'] = $domainName;
    $params['multipackName'] = $multipackName;
    $params['less'] = $less;
    $params['country'] = $country;
    $params['menuTop'] = $menuTop;
    $params['menuBottom'] = $menuBottom;
    $params['language'] = $language;
    $params['menuFooter'] = $menuFooter;
    $params['searchNumberResults'] = $searchNumberResults;
    $params['searchTitle'] = $searchTitle;
    $params['searchDescription'] = $searchDescription;
    $params['searchTitleNoWhere'] = $searchTitleNoWhere;
    $params['searchDescriptionNoWhere'] = $searchDescriptionNoWhere;
    $params['searchIntroHeader'] = $searchIntroHeader;
    $params['searchIntroText'] = $searchIntroText;
    $params['searchShowAll'] = $searchShowAll;
    $params['searchUnitOfDistance'] = $searchUnitOfDistance;
    $params['cookiePolicyShow'] = $cookiePolicyShow;
    $params['cookiePolicyUrl'] = $cookiePolicyUrl;
    $params['twitterUrl'] = $twitterUrl;
    $params['facebookUrl'] = $facebookUrl;
    return CentralIndex::doCurl("POST","/multipack",$params);
  }


  /**
   * Get a multipack
   *
   *  @param multipack_id - the unique id to search for
   *  @return - the data from the api
  */
  public function getMultipack( $multipack_id) {
    $params = array();
    $params['multipack_id'] = $multipack_id;
    return CentralIndex::doCurl("GET","/multipack",$params);
  }


  /**
   * Add an admin theme to a multipack
   *
   *  @param multipack_id - the unique id to search for
   *  @param filedata
   *  @return - the data from the api
  */
  public function postMultipackAdminCSS( $multipack_id, $filedata) {
    $params = array();
    $params['multipack_id'] = $multipack_id;
    $params['filedata'] = $filedata;
    return CentralIndex::doCurl("POST","/multipack/adminCSS",$params);
  }


  /**
   * Add a Admin logo to a Multipack domain
   *
   *  @param multipack_id - the unique id to search for
   *  @param filedata
   *  @return - the data from the api
  */
  public function postMultipackAdminLogo( $multipack_id, $filedata) {
    $params = array();
    $params['multipack_id'] = $multipack_id;
    $params['filedata'] = $filedata;
    return CentralIndex::doCurl("POST","/multipack/adminLogo",$params);
  }


  /**
   * Get a multipack using a domain name
   *
   *  @param domainName - the domain name to search for
   *  @return - the data from the api
  */
  public function getMultipackBy_domain_name( $domainName) {
    $params = array();
    $params['domainName'] = $domainName;
    return CentralIndex::doCurl("GET","/multipack/by_domain_name",$params);
  }


  /**
   * duplicates a given multipack
   *
   *  @param multipack_id - the unique id to search for
   *  @param domainName - the domain name to serve this multipack site on (no leading http:// or anything please)
   *  @param group_id - the group to use for search
   *  @return - the data from the api
  */
  public function getMultipackClone( $multipack_id, $domainName, $group_id) {
    $params = array();
    $params['multipack_id'] = $multipack_id;
    $params['domainName'] = $domainName;
    $params['group_id'] = $group_id;
    return CentralIndex::doCurl("GET","/multipack/clone",$params);
  }


  /**
   * returns the LESS theme from a multipack
   *
   *  @param multipack_id - the unique id to search for
   *  @return - the data from the api
  */
  public function getMultipackLess( $multipack_id) {
    $params = array();
    $params['multipack_id'] = $multipack_id;
    return CentralIndex::doCurl("GET","/multipack/less",$params);
  }


  /**
   * Add a logo to a multipack domain
   *
   *  @param multipack_id - the unique id to search for
   *  @param filedata
   *  @return - the data from the api
  */
  public function postMultipackLogo( $multipack_id, $filedata) {
    $params = array();
    $params['multipack_id'] = $multipack_id;
    $params['filedata'] = $filedata;
    return CentralIndex::doCurl("POST","/multipack/logo",$params);
  }


  /**
   * Add a map pin to a multipack domain
   *
   *  @param multipack_id - the unique id to search for
   *  @param filedata
   *  @param mapPinOffsetX
   *  @param mapPinOffsetY
   *  @return - the data from the api
  */
  public function postMultipackMap_pin( $multipack_id, $filedata, $mapPinOffsetX, $mapPinOffsetY) {
    $params = array();
    $params['multipack_id'] = $multipack_id;
    $params['filedata'] = $filedata;
    $params['mapPinOffsetX'] = $mapPinOffsetX;
    $params['mapPinOffsetY'] = $mapPinOffsetY;
    return CentralIndex::doCurl("POST","/multipack/map_pin",$params);
  }


  /**
   * Create an ops_log
   *
   *  @param success
   *  @param type
   *  @param action
   *  @param data
   *  @param slack_channel
   *  @return - the data from the api
  */
  public function postOps_log( $success, $type, $action, $data, $slack_channel) {
    $params = array();
    $params['success'] = $success;
    $params['type'] = $type;
    $params['action'] = $action;
    $params['data'] = $data;
    $params['slack_channel'] = $slack_channel;
    return CentralIndex::doCurl("POST","/ops_log",$params);
  }


  /**
   * Fetch an ops_log
   *
   *  @param ops_log_id
   *  @return - the data from the api
  */
  public function getOps_log( $ops_log_id) {
    $params = array();
    $params['ops_log_id'] = $ops_log_id;
    return CentralIndex::doCurl("GET","/ops_log",$params);
  }


  /**
   * Run PTB for a given ingest job ID.
   *
   *  @param ingest_job_id - The ingest job ID
   *  @return - the data from the api
  */
  public function postPaintBy_ingest_job_id( $ingest_job_id) {
    $params = array();
    $params['ingest_job_id'] = $ingest_job_id;
    return CentralIndex::doCurl("POST","/paint/by_ingest_job_id",$params);
  }


  /**
   * With a known entity id syndication of data back to a partner is enabled
   *
   *  @param entity_id
   *  @param publisher_id
   *  @param expiry_date
   *  @return - the data from the api
  */
  public function postPartnersyndicateActivate( $entity_id, $publisher_id, $expiry_date) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['publisher_id'] = $publisher_id;
    $params['expiry_date'] = $expiry_date;
    return CentralIndex::doCurl("POST","/partnersyndicate/activate",$params);
  }


  /**
   * With a known entity id syndication of data back to a partner is enabled
   *
   *  @param entity_id
   *  @param publisher_id
   *  @param expiry_date
   *  @return - the data from the api
  */
  public function postPartnersyndicateActivate( $entity_id, $publisher_id, $expiry_date) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['publisher_id'] = $publisher_id;
    $params['expiry_date'] = $expiry_date;
    return CentralIndex::doCurl("POST","/partnersyndicate/activate",$params);
  }


  /**
   * This will call into CK in order to create the entity on the third party system.
   *
   *  @param entity_id
   *  @return - the data from the api
  */
  public function postPartnersyndicateCreate( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("POST","/partnersyndicate/create",$params);
  }


  /**
   * If this call fails CK is nudged for a human intervention for the future (so the call is NOT passive)
   *
   *  @param vendor_cat_id
   *  @param vendor_cat_string
   *  @param vendor
   *  @return - the data from the api
  */
  public function getPartnersyndicateRequestcat( $vendor_cat_id, $vendor_cat_string, $vendor) {
    $params = array();
    $params['vendor_cat_id'] = $vendor_cat_id;
    $params['vendor_cat_string'] = $vendor_cat_string;
    $params['vendor'] = $vendor;
    return CentralIndex::doCurl("GET","/partnersyndicate/requestcat",$params);
  }


  /**
   * Get plugin data
   *
   *  @param id
   *  @return - the data from the api
  */
  public function getPlugin( $id) {
    $params = array();
    $params['id'] = $id;
    return CentralIndex::doCurl("GET","/plugin",$params);
  }


  /**
   * When a plugin is added to the system it must be added to the service
   *
   *  @param id
   *  @param slug
   *  @param owner
   *  @param scope
   *  @param status
   *  @return - the data from the api
  */
  public function postPlugin( $id, $slug, $owner, $scope, $status) {
    $params = array();
    $params['id'] = $id;
    $params['slug'] = $slug;
    $params['owner'] = $owner;
    $params['scope'] = $scope;
    $params['status'] = $status;
    return CentralIndex::doCurl("POST","/plugin",$params);
  }


  /**
   * With a known entity id, a plugin is enabled
   *
   *  @param entity_id
   *  @param plugin
   *  @param expiry_date
   *  @return - the data from the api
  */
  public function postPluginActivate( $entity_id, $plugin, $expiry_date) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['plugin'] = $plugin;
    $params['expiry_date'] = $expiry_date;
    return CentralIndex::doCurl("POST","/plugin/activate",$params);
  }


  /**
   * With a known entity id, a plugin is enabled
   *
   *  @param pluginid
   *  @param userid
   *  @param entity_id
   *  @param storekey
   *  @param storeval
   *  @return - the data from the api
  */
  public function postPluginVar( $pluginid, $userid, $entity_id, $storekey, $storeval) {
    $params = array();
    $params['pluginid'] = $pluginid;
    $params['userid'] = $userid;
    $params['entity_id'] = $entity_id;
    $params['storekey'] = $storekey;
    $params['storeval'] = $storeval;
    return CentralIndex::doCurl("POST","/plugin/var",$params);
  }


  /**
   * Get variables related to a particular entity
   *
   *  @param entityid
   *  @return - the data from the api
  */
  public function getPluginVarsByEntityId( $entityid) {
    $params = array();
    $params['entityid'] = $entityid;
    return CentralIndex::doCurl("GET","/plugin/vars/byEntityId",$params);
  }


  /**
   * Get info on all plugins
   *
   *  @return - the data from the api
  */
  public function getPlugins() {
    $params = array();
    return CentralIndex::doCurl("GET","/plugins",$params);
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
   * Update/Add a product
   *
   *  @param product_id - The ID of the product
   *  @param name - The name of the product
   *  @param strapline - The description of the product
   *  @param paygateid - The product id in the payment gateway (required for Stripe)
   *  @param price - The price of the product
   *  @param tax_rate - The tax markup for this product
   *  @param currency - The currency in which the price is in
   *  @param active - is this an active product
   *  @param billing_period
   *  @param title - Title of the product
   *  @param intro_paragraph - introduction paragraph
   *  @param bullets - pipe separated product features
   *  @param outro_paragraph - closing paragraph
   *  @param thanks_paragraph - thank you paragraph
   *  @return - the data from the api
  */
  public function postProduct( $product_id, $name, $strapline, $paygateid, $price, $tax_rate, $currency, $active, $billing_period, $title, $intro_paragraph, $bullets, $outro_paragraph, $thanks_paragraph) {
    $params = array();
    $params['product_id'] = $product_id;
    $params['name'] = $name;
    $params['strapline'] = $strapline;
    $params['paygateid'] = $paygateid;
    $params['price'] = $price;
    $params['tax_rate'] = $tax_rate;
    $params['currency'] = $currency;
    $params['active'] = $active;
    $params['billing_period'] = $billing_period;
    $params['title'] = $title;
    $params['intro_paragraph'] = $intro_paragraph;
    $params['bullets'] = $bullets;
    $params['outro_paragraph'] = $outro_paragraph;
    $params['thanks_paragraph'] = $thanks_paragraph;
    return CentralIndex::doCurl("POST","/product",$params);
  }


  /**
   * Returns the product information given a valid product_id
   *
   *  @param product_id
   *  @return - the data from the api
  */
  public function getProduct( $product_id) {
    $params = array();
    $params['product_id'] = $product_id;
    return CentralIndex::doCurl("GET","/product",$params);
  }


  /**
   * Removes a provisioning object from product
   *
   *  @param product_id
   *  @param gen_id
   *  @return - the data from the api
  */
  public function deleteProductProvisioning( $product_id, $gen_id) {
    $params = array();
    $params['product_id'] = $product_id;
    $params['gen_id'] = $gen_id;
    return CentralIndex::doCurl("DELETE","/product/provisioning",$params);
  }


  /**
   * Adds advertising provisioning object to a product
   *
   *  @param product_id
   *  @param publisher_id
   *  @param max_tags
   *  @param max_locations
   *  @return - the data from the api
  */
  public function postProductProvisioningAdvert( $product_id, $publisher_id, $max_tags, $max_locations) {
    $params = array();
    $params['product_id'] = $product_id;
    $params['publisher_id'] = $publisher_id;
    $params['max_tags'] = $max_tags;
    $params['max_locations'] = $max_locations;
    return CentralIndex::doCurl("POST","/product/provisioning/advert",$params);
  }


  /**
   * Adds claim provisioning object to a product
   *
   *  @param product_id
   *  @return - the data from the api
  */
  public function postProductProvisioningClaim( $product_id) {
    $params = array();
    $params['product_id'] = $product_id;
    return CentralIndex::doCurl("POST","/product/provisioning/claim",$params);
  }


  /**
   * Adds partnersyndicate provisioning object to a product
   *
   *  @param product_id
   *  @param publisher_id
   *  @return - the data from the api
  */
  public function postProductProvisioningPartnersyndicate( $product_id, $publisher_id) {
    $params = array();
    $params['product_id'] = $product_id;
    $params['publisher_id'] = $publisher_id;
    return CentralIndex::doCurl("POST","/product/provisioning/partnersyndicate",$params);
  }


  /**
   * Adds plugin provisioning object to a product
   *
   *  @param product_id
   *  @param publisher_id
   *  @return - the data from the api
  */
  public function postProductProvisioningPlugin( $product_id, $publisher_id) {
    $params = array();
    $params['product_id'] = $product_id;
    $params['publisher_id'] = $publisher_id;
    return CentralIndex::doCurl("POST","/product/provisioning/plugin",$params);
  }


  /**
   * Adds SCheduleSMS provisioning object to a product
   *
   *  @param product_id
   *  @param package
   *  @return - the data from the api
  */
  public function postProductProvisioningSchedulesms( $product_id, $package) {
    $params = array();
    $params['product_id'] = $product_id;
    $params['package'] = $package;
    return CentralIndex::doCurl("POST","/product/provisioning/schedulesms",$params);
  }


  /**
   * Adds syndication provisioning object to a product
   *
   *  @param product_id
   *  @param publisher_id
   *  @return - the data from the api
  */
  public function postProductProvisioningSyndication( $product_id, $publisher_id) {
    $params = array();
    $params['product_id'] = $product_id;
    $params['publisher_id'] = $publisher_id;
    return CentralIndex::doCurl("POST","/product/provisioning/syndication",$params);
  }


  /**
   * Perform the whole PTB process on the supplied entity
   *
   *  @param entity_id
   *  @param destructive
   *  @return - the data from the api
  */
  public function getPtbAll( $entity_id, $destructive) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['destructive'] = $destructive;
    return CentralIndex::doCurl("GET","/ptb/all",$params);
  }


  /**
   * Report on what happened to specific entity_id
   *
   *  @param year - the year to examine
   *  @param month - the month to examine
   *  @param entity_id - the entity to research
   *  @return - the data from the api
  */
  public function getPtbLog( $year, $month, $entity_id) {
    $params = array();
    $params['year'] = $year;
    $params['month'] = $month;
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("GET","/ptb/log",$params);
  }


  /**
   * Process an entity with a specific PTB module
   *
   *  @param entity_id
   *  @param module
   *  @param destructive
   *  @return - the data from the api
  */
  public function getPtbModule( $entity_id, $module, $destructive) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['module'] = $module;
    $params['destructive'] = $destructive;
    return CentralIndex::doCurl("GET","/ptb/module",$params);
  }


  /**
   * Report on the run-rate of the Paint the Bridge System
   *
   *  @param country - the country to get the runrate for
   *  @param year - the year to examine
   *  @param month - the month to examine
   *  @param day - the day to examine
   *  @return - the data from the api
  */
  public function getPtbRunrate( $country, $year, $month, $day) {
    $params = array();
    $params['country'] = $country;
    $params['year'] = $year;
    $params['month'] = $month;
    $params['day'] = $day;
    return CentralIndex::doCurl("GET","/ptb/runrate",$params);
  }


  /**
   * Report on the value being added by Paint The Bridge
   *
   *  @param country - the country to get the runrate for
   *  @param year - the year to examine
   *  @param month - the month to examine
   *  @param day - the day to examine
   *  @return - the data from the api
  */
  public function getPtbValueadded( $country, $year, $month, $day) {
    $params = array();
    $params['country'] = $country;
    $params['year'] = $year;
    $params['month'] = $month;
    $params['day'] = $day;
    return CentralIndex::doCurl("GET","/ptb/valueadded",$params);
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
   * Update/Add a publisher
   *
   *  @param publisher_id
   *  @param country
   *  @param name
   *  @param description
   *  @param active
   *  @param url_patterns
   *  @param premium_adverts_platinum
   *  @param premium_adverts_gold
   *  @return - the data from the api
  */
  public function postPublisher( $publisher_id, $country, $name, $description, $active, $url_patterns, $premium_adverts_platinum, $premium_adverts_gold) {
    $params = array();
    $params['publisher_id'] = $publisher_id;
    $params['country'] = $country;
    $params['name'] = $name;
    $params['description'] = $description;
    $params['active'] = $active;
    $params['url_patterns'] = $url_patterns;
    $params['premium_adverts_platinum'] = $premium_adverts_platinum;
    $params['premium_adverts_gold'] = $premium_adverts_gold;
    return CentralIndex::doCurl("POST","/publisher",$params);
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
   * Returns a publisher that has the specified masheryid
   *
   *  @param publisher_masheryid
   *  @return - the data from the api
  */
  public function getPublisherBy_masheryid( $publisher_masheryid) {
    $params = array();
    $params['publisher_masheryid'] = $publisher_masheryid;
    return CentralIndex::doCurl("GET","/publisher/by_masheryid",$params);
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
   * Find a queue item by its cloudant id
   *
   *  @param queue_id
   *  @return - the data from the api
  */
  public function getQueueBy_id( $queue_id) {
    $params = array();
    $params['queue_id'] = $queue_id;
    return CentralIndex::doCurl("GET","/queue/by_id",$params);
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
   * Create an SQS queue item
   *
   *  @param queue_name
   *  @param data
   *  @return - the data from the api
  */
  public function putQueue_sqs( $queue_name, $data) {
    $params = array();
    $params['queue_name'] = $queue_name;
    $params['data'] = $data;
    return CentralIndex::doCurl("PUT","/queue_sqs",$params);
  }


  /**
   * Update/Add a reseller
   *
   *  @param reseller_id
   *  @param country
   *  @param name
   *  @param description
   *  @param active
   *  @param products
   *  @param master_user_id
   *  @return - the data from the api
  */
  public function postReseller( $reseller_id, $country, $name, $description, $active, $products, $master_user_id) {
    $params = array();
    $params['reseller_id'] = $reseller_id;
    $params['country'] = $country;
    $params['name'] = $name;
    $params['description'] = $description;
    $params['active'] = $active;
    $params['products'] = $products;
    $params['master_user_id'] = $master_user_id;
    return CentralIndex::doCurl("POST","/reseller",$params);
  }


  /**
   * Returns reseller that matches a given reseller id
   *
   *  @param reseller_id
   *  @return - the data from the api
  */
  public function getReseller( $reseller_id) {
    $params = array();
    $params['reseller_id'] = $reseller_id;
    return CentralIndex::doCurl("GET","/reseller",$params);
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
   * Return a sales log by id
   *
   *  @param from_date
   *  @param country
   *  @param action_type
   *  @return - the data from the api
  */
  public function getSales_logBy_countryBy_date( $from_date, $country, $action_type) {
    $params = array();
    $params['from_date'] = $from_date;
    $params['country'] = $country;
    $params['action_type'] = $action_type;
    return CentralIndex::doCurl("GET","/sales_log/by_country/by_date",$params);
  }


  /**
   * Return a sales log by id
   *
   *  @param from_date
   *  @param to_date
   *  @return - the data from the api
  */
  public function getSales_logBy_date( $from_date, $to_date) {
    $params = array();
    $params['from_date'] = $from_date;
    $params['to_date'] = $to_date;
    return CentralIndex::doCurl("GET","/sales_log/by_date",$params);
  }


  /**
   * Log a sale
   *
   *  @param entity_id - The entity the sale was made against
   *  @param country - The country code the sales log orginated
   *  @param action_type - The type of action we are performing
   *  @param ad_type - The type of advertisements
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
  public function postSales_logEntity( $entity_id, $country, $action_type, $ad_type, $publisher_id, $mashery_id, $reseller_ref, $reseller_agent_id, $max_tags, $max_locations, $extra_tags, $extra_locations, $expiry_date) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['country'] = $country;
    $params['action_type'] = $action_type;
    $params['ad_type'] = $ad_type;
    $params['publisher_id'] = $publisher_id;
    $params['mashery_id'] = $mashery_id;
    $params['reseller_ref'] = $reseller_ref;
    $params['reseller_agent_id'] = $reseller_agent_id;
    $params['max_tags'] = $max_tags;
    $params['max_locations'] = $max_locations;
    $params['extra_tags'] = $extra_tags;
    $params['extra_locations'] = $extra_locations;
    $params['expiry_date'] = $expiry_date;
    return CentralIndex::doCurl("POST","/sales_log/entity",$params);
  }


  /**
   * Add a Sales Log document for a syndication action
   *
   *  @param action_type
   *  @param syndication_type
   *  @param publisher_id
   *  @param expiry_date
   *  @param entity_id
   *  @param group_id
   *  @param seed_masheryid
   *  @param supplier_masheryid
   *  @param country
   *  @param reseller_masheryid
   *  @return - the data from the api
  */
  public function postSales_logSyndication( $action_type, $syndication_type, $publisher_id, $expiry_date, $entity_id, $group_id, $seed_masheryid, $supplier_masheryid, $country, $reseller_masheryid) {
    $params = array();
    $params['action_type'] = $action_type;
    $params['syndication_type'] = $syndication_type;
    $params['publisher_id'] = $publisher_id;
    $params['expiry_date'] = $expiry_date;
    $params['entity_id'] = $entity_id;
    $params['group_id'] = $group_id;
    $params['seed_masheryid'] = $seed_masheryid;
    $params['supplier_masheryid'] = $supplier_masheryid;
    $params['country'] = $country;
    $params['reseller_masheryid'] = $reseller_masheryid;
    return CentralIndex::doCurl("POST","/sales_log/syndication",$params);
  }


  /**
   * Converts an Entity into a submission at the Scoot Partner API
   *
   *  @param entity_id - The entity to parse
   *  @param reseller - The reseller Mashery ID
   *  @return - the data from the api
  */
  public function postScoot_priority( $entity_id, $reseller) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['reseller'] = $reseller;
    return CentralIndex::doCurl("POST","/scoot_priority",$params);
  }


  /**
   * Make a url shorter
   *
   *  @param url - the url to shorten
   *  @return - the data from the api
  */
  public function putShortenurl( $url) {
    $params = array();
    $params['url'] = $url;
    return CentralIndex::doCurl("PUT","/shortenurl",$params);
  }


  /**
   * get the long url from the db
   *
   *  @param id - the id to fetch from the db
   *  @return - the data from the api
  */
  public function getShortenurl( $id) {
    $params = array();
    $params['id'] = $id;
    return CentralIndex::doCurl("GET","/shortenurl",$params);
  }


  /**
   * For insance, reporting a phone number as wrong
   *
   *  @param entity_id - A valid entity_id e.g. 379236608286720
   *  @param country - The country code from where the signal originated e.g. ie
   *  @param gen_id - The gen_id for the item being reported
   *  @param signal_type - The signal that is to be reported e.g. wrong
   *  @param data_type - The type of data being reported
   *  @param inactive_reason - The reason for making the entity inactive
   *  @param inactive_description - A description to accompany the inactive reasoning
   *  @param feedback - Some feedback from the person submitting the signal
   *  @return - the data from the api
  */
  public function postSignal( $entity_id, $country, $gen_id, $signal_type, $data_type, $inactive_reason, $inactive_description, $feedback) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['country'] = $country;
    $params['gen_id'] = $gen_id;
    $params['signal_type'] = $signal_type;
    $params['data_type'] = $data_type;
    $params['inactive_reason'] = $inactive_reason;
    $params['inactive_description'] = $inactive_description;
    $params['feedback'] = $feedback;
    return CentralIndex::doCurl("POST","/signal",$params);
  }


  /**
   * Get a spider document
   *
   *  @param spider_id
   *  @return - the data from the api
  */
  public function getSpider( $spider_id) {
    $params = array();
    $params['spider_id'] = $spider_id;
    return CentralIndex::doCurl("GET","/spider",$params);
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
   * Get the stats on an entity in a given year
   *
   *  @param entity_id - A valid entity_id e.g. 379236608286720
   *  @param year - The year to report on
   *  @return - the data from the api
  */
  public function getStatsEntityBy_year( $entity_id, $year) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['year'] = $year;
    return CentralIndex::doCurl("GET","/stats/entity/by_year",$params);
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
   * get a Syndication
   *
   *  @param syndication_id
   *  @return - the data from the api
  */
  public function getSyndication( $syndication_id) {
    $params = array();
    $params['syndication_id'] = $syndication_id;
    return CentralIndex::doCurl("GET","/syndication",$params);
  }


  /**
   * get a Syndication by entity_id
   *
   *  @param entity_id
   *  @return - the data from the api
  */
  public function getSyndicationBy_entity_id( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("GET","/syndication/by_entity_id",$params);
  }


  /**
   * Get a Syndication by Reseller (Mashery ID) and optional entity ID
   *
   *  @param reseller_masheryid
   *  @param entity_id
   *  @return - the data from the api
  */
  public function getSyndicationBy_reseller( $reseller_masheryid, $entity_id) {
    $params = array();
    $params['reseller_masheryid'] = $reseller_masheryid;
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("GET","/syndication/by_reseller",$params);
  }


  /**
   * Cancel a syndication
   *
   *  @param syndication_id
   *  @return - the data from the api
  */
  public function postSyndicationCancel( $syndication_id) {
    $params = array();
    $params['syndication_id'] = $syndication_id;
    return CentralIndex::doCurl("POST","/syndication/cancel",$params);
  }


  /**
   * Add a Syndicate
   *
   *  @param syndication_type
   *  @param publisher_id
   *  @param expiry_date
   *  @param entity_id
   *  @param group_id
   *  @param seed_masheryid
   *  @param supplier_masheryid
   *  @param country
   *  @param data_filter
   *  @return - the data from the api
  */
  public function postSyndicationCreate( $syndication_type, $publisher_id, $expiry_date, $entity_id, $group_id, $seed_masheryid, $supplier_masheryid, $country, $data_filter) {
    $params = array();
    $params['syndication_type'] = $syndication_type;
    $params['publisher_id'] = $publisher_id;
    $params['expiry_date'] = $expiry_date;
    $params['entity_id'] = $entity_id;
    $params['group_id'] = $group_id;
    $params['seed_masheryid'] = $seed_masheryid;
    $params['supplier_masheryid'] = $supplier_masheryid;
    $params['country'] = $country;
    $params['data_filter'] = $data_filter;
    return CentralIndex::doCurl("POST","/syndication/create",$params);
  }


  /**
   * Renew a Syndicate
   *
   *  @param syndication_type
   *  @param publisher_id
   *  @param entity_id
   *  @param group_id
   *  @param seed_masheryid
   *  @param supplier_masheryid
   *  @param country
   *  @param expiry_date
   *  @return - the data from the api
  */
  public function postSyndicationRenew( $syndication_type, $publisher_id, $entity_id, $group_id, $seed_masheryid, $supplier_masheryid, $country, $expiry_date) {
    $params = array();
    $params['syndication_type'] = $syndication_type;
    $params['publisher_id'] = $publisher_id;
    $params['entity_id'] = $entity_id;
    $params['group_id'] = $group_id;
    $params['seed_masheryid'] = $seed_masheryid;
    $params['supplier_masheryid'] = $supplier_masheryid;
    $params['country'] = $country;
    $params['expiry_date'] = $expiry_date;
    return CentralIndex::doCurl("POST","/syndication/renew",$params);
  }


  /**
   * When we get a syndication update make a record of it
   *
   *  @param entity_id - The entity to pull
   *  @param publisher_id - The publisher this log entry refers to
   *  @param action - The log type
   *  @param success - If the syndication was successful
   *  @param message - An optional message e.g. submitted to the syndication partner
   *  @param syndicated_id - The entity as known to the publisher
   *  @return - the data from the api
  */
  public function postSyndication_log( $entity_id, $publisher_id, $action, $success, $message, $syndicated_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['publisher_id'] = $publisher_id;
    $params['action'] = $action;
    $params['success'] = $success;
    $params['message'] = $message;
    $params['syndicated_id'] = $syndicated_id;
    return CentralIndex::doCurl("POST","/syndication_log",$params);
  }


  /**
   * Get all syndication log entries for a given entity id
   *
   *  @param entity_id
   *  @param page
   *  @param per_page
   *  @return - the data from the api
  */
  public function getSyndication_logBy_entity_id( $entity_id, $page, $per_page) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['page'] = $page;
    $params['per_page'] = $per_page;
    return CentralIndex::doCurl("GET","/syndication_log/by_entity_id",$params);
  }


  /**
   * Get the latest syndication log feedback entry for a given entity id and publisher
   *
   *  @param entity_id
   *  @param publisher_id
   *  @return - the data from the api
  */
  public function getSyndication_logLast_syndicated_id( $entity_id, $publisher_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['publisher_id'] = $publisher_id;
    return CentralIndex::doCurl("GET","/syndication_log/last_syndicated_id",$params);
  }


  /**
   * Returns a Syndication Submission
   *
   *  @param syndication_submission_id
   *  @return - the data from the api
  */
  public function getSyndication_submission( $syndication_submission_id) {
    $params = array();
    $params['syndication_submission_id'] = $syndication_submission_id;
    return CentralIndex::doCurl("GET","/syndication_submission",$params);
  }


  /**
   * Creates a new Syndication Submission
   *
   *  @param syndication_type
   *  @param entity_id
   *  @param publisher_id
   *  @param submission_id
   *  @return - the data from the api
  */
  public function putSyndication_submission( $syndication_type, $entity_id, $publisher_id, $submission_id) {
    $params = array();
    $params['syndication_type'] = $syndication_type;
    $params['entity_id'] = $entity_id;
    $params['publisher_id'] = $publisher_id;
    $params['submission_id'] = $submission_id;
    return CentralIndex::doCurl("PUT","/syndication_submission",$params);
  }


  /**
   * Set active to false for a Syndication Submission
   *
   *  @param syndication_submission_id
   *  @return - the data from the api
  */
  public function postSyndication_submissionDeactivate( $syndication_submission_id) {
    $params = array();
    $params['syndication_submission_id'] = $syndication_submission_id;
    return CentralIndex::doCurl("POST","/syndication_submission/deactivate",$params);
  }


  /**
   * Set the processed date to now for a Syndication Submission
   *
   *  @param syndication_submission_id
   *  @return - the data from the api
  */
  public function postSyndication_submissionProcessed( $syndication_submission_id) {
    $params = array();
    $params['syndication_submission_id'] = $syndication_submission_id;
    return CentralIndex::doCurl("POST","/syndication_submission/processed",$params);
  }


  /**
   * Provides a tokenised URL to redirect a user so they can add an entity to Central Index
   *
   *  @param language - The language to use to render the add path e.g. en
   *  @param portal_name - The name of the website that data is to be added on e.g. YourLocal
   *  @param partner - syndication partner (eg 192)
   *  @param country - The country of the entity to be added e.g. gb
   *  @param flatpack_id - The id of the flatpack site where the request originated
   *  @return - the data from the api
  */
  public function getTokenAdd( $language, $portal_name, $partner, $country, $flatpack_id) {
    $params = array();
    $params['language'] = $language;
    $params['portal_name'] = $portal_name;
    $params['partner'] = $partner;
    $params['country'] = $country;
    $params['flatpack_id'] = $flatpack_id;
    return CentralIndex::doCurl("GET","/token/add",$params);
  }


  /**
   * Provides a tokenised URL to redirect a user to claim an entity on Central Index
   *
   *  @param entity_id - Entity ID to be claimed e.g. 380348266819584
   *  @param language - The language to use to render the claim path e.g. en
   *  @param portal_name - The name of the website that entity is being claimed on e.g. YourLocal
   *  @param flatpack_id - The id of the flatpack site where the request originated
   *  @param admin_host - The admin host to refer back to - will only be respected if whitelisted in configuration
   *  @return - the data from the api
  */
  public function getTokenClaim( $entity_id, $language, $portal_name, $flatpack_id, $admin_host) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['language'] = $language;
    $params['portal_name'] = $portal_name;
    $params['flatpack_id'] = $flatpack_id;
    $params['admin_host'] = $admin_host;
    return CentralIndex::doCurl("GET","/token/claim",$params);
  }


  /**
   * Fetch token for the contact us method
   *
   *  @param portal_name - The portal name
   *  @param flatpack_id - The id of the flatpack site where the request originated
   *  @param language - en, es, etc...
   *  @param referring_url - the url where the request came from
   *  @return - the data from the api
  */
  public function getTokenContact_us( $portal_name, $flatpack_id, $language, $referring_url) {
    $params = array();
    $params['portal_name'] = $portal_name;
    $params['flatpack_id'] = $flatpack_id;
    $params['language'] = $language;
    $params['referring_url'] = $referring_url;
    return CentralIndex::doCurl("GET","/token/contact_us",$params);
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
   * Fetch token for edit path
   *
   *  @param entity_id - The id of the entity being upgraded
   *  @param language - The language for the app
   *  @param flatpack_id - The id of the flatpack site where the request originated
   *  @param edit_page - the page in the edit path that the user should land on
   *  @return - the data from the api
  */
  public function getTokenEdit( $entity_id, $language, $flatpack_id, $edit_page) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['language'] = $language;
    $params['flatpack_id'] = $flatpack_id;
    $params['edit_page'] = $edit_page;
    return CentralIndex::doCurl("GET","/token/edit",$params);
  }


  /**
   * Fetch token for login path
   *
   *  @param portal_name - The name of the application that has initiated the login process, example: 'Your Local'
   *  @param language - The language for the app
   *  @param flatpack_id - The id of the flatpack site where the request originated
   *  @param multipack_id - The id of the multipack site where the request originated
   *  @return - the data from the api
  */
  public function getTokenLogin( $portal_name, $language, $flatpack_id, $multipack_id) {
    $params = array();
    $params['portal_name'] = $portal_name;
    $params['language'] = $language;
    $params['flatpack_id'] = $flatpack_id;
    $params['multipack_id'] = $multipack_id;
    return CentralIndex::doCurl("GET","/token/login",$params);
  }


  /**
   * Get a tokenised url for an password reset
   *
   *  @param login_id - Login id
   *  @param flatpack_id - The id of the flatpack site where the request originated
   *  @param entity_id
   *  @param action
   *  @return - the data from the api
  */
  public function getTokenLoginReset_password( $login_id, $flatpack_id, $entity_id, $action) {
    $params = array();
    $params['login_id'] = $login_id;
    $params['flatpack_id'] = $flatpack_id;
    $params['entity_id'] = $entity_id;
    $params['action'] = $action;
    return CentralIndex::doCurl("GET","/token/login/reset_password",$params);
  }


  /**
   * Get a tokenised url for an email verification
   *
   *  @param email - Email address
   *  @param flatpack_id - The id of the flatpack site where the request originated
   *  @param entity_id
   *  @param action
   *  @return - the data from the api
  */
  public function getTokenLoginSet_password( $email, $flatpack_id, $entity_id, $action) {
    $params = array();
    $params['email'] = $email;
    $params['flatpack_id'] = $flatpack_id;
    $params['entity_id'] = $entity_id;
    $params['action'] = $action;
    return CentralIndex::doCurl("GET","/token/login/set_password",$params);
  }


  /**
   * Fetch token for messaging path
   *
   *  @param entity_id - The id of the entity being messaged
   *  @param portal_name - The name of the application that has initiated the email process, example: 'Your Local'
   *  @param language - The language for the app
   *  @param flatpack_id - The id of the flatpack site where the request originated
   *  @return - the data from the api
  */
  public function getTokenMessage( $entity_id, $portal_name, $language, $flatpack_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['portal_name'] = $portal_name;
    $params['language'] = $language;
    $params['flatpack_id'] = $flatpack_id;
    return CentralIndex::doCurl("GET","/token/message",$params);
  }


  /**
   * Fetch token for product path
   *
   *  @param entity_id - The id of the entity to add a product to
   *  @param product_id - The product id of the product
   *  @param language - The language for the app
   *  @param portal_name - The portal name
   *  @param flatpack_id - The id of the flatpack site where the request originated
   *  @param source - email, social media etc
   *  @param channel - 
   *  @param campaign - the campaign identifier
   *  @return - the data from the api
  */
  public function getTokenProduct( $entity_id, $product_id, $language, $portal_name, $flatpack_id, $source, $channel, $campaign) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['product_id'] = $product_id;
    $params['language'] = $language;
    $params['portal_name'] = $portal_name;
    $params['flatpack_id'] = $flatpack_id;
    $params['source'] = $source;
    $params['channel'] = $channel;
    $params['campaign'] = $campaign;
    return CentralIndex::doCurl("GET","/token/product",$params);
  }


  /**
   * Fetch token for product path
   *
   *  @param entity_id - The id of the entity to add a product to
   *  @param portal_name - The portal name
   *  @param flatpack_id - The id of the flatpack site where the request originated
   *  @param language - en, es, etc...
   *  @return - the data from the api
  */
  public function getTokenProduct_selector( $entity_id, $portal_name, $flatpack_id, $language) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['portal_name'] = $portal_name;
    $params['flatpack_id'] = $flatpack_id;
    $params['language'] = $language;
    return CentralIndex::doCurl("GET","/token/product_selector",$params);
  }


  /**
   * Provides a tokenised URL that allows a user to report incorrect entity information
   *
   *  @param entity_id - The unique Entity ID e.g. 379236608286720
   *  @param portal_name - The name of the portal that the user is coming from e.g. YourLocal
   *  @param language - The language to use to render the report path
   *  @param flatpack_id - The id of the flatpack site where the request originated
   *  @return - the data from the api
  */
  public function getTokenReport( $entity_id, $portal_name, $language, $flatpack_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['portal_name'] = $portal_name;
    $params['language'] = $language;
    $params['flatpack_id'] = $flatpack_id;
    return CentralIndex::doCurl("GET","/token/report",$params);
  }


  /**
   * Get a tokenised url for the review path
   *
   *  @param portal_name - The portal name
   *  @param entity_id
   *  @param language - en, es, etc...
   *  @param flatpack_id - The id of the flatpack site where the request originated
   *  @return - the data from the api
  */
  public function getTokenReview( $portal_name, $entity_id, $language, $flatpack_id) {
    $params = array();
    $params['portal_name'] = $portal_name;
    $params['entity_id'] = $entity_id;
    $params['language'] = $language;
    $params['flatpack_id'] = $flatpack_id;
    return CentralIndex::doCurl("GET","/token/review",$params);
  }


  /**
   * Get a tokenised url for the testimonial path
   *
   *  @param portal_name - The portal name
   *  @param flatpack_id - The id of the flatpack site where the request originated
   *  @param language - en, es, etc...
   *  @param entity_id
   *  @param shorten_url
   *  @return - the data from the api
  */
  public function getTokenTestimonial( $portal_name, $flatpack_id, $language, $entity_id, $shorten_url) {
    $params = array();
    $params['portal_name'] = $portal_name;
    $params['flatpack_id'] = $flatpack_id;
    $params['language'] = $language;
    $params['entity_id'] = $entity_id;
    $params['shorten_url'] = $shorten_url;
    return CentralIndex::doCurl("GET","/token/testimonial",$params);
  }


  /**
   * The JaroWinklerDistance between two entities postal address objects
   *
   *  @param first_entity_id - The entity id of the first entity to be used in the postal address difference
   *  @param second_entity_id - The entity id of the second entity to be used in the postal address difference
   *  @return - the data from the api
  */
  public function getToolsAddressdiff( $first_entity_id, $second_entity_id) {
    $params = array();
    $params['first_entity_id'] = $first_entity_id;
    $params['second_entity_id'] = $second_entity_id;
    return CentralIndex::doCurl("GET","/tools/addressdiff",$params);
  }


  /**
   * An API call to test crashing the server
   *
   *  @return - the data from the api
  */
  public function getToolsCrash() {
    $params = array();
    return CentralIndex::doCurl("GET","/tools/crash",$params);
  }


  /**
   * Provide a method, a path and some data to run a load of curl commands and get emailed when complete
   *
   *  @param method - The method e.g. POST
   *  @param path - The relative api call e.g. /entity/phone
   *  @param filedata - A tab separated file for ingest
   *  @param email - Response email address e.g. dave@fender.com
   *  @return - the data from the api
  */
  public function postToolsCurl( $method, $path, $filedata, $email) {
    $params = array();
    $params['method'] = $method;
    $params['path'] = $path;
    $params['filedata'] = $filedata;
    $params['email'] = $email;
    return CentralIndex::doCurl("POST","/tools/curl",$params);
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
   * Supply an address to geocode - returns lat/lon and accuracy
   *
   *  @param building_number
   *  @param address1
   *  @param address2
   *  @param address3
   *  @param district
   *  @param town
   *  @param county
   *  @param province
   *  @param postcode
   *  @param country
   *  @return - the data from the api
  */
  public function getToolsGeocode( $building_number, $address1, $address2, $address3, $district, $town, $county, $province, $postcode, $country) {
    $params = array();
    $params['building_number'] = $building_number;
    $params['address1'] = $address1;
    $params['address2'] = $address2;
    $params['address3'] = $address3;
    $params['district'] = $district;
    $params['town'] = $town;
    $params['county'] = $county;
    $params['province'] = $province;
    $params['postcode'] = $postcode;
    $params['country'] = $country;
    return CentralIndex::doCurl("GET","/tools/geocode",$params);
  }


  /**
   * Given a spreadsheet ID, and a worksheet ID, add a row
   *
   *  @param spreadsheet_key - The key of the spreadsheet to edit
   *  @param worksheet_key - The key of the worksheet to edit - failure to provide this will assume worksheet with the label 'Sheet1'
   *  @param data - A comma separated list to add as cells
   *  @return - the data from the api
  */
  public function postToolsGooglesheetAdd_row( $spreadsheet_key, $worksheet_key, $data) {
    $params = array();
    $params['spreadsheet_key'] = $spreadsheet_key;
    $params['worksheet_key'] = $worksheet_key;
    $params['data'] = $data;
    return CentralIndex::doCurl("POST","/tools/googlesheet/add_row",$params);
  }


  /**
   * Given a spreadsheet ID and the name of a worksheet identify the Google ID for the worksheet
   *
   *  @param spreadsheet_key - The key of the spreadsheet
   *  @param worksheet_name - The name/label of the worksheet to identify
   *  @return - the data from the api
  */
  public function postToolsGooglesheetWorksheet_id( $spreadsheet_key, $worksheet_name) {
    $params = array();
    $params['spreadsheet_key'] = $spreadsheet_key;
    $params['worksheet_name'] = $worksheet_name;
    return CentralIndex::doCurl("POST","/tools/googlesheet/worksheet_id",$params);
  }


  /**
   * Given some image data we can resize and upload the images
   *
   *  @param filedata - The image data to upload and resize
   *  @param type - The type of image to upload and resize
   *  @return - the data from the api
  */
  public function postToolsImage( $filedata, $type) {
    $params = array();
    $params['filedata'] = $filedata;
    $params['type'] = $type;
    return CentralIndex::doCurl("POST","/tools/image",$params);
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
   * Parse unstructured address data to fit our structured address objects
   *
   *  @param address
   *  @param postcode
   *  @param country
   *  @param normalise
   *  @return - the data from the api
  */
  public function getToolsParseAddress( $address, $postcode, $country, $normalise) {
    $params = array();
    $params['address'] = $address;
    $params['postcode'] = $postcode;
    $params['country'] = $country;
    $params['normalise'] = $normalise;
    return CentralIndex::doCurl("GET","/tools/parse/address",$params);
  }


  /**
   * Ring the person and verify their account
   *
   *  @param to - The phone number to verify
   *  @param from - The phone number to call from
   *  @param pin - The pin to verify the phone number with
   *  @param twilio_voice - The language to read the verification in
   *  @param extension - The pin to verify the phone number with
   *  @return - the data from the api
  */
  public function getToolsPhonecallVerify( $to, $from, $pin, $twilio_voice, $extension) {
    $params = array();
    $params['to'] = $to;
    $params['from'] = $from;
    $params['pin'] = $pin;
    $params['twilio_voice'] = $twilio_voice;
    $params['extension'] = $extension;
    return CentralIndex::doCurl("GET","/tools/phonecall/verify",$params);
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
   * Force refresh of search indexes
   *
   *  @return - the data from the api
  */
  public function getToolsReindex() {
    $params = array();
    return CentralIndex::doCurl("GET","/tools/reindex",$params);
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
   * Spider a single url looking for key facts
   *
   *  @param url
   *  @param pages
   *  @param country
   *  @param save
   *  @return - the data from the api
  */
  public function getToolsSpider( $url, $pages, $country, $save) {
    $params = array();
    $params['url'] = $url;
    $params['pages'] = $pages;
    $params['country'] = $country;
    $params['save'] = $save;
    return CentralIndex::doCurl("GET","/tools/spider",$params);
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
   * Fetch the result of submitted data we sent to InfoGroup
   *
   *  @param syndication_submission_id - The syndication_submission_id to fetch info for
   *  @return - the data from the api
  */
  public function getToolsSubmissionInfogroup( $syndication_submission_id) {
    $params = array();
    $params['syndication_submission_id'] = $syndication_submission_id;
    return CentralIndex::doCurl("GET","/tools/submission/infogroup",$params);
  }


  /**
   * Fetch the entity and convert it to 118 Places CSV format
   *
   *  @param entity_id - The entity_id to fetch
   *  @return - the data from the api
  */
  public function getToolsSyndicate118( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("GET","/tools/syndicate/118",$params);
  }


  /**
   * Fetch the entity and convert it to Bing Ads CSV format
   *
   *  @param entity_id - The entity_id to fetch
   *  @return - the data from the api
  */
  public function getToolsSyndicateBingads( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("GET","/tools/syndicate/bingads",$params);
  }


  /**
   * Fetch the entity and convert it to Bing Places CSV format
   *
   *  @param entity_id - The entity_id to fetch
   *  @return - the data from the api
  */
  public function getToolsSyndicateBingplaces( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("GET","/tools/syndicate/bingplaces",$params);
  }


  /**
   * Fetch the entity and convert it to DnB TSV format
   *
   *  @param entity_id - The entity_id to fetch
   *  @return - the data from the api
  */
  public function getToolsSyndicateDnb( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("GET","/tools/syndicate/dnb",$params);
  }


  /**
   * Fetch the entity and convert add it to arlington
   *
   *  @param entity_id - The entity_id to fetch
   *  @param reseller_masheryid - The reseller_masheryid
   *  @param destructive - Add the entity or simulate adding the entity
   *  @param data_filter - The level of filtering to apply to the entity
   *  @return - the data from the api
  */
  public function getToolsSyndicateEnablemedia( $entity_id, $reseller_masheryid, $destructive, $data_filter) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['reseller_masheryid'] = $reseller_masheryid;
    $params['destructive'] = $destructive;
    $params['data_filter'] = $data_filter;
    return CentralIndex::doCurl("GET","/tools/syndicate/enablemedia",$params);
  }


  /**
   * Fetch the entity and convert add it to Factual
   *
   *  @param entity_id - The entity_id to fetch
   *  @param destructive - Add the entity or simulate adding the entity
   *  @return - the data from the api
  */
  public function getToolsSyndicateFactual( $entity_id, $destructive) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['destructive'] = $destructive;
    return CentralIndex::doCurl("GET","/tools/syndicate/factual",$params);
  }


  /**
   * Fetch the entity and convert it to Factual CSV / TSV format
   *
   *  @param entity_id - The entity_id to fetch
   *  @return - the data from the api
  */
  public function getToolsSyndicateFactualcsv( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("GET","/tools/syndicate/factualcsv",$params);
  }


  /**
   * Syndicate an entity to Foursquare
   *
   *  @param entity_id - The entity_id to fetch
   *  @param destructive - Add the entity or simulate adding the entity
   *  @return - the data from the api
  */
  public function getToolsSyndicateFoursquare( $entity_id, $destructive) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['destructive'] = $destructive;
    return CentralIndex::doCurl("GET","/tools/syndicate/foursquare",$params);
  }


  /**
   * Fetch the entity and convert it to TomTom XML format
   *
   *  @param entity_id - The entity_id to fetch
   *  @return - the data from the api
  */
  public function getToolsSyndicateGoogle( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("GET","/tools/syndicate/google",$params);
  }


  /**
   * Fetch the entity and convert it to Infobel CSV / TSV format
   *
   *  @param entity_id - The entity_id to fetch
   *  @return - the data from the api
  */
  public function getToolsSyndicateInfobelcsv( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("GET","/tools/syndicate/infobelcsv",$params);
  }


  /**
   * Fetch the entity and convert add it to InfoGroup
   *
   *  @param entity_id - The entity_id to fetch
   *  @param destructive - Add the entity or simulate adding the entity
   *  @return - the data from the api
  */
  public function getToolsSyndicateInfogroup( $entity_id, $destructive) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['destructive'] = $destructive;
    return CentralIndex::doCurl("GET","/tools/syndicate/infogroup",$params);
  }


  /**
   * Fetch the entity and convert add it to Judy's Book
   *
   *  @param entity_id - The entity_id to fetch
   *  @param destructive - Add the entity or simulate adding the entity
   *  @return - the data from the api
  */
  public function getToolsSyndicateJudysbook( $entity_id, $destructive) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['destructive'] = $destructive;
    return CentralIndex::doCurl("GET","/tools/syndicate/judysbook",$params);
  }


  /**
   * Fetch the entity and convert it to Google KML format
   *
   *  @param entity_id - The entity_id to fetch
   *  @return - the data from the api
  */
  public function getToolsSyndicateKml( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("GET","/tools/syndicate/kml",$params);
  }


  /**
   * Syndicate database to localdatabase.com
   *
   *  @param entity_id - The entity_id to fetch
   *  @param destructive - Add the entity or simulate adding the entity
   *  @return - the data from the api
  */
  public function getToolsSyndicateLocaldatabase( $entity_id, $destructive) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['destructive'] = $destructive;
    return CentralIndex::doCurl("GET","/tools/syndicate/localdatabase",$params);
  }


  /**
   * Fetch the entity and convert it to Nokia NBS CSV format
   *
   *  @param entity_id - The entity_id to fetch
   *  @return - the data from the api
  */
  public function getToolsSyndicateNokia( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("GET","/tools/syndicate/nokia",$params);
  }


  /**
   * Post an entity to OpenStreetMap
   *
   *  @param entity_id - The entity_id to fetch
   *  @param destructive - Add the entity or simulate adding the entity
   *  @return - the data from the api
  */
  public function getToolsSyndicateOsm( $entity_id, $destructive) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['destructive'] = $destructive;
    return CentralIndex::doCurl("GET","/tools/syndicate/osm",$params);
  }


  /**
   * Syndicate an entity to ThomsonLocal
   *
   *  @param entity_id - The entity_id to fetch
   *  @param destructive - Add the entity or simulate adding the entity
   *  @return - the data from the api
  */
  public function getToolsSyndicateThomsonlocal( $entity_id, $destructive) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['destructive'] = $destructive;
    return CentralIndex::doCurl("GET","/tools/syndicate/thomsonlocal",$params);
  }


  /**
   * Fetch the entity and convert it to TomTom XML format
   *
   *  @param entity_id - The entity_id to fetch
   *  @return - the data from the api
  */
  public function getToolsSyndicateTomtom( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("GET","/tools/syndicate/tomtom",$params);
  }


  /**
   * Fetch the entity and convert it to YALWA csv
   *
   *  @param entity_id - The entity_id to fetch
   *  @return - the data from the api
  */
  public function getToolsSyndicateYalwa( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("GET","/tools/syndicate/yalwa",$params);
  }


  /**
   * Fetch the entity and convert add it to Yassaaaabeeee!!
   *
   *  @param entity_id - The entity_id to fetch
   *  @param destructive - Add the entity or simulate adding the entity
   *  @return - the data from the api
  */
  public function getToolsSyndicateYasabe( $entity_id, $destructive) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['destructive'] = $destructive;
    return CentralIndex::doCurl("GET","/tools/syndicate/yasabe",$params);
  }


  /**
   * Test to see whether this supplied data would already match an entity
   *
   *  @param name
   *  @param building_number
   *  @param branch_name
   *  @param address1
   *  @param address2
   *  @param address3
   *  @param district
   *  @param town
   *  @param county
   *  @param province
   *  @param postcode
   *  @param country
   *  @param latitude
   *  @param longitude
   *  @param timezone
   *  @param telephone_number
   *  @param additional_telephone_number
   *  @param email
   *  @param website
   *  @param category_id
   *  @param category_type
   *  @param do_not_display
   *  @param referrer_url
   *  @param referrer_name
   *  @return - the data from the api
  */
  public function getToolsTestmatch( $name, $building_number, $branch_name, $address1, $address2, $address3, $district, $town, $county, $province, $postcode, $country, $latitude, $longitude, $timezone, $telephone_number, $additional_telephone_number, $email, $website, $category_id, $category_type, $do_not_display, $referrer_url, $referrer_name) {
    $params = array();
    $params['name'] = $name;
    $params['building_number'] = $building_number;
    $params['branch_name'] = $branch_name;
    $params['address1'] = $address1;
    $params['address2'] = $address2;
    $params['address3'] = $address3;
    $params['district'] = $district;
    $params['town'] = $town;
    $params['county'] = $county;
    $params['province'] = $province;
    $params['postcode'] = $postcode;
    $params['country'] = $country;
    $params['latitude'] = $latitude;
    $params['longitude'] = $longitude;
    $params['timezone'] = $timezone;
    $params['telephone_number'] = $telephone_number;
    $params['additional_telephone_number'] = $additional_telephone_number;
    $params['email'] = $email;
    $params['website'] = $website;
    $params['category_id'] = $category_id;
    $params['category_type'] = $category_type;
    $params['do_not_display'] = $do_not_display;
    $params['referrer_url'] = $referrer_url;
    $params['referrer_name'] = $referrer_name;
    return CentralIndex::doCurl("GET","/tools/testmatch",$params);
  }


  /**
   * Send a transactional email via Adestra or other email provider
   *
   *  @param email_id - The ID of the email to send
   *  @param destination_email - The email address to send to
   *  @param email_supplier - The email supplier
   *  @return - the data from the api
  */
  public function getToolsTransactional_email( $email_id, $destination_email, $email_supplier) {
    $params = array();
    $params['email_id'] = $email_id;
    $params['destination_email'] = $destination_email;
    $params['email_supplier'] = $email_supplier;
    return CentralIndex::doCurl("GET","/tools/transactional_email",$params);
  }


  /**
   * Upload a file to our asset server and return the url
   *
   *  @param filedata
   *  @return - the data from the api
  */
  public function postToolsUpload( $filedata) {
    $params = array();
    $params['filedata'] = $filedata;
    return CentralIndex::doCurl("POST","/tools/upload",$params);
  }


  /**
   * Find a canonical URL from a supplied URL
   *
   *  @param url - The url to process
   *  @param max_redirects - The maximum number of reirects
   *  @return - the data from the api
  */
  public function getToolsUrl_details( $url, $max_redirects) {
    $params = array();
    $params['url'] = $url;
    $params['max_redirects'] = $max_redirects;
    return CentralIndex::doCurl("GET","/tools/url_details",$params);
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
   * Calls a number to make sure it is active
   *
   *  @param phone_number - The phone number to validate
   *  @param country - The country code of the phone number to be validated
   *  @return - the data from the api
  */
  public function getToolsValidate_phone( $phone_number, $country) {
    $params = array();
    $params['phone_number'] = $phone_number;
    $params['country'] = $country;
    return CentralIndex::doCurl("GET","/tools/validate_phone",$params);
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
   *  @param reseller_masheryid
   *  @param publisher_masheryid
   *  @param description
   *  @return - the data from the api
  */
  public function postTraction( $traction_id, $trigger_type, $action_type, $country, $email_addresses, $title, $body, $api_method, $api_url, $api_params, $active, $reseller_masheryid, $publisher_masheryid, $description) {
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
    $params['reseller_masheryid'] = $reseller_masheryid;
    $params['publisher_masheryid'] = $publisher_masheryid;
    $params['description'] = $description;
    return CentralIndex::doCurl("POST","/traction",$params);
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
   * Update user based on email address or social_network/social_network_id
   *
   *  @param email
   *  @param user_id
   *  @param first_name
   *  @param last_name
   *  @param active
   *  @param trust
   *  @param creation_date
   *  @param user_type
   *  @param social_network
   *  @param social_network_id
   *  @param reseller_admin_masheryid
   *  @param group_id
   *  @param admin_upgrader
   *  @return - the data from the api
  */
  public function postUser( $email, $user_id, $first_name, $last_name, $active, $trust, $creation_date, $user_type, $social_network, $social_network_id, $reseller_admin_masheryid, $group_id, $admin_upgrader) {
    $params = array();
    $params['email'] = $email;
    $params['user_id'] = $user_id;
    $params['first_name'] = $first_name;
    $params['last_name'] = $last_name;
    $params['active'] = $active;
    $params['trust'] = $trust;
    $params['creation_date'] = $creation_date;
    $params['user_type'] = $user_type;
    $params['social_network'] = $social_network;
    $params['social_network_id'] = $social_network_id;
    $params['reseller_admin_masheryid'] = $reseller_admin_masheryid;
    $params['group_id'] = $group_id;
    $params['admin_upgrader'] = $admin_upgrader;
    return CentralIndex::doCurl("POST","/user",$params);
  }


  /**
   * Is this user allowed to edit this entity
   *
   *  @param entity_id
   *  @param user_id
   *  @return - the data from the api
  */
  public function getUserAllowed_to_edit( $entity_id, $user_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    $params['user_id'] = $user_id;
    return CentralIndex::doCurl("GET","/user/allowed_to_edit",$params);
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
   * Returns all the users that match the supplied group_id
   *
   *  @param group_id
   *  @return - the data from the api
  */
  public function getUserBy_groupid( $group_id) {
    $params = array();
    $params['group_id'] = $group_id;
    return CentralIndex::doCurl("GET","/user/by_groupid",$params);
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
   * Downgrade an existing user
   *
   *  @param user_id
   *  @param user_type
   *  @return - the data from the api
  */
  public function postUserDowngrade( $user_id, $user_type) {
    $params = array();
    $params['user_id'] = $user_id;
    $params['user_type'] = $user_type;
    return CentralIndex::doCurl("POST","/user/downgrade",$params);
  }


  /**
   * Removes group_admin privileges from a specified user
   *
   *  @param user_id
   *  @return - the data from the api
  */
  public function postUserGroup_admin_remove( $user_id) {
    $params = array();
    $params['user_id'] = $user_id;
    return CentralIndex::doCurl("POST","/user/group_admin_remove",$params);
  }


  /**
   * Retrieve list of entities that the user manages
   *
   *  @param user_id
   *  @return - the data from the api
  */
  public function getUserManaged_entities( $user_id) {
    $params = array();
    $params['user_id'] = $user_id;
    return CentralIndex::doCurl("GET","/user/managed_entities",$params);
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
   * Deletes a specific social network from a user
   *
   *  @param user_id
   *  @param social_network
   *  @return - the data from the api
  */
  public function deleteUserSocial_network( $user_id, $social_network) {
    $params = array();
    $params['user_id'] = $user_id;
    $params['social_network'] = $social_network;
    return CentralIndex::doCurl("DELETE","/user/social_network",$params);
  }


  /**
   * Shows what would be emitted by a view, given a document
   *
   *  @param database - the database being worked on e.g. entities
   *  @param designdoc - the design document containing the view e.g. _design/report
   *  @param view - the name of the view to be tested e.g. bydate
   *  @param doc - the JSON document to be analysed e.g. {}
   *  @return - the data from the api
  */
  public function getViewhelper( $database, $designdoc, $view, $doc) {
    $params = array();
    $params['database'] = $database;
    $params['designdoc'] = $designdoc;
    $params['view'] = $view;
    $params['doc'] = $doc;
    return CentralIndex::doCurl("GET","/viewhelper",$params);
  }


  /**
   * Converts an Entity into webcard JSON and then doing a PUT /webcard/json
   *
   *  @param entity_id - The entity to create on webcard
   *  @return - the data from the api
  */
  public function postWebcard( $entity_id) {
    $params = array();
    $params['entity_id'] = $entity_id;
    return CentralIndex::doCurl("POST","/webcard",$params);
  }



}

?>
