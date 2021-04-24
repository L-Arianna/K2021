<?php
include "configuration/config_etc.php";
include "configuration/config_include.php";
etc();
encryption();
session();
connect();
head();
body();
timing();
//alltotal();
pagination();
?>

<?php
if (!login_check()) {
?>
	<meta http-equiv="refresh" content="0; url=logout" />
<?php
	exit(0);
}
?>
<div class="wrapper">
	<?php
	theader();
	menu();
	body();
	?>
	<!--start page wrapper -->
	<div class="page-wrapper">
		<div class="page-content">
			<!-- BREADCRUMB -->
			<!-- SETTING START-->

			<?php
			error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
			include "configuration/config_chmod.php";
			$halaman = "invoice_jual"; // halaman
			$dataapa = "Invoice Penjualan"; // data
			$tabeldatabase = "invoicejual"; // tabel database
			$chmod = $chmenu6; // Hak akses Menu
			$forward = mysqli_real_escape_string($conn, $tabeldatabase); // tabel database
			$forwardpage = mysqli_real_escape_string($conn, $halaman); // halaman
			$tabel = "sale";

			$insert = $_POST['insert'];
			// $nota = $_GET['nota'];

			date_default_timezone_set("Asia/Jakarta");
			$today = date('d-m-Y');
			?>






			<ol class="breadcrumb ">
				<li><a href="index">Dashboard </a></li>
				<li><a href="penjualan"><?php echo $dataapa ?></a></li>
				<?php

				if ($search != null || $search != "") {
				?>
					<li> <a href="<?php echo $halaman; ?>">Data <?php echo $dataapa ?></a></li>
					<li class="active"><?php
												echo $search;
												?></li>
				<?php
				} else {
				?>
					<li class="active">Data <?php echo $dataapa ?></li>
				<?php
				}
				?>
			</ol>
			<h6 class="mb-0 text-uppercase"></h6>
			<hr />
			<div class="card">
				<div class="card-body">
					<!-- ./col -->


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


					$tglbayar = date("d-m-Y", strtotime($row['tglsale']));

					$due = date("d-m-Y", strtotime($row['duedate']));



					$bayar = $row['kasir'];
					$total = $row['total'];
					$keterangan = $row['keterangan'];
					$pelanggan = $row['pelanggan'];
					$status = $row['status'];
					$diskon = $row['diskon'];
					$pot = $row['potongan'];
					$biaya = $row['biaya'];
					$totalprice = $total + $pot - $biaya;

					$sql2 = "SELECT * FROM pelanggan where kode='$pelanggan' ";
					$hasil2 = mysqli_query($conn, $sql2);
					$row = mysqli_fetch_assoc($hasil2);

					$kode = $row['kode'];
					$customer = $row['nama'];
					$nohp = $row['nohp'];
					$address = $row['alamat'];

					$sql1 = "SELECT * FROM sale where nota='$nota' ";
					$hasil1 = mysqli_query($conn, $sql1);
					$row = mysqli_fetch_assoc($hasil1);

					$noPO = $row['no_po'];
					$faktur_pajak = $row['faktur_pajak'];
					$no_surat = $row['no_surat_jalan'];
					$namapt = $row['nama_pt'];
					$alamatpt = $row['alamat_pt'];
					$notelppt = $row['no_tlp'];

					$sql3 = "SELECT * FROM update_pelanggan where kode='$pelanggan' ";
					$hasil3 = mysqli_query($conn, $sql3);
					$row = mysqli_fetch_assoc($hasil3);

					$kode_new = $row['kode'];
					$customer_new = $row['nama'];
					$nohp_new = $row['nohp'];
					$address_new = $row['alamat'];

					?>

					<!-- BOX INSERT BERHASIL -->

					<!-- BREADCRUMB -->
					<?php
					//menyimpan ke tabel bayar
					if (isset($_POST["simpan"])) {
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
							// $nota = mysqli_real_escape_string($conn, $_POST["nota"]);
							$kode = mysqli_real_escape_string($conn, $_POST["kode"]);
							$customer = mysqli_real_escape_string($conn, $_POST["nama"]);
							$address = mysqli_real_escape_string($conn, $_POST["alamat"]);
							$nohp = mysqli_real_escape_string($conn, $_POST["nohp"]);

							$kode_new = mysqli_real_escape_string($conn, $_POST["kode"]);
							$customer_new = mysqli_real_escape_string($conn, $_POST["nama"]);
							$address_new = mysqli_real_escape_string($conn, $_POST["alamat"]);
							$nohp_new = mysqli_real_escape_string($conn, $_POST["nohp"]);



							$kasir = $_SESSION["username"];
							$berhasil = "berhasil";
							$belum = "belum";
							$insert = ($_POST["insert"]);


							$sql = "select * from sale where nota='$kode'";
							$result = mysqli_query($conn, $sql);

							if (mysqli_num_rows($result) > 0) {

								echo "<script type='text/javascript'>  alert('Data penjualan yang sudah ada tidak bisa diubah!');</script>";
							} else if (($chmod >= 2 || $_SESSION['jabatan'] == 'admin')) {


								// $sql2 = "UPDATE `sale` SET `pelanggan`='$customer',`alamat`='$address' ,`no_hp`= '$nohp' WHERE `nota` = '$nota'";
								$sql2 = "INSERT INTO `update_pelanggan`(`kode`, `nama`, `alamat`, `nohp`) VALUES ('$kode','$customer','$address','$nohp')";

								$insertan = mysqli_query($conn, $sql2);

								$sql5 = "UPDATE `update_pelanggan` SET `nama`='$customer_new',`alamat`='$address_new',`nohp`='$nohp_new' WHERE `kode` = '$kode_new'";
								$insertan = mysqli_query($conn, $sql5);


								//update mutasi
								$sql3 = "UPDATE mutasi SET status='$berhasil' where keterangan='$nota'";
								$updatemutasi = mysqli_query($conn, $sql3);


								echo "<script type='text/javascript'>  alert('Berhasil, Data telah disimpan!'); </script>";
								// echo "<script type='text/javascript'>window.location = 'surat_jalan';</script>";

								// echo print_r($sql2);
							} else {
								echo "<script type='text/javascript'>  alert('Gagal, Data gagal disimpan! Pastikan pembayaran benar');</script>";
							}
						}
					}


					?>



					<script>
						window.setTimeout(function() {
							$("#myAlert").fadeTo(500, 0).slideUp(1000, function() {
								$(this).remove();
							});
						}, 5000);
					</script>

					<!-- BOX INFORMASI -->
					<?php
					if ($chmod >= 2 || $_SESSION['jabatan'] == 'admin') {
					?>


						<!-- KONTEN BODY AWAL -->
						<div class="box box-default">
							<div class="box-header with-border">
								<h3 class="box-title">Data <?php echo $dataapa; ?></h3>
								<hr />
							</div>
							<!-- /.box-header -->

							<div class="box-body">
								<div class="table-responsive">
									<!----------------KONTEN------------------->
									<?php

									?>
									<div id="main">
										<div class="container-fluid">


											<!-- Main content -->
											<section class="invoice">
												<!-- title row -->
												<div class="row">
													<div class="col-sm">
														<h3>
															<?php echo $namapt; ?>
														</h3>
													</div>
													<div class="col-sm">
													</div>
													<div class="col-sm">
														<small>Date: <?php echo $today; ?></small>
													</div>
												</div>
												<!-- info row -->

												<div class="row mb-2">
													<div class="col-sm-4 invoice-col">
														FROM:
														<address>
															<strong> <?php echo $namapt; ?></strong><br>
															<?php echo $alamatpt; ?><br>
															Phone: <?php echo $notelppt; ?><br>
															<b>No Surat Jalan : <?= $no_surat ?></b><br>
															<b>No PO : <?= $noPO ?></b><br>
															<!--       Email: info@almasaeedstudio.com                 -->
														</address>
													</div>
													<!-- /.col -->
													<div class="col-sm-4 invoice-col">

													</div>
													<!-- /.col -->
													<div class="col-sm-4 invoice-col">
														To : <br>
														<?php if ($kode_new == null) { ?>
															<strong> <?php echo $customer; ?></strong><br>
															<?php echo $address; ?><br>
															Phone: <?php echo $nohp; ?><br>
														<?php } elseif ($kode_new > 0) { ?>
															<strong> <?php echo $customer_new; ?></strong><br>
															<?php echo $address_new; ?><br>
															Phone: <?php echo $nohp_new; ?><br>
														<?php } ?>
													</div>
													<!-- /.col -->
												</div>

												<!-- /.row -->
												<div class="box box-warning">
													<div class="box-body">
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


															<div class="col-md-12 table-responsive">
																<table class="table table-striped">
																	<thead>
																		<tr>
																			<th>Packing</th>
																			<th>Qty</th>
																			<th>Total Qty</th>
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
																				<td><?php echo mysqli_real_escape_string($conn, $fill['jumlah_satuan']); ?> <?php echo mysqli_real_escape_string($conn, $fill['satuan']); ?></td>
																				<td><?php echo mysqli_real_escape_string($conn, $fill['jumlah']); ?></td>
																				<td><?php echo mysqli_real_escape_string($conn, number_format($fill['total_satuan'])); ?> <?php echo mysqli_real_escape_string($conn, $fill['satuan']); ?></td>
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
														<!-- /.row -->
													</div>
												</div>

												<div class="box box-success">
													<div class="box-body">
														<div class="row mb-1">
															<div class="col-md-10">
																<p class="lead">Tanda terima:</p>
																<?php if ($kode_new == null) { ?>
																	<strong><?php echo $customer; ?></strong><br>
																<?php } elseif ($kode_new > 0) { ?>
																	<strong><?php echo $customer_new; ?></strong><br>
																<?php } ?>
																<br>
																<br>
																<br>
																<br>
																<br>
																<br>
																<br>
																<!-- <p><u>Nama Penerima</u></p> -->
															</div>
															<div class="col-md-2">
																<p class="lead">Hormat Kami</p>
															</div>
														</div>
														<div class="col">
															<div class="btn-group" role="group" aria-label="First group">
																<a href="print_surat_jalan?nota=<?php echo $nota; ?>" target="_blank" class="btn btn-primary btn-sm"><i class="bx bx-printer"></i> Print</a>

															</div>
															<?php if ($kode_new == 0) { ?>
																<a href="" class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#exampleExtraLargeModal"><i class="bx bx-edit-alt"></i>Ganti Nama Penerima</a>
															<?php } else { ?>
																<a href="" class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#exampleExtraLargeModal1"><i class="bx bx-edit-alt"></i>Ganti Nama </a>
															<?php } ?>

															<div class="modal fade" id="exampleExtraLargeModal" tabindex="-1" aria-hidden="true">
																<div class="modal-dialog modal-xl">
																	<div class="modal-content">
																		<div class="modal-header">
																			<h5 class="modal-title">Tambah Penerima Barang Baru</h5>
																			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
																		</div>
																		<form method="post" id="Myform" class="form-user">
																			<div class="modal-body">
																				<div class="row">
																					<div class="col-md-4">
																						<label for="form-control">Nama Pelanggan</label>
																						<input type="text" name="nama" class="form-control" value="<?php echo $customer; ?>" placeholder="masukan nama pelanggan" required>
																						<input type="hidden" name="kode" class="form-control" value="<?php echo $kode ?>">
																					</div>
																					<div class="col-md-4">
																						<label for="form-control">Alamat Pelanggan</label>
																						<input type="text" name="alamat" class="form-control" value="<?php echo $address; ?>" placeholder="masukan alamat pelanggan" required>
																					</div>
																					<div class="col-md-4">
																						<label for="form-control">Nomor Hp Pelanggan</label>
																						<input type="text" name="nohp" class="form-control" value="<?php echo $nohp; ?>" placeholder="masukan nomor hp pelanggan" required>
																					</div>
																				</div>
																			</div>
																			<div class="modal-footer">
																				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
																				<button type="submit" class="btn btn-primary" name="simpan" onclick=" document.getElementById('Myform').submit();">SIMPAN</button>
																			</div>
																		</form>
																	</div>
																</div>
															</div>

															<div class="modal fade" id="exampleExtraLargeModal1" tabindex="-1" aria-hidden="true">
																<div class="modal-dialog modal-xl">
																	<div class="modal-content">
																		<div class="modal-header">
																			<h5 class="modal-title">Edit Penerima Barang</h5>
																			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
																		</div>
																		<form method="post" id="Myform" class="form-user">
																			<div class="modal-body">
																				<div class="row">
																					<div class="col-md-4">
																						<label for="form-control">Nama Pelanggan</label>
																						<input type="text" name="nama" class="form-control" value="<?php echo $customer_new; ?>" placeholder="masukan nama pelanggan" required>
																						<input type="hidden" name="kode" class="form-control" value="<?php echo $kode_new ?>">
																					</div>
																					<div class="col-md-4">
																						<label for="form-control">Alamat Pelanggan</label>
																						<input type="text" name="alamat" class="form-control" value="<?php echo $address_new; ?>" placeholder="masukan alamat pelanggan" required>
																					</div>
																					<div class="col-md-4">
																						<label for="form-control">Nomor Hp Pelanggan</label>
																						<input type="text" name="nohp" class="form-control" value="<?php echo $nohp_new; ?>" placeholder="masukan nomor hp pelanggan" required>
																					</div>
																				</div>
																			</div>
																			<div class="modal-footer">
																				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
																				<button type="submit" class="btn btn-primary" name="simpan" onclick=" document.getElementById('Myform').submit();">SIMPAN</button>
																			</div>
																		</form>
																	</div>
																</div>
															</div>



														</div>
													</div>
													<!-- this row will not appear when printing -->
											</section>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php
					} else {
					?>
						<div class="callout callout-danger">
							<h4>Info</h4>
							<b>Hanya user tertentu yang dapat mengakses halaman <?php echo $dataapa; ?> ini .</b>
						</div>
					<?php
					}
					?>
				</div>
			</div>
			<div class="modal fade" id="modal-hutang">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">Hutang Penjualan #<?php echo $nota; ?></h4>
						</div>
						<div class="modal-body">
							<form method="post">
								<input type="hidden" class="form-control" value="<?php echo $nota; ?>" id="nota" name="nota">
								<div class="row">
									<div class="form-group col-md-8 col-xs-12">
										<label for="nama" class="col-sm-3 control-label">Debitur:</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="debitur" value="<?php echo $customer; ?>" readonly>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-8 col-xs-12">
										<label for="nama" class="col-sm-3 control-label">Jatuh Tempo:</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="datepicker" name="ref">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-8 col-xs-12">
										<label for="nama" class="col-sm-3 control-label">Jumlah:</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="jml" name="jml" value="<?php echo $totalprice; ?>" readonly>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-8 col-xs-12">
										<label for="nama" class="col-sm-3 control-label">Keterangan:</label>
										<div class="col-sm-9">
											<textarea style="width:100%"></textarea>
										</div>
									</div>
								</div>
								<input type="hidden" class="form-control" id="jml1" name="jml1" value="<?php echo $totalprice; ?>" readonly>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
							<button type="submit" name="save" class="btn btn-primary">Simpan</button>
						</div>
					</div>
					</form>
				</div>
			</div>
		</div>
		<script src='jquery-3.1.1.min.js' type='text/javascript'></script>
		<link href='jquery-ui.min.css' rel='stylesheet' type='text/css'>
		<script src='jquery-ui.min.js' type='text/javascript'></script>

		<script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
		<script src="1-11-4-jquery-ui.min.js"></script>
		<script>
			$.widget.bridge('uibutton', $.ui.button);
		</script>
		<script src="dist/bootstrap/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
		<script src="dist/plugins/morris/morris.min.js"></script>
		<script src="dist/plugins/sparkline/jquery.sparkline.min.js"></script>
		<script src="dist/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
		<script src="dist/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
		<script src="dist/plugins/knob/jquery.knob.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
		<script src="dist/plugins/daterangepicker/daterangepicker.js"></script>
		<script src="dist/plugins/datepicker/bootstrap-datepicker.js"></script>
		<script src="dist/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
		<script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
		<script src="dist/plugins/fastclick/fastclick.js"></script>
		<script src="dist/js/app.min.js"></script>
		<script src="dist/js/demo.js"></script>
		<script src="dist/plugins/datatables/jquery.dataTables.min.js"></script>
		<script src="dist/plugins/datatables/dataTables.bootstrap.min.js"></script>
		<script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
		<script src="dist/plugins/fastclick/fastclick.js"></script>
		<script src="dist/plugins/select2/select2.full.min.js"></script>
		<script src="dist/plugins/input-mask/jquery.inputmask.js"></script>
		<script src="dist/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
		<script src="dist/plugins/input-mask/jquery.inputmask.extensions.js"></script>
		<script src="dist/plugins/timepicker/bootstrap-timepicker.min.js"></script>
		<script src="dist/plugins/iCheck/icheck.min.js"></script>

		<!--fungsi AUTO Complete-->
		<!-- Script -->
		<script type='text/javascript'>
			$(function() {

				$("#barcode").autocomplete({
					source: function(request, response) {

						$.ajax({
							url: "server.php",
							type: 'post',
							dataType: "json",
							data: {
								search: request.term
							},
							success: function(data) {
								response(data);
							}
						});
					},
					select: function(event, ui) {
						$('#nama').val(ui.item.label);
						$('#barcode').val(ui.item.value); // display the selected text
						$('#hargajual').val(ui.item.hjual);
						$('#stok').val(ui.item.sisa); // display the selected text
						$('#hargabeli').val(ui.item.hbeli);
						$('#jumlah').val(ui.item.jumlah);
						$('#kode').val(ui.item.kode); // save selected id to input
						return false;

					}
				});

				// Multiple select
				$("#multi_autocomplete").autocomplete({
					source: function(request, response) {

						var searchText = extractLast(request.term);
						$.ajax({
							url: "server.php",
							type: 'post',
							dataType: "json",
							data: {
								search: searchText
							},
							success: function(data) {
								response(data);
							}
						});
					},
					select: function(event, ui) {
						var terms = split($('#multi_autocomplete').val());

						terms.pop();

						terms.push(ui.item.label);

						terms.push("");
						$('#multi_autocomplete').val(terms.join(", "));

						// Id
						var terms = split($('#selectuser_ids').val());

						terms.pop();

						terms.push(ui.item.value);

						terms.push("");
						$('#selectuser_ids').val(terms.join(", "));

						return false;
					}

				});
			});

			function split(val) {
				return val.split(/,\s*/);
			}

			function extractLast(term) {
				return split(term).pop();
			}
		</script>

		<!--AUTO Complete-->

		<script>
			$(function() {
				//Initialize Select2 Elements
				$(".select2").select2();

				//Datemask dd/mm/yyyy
				$("#datemask").inputmask("yyyy-mm-dd", {
					"placeholder": "yyyy/mm/dd"
				});
				//Datemask2 mm/dd/yyyy
				$("#datemask2").inputmask("yyyy-mm-dd", {
					"placeholder": "yyyy/mm/dd"
				});
				//Money Euro
				$("[data-mask]").inputmask();

				//Date range picker
				$('#reservation').daterangepicker();
				//Date range picker with time picker
				$('#reservationtime').daterangepicker({
					timePicker: true,
					timePickerIncrement: 30,
					format: 'YYYY/MM/DD h:mm A'
				});
				//Date range as a button
				$('#daterange-btn').daterangepicker({
						ranges: {
							'Hari Ini': [moment(), moment()],
							'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
							'Akhir 7 Hari': [moment().subtract(6, 'days'), moment()],
							'Akhir 30 Hari': [moment().subtract(29, 'days'), moment()],
							'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
							'Akhir Bulan': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
						},
						startDate: moment().subtract(29, 'days'),
						endDate: moment()
					},
					function(start, end) {
						$('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
					}
				);

				//Date picker
				$('#datepicker').datepicker({
					autoclose: true
				});

				$('.datepicker').datepicker({
					dateFormat: 'yyyy-mm-dd'
				});

				//Date picker 2
				$('#datepicker2').datepicker('update', new Date());

				$('#datepicker2').datepicker({
					autoclose: true
				});

				$('.datepicker2').datepicker({
					dateFormat: 'yyyy-mm-dd'
				});


				//iCheck for checkbox and radio inputs
				$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
					checkboxClass: 'icheckbox_minimal-blue',
					radioClass: 'iradio_minimal-blue'
				});
				//Red color scheme for iCheck
				$('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
					checkboxClass: 'icheckbox_minimal-red',
					radioClass: 'iradio_minimal-red'
				});
				//Flat red color scheme for iCheck
				$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
					checkboxClass: 'icheckbox_flat-green',
					radioClass: 'iradio_flat-green'
				});

				//Colorpicker
				$(".my-colorpicker1").colorpicker();
				//color picker with addon
				$(".my-colorpicker2").colorpicker();

				//Timepicker
				$(".timepicker").timepicker({
					showInputs: false
				});
			});
		</script>
		<?php footer() ?>