<?php
	/**
	 * APLib - A PHP library to create your website smooth, easy & secure
	 *
	 * @package   APLib
	 * @version   0.1
	 * @author    ALMA PRO LEADER
	 * @license   MIT License
	 * @copyright 2017-2018
	 * @link      https://github.com/almapro/APLib/
	 */

  namespace APLib\Themes\Forms;

  /**
   * Client Login - Form inspiration by Rikon Rahman (https://twitter.com/rikonrahman)
   * URL: https://dribbble.com/shots/1351676-Client-Login-Form-Design
   */
  class ClientLogin
  {

    /**
     * Include the form files
     *
     * @return  void
     */
    public static function include()
    {
      \APLib\Response\Header\Link::add(APLibHTML.'Themes/css/clientlogin.css');
    }

    /**
     * Show the default form page
     *
     * @return  void
     */
    public static function default()
    {
      ob_start(); ?>
      <form method="post">
        <div class="clientlogin">
          <div class="clientlogin-header">
            <div class="clientlogin-title">
              Client Login
            </div>
            <div class="clientlogin-close">
              X
            </div>
          </div>
          <div class="clientlogin-body">
            <div class="clientlogin-indicator">
              <div class="clientlogin-status">
                You are almost done! One more step.
              </div>
              <div class="clientlogin-bar first active">O</div>
              <div class="clientlogin-bar active">O</div>
              <div class="clientlogin-bar">O</div>
            </div>
            <input class="clientlogin-input username" type="text" name="username" placeholder="Username" />
            <input class="clientlogin-input password" type="password" name="password" placeholder="Password" />
            <input class="clientlogin-input submit" type="submit" name="submit" value="LOGIN" />
          </div>
          <div class="clientlogin-footer">
            <input class="clientlogin-input extra left" type="checkbox" name="remember" /> Remember Me
            <a class="clientlogin-input extra right" href="#forgot">Forgot Password?</a>
          </div>
        </div>
      </form><?php
      \APLib\Response\Body::add(ob_get_clean());
    }
  }
?>
