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
<?php
theader();
menu();
body();
?>
<div class="page-wrapper">
  <div class="page-content">
    <div class="row">
      <div class="col-lg-12">
        <!-- SETTING START-->

        <?php
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        include "configuration/config_chmod.php";
        $halaman = "hutang_beli"; // halaman
        $dataapa = "Hutang Pembelian"; // data
        $tabeldatabase = "hutang"; // tabel database
        $chmod = $chmenu5; // Hak akses Menu
        $forward = mysqli_real_escape_string($conn, $tabeldatabase); // tabel database
        $forwardpage = mysqli_real_escape_string($conn, $halaman); // halaman
        $search = $_POST['search'];

        ?>

        <!-- SETTING STOP -->


        <!-- BREADCRUMB -->

        <ol class="breadcrumb ">
          <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard </a></li>
          <li><a href="<?php echo $halaman; ?>"><?php echo $dataapa ?></a></li>
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

        <!-- BREADCRUMB -->

        <!-- BOX HAPUS BERHASIL -->

        <script>
          window.setTimeout(function() {
            $("#myAlert").fadeTo(500, 0).slideUp(1000, function() {
              $(this).remove();
            });
          }, 5000);
        </script>

        <?php
        $hapusberhasil = $_POST['hapusberhasil'];

        if ($hapusberhasil == 1) {
        ?>
          <div id="myAlert" class="alert alert-success alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Berhasil!</strong> <?php echo $dataapa; ?> telah berhasil dihapus dari Data supplier <?php echo $dataapa; ?>.
          </div>

          <!-- BOX HAPUS BERHASIL -->
        <?php
        } elseif ($hapusberhasil == 2) {
        ?>
          <div id="myAlert" class="alert alert-danger alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Gagal!</strong> <?php echo $dataapa; ?> tidak bisa dihapus dari Data <?php echo $dataapa; ?> karena telah melakukan transaksi sebelumnya, gunakan menu update untuk merubah informasi <?php echo $dataapa; ?> .
          </div>
        <?php
        } elseif ($hapusberhasil == 3) {
        ?>
          <div id="myAlert" class="alert alert-danger alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Gagal!</strong> Hanya user tertentu yang dapat mengupdate Data <?php echo $dataapa; ?> .
          </div>
        <?php
        }

        ?>
        <!-- BOX INFORMASI -->
        <?php
        if ($chmod == '1' || $chmod == '2' || $chmod == '3' || $chmod == '4' || $chmod == '5' || $_SESSION['jabatan'] == 'admin') {
        } else {
        ?>
          <div class="callout callout-danger">
            <h4>Info</h4>
            <b>Hanya user tertentu yang dapat mengakses halaman <?php echo $dataapa; ?> ini .</b>
          </div>
        <?php
        }
        ?>

        <?php
        if ($chmod >= 1 || $_SESSION['jabatan'] == 'admin') {
        ?>

          <?php

          $sqla = "SELECT no, COUNT( * ) AS totaldata FROM $forward";
          $hasila = mysqli_query($conn, $sqla);
          $rowa = mysqli_fetch_assoc($hasila);
          $totaldata = $rowa['totaldata'];

          ?>
          <div class="card">
            <div class="card-header">
              <h3>Data <?php echo $forward ?> <span class="label label-default"><?php echo $totaldata; ?></span>
              </h3>
            </div>

            <!-- /.box-header -->
            <!-- /.Paginasi -->
            <?php
            error_reporting(E_ALL ^ E_DEPRECATED);
            $sql    = "select * from $forward order by no";
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
            <div class="card-body">
            
            <form method="post">
                <br />
                <div class="input-group input-group-sm" style="width: 250px;">
                  <input type="text" name="search" class="form-control pull-right" placeholder="Cari">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-primary"><i class="bx bx-search"></i></button>
                  </div>
                </div>
              </form>
              <div class="table-responsive">
              <table class="table table-hover ">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nota</th>
                    <th>Supplier</th>
                    <th>Tanggal</th>
                    <th>Jatuh Tempo</th>
                    <th>Jumlah</th>
                    <th>Status</th>

                    <?php if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') { ?>
                      <th>Opsi</th>
                    <?php } else {
                    } ?>
                  </tr>
                </thead>
                <?php
                error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
                $search = $_POST['search'];

                if ($search != null || $search != "") {

                  if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    if (isset($_POST['search'])) {
                      $query1 = "SELECT * FROM  $forward where nota like '%$search%' or nama like '%$search%' order by no limit $rpp";
                      $hasil = mysqli_query($conn, $query1);
                      $no = 1;
                      while ($fill = mysqli_fetch_assoc($hasil)) {
                ?>
                        <tbody>
                          <tr>
                            <td><?php echo ++$no_urut; ?></td>
                            <td><?php echo mysqli_real_escape_string($conn, $fill['nota']); ?></td>
                            <td><?php $supl = $fill['kreditur'];
                                $sql = "SELECT nama FROM supplier where kode='$supl'";
                                $r = mysqli_fetch_assoc(mysqli_query($conn, $sql));
                                echo $r['nama']; ?>


                            </td>
                            <td><?php echo mysqli_real_escape_string($conn, $fill['tgl']); ?></td>
                            <td><?php echo mysqli_real_escape_string($conn, $fill['due']); ?></td>
                            <td><?php echo mysqli_real_escape_string($conn, $fill['hutang']); ?></td>
                            <td><?php echo mysqli_real_escape_string($conn, $fill['status']); ?></td>


                            <td>
                              <?php if (($chmod >= 2 || $_SESSION['jabatan'] == 'admin') && ($fill['status'] == 'hutang')) { ?>
                                <button type="button" class="btn btn-success btn-xs" onclick="window.location.href='bayar_hutang_beli?nota=<?php echo $fill['nota']; ?>'">Bayar</button>
                              <?php } else {
                              } ?>


                            </td>
                          </tr><?php;
                              }

                                ?>
                        </tbody>
              </table>
              </div>
              <div align="right"><?php if ($tcount >= $rpp) {
                                    echo paginate_one($reload, $page, $tpages);
                                  } else {
                                  } ?></div>
            <?php
                    }
                  }
                } else {
                  while (($count < $rpp) && ($i < $tcount)) {
                    mysqli_data_seek($result, $i);
                    $fill = mysqli_fetch_array($result);
            ?>
            <tbody>
              <tr>
                <td><?php echo ++$no_urut; ?></td>
                <td><?php echo mysqli_real_escape_string($conn, $fill['nota']); ?></td>
                <td><?php $supl = $fill['kreditur'];
                    $sql = "SELECT nama FROM supplier where kode='$supl'";
                    $r = mysqli_fetch_assoc(mysqli_query($conn, $sql));
                    echo $r['nama']; ?>
                </td>
                <td><?php echo mysqli_real_escape_string($conn, $fill['tgl']); ?></td>
                <td><?php echo mysqli_real_escape_string($conn, $fill['due']); ?></td>
                <td><?php echo mysqli_real_escape_string($conn, $fill['hutang']); ?></td>
                <td><?php echo mysqli_real_escape_string($conn, $fill['status']); ?></td>

                <td>
                  <?php if (($chmod >= 2 || $_SESSION['jabatan'] == 'admin') && ($fill['status'] == 'hutang')) { ?>
                    <button type="button" class="btn btn-success btn-xs" onclick="window.location.href='bayar_hutang_beli?nota=<?php echo $fill['nota']; ?>'">Bayar</button>
                  <?php } else {
                    } ?>


                </td>
              </tr>
            <?php
                    $i++;
                    $count++;
                  }

            ?>
            </tbody>
            </table>
            <div align="right"><?php if ($tcount >= $rpp) {
                                  echo paginate_one($reload, $page, $tpages);
                                } else {
                                } ?></div>
          <?php } ?>
          <div class="col-xs-1" align="right">

          </div>
            </div>
            <!-- /.box-body -->
          </div>

        <?php } else {
        } ?>
      </div>
      <!-- ./col -->
    </div>

  </div>
  <!-- /.content-wrapper -->
</div>
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
<script src="dist/js/pages/dashboard.js"></script>
<script src="dist/js/demo.js"></script>
<script src="dist/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="dist/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="dist/plugins/fastclick/fastclick.js"></script>
<?php footer(); ?>
