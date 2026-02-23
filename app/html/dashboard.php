<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>SkyToday - Dashboard</title>
    <?php include 'assets/elements/head.php'; ?>
    <link rel="stylesheet" href="assets/css/dashboard.css" />
  </head>

  <body>
    <?php include 'assets/elements/navbar.php'; ?>

    <div class="main-container container-fluid">
      <div class="row my-5">
        <div
          class="col col-12 col-md-9 col-lg-6 search-div d-flex justify-content-left align-items-center text-center my-5"
        >
          <div class="input-group search-container me-3">
            <input
              type="text"
              id="inputLocation"
              class="form-control"
              placeholder="Enter ZIP code or place"
            />
            <button id="btnGetWeather" class="btn btn-primary">
              Get Weather
            </button>
          </div>

          <div
            id="loadingSpinner"
            class="spinner-border spinner-blue d-none"
            role="status"
          >
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </div>

      <div id="result"></div>

      <div class="location-div my-1" id="location-container">
        <!-- renderLocation() will builds stuff in here -->
      </div>

      <div class="api-container container-fluid gx-0">
        <div class="row" id="api-cards-row">
          <!-- cards will appear in here (built by js) -->
        </div>
      </div>
    </div>

    <?php include 'assets/elements/footer.php'; ?>

    <?php include 'assets/elements/scripts.php'; ?>
    <script type="module" src="assets/js/weather.js"></script>
    <script>
      const tooltipTriggerList = document.querySelectorAll(
        '[data-bs-toggle="tooltip"]'
      );
      const tooltipList = [...tooltipTriggerList].map(
        (el) => new bootstrap.Tooltip(el)
      );
    </script>
  </body>
</html>
