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

  namespace APLib\Themes;

  /**
   * Sleek - Theme inspiration by Rikon Rahman (https://twitter.com/rikonrahman)
   * URL: https://dribbble.com/shots/3024790-Sleek-Multipurpose-Creative-PSD-Template
   */
  class Sleek
  {

    /**
     * Include the theme files
     *
     * @return  void
     */
    public static function include()
    {
      \APLib\Response\Header\Link::add(APLibHTML.'Themes/css/sleek.css');
    }

    /**
     * Show the default theme page
     *
     * @return  void
     */
    public static function default()
    {
      ob_start(); ?>
      <div class="sleek-container">
        <div class="sleek-navbar sleek-top">
          <a class="sleek-right sleek-nav sleek-rounded" href="https://dribbble.com/shots/3024790-Sleek-Multipurpose-Creative-PSD-Template">Theme</a>
          <a class="sleek-nav active" href="<?php echo $_SERVER['REQUEST_URI']; ?>">Home</a>
          <a class="sleek-nav" href="https://twitter.com/rikonrahman">Twitter</a>
          <a class="sleek-nav" href="https://dribbble.com/rikonrahman">Dribbble</a>
          <a class="sleek-nav" href="https://cdn.dribbble.com/users/186189/screenshots/3024790/attachments/633632/sleek_-_creative_website_template_-_one_page_-_dark.png">Image</a>
        </div>
        <div class="sleek-jumbo">
          Sleek
          <div class="sleek-jumbo-desc">Theme inspiration by Rikon Rahman</div>
        </div>
      </div><?php
      \APLib\Response\Body\JavaScript::add('$("body").toggleClass("sleek");');
      \APLib\Response\Body::add(ob_get_clean());
    }
  }
?>
