<?php

    require "parsedown/parsedown.php";
    
    $Parsedown = new Parsedown();
    
    $docs = shell_exec("grep -irl '\/\*\* markdown' "); // find files with markdown comment blocks
    
    $docs = explode("\n", $docs); // split list of files into an array
    
    array_pop($docs); // remove empty element from the end
    
    sort($docs);
    
    $toc = generate_toc($docs);
    
    $html_docs = generate_docs($docs);
    
?>

<html>
    <head>
        <link rel="stylesheet" href="parsedown/prism.css" />
        <style>
        
            * { box-sizing: border-box; }
            
            body {
                font-family: sans-serif;
                display: grid;
                grid-template-areas:
                    "header header"
                    "sidebar docs";
                grid-template-rows: 50px 1fr;
                grid-template-columns: 300px 1fr;
                margin: 0px;
                background-color: #ececec;
            }
            
            table {
                border-width: 1px 0px 0px 1px;
                border-style: solid;
                border-color: black;
                border-spacing: 0px;
            }
            
            th, td {
                border-width: 0px 1px 1px 0px;
                border-style: solid;
                border-color: black;
                padding: 10px;
            }
            
            header {
                grid-area: header;
                color: #b5b5b5;
                background-color: #383838;
                box-shadow: 0px 6px 10px -5px black;
                z-index: 99;
            }
            
            header>h1 { margin: 10px;}
            
            table-of-contents {
                grid-area: sidebar;
                background-color: #c5c5c5;
                padding: 10px;
                box-shadow: inset -6px 0px 7px -6px black;
            }
            
            my-docs {
                grid-area: docs;
                padding: 10px 10px 200px 10px;
            }
            
            table-of-contents, my-docs {
                max-height: calc(100vh - 52px);
                overflow-y: auto;
            }
            
            button[goto-top]{
                position: fixed;
                bottom: 10px;
                right: 10px;
            }
            section {
                width:80%; 
                margin:0 auto;
            }
        </style>
    </head>
    <body>
        
        <header>
            <a name='top'></a>
            <h1>Docs</h1>
        </header>
        
        <table-of-contents>
            <h3>Table of Contents</h3>    
            <?= $toc ?>
        </table-of-contents>
        
        <my-docs>
            <?= $html_docs ?>
        </my-docs>
        
        <a href='#top'>
            <button goto-top>Go To Top</button>
        </a>
        
        <script src="parsedown/prism.js"></script>
    </body>
</html>

<?php

    function generate_toc($docs){
        $toc = "";
        foreach($docs as $doc){
            $title = beautify_name($doc);
            $toc .= "<li> <a href='#$doc' > $title </a> </li>";
        }
        return $toc;
    }
    
    function generate_docs($docs){
        $html_docs = "";
        foreach($docs as $doc){
            
            $title = beautify_name($doc);
            
            $html_docs .= "<a name='$doc'></a>";  // set anchor link for table of contents
            $html_docs .= "<h1> $title </h1> <hr>"; // set title header of doc (path/file)
            
            $matches = find_markdown_blocks_in_file($doc);
            
            $html_docs .= "<section>";  // create a section for each doc
            $html_docs .= get_markdown($matches);  // generate markdown for each markdown comment block found in the file
            $html_docs .= "</section>";
        }
        return $html_docs;
    }
    
    function beautify_name($doc){
        $doc = str_replace("_docs/", "", $doc);
        $doc = str_replace("_", " ", $doc);
        $doc = str_replace(".md", "", $doc);
        return $doc;
    }
    
    function find_markdown_blocks_in_file($doc){
        $dRoot = $_SERVER['DOCUMENT_ROOT'];
        $file = file_get_contents($dRoot . "/" . $doc);
        preg_match_all("/\/\*\* markdown([\S\s]*?)\*\//", $file, $matches);
        return $matches[1]; // return only the group/text between the opening and closing markdown comment block ([\S\s]*?)
    }

    function get_markdown($matches){
        global $Parsedown;
        $html_docs = "";
        foreach($matches as $match){
            $html_docs .= $Parsedown->text($match);
            $html_docs .= "<hr>";
        }
        return $html_docs;
    }
?>






