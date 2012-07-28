<?php
/**
 * Internationalisation file for SpecialUploadLocal extension.
 *
 * @package MediaWiki
 * @subpackage Extensions
*/
$messages = array();

$messages['en'] = array(
	'uploadlocal-desc' => 'Allows users to link in files already on the server',
	'specialuploadlocal' => 'Upload local files',
	'uploadlocal' => 'Upload local files',
	'uploadlocal_directory_readonly' => 'The local upload directory ($1) is not writeable by the webserver.',
	'uploadlocaltext' => 'Use this form to mass upload files already on the server in the upload local directory.
You can find out more general information at [[Special:Upload|the regular upload file page]].',
	'uploadlocalbtn' => 'Upload local files',
	'nolocalfiles' => 'There are no files in the local upload folder. Try placing some files in "<code>$1</code>."',
	'uploadednolocalfiles' => 'You did not upload any files.',
	'allfilessuccessful' => 'All files uploaded successfully',
	'uploadlocalerrors' => 'Some files had errors',
	'allfilessuccessfultext' => 'All files uploaded successfully.
Return to [[{{int:mainpage}}]].',
	'uploadlocal_descriptions_append' => 'Append to description:',
	'uploadlocal_descriptions_prepend' => 'Prepend to description:',
	'uploadlocal_dest_file_append' => 'Append to destination filename:',
	'uploadlocal_dest_file_prepend' => 'Prepend to destination filename: ',
	'uploadlocal_file_list' => 'Files ready for upload',
	'uploadlocal_file_list_explanation' => "'''X''' indicates whether or not you want the file to be uploaded (uncheck to prevent a file from being processed).
'''W''' indicates whether you want the file added to your watchlist.",
	'uploadlocal_global_params' => 'Global parameters',
	'uploadlocal_global_params_explanation' => "What is entered here will automatically get added to the entries listed above.
This helps remove repetitive text such as categories and metadata.
To '''append''' is to add to the end of text, while to '''prepend''' means to add to the beginning of text.
Especially for descriptions, make sure you give a few linebreaks before/after the text.",

	'uploadlocal_error_badtitle' => 'The file name $1 is invalid. The name might be too long or the file extension may be prohibited.',
	'uploadlocal_error_empty' => 'The file you submitted was empty.',
	'uploadlocal_error_exists' => 'The file $1 already exists.',
	'uploadlocal_error_badprefix' => 'The file name $1 is prohibited by the wiki settings.',
	'uploadlocal_error_empty' => 'The file you submitted was empty.',
	'uploadlocal_error_missing' => 'The file is missing an extension.',
	'uploadlocal_error_badtype' => 'This type of file is banned.',
	'uploadlocal_error_tooshort' => 'The filename is too short.',
	'uploadlocal_error_illegal' => 'The filename is not allowed.',
	'uploadlocal_error_overwrite' => 'Overwriting an existing file is not allowed.',
	'uploadlocal_error_verify' => 'This file did not pass file verification: $1.',
	'uploadlocal_error_hook' => 'The modification you tried to make was aborted by an extension hook.',
	'uploadlocal_error_unknown' => 'An unknown error occurred.',

	'right-uploadlocal' => 'Upload files from the local machine',
	'action-uploadlocal' => 'upload files from the local machine',
);

$messages['de'] = array(
	'uploadlocal-desc' => 'Ermöglicht es Benutzern auf dem Server befindliche Dateien zu nutzen',
	'specialuploadlocal' => 'Lokale Dateien hochladen',
	'uploadlocal' => 'Lokale Dateien hochladen',
	'uploadlocal_directory_readonly' => 'Der lokale Upload Ordner ($1) ist nicht vom Webserver beschreibbar.',
	'uploadlocaltext' => 'Benutze dieses Formular um Dateien auf dem Server im lokalen Upload-Ordner.
Allgemeinen Informationen sind auf der Seite für [[Special:Upload|den regulären Dateiupload]] zu finden.',
	'uploadlocalbtn' => 'Lokale Dateien hochladen',
	'nolocalfiles' => 'Es gibt keine Dateiene im lokalen Upload-Ordner. Lade Dateien in "<code>$1</code>."',
	'uploadednolocalfiles' => 'Keine Dateien hochgeladen.',
	'allfilessuccessful' => 'Alle Dateien erfolgreich hochgeladen',
	'uploadlocalerrors' => 'Einige Dateien hatten Fehler',
	'allfilessuccessfultext' => 'Alle Dateien erfolgreich hochgeladen.
Zurück zur [[{{int:mainpage}}]].',
	'uploadlocal_descriptions_append' => 'Der Beschreibung anfügen:',
	'uploadlocal_descriptions_prepend' => 'Der Beschreibung voranstellen:',
	'uploadlocal_dest_file_append' => 'Dem Dateinamen anfügen:',
	'uploadlocal_dest_file_prepend' => 'Dem Dateinamen voranstellen: ',
	'uploadlocal_file_list' => 'Dateien fertig zum hochladen',
	'uploadlocal_file_list_explanation' => "'''X''' zeigt die Dateien die hochgeladen werden sollen (Häckchen entfernen um die Dateien auszuschließen).
'''W''' zeigt Dateien die zur Beobachtungsliste hinzugefügt werden sollen.",
	'uploadlocal_global_params' => 'Globale Parameter',
	'uploadlocal_global_params_explanation' => "Was hier eingetragen wird, wird automatisch den oben gelisteten Einträgen hinzugefügt.
Das hilft dabei wiederholten Text wie Kategorien und Metadaten zu entfernen.
'''anfügen''' fügt den Text am Ende ein, wohingegen '''voranstellen''' am Anfang des Textes eingefügt wird.
Bei der Beschreibung sollten einige Zeilenumbrüche am Anfang und Ende des Textes eingefügt werden.",

	'uploadlocal_error_badtitle' => 'Der Dateiname $1 ist unzulässig. Der Name ist zu lang oder der Datei-Suffix ist verboten.',
	'uploadlocal_error_empty' => 'Die gesendete Datei ist leer.',
	'uploadlocal_error_exists' => 'Die Datei $1 existiert bereits.',
	'uploadlocal_error_badprefix' => 'Der Dateiname $1 ist durch die Wiki Einstellungen verboten.',
	'uploadlocal_error_empty' => 'Die übertragene Datei war leer.',
	'uploadlocal_error_missing' => 'Dieser Datei fehlt ein Suffix.',
	'uploadlocal_error_badtype' => 'Dieser Dateitype wurde verbannt.',
	'uploadlocal_error_tooshort' => 'Der Dateiname ist zu kurz.',
	'uploadlocal_error_illegal' => 'Der Dateiname ist nicht zulässig.',
	'uploadlocal_error_overwrite' => 'Überschreiben bestehender Dateien ist nicht zulässig.',
	'uploadlocal_error_verify' => 'Diese Datei konnte nicht verifiziert werden: $1.',
	'uploadlocal_error_hook' => 'Die Modifikation wurde von einem Extension Hook unterbrochen.',
	'uploadlocal_error_unknown' => 'Ein unbekannter Fehler ist aufgetreten.',

	'right-uploadlocal' => 'Hochladen von Dateien von der lokalen Maschine',
	'action-uploadlocal' => 'hochladen von Dateien von der lokalen Maschine',
);
