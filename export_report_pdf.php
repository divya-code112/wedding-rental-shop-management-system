<?php
include "../includes/db.php";
require_once __DIR__ . "/../includes/TCPDF-main/tcpdf.php";

$type = $_GET['type'] ?? 'profit';
$from = $_GET['from'] ?? date('Y-m-01');
$to   = $_GET['to'] ?? date('Y-m-d');

$pdf = new TCPDF('P','mm','A4',true,'UTF-8',false);
$pdf->SetCreator('Royal Drapes');
$pdf->SetTitle('Business Report');
$pdf->SetMargins(10,10,10);
$pdf->AddPage();
$pdf->SetFont('helvetica','',11);

$html = "<h2 style='text-align:center'>Royal Drapes</h2>
<h4 style='text-align:center'>".ucwords($type)." Report</h4>
<p><b>From:</b> $from &nbsp;&nbsp; <b>To:</b> $to</p><hr>";

// ---------- PREPARE CHART IMAGE ----------
function chart_image($profits,$losses){
    $w=500;$h=300;
    $img=imagecreatetruecolor($w,$h);
    $white=imagecolorallocate($img,255,255,255);
    $black=imagecolorallocate($img,0,0,0);
    $green=imagecolorallocate($img,0,128,0);
    $red=imagecolorallocate($img,255,0,0);
    imagefill($img,0,0,$white);

    $margin=50; $maxVal=max(max($profits),max($losses))+50;
    $barWidth=20;
    for($i=0;$i<12;$i++){
        $x1=$margin+$i*40;
        $y1=$h-($profits[$i]/$maxVal)*($h-2*$margin)-$margin;
        $x2=$x1+$barWidth;
        $y2=$h-$margin;
        imagefilledrectangle($img,$x1,$y1,$x2,$y2,$green);

        $x1l=$x1+$barWidth+5;
        $x2l=$x1l+$barWidth;
        $y1l=$h-($losses[$i]/$maxVal)*($h-2*$margin)-$margin;
        $y2l=$h-$margin;
        imagefilledrectangle($img,$x1l,$y1l,$x2l,$y2l,$red);
    }
    $tmp=tempnam(sys_get_temp_dir(),'chart').'.png';
    imagepng($img,$tmp);
    imagedestroy($img);
    return $tmp;
}

// ---------- PROFIT REPORT ----------
if($type=='profit'){
    $profit = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT IFNULL(SUM(amount),0)c FROM payment
         WHERE payment_status='success' AND DATE(transaction_date) BETWEEN '$from' AND '$to'"))['c'];
    $loss_late = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT IFNULL(SUM(late_fee),0)c FROM returns WHERE DATE(return_date) BETWEEN '$from' AND '$to'"))['c'];
    $loss_damage = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT IFNULL(SUM(damage_fee),0)c FROM returns WHERE DATE(return_date) BETWEEN '$from' AND '$to'"))['c'];
    $refund = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT IFNULL(SUM(amount),0)c FROM payment WHERE payment_type='refund' AND DATE(transaction_date) BETWEEN '$from' AND '$to'"))['c'];

    $total_loss = $loss_late + $loss_damage + $refund;
    $net = $profit - $total_loss;

    $html.="<table border='1' cellpadding='8'>
    <tr><th>Profit</th><td>₹".number_format($profit,2)."</td></tr>
    <tr><th>Total Loss</th><td>₹".number_format($total_loss,2)."</td></tr>
    <tr><th>Net Profit</th><td>₹".number_format($net,2)."</td></tr>
    </table><br>";

    $profits=[];
    $losses=[];
    for($m=1;$m<=12;$m++){
        $p=mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(amount),0)c FROM payment WHERE payment_status='success' AND MONTH(transaction_date)=$m"))['c'];
        $l=mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(late_fee+damage_fee),0)c FROM returns WHERE MONTH(return_date)=$m"))['c'];
        $profits[]=$p; $losses[]=$l;
    }
    $chart_file = chart_image($profits,$losses);
    $pdf->Image($chart_file,15,$pdf->GetY(),180,80);
    unlink($chart_file);
}

// ---------- OTHER REPORTS ----------
elseif($type=='sales'){
    $res=mysqli_query($conn,"SELECT order_id,order_date,total_amount_payable FROM orders WHERE DATE(order_date) BETWEEN '$from' AND '$to'");
    $html.="<table border='1' cellpadding='6'><tr><th>Order ID</th><th>Date</th><th>Amount</th></tr>";
    while($r=mysqli_fetch_assoc($res)){
        $html.="<tr><td>{$r['order_id']}</td><td>{$r['order_date']}</td><td>₹{$r['total_amount_payable']}</td></tr>";
    }
    $html.="</table>";
}
elseif($type=='returns'){
    $res=mysqli_query($conn,"SELECT return_id,late_fee,damage_fee FROM returns WHERE DATE(return_date) BETWEEN '$from' AND '$to'");
    $html.="<table border='1' cellpadding='6'><tr><th>ID</th><th>Late Fee</th><th>Damage Fee</th></tr>";
    while($r=mysqli_fetch_assoc($res)){
        $html.="<tr><td>{$r['return_id']}</td><td>₹{$r['late_fee']}</td><td>₹{$r['damage_fee']}</td></tr>";
    }
    $html.="</table>";
}
elseif($type=='payments'){
    $res=mysqli_query($conn,"SELECT payment_id,amount,payment_method,payment_status FROM payment WHERE DATE(transaction_date) BETWEEN '$from' AND '$to'");
    $html.="<table border='1' cellpadding='6'><tr><th>ID</th><th>Amount</th><th>Method</th><th>Status</th></tr>";
    while($r=mysqli_fetch_assoc($res)){
        $html.="<tr><td>{$r['payment_id']}</td><td>₹{$r['amount']}</td><td>{$r['payment_method']}</td><td>{$r['payment_status']}</td></tr>";
    }
    $html.="</table>";
}
elseif($type=='rental'){
    $res=mysqli_query($conn,"SELECT o.order_id,u.full_name,p.product_name,oi.rental_days,oi.total_price
          FROM order_items oi
          JOIN orders o ON oi.order_id=o.order_id
          JOIN users u ON o.user_id=u.user_id
          JOIN products p ON oi.product_id=p.product_id
          WHERE DATE(o.order_date) BETWEEN '$from' AND '$to'");
    $html.="<table border='1' cellpadding='6'><tr><th>Order ID</th><th>Customer</th><th>Product</th><th>Days</th><th>Amount</th></tr>";
    while($r=mysqli_fetch_assoc($res)){
        $html.="<tr><td>{$r['order_id']}</td><td>{$r['full_name']}</td><td>{$r['product_name']}</td><td>{$r['rental_days']}</td><td>₹{$r['total_price']}</td></tr>";
    }
    $html.="</table>";
}
else{ $html.="<p>No data available for selected report.</p>"; }

$pdf->writeHTML($html);
$pdf->Output("{$type}_report.pdf","D");
