<?php
include "connection.php";

$categoryId = $_POST['categoryId'];
echo "<option>Select SubCategory</option>";
$res= mysql_query("select * from freelancer_mmv_filter WHERE parent_id = '$categoryId'"); 
while($datas = mysql_fetch_array($res))
{
	echo '<option value='.$datas[filter_id].'>'.$datas[title].'</option>';
}
?>