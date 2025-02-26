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
        <!-- Card container for interview form -->
        <div class="card card-info">
          <!-- Card header with title -->
          <div class="card-header border-transparent">
            <h3 class="h3">Entretien d'inscription</h3>
          </div>

          <div class="card-body">
            <!-- Interview form -->
            <form>
              <!-- Select2 -->
              <div class="form-group">
                <label for="minimal-select">Nom de l'inscrit</label>
                <select id="minimal-select" class="form-control select2" style="width: 100%;">
                  <option selected="selected" disabled>Nom</option>
                  <option>Ahmed El Mansouri</option>
                  <option>Fatima Benali</option>
                  <option>Mohamed Alaoui</option>
                  <option>Khadija El Amrani</option>
                  <option>Youssef Belkacem</option>
                  <option>Suirita Fahd</option>
                </select>
              </div>

              <!-- Accordion section for different interview categories -->
              <div id="accordion">

                <!-- Category 1: Francais -->
                <div class="card">
                  <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                      <!-- Accordion toggle button -->
                      <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Francais
                      </button>
                    </h5>
                  </div>

                  <!-- Accordion content -->
                  <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                      <!-- Interview question: Present yourself -->
                      <div class="form-group">
                        <label for="question1">Présentez-vous</label>
                        <div class="grade-container d-flex justify-content-center">
                          <!-- Grade options -->
                          <div class="grade-box grade-1 mx-1" data-grade="1">1</div>
                          <div class="grade-box grade-2 mx-1" data-grade="2">2</div>
                          <div class="grade-box grade-3 mx-1" data-grade="3">3</div>
                        </div>
                      </div>
                      <!-- Interview question: General -->
                      <div class="form-group">
                        <label for="question2">Général</label>
                        <div class="grade-container d-flex justify-content-center">
                          <div class="grade-box grade-1 mx-1" data-grade="1">1</div>
                          <div class="grade-box grade-2 mx-1" data-grade="2">2</div>
                          <div class="grade-box grade-3 mx-1" data-grade="3">3</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Category 2: Anglais -->
                <div class="card">
                  <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                      <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Anglais
                      </button>
                    </h5>
                  </div>

                  <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">
                      <!-- Interview question: Introduce yourself -->
                      <div class="form-group">
                        <label for="question3">Introduce yourself</label>
                        <div class="grade-container d-flex justify-content-center">
                          <div class="grade-box grade-1 mx-1" data-grade="1">1</div>
                          <div class="grade-box grade-2 mx-1" data-grade="2">2</div>
                          <div class="grade-box grade-3 mx-1" data-grade="3">3</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Category 3: Programmation -->
                <div class="card">
                  <div class="card-header" id="headingThree">
                    <h5 class="mb-0">
                      <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Programmation
                      </button>
                    </h5>
                  </div>

                  <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                    <div class="card-body">
                      <!-- Interview question: Technical -->
                      <div class="form-group">
                        <label for="question4">Question</label>
                        <div class="grade-container d-flex justify-content-center">
                          <div class="grade-box grade-1 mx-1" data-grade="1">1</div>
                          <div class="grade-box grade-2 mx-1" data-grade="2">2</div>
                          <div class="grade-box grade-3 mx-1" data-grade="3">3</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Category 4: Soft Skills -->
                <div class="card">
                  <div class="card-header" id="headingFour">
                    <h5 class="mb-0">
                      <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        Soft Skills
                      </button>
                    </h5>
                  </div>

                  <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                    <div class="card-body">
                      <!-- Interview question: Soft Skills -->
                      <div class="form-group">
                        <label for="question5">Question</label>
                        <div class="grade-container d-flex justify-content-center">
                          <div class="grade-box grade-1 mx-1" data-grade="1">1</div>
                          <div class="grade-box grade-2 mx-1" data-grade="2">2</div>
                          <div class="grade-box grade-3 mx-1" data-grade="3">3</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Category 5: Travail en Équipe -->
                <div class="card">
                  <div class="card-header" id="headingFive">
                    <h5 class="mb-0">
                      <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        Travail en Équipe
                      </button>
                    </h5>
                  </div>

                  <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                    <div class="card-body">
                      <!-- Interview question: Teamwork -->
                      <div class="form-group">
                        <label for="question6">Question</label>
                        <div class="grade-container d-flex justify-content-center">
                          <div class="grade-box grade-1 mx-1" data-grade="1">1</div>
                          <div class="grade-box grade-2 mx-1" data-grade="2">2</div>
                          <div class="grade-box grade-3 mx-1" data-grade="3">3</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
              <!-- End of Accordion Section -->

              <!-- Form action buttons -->
              <div class="row">
                <div class="col-12">
                  <a href="../dashboard/dashboard.php" class="btn btn-secondary">Annuler</a>
                  <a href="./index.php" class="btn btn-md btn-info float-right">Valider</a>
                </div>
              </div>
            </form>
          </div>
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

  <!-- Styles for grade box UI -->
  <style>
    .grade-box {
      width: 25px;
      height: 25px;
      line-height: 25px;
      text-align: center;
      color: white;
      font-weight: bold;
      border-radius: 5px;
      cursor: pointer;
      font-size: 0.8rem;
    }

    .grade-1 {
      background-color: #f56954;
    }

    .grade-2 {
      background-color: #f39c12;
    }

    .grade-3 {
      background-color: #00a65a;
    }

    .selected {
      border: 1px solid black;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }
  </style>

  <!-- Script for handling grade box selection -->
  <script>
    $(document).ready(function() {
      if ($.fn.select2) {
        $('.select2').select2();
      } else {
        console.error("Select2 library not loaded.");
      }
    });

    document.addEventListener('DOMContentLoaded', () => {
      const gradeContainers = document.querySelectorAll('.grade-container');

      gradeContainers.forEach(container => {
        container.addEventListener('click', (e) => {
          if (e.target.classList.contains('grade-box')) {
            const isSelected = e.target.classList.contains('selected');
            container.querySelectorAll('.grade-box').forEach(box => box.classList.remove('selected'));
            if (!isSelected) e.target.classList.add('selected');
          }
        });
      });
    });
  </script>
</body>

</html>
