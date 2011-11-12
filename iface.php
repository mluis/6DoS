<html>
<head>
<title>Force-Directed Layout</title>
<script type="text/javascript" src="http://mbostock.github.com/protovis/protovis-r3.2.js"></script>
<script type="text/javascript" src="graph.js"></script>
<style type="text/css">

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
    session_start();
    if($_SESSION['NetworkGraph']==NULL){
        $db = new CodeBitsDatabase();
        $graph = new NetworkGraph($db,true);
        $_SESSION['NetworkGraph']=$graph;
    }
    
    $graph=$_SESSION['NetworkGraph'];
    
    if(!isset ($_POST['name']) || !isset ($_POST['password'])){
        header("Location: login.php?error=parametros errados");
        return;
    }
    if(!$graph->login($_POST['name'], $_POST['password'])){
        header("Location: login.php?error=login");
    }
    
?>

<table>
    <tr><label style="color: white">Select Users</lable></tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td><label style="color: white">First User</lable></td>
                    <td><input type="input" name="first" id="first"></input></td>
                </tr>
                <tr>
                    <td><label style="color: white">Second User</lable></td>
                    <td><input type="input" name="second" id="second"></input></td>
                </tr>
                <tr><td><input type="submit" value="Get graph!"/></td></tr>
            </table>
        </td>
    </tr>
</table>
<script type="text/javascript+protovis">
    
	var w = document.body.clientWidth,
	h = document.body.clientHeight,
	colors = pv.Colors.category19();

	var vis = new pv.Panel()
        .canvas("graph")
	.width(w)
	.height(h)
	.event("mousedown", pv.Behavior.pan())
	.event("mousewheel", pv.Behavior.zoom(1.2));
        pv.Behavior.zoom(1);
	var force = vis.add(pv.Layout.Force)
	.nodes(miserables.nodes)
	.links(miserables.links)
        .chargeConstant(-100) 
        .springConstant(0.05) 
        .chargeMaxDistance(600) 
        .springLength(150);
        
        force.link.add(pv.Line).lineWidth(2)
        .strokeStyle("#78B9E2");
        
	/*force.node.add(pv.Dot)
	.size(function(d) (d.linkDegree + 4) * Math.pow(this.scale, -1.5))
	.fillStyle(function(d) d.fix ? "brown" : colors(d.group))
	.strokeStyle(function() this.fillStyle().darker())
	.lineWidth(1)
	.title(function(d) d.nodeName)
	.event("mousedown", pv.Behavior.drag())
	.event("drag", force);*/
        
	force.node.add(pv.Image)
	.url(function(n) n.url)
	.width(function(n)10+(n.friends*0.05))
	.height(function(n)10+(n.friends*0.05))
	.event("mousedown", pv.Behavior.drag())
	.event("drag", force);
	vis.render();

</script>

<div id="graph">
</div>
</body>
</html>
