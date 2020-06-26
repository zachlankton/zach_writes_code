<style>
    html { font-family: sans-serif; }
    .red { background-color: red; }
    .green { background-color: green; }
</style>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/** markdown 

# Run Tests!

Run tests is our cool little testing library

*/
    function _assert($expr, $expected, $msg = ""){
        $dbg = debug_backtrace();
        $file = $dbg[0]['file'];
        $lineNo = $dbg[0]['line'];
        
        $result = $expr === $expected ? "PASS" : "FAIL";
        $class = $expr === $expected ? 'class="green"' : 'class="red"';
        
        echo "<li $class> $file: $lineNo: '$expr'==='$expected' --- $msg: $result </li>";
    }
    
   
   // GET DEFAULT CLASSES THAT ARE LOADED INTO PHP
   $default_classes = get_declared_classes();
   
   // REQUIRE ALL TESTS IN THE DIRECTORY OF TESTS
   foreach (scandir("tests") as $filename){
       $path = "tests/" . $filename;
       if (is_file($path)){
           try {
               require $path;
           }catch (Exception $e){
               echo $e->getMessage();
           }
       }
   }
   
   // LOAD/INIT ALL THE CLASSES THAT EXISTS INSIDE THOSE THAT WE REQUIRED
   $fresh_classes = get_declared_classes();
   $classes = array_diff_key($fresh_classes, $default_classes);
   
   
   // LOOP THROUGH EACH CLASS 
   foreach ($classes as $class){
       echo "<h4> Test Class: $class </h4>";
       $instance = new $class;
       runTests( $instance );
       
   }

   // AND RUN EACH TEST INSIDE THE CLASS
   function runTests( $instance ){
   
       $tests = get_class_methods($instance);
       
       foreach( $tests as $test ){
           if ($test == "__construct") continue;
           echo "<h5>Test Function: $test</h5>";
           echo "<ul>";
           try {
               $instance->$test();
           } catch (Exception $e) {
               echo $e->getMessage();
           }
           echo "</ul>";
       }
   }
   

?>