<?php
class UploadLocal extends SpecialPage {
	function __construct() {
		parent::__construct( 'UploadLocal',  'uploadlocal' );
	}

	public function doesWrites() {
		return true;
	}

	function execute( $par ) {
		global $wgRequest, $wgUploadLocalDirectory;
		
		$prefix = 'extensions/UploadLocal/';
		require($prefix . 'UploadLocalDirectory.php');
		require($prefix . 'UploadLocalForm.php');
		
		$directory = new UploadLocalDirectory($wgRequest, $wgUploadLocalDirectory);
		$directory->execute();
	}

	protected function getGroupName() {
		return 'media';
	}
}