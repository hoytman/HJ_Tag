<?php

/**
 *
 * This content is released under the MIT License (MIT)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package         HJ_Tag
 * @author          Hoyt Jolly
 * @license         https://opensource.org/licenses/MIT	
 * @link	
 * @filesource
 */

/**
 * HJ_Tag Class
 *
 * This class provides an object oriented approach to developing and rendering
 * nested HTML tags.  It also includes a caching system.
 *
 * @author		Hoyt Jolly
 * @link		
 */

class HJ_Tag {

    /**
    * the name of the tag (div, h1, a, etc.)
    *
    * @var	String
    */
    private $name = '';
    
    /**
    * boolean: is this a self closing tag (like br or submit)?
    *
    * @var	Boolean
    */
    private $self_closing = false;
    
    /**
    * an array of elements inside of this tag.  Strings or HJ_Tag objects
    *
    * @var	Array
    */
    private $elements = array();
    
    /**
    * an array of classes on this tag (strings).
    *
    * @var	Array
    */
    private $classes = array();
    
    /**
    * an associative array of attributes on this tag (string=>string).
    * name=>value 
    *
    * @var	Array
    */
    private $attributes = array();
    
    
    /**
    * an indexed of associative array of replacements that need to be integrated
    * before this tag returns data. replace_this=>with_this
    *
    *
    * @var	Array
    */
    private $replacements = null;
    
    /**
    * if filled, this block will be wrapped in an HTML comment
    *
    * @var	String / boolean
    */
    private $notation = false;
    
    /**
    * After the tag (and child tags) are rendered, the HTML string is stored
    * here.  The indents are encoded and the HTML comments are omitted
    *
    * @var	String
    */
    private $renderedHtmlTag = ""; 
    
    /**
    * If a replacement is performed, the new string is placed here
    *
    * @var	String
    */
    private $renderedHtmlTagWithReplacements = ""; //rendered string.  If a 
    
    /**
    * the character(s) for each indentation level (used before each line.)
    *
    * @var	String
    */
    private static $indent_str = '    ';
    
    /**
    * the current indentation level.
    *
    * @var	int
    */
    private static $initial_indentation = 0;

    /**
    * When true, each render section will be wrapped in a generic HTML comment
    *
    * @var	boolean
    */
    private static $blockNotation = false;
    
    /**
    * the file path to the cache file.
    *
    * @var	String
    */
    private static $cachePath = "";
    
    /**
    * an associative array of all of the cached tags
    *
    * @var	Array
    */
    private static $cacheArray;
    
    /**
    * When true, the cache file will be updated at the end of execution.  
    * 
    * @var	boolean
    */
    private static $cacheUpdate = false; 
    
    /**
    * The number of seconds that need to pass before the tags cache expires.
    *
    * @var	int
    */
    private $cacheRefreshTime = 0;
    
    /**
    * A string that identifies this tag within the cache
    *
    * @var	String
    */
    private $cacheID = false;
    
    /**
    * When true, the render() function will only return the cache value and end.
    *
    * @var	boolean
    */
    private $useCacheValue = false;
    
    /**
    * When true, the buffer is being captured, and calling add() or addHtml()
    * will add the text in the buffer to the tag as HTML. 
    *
    * @var	boolean
    */
    private $captureOn = false;
    
    /**
    * A link back to the parent tag. 
    *
    * @var	HJ_Tag
    */
    private $parent; 
    
    

    /**
    * Class Constructor
    *
    * Sets up the tag name and "is it self closing?"
    *  
    * @param	String	$tagName    The name of the tag
    * @param	String	$name       used to call add()
    * @param	String	$data       used to call add()
    * 
    * 
    * @return	none
    */
    public function __construct($tagName='', $name='', $data=''){
        
        if(substr($tagName, -1) == '/'){
            $this->name = substr($tagName, 0, -1);
            $this->self_closing = true;
        }else{
        
            $this->name = $tagName;
            
        }
        
        if($name){
            $this->add($name, $data);
        }

    }
    
    
    /**
    * __destruct()
    *
    * Attempts to save the cache file if there are changes.
    * 
    * @return	none
    */
    public function __destruct(){
        $this->saveHTMLCache();
    }
    
    /**
    * saveHTMLCache()
    *
    * Static. Saves the cache array to a file
    * 
    * @return	none
    */
    public static function saveHTMLCache(){
        
        if(self::$cacheUpdate){
            
            file_put_contents(self::$cachePath, serialize(self::$cacheArray));
            
        }
        
        self::$cacheUpdate = false;
        
    }
    
    /**
    * setParent($obj)
    *
    * Sets the parent of the object.  Called by the parent.
    * 
    * @return	none
    */
    protected function setParent($obj){
        $this->parent = $obj;
    }
    
    /**
    * setIndentLevel()
    *
    * Sets initial indent level.
    * 
    * @param	int     $level  the initial starting level
     * 
     * @return	none
    */
    public static function setIndentLevel($level){
        self::$initial_indentation = $level;
    }
    
    /**
    * setIndentString()
    *
    * Sets initial indent characters user per indent level.
    * 
    * @param    String  $str    the characters used for each indentation level
    * 
    * @return	none
    */
    public static function setIndentString($str){
        self::$indent_str = $str;
    }
    
    /**
    * activateBlockNotation()
    *
    * Activates notation tags for each rendered section.
    * 
    * @return	none
    */
    public static function activateBlockNotation(){
        self::$blockNotation = true;
    }
    
    /**
    * LoadHTMLCache()
    *
    * Static. Loads the cache file from the server.  
    * Call this function only if file caching is used.
    * 
    * @param    String  $str    the file path to the cache file on the server
    * 
    * @return	none
    */
    public static function LoadHTMLCache($str){
    
        if(substr($str,0,1) != DIRECTORY_SEPARATOR)
                
                $str = DIRECTORY_SEPARATOR.$str;
        
        $str = __DIR__.$str;
        
        self::$cachePath = $str;
       
        if(!file_exists($str)){
            
            self::$cacheArray= array();
    
            file_put_contents($str,serialize(array()));
        
        }else{
            
            $data_str = file_get_contents($str);

            $data_arr = unserialize($data_str);

            if($data_arr && is_array($data_arr)){
                
                self::$cacheArray = $data_arr;
         
            }else{

                self::$cacheArray= array();
    
                file_put_contents($str, serialize(array()));
              
               }
            
        }

    }
        
    /**
    * activateRender()
    *
    * This function is called internally if new data is added to the object.
    * It triggers the Object to re-render it's HTML string
    * as well as all of it's parent objects.
    * 
    * @return	none
    */
    private function activateRender(){
        
        $this->renderedHtmlTag = '';
        $this->beforeReplacementHTML = '';
        
        if($this->parent)
            $this->parent->activateRender();
    }
    
    
    /**
    * add()
    *
    * A multi-use function with the ability to accept new classes, attributes and
    * internal HTML objects/strings.  Normally, it's called with a name/value pair.
    * $name can be 'html' or any attribute name.  $data would then be saved as the 
    * value.  also, if a name/value pair is used, the reset option can be used too.
    * 
    * calling add() with 'class' causes a new class to be added.
    * calling add() with other attributes causes attribute replacement.
    *   
    * if $data is left blank, then the $name is treated as html, and placed within 
    * the tag.   
    * 
    * if called after captuer(), the data within the buffer will be processed as
    * Html and the buffer capture will be deactivated 
    * 
    * If the function is called and passed 
    * an associative array, each key/value pair is processed as $name/$data . 
    * 
    * @param    String|Array  $name   the attribute name or 'html' or just html text
    * @param    String        $data   the value to be stored
    * 
    * @return	none
    */
    public function add($name='', $data=''){

        if($this->captureOn){
            
            $this->addHtml();
            
        }else if(is_array($name)){
            
            foreach($name as $key=>$value){
                $this->add($key, $value);
            }
            
        }else{

            switch($name){
                case 'html':
                case 'Html':
                case 'HTML':
                    $this->addHtml($data);
                break;
                case 'class':
                    $this->addClass($data);
                break;
                default:
                    if(!$data){
                        $this->addHtml($name);
                    }else{
                        
                        $this->addAttribute($name, $data);
                    }
                break;

            }
        }
    }
    
    /**
    * addHtml()
    *
    * Adds Strings or HJ_Tag objects to the current tag as internal Html
    * 
    * if called after capture(), the data within the buffer will be processed as
    * Html and the buffer capture will be deactivated 
    * 
    * @param    String|HT_Tag  $element    Html to be added
    * 
    * @return	none
    */
    public function addHtml($element=''){
        
        if($this->captureOn){
            
            $this->captureOn = false;
            $element = ob_get_clean();
            
        }
        
        $this->activateRender();
        
        $this->elements[] = $element;
        
        if(is_a($element, 'HJ_Tag')){
            $element->setParent($this);
        }

    }
    
    
    /**
    * addClass()
    *
    * Adds a new class to the current tag
    * 
    * @param    String  $element    Class name
    * @return	none
    */
    public function addClass($class){
        $this->activateRender();
        $this->classes[] = $class;
    }
    
    /**
    * addAttribute()
    *
    * Adds a new attribute to the current tag
    * 
    * @param    String  $name   attribute name
    * @param    String  $value  attribute value
    * @return	none
    */
    public function addAttribute($name, $value){
        
        $this->activateRender();
        $this->attributes[$name] = $value;
        
    }
    
    /**
    * addNote()
    *
    * Adds a note, which will be rendered as an HTML comment, wrapping the tag
    * Similar to activateBlockNotation(), but for this tag object only. 
    * 
    * @param    String  $value   the note 
    * @return	none
    */
    public function addNote($value=''){
        
        $this->notation = $value;
        
    }
    
    /**
    * setReplacement()
    *
    * accepts an array of replacements for this object.  If the array is Associative
    * then the rendered HTML string will have a replacement performed for each
    * entry so that $find=>$replace_with.
    * 
    * if an indexed array is used, it must contain associative arrays, each of
    * which will be processed as an associative arrays.  each entry in an indexed 
    * array will generate a new copy of the HTML string, all of which are concatenated
    * together and returned as the final string value for the tag object   
    * 
    * @param    Array  $rep     An array of replacement values
    * @return	none
    */
    public function setReplacement($rep = null){
        $this->activateRender();
        $this->replacements = $rep;
    }
    
    /**
    * capture()
    *
    * Activates the buffer capture.  The capture is ended and utilized by 
    * either add() or addHtml(). 
    * 
    * @param    none
    * @return	none
    */
    public function capture(){
        if(!$this->captureOn){
            ob_start();
            $this->captureOn = true;
        }
    }
 
    /**
    * reset()
    *
    * Resets the tag's html or other attribute
    * 
    * @param    String  $type   sets the reset type 
    * @return	none
    */
    public function reset($type=''){
        
        $this->activateRender();
        
        switch($type){
            case 'html':
            case 'Html':
            case 'HTML':
            case '';
                $this->elements = array();
            break;
            case 'class':
            case 'classes':
                $this->classes = array();
            break;
            case 'attribute':
            case 'attributes':
                $this->attributes = array();
            break;
            case 'replacement':
            case 'replacements':
                $this->replacements = null;
            break;
            default:
                if(isset($this->attributes[$type])){
                    unset($this->attributes[$type]);
                }
            break;
        }
        
        
    }
    
    /**
    * setCacheId()
    *
    * sets the cache ID and refresh times.  If you only set a min time for the
    * cache refresh, the render() function will re-render the tag as soon as
    * it finds that the cache value is older.  If a max time is also given, 
    * then  render() might re-render, with odds of a re-render happening 
    * increasing as the age of the cache value approached max
    * 
    * @param    String  $cacheID    Identifier for this tag in the cache file
    * @param	int     $time       min number of seconds before this cache is invalid
    * @param	int     $time       max number of seconds before this cache is invalid
    * @return   none
    */
    public function setCacheId($cacheID, $time=0, $time2 = 0){
        $this->cacheID = $cacheID;
        
        if($time < 0){$time = 0;}

        
        if($time2 > $time){
            $this->cacheRefreshTime = rand($time, $time2);
        }else{
            $this->cacheRefreshTime = $time;
        }
    }
    
    
    /**
    * cache()
    *
    * This function tests to see if a cached value can be used for the next render
    * If the test passes, then the next render will only return a cached.  This 
    * function returns a boolean which can be used to skip all of the function 
    * calls that are used to build this tag.  To do this, wrap all of the functions
    * used to set up this tag (except for __construct() and the render() ) in
    * and if() block, and uses the returned boolean as the if()s conditional.  
    * if the tag needs to be re-rendered, this function will return true, and the 
    * setup functions will be performed.  If the cache is valid, false will return 
    * returned, and the setup function calls will be skipped.
    * If you wish to force a re-render, pass true to this function. 
    * 
    * @param    Boolean  $override   if true, this tag will be re-rendered 
    * @return	Boolean     if the tag needs to be re-rendered, true is returned
    */
    public function cache($override = false){

        $time = $this->cacheRefreshTime;
        
        if(!isset(self::$cacheArray[$this->cacheID])){
            $this->useCacheValue = false;
            return true;
        }else if($time && $time + (int)self::$cacheArray[$this->cacheID][1] < time()){
            $this->useCacheValue = false;
            return true;
        }else if($override){
            
            $this->useCacheValue = false;
            return true;
            
        }else{
           
            $this->useCacheValue = true;
            return false;
            
        }
            
    }
    

    /**
     * render()
     * 
     * returns a string version of this tag and all of the tags that it contains.
     * values are saved, so calling this function repeatedly will provide a time
     * savings.  
     * 
     * @param   none
     * @return  String  a rendering of this tags.
     * 
     */
    
    public function render(){

        //if there is a cache value avalible, use it.
        
        if($this->useCacheValue){
            
            return self::$cacheArray[$this->cacheID][0];
        }
        
        //get a rendering of this tags contents
        
        $this->renderedHtmlTag = $this->renderObjectData();
        
        //perform required replacements
        
        if($this->replacements){
            
           $outputStr = $this->applyReplacements();
           $this->renderedHtmlTagWithReplacements = $outputStr;
           
        }else{
           $outputStr = $this->renderedHtmlTag;
        }
        
        //apply the correct indentation

        $outputStr = $this->applyIndent($outputStr);
        
        //update the cache 
        
        if($this->cacheID !== false){
            self::$cacheArray[$this->cacheID] = array();
            self::$cacheArray[$this->cacheID][0] = $outputStr;
            self::$cacheArray[$this->cacheID][1] = time();
            self::$cacheUpdate = true;
        }
        
        //add notations.
        
        if(self::$blockNotation){
            $outputStr = $this->getNotation(true).$outputStr.$this->getNotation();
        }

        return $outputStr;
        
    }
    
    
    /**
     * renderObjectData()
     * 
     * called by render().  This function will takes the contents of the tag,
     * including the contents of all of the tags it contains, and
     * create a string version. 
     * 
     * @param   none
     * @return  String  a basic-context rendering of this tag.
     * 
     */
    protected function renderObjectData(){   
        
        if($this->renderedHtmlTag)
            return $this->renderedHtmlTag;
        
        $HtmlString = '';
        
        if($this->name){
        
            $HtmlString .= '&^;<'.$this->name.' ';

            foreach($this->attributes as $name=>$value){
                if($value == ""){
                    $HtmlString .=  "$name ";
                }else{
                    $HtmlString .=  "$name='$value' ";
                }
            }

            if($this->classes){

                $HtmlString .=  'class="';

                foreach($this->classes as $value){
                    $HtmlString .=  "$value ";
                }

                $HtmlString .=  '"';
            }

            if($this->self_closing){

                $HtmlString .=  " />".PHP_EOL;

            }else{

                $HtmlString .=  ">".PHP_EOL;

            }
        
        }
        
        $containedHtml = '';

        foreach($this->elements as $value){

            if(!is_a($value, 'HJ_Tag')){
                $HtmlString .= '&^;&#9;' . $value . PHP_EOL;
            }else{
                $HtmlString .= 
                    str_replace('&^;','&^;&#9;',$value->renderObjectData());
            }

        }
         
        if(!$this->self_closing && $this->name){
        
            $HtmlString .= "&^;</$this->name>".PHP_EOL;
            
        }
        
        $this->renderedHtmlTag = $HtmlString;
        
        return $HtmlString;
            

    }
    
    /**
     * applyReplacements()
     * 
     * called by render().  Performs all required replacements.
     * 
     * Each entry in an indexed must be an associative array.  Each will be 
     * passed back to this function in a recursive call for processing.
     * 
     * @param   Array   $vars   used for recursive self-calls only
     * @return  String  rendering of this tag with replacements.
     * 
     */
    public function applyReplacements($vars = null){
        
        if($vars === null){
            $vars = $this->replacements;
        }

        $element = reset($vars);
        
        $key = key($vars);
        
        if(!is_int($key)){
            
            $return_string = $this->renderedHtmlTag;
            
            foreach($vars as $key => $element){
                
                if(is_a($element, 'HJ_Tag')){
                    
                    $element = $element->renderObjectData();

                }
                    
                $return_string = str_replace($key, 
                    $element, $return_string);

            }

        }else{
            
            $return_string = '';
            
            foreach($vars as $entry){
                $return_string .= $this->applyReplacements($entry);
            }
            
        }
        
        return $return_string;
        
    }
    
    /**
     * applyIndent()
     * 
     * called by render().  Applies correct indentation.
     * 
     * @param   String   $outputStr     input rendering of this tag.
     * @return  String  rendering of this tag with indentations.
     */
    private function applyIndent($outputStr){
        
        $base_indent = '';
        
        if(self::$indent_str !== null){
              
            $outputStr = str_replace('&#9;', self::$indent_str, $outputStr);
            
            for($i=0; $i<self::$initial_indentation; $i++){
                $base_indent .= self::$indent_str;
            }
            
        }else{
            
            for($i=0; $i<self::$initial_indentation; $i++){
                $base_indent .= '&#9;';
            }
            
        }
        
        $outputStr = str_replace('&^;', $base_indent, $outputStr);
        
        return $outputStr;
        
    }
    
    /**
     * getNotation()
     * 
     * called by render().  returns notations as HTML comemnts.  returns either 
     * the initial notations for the block or the ending notations.
     * 
     * @param   Boolean   $opening     if true, returns initial notations
     * @return  String  string of notations.
     */
    public function getNotation($opening = true){
        
        if($this->notation != ''){
            $notate = $this->notation;
        }else{
            $notate = '<'.$this->name.'>';
        }
        
        if($opening){
            return PHP_EOL.
                    '<!-- start of ['.$notate.'] block -->'
                    .PHP_EOL.PHP_EOL;
        }else{
            return  PHP_EOL.
                    '<!-- end of ['.$notate.'] block -->'
                    .PHP_EOL.PHP_EOL;
        }
        
        
    }

    
}
