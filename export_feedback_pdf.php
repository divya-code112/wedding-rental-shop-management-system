<?php
include "../includes/db.php";
require_once __DIR__ . "/../includes/TCPDF-main/tcpdf.php";

$search = $_GET['search'] ?? '';
$rating_filter = $_GET['rating'] ?? '';

$where = " WHERE 1 ";
if($search != ''){
    $search = mysqli_real_escape_string($conn, $search);
    $where .= " AND (u.full_name LIKE '%$search%' OR p.product_name LIKE '%$search%') ";
}
if($rating_filter != ''){
    $rating_filter = intval($rating_filter);
    $where .= " AND f.rating = $rating_filter ";
}

$res = mysqli_query($conn,"
    SELECT f.feedback_id, u.full_name, p.product_name, f.rating, f.comments, f.feedback_date
    FROM feedback f
    JOIN users u ON f.user_id=u.user_id
    JOIN products p ON f.product_id=p.product_id
    $where
    ORDER BY f.feedback_date DESC
");

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica','',12);

$html = "<h2>User Feedback Report</h2><hr>";
$html .= "<table border='1' cellpadding='6'>
<tr><th>ID</th><th>User</th><th>Product</th><th>Rating</th><th>Comments</th><th>Date</th></tr>";

while($row=mysqli_fetch_assoc($res)){
    $html.="<tr>
        <td>{$row['feedback_id']}</td>
        <td>{$row['full_name']}</td>
        <td>{$row['product_name']}</td>
        <td>{$row['rating']}</td>
        <td>{$row['comments']}</td>
        <td>{$row['feedback_date']}</td>
    </tr>";
}

$html.="</table>";
$pdf->writeHTML($html);
$pdf->Output("feedback_report.pdf","D");
