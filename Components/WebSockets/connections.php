<?php
    namespace APLib\WebSockets;

    /**
     * Connections - A class to manage WebSockets' connections
     */
    class Connections
    {

        /**
         * @var  SplObjectStorage  $connections  An SplObjectStorage to store active connections
         */
        private static $connections;

        /**
         * Initiate Connections
         *
         * @return void
         */
        public static function init()
        {
            static::$connections = new \SplObjectStorage;
        }

        /**
         * Add a new connection
         *
         * @param  Object $connection connection Object
         * @param  string $id         connection ID
         *
         * @return void
         */
        public static function add($connection, $id)
        {
            static::$connections->attach($connection, array('verified' => false, 'key' => null, 'ip' => $connection->remoteAddress, 'id' => $id));
        }

        /**
         * Remove a connection
         *
         * @param  Object $connection connection Object
         *
         * @return void
         */
        public static function remove($connection)
        {
            if(isset(static::$functions['close'])) static::$functions['close']($connection);
            static::$connections->detach($connection);
        }

        /**
         * Find a connection Object by ID
         *
         * @param  string $id ID to look for
         *
         * @return Object
         */
        public static function find($id)
        {
            foreach (static::$connections as $ignored)
            {
                $data = static::$connections->getInfo();
                if($data['id'] == $id) return static::$connections->current();
            }
            return null;
        }

        /**
         * Return a connection Object's ID
         *
         * @param  Object $connection Object to look for
         *
         * @return string
         */
        public static function id($connection)
        {
            foreach (static::$connections as $ignored)
            {
                if(static::$connections->current() == $connection) return static::get($connection, 'id');
            }
            return null;
        }

        /**
         * Get a connection Object's associated data
         *
         * @param  Object $connection connection Object
         *
         * @return mixed
         */
        private static function getData($connection)
        {
            return static::$connections->offsetGet($connection);
        }

        /**
         * Set a connection Object's associated data
         *
         * @param  Object $connection connection Object
         * @param  mixed  $data       data to associate
         *
         * @return void
         */
        private static function setData($connection, $data)
        {
            static::$connections->offsetSet($connection, $data);
        }

        /**
         * Get a connection Object's associated data key
         *
         * @param  Object $connection connection Object
         * @param  string $key        key to get
         *
         * @return mixed
         */
        public static function get($connection, $key)
        {
            $data = static::getData($connection);
            return $data[$key];
        }

        /**
         * Set a connection Object's associated data key
         *
         * @param  Object $connection connection Object
         * @param  string $key        key to set the value for
         * @param  mixed  $value      value to set
         *
         * @return void
         */
        public static function set($connection, $key, $value)
        {
            if($key == 'ip' || $key == 'id') return;
            $data       = static::getData($connection);
            $data[$key] = $value;
            static::setData($connection, $data);
        }

        /**
         * Broadcast an event to all active connections
         *
         * @param  string $event    event to broadcast
         * @param  mixed  $include  an array of IDs/connections to broadcast to
         * @param  mixed  $exclude  an array of IDs/connections to exclude from broadcast
         *
         * @return void
         */
        public static function broadcast($event, $include = null, $exclude = null)
        {
            foreach (static::$connections as $ignored)
            {
                $ok = true;
                if($include != null)
                {
                    $ok = false;
                    for ($i=0; $i < sizeof($include); $i++)
                    {
                        if($include[$i] == static::$connections->current()) $ok = true;
                        if($include[$i] == static::get(static::$connections->current(), 'id')) $ok = true;
                    }
                }
                if($exclude != null)
                {
                    for ($i=0; $i < sizeof($exclude); $i++)
                    {
                        if($exclude[$i] == static::$connections->current()) $ok = false;
                        if($exclude[$i] == static::get(static::$connections->current(), 'id')) $ok = false;
                    }
                }
                if($ok) static::$connections->current()->send($event);
            }
        }
    }
?>
