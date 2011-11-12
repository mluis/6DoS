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
    }else{
        $graph=$_SESSION['NetworkGraph'];
    }
    if(isset ($_GET['error'])){
        echo 'alert("'.$_GET['error'].'");';
    }
    ?>
        <form id="myForm" action="iface.php" method="post"> 
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
                    <tr>
                        <td><input style="visibility: hidden" type="input" name="first" id="first"></input></td>
                    </tr>
                    <tr>
                        <td><input style="visibility: hidden" type="input" name="second" id="second"></input></td>
                    </tr>
                </table>
            </form>
    </body>
</html>
