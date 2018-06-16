# APLib
A PHP library to create your website smooth, easy &amp; secure.

## Setup
1. Download APLib:  
   * `cd [DOCUMENT ROOT]`  
   * `git clone https://github.com/almapro/APLib.git`  
2. Download [APLib externals](https://github.com/almapro/APLib-ext/):  
   * `git clone https://github.com/almapro/APLib-ext.git`  
3. Put everything in place:  
   * Move all sub folders of APLib-ext folder to `DOCUMENT ROOT`

## Usage
Using **APLib** can be complex, but let's start step-by-step:  
   1. Include _APlib_ in your project:  
      `require_once('PATH/TO/APLib/core.php');`  
   2. Optionally configure settings using *config*:  
      `\APLib\Config::set('SETTING NAME', 'SETTING VALUE');`  

      For example:  
      `\APLib\Config::set('title', "My page's title");`  
      This will set the page's title (`<title>My page's title</title>`).
   3. Initiate the library:  
      `\APLib\Core::init();`  
   4. Add your body:  
      `\APLib\Response\Body::add("<h3>Hello code</h3>");`  
   5. Run the library to deliver your page:  
      `\APLib\Core::run();`
