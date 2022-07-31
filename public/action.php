<?php

//action.php

$connect = new PDO("mysql:host=localhost; dbname=cpps", "root", "JuanseyAlfo");

$received_data = json_decode(file_get_contents("php://input"));

$data = array();

if($received_data->query != '')
{
	$query = "
	SELECT * FROM cpps07 
        WHERE cod_os LIKE '%".$received_data->query."%' 
        OR desc_os LIKE '%".$received_data->query."%' 
        ORDER BY id DESC
	";
}
else
{
	$query = "
	SELECT * FROM cpps07 
	ORDER BY id DESC
	";
}

$statement = $connect->prepare($query);

$statement->execute();

while($row = $statement->fetch(PDO::FETCH_ASSOC))
{
	$data[] = $row;
}

echo json_encode($data);

?>