<?php
    namespace APLib\WebSockets;
    use \Ratchet\MessageComponentInterface;
    use \Ratchet\ConnectionInterface;

    /**
     * Message - A WebSockets message class
     */
    class Message implements MessageComponentInterface
    {

        public function onOpen($conn)
        {
            \APLib\WebSockets::add($conn);
        }

        public function onMessage($from, $msg)
        {
            \APLib\WebSockets::respond($from, $msg);
        }

        public function onClose($conn)
        {
            \APLib\WebSockets::remove($conn);
        }

        public function onError($conn, $e)
        {
            \APLib\Logger::Error($e);
            $conn->close();
        }
    }

?>
