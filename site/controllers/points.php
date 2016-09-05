<?php
defined('_JEXEC') or die('Restricted access');
class ETCControllerPoints extends JControllerLegacy
{
	private $pass_ip;
	function __construct($config = array()){
		parent::__construct($config);
		jimport('joomla.log.log');
		JLog::addLogger(
			array(
				'text_file' => 'com_etc.connect_log.php',
				'text_entry_format' => '{DATETIME} {CLIENTIP} {MESSAGE}'
			),
			JLog::ALL,
			array('com_etc')
		);
		$allow_ips = JComponentHelper::getParams('com_etc')->get('allow_ips');
		$this->pass_ip = explode(',',$allow_ips);
	}
	public function initPoints(){
		if(in_array($_SERVER['REMOTE_ADDR'],$this->pass_ip)){
			JLog::add('initPoints',JLog::ALL,'com_etc',date('Y-m-d H:i:s'));
			$lang = JFactory::getLanguage();
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('user_id,profile_value')->from($db->qn('#__user_profiles'))->where($db->qn('profile_key') . ' = ' . $db->q('extendedProfile.count'))->where($db->qn('profile_value') . ' < ' . 10);
			$db->setQuery($query);
			$user_arr = $db->loadObjectList();
			foreach($user_arr as $row){
				$user = JFactory::getUser($row->user_id);
				$lang->load('com_etc', JPATH_SITE, $user->getParam('language'), true);
				$diff = 10-($row->profile_value);
				Points::record($row->user_id,JText::_('ETC_POINTS_INIT_LABEL'),JText::_('ETC_POINTS_INIT_DESC'),$diff);
			}
		}
		JError::raiseError(404,'');
	}
	
	public function mCloudupdatePoints(){
		$user_id = JRequest::getVar('user_id',NULL);
		$start_time = JRequest::getVar('start_time',NULL);
		$end_time = JRequest::getVar('end_time',NULL);
		$points = JRequest::getVar('points',NULL);
		$mobile_id = JRequest::getVar('mobile_id',NULL);
		if($user_id == NULL || $start_time == NULL || $end_time == NULL || $points == NULL || $mobile_id == NULL){
			JError::raiseError(404,'');
			exit;
		}
		$mobile = Mobile::getMobile($mobile_id,true);
		JLog::add('mCloudupdatePoints',JLog::ALL,'com_etc',date('Y-m-d H:i:s'));
		$html = '<div>選擇手機：'.$mobile->title . ' - ' . $mobile->model .'</div>';
		$html .= '<div>操作時間：'.$start_time . ' ~ ' . $end_time.'</div>';
		Points::record($user_id,'雲端手機操作，共扣除 '.abs($points) . ' 點',$html,$points,false);
	}
}