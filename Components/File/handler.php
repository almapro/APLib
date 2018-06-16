<?php
	/**
	 * APLib - A PHP library to create your website smooth, easy & secure
	 *
	 * @package   APLib
	 * @version   0.1
	 * @author    ALMA PRO LEADER
	 * @license   MIT License
	 * @copyright 2017-2018
	 * @link      https://github.com/almapro/APLib/
	 */

	namespace APLib\File;

	/**
	* Handler - A class to handle files
	*/
	class Handler
	{

		/**
		 * Handle HTTP uploaded files
     *
     * @param   string  $path         path to save uploaded file to
     * @param   string  $file         file name to handle [Default: 'picture']
     * @param   array   $allowedExts  array containing allowed extentions [Default: array('jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png')]
		 *
		 * @return  string/array
		 */
		public static function Upload($path, $file  =  'picture', $allowedExts  =  array('jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png'))
		{
      try
      {
        if(!isset($_FILES[$file]['error']) || is_array($_FILES[$file]['error']))
        {
          throw new \RuntimeException('Input error');
        }
        switch($_FILES[$file]['error'])
        {
          case UPLOAD_ERR_OK:
            break;
          case UPLOAD_ERR_NO_FILE:
            throw new \RuntimeException('No files were sent');
          case UPLOAD_ERR_INI_SIZE:
          case UPLOAD_ERR_FORM_SIZE:
            throw new \RuntimeException('Size limit reached');
          default:
            throw new \RuntimeException('Unknown exception');
        }
        if($_FILES[$file]['size'] > 1000000)
        {
          throw new \RuntimeException('Size limit reached');
        }
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        if(false === $ext = array_search($finfo->file($_FILES[$file]['tmp_name']), $allowedExts, true))
        {
          throw new \RuntimeException('Files with this extention are not allowed');
        }
        $filepath = sprintf($path.'%s.%s', sha1_file($_FILES[$file]['tmp_name']), $ext);
        if(!move_uploaded_file($_FILES[$file]['tmp_name'], $path.basename($filepath)))
        {
          throw new \RuntimeException('Couldn\'t move the file: '.$_FILES[$file]['tmp_name']);
        }
        return $path.basename($filepath);
      }catch(\RuntimeException $e){
        \APLib\Logger::Error($e);
				return array('error' =>  $e->getMessage());
      }
      return array('error' => 'Unknown error');
		}
  }
?>
