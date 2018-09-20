<?php
    namespace APLib\Chat;

    /**
     * Contacts - A class to manage contacts in chat
     */
    class Contacts
    {

        public static function init($username)
        {
            $stmt = \APLib\DB::prepare("INSERT INTO chat_contacts(username) SELECT * FROM (SELECT ? AS username) AS tmp WHERE NOT EXISTS (SELECT * FROM chat_contacts WHERE username = ?) LIMIT 1");
            $stmt->bind_param('ss', $username, $username);
            $stmt->execute();
            return ($stmt->affected_rows > 0);
        }

        public static function all($username)
        {
            $contacts = array();
            $stmt     = \APLib\DB::prepare("SELECT contacts FROM chat_contacts WHERE username = ?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($json_contacts);
            if($stmt->fetch())
            {
                $temp_contact = json_decode($json_contacts);
                foreach($temp_contact as $user => $info)
                {
                    $userinfo = \APLib\Users::account($user);
                    if($userinfo)
                    {
                        $info['last seen'] = static::lastSeen($user);
                        $info['online']    = ((time()-$info['last seen']) <= 600)) ? true : false;
                        $info['avatar']    = $userinfo['avatar'];
                        $info['name']      = $userinfo['first name'].' '.$userinfo['last name'];
                        array_push($contacts, array($user => $info));
                    }
                }
            }
            return $contacts;
        }

        public static function add($username, $user)
        {
            $stmt = \APLib\DB::prepare("SELECT contacts FROM chat_contacts WHERE username = ?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($json_contacts);
            if($stmt->fetch())
            {
                $contacts        = json_decode($json_contacts);
                $contacts[$user] = array(
                    'typing'    => false,
                    'uploading' => false
                );
                $json_contacts   = json_encode($contacts);
                $stmt            = \APLib\DB::prepare("UPDATE chat_contacts SET contacts = ? WHERE username = ?");
                $stmt->bind_param('ss', $json_contacts, $username);
                $stmt->execute();
                return ($stmt->affected_rows > 0);
            }
            return false;
        }

        public static function delete($username, $user)
        {
            $stmt = \APLib\DB::prepare("SELECT contacts FROM chat_contacts WHERE username = ?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($json_contacts);
            if($stmt->fetch())
            {
                $contacts      = json_decode($json_contacts);
                if(isset($contacts[$user])) unset($contacts[$user]);
                $json_contacts = json_encode($contacts);
                $stmt          = \APLib\DB::prepare("UPDATE chat_contacts SET contacts = ? WHERE username = ?");
                $stmt->bind_param('ss', $json_contacts, $username);
                $stmt->execute();
                return ($stmt->affected_rows > 0);
            }
            return false;
        }

        public static function lastSeen($username)
        {
            $stmt = \APLib\DB::prepare("SELECT lastSeen FROM chat_contacts WHERE username = ?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($lastSeen);
            $stmt->fetch();
            return $lastSeen;
        }

        public static function setLastSeen($username)
        {
            $stmt = \APLib\DB::prepare("UPDATE chat_contacts SET lastSeen = CURRENT_TIMESTAMP WHERE username = ?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            return ($stmt->affected_rows > 0);
        }

        public static function setTyping($username, $toUser, $started = true)
        {
            $contacts = array();
            $stmt     = \APLib\DB::prepare("SELECT contacts FROM chat_contacts WHERE username = ?");
            $stmt->bind_param('s', $toUser);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($json_contacts);
            if($stmt->fetch())
            {
                $temp_contact = json_decode($json_contacts);
                foreach($temp_contact as $user => $info)
                {
                    if($user == $username)
                    {
                        $info['typing'] = $started;
                    }
                    array_push($contacts, array($user => $info));
                }
                $json_contacts = json_encode($contacts);
                $stmt          = \APLib\DB::prepare("UPDATE chat_contacts SET contacts = ? WHERE username = ?");
                $stmt->bind_param('ss', $json_contacts, $toUser);
                $stmt->execute();
                return ($stmt->affected_rows > 0);
            }
            return false;
        }

        public static function setUploading($username, $toUser, $started = true)
        {
            $contacts = array();
            $stmt     = \APLib\DB::prepare("SELECT contacts FROM chat_contacts WHERE username = ?");
            $stmt->bind_param('s', $toUser);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($json_contacts);
            if($stmt->fetch())
            {
                $temp_contact = json_decode($json_contacts);
                foreach($temp_contact as $user => $info)
                {
                    if($user == $username)
                    {
                        $info['uploading'] = $started;
                    }
                    array_push($contacts, array($user => $info));
                }
                $json_contacts = json_encode($contacts);
                $stmt          = \APLib\DB::prepare("UPDATE chat_contacts SET contacts = ? WHERE username = ?");
                $stmt->bind_param('ss', $json_contacts, $toUser);
                $stmt->execute();
                return ($stmt->affected_rows > 0);
            }
            return false;
        }

        public static function table()
        {
            \APLib\DB::query(
                "CREATE TABLE IF NOT EXISTS chat_contacts(
                    id int NOT NULL AUTO_INCREMENT,
                    username VARCHAR(60) NOT NULL,
                    lastSeen TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    contacts TEXT NOT NULL DEFAULT '{}',
                    INDEX (username),
                    INDEX (lastSeen),
                    INDEX (contacts),
                    PRIMARY KEY(id, username),
                    CONSTRAINT FK_chat_contact_username FOREIGN KEY (username) REFERENCES accounts(username) ON UPDATE CASCADE ON DELETE UPDATE
                ) ENGINE=INNODB"
            );
        }
    }
?>
