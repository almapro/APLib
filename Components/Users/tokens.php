<?php
namespace APLib\Users;

/**
 * Tokens - Tokens class
 */
class Tokens
{

  public static function valid($token)
  {
    return false;
  }

  public static function generate($username)
  {
    $token = \APLib\Extras::RandomString();
    return $token;
  }

  public static function table()
  {
    \APLib\DB::query(
      "CREATE TABLE IF NOT EXISTS users_tokens(
        id INT NOT NULL AUTO_INCREMENT,
        username VARCHAR(60) NOT NULL,
        token VARCHAR(60) NOT NULL,
        INDEX (username),
        PRIMARY KEY (token),
        CONSTRAINT FK_users_token FOREIGN KEY (username) REFERENCES accounts(username) ON UPDATE CASCADE ON DELETE CASCADE
      ) ENGINE=INNODB"
    );
    \APLib\Users\Tokens\Privilages::table();
    \APLib\Privilages::table();
  }
}

?>
