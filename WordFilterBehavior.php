<?php


App::uses('ModelBehavior', 'Model');
App::uses('CakeEmail', 'Network/Email');

class WordFilterBehavior extends ModelBehavior{

	/**
	Words to filter
	**/

	public $words = array(
			'bomb'
	);

	public $settings;

	/**
	Setting up the behavior
	**/

	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = array(
					'fields' => array('username', 'name'),
					'type' => 'save',
			);
		}
		$this->settings[$Model->alias] = array_merge(
				$this->settings[$Model->alias], (array)$settings);
	}

	/**
	Will fire before Saving
	**/

	public function beforeSave(Model $model, $options = array()){

		if (!empty($this->settings[$model->alias]['fields']) && $this->settings[$model->alias]['type'] == 'save'){
			//Looping through the data
			foreach($model->data as $row) {
				foreach($this->settings[$model->alias]['fields'] as $field) {
					if (isset($row[$field])){
						foreach($this->words as $word){
							if (preg_match("/$word/i", $row[$field])) {
								$this->notify($field, $row[$field]);
							}
						}
					}
				}
			}
		}
		return true;
	}

	/**
	Will fire the notification email.
	Please change the Email->to to your email
	**/

	public function notify($field, $value) {
		$message = "\"Bomb\" fourd found in field: $field and text: $value";
		//	$Email = new CakeEmail('gmail');
		$Email = new CakeEmail('default');
		$Email->from(array('support@example.com' => 'Support'));
		$Email->to('global.tester.mitz@gmail.com'); //change this to your email
		$Email->subject('Bomb word found');
		$Email->send($message);
	}
}