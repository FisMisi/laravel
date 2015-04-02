<?php

return array(
// set your paypal credential
    'client_id' => 'AUSZ8C7K8ySmhIbJnouTsu5-oujoLH-3E5tPtzX_03MFycQWVR7_UlcNcFfC0j7cdTGDHVBh9IP64EXd',
    'secret' => 'ECv7Zu8CDl2ThmElWvVvlTXPvQGWTfPNkTB26JUihszUvExK0RWnqlo8rKpM_IR-vKTp75k60LqophVX',
    /**
     * SDK configuration 
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => 'sandbox',
        /**
         * Specify the max request time in seconds
         */
        'http.ConnectionTimeOut' => 30,
        /**
         * Whether want to log to a file
         */
        'log.LogEnabled' => true,
        /**
         * Specify the file that want to write on
         */
        'log.FileName' => storage_path() . '/logs/paypal.log',
        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ),
);
