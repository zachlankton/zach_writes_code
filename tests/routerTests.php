<?php

    class RouterTest {
        
        function __construct(){
            require "classes/router.php";
        }
        
        function test_get_route(){
            
            $rtr = new Router();
            
            // setup inputs
            $routes = [
                "/",
                "/user/:id",
                "/user/add",
                "/orders",
                "/orders/new",
                "/orders/:id",
                "/orders/delete/:id",
                "/ambiguous/:id",
                "/ambiguous/:test",
                "/ambiguous/static",
                "/ambiguous/:var/test"
            ];
            
            
            
            $test1 =  $rtr->get_route("/",                              $routes);
            $test2 =  $rtr->get_route("/user/1234",                     $routes);
            $test3 =  $rtr->get_route("/orders",                        $routes);
            $test4 =  $rtr->get_route("/orders/new",                    $routes);
            $test5 =  $rtr->get_route("/orders/654",                    $routes);
            $test6 =  $rtr->get_route("/orders/delete/987",             $routes);
            $test7 =  $rtr->get_route("/something_that_doesnt_exist",   $routes);
            $test8 =  $rtr->get_route("/ambiguous/9999",                $routes);
            $test9 =  $rtr->get_route("/ambiguous/static",              $routes);
            $test10 = $rtr->get_route("/ambiguous/9999/test",           $routes);
            
            
            
            //      $expr               $expected                       $msg
            _assert($test1,             "/",                        "get / STATIC"                          );
            _assert($test2,             "/user/:id",                "get /user/1234 DYNAMIC"                );
            _assert($test3,             "/orders",                  "get /orders STATIC"                    );
            _assert($test4,             "/orders/new",              "get /orders/new STATIC"                );
            _assert($test5,             "/orders/:id",              "get /orders/654 DYNAMIC"               );
            _assert($test6,             "/orders/delete/:id",       "get /orders/delete/987 DYNAMIC"        );
            _assert($test7,             "Route Not Found",          "get /something_that_doesnt_exist"      );
            _assert($test9,             "/ambiguous/static",        "get /ambiguous/static STATIC"          );
            _assert($test10,            "/ambiguous/:var/test",     "get /ambiguous/9999/test DYNAMIC"      );
            
            _assert($test8['error'],        "ambiguous routes matched",     "get /ambiguous/9999 DYNAMIC"                           );
            _assert($test8['routes'][0],    "/ambiguous/:id",               "get /ambiguous/9999 ROUTES MATCHED 1 DYNAMIC"          );
            _assert($test8['routes'][1],    "/ambiguous/:test",             "get /ambiguous/9999 ROUTES MATCHED 2 DYNAMIC"          );
            
        }

    }

?>