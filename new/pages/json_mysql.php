
<?php
//Converting db values into json data
header('content-type:application/json');

$con=mysqli_connect('localhost', 'root', '123', 'scholarship') or die(mysqli_error());

$sql = '';
$qry = "SELECT FormData  FROM `studentscholarship";
$result=mysqli_query($con,$qry);
$rows=array();

while($row = mysqli_fetch_array($result))
{
		$rows[] = $row;
}

$jsonfile = json_encode($rows);
$array = json_decode($jsonfile, true);

foreach($array as $row)
{
	 $sql .= "INSERT INTO json_mysql_blob(sno, edit, stype,studentid, duration_of_course, applicant_name_surname, course_or_subject, date_of_birth, gender, green_book_no, gb_date_and_place_of_issue, gb_last_payment, residential_certificate_no, rc_date_and_place_of_issue, rc_valid_till, father_name_surname, f_green_book_no, f_gb_last_payment_made, mother_name_and_surname, m_green_book_no, m_gb_last_payment_date, spouse_name_and_surname, s_green_book_no, s_gb_last_payment_made, medical_fitness_c_s, permanent_address, contact_numbers) VALUES('".$row[" "]."', '".$row["edit_type"]."', '".$row["stype"]."', '".$row["studentid"]."', '".$row["duration_of_course"]."', '".$row["applicant_name_surname"]."',  '".$row["course_or_subject"]."', '".$row["date_of_birth"]."', '".$row["gender"]."', '".$row["green_book_no"]."', '".$row["gb_date_and_place_of_issue"]."', '".$row["gb_last_payment"]."', '".$row["residential_certificate_no"]."', '".$row["rc_date_and_place_of_issue"]."', '".$row["rc_valid_till"]."', '".$row["father_name_surname"]."', '".$row["f_green_book_no"]."', '".$row["f_gb_last_payment_made"]."', '".$row["mother_name_and_surname"]."', '".$row["m_green_book_no"]."', '".$row["m_gb_last_payment_date"]."', '".$row["spouse_name_and_surname"]."', '".$row["s_green_book_no"]."', '".$row["s_gb_last_payment_made"]."', '".$row["medical_fitness_c_s"]."', '".$row["contact_numbers"]."');";

	 	mysqli_query($con, $sql);
}

echo "Form Data is Inserted Correctly";

?>