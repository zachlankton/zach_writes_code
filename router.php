<?php

    $uri = strtok($_SERVER['REQUEST_URI'], "?");
    
    require ("classes/router.php");
    
    $rtr = new Router();
    
    $dir = "routes";
    
    //find all paths that have an index.php 
    $routes = shell_exec('find ' . $dir . '/ -name "index.php"');
    
    // remove $dir and index.php from / route
    $routes = str_replace($dir . "/index.php", "/", $routes);
    
    // remove /index.php from all routes
    $routes = str_replace("/index.php", "", $routes);
    
    // remove $dir from all routes
    $routes = str_replace($dir, "", $routes);
    
    // split route string into an array of routes
    $routes = explode("\n", $routes);
    
    $route = $rtr->get_route($uri, $routes);
    
    if ($route == "Route Not Found") die("Route Not Found!");
    
    include __DIR__ . "/routes" . $route . "/index.php";
?>