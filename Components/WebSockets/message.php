<?php
    namespace APLib\WebSockets;

    /**
     * Message - A WebSockets message class
     */
    class Message implements \Ratchet\MessageComponentInterface
    {

        public function onOpen($conn)
        {
            \APLib\WebSockets::open($conn);
        }

        public function onMessage($from, $msg)
        {
            \APLib\WebSockets::message($from, $msg);
        }

        public function onClose($conn)
        {
            \APLib\WebSockets::close($conn);
        }

        public function onError($conn, $e)
        {
            \APLib\Logger::Error($e);
            \APLib\WebSockets::error($conn, $e);
            $conn->close();
        }
    }

?>
