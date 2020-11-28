<?php
require(__DIR__.'/../init.php');

$vars = [
	'commands' => json_encode([
		// barcodes must be strings because.. javascript
		[BARCODE_MODE_ADD, 'Add Beer'],
		[BARCODE_MODE_DEFAULT, 'Normal Mode'],
		[BARCODE_ADD3_LASTBEER, 'Add 3 to last beer scanned'],
		[BARCODE_ADD5_LASTBEER, 'Add 5 to last beer scanned'],
		[BARCODE_ADD11_LASTBEER, 'Add 11 to last beer scanned'],
		["123456", 'Test Beer'],
	]),
];

displayPage('help.html', $vars);
