<?php
    namespace APLib\WebSockets;
    use \Ratchet\Server\IoServer;
    use \Ratchet\Http\HttpServer;
    use \Ratchet\WebSocket\WsServer;

    /**
     * Server - A WebSockets server
     */
    class Server
    {
        private static $server = null;

        public static function init($port)
        {
            if(static::$server != null) return;
            static::$server = IoServer::factory(
                new HttpServer(
                    new WsServer(
                        new \APLib\WebSockets\Message()
                    )
                ),
                $port
            );
        }

        public static function run()
        {
            if(static::$server == null) return;
            static::$server->run();
        }
    }

?>
