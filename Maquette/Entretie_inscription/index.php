<!DOCTYPE html>
<html lang="en">

<?php
// Include the head section, which contains meta tags, title, and CSS links
include_once '../layouts/head.php';
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div class="wrapper">
    <?php
    // Include navigation bar and sidebar for the layout
    include_once '../layouts/nav.php';
    include_once '../layouts/aside.php';
    ?>

    <main class="py-4">
      <div class="content-wrapper p-3">
        <div class="card card-info">

          <!-- Card header with title -->
          <div class="card-header border-transparent">
            <h3 class="h3">Entretien d'inscription</h3>
          </div>

          <div class="card-body">
            <div class="card-header p-0 px-3 pb-3">
              <div class="row">
                <div class="col-12">

                  <!-- Button to navigate to the create page -->
                  <a href="./create.php" class="btn btn-md btn-info float-right">Ajouter Entretien</a>

                  <!-- Search bar -->
                  <div class="card-tools">
                    <div class="input-group input-group" style="width: 300px;">
                      <input type="text" name="table_search" class="form-control float-right" placeholder="Nom de l'inscrit">

                      <div class="input-group-append">
                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>

            <!-- Table with  -->
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>Les inscrits</th>
                  <th>Status</th>
                  <th>Date de l'entretien</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Ahmed El Mansouri</td>
                  <td><small class="badge badge-success">Acceptés</small></td>
                  <td>15-8-2024</td>
                  <td>
                    <!-- Action buttons (View, Edit, Delete) -->
                    <div class="btn-group btn-group-sm">
                      <a href="#" class="btn btn-primary"><i class="fas fa-eye"></i></a> <!-- View button -->
                      <a href="./edit.php" class="btn btn-info"><i class="fas fa-edit"></i></a> <!-- Edit button -->
                      <a href="#" class="btn btn-danger"><i class="fas fa-trash"></i></a> <!-- Delete button -->
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>Fatima Benali</td>
                  <td><small class="badge badge-warning">En Attente</small></td>
                  <td>15-8-2024</td>
                  <td>
                    <!-- Action buttons (View, Edit, Delete) -->
                    <div class="btn-group btn-group-sm">
                      <a href="#" class="btn btn-primary"><i class="fas fa-eye"></i></a> <!-- View button -->
                      <a href="./edit.php" class="btn btn-info"><i class="fas fa-edit"></i></a> <!-- Edit button -->
                      <a href="#" class="btn btn-danger"><i class="fas fa-trash"></i></a> <!-- Delete button -->
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>Mohamed Alaoui</td>
                  <td><small class="badge badge-danger">Refusés</small></td>
                  <td>15-8-2024</td>
                  <td>
                    <!-- Action buttons (View, Edit, Delete) -->
                    <div class="btn-group btn-group-sm">
                      <a href="#" class="btn btn-primary"><i class="fas fa-eye"></i></a> <!-- View button -->
                      <a href="./edit.php" class="btn btn-info"><i class="fas fa-edit"></i></a> <!-- Edit button -->
                      <a href="#" class="btn btn-danger"><i class="fas fa-trash"></i></a> <!-- Delete button -->
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>Khadija El Amrani</td>
                  <td><small class="badge badge-warning">En Attente</small></td>
                  <td>16-8-2024</td>
                  <td>
                    <!-- Action buttons (View, Edit, Delete) -->
                    <div class="btn-group btn-group-sm">
                      <a href="#" class="btn btn-primary"><i class="fas fa-eye"></i></a> <!-- View button -->
                      <a href="./edit.php" class="btn btn-info"><i class="fas fa-edit"></i></a> <!-- Edit button -->
                      <a href="#" class="btn btn-danger"><i class="fas fa-trash"></i></a> <!-- Delete button -->
                    </div>
                  </td>
                </tr>

                <tr>
                  <td>Youssef Belkacem</td>
                  <td><small class="badge badge-danger">Refusés</small></td>
                  <td>16-8-2024</td>
                  <td>
                    <!-- Action buttons (View, Edit, Delete) -->
                    <div class="btn-group btn-group-sm">
                      <a href="#" class="btn btn-primary"><i class="fas fa-eye"></i></a> <!-- View button -->
                      <a href="./edit.php" class="btn btn-info"><i class="fas fa-edit"></i></a> <!-- Edit button -->
                      <a href="#" class="btn btn-danger"><i class="fas fa-trash"></i></a> <!-- Delete button -->
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="card-footer d-flex justify-content-center bg-white">
              <ul class="pagination m-0">
                <!-- Pagination controls -->
                <li class="page-item"><a class="page-link" href="#">«</a></li> <!-- Previous page -->
                <li class="page-item"><a class="page-link bg-info" href="#">1</a></li> <!-- Page 1 -->
                <li class="page-item"><a class="page-link" href="#">2</a></li> <!-- Page 2 -->
                <li class="page-item"><a class="page-link" href="#">3</a></li> <!-- Page 3 -->
                <li class="page-item"><a class="page-link" href="#">»</a></li> <!-- Next page -->
              </ul>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
    </main>

    <?php
    // Include the footer section
    include_once '../layouts/footer.php';
    ?>
  </div>

  <?php
  // Include JavaScript files and dependencies
  include_once '../layouts/script-link.php';
  ?>

</body>

</html>
