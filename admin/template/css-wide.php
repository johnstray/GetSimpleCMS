<?php

if (!defined('IN_GS')) {
    die('you cannot load this page directly.');
}

/**
 * CSS
 **/
?>
/* CSS-wide.php */
/* <style> */
body{
    margin:0 15px;
}

body #header{
    margin-left: -15px;
    padding: 0 15px;
    margin-right: -15px;
}

.wrapper{
    /* main wrapper */
    width:100%;
    max-width:<?php echo $width; ?>;
}

<?php echo $widepagecss; ?>

.wrapper .nav{
    /* top header nav wrapper */
    width:75%;
}

.wrapper .nav.secondary{
    /* top header nav wrapper */
    width:100%;
}

.wrapper table {
    /* tables in wrapper, eg page management lists */
    width:100%;
}

#maincontent{
    /* wrapper for admin inputs */
    width:100%;
    float:left;
    /* margin-left: 35px; */
}

#maincontent .main {
    margin-right: 250px;
    min-width: 330px;
}

#sidebar {
    margin-left: -225px;
} /*margin-right:15px;*/

body.nosidebar #header .wrapper  {
    width:100%;
}

body.nosidebar #maincontent .main {
    margin-right: 0;
}

textarea, form input.title{
    /* resize backend textareas */
    width:100% ;
}

form.manyinputs textarea{
    /* resize backend textareas for components */
    width:100% ;
} /*max-width: 600px;*/


.wideopt,.widesec {
    max-width:800px;
}

.leftopt,.rightopt,.leftsec,.rightsec {
    max-width:400px;
}

.rightopt,.rightsec {
    float:left;
}

/* </style> */
