<?php
class UploadLocalDirectory {
	
	public $_directory;
	public $_was_posted;
	public $_available_files;
	
	function UploadLocalDirectory($request, $directory) {
		$this->_directory = $directory;
		$this->_was_posted = $request->wasPosted();
		$this->_available_files = $this->getFilenamesOfFilesInDirectory($directory);
	}
	
	function execute() {
		global $wgOut, $wgRequest, $wgUser, $wgEnableUploads,
		  $wgUploadDirectory;
		  
		$wgOut->setPageTitle( wfMessage('specialuploadlocal') );
		
		// a bit of this is stolen from the SpecialUpload code, duplication
		// is bad but there was no way to meaningfully integrate it into
		// this code.
		
		if( ! $wgEnableUploads ) {
			$wgOut->addWikiText( wfMessage( 'uploaddisabled' )->text() );
			return;
		}
		
		// uses extension specific permission "uploadlocal"
		if (!$wgUser->isAllowed( 'uploadlocal' ) || $wgUser->isBlocked() ) {
			$wgOut->errorpage( 'uploadnologin', 'uploadnologintext' );
			return;
		}
		
		if( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		
		// check if both relevant directories are writeable
		if ( !is_writeable( $wgUploadDirectory ) ) {
			$wgOut->addWikiText( wfMessage( 'upload_directory_read_only', $wgUploadDirectory )->text() );
			return;
		}
		if ( !is_writeable($this->_directory) ) {
			$wgOut->addWikiText( wfMessage( 'upload_directory_read_only', $this->_directory )->text() );
			return;
		}
		
		// check if there are any files to upload
		if (empty($this->_available_files)) {
			$wgOut->addWikitext( wfMessage( 'uploadlocaltext' )->text() );
			$wgOut->addWikitext( wfMessage( 'nolocalfiles', $this->_directory )->escaped() );
			return;
		}
		
		if ($this->_was_posted) {
			$this->processUploads($wgRequest,$wgUser);
		} else {
			$this->showForm();
		}
		
	}
	
	function showForm() {
		global $wgOut;
		$wgOut->addWikitext( wfMessage( 'uploadlocaltext' )->text() );
		
		$titleObj = Title::makeTitle( NS_SPECIAL, 'UploadLocal' );
		$action = htmlspecialchars( $titleObj->getLocalURL() );
		
		$wgOut->addHTML('<form id="uploadlocal" method="post"'.
			' enctype="multipart/form-data" action="'.$action.'">');
		
		$wgOut->addWikitext('==' . wfMessage('uploadlocal_file_list')->text() . '==');
		$wgOut->addWikitext(wfMessage('uploadlocal_file_list_explanation')->text());
		
		$html = '';
		
		$html .= '<table border="0" cellspacing="0" style="width:100%;">';
		$html .= '<theader>'.
			'<tr><th style="width:1em;">X</th>'.
			'<th style="width:1em;">W</th>'.
			'<th style="width:10em;text-align:left;">'.wfMessage('sourcefilename')->escaped().'</th>'.
			'<th style="width:10em;text-align:left;">'.wfMessage('destfilename')->escaped().'</th>'.
			'<th style="text-align:left;">'.wfMessage('filedesc')->escaped().'</th></tr></theader>';
		$html .= '<tbody>';
		$i = 1;
		foreach ($this->_available_files as $file) {
			$html_file = htmlentities($file);
			$html .= '<tr' .
				($i % 2 ? ' style="background-color:#EEE;"' : '') .
				'>'.
				'<td><input type="checkbox" checked="checked"'.
				' name="wpUploadFiles['.$i.']" value="'.$html_file.'" /></td>'.
				'<td><input type="checkbox" checked="checked"'.
				' name="wpWatchThese['.$i.']" value="'.$html_file.'" /></td>'.
				'<td>'.$html_file.'</td>'.
				'<td><input type="text" name="wpDestFiles['.$i.']"'.
				' value="'.$html_file.'" /></td>'.
				'<td style="width:50%;">'.
				'<textarea name="wpUploadDescriptions['.$i.']" cols="60" '.
				'rows="2" style="width:100%;"></textarea></td>'.
				'</tr>';
			$i++;
		}
		$html .= '</tbody></table>';
		
		$wgOut->addHTML($html);
		$wgOut->addWikitext('==' . wfMessage('uploadlocal_global_params')->text() . '==');
		$wgOut->addWikitext(wfMessage('uploadlocal_global_params_explanation')->text());
		$html = '';
		
		$html .= '<table border="0" style="width:100%;">';
		$html .= '<tbody>'.
			'<tr><th style="text-align:right;width:25%;">'.wfMessage('uploadlocal_descriptions_prepend')->escaped().'</th>'.
			'<td><textarea cols="70" rows="3" style="width:100%;" name="wpUploadDescriptionsPrepend"></textarea></td></tr>'.
			
			'<tr><th style="text-align:right;">'.wfMessage('uploadlocal_descriptions_append')->escaped().'</th>'.
			'<td><textarea cols="70" rows="3" style="width:100%;" name="wpUploadDescriptionsAppend"></textarea></td></tr>'.
			
			'<tr><th style="text-align:right;">'.wfMessage('uploadlocal_dest_file_prepend')->escaped().'</th>'.
			'<td><input type="text" style="width:100%;" name="wpDestFilesPrepend" /></td></tr>'.
			
			'<tr><th style="text-align:right;">'.wfMessage('uploadlocal_dest_file_append')->escaped().'</th>'.
			'<td><input type="text" style="width:100%;" name="wpDestFilesAppend" /></td></tr>'.
			
			'</tbody>';
		$html .= '</table>';
		
		$html .= '<input type="submit" name="wpUploadLocal" value="'.wfMessage('uploadlocalbtn')->escaped().'" /></form>';
		
		$wgOut->addHTML($html);
	}
	
	function getFilenamesOfFilesInDirectory($directory) {
		if (! is_dir($directory) || ! is_readable($directory)) return array();
		$dh = opendir($directory);
		$filenames = array();
		# Make sure the handle opens correctly
		if( !$dh ) {
			return $filenames; 
		}
		while (($file = readdir($dh)) !== false) {
			if ($file == '.' || $file == '..') continue;
			// check if it's a directory
			if (is_dir($directory . '/' . $file)) continue;
			// check if it's a hidden file - regexp: /\.[^.]+/
			if ($file[0] == '.' && strpos($file,'.',1) === false) {
				continue;
			}
			$filenames[] = $file;
		}
		sort($filenames);
		closedir($dh);
		return $filenames;
	}
	
	function processUploads( $request, $user ) {
		global $wgOut, $wgContLang;
		
		$r_files		= $request->getArray('wpUploadFiles', array());
		$r_descriptions = $request->getArray('wpUploadDescriptions', array());
		$r_watch_these  = $request->getArray('wpWatchThese', array());
		$r_dest_files   = $request->getArray('wpDestFiles', array());
		
		$r_descriptions_prepend = $request->getVal('wpUploadDescriptionsPrepend' , '');
		$r_descriptions_append  = $request->getVal('wpUploadDescriptionsAppend', '');
		$r_dest_files_prepend   = $request->getVal('wpDestFilesPrepend', '');
		$r_dest_files_append	= $request->getVal('wpDestFilesAppend', '');
		
		$files_to_process = array();
		$forms = array();
		
		foreach ($r_files as $i => $file) {
			if (!in_array($file, $this->_available_files)) continue;
			// Build destination filename
			$r_dest_file = $r_dest_files_prepend . (isset($r_dest_files[$i]) ?
				$r_dest_files[$i] : $file);
			$p = strrpos($r_dest_file, '.');
			if ($p !== false) {
				$r_dest_file = substr($r_dest_file, 0, $p) . $r_dest_files_append
					. substr($r_dest_file, $p);
			} else {
				$r_dest_file = $r_dest_file . $r_dest_files_append;
			}
			
			$forms[] = new UploadLocalForm(
			   $file,
			   $r_descriptions_prepend . ($r_descriptions_prepend ? "\n" : '') .
				 (isset($r_descriptions[$i]) ? $r_descriptions[$i] : '') .
				 ($r_descriptions_append ? "\n" : '') . $r_descriptions_append,
			   in_array($file, $r_watch_these),
			   $r_dest_file
			);
			
		}
		
		if (empty($forms)) {
			$wgOut->addWikitext( wfMessage( 'uploadednolocalfiles' )->text() );
			return;
		}
		$no_error = true;
		$errors = array();
		
		foreach($forms as $key => $value) {
			$forms[$key]->processUpload( $user );
			if ($forms[$key]->getError()) {
				$errors[$forms[$key]->getFilename()] = $forms[$key]->getError();
				unset($forms[$key]);
			}
		}
		//ugly hack to stop the thing from redirecting. Really annoying.
		$wgOut->redirect('');
		
		if (empty($errors)) {
			$wgOut->setPageTitle(wfMessage('allfilessuccessful'));
			$wgOut->addWikitext(wfMessage('allfilessuccessfultext')->text());
		} else {
			$wgOut->setPageTitle(wfMessage('uploadlocalerrors'));
			$wgOut->addHTML('<ul>');
			foreach ($errors as $name => $error) {
				$wgOut->addHTML('<li>'.$name.' - '.$error.'</li>');
			}
			$wgOut->addHTML('</ul>');
		}
		
		$links_to_images = '';
		foreach($forms as $key => $form) {
			// language-neutral namespacing thanks to Eric Lemoine
			$links_to_images .= '* [[:'.$wgContLang->getNsText( NS_IMAGE ).':'.$forms[$key]->getUploadSaveName().']]'."\n";
		}
		$wgOut->addWikitext($links_to_images);
	}
}
