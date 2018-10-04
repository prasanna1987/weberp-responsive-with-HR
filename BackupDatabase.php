<?php

$PageSecurity = 15; //hard coded in case database is old and PageSecurity stuff cannot be retrieved

include('includes/session.php');
$Title = _('Backup nERP Database');
include('includes/header.php');

if (isset($_GET['BackupFile'])){
	$BackupFiles = scandir('companies/' . $_SESSION['DatabaseName'], 0);
	$DeletedFiles = false;
	foreach ($BackupFiles as $BackupFile){

		if (mb_substr($BackupFile,0,6)=='Backup'){

			$DeleteResult = unlink('companies/' . $_SESSION['DatabaseName'] . '/' . $BackupFile);

			if ($DeleteResult==true){
				echo prnMsg(_('Deleted') . ' companies/' . $_SESSION['DatabaseName'] . '/' . $BackupFile,'info');
				$DeletedFiles = true;
			} else {
				echo prnMsg(_('Unable to delete'). ' companies/' . $_SESSION['DatabaseName'] . '/' . $BackupFile,'warn');
			}
		}
	}
	if ($DeletedFiles){
		echo prnMsg(_('All backup files on the server have been deleted'),'success');
	} else {
		echo prnMsg(_('No backup files on the server were deleted'),'info');
	}
} else {

	$BackupFile =   $RootPath . '/companies/' . $_SESSION['DatabaseName']  .'/' . _('Backup') . '_' . Date('Y-m-d-H-i-s') . '.sql.gz';
	$Command = 'mysqldump --opt -h' . $host . ' -u' . $DBUser . ' -p' . $DBPassword  . '  ' . $_SESSION['DatabaseName'] . '| gzip > ' .
	$_SERVER['DOCUMENT_ROOT'] . $BackupFile;

	exec($Command);
	echo prnMsg(_('The backup file has now been created. You must download this to your computer because in case the web-server has a disk failure the backup would then be on your computer. Use the link below') . '<br /><br /><a href="' . $BackupFile  . '" class="btn btn-warning">' . _('Download the backup file to your computer') . '</a>','success');<br />
';
	echo prnMsg(_('Once you have downloaded the backup file to your computer you should use the link below to delete it - backup files can consume a lot of disk space on your web-server and will accumulate if not deleted - they also contain sensitive information which would otherwise be available for others to download!<br /><br /><a href="'. htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?BackupFile=' .$BackupFile  .'" class="btn btn-danger">' . _('Delete the backup file from the server') . '</a>'),'info');
	

}
/*
//this could be a weighty file attachment!!
include('includes/htmlMimeMail.php');
$mail = new htmlMimeMail();
$attachment = $mail->getFile( $BackupFile);
$mail->setText(_('nERP backup file attached'));
$mail->addAttachment($attachment, $BackupFile, 'application/gz');
$mail->setSubject(_('Database Backup'));
$mail->setFrom($_SESSION['CompanyRecord']['coyname'] . '<' . $_SESSION['CompanyRecord']['email'] . '>');
$result = $mail->send(array('"' . $_SESSION['UsersRealName'] . '" <' . $_SESSION['UserEmail'] . '>'));

prnMsg(_('A backup of the database has been taken and emailed to you'), 'info');
unlink($BackupFile); // would be a security issue to leave it there for all to download/see
*/
include('includes/footer.php');
?>