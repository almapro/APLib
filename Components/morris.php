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

  namespace APLib;

  /**
   * Morris - A jQuery plugin implementation class
   */
  class Morris
  {

    /**
     * Create a new Donut chart
     *
     * @param   array   $data    an array containing data labels & values
     * @param   array   $colors  an array containing data colors [Default: NULL]
     * @param   string  $id      a unique ID for the new checkbox [Default: EMPTY]
     *
     * @return  string
     */
    public static function donut($data, $colors  =  null, $id  =  '')
    {
      if(!is_array($data)) return '';
      $id  =  ($id  !=  '') ? $id : \APLib\Extras::RandomString();
      \APLib\Response\Body\JavaScript::add("Morris.Donut({");
      \APLib\Response\Body\JavaScript::add("  element: '{$id}',");
      \APLib\Response\Body\JavaScript::add("  data: [");
      foreach($data as $row)
      {
        \APLib\Response\Body\JavaScript::add("    {label: '{$row['label']}', value: {$row['value']}},");
      }
      \APLib\Response\Body\JavaScript::add("  ],");
      \APLib\Response\Body\JavaScript::add("  colors: [");
      foreach($colors as $color)
      {
        \APLib\Response\Body\JavaScript::add("    '{$color}',");
      }
      \APLib\Response\Body\JavaScript::add("  ]");
      \APLib\Response\Body\JavaScript::add("});");
      return "<div id='{$id}'></div>";
    }
  }
?>
