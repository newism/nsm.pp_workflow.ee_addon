<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * NSM Publish Plus: Workflow Model 
 *
 * @package			NsmPublishPlusWorkflow
 * @version			0.0.1
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
	
	
	public function setNewReviewDate($date)
	{
		$this->last_review_date = $this->next_review_date;
		$this->next_review_date = $date;
	}
	
	
	/**
	 * PHP5 toString function.
	 * Returns a string version of class instance
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
	
	
	// retrieves data in a tag friendly ready way
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
	 * Returns array of report preset objects that appear in the array parameter
	 *
	 * @access public
	 * @static
	 * @param int $site_id Site ID
	 * @param int $entry_id Entry ID
	 * @param int $field_id Field ID
	 * @return array Collection of configuration presets
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
	 * Returns array of report preset objects that appear in the array parameter
	 *
	 * @access public
	 * @static
	 * @param int $site_id Site ID
	 * @param int $entry_id Entry ID
	 * @param int $field_id Field ID
	 * @return array Collection of configuration presets
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
	 * Returns array of report preset objects that appear in the array parameter
	 *
	 * @access public
	 * @static
	 * @param int $site_id Site ID
	 * @param int $entry_id Entry ID
	 * @param int $field_id Field ID
	 * @return array Collection of configuration presets
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
	 * Returns array of report preset objects that appear in the array parameter
	 *
	 * @access public
	 * @static
	 * @param int $site_id Site ID
	 * @param int $entry_id Entry ID
	 * @param int $field_id Field ID
	 * @return array Collection of configuration presets
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
	 * Returns array of report preset objects that appear in the array parameter
	 *
	 * @access public
	 * @static
	 * @param int $site_id Site ID
	 * @param int $entry_id Entry ID
	 * @param int $field_id Field ID
	 * @return array Collection of configuration presets
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
	 * Returns array of report preset objects that appear in the array parameter
	 *
	 * @access public
	 * @static
	 * @param int $site_id Site ID
	 * @param int $entry_id Entry ID
	 * @param int $field_id Field ID
	 * @return array Collection of configuration presets
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
	 * Create the model table
	 */
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