<?php
require_once("../../config.inc.php");
require_once("../functions/common.php");
require_once("../functions/xml.inc.php");

testlinkInitPage($db);
$templateCfg = templateConfiguration();
$args = init_args();
$projectName= $args->tproject_name;
$tproject_id= $args->tproject_id;

$sql='select prefix from testprojects where id='.$tproject_id;
$result=$db->exec_query($sql);
$myrow = $db->fetch_array($result);
$project_prefix=$myrow['prefix'];

$sql='select id, name from nodes_hierarchy where node_type_id=2 and parent_id='.$tproject_id;
$result = $db->exec_query($sql);
$tree='<div><ul style="list-style-type:none">';
while($myrow = $db->fetch_array($result))
{
	$ID = $myrow['id'];
	$name= $myrow['name'];
	$sql='select id, name from nodes_hierarchy where parent_id='.$ID.' and id in (select parent_id from nodes_hierarchy where id in (select id from tcversions where execution_type=2))';
	$data=$db->exec_query($sql);
	if($data->_numOfRows>0)
	{
		$tree.='<label id="suite'.$ID.'">-</label><input type="checkbox" class="suiteCheckbox" id="ch-'.$ID.'"><span id="'.$ID.'" class="testSuiteName" style="cursor: pointer;">'.$name.'</span>';
		$tree.='<li><ul style="list-style-type:none;" id="'.'tc'.$ID.'">';
		while($row=$db->fetch_array($data))
		{
			$tree.='<li><input type="checkbox" class="checkbox-ch-'.$ID.'" name="testcase" value="'.$row['name'].'" id="'.$row['id'].'">'.$row['name'];
			$sql='select value from cfield_design_values inner join nodes_hierarchy on node_id=id where node_type_id=4 and parent_id='.$row['id'];
			$fileNames=$db->exec_query($sql);
			$fileRow=$db->fetch_array($fileNames);
			$tree.='<input type="text" value="'.$fileRow['value'].'"></li>';
		}
		$tree.='</ul></li>';
	}
}
$tree.='</ul></div>';
$body='<body><form action="triggerAutomation.php" method="post"> <input type="text" name="projectName" value="'.$projectName.'" disabled>';
$body.='<input type="hidden" value="'.$project_prefix.'" name="project_prefix"><input type="submit" value="Start Project Automation">'.$tree.'</form></body></html>';

$script='<!DOCTYPE html><html><head><script src="/testlink/third_party/jquery/jquery-2.0.3.min.js"></script>
<script>
$(document).ready(function(){
    $(".testSuiteName").click(function(){
        var id=$(this).attr("id");
		$("#tc"+id).slideToggle();
		$("#suite"+id).html(($("#suite"+id).html()==="+")?"-":"+");
    });
	
	$(".suiteCheckbox").change(function(){
		var id=$(this).attr("id");
		$(".checkbox-"+id).prop("checked", ($(this).is(":checked"))?true:false);
	});
});
</script></head>';

echo $script;
echo $body;


function init_args()
{
  $_REQUEST = strings_stripSlashes($_REQUEST);
    
  $args = new stdClass();

  $args->tproject_name = $_SESSION['testprojectName'];
  $args->tproject_id= $_SESSION['testprojectID'];

  return $args;
}
?>