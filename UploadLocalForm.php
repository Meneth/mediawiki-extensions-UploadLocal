<?php
class UploadLocalForm {
	
	var $_error = '';
	var $_filename;
	
	/**
	 * With our parameters, simulate a request
	 */
	function UploadLocalForm($filename, $description, $watch, $dest) {
		global $wgUploadLocalDirectory;
		
		// Prepare our directory
		$dir = $wgUploadLocalDirectory;
		if ($dir[strlen($dir)-1] !== '/') $dir .= '/';
		
		$this->_filename = $filename;
		$this->comment = $description;
		$this->watch = $watch;
		$this->mDesiredDestName = $dest;
		$name = $dir . $filename;
		
		$this->upload = new UploadFromFile();
		$fileInfo = array(
			'name' => $dest,
			'size' => filesize( $name ),
			'tmp_name' => $name,
			'error' => 0
		);
		
		if( !class_exists( 'WebRequestUpload' ) ) {
			# Prior to 1.17, there was no WebRequestUpload class and the initialize function was a bit different
			$this->upload->initialize( $dest, $name, filesize( $name ) );
		} else {
			# For 1.17+
			$this->upload->initialize( $dest, new WebRequestUploadLocal( true, $fileInfo ) );
		}
	}
		
	function processUpload( $user ) {
		# Have to call this line to set the extension to avoid a VERIFICATION_ERROR
		$title = $this->upload->getTitle();
		
		if( $title == null ) {
			# We should check mTitleError or call validateName() here to get the specific problem with the file but since both are protected we can't. Protected === Bullshit.
			$this->uploadError( wfMsg( 'uploadlocal_error_badtitle', $this->getFilename() ) );
			return;
		}
		
		# Check for warnings like the file already exists in the wiki
		$warnings = $this->upload->checkWarnings();
		if ( $warnings && isset( $warnings['exists'] ) ) {
			if( $warnings['exists']['warning'] == 'exists' || $warnings['exists']['warning'] == 'page-exists' ) {
				$this->uploadError( wfMsg( 'uploadlocal_error_exists', $warnings['exists']['file']->getName() ) );
				return;
			} elseif( $warnings['exists']['warning'] == 'bad-prefix' ) {
				$this->uploadError( wfMsg( 'uploadlocal_error_badprefix', $warnings['exists']['file']->getName() ) );
				return;
			}
		}
		
		# Check for verifications that the upload succeded.
		$verification = $this->upload->verifyUpload();
		if ( $verification['status'] === UploadBase::OK ) {
			$this->upload->performUpload( $this->comment, $this->comment, $this->watch, $user );
	
			// Cleanup any temporary mess
			$this->upload->cleanupTempFile();
		} else {
			switch( $verification['status'] ) {
				case UploadBase::EMPTY_FILE:
					$this->uploadError( wfMsg( 'uploadlocal_error_empty' ) );
					break;
				case UploadBase::FILETYPE_MISSING:
					$this->uploadError( wfMsg( 'uploadlocal_error_missing' ) );
					break;
				case UploadBase::FILETYPE_BADTYPE:
					global $wgFileExtensions;
					$this->uploadError( wfMsg( 'uploadlocal_error_badtype' ) );
					break;
				case UploadBase::MIN_LENGTH_PARTNAME:
					$this->uploadError( wfMsg( 'uploadlocal_error_tooshort' ) );
					break;
				case UploadBase::ILLEGAL_FILENAME:
					$this->uploadError( wfMsg( 'uploadlocal_error_illegal' ) );
					break;
				case UploadBase::OVERWRITE_EXISTING_FILE:
					$this->uploadError( wfMsg( 'uploadlocal_error_overwrite' ) );
					break;
				case UploadBase::VERIFICATION_ERROR:
					$this->uploadError( wfMsg( 'uploadlocal_error_verify', $verification['details'][0] ) );
					break;
				case UploadBase::HOOK_ABORTED:
					$this->uploadError( wfMsg( 'uploadlocal_error_hook' ) );
					break;
				default:
					$this->uploadError( wfMsg( 'uploadlocal_error_unknown' ) );
					break;
			}
		}
	}
	
	function getFilename() {return $this->_filename;}
	function getUploadSaveName() {return $this->mDesiredDestName;}
		
	function mainUploadForm($msg='') {$this->_error = $msg;}
	function uploadError($error) {$this->_error = $error;}
	function showSuccess() {}
	
	function getError() {return $this->_error;}
}
