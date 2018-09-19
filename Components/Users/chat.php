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
            $stmt->bind_result($id, $sender, $receiver, $message, $beenRead, $senddate, $receivedate, $readdate);
            while($stmt->fetch()) array_push($chats, array($id => array('sender' => $sender, 'receiver' => $receiver, 'message' => $message, 'read' => $beenRead, 'send date' => $senddate, 'receive date' => $receivedate, 'read date' => $readdate)));
            return $chats;
        }

        public static function send($sender, $receiver, $message)
        {
            $now  = strtotime(date("m/j/Y g:i:s A"));
            $stmt = \APLib\DB::prepare("INSERT INTO chat(sender, receiver, message, senddate) VALUES(?, ?, ?, ?)");
            $stmt->bind_param('ssss', $sender, $receiver, $message, $now);
            $stmt->execute();
            if($stmt->affected_rows > 0)
            {
                $stmt = \APLib\DB::prepare("SELECT id FROM chat WHERE sender = ? AND receiver = ? AND message = ? AND senddate = ? LIMIT 1");
                $stmt->bind_param('ssss', $sender, $receiver, $message, $now);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($id);
                $stmt->fetch();
                return $id;
            }
            return false;
        }

        public static function unread($receiver)
        {
            $now   = strtotime(date("m/j/Y g:i:s A"));
            $id    = null;
            $chats = array();
            $stmt  = \APLib\DB::prepare("SELECT id,sender,message,beenRead,senddate,receivedate,readdate FROM chat WHERE beenRead = 0 AND receiver = ? ORDER BY senddate DESC");
            $stmt->bind_param('s', $receiver);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $sender, $message, $beenRead, $senddate, $receivedate, $readdate);
            $msg = \APLib\DB::prepare("UPDATE chat SET receivedate = ? WHERE id = ? AND receivedate = NULL");
            $msg->bind_param('si', $now, $id);
            while($stmt->fetch())
            {
                $msg->execute();
                array_push($chats, array($id => array('sender' => $sender, 'receiver' => $receiver, 'message' => $message, 'read' => $beenRead, 'send date' => $senddate, 'receive date' => $now, 'read date' => $readdate)));
            }
            return $chats;
        }

        public static function read($id)
        {
            $now  = strtotime(date("m/j/Y g:i:s A"));
            $stmt = \APLib\DB::prepare("UPDATE chat SET beenRead = 1, readdate = ? WHERE id = ? AND beenRead = 0 AND readdate = NULL");
            $stmt->bind_param('is', $id, $now);
            $stmt->execute();
            return ($stmt->affected_rows > 0);
        }

        public static function lastCID($username)
        {
            $stmt  = \APLib\DB::prepare("SELECT sender,receiver FROM chat WHERE (receiver = ? AND beenRead = 1) OR sender = ? ORDER BY senddate DESC LIMIT 1");
            $stmt->bind_param('ss', $username, $username);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($sender, $receiver);
            $stmt->fetch();
            return ($sender != $username) ? $sender : $receiver;
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
                    senddate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    receivedate timestamp NOT NULL,
                    readdate timestamp NOT NULL,
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
