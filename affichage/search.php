<?php
    session_start();
    if(!isset($_SESSION["user_id"]))header('Location: login.html');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Database | SPSE</title>

    <!-- Custom fonts for this template -->
    <link
      href="vendor/fontawesome-free/css/all.min.css"
      rel="stylesheet"
      type="text/css"
    />
    <link
      href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
      rel="stylesheet"
    />

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet" />

    <!-- Custom styles for this page -->
    <link
      href="vendor/datatables/dataTables.bootstrap4.min.css"
      rel="stylesheet"
    />
  </head>

  <body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
          <!-- Topbar -->
          <nav
            class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow"
          >
            <!-- Title -->
            <h4>Systeme de Planification, Suivie et Evaluation</h4>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
              <!-- Nav Item - User Information -->
              <li class="nav-item">
                <a
                  class="nav-link"
                  href="out.php"
                  id="userDropdown"
                  role="button"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  <i
                    class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"
                  ></i>
                  <span class="mr-2 d-none d-lg-inline text-gray-600 small"
                    >Déconnexion</span
                  >
                </a>
              </li>
            </ul>
          </nav>
          <!-- End of Topbar -->

          <?php 
            // require 'dbaccess.php'; 
            // $thematiques = get("Select * from thematique order by label");
            // $th = findReponsesByThematique($_GET["thematique"]);
            // $qs = getAllQuestionByThematique($_GET["thematique"]);

            require 'dbaccess.php'; 
            $thematiques = get("Select * from thematique order by label");
            // $th = findReponsesByThematique($_GET["thematique"]);
            $qs = getAllQuestionByThematique($_GET["thematique"]);
          	$ss = getTableByThematique($_GET["thematique"], $_GET["start"], $_GET["end"]);

            $ind = findIndicateurByThematique($_GET["thematique"], $_GET["start"], $_GET["end"]);

            // var_dump($ind);
          ?>

          <!-- Begin Page Content -->
          <div class="container-fluid">
          <form action="search.php" method="get">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-gr">
                    <label for="thematique">Thématique</label>
                    <select
                      class="form-control"
                      name="thematique"
                      id="thematique"
                    >
                    <?php foreach ($thematiques as $th) { ?>
                      <option value="<?php echo $th["id"] ?>"><?php echo $th["label"] ?></option>
                    <?php } ?>
                    </select>
                  </div>
                </div>
                <!-- <div class="col-md-2">
                  <div class="form-gr">
                    <label for="level">Niveau</label>
                    <select class="form-control" name="level" id="level">
                      <option value="District">District</option>
                      <option value="Regional">Regional</option>
                      <option value="National">National</option>
                    </select>
                  </div>
                </div> -->
                <div class="col-md-3">
                  <div class="form-gr">
                    <label for="start">Date de début</label>
                    <input
                      class="form-control"
                      type="date"
                      name="start"
                      id="start"
                    />
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-gr">
                    <label for="end">Date de fin</label>
                    <input class="form-control" type="date" name="end" id="end" />
                  </div>
                </div>
                <div class="col-md-3">
                  <br>
                  <button type="submit" style="margin-top: .5em;" class="btn btn-primary">Filtrer</button>
                </div>
              </div>
            </form>
            <br>

            <style>
              .table.dataTable th {
                white-space: nowrap;
              }
            </style>

            <!-- Indicateur -->
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                  Indicateurs
                </h6>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table
                    class="table table-bordered"
                    id=""
                    width="100%"
                    cellspacing="0"
                  >
                  <thead>
                      <tr>
                          <th>Désignation</th>  
                          <th>Valeur</th>  
                      </tr>
                    </thead>
                    
                    <tbody>
                      <?php foreach ($ind as $len) { ?>
                          <tr>
                              <td><?php echo $len["label"] ?></td>
                              <td><?php echo $len["value"] ?></td>
                          </tr>  
                        <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Data -->
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                  Base de données
                </h6>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table
                    class="table table-bordered"
                    id="dataTable"
                    width="100%"
                    cellspacing="0"
                  >
                  <thead>
                      <tr>
                        <?php foreach ($qs as $key) { ?>
                          <th><?php echo $key["question"] ?></th>  
                        <?php } ?>
                      </tr>
                    </thead>
                    
                    <tbody>
                      <?php foreach ($ss as $len) { ?>
                          <tr>
                            <?php foreach ($len as $ll) { ?>
                              <td>
                                <?php echo $ll ?>
                              </td>
                            <?php } ?>
                          </tr>  
                        <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright &copy; SPSE 2022</span>
            </div>
          </div>
        </footer>
        <!-- End of Footer -->
      </div>
      <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div
      class="modal fade"
      id="logoutModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button
              class="close"
              type="button"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            Select "Logout" below if you are ready to end your current session.
          </div>
          <div class="modal-footer">
            <button
              class="btn btn-secondary"
              type="button"
              data-dismiss="modal"
            >
              Cancel
            </button>
            <a class="btn btn-primary" href="login.html">Logout</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
  </body>
</html>
