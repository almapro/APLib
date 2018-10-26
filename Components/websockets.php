<?php
    namespace APLib;

    /**
     * WebSockets - A class to manage WebSockets connections
     */
    class WebSockets
    {

        /**
         * @var array $functions an array of functions to respond to all events
         */
        private static $functions = array();

        /**
         * Initiate WebSockets
         *
         * @param  function $message a function to handle new messages
         * @param  mixed    $events  an array containing functions to handle onOpen, onClose & onError events [Default: null]
         *
         * @return void
         */
        public static function init($message, $events = null)
        {
            static::$functions['message'] = $message;
            if($events != null)
            {
                if(isset($events['open']))  static::$functions['open']  = $events['open'];
                if(isset($events['close'])) static::$functions['close'] = $events['close'];
                if(isset($events['error'])) static::$functions['error'] = $events['error'];
            }
            \APLib\WebSockets\Connections::init();
        }

        /**
         * Handle messages
         *
         * @param  Object $conn    Connection Object
         * @param  string $message A message to handle
         *
         * @return void
         */
        public static function message($conn, $message)
        {
            static::$functions['message']($conn, $message);
        }

        /**
         * Handle an error
         *
         * @param  Object    $conn Connection Object
         * @param  Exception $e    Error to handle
         *
         * @return void
         */
        public static function error($conn, $e)
        {
            if(isset(static::$functions['error'])) static::$functions['error']($conn, $e);
        }

        /**
         * Handle a new connection
         *
         * @param  Object $conn Connection Object
         *
         * @return void
         */
        public static function open($conn)
        {
            if(isset(static::$functions['open'])) static::$functions['open']($conn);
            \APLib\WebSockets\Connections::add($conn, \APLib\Extras::RandomString());
        }

        /**
         * Handle onClose event
         *
         * @param  Object $conn Connection Object
         *
         * @return void
         */
        public static function close($conn)
        {
            if(isset(static::$functions['close'])) static::$functions['close']($conn);
            \APLib\WebSockets\Connections::remove($conn);
        }
    }

?>
