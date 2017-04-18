<?php

/**
 * CKFinder
 * ========
 * http://cksource.com/ckfinder
 * Copyright (C) 2007-2014, CKSource - Frederico Knabben. All rights reserved.
 *
 * The software, this file and its contents are subject to the CKFinder
 * License. Please read the license.txt file before using, installing, copying,
 * modifying or distribute this file or part of its contents. The contents of
 * this file is part of the Source Code of CKFinder.
 */
if (!defined('IN_CKFINDER')) exit;

/**
 * @package CKFinder
 * @subpackage CommandHandlers
 * @copyright CKSource - Frederico Knabben
 */

/**
 * Handle FileUpload command
 *
 * @package CKFinder
 * @subpackage CommandHandlers
 * @copyright CKSource - Frederico Knabben
 */
class CKFinder_Connector_CommandHandler_FileUpload extends CKFinder_Connector_CommandHandler_CommandHandlerBase
{
    /**
     * Command name
     *
     * @access protected
     * @var string
     */
    protected $command = "FileUpload";

    /**
     * send response (save uploaded file, resize if required)
     * @access public
     *
     */
    public function check_file_existed($fileName, $file_upload = ''){
		/*/var_dump($file);
		$error = array("HTTP/1.1 404 Not Found","HTTP/1.1 301 Moved Permanently");
		if($file=="" || $file==null) return false;
		$file_headers = @get_headers($file);
		if(!$file_headers) return false;
		for($i=0;$i<count($error);$i++){
			if($file_headers[0] == $error[$i]) {
				return false;
			}		
		}return true;
		*/
    	if(CONNECTED_FTP_MEDIA){
    		 
    		$_FTP = $GLOBALS['_FTP'];
    		$url = parse_url($this->_currentFolder->getUrl());
    		$file = $_FTP->config['root_directory'].'/'.$url['path'].'/'.$fileName;
    		$file = str_replace('//', '/', $file);
    		$existed = $_FTP->check_file_existed($file);
    		if($existed){
    			$file_url = $this->_currentFolder->getUrl() .'/'. $fileName;
    			if(@md5_file($file_url) == @md5_file($file_upload)){
    				return false;
    			}
    			return true;
    		}
    		return false;
    	}else{
    		$url = parse_url($this->_currentFolder->getUrl());
    		$file = $url['path'] .'/' .$fileName;
    		$file = str_replace('//', '/', $file);
    		$existed = file_exists($file);
    		if($existed){
    			$file_url = $this->_currentFolder->getUrl() .'/'. $fileName;
    			if(@md5_file($file_url) == @md5_file($file_upload)){
    				return false;
    			}
    			return true;
    		}
    		return false;
    	}
	} 
    public function sendResponse()
    {
        $iErrorNumber = CKFINDER_CONNECTOR_ERROR_NONE;

        $_config =& CKFinder_Connector_Core_Factory::getInstance("Core_Config");
        $oRegistry =& CKFinder_Connector_Core_Factory::getInstance("Core_Registry");
        $oRegistry->set("FileUpload_fileName", "unknown file");

        $uploadedFile = array_shift($_FILES);

        if (!isset($uploadedFile['name'])) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UPLOADED_INVALID);
        }

        $sUnsafeFileName = CKFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding(CKFinder_Connector_Utils_Misc::mbBasename($uploadedFile['name']));
        $sFileName =$sFileNameOrginal= CKFinder_Connector_Utils_FileSystem::secureFileName($sUnsafeFileName);

        if ($sFileName != $sUnsafeFileName) {
          $iErrorNumber = CKFINDER_CONNECTOR_ERROR_UPLOADED_INVALID_NAME_RENAMED;
        }
        
        ///
        $tmp_name = CKFinder_Connector_Utils_FileSystem::getFileNameWithoutExtension($sFileName);
        $exten = CKFinder_Connector_Utils_FileSystem::getExtension($sFileName);
        $sFileName = unMark($tmp_name). '.' .$exten;
        ///
        $oRegistry->set("FileUpload_fileName", $sFileName);

        $this->checkConnector();
        $this->checkRequest();

        if (!$this->_currentFolder->checkAcl(CKFINDER_CONNECTOR_ACL_FILE_UPLOAD)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        $_resourceTypeConfig = $this->_currentFolder->getResourceTypeConfig();
        if (!CKFinder_Connector_Utils_FileSystem::checkFileName($sFileName) || $_resourceTypeConfig->checkIsHiddenFile($sFileName)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_NAME);
        }

        $resourceTypeInfo = $this->_currentFolder->getResourceTypeConfig();
        if (!$resourceTypeInfo->checkExtension($sFileName)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_EXTENSION);
        }

        $oRegistry->set("FileUpload_fileName", $sFileName);
        $oRegistry->set("FileUpload_url", $this->_currentFolder->getUrl());

        $maxSize = $resourceTypeInfo->getMaxSize();
        if (!$_config->checkSizeAfterScaling() && $maxSize && $uploadedFile['size']>$maxSize) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UPLOADED_TOO_BIG);
        }

        $htmlExtensions = $_config->getHtmlExtensions();
        $sExtension = CKFinder_Connector_Utils_FileSystem::getExtension($sFileName);

        if ($htmlExtensions
        && !CKFinder_Connector_Utils_Misc::inArrayCaseInsensitive($sExtension, $htmlExtensions)
        && ($detectHtml = CKFinder_Connector_Utils_FileSystem::detectHtml($uploadedFile['tmp_name'])) === true ) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UPLOADED_WRONG_HTML_FILE);
        }

        $secureImageUploads = $_config->getSecureImageUploads();
        if ($secureImageUploads
        && ($isImageValid = CKFinder_Connector_Utils_FileSystem::isImageValid($uploadedFile['tmp_name'], $sExtension)) === false ) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UPLOADED_CORRUPT);
        }

        switch ($uploadedFile['error']) {
            case UPLOAD_ERR_OK:
                break;

            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UPLOADED_TOO_BIG);
                break;

            case UPLOAD_ERR_PARTIAL:
            case UPLOAD_ERR_NO_FILE:
                $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UPLOADED_CORRUPT);
                break;

            case UPLOAD_ERR_NO_TMP_DIR:
                $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UPLOADED_NO_TMP_DIR);
                break;

            case UPLOAD_ERR_CANT_WRITE:
                $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
                break;

            case UPLOAD_ERR_EXTENSION:
                $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
                break;
        }

        $sServerDir = $this->_currentFolder->getServerPath();
        //var_dump($sServerDir); exit; 
        //var_dump($this->_currentFolder->getUrl()); exit; 
        $iCounter = $icx = 0;
        while (true)
        {
        	$icx ++; if($icx > 10) {break; }
            $sFilePath = CKFinder_Connector_Utils_FileSystem::combinePaths($sServerDir, $sFileName);
            $sFilePath=str_replace('\\','',$sFilePath);
            //$sFilePath1=$sFilePath;
			$sFilePath .='/'.$sFileName;
            //var_dump($sFilePath);  

            if ($this->check_file_existed($sFileName,$uploadedFile['tmp_name'])) {
                $iCounter++;
                $sFileName =
                unMark(CKFinder_Connector_Utils_FileSystem::getFileNameWithoutExtension($sFileNameOrginal) ).
                "-" . $iCounter . "" . "." .
                CKFinder_Connector_Utils_FileSystem::getExtension($sFileNameOrginal);
				//
                $oRegistry->set("FileUpload_fileName", $sFileName);
//var_dump($sFileName);
                $iErrorNumber = CKFINDER_CONNECTOR_ERROR_UPLOADED_FILE_RENAMED;
            } else {
				$uploaded = false;
				///view($sFilePath); exit;
                if(CONNECTED_FTP_MEDIA){
                    $_FTP = $GLOBALS['_FTP'];
                   // var_dump($sFileName);
                   // var_dump($sServerDir);exit;
                    $uploaded = $_FTP->upload_file_ckeditor($sServerDir,$sFileName,$uploadedFile['tmp_name']);
                }else{
                    $uploaded = move_uploaded_file($uploadedFile['tmp_name'], $sFilePath);
                }
                //var_dump($uploaded);
                if (false === $uploaded) {
                    $iErrorNumber = CKFINDER_CONNECTOR_ERROR_ACCESS_DENIED;
                }
                else {
                    if (isset($detectHtml) && $detectHtml === -1 && CKFinder_Connector_Utils_FileSystem::detectHtml($sFilePath) === true) {
                        @unlink($sFilePath);
                        $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UPLOADED_WRONG_HTML_FILE);
                    }
                    else if (isset($isImageValid) && $isImageValid === -1 && CKFinder_Connector_Utils_FileSystem::isImageValid($sFilePath, $sExtension) === false) {
                        @unlink($sFilePath);
                        $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UPLOADED_CORRUPT);
                    }
                }
                if (is_file($sFilePath) && ($perms = $_config->getChmodFiles())) {
                    $oldumask = umask(0);
                    chmod($sFilePath, $perms);
                    umask($oldumask);
                }
                break;
            }
            //exit;
        }

        if (!$_config->checkSizeAfterScaling()) {
            $this->_errorHandler->throwError($iErrorNumber, true, false);
        }

        //resize image if required
        require_once CKFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/Thumbnail.php";
        $_imagesConfig = $_config->getImagesConfig();

        if ($_imagesConfig->getMaxWidth()>0 && $_imagesConfig->getMaxHeight()>0 && $_imagesConfig->getQuality()>0) {
            CKFinder_Connector_CommandHandler_Thumbnail::createThumb($sFilePath, $sFilePath, $_imagesConfig->getMaxWidth(), $_imagesConfig->getMaxHeight(), $_imagesConfig->getQuality(), true) ;
        }

        if ($_config->checkSizeAfterScaling()) {
            //check file size after scaling, attempt to delete if too big
            clearstatcache();
            if ($maxSize && filesize($sFilePath)>$maxSize) {
                @unlink($sFilePath);
                $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UPLOADED_TOO_BIG);
            }
            else {
                $this->_errorHandler->throwError($iErrorNumber, true, false);
            }
        }

        CKFinder_Connector_Core_Hooks::run('AfterFileUpload', array(&$this->_currentFolder, &$uploadedFile, &$sFilePath));
    }
}
