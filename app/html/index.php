<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>SkyToday</title>
    <link rel="stylesheet" href="assets/css/index.css" />
    <?php include 'assets/elements/head.php'; ?>
  </head>

  <body>
    <?php include 'assets/elements/navbar.php'; ?>

    <div class="main-container container-fluid">
      <div class="gap3"></div>
      <div
        class="d-flex flex-column justify-content-center align-items-center mt-5"
      >
        <div class="main-logo slide-in-down">
          <img
            src="assets/images/logoText.png"
            alt="SkyToday Logo"
            width="400"
            height="120"
          />
        </div>
        <button
          class="btn sky-btn slide-in-up"
          type="button"
          onclick="location.href='/dashboard.php'"
        >
          Get Started
        </button>
      </div>

      <div class="gap2"></div>

      <div class="swing-bar-2 mt-5">
        <div class="row">
          <div class="col-1"></div>
          <div class="col-11">
            <h1 class="slide-in-left header-title">About SkyToday</h1>
          </div>
        </div>
      </div>

      <div
        class="d-flex flex-column justify-content-center align-items-start mt-5"
      >
        <div class="row">
          <div class="col-1"></div>
          <p class="app-description slide-in-up">
            SkyToday is a weather app that can provide the user with data from multiple 
            source API's for a trustworthy forecast. This app also has an Artificial Intelligence generated 
            summary of the data, for quick and easy understanding of the weather conditions. Visit the 
            weather page to try it out.
          </p>
        </div>
      </div>

      <div class="gap"></div>
      <div class="row g-0">
        <div class="col-3"></div>
        <div class="col-6">
          <hr />
        </div>
        <div class="col-3"></div>
      </div>
      <div class="gap"></div>


      <div class="swing-bar-2 mt-5">
        <div class="row">
          <div class="col-1"></div>
          <div class="col-11">
            <h1 class="slide-in-left header-title">Frameworks and Tools</h1>
          </div>
        </div>
      </div>

      <div class="gap"></div>


      <div
        class="d-flex flex-column justify-content-center align-items-start mt-5"
      >
        <div class="row">
          <div class="col-1"></div>
          <p class="app-description-3 slide-in-up">
            Our app uses an SQLite database and stores the JSON responses of the 
            API's from each user's searches. This allows the users to use the history page
            to view their past searches by querying this database and displaying 
            the response on the weather page. jQuery is used to streamline getting data from 
            the API's with it's library, making it easy to send GET requests and receive the responses. 
            AJAX is then employed to make sure all of this happens asynchronously in the background, and 
            when the response is received, it updates the page without reloading it, updating 
            only the parts needed.
          </p>
        </div>
      </div>

      <div class="gap2"></div>


      <div class="row row-cols-4 row-cols-md-5 row-cols-lg-6 g-3 d-flex justify-content-center">
        <div class="col mx-3">
          <div class="skills-card card card-body shadow text-center">
            <div class="skill-icon mb-2">
              <img src="/assets/images/logos/AJAX.png" class="img-fluid" />
            </div>
            <p class="skill-label">AJAX</p>
          </div>
        </div>
        <div class="col mx-3">
          <div class="skills-card card card-body shadow text-center">
            <div class="skill-icon mb-2">
              <img src="/assets/images/logos/jQuery.png" class="img-fluid" />
            </div>
            <p class="skill-label">jQuery</p>
          </div>
        </div>
        <div class="col mx-3">
          <div class="skills-card card card-body shadow text-center">
            <div class="skill-icon mb-2">
              <img src="/assets/images/logos/sqlite.png" class="img-fluid" />
            </div>
            <p class="skill-label">SQLite</p>
          </div>
        </div>
      </div>


      <div class="gap"></div>

      <div class="row g-0">
        <div class="col-3"></div>
        <div class="col-6">
          <hr />
        </div>
        <div class="col-3"></div>
      </div>

      <div class="gap2"></div>

      <div class="swing-bar mt-5">
        <div class="row">
          <div class="col-1"></div>
          <div class="col-4 slide-in-left">
            <img
              src="assets/images/openMeteo.png"
              alt="Weather App Demo"
              height="20%"
            />
          </div>
          <div class="col-7"></div>
        </div>
      </div>

      <div
        class="d-flex flex-column justify-content-center align-items-start mt-5"
      >
        <div class="row">
          <div class="col-1"></div>
          <p class="app-description slide-in-up">
            Open Meteo is one of the weather data sources used in SkyToday. Open Meteo 
            gathers its information from multiple global weather prediction models, 
            like the ECMWF and NOAA. They simulate weather behavior and use 
            them to generate predictions of weather, and generates it into a forecast.
          </p>
        </div>
      </div>

      <div class="gap2"></div>

      <div class="swing-bar">
        <div class="row">
          <div class="col-1"></div>
          <div class="col-4 slide-in-left">
            <img
              src="assets/images/weatherAPI.png"
              alt="Weather App Demo"
              height="20%"
            />
          </div>
          <div class="col-7"></div>
        </div>
      </div>

      <div
        class="d-flex flex-column justify-content-center align-items-start mt-5"
      >
        <div class="row">
          <div class="col-1"></div>
          <p class="app-description slide-in-up">
            WeatherAPI is another of the weather data sources used in SkyToday. 
            WeatherAPI gets its data from official meteorological stations, 
            government weather services, radar systems, and satalite feeds. It uses 
            this data to create forecast models for locations across the world.
          </p>
        </div>
      </div>

      <div class="gap2"></div>
    </div>
    <?php include 'assets/elements/footer.php'; ?>
    
    <?php include 'assets/elements/scripts.php'; ?>
    <script src="assets/js/animation.js"></script>
  </body>
</html>
