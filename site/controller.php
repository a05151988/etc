<?php
defined('_JEXEC') or die('Restricted access');
class ETCController extends JControllerLegacy
{
	function __construct($config = array()){
		parent::__construct($config);
	}
	public function getLibUrl(){
		if(empty($_SERVER['HTTP_REFERER']))
			JError::raiseError(404,'');
		$target = JRequest::getVar('t',NULL);
		$code = JRequest::getVar('c','');
		if($code == NULL)
			JError::raiseError(404,'');
		if($target == NULL){
			$path = JPATH_LIBRARIES . '/mylib/'.base64_decode($code);
		}else{
			$target = explode('.',$target);
			switch($target[0]){
				case 'c' : 
					$option = 'components';
					$prefix = 'com_';
					$path = JPATH_SITE . '/'. $option . '/' . $prefix.$target[1] . '/' . base64_decode($code);
					break;
				case 'm' : 
					$option = 'modules';
					$prefix = 'mod_';
					$path = JPATH_SITE . '/' . $option . '/' . $prefix.$target[1] . '/' . base64_decode($code);
					break;
				case 'p' : 
					$option = 'plugins';
					$path = JPATH_SITE . '/' . $option . '/' .$target[1].'/'.$target[2] . '/' . base64_decode($code);
					break;
				case 's' :
					$path = JPATH_SITE . '/' .base64_decode($code);
					break;
			}
			$path = str_replace('//','/',$path);
		}
		$extension = pathinfo($path,PATHINFO_EXTENSION);
		switch($extension){
			case 'css' :
				$contentType = 'text/css';
				break;
			case 'js' : 
				$contentType = 'application/javascript';
				break;
			default : 
				$contentType = $this->getContentType($path,$extension);
		}
		header('Content-type: ' . $contentType);
		readfile($path);
		JFactory::getApplication()->close();
	}
	public function getContentType($filename,$extension){
		if(!function_exists('mime_content_type')) {
			$mime_types = array(
				'txt'  => 'text/plain',
				'htm'  => 'text/html',
				'html' => 'text/html',
				'php'  => 'text/html',
				'css'  => 'text/css',
				'js'   => 'application/javascript',
				'json' => 'application/json',
				'xml'  => 'application/xml',
				'swf'  => 'application/x-shockwave-flash',
				'flv'  => 'video/x-flv',

				// images
				'png'  => 'image/png',
				'jpe'  => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'jpg'  => 'image/jpeg',
				'gif'  => 'image/gif',
				'bmp'  => 'image/bmp',
				'ico'  => 'image/vnd.microsoft.icon',
				'tiff' => 'image/tiff',
				'tif'  => 'image/tiff',
				'svg'  => 'image/svg+xml',
				'svgz' => 'image/svg+xml',

				// archives
				'zip'  => 'application/zip',
				'rar'  => 'application/x-rar-compressed',
				'exe'  => 'application/x-msdownload',
				'msi'  => 'application/x-msdownload',
				'cab'  => 'application/vnd.ms-cab-compressed',

				// audio/video
				'mp3'  => 'audio/mpeg',
				'qt'   => 'video/quicktime',
				'mov'  => 'video/quicktime',

				// adobe
				'pdf'  => 'application/pdf',
				'psd'  => 'image/vnd.adobe.photoshop',
				'ai'   => 'application/postscript',
				'eps'  => 'application/postscript',
				'ps'   => 'application/postscript',

				// ms office
				'doc'  => 'application/msword',
				'rtf'  => 'application/rtf',
				'xls'  => 'application/vnd.ms-excel',
				'ppt'  => 'application/vnd.ms-powerpoint',

				// open office
				'odt'  => 'application/vnd.oasis.opendocument.text',
				'ods'  => 'application/vnd.oasis.opendocument.spreadsheet',
			);
			if (array_key_exists($extension, $mime_types)) {
				return $mime_types[$extension];
			} elseif (function_exists('finfo_open')) {
				$finfo = finfo_open(FILEINFO_MIME);
				$mimetype = finfo_file($finfo, $filename);
				finfo_close($finfo);
				return $mimetype;
			} else {
				return 'application/octet-stream';
			}
		}else
			return mime_content_type($filename);
	}
}