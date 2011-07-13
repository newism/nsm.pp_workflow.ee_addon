<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * NSM Publish Plus: Workflow Model 
 *
 * @package			NsmPublishPlusWorkflow
 * @version			0.10.1
 * @author			Leevi Graham <http://leevigraham.com>
 * @copyright 		Copyright (c) 2007-2010 Newism <http://newism.com.au>
 * @license 		Commercial - please see LICENSE file included with this distribution
 * @link			http://expressionengine-addons.com/nsm-example-addon
 * @see				http://codeigniter.com/user_guide/general/models.html
 **/
class Nsm_pp_workflow_model {
	
	public $id = false;
	public $entry_id = false;
	public $channel_id = false;
	public $entry_state = false;
	public $last_review_date = false;
	public $next_review_date = false;
	public $site_id = false;
	
	private $EE;
	
	/**
	 * PHP5 constructor function.
	 *
	 * Creates an instance of the object and assigns any values that have been passed to the constructor to the object
	 *
	 * @access public
	 * @param array $data Information to be assigned to the object instance
	 * @return void
	 **/
	public function __construct($data = array()) {
		$this->EE =& get_instance();
		if(count($data) > 0){
			$this->setData($data);
		}
	}
	
	/**
	 * Sets a new Next Review Date and overwrites the Last Review Date with the previous Next Review Date value.
	 *
	 * @access public
	 * @param int Time-stamp of next review date
	 * @return string This instance's title
	 **/
	public function setNewReviewDate($date)
	{
		$this->last_review_date = $this->next_review_date;
		$this->next_review_date = $date;
	}
	
	
	/**
	 * PHP5 toString function.
	 * Returns a string version of class instance.
	 *
	 * @access public
	 * @return string This instance's title
	 **/
	public function __toString()
	{
		return 'Review ID '.$this->id;
	}
	
	
	/**
	 * Inserts the instance into the database.
	 *
	 * @access public
	 * @return int Inserted ID of preset
	 **/
	public function add()
	{
		$this->EE->db->insert(self::$table_name, $this->_prepareData());
		$id = $this->EE->db->insert_id();
		$this->id = $id;
		return $id;
	}

	/**
	 * Updates the saved preset details in the database with the new values.
	 *
	 * @access public
	 * @return int Number of affected rows (should be '1' if successful)
	 **/
	public function update()
	{
		$update = $this->EE->db->update(self::$table_name, $this->_prepareData(), array('id' => $this->id));
		if($update){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * Deletes the preset from the database
	 *
	 * @access public
	 * @return int Number of affected rows (should be '1' if successful)
	 **/
	public function delete()
	{
		$this->EE->db->delete(self::$table_name, array('id' => $this->id));
		return $this->EE->db->affected_rows();
	}
	
	/**
	 * Prepares object data for update/insert commands
	 *
	 * Only array keys that are present in the model's database table are included
	 *
	 * @access private
	 * @return array The modified data
	 */
	private function _prepareData()
	{
		$data = array();
		foreach (self::$table_fields as $key => $definition) {
			$new_value = "";
			switch ($key) {
				default:
					if(property_exists($this, $key)){
						$new_value = $this->{$key};
					}
				break;
			}
			if($key !== 'id'){
				$data[$key] = $new_value;
			}
		}
		return $data;
	}

	/**
	 * Assigns applicable data from array to the object
	 *
	 * Only array keys that correspond to properties of the model are processed 
	 *
	 * @access public
	 * @param array $data Data to be assigned to the object
	 * @return void
	 */
	public function setData($data = array()) {
		if(count($data) > 0){
			foreach ($data as $key => $value) {
				if(property_exists($this, $key)){
					switch($key){
						default:
							$new_value = $value;
						break;
					}
					$this->{$key} = $new_value;
				}
			}
		}
	}
	
	/**
	 * Returns an array representation of the object instance.
	 * 
	 * @access public
	 * @return array
	 */
	public function asArray()
	{
		$data = array();
		foreach (self::$table_fields as $key => $definition) {
			$new_value = "";
			switch ($key) {
				default:
					if(property_exists($this, $key)){
						$new_value = $this->{$key};
					}
				break;
			}
			$data[$key] = $new_value;
		}
		return $data;
	}
	
	/**
	 * Returns an array representation of the object instance in the EE tagdata format.
	 * 
	 * @access public
	 * @return array
	 */
	public function tag_data()
	{
		$as_array = $this->asArray();
		$tagdata = array();
		foreach ($as_array as $key => $value) {
			switch($key){
				default:
					$new_value = $value;
				break;
			}
			$tagdata['nsm_pp_'.$key] = $new_value;
		}
		return $tagdata;
	}
	
	
	
	/**
	 * Retrieves a workflow object containing workflow meta-data assigned to the Entry ID. 
	 *
	 * @access public
	 * @static
	 * @param int $entry_id Entry ID
	 * @return mixed If no results return false Else return an instance of the object
	 **/
	public static function findByEntryId($entry_id)
	{
		$EE =& get_instance();
		$EE->db->from(self::$table_name);
		$EE->db->where('entry_id', $entry_id);
		$EE->db->where('site_id', $EE->config->item('site_id'));
		$get_entries = $EE->db->get();
		$results = $get_entries->result_array();
		$entries = array();
		if(count($results) > 0){
			foreach($results as $result){
				return new Nsm_pp_workflow_model($result);
			}
		}else{
			return false;
		}
		return $entries;
	}
	
	
	
	/**
	 * Retrieves an array collection of workflow objects that are up for review.
	 *
	 * @access public
	 * @static
	 * @param array $channel_ids Array of channel IDs to search within
	 * @return mixed If no results return false Else return an array of objects
	 **/
	public static function findByReviewNow($channel_ids)
	{
		$EE =& get_instance();
		$EE->load->helper('date');
		
		$EE->db->where('next_review_date < '.now());
		$EE->db->where_in('channel_id', $channel_ids);
		$EE->db->where('entry_state != "review"');
		$EE->db->where('site_id', $EE->config->item('site_id'));
		$get_entries = $EE->db->get(self::$table_name);
		if($get_entries->num_rows() == 0){
			return array();
		}
		if(!$get_entries){
			return false;
		}
		$results = $get_entries->result_array();
		$entries = array();
		if(count($results) > 0){
			foreach($results as $result){
				$entries[] = new Nsm_pp_workflow_model($result);
			}
		}
		return $entries;
	}
	
	
	/**
	 * Extracts and returns the Entry IDs from the workflow metadata collection as an array
	 *
	 * @access public
	 * @static
	 * @param array $entries Array of Workflow objects
	 * @return array Collection of unique Entry IDs
	 **/
	public static function getCollectionEntryIds($entries)
	{
		$ids = array();
		for($i = 0, $m = count($entries); $i < $m; $i += 1){
			$ids[ $entries[$i]->entry_id ] = 1;
		}
		return array_keys($ids);
	}
	
	
	/**
	 * Update workflow table entries entry state that are up for review.
	 *
	 * @access public
	 * @static
	 * @param array $channel_ids Array of channel IDs to search within
	 * @return bool Database status
	 **/
	public static function updateEntryState($channel_ids)
	{
		$EE =& get_instance();
		$EE->load->helper('date');
		
		$EE->db->where('`next_review_date` < '.now());
		$EE->db->where_in('channel_id', $channel_ids);
		$EE->db->where('entry_state != "review"');
		$EE->db->where('site_id', $EE->config->item('site_id'));
		if( $EE->db->update(self::$table_name, array('entry_state'=>'review')) ){
			return true;
		}else{
			return false;
		}
	}
	
	
	/**
	 * Find workflow entry matching the workflow ID.
	 *
	 * @access public
	 * @static
	 * @param int $id Workflow ID
	 * @return mixed If no results return false Else return an instance of the object
	 **/
	public static function findById($id)
	{
		$EE =& get_instance();
		$EE->db->from(self::$table_name);
		$EE->db->where('id', $id);
		$EE->db->where('site_id', $EE->config->item('site_id'));
		$EE->db->limit(1);
		$get_entry = $EE->db->get();
		$results = $get_entry->result_array();
		$entry = array();
		if(count($results) > 0){
			foreach($results as $result){
				return new Nsm_pp_workflow_model($result);
			}
		}else{
			return false;
		}
		return $entry;
	}
	
	
	/**
	 * Finds all workflow entries that have status of review.
	 *
	 * @access public
	 * @static
	 * @param array $channel_ids Array of channel IDs to search within
	 * @return mixed If no results return false Else return an array of objects
	 **/
	public static function findStateReview($channel_ids)
	{
		$EE =& get_instance();
		$EE->db->from(self::$table_name);
		$EE->db->where('entry_state = "review"');
		$EE->db->where_in('channel_id', $channel_ids);
		$EE->db->where('site_id', $EE->config->item('site_id'));
		$get_entries = $EE->db->get();
		$results = $get_entries->result_array();
		$entries = array();
		if(count($results) > 0){
			foreach($results as $result){
				$entries[] = new Nsm_pp_workflow_model($result);
			}
		}
		return $entries;
	}
	
	
	/**
	 * Finds all workflow entries that match the status parameter.
	 *
	 * @access public
	 * @static
	 * @param string $state Workflow state to search entries by
	 * @param array $channel_ids Array of channel IDs to search within
	 * @return mixed If no results return false Else return an array of objects
	 **/
	public static function findByState($state, $channel_ids)
	{
		$EE =& get_instance();
		$EE->db->from(self::$table_name);
		$EE->db->where('entry_state = "'.$state.'"');
		$EE->db->where_in('channel_id', $channel_ids);
		$EE->db->where('site_id', $EE->config->item('site_id'));
		$get_entries = $EE->db->get();
		$results = $get_entries->result_array();
		$entries = array();
		if(count($results) > 0){
			foreach($results as $result){
				$entries[] = new Nsm_pp_workflow_model($result);
			}
		}
		return $entries;
	}
	
	
	/**
	 * Returns the table name used by this model.
	 *
	 * @access public
	 * @static
	 * @return string Table name
	 **/
	public static function getTableName()
	{
		return self::$table_name;
	}
	
	/**
	 * The model table
	 * 
	 * @var string
	 */
	private static $table_name = "nsm_pp_workflow_entries";

	/**
	 * The model table fields
	 * 
	 * @var array
	 */
	private static $table_fields = array(
		"id" 				=> array('type' => 'INT', 'constraint' => '10', 'auto_increment' => TRUE, 'unsigned' => TRUE),
		"entry_id" 			=> array('type' => 'INT', 'constraint' => '10'),
		"channel_id" 		=> array('type' => 'INT', 'constraint' => '10'),
		"entry_state" 		=> array('type' => 'VARCHAR', 'constraint' => '10'),
		"last_review_date" 	=> array('type' => 'INT', 'constraint' => '10'),
		"next_review_date" 	=> array('type' => 'INT', 'constraint' => '10'),
		"site_id" 			=> array('type' => 'INT', 'constraint' => '10')
	);

	/**
	 * Create the database table.
	 *
	 * @access public
	 * @static
	 * @return void
	 **/
	public static function createTable() {
		$EE =& get_instance();
		$EE->load->dbforge();
		$EE->dbforge->add_field(self::$table_fields);
		$EE->dbforge->add_key('id', TRUE);

		if (!$EE->dbforge->create_table(self::$table_name, TRUE)) {
			show_error("Unable to create table in ".__CLASS__.": " . $EE->config->item('db_prefix') . self::$table_name);
			log_message('error', "Unable to create settings table for ".__CLASS__.": " . $EE->config->item('db_prefix') . self::$table_name);
		}
	}
}