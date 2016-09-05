<?php
	function ETCBuildRoute(&$query){
		$segments = array();
		if(isset($query['task'])){
			$segments[0] = 'getUrl';
			$target = (isset($query['t'])) ? $query['t'] : '';
			$target = explode('.',$target);
			switch($target[0]){
				case 'c' : 
					$option = 'components';
					$obj = $target[1];
					break;
				case 'm' : 
					$option = 'modules';
					$obj = $target[1];
					break;
				case 'p' : 
					$option = 'plugins';
					$obj = $target[1];
					break;
				case 's' :
					$option = 'system';
					$obj = rand(0,999);
					break;
				default : 
					$option = rand(0,999);;
					$obj = rand(0,999);
					break;
			}
			$segments[1] = $option;
			$segments[2] = $obj;
			$segments[3] = $query['c'];
			$segments[4] = md5(strtotime(date('Y-m-d H:i:s')).rand(0,9999));
			unset($query['task']);
			unset($query['t']);
			unset($query['c']);
		}
		return $segments;
	}
	function ETCParseRoute($segments) {
		$vars = array();
		if($segments[0] == 'getUrl'){
			$vars['task'] = 'getLibUrl';
			$option = $segments[1];
			switch($option){
				case 'components' : 
					$vars['t'] = 'c.'.$segments[2];
					break;
				case 'modules' : 
					$vars['t'] = 'm.'.$segments[2];
					break;
				case 'plugins' :
					$vars['t'] = 'p.'.$segments[2];
					break;
				case 'system' : 
					$vars['t'] = 's';
					break;
			}
			$vars['c'] = $segments[3];
		}
		return $vars;
	}
?>