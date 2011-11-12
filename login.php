<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <style>
            body {
            background-image:url('image/armyofbots.jpg');
            background-repeat:no-repeat;
            }
        </style>
    </head>
    <body>
    <?php
    require_once 'CodebitsApi.php';
    require_once 'NetworkGraph.php';
    require_once 'class.database.php';
    if(!isset ($_SESSION)){
        session_start();
    }
    if($_SESSION['NetworkGraph']==NULL){
        $db = new CodeBitsDatabase();
        $graph = new NetworkGraph($db,true);
        $_SESSION['NetworkGraph']=$graph;
    }
    if(isset ($_GET['error'])){
        echo 'alert("'.$_GET['error'].'");';
    }
    ?>
        <form id="myForm" action="iface.php?first=Eriksson Monteiro&second=Celso Martinho" method="post"> 
                <table>
                    <tr>
                        <td><label style="color: white">Name</label></td>
                        <td><input type="text" name="name" id ="name" /></td>
                    </tr>
                    <tr>
                        <td><label style="color: white">Password</label></td>
                        <td><input type="password" name="password" id="password"/></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" value="login"/> </td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>
