<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>SkyToday</title>
    <?php include 'assets/elements/head.php'; ?>
    <link rel="stylesheet" href="/assets/css/artnak.css" />
  </head>

  <body>
    <?php include 'assets/elements/navbar.php'; ?>

    <!-- top section: profile,name, and description -->

    <div class="first-section-background">
      <div class="first-container container-fluid">
        <div class="first-section row">
          <div class="col-12 col-xl-3 d-flex align-items-center">
            <div class="profile" style="height: 100%">
              <img
                src="/assets/images/artnak.png"
                class=""
                style="max-width: 100%"
              />
            </div>
          </div>

          <div class="my-info col-12 col-xl-9">
            <p class="fs-5 fw-light mb-2">Hello, my name is</p>
            <h1 class="fw-bold mb-4 fs-1">Jacob Artnak</h1>
            <p class="fs-6">
              Hi, I'm Jacob, a computer science major with a passion for
              creating products that make a real impact. Coding gives me the
              ability to turn ideas into tangible experiences, something that
              has motivated me since I first got into programming through game
              development. Today, game development remains both a creative
              outlet and a side project I pursue for fun and income. Outside of
              tech, I enjoy working out and spending time in nature,
              backpacking, climbing, camping, and listening to audiobooks. I
              hope you enjoyed exploring the weather app we created for our
              CSE383 final project!
            </p>

            <div class="mt-3 row gap-2 ms-1">
              <div
                class="col-auto about-tibbet"
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                title="My major"
              >
                <img src="/assets/images/icons/degree.png" width="18" />
                <span>Computer Science</span>
              </div>
              <div
                class="col-auto about-tibbet"
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                title="University"
              >
                <img src="/assets/images/icons/graduation-cap.png" width="18" />
                <span>Miami University, Oxford OH</span>
              </div>
              <div
                class="col-auto about-tibbet"
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                title="Expected Graduation Date"
              >
                <img src="/assets/images/icons/clock-white.png" width="18" />
                <span>December, 2028</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="main-container container-fluid">
      <div
        class="second-section d-flex flex-column align-items-center mt-5 mb-5"
      >
        <p class="fs-3 fw-semibold">Skills Overview</p>
        <p class="text-center">
          A list of skills that I've gained over the years through personal
          projects and university assignments.
        </p>
      </div>

      <div
        class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6 g-3"
      >
        <div class="col">
          <div class="skills-card card card-body shadow text-center">
            <div class="skill-icon mb-2">
              <img src="/assets/images/logos/html-logo.png" class="img-fluid" />
            </div>
            <p class="skill-label">HTML</p>
          </div>
        </div>
        <div class="col">
          <div class="skills-card card card-body shadow text-center">
            <div class="skill-icon mb-2">
              <img src="/assets/images/logos/css-logo.png" class="img-fluid" />
            </div>
            <p class="skill-label">CSS</p>
          </div>
        </div>
        <div class="col">
          <div class="skills-card card card-body shadow text-center">
            <div class="skill-icon mb-2">
              <img src="/assets/images/logos/js-logo.png" class="img-fluid" />
            </div>
            <p class="skill-label">JavaScript</p>
          </div>
        </div>
        <div class="col">
          <div class="skills-card card card-body shadow text-center">
            <div class="skill-icon mb-2">
              <img
                src="/assets/images/logos/react-logo.png"
                class="img-fluid"
              />
            </div>
            <p class="skill-label">React</p>
          </div>
        </div>
        <div class="col">
          <div class="skills-card card card-body shadow text-center">
            <div class="skill-icon mb-2">
              <img
                src="/assets/images/logos/docker-logo.svg"
                class="img-fluid"
              />
            </div>
            <p class="skill-label">Docker</p>
          </div>
        </div>
        <div class="col">
          <div class="skills-card card card-body shadow text-center">
            <div class="skill-icon mb-2">
              <img
                src="/assets/images/logos/github-logo.webp"
                class="img-fluid"
              />
            </div>
            <p class="skill-label">Github</p>
          </div>
        </div>
        <div class="col">
          <div class="skills-card card card-body shadow text-center">
            <div class="skill-icon mb-2">
              <img src="/assets/images/logos/lua-logo.png" class="img-fluid" />
            </div>
            <p class="skill-label">Lua</p>
          </div>
        </div>
        <div class="col">
          <div class="skills-card card card-body shadow text-center">
            <div class="skill-icon mb-2">
              <img src="/assets/images/logos/java-logo.png" class="img-fluid" />
            </div>
            <p class="skill-label">Java</p>
          </div>
        </div>
        <div class="col">
          <div class="skills-card card card-body shadow text-center">
            <div class="skill-icon mb-2">
              <img src="/assets/images/logos/c++-logo.png" class="img-fluid" />
            </div>
            <p class="skill-label">C++</p>
          </div>
        </div>
        <div class="col">
          <div class="skills-card card card-body shadow text-center">
            <div class="skill-icon mb-2">
              <img
                src="/assets/images/logos/python-logo.png"
                class="img-fluid"
              />
            </div>
            <p class="skill-label">Python</p>
          </div>
        </div>
        <div class="col">
          <div class="skills-card card card-body shadow text-center">
            <div class="skill-icon mb-2">
              <img
                src="/assets/images/logos/node.js-logo.png"
                class="img-fluid"
              />
            </div>
            <p class="skill-label">Node.js</p>
          </div>
        </div>
        <div class="col">
          <div class="skills-card card card-body shadow text-center">
            <div class="skill-icon mb-2">
              <img
                src="/assets/images/logos/mongodb-logo.png"
                class="img-fluid"
              />
            </div>
            <p class="skill-label">MongoDB</p>
          </div>
        </div>
      </div>

      <hr class="mt-5" />
      <div
        class="second-section d-flex flex-column align-items-center mt-5 mb-3"
      >
        <p class="fs-3 fw-semibold mt-3 mb-5">Contributions</p>
      </div>

      <div class="contribution-container row gy-5 w-lg-50 w-100">
        <div class="col-12 col-md-6">
          <div
            class="contribution-card card card-body shadow d-flex flex-column align-items-center text-center h-100"
          >
            <div class="cont-img">
              <img src="/assets/images/icons/back-end.png" />
            </div>

            <p class="mt-4 fw-semibold fs-5">Back End</p>
            <hr />
            <p class="mt-4 fw-normal fs-6">
              For the back end, I developed the PHP infrastructure that
              coordinated requests between the client and our two selected
              weather APIs. We chose WeatherAPI and OpenMeteo (also used for
              geocoding) for their reliability, consistency, and ease of
              integration. My work focused on clean data handling, error
              management, and building endpoints that provided a seamless
              experience for the front end.
            </p>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div
            class="contribution-card card card-body shadow d-flex flex-column align-items-center text-center h-100"
          >
            <div class="cont-img">
              <img src="/assets/images/icons/front-end.png" />
            </div>

            <p class="mt-4 fw-semibold fs-5">Front End</p>
            <hr />
            <p class="mt-4 fw-normal fs-6">
              On the front end, I designed and built the weather dashboard and
              history page, implementing their functionality using JavaScript.
              The client-side logic communicates with our back-end services to
              provide a responsive and intuitive user experience. My goal was to
              create an interface that feels clean, efficient, and smooth to
              interact with, and link it with the PHP code.
            </p>
          </div>
        </div>
      </div>

      <hr class="mt-5" />
      <div
        class="second-section d-flex flex-column align-items-center mt-5 mb-3"
      >
        <p class="fs-3 fw-semibold mt-3 mb-5">Thoughts</p>
        <p class="mt-4 fw-normal fs-6">
          This website was created as our final project for CSE383, and it was a
          strong learning experience. I enjoyed the challenge of making the
          interface visually appealing and working without a framework—going
          back to pure JavaScript, HTML, and CSS helped reinforce my
          fundamentals. The project also strengthened my teamwork and
          communication skills. My teammate and I learned how to divide
          responsibilities, collaborate effectively, and navigate differences
          productively. Gaining more hands-on experience with GitHub was another
          major benefit of the project.
        </p>
      </div>
    </div>

    <?php include 'assets/elements/footer.php'; ?>
    <!-- includes jquery and bootstrap (make sure this comes before page specific scripts) -->
    <?php include 'assets/elements/scripts.php'; ?>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        var tooltipTriggerList = document.querySelectorAll(
          '[data-bs-toggle="tooltip"]'
        );

        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
          new bootstrap.Tooltip(tooltipTriggerEl);
        });
      });
    </script>
  </body>
</html>
