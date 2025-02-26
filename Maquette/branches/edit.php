<!DOCTYPE html>
<html lang="en">

<?php
// Include the head section (meta tags, title, CSS links)
include_once '../layouts/head.php';
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div class="wrapper">
    <?php
    // Include the navigation bar and sidebar
    include_once '../layouts/nav.php';
    include_once '../layouts/aside.php';
    ?>

    <main class="py-4">
      <div class="content-wrapper p-3">
        <!-- Card container for the main content -->
        <div class="card card-info">
          <!-- Card Header -->
          <div class="card-header border-transparent">
            <h3 class="h3">Modification d'une branche</h3>
          </div>

          <!-- Card Body -->
          <div class="card-body">
            <form action="dashboard.php" method="post">
              <div class="form-group">
                <label for="branchName">Nom de la branche</label>
                <input type="text" class="form-control" id="branchName" name="branchName" value="Français" placeholder="Branche">
              </div>

              <div class="form-group">
                <label for="" class="mt-2">Questions</label>
                <button type="button" class="btn btn-success float-right mb-2 mr-2" onclick="addQuestionField()">+</button>
                <div id="questionsContainer">
                  <!-- Initial Question Input -->
                  <div class="input-group mb-2">
                    <input type="text" name="questions[]" class="form-control" placeholder="Ajouter une question">
                  </div>
                  <div class="input-group mb-2">
                    <input type="text" name="questions[]" class="form-control" value="Presenter vous" placeholder="Ajouter une question">
                    <div class="input-group-append"><button type="button" class="btn btn-danger">-</button></div>
                  </div>
                  <div class="input-group mb-2">
                    <input type="text" name="questions[]" class="form-control" value="Général" placeholder="Ajouter une question">
                    <div class="input-group-append"><button type="button" class="btn btn-danger">-</button></div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-12">
                  <a href="./index.php" class="btn btn-secondary">Annuler</a>
                  <a href="./index.php" class="btn btn-md btn-info float-right">Modifier</a>
                </div>
              </div>
            </form>
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
  // Include JavaScript files and dependencies
  include_once '../layouts/script-link.php';
  ?>

  <script>
    function addQuestionField() {
      // Create a new input group for the question
      const container = document.getElementById('questionsContainer');
      const inputGroup = document.createElement('div');
      inputGroup.className = 'input-group mb-2';

      // Create the input field
      const inputField = document.createElement('input');
      inputField.type = 'text';
      inputField.name = 'questions[]';
      inputField.className = 'form-control';
      inputField.placeholder = 'Ajouter une question';

      // Create the button group
      const inputGroupAppend = document.createElement('div');
      inputGroupAppend.className = 'input-group-append';

      const removeButton = document.createElement('button');
      removeButton.type = 'button';
      removeButton.className = 'btn btn-danger';
      removeButton.textContent = '-';
      removeButton.onclick = function() {
        container.removeChild(inputGroup);
      };

      // Append the button to the group
      inputGroupAppend.appendChild(removeButton);

      // Add the input field and button group to the input group
      inputGroup.appendChild(inputField);
      inputGroup.appendChild(inputGroupAppend);

      // Append the input group to the container
      container.appendChild(inputGroup);
    }
  </script>
</body>

</html>
