<?php

$conn = mysqli_connect('localhost', 'root', '', 'demo');
$columns = array('emp_no', 'first_name', 'last_name', 'hire_date');
$request = $_REQUEST;

$query = $conn->query("SELECT emp_no, first_name, last_name, hire_date FROM employees");
$totalData = $query->num_rows;

$sql = "SELECT emp_no, first_name, last_name, hire_date FROM employees WHERE 1 = 1";

//Searching
if(!empty($request['sSearch']))
{	
	$search = $request['sSearch'];
	$sql .= " AND emp_no LIKE '".$search."%'";
	$sql .= " OR first_name LIKE '%".$search."%'";
	$sql .= " OR last_name LIKE '%".$search."%'";
	$sql .= " OR hire_date LIKE '".$search."%'";
}

$query = $conn->query($sql);
$totalFilter = $query->num_rows;

// Ordering and limitation
$sql .= " ORDER BY ". $columns[$request['iSortCol_0']]. " ". $request['sSortDir_0']. " LIMIT ". $request['iDisplayStart'] .",". $request['iDisplayLength'];

$query = $conn->query($sql);

$data=array();
while ($res = $query->fetch_array(MYSQLI_ASSOC))
{
	$data[] = $res;
}

$response = array(
	'draw' => intval($request['draw']),
	'recordsTotal' => intval($totalData),
	'recordsFiltered' => intval($totalFilter),
	'data' => $data
);

echo json_encode($response);