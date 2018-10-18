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
            echo "Open\r\n";
            \APLib\WebSockets::add($conn);
        }

        public function onMessage($from, $msg)
        {
            echo "Message: {$msg}\r\n";
            \APLib\WebSockets::respond($from, $msg);
        }

        public function onClose($conn)
        {
            echo "Close\r\n";
            \APLib\WebSockets::remove($conn);
        }

        public function onError($conn, $e)
        {
            echo "Error\r\n";
            \APLib\Logger::Error($e);
            $conn->close();
        }
    }

?>
