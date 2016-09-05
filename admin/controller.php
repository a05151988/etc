<?php
defined('_JEXEC') or die('Restricted access');
class ETCController extends JControllerLegacy
{
	protected $default_view = 'default';
	public function __construct($config = array()){
		parent::__construct($config);
	}
	public function display($cachable = false, $urlparams = false){
		parent::display();
	}
}