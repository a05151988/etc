<?php
defined('_JEXEC') or die('Restricted access');
class Com_PointManagerInstallerScript
{
	function install($parent) {
		echo "Install Complete";
	}

	function uninstall($parent) {
		echo "Uninstall Complete";
	}

	function update($parent) {
		echo "Update Complete";
	}
 
	function preflight($type, $parent) {
	}
 
	function postflight($type, $parent) {
	}
}