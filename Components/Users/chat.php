<?php
    namespace APLib\Users;

    /**
    * Chat - A class to manage chat
    */
    class Chat
    {

        public static function fetch($receiver, $sender, $offset = 0, $limit = 25)
        {
            $chats = array();
            $stmt  = \APLib\DB::prepare("SELECT id,sender,receiver,message,beenRead,senddate,receivedate,readdate FROM chat WHERE (receiver OR sender) = ? AND (sender OR receiver) = ? ORDER BY senddate DESC LIMIT ?,?");
            $stmt->bind_param('ssii', $receiver, $sender, $offset, $limit);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $message, $beenRead, $senddate, $receivedate, $readdate);
            while($stmt->fetch()) array_push($chats, array('id' => $id, 'sender' => $sender, 'receiver' => $receiver, 'message' => $message, 'read' => $beenRead, 'send date' => $senddate, 'receive date' => $receivedate, 'read date' => $readdate));
            return $chats;
        }

        public static function send($sender, $receiver, $message)
        {
            $now  = date("m/j/Y g:i:s A");
            $stmt = \APLib\DB::prepare("INSERT INTO chat(sender, receiver, message, senddate) VALUES(?, ?, ?, ?)");
            $stmt->bind_param('ssss', $sender, $receiver, $message, $now);
            $stmt->execute();
            return ($stmt->affected_rows > 0);
        }

        public static function unread($receiver)
        {
            $now   = date("m/j/Y g:i:s A");
            $id    = null;
            $chats = array();
            $stmt  = \APLib\DB::prepare("SELECT id,sender,message,beenRead,senddate,receivedate,readdate FROM chat WHERE beenRead = 0 AND receiver = ? ORDER BY senddate DESC");
            $stmt->bind_param('s', $receiver);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $message, $beenRead, $senddate, $receivedate, $readdate);
            $msg = \APLib\DB::prepare("UPDATE chat SET receivedate = ? WHERE id = ? AND receivedate = NULL");
            $msg->bind_param('si', $now, $id);
            while($stmt->fetch())
            {
                $msg->execute();
                array_push($chats, array('id' => $id, 'sender' => $sender, 'receiver' => $receiver, 'message' => $message, 'read' => $beenRead, 'send date' => $senddate, 'receive date' => $now, 'read date' => $readdate));
            }
            return $chats;
        }

        public static function read($id)
        {
            $now  = date("m/j/Y g:i:s A");
            $stmt = \APLib\DB::prepare("UPDATE chat SET beenRead = 1, readdate = ? WHERE id = ? AND beenRead = 0 AND readdate = NULL");
            $stmt->bind_param('is', $id, $now);
            $stmt->execute();
            return ($stmt->affected_rows > 0);
        }

        public static function table()
        {
            \APLib\DB::query(
                "CREATE TABLE IF NOT EXISTS chat(
                    id int NOT NULL AUTO_INCREMENT,
                    sender VARCHAR(60) NOT NULL,
                    receiver VARCHAR(60) NOT NULL,
                    message TEXT NOT NULL,
                    beenRead boolean NOT NULL DEFAULT FALSE,
                    senddate date NOT NULL,
                    receivedate date NOT NULL,
                    readdate date NOT NULL,
                    INDEX (sender),
                    INDEX (receiver),
                    PRIMARY KEY(id),
                    CONSTRAINT FK_chat_sender FOREIGN KEY (sender) REFERENCES accounts(username) ON UPDATE CASCADE ON DELETE RESTRICT,
                    CONSTRAINT FK_chat_receiver FOREIGN KEY (receiver) REFERENCES accounts(username) ON UPDATE CASCADE ON DELETE RESTRICT
                ) ENGINE=INNODB"
            );
        }
    }
?>
