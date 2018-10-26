<?php
    namespace APLib\WebSockets;

    /**
     * Channels - A class to control channels in WebSockets
     */
    class Channels
    {

        /**
         * @var array $channels an array to contain channels
         */
        private static $channels = array();

        /**
         * Create a new channel
         *
         * @param  string $name channel's name
         *
         * @return void
         */
        public static function create($name)
        {
            static::$channels[$name] = array();
        }

        /**
         * Destroy a channel
         *
         * @param  string $name channel's name
         *
         * @return void
         */
        public static function destroy($name)
        {
            unset(static::$channels[$name]);
        }

        /**
         * Subscribe to a channel
         *
         * @param  string $name        channel's name
         * @param  string $id          subscriber's ID
         * @param  Object $connection  subscriber's connection. [Default: null]
         *
         * @return void
         */
        public static function subscribe($name, $id, $connection = null)
        {
            static::$channels[$name][$id] = ($connection == null) ? \APLib\WebSockets\Connections::find($id) : $connection;
        }

        /**
         * Unsubscribe from a channel
         *
         * @param  string $name channel's name
         * @param  string $id   subscriber's ID
         *
         * @return void
         */
        public static function unsubscribe($name, $id)
        {
            unset(static::$channels[$name][$id]);
        }

        /**
         * Check if an ID is subscribed to a channel
         *
         * @param  string $name  channel's name
         * @param  string  $id   subscriber's ID
         *
         * @return void
         */
        public static function subscribed($name, $id)
        {
            return (isset(static::$channels[$name][$id]));
        }

        /**
         * Return a subscriber's connection Object from a channel
         *
         * @param  string $name channel's name
         * @param  string $id   subscriber's ID
         *
         * @return Object
         */
        public static function subscriber($name, $id)
        {
            return static::$channels[$name][$id];
        }

        /**
         * Return all subscribers of a channel
         *
         * @param  string $name        channel's name
         *
         * @return void
         */
        public static function subscribers($name)
        {
            return static::$channels[$name];
        }

        /**
         * Broadcast an event to a channel
         *
         * @param  string $name     channel's name
         * @param  string $event    event to broadcast
         * @param  mixed  $include  an array of subscribers/connections to broadcast to
         * @param  mixed  $exclude  an array of subscribers/connections to exclude from broadcast
         *
         * @return void
         */
        public static function broadcast($name, $event, $include = null, $exclude = null)
        {
            if(!isset(static::$channels[$name])) return;
            foreach (static::$channels[$name] as $subscriber => $connection)
            {
                $ok = true;
                if($include != null)
                {
                    $ok = false;
                    for ($i=0; $i < sizeof($include); $i++)
                    {
                        if($include[$i] == $subscriber) $ok = true;
                        if($include[$i] == $connection) $ok = true;
                    }
                }
                if($exclude != null)
                {
                    for ($i=0; $i < sizeof($exclude); $i++)
                    {
                        if($exclude[$i] == $subscriber) $ok = false;
                        if($exclude[$i] == $connection) $ok = false;
                    }
                }
                if($ok) $connection->send($event);
            }
        }
    }
?>
