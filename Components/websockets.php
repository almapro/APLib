<?php
    namespace APLib;

    /**
     * WebSockets - A class to manage WebSockets connections
     */
    class WebSockets
    {
        /**
         * @var  SplObjectStorage  $connections  An SplObjectStorage to store active connections
         */
        private static $connections;

        /**
         * @var function $function a respond callback function
         */
        private static $respond = array();

        public static function init($function)
        {
            static::$respond['function'] = $function;
            static::$connections         = new \SplObjectStorage;
        }

        /**
         * Respond to a message
         *
         * @param  $conn    Connection to reply to
         * @param  $message A message to respond to
         *
         * @return void
         */
        public static function respond($conn, $message)
        {
            static::$respond['function']($conn, $message);
        }

        public static function add($conn)
        {
            static::$connections->attach($conn, array('verified' => false, 'key' => null, 'ip' => $conn->remoteAddress));
        }

        public static function remove($conn)
        {
            static::$connections->detach($conn);
        }

        public static function set($conn, $data)
        {
            static::$connections->offsetSet($conn, $data);
        }

        public static function get($conn)
        {
            return static::$connections->offsetGet($conn);
        }
    }

?>
