<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>PT. Kurnia Makmur| Invoice</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="dist/bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="dist/ico/font-awesome/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="dist/ico/ionicons/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>



<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));


include "configuration/config_chmod.php";
include "configuration/config_connect.php";
$halaman = "invoice_jual"; // halaman
$dataapa = "Invoice Penjualan"; // data
$tabeldatabase = "invoicejual"; // tabel database
$chmod = $chmenu6; // Hak akses Menu
$forward = mysqli_real_escape_string($conn, $tabeldatabase); // tabel database
$forwardpage = mysqli_real_escape_string($conn, $halaman); // halaman
$tabel = "sale";

date_default_timezone_set("Asia/Jakarta");
$today = date('d-m-Y');

?>
<?php
$decimal = "0";
$a_decimal = ",";
$thousand = ".";
?>
<?php
$nota = $_GET["nota"];

$sql1 = "SELECT * FROM data";
$hasil1 = mysqli_query($conn, $sql1);
$row = mysqli_fetch_assoc($hasil1);
$nama = $row['nama'];
$alamat = $row['alamat'];
$notelp = $row['notelp'];
$tagline = $row['tagline'];
$signature = $row['signature'];
$avatar = $row['avatar'];

$sql1 = "SELECT * FROM $tabel where nota='$nota'";
$hasil1 = mysqli_query($conn, $sql1);
$row = mysqli_fetch_assoc($hasil1);

$bayar = $row['kasir'];
$total = $row['total'];
$status = $row['status'];
$keterangan = $row['keterangan'];
$pelanggan = $row['pelanggan'];
$diskon = $row['diskon'];
$pot = $row['potongan'];
$biaya = $row['biaya'];
$totalprice = $total + $pot - $biaya;

$tglbayar = date("d-m-Y", strtotime($row['tglsale']));

$due = date("d-m-Y", strtotime($row['duedate']));

$sql1 = "SELECT * FROM sale where nota='$nota' ";
$hasil1 = mysqli_query($conn, $sql1);
$row = mysqli_fetch_assoc($hasil1);
$customer = $row['pelanggan'];
$nohp = $row['no_hp'];
$address = $row['alamat'];
$noPO = $row['no_po'];
$faktur_pajak = $row['faktur_pajak'];
$no_surat = $row['no_surat_jalan'];



?>


<body onload="window.print();">
	<!--   -->
	<div class="wrapper">
		<!-- Main content -->
		<section class="invoice">
			<!-- title row -->
			<div class="row">
				<div class="col-lg-12">
					<h2 class="page-header">
						<i class="fa fa-globe"></i> <?php echo $nama; ?>
						<small class="pull-right">SURAT JALAN || Date: <?php echo $today; ?></small>
					</h2>
				</div>
				<!-- /.col -->
			</div>
			<!-- info row -->
			<div class="row invoice-info">
				<div class="col-sm-5 invoice-col">
					<strong>From</strong>
					<address>
					</address>
				</div>
				<!-- /.col -->
				<div class="col-sm-3 invoice-col">
					To
					<address>
						<strong><?php echo $customer; ?></strong><br>
						<?php echo $address; ?><br>

						Phone: <?php echo $nohp; ?><br>
					</address>
				</div>
				<!-- /.col -->
				<div class="col-sm-4 invoice-col">
					<b>Invoice #<?php echo $nota; ?></b><br>
					<b>No Surat Jalan : </b><br>
					<b>No PO : </b><br>
					<b>Jatuh Tempo:</b> <?php echo $due; ?><br>
				</div>
				<!-- /.col -->
			</div>
			<!-- /.row -->

			<!-- Table row -->
			<div class="row">
				<?php
				error_reporting(E_ALL ^ E_DEPRECATED);

				$sql    = "select * from $tabeldatabase where nota ='$nota' order by no";
				$result = mysqli_query($conn, $sql);
				$rpp    = 15;
				$reload = "$halaman" . "?pagination=true";
				$page   = intval(isset($_GET["page"]) ? $_GET["page"] : 0);



				if ($page <= 0)
					$page = 1;
				$tcount  = mysqli_num_rows($result);
				$tpages  = ($tcount) ? ceil($tcount / $rpp) : 1;
				$count   = 0;
				$i       = ($page - 1) * $rpp;
				$no_urut = ($page - 1) * $rpp;
				?>
				<div class="col-xs-12 table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Qty</th>
								<th>Product</th>
							</tr>
						</thead>
						<?php
						error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
						while (($count < $rpp) && ($i < $tcount)) {
							mysqli_data_seek($result, $i);
							$fill = mysqli_fetch_array($result);
						?>
							<tbody>
								<tr>
									<td><?php echo mysqli_real_escape_string($conn, $fill['jumlah']); ?></td>
									<td><?php echo mysqli_real_escape_string($conn, $fill['nama']); ?></td>
								</tr>


							<?php
							$i++;
							$count++;
						}

							?>
							</tbody>
					</table>
				</div>
				<!-- /.col -->
			</div>
			<div class="col-lg-12">
				<table class="table table-print">
					<thead>
						<tr>
							<th>Tanda terima </th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th>Hormat Kami</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $customer; ?></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="4">
								<table class="table mb-10">
									<br>
									<br>
									<br>
									<br>
									<br>
									<u>(------------------------------)</u>
								</table>
							</td>
						</tr>
					</tbody>
				</table>



		</section>
		<!-- /.content -->
	</div>
	<H4 align="center"><?php echo $signature ?><H4>
			<!-- ./wrapper -->
</body>

</html>