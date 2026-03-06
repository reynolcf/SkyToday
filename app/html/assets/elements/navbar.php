 <!-- html/inc/nav.php -->
<link rel="stylesheet" href="/assets/css/navbar.css"/>
 <nav class="navbar navbar-expand-lg fixed-top sky-navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="/index.php">
            <img src="assets/images/logoCircle.png" alt="SkyToday Logo" width="50" height="50">
        </a>
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/dashboard.php">Weather</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/history.php">History</a>
            </li>
            
            </ul>
        </div>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item dropdown">
                <a
                class="nav-link dropdown-toggle"
                href="#"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                >
                Developers
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="/reynolds.php">Cameron Reynolds</a></li>
                <li><a class="dropdown-item" href="/artnak.php">Jacob Artnak</a></li>
                </ul>
            </li>
            </ul>
        </div>
    </div>
</nav>