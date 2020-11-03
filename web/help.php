<?php
require(__DIR__.'/../init.php');

$vars = [
	'commands' => json_encode([
		// barcodes must be strings because.. javascript
		[BARCODE_MODE_ADD, 'Add Beer'],
		[BARCODE_MODE_DEFAULT, 'Normal Mode'],
		[BARCODE_ADD6_LASTBEER, 'Add 6 to last beer scanned'],
		[BARCODE_ADD12_LASTBEER, 'Add 12 to last beer scanned'],
		["123456", 'Test Beer'],
	]),
];

displayPage('help.html', $vars);
