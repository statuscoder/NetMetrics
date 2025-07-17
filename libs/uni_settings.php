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

    const AUTH_ID           = 1;
    const PING_ID           = 2;
    const GEO_ID            = 3;
    const DOWNLOAD_ID       = 4;
    const UPLOAD_ID         = 5;

    const TOTAL_SIZE        = 70 * 1024 * 1024;
    const CHUNK_SIZE        = 500 * 1024;
}
?>
