<?php
    require('remote/init.php');


    $request = new Request(array('restful' => true));

  //  print_r($request);



    if(isset($_SESSION['uid']))
    {
    require('remote/app/controllers/' . $request->controller . '.php');
    $controller_name = ucfirst($request->controller);
    $controller = new $controller_name;

  // print_r($request);

    echo $controller->dispatch($request);
    }

else
{
    echo 'unidentified user';
}

