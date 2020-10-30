<?php
require_once(__DIR__.'/../init.php');

if ($_POST['action'] != 'scanned')
{
	displayError('Invalid action', '/');
}

define('BARCODE_MODE_ADD', '111111');
define('BARCODE_MODE_DEFAULT', '222');

switch ($_POST['barcode'])
{
	case BARCODE_MODE_ADD:
		/**
		*	Barcode was scanned that puts us into beer add mode - this means we'll increment counts
		*	until we hit the timeout or another mode is scanned
		*/
		$_SESSION['barcode_mode'] = 'add';
		$_SESSION['barcode_mode_changed'] = time();
		displaySuccess('Entered beer add mode', '/');
	break;

	case BARCODE_MODE_DEFAULT:
		/**
		*	Go back to standard decrement on scan mode
		*/
		unset($_SESSION['barcode_mode']);
		unset($_SESSION['barcode_mode_changed']);
		displaySuccess('Went back to default mode', '/');
	break;
}

/**
*	timeout after 30 minutes of a mode change.  This doesn't handle DST, but who cares
*/
if (isset($_SESSION['barcode_mode_changed']) && time() - $_SESSION['barcode_mode_changed'] > 30*60)
{
	unset($_SESSION['barcode_mode_changed']);
	unset($_SESSION['barcode_mode']);
}

$beer = Beer::getByUPC($_POST['barcode']);
if (!$beer->isInitialized())
{
	displayError('Unknown beer, please add', '/beer_edit.php?BEERID=new&upc='.intVal($_POST['barcode']));
}
else if (isset($_SESSION['barcode_mode']) && $_SESSION['barcode_mode'] == 'add')
{
	$beer->set(['count_available' => $beer['count_available']+1]);
	displaySuccess('Added one to '.$beer['name'], '/');
}
else
{
	// default action - assume a beer has been drunk
	$beer->set(['count_available' => $beer['count_available']-1]);
	displaySuccess('Inventory updated', '/');
}
