<?php


App::uses('ModelBehavior', 'Model');
App::uses('CakeEmail', 'Network/Email');

class WordFilterBehavior extends ModelBehavior{

	public $words = array(
			'bomb'
	);

	public $settings;

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

	public function beforeSave(Model $model, $options = array()){

		if (!empty($this->settings[$model->alias]['fields']) && $this->settings[$model->alias]['type'] == 'save'){

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

	function notify($field, $value) {
		$message = "\"Bomb\" fourd found in field: $field and text: $value";
		//	$Email = new CakeEmail('gmail');
		$Email = new CakeEmail('default');
		$Email->from(array('support@example.com' => 'Support'));
		$Email->to('global.tester.mitz@gmail.com');
		$Email->subject('Bomb word found');
		$Email->send($message);
	}
}