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

	namespace APLib\Response;

	/**
	* HTTP - A class to control HTTP response
	*/
	class HTTP
	{

    /**
     * Send a specific http status response
     *
     * @param  int   $status  status code
     *
     * @return void
     */
    public static function status($status)
    {
      switch($status)
      {
        case 403:
          header("HTTP/1.1 403 Forbidden");
          die();
          break;
        case 404:
          header("HTTP/1.1 404 Not Found");
          die();
          break;
        case 500:
          header("HTTP/1.0 500 Internal Server Error");
          die();
          break;
        case 503:
          header("HTTP/1.0 503 Service Unavailable");
          die();
          break;
      }
    }

		/**
		 * Resend specific params with the same request method
		 *
		 * @param   array  $params  an array of params to resend
		 *
		 * @return  void
		 */
		public static function resend($params)
		{
			if(\APLib\Request\HTTP::post())
			{
				if(\APLib\Request\HTTP::json())
				{
					$json_arr  =  '{';
					foreach($params as $param)
					{
						$json_arr  .=  "'$param[0]' : '$param[1]', ";
					}
					$json_arr  .= '}';
					die("<script>
	var xmlHTTP = new XMLHttpRequest();
	xmlHTTP.onreadystatechange = function(){
		if(xmlHTTP.readyState == 4 && xmlHTTP.status == 200){
			alert(xmlHTTP.responseText);
		}
	}
	xmlHTTP.open('POST',location.href);
	xmlHTTP.setRequestHeader(
		'Content-Type',
		'application/json'
	);
	xmlHTTP.send(JSON.stringify({$json_arr}));
</script>");
				}
				else
				{
					echo "<form method='post' id='resend'>\r\n";
					foreach($params as $param)
					{
						echo "	<input type='hidden' name='$param[0]' value='$param[1]' />\r\n";
					}
					die("</form>
<script>
	var form = document.getElementById('resend');
	form.submit();
</script>");
				}
			}
			else
			{
				$url  =  explode('?', \APLib\Request\HTTP::URL())[0].'?';
				foreach($params as $param)
				{
					$url  .=  \APLib\Security::ToHTML($param[0]).'='.\APLib\Security::ToHTML($param[1]).'&';
				}
				$url  =  substr($url, 0, -1);
				die("<script>
	alert('$url');
	location.href = '$url';
</script>");
			}
		}
  }
?>
