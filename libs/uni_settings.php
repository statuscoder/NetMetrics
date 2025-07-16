<?php
//header('Content-Type: text/html; charset='.Settings::CHARSET);

class Settings
{
    const VERSION           = '1.0.0';
    const SERVER_NAME       = 'Metric Server';
    const WSOCKET_PORT      = 5557;
    const WSOCKET_COUNT     = 4;
    const DEBUG             = true;
    const CHARSET           = 'UTF-8';
		const LOGFOLDER         = '/log';
    const DATE              = '%d.%m.%Y';
    const TIME              = '%H:%i';
}
?>
