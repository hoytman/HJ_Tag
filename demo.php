<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/New_York');

function show(){
    
    $element = ob_get_clean(); 
    echo '<pre>';
    echo str_replace(array('<','>'),array('&lt;','&gt;'),$element);
    echo '</pre>';
    eval($element);
    
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <style>
        
            body{
                padding:    10px;
                font-size:  18px;
                font-family: arial;
            }
            
            .red{color: #900;}
            .black{color: #000;}  
            .green{color: #060;}
            .blue{color: #00c;}
            
            pre{
                
                width:      auto; 
                padding:    10px 10px 0px 10px; 
                margin:     5px 10px;
                border:     2px solid #000;
                margin-bottom: 20px;
            }
            
            pre.not-done{
                background: burlywood;
            }
            
            .demo{
                border-radius: 10px;
                width:      auto; 
                padding:    10px; 
                margin:     0px 0px;
                border:     2px solid #555;
            }
            
            .box{
                padding:    10px;
                border:     2px solid #555;
                display:    inline-block;
            }

            @media screen and (min-width: 768px) {
                .demo   { width: 750px;}
                pre     { width: 730px;}
                
            }

            p.demo{ 
                background: #dff;
            }
        
            h1.demo{
                padding: 10px;
                margin-top: 30px;
                margin-bottom: 5px;
                background: #ff9;
            }
            
            h3.demo{
                padding: 10px;
                margin-top: 30px;
                margin-bottom: 5px;
                background: #ccf;
            }

        </style>
    
    </head>

    <body>
      
      
        <h1 class="demo">
            HJ_Tag class help
        </h1>
        
        <h3 class="demo">
            Setup
        </h3>
        
        <p class="demo">
            The HJ_Tag class is an object oriented Html generator.  In order to
            use it, first you need to include it.  (note, all of the code that
            appears in a white background section is performed for this 
            demonstration as they appear.)
        </p>
        
        <pre class="">
            require_once('HJ_Tag.php');
        </pre>
        
<?php require_once('HJ_Tag.php'); ?>
        
        <p class="demo">
            Next, specify the characters that comprise one tab level using the
            static function setIndentString().  The default is 4 spaces.
        </p>
        
        <pre class="">
            HJ_Tag::setIndentString('    ');
        </pre>

<?php HJ_Tag::setIndentString('    '); ?>
        
        <p class="demo">
            Specify the number of indentation levels that the rendered 
            tags should start at using the static function setIndentLevel().
            We will use 2.  Note: &lt;head&gt; should be at 0
            and &lt;body&gt; should be at 1.  If you ever want to manually 
            change the indent level, you can call this function again.
        </p>
        
        <pre class="">
            HJ_Tag::setIndentLevel(2);
        </pre>
        
<?php HJ_Tag::setIndentLevel(2); ?>
        
        <p class="demo">
            You can also call the static function activateBlockNotation() to 
            place Html comment
            blocks around rendered groups of tags.   (note, all of the code that
            appears in a brown background section is not performed.)           
        </p>
        
        <pre class="not-done">
            HJ_Tag::activateBlockNotation();
        </pre>

        <h3 class="demo">
            Rendering a tag object
        </h3>
        
        <p class="demo">
            First, create a new HJ_Tag object and pass a string that designated
            the type of tag that this object will represent (h1, p, div, etc.)
            note: if the tage is self closing, place '/' at the end of the 
            paramater like this: 'br/'.
        </p>
        
        <pre class="">
            $tag_title = new HJ_Tag('h1');
        </pre>
        
<?php $tag_title = new HJ_Tag('h1'); ?>
        
        <p class="demo">
            You can call addAttribute() to add attributes to the tag (id, 
            href, target, style, etc.)  addAttribute() takes two paramaters: the name of the 
            attribute and the value.  Also, addAttribute() can be called multiple times
            to add several attributes.
        </p>
        
        <pre class="">
            $tag_title->addAttribute("id", "main_header");
        </pre>
        
<?php $tag_title->addAttribute("id", "main_header"); ?>
        
        <p class="demo">
            You can call addClass() to add classes to the tag.  addClass() 
            accepts one paramater: a class name. Also, addAttribute() can be 
            called repeatedly.
        </p>
        
        <pre class="">
            $tag_title->addClass("red");
        </pre>
        
<?php $tag_title->addClass("red"); ?>
        
        <p class="demo">
            You can call addHtml() to add a string or another HT_Tag to the
            current HJ_Tag obejct.  When rendered, it will be placed betwen the 
            open and close tags of the current HJ_Tag object.  addHtml() can 
            be called multiple times.
        </p>
        
        <pre class="">
            $tag_title->addHtml("This is an H1 tag");
        </pre>
        
<?php $tag_title->addHtml("This is an H1 tag"); ?>
            
      <p class="demo">
            Finally, call render() and the HJ_Tag object will render and return 
            an Html string for you to print.  
        </p>
        
        <pre class="">
            echo $tag_title->render();
        </pre>
        
<?php echo $tag_title->render(); ?>

        <h3 class="demo">
            Rendering a tag object using add()
        </h3>
        
        <p class="demo">
            add() is a multi-use function that can replace addClass(), addAttribute(),
            and addHtml().  to use it, pass 'class', 'html' or and attribute name as
            the first parameter.  Then pass the value as the second.  Also, if only 
            one parameter is passed, it is added using addHtml().<br /><br />
            Valid parameters for class: 'class' <br />
            Valid parameters for Html: 'html', 'Html', 'HTML', *none<br />
            Attributes are specified individually by name.
        </p>
        
        <pre class="">
            $tag_subtitle = new HJ_Tag('h2');
     
            $tag_subtitle->add('id', 'sub_title');
            $tag_subtitle->add('class', 'green');
            $tag_subtitle->add('Html', 'This is an H2 tag. ');
            $tag_subtitle->add('It is smaller than H1.');
     
            echo $tag_subtitle->render();
        </pre>
        
<?php 
            $tag_subtitle = new HJ_Tag('h2');
     
            $tag_subtitle->add('id', 'sub_title');
            $tag_subtitle->add('class', 'blue');
            $tag_subtitle->add('Html', 'This is an H2 tag. ');
            $tag_subtitle->add('It is smaller than H1.');
     
            echo $tag_subtitle->render();
        ?>
            
        <h3 class="demo">
            Rendering a tag object using add() and an array.
        </h3>
        
        <p class="demo">
            add() can also accept an associative array containing name/value pairs
        </p>
        
        <pre class="">
            $tag_p = new HJ_tag('p');

            $data_array = array(
                'id'=>'first_sub_text',
                'class'=>'black',
                'html'=>'a paragraph tag is great for holding text!'
            );

            $tag_p->add($data_array);

            echo $tag_p->render();
        </pre>
        
<?php 
            $tag_p = new HJ_tag('p');

            $data_array = array(
                'id'=>'first_sub_text',
                'class'=>'green',
                'html'=>'A paragraph tag is great for holding text!'
            );

            $tag_p->add($data_array);

            echo $tag_p->render();
        ?>
        
        <h3 class="demo">
            Changing and re-rendering tags
        </h3>
        
        <p class="demo">
            Even after a tag is rendered, you can add additional Html, classes, 
            or attributes to that object and render it again.
        </p>
        
        <pre class="">
            $tag_p->add(" Now... Let's add some more text.");
            echo $tag_p->render();
        </pre>
        
<?php 
        
            $tag_p->add(" Now... Let's add some more text.");
            echo $tag_p->render();
            
        ?>
        
        <p class="demo">
            If you want to clear a specific kind of data from the tag before adding
            more, use reset();<br /><br />
            Valid parameters for all classes: 'class', 'classes' <br />
            Valid parameters for all Html: 'html', 'Html', 'HTML'<br />
            Valid parameters for all attributes: 'attribute', 'attributes'<br />
            Also, individual attributes can be specified by name.
        </p>
        
        <pre class="">
            $tag_p->reset('html');
            $tag_p->reset('class');
            $tag_p->add(" New text!");
            $tag_p->add('class', 'blue');
            echo $tag_p->render();
        </pre>
        
<?php 
        
            $tag_p->reset('html');
            $tag_p->reset('class');
            $tag_p->add(" New text, same p tag object");
            $tag_p->add('class', 'blue');
            echo $tag_p->render();
            
        ?>
        
        <h3 class="demo">
            Capturing text from the output buffer
        </h3>
        
        <p class="demo">
            You can use capture() to aquire text strings directly from the 
            document.  Simple call capture() before an Html section, 
            and all of the Html text will be acquired by HJ_Tag.  finaly, 
            call either add() or addHtml() without parameters to store the text
            as HTML
            
        </p>
        
        <pre class="">

            $tag_capture = new HJ_Tag('div');
            $tag_capture->capture(); 

?&gt;
        
            &lt;h1&gt;Hello There&lt;/h1&gt;
            &lt;p&gt;it is nice to meet you&lt;/p&gt;
        
&lt;?php

            $tag_capture->addHtml();
            echo $tag_capture->render();
            
        </pre>
        
<?php 
        
            $tag_capture = new HJ_Tag('div');
            $tag_capture->capture(); 
            
?>
        
        <h1>Hello There</h1>
        <p>it is nice to meet you</p>
        
<?php
        
            $tag_capture->addHtml();
            echo $tag_capture->render();
            
            
        ?>
        
        
        

        <h3 class="demo">
            Nesting Tags
        </h3>
        
        <p class="demo">
            Tag objects can be added to other tag objects using add() or addHtml()
            Also, if you include a second and third parameter in the object constructor,
            they will automatically be used to call add();
        </p>
        
        <pre class="">
            $tag_list = new HJ_Tag('ul');

            $tag_list_item = new HJ_Tag('li', 'this is a list item');

            $tag_list_item_2 = new HJ_Tag('li', 'another list item');
            $tag_list_item_2->add('class', 'red');

            $tag_list->add($tag_list_item);
            $tag_list->add($tag_list_item_2);
            
            echo $tag_list->render();
        </pre>
        
<?php 
        
            $tag_list = new HJ_Tag('ul');

            $tag_list_item = new HJ_Tag('li', 'this is a list item');

            $tag_list_item_2 = new HJ_Tag('li', 'another list item');
            $tag_list_item_2->add('class', 'red');

            $tag_list->add($tag_list_item);
            $tag_list->add($tag_list_item_2);
            
            echo $tag_list->render();
        ?>

        <p class="demo">
            Because tag objects are stored as objects and then rendered at the same time,
            you will need to create and add multiple objects as shown above.  In the example 
            below, a single tag object is used to add two Html strings.  Note the result.
        </p>
        
        <pre class="">
            $tag_list = new HJ_Tag('ul');

            $tag_list_item = new HJ_Tag('li', 'I should be first');

            $tag_list->add($tag_list_item);

            $tag_list_item->reset('html');
            $tag_list_item->add('However, this is what happens!');
            $tag_list_item->add('class', 'red');

            $tag_list->add($tag_list_item);

            echo $tag_list->render();
        </pre>
        
<?php 
            $tag_list = new HJ_Tag('ul');

            $tag_list_item = new HJ_Tag('li', 'I should be first');

            $tag_list->add($tag_list_item);

            $tag_list_item->reset('html');
            $tag_list_item->add('However, this is what happens!');
            $tag_list_item->add('class', 'red');

            $tag_list->add($tag_list_item);

            echo $tag_list->render();
?>
 
        <h3 class="demo">
            Using Replacements
        </h3>
        
        <p class="demo">
            You can create templates (containing place holders) and fill in those
            place holders with important data
            during the render process.  as a demo, we will use template.html.
            template.html contains the following:
        </p>
        
        <pre>

&lt;div class="section" style="display:inline-block; 
    border:1px solid black; margin 5px; padding: 5px"&gt;
  &lt;h2 class="section-heading" style="color:brown;"&gt;@@my_title&lt;/h2&gt;
  &lt;p class="section-text" style="margin:20px;"&gt;@@some_text&lt;/p&gt;
&lt;/div&gt;

        </pre>     
<div class="section" style="display:inline-block; border:1px solid black; margin 5px; padding: 5px">
  <h2 class="section-heading" style="color:brown;">@@my_title</h2>
  <p class="section-text" style="margin:20px;">@@some_text</p>
</div>
         <p class="demo">
            To use a template file, simply combine file_get_contents() with add().
            Note: if you load Html from a file, you do not have to
            set the tag type when you call new HJ_Tag();.
        </p>
        <pre>
            
            $file_tag = new HJ_Tag();
            $file_tag->add(file_get_contents('template.html'));
            echo $file_tag->render();

        </pre>
        

<?php
        $file_tag = new HJ_Tag();
        $file_tag->add(file_get_contents('template.html'));
        echo $file_tag->render();
?>
        
        <p class="demo">
            @@my_title and @@some_text are the place holders.  To perform the replacement,
            call setReplacement() with an associative array that contains the new
            data.  Note: we can reuse $file_tag 
        </p>  
        
        <pre>
            
            $block_info = array(
                '@@my_title' => 'My Block',
                '@@some_text' => 'This is an amazing block!',
            );

            $file_tag->setReplacement($block_info);
            echo $file_tag->render();

        </pre>

<?php
            $block_info = array(
                '@@my_title' => 'My Block',
                '@@some_text' => 'This is an amazing block!',
            );

            $file_tag->setReplacement($block_info);
            echo $file_tag->render();
?>
        
        <p class="demo">
            Calling setReplacement() again will replace any databank
            that has already been set.
        </p>
        
                <pre>
            
            $block_info = array(
                '@@my_title' => 'My Block',
                '@@some_text' => 'This is an amazing block!',
            );

            $file_tag->setReplacement($block_info);
            echo $file_tag->render();

        </pre>

<?php
            $block_info = array(
                '@@my_title' => 'My Block',
                '@@some_text' => 'New data',
            );

            $file_tag->setReplacement($block_info);
            echo $file_tag->render();
?>
        <p class="demo">
            You can use setReplacement() to iterate over an array and
            create multiple Html blocks.  If you pass setReplacement() 
            an indexed array which contains associative arrays, multiple 
            Html sections will be created/.
        </p>
        
                <pre>
            
            $block_collection[] = array(
                '@@my_title' => 'Block #1',
                '@@some_text' => 'This is an amazing block!',
            );

            $block_collection[] = array(
                '@@my_title' => 'Block #2',
                '@@some_text' => 'This block is even better!',
            );

            $block_collection[] = array(
                '@@my_title' => 'Block #3',
                '@@some_text' => 'Look out!  Its block 3!',
            );

            $file_tag->setReplacement($block_collection);
            echo $file_tag->render();

        </pre>

<?php
            $block_collection[] = array(
                '@@my_title' => 'Block #1',
                '@@some_text' => 'This is an amazing block!',
            );

            $block_collection[] = array(
                '@@my_title' => 'Block #2',
                '@@some_text' => 'This block is even better!',
            );

            $block_collection[] = array(
                '@@my_title' => 'Block #3',
                '@@some_text' => 'Look out!  Its block 3!',
            );

            $file_tag->setReplacement($block_collection);
            echo $file_tag->render();
?>
       
         <p class="demo">
            You can also iterate over HJ_Tag objects.  This is very useful for
            making template files.
        </p>
        
            <pre>

            $block_collection = array();

            $list_tag = new HJ_Tag('ul');
            $item_tag = new HJ_Tag('li', 'list item');

            $list_tag->add($item_tag);
            $list_tag->add($item_tag);
            $list_tag->add($item_tag);
            
            $block_collection[] = array(
                '@@my_title' => 'Block #1',
                '@@some_text' => $list_tag,
            );

            $block_collection[] = array(
                '@@my_title' => 'Block #2',
                '@@some_text' => $list_tag,
            );

            $block_collection[] = array(
                '@@my_title' => 'Block #3',
                '@@some_text' => $list_tag,
            );

            $file_tag->setReplacement($block_collection);
            echo $file_tag->render();

        </pre>

<?php
            $block_collection = array();

            $block_collection = array();

            $list_tag = new HJ_Tag('ul');
            $item_tag = new HJ_Tag('li', 'list item');
            
            $list_tag->add($item_tag);
            $list_tag->add($item_tag);
            $list_tag->add($item_tag);

            
            $block_collection[] = array(
                '@@my_title' => 'Block #1',
                '@@some_text' => $list_tag,
            );

            $block_collection[] = array(
                '@@my_title' => 'Block #2',
                '@@some_text' => $list_tag,
            );

            $block_collection[] = array(
                '@@my_title' => 'Block #3',
                '@@some_text' => $list_tag,
            );

            $file_tag->setReplacement($block_collection);
            echo $file_tag->render();
?>
 
        <h3 class="demo">
            Tag caching
        </h3>
        
        <p class="demo">
            The system has a caching feature which can be used to save renders
            to a file.  First, you will need to use LoadHTMLCache() to 
            specify the location of the cache file.  then, any time you want to
            save a tag to the cache, call  $cached_tag->setCacheId();  the 
            first parameter is a unique id for that tag's content.  the second and third
            parameters specify a range of time (in seconds) within which the cache will
            be re-rendered.  This tag cache will be re-rendered somewhere between 100 
            and 200 seconds from its creation
        </p> 
        <pre>

            HJ_Tag::LoadHTMLCache("HTMLcache.txt");

            $cached_tag = new HJ_Tag('div');

            $cached_tag->setCacheId('cache_001', 100, 200);

        </pre>

        <p class="demo">
            Next, wrap all of the code for that tag (except the render() call)
            in an if() block and fill the conditional with the cache() function
            as shown.  This will skip the block unless the re-render needs to happen();
            Finally, the render function will provide data from the cache.  Note:
            if the time displayed is inaccurate, that's because it's coming from
            the cache!
        </p> 
        <pre>

            if($cached_tag->cache()){

                echo '&lt;h3 class="red"&gt;Re-rendered!&lt;/h3&gt;';

                $cached_tag->add('class', 'box');
                $cached_tag->add('style', 'width: 200px;');

                $h1_tag = new HJ_Tag('h1', 'Hello There');

                $cached_tag->add($h1_tag);

                $p_tag = new HJ_Tag('p');
                $p_tag->add('The current time is:');
                $p_tag->add(date("F j, Y, g:i:s a"));

                $cached_tag->add($p_tag);

            }

            echo $cached_tag->render();
        </pre>
        
<?php

            HJ_Tag::LoadHTMLCache("HTMLcache.txt");
            
            $cached_tag = new HJ_Tag('div');

            $cached_tag->setCacheId('cache_001', 100, 200);

            if($cached_tag->cache()){
                
                echo '<h3 class="red">Re-rendered!</h3>';

                $cached_tag->add('class', 'box');
                $cached_tag->add('style', 'width: 200px;');

                $h1_tag = new HJ_Tag('h1', 'Hello There');

                $cached_tag->add($h1_tag);

                $p_tag = new HJ_Tag('p');
                $p_tag->add('The current time is:');
                $p_tag->add(date("F j, Y, g:i:s a"));

                $cached_tag->add($p_tag);

            }

            echo $cached_tag->render();
                
?>
        <p class="demo">
            Cached values can also include replacements.  Note: Once a tag is cached,
            it will either be completely rendered, or completely copied from the cache.
            There is no way to do anything new to a tag that is coming from the cache
            unless you add that tag to another tag.
        <p>
        <pre>
        
            $cached_file_tag = new HJ_Tag();

            $cached_file_tag->setCacheId('cache_002', 100, 200);

            if($cached_file_tag->cache()){

                echo '&lt;h3 class="red"&gt;Re-rendered!&lt;/h3&gt;';

                $cached_file_tag->
                    add(file_get_contents('template.html'));

                $block_collection = array();
                
                $block_collection[] = array(
                    '@@my_title' => 'Time Block',
                    '@@some_text' => date("F j, Y, g:i:s a"),
                );

                $block_collection[] = array(
                    '@@my_title' => 'Random Block',
                    '@@some_text' => rand(0,1000),
                );

                $cached_file_tag->setReplacement($block_collection);

            }

            echo $cached_file_tag->render();

        </pre>
<?php
        
            $cached_file_tag = new HJ_Tag();

            $cached_file_tag->setCacheId('cache_002', 100, 200);

            if($cached_file_tag->cache()){
                
                echo '<h3 class="red">Re-rendered!</h3>';

                $cached_file_tag->add(file_get_contents('template.html'));

                $block_collection = array();
                
                $block_collection[] = array(
                    '@@my_title' => 'Time Block',
                    '@@some_text' => date("F j, Y, g:i:s a"),
                );

                $block_collection[] = array(
                    '@@my_title' => 'Random Block',
                    '@@some_text' => rand(0,1000),
                );

                $cached_file_tag->setReplacement($block_collection);

            }

            echo $cached_file_tag->render();
            
?>
        <p class="demo">
            If you want to force the section to re-render the cache, pass 'true'
            to the cache() function.  
        <p>
        <pre>
        
            $p_tag = new HJ_Tag('p');

            $p_tag->setCacheId('cache_002', 1000000, 200000);

            if($div_tag->cache(true)){

                echo '&lt;h3 class="red"&gt;Re-rendered!&lt;/h3&gt;';

                $p_tag->add(date("F j, Y, g:i:s a"));

            }

            echo $p_tag->render();

        </pre>
        
<?php
        
            $p_tag = new HJ_Tag('p');

            $p_tag->setCacheId('cache_003', 1000000, 200000);

            if($p_tag->cache(true)){
                
                echo '<h3 class="red">Re-rendered!</h3>';

                $p_tag->add(date("F j, Y, g:i:s a"));

            }

            echo $p_tag->render();
            
?>

  </body>
</html>
