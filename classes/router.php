<?php 
    
    class Router {
        
/** markdown

# Get Route {#my-cool-header}

Get Route is a Function that will take 2 parameters: uri, 
and a routes array and return the route that matches the request

```php
    public function get_route ( $uri , $routes ) {
        return $routes_matched | $ambiguous_routes | "Route Not Found";
    }
```

Param | Description
---- | ----
$uri | The request from the client (ie: /user/1234 )
$routes | An array (list) of routes

*/
        public function get_route($uri, $routes){
            // if static route found return $uri;
            if (in_array($uri, $routes) ) return $uri;
            
            // else find dynamic routes that match
            
            $routes_matched = [];
            
            //loop through each route
            foreach( $routes as $route) {
                $dPos = strpos($route, ":");  // look for : indicating a dynamic section
                if ($dPos !== false) { // this route contains dynamic sections
                    
                    // split route and uri into arrays
                    $uriSplit = explode("/", $uri);
                    $routeSplit = explode("/", $route);
                    
                    // get lengths of arrays and check if same
                    $uCount = count($uriSplit);
                    $rCount = count($routeSplit);
                    if ($rCount !== $uCount) continue;
                    
                    // pop off the blank value at the begging of the arrays
                    array_shift($uriSplit);
                    array_shift($routeSplit);
                    
                    $routesMatch = $this->dynamic_route_match($uriSplit, $routeSplit);
                    if ($routesMatch) $routes_matched[] = $route;
                }
            }
            
            // if we found a single match we successfully matched a route
            if ( count($routes_matched) == 1) return $routes_matched[0];
            
            // if we got more than one, the routes list contains ambiguous dynamic routes
            if ( count($routes_matched) > 1 ) return ["error"=>"ambiguous routes matched", "routes"=>$routes_matched];
            
            
            return ("Route Not Found");
        }
        
/** markdown

# Dynamic Route Match

```php
    private function dynamic_route_match ( $uriSplit , $routeSplit ) {
        return true | false ;
    }
```

*/
        private function dynamic_route_match($uriSplit, $routeSplit){
            
            $matches = true;
            
            foreach ($uriSplit as $key => $uriSection){
                
                //check if this section is dynamic skip
                if ( strpos($routeSplit[$key], ":") !== false ) continue;
                
                // check if static section matches
                if ($uriSection !== $routeSplit[$key]) {
                    $matches = false;
                    break;
                }
            }
            
            return $matches;
                        
        }

    }
?>




