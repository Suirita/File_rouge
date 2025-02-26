<!DOCTYPE html>
<html lang="en">

<?php
// Include the head section (meta tags, title, CSS links)
include_once '../layouts/head.php';
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div class="wrapper">
    <?php
    // Include the navigation bar and sidebar for site navigation
    include_once '../layouts/nav.php';
    include_once '../layouts/aside.php';
    ?>

    <main class="py-4">
      <div class="content-wrapper p-3">
        <!-- Card container for the main content -->
        <div class="card card-info">

          <!-- Card Header: Displays the section title -->
          <div class="card-header border-transparent">
            <h3 class="h3">Branches</h3>
          </div>

          <!-- Row containing the Add button -->
          <div class="row m-2">
            <div class="col-12">
              <!-- Button to navigate to the create page -->
              <a href="./create.php" class="btn btn-md btn-info float-right">Ajouter Branche</a>
            </div>
          </div>

          <!-- Card Body: Table displaying branches and associated data -->
          <div class="card-body p-0">
            <!-- Responsive striped table -->
            <div class="table table-striped table-responsive">
              <table class="table m-0">
                <thead>
                  <tr>
                    <th>Branche</th> <!-- Column header for branches -->
                    <th>Questions</th> <!-- Column header for the number of questions -->
                    <th>Actions</th> <!-- Column header for available actions -->
                  </tr>
                </thead>
                <tbody>
                  <!-- Row for the "Français" branch -->
                  <tr>
                    <td>Français</td> <!-- Branch name -->
                    <td>2</td> <!-- Number of questions -->
                    <td>
                      <!-- Action buttons (View, Edit, Delete) -->
                      <div class="btn-group btn-group-sm">
                        <a href="#" class="btn btn-primary"><i class="fas fa-eye"></i></a> <!-- View button -->
                        <a href="./edit.php" class="btn btn-info"><i class="fas fa-edit"></i></a> <!-- Edit button -->
                        <a href="#" class="btn btn-danger"><i class="fas fa-trash"></i></a> <!-- Delete button -->
                      </div>
                    </td>
                  </tr>

                  <!-- Additional rows for other branches -->
                  <tr>
                    <td>Anglais</td>
                    <td>1</td>
                    <td>
                      <div class="btn-group btn-group-sm">
                        <a href="#" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                        <a href="./edit.php" class="btn btn-info"><i class="fas fa-edit"></i></a>
                        <a href="#" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Programmation</td>
                    <td>3</td>
                    <td>
                      <div class="btn-group btn-group-sm">
                        <a href="#" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                        <a href="./edit.php" class="btn btn-info"><i class="fas fa-edit"></i></a>
                        <a href="#" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Soft Skills</td>
                    <td>2</td>
                    <td>
                      <div class="btn-group btn-group-sm">
                        <a href="#" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                        <a href="./edit.php" class="btn btn-info"><i class="fas fa-edit"></i></a>
                        <a href="#" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Travail en Équipe</td>
                    <td>1</td>
                    <td>
                      <div class="btn-group btn-group-sm">
                        <a href="#" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                        <a href="./edit.php" class="btn btn-info"><i class="fas fa-edit"></i></a>
                        <a href="#" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Card Footer: Pagination for navigation -->
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
      </div>
    </main>

    <?php
    // Include the footer
    include_once '../layouts/footer.php';
    ?>
  </div>

  <?php
  // Include JavaScript files and dependencies for functionality
  include_once '../layouts/script-link.php';
  ?>
</body>

</html>
