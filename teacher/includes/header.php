<?php 
session_start(); // Start the session

// Check if teacher is logged in
if (!isset($_SESSION['teacher'])) {
  // Redirect to login page if not logged in
  header('Location: ../index.php');
  exit();
}

// Retrieve user data from session
$Id = $_SESSION['teacher']['Id'];
$teacherId = $_SESSION['teacher']['teacher_id'];
$email = $_SESSION['teacher']['email'];
$status = $_SESSION['teacher']['status'];
$phone = $_SESSION['teacher']['phone'];
$name =$_SESSION['teacher']['name'];
$address =$_SESSION['teacher']['address'];
$desgination =$_SESSION['teacher']['desgination'];
$dob =$_SESSION['teacher']['dob'];
$department=$_SESSION['teacher']['department'];

?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="">
  
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Liberal College Admin Section">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="../../assets/img/logo.webp" type="image/x-icon">
    
    <!-- Map CSS -->
    
    <!-- Libs CSS -->
    <link rel="stylesheet" href="../../assets/css/libs.bundle.css">
    
    <!-- Theme CSS -->
    <link rel="stylesheet" href="../../assets/css/theme.bundle.css">
    


    <style>body { display: none; }</style>
    
    <!-- Title -->
    <title>Liberal College</title>
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-156446909-1"></script><script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag("js", new Date());gtag("config", "UA-156446909-1");</script>
  <style>
      .text-red {
        color: #b30000;
    }
    .hidden-column {
    display: none;
}
.p{
  font-size:20px;
}
.sticky-footer {
    padding: 10px 0;
    text-align: center;
    width: 100%;
}
.wrapper {
    display: flex;
    flex-direction: column;
    min-height: 100vh; /* Full viewport height */
}

  </style>
  
  </head>
  <body>

    
    
      <!-- Offcanvas: Demo -->
      <form class="offcanvas offcanvas-end" id="offcanvasDemo" tabindex="-1">
        <div class="offcanvas-body">
      
          <!-- Close -->
          <a class="btn-close" href="#" data-bs-dismiss="offcanvas" aria-label="Close"></a>
      
          
      
          <!-- Heading -->
          <h2 class="text-center mb-2">
          Adjust to your comfort.
          </h2>
      
        
      
          <!-- Divider -->
          <hr class="mb-4">
      
          <!-- Heading -->
          <h4 class="mb-1">
            Color Scheme
          </h4>
      
          <!-- Text -->
          <p class="small text-body-secondary mb-3">
            Overall light or dark presentation.
          </p>
      
          <!-- Button group -->
          <div class="btn-group-toggle row gx-2 mb-4">
            <div class="col">
              <input class="btn-check" name="colorScheme" id="colorSchemeLight" type="radio" value="light">
              <label class="btn w-100 btn-white" for="colorSchemeLight">
                <i class="fe fe-sun me-2"></i> Light Mode
              </label>
            </div>
            <div class="col">
              <input class="btn-check" name="colorScheme" id="colorSchemeDark" type="radio" value="dark">
              <label class="btn w-100 btn-white" for="colorSchemeDark">
                <i class="fe fe-moon me-2"></i> Dark Mode
              </label>
            </div>
          </div>
      
          <!-- Heading -->
          <h4 class="mb-1">
            Navigation Position
          </h4>
      
          <!-- Text -->
          <p class="small text-body-secondary mb-3">
            Select the primary navigation paradigm for your app.
          </p>
      
          <!-- Button group -->
          <div class="btn-group-toggle row gx-2 mb-4">
            <div class="col">
              <input class="btn-check" name="navPosition" id="navPositionSidenav" type="radio" value="sidenav">
              <label class="btn w-100 btn-white" for="navPositionSidenav">
                Sidenav
              </label>
            </div>
            <div class="col">
              <input class="btn-check" name="navPosition" id="navPositionTopnav" type="radio" value="topnav">
              <label class="btn w-100 btn-white" for="navPositionTopnav">
                Topnav
              </label>
            </div>
            <div class="col">
              <input class="btn-check" name="navPosition" id="navPositionCombo" type="radio" value="combo">
              <label class="btn w-100 btn-white" for="navPositionCombo">
                Combo
              </label>
            </div>
          </div>
      
          <!-- Collapse -->
          <div id="sidebarSizeContainer">
      
            <!-- Heading -->
            <h4 class="mb-1">
              Sidenav Sizing
            </h4>
      
            <!-- Text -->
            <p class="small text-body-secondary mb-3">
              Standard navigation sizing or minified icons with dropdowns.
            </p>
      
            <!-- Button group -->
            <div class="btn-group-toggle row gx-2 mb-4">
              <div class="col">
                <input class="btn-check" name="sidebarSize" id="sidebarSizeBase" type="radio" value="base">
                <label class="btn w-100 btn-white" for="sidebarSizeBase">
                  Fullsize
                </label>
              </div>
              <div class="col">
                <input class="btn-check" name="sidebarSize" id="sidebarSizeSmall" type="radio" value="small">
                <label class="btn w-100 btn-white" for="sidebarSizeSmall">
                  Icons
                </label>
              </div>
            </div>
      
          </div>
      
          <!-- Heading -->
          <h4 class="mb-1">
            Navigation Color
          </h4>
      
          <!-- Text -->
          <p class="small text-body-secondary mb-3">
            Usually dictated by the color scheme, but can be overriden.
          </p>
      
          <!-- Button group -->
          <div class="btn-group-toggle row gx-2">
            <div class="col">
              <input class="btn-check" name="navColor" id="navColorDefault" type="radio" value="default">
              <label class="btn w-100 btn-white" for="navColorDefault">
                Default
              </label>
            </div>
            <div class="col">
              <input class="btn-check" name="navColor" id="navColorInverted" type="radio" value="inverted">
              <label class="btn w-100 btn-white" for="navColorInverted">
                Inverted
              </label>
            </div>
            <div class="col">
              <input class="btn-check" name="navColor" id="navColorVibrant" type="radio" value="vibrant">
              <label class="btn w-100 btn-white" for="navColorVibrant">
                Vibrant
              </label>
            </div>
          </div>
      
        </div>
        <div class="offcanvas-header">
      
          <!-- Button -->
          <button type="submit" class="btn w-100 btn-primary mt-auto">
            Apply
          </button>
      
        </div>
      </form>
    <!-- NAVIGATION -->
    <div data-bs-theme="">
      <nav class="navbar navbar-vertical fixed-start navbar-expand-md" id="sidebar">
        <div class="container-fluid">
      
          <!-- Toggler -->
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse" aria-controls="sidebarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
      
          <!-- Brand -->
          <a  href="dashboard.php" style="display:flex;padding:10px;align-items:center;font-family:Cursive fonts;justify-content:center;">
            <img src="../../assets/img/logo.webp"  alt="..." style="width;70px;height:70px;">
          </a>
      
          <!-- User (xs) -->
          <div class="navbar-user d-md-none">
      
            <!-- Dropdown -->
            <div class="dropdown">
      
              <!-- Toggle -->
              <a href="#" id="sidebarIcon" class="dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-sm avatar-online">
                  <img src="../../assets/img/avatars/profiles/R.png" class="avatar-img rounded-circle" alt="...">
                </div>
              </a>
      
              <!-- Menu -->
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="sidebarIcon">
                <a href="profile.php" class="dropdown-item">Profile</a>
                <hr class="dropdown-divider">
                <a href="../controller/logout.php" class="dropdown-item">Logout</a>
              </div>
      
            </div>
      
          </div>
      
          <!-- Collapse -->
          <div class="collapse navbar-collapse" id="sidebarCollapse">
      
            <!-- Form -->
            
      
            <!-- Navigation -->
            <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link " href="dashboard.php">
                  <i class="fe fe-home"></i> Dashboard
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link " href="notice.php">
                  <i class="fe fe-plus"></i> Notice
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="sidebarDashboards">
                  <i class="fe fe-book"></i> Marks
                </a>
                <div class="collapse show" id="sidebarDashboards">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a href="add_marks.php" class="nav-link active">
                        Add Marks
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="View_Marks.php" class="nav-link ">
                        View Marks
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="Export_Marks.php" class="nav-link ">
                       Export Marks
                      </a>
                    </li>
                   
                  
                  </ul>
                </div>
              </li>
             
             
              
              
            </ul>
      
            <!-- Divider -->
            <hr class="navbar-divider my-3">
      
            <!-- Heading -->
            
      
         
      
            <!-- Push content down -->
            <div class="mt-auto"></div>
      
              <!-- Customize -->
              <div class="mb-4" id="popoverDemo" title="Adjust to your Comfortable!" data-bs-content="Switch the demo to Dark Mode or adjust the navigation layout, icons, and colors!">
                <a class="btn w-100 btn-primary" data-bs-toggle="offcanvas" href="#offcanvasDemo" aria-controls="offcanvasDemo">
                  <i class="fe fe-sliders me-2"></i> Customize
                </a>
              </div>
              <div id="popoverDemoContainer" data-bs-theme="dark"></div>
      
              <!-- User (md) -->
              <div class="navbar-user d-none d-md-flex" id="sidebarUser">
      
                
      
                <!-- Dropup -->
                <div class="dropup">
      
                  <!-- Toggle -->
                  <a href="#" id="sidebarIconCopy" class="dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-sm avatar-online">
                      <img src="../../assets/img/avatars/profiles/R.png" class="avatar-img rounded-circle" alt="...">
                    </div>
                  </a>
      
                  <!-- Menu -->
                  <div class="dropdown-menu" aria-labelledby="sidebarIconCopy">
                    <a href="profile.php" class="dropdown-item">Profile</a>
                    <hr class="dropdown-divider">
                    <a href="../controller/logout.php" class="dropdown-item">Logout</a>
                  </div>
      
                </div>
      
                <!-- Icon -->
              
      
              </div>
      
          </div> <!-- / .navbar-collapse -->
      
        </div>
      </nav>
    </div>
    <div data-bs-theme="">
      <nav class="navbar navbar-vertical navbar-vertical-sm fixed-start navbar-expand-md" id="sidebarSmall">
        <div class="container-fluid">
      
          <!-- Toggler -->
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarSmallCollapse" aria-controls="sidebarSmallCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
      
          <!-- Brand -->
          <a  href="dashboard.php" style="justify-content:center;display: flex;align-items: center;height:70px;">
            <img src= "../../assets/img/logo.webp" class="navbar-brand-img 
            mx-auto" alt="logo" >
          </a>
          <!-- User (xs) -->
          <div class="navbar-user d-md-none">
      
            <!-- Dropdown -->
            <div class="dropdown">
      
              <!-- Toggle -->
              <a href="#" id="sidebarSmallIcon" class="dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-sm avatar-online">
                  <img src="../../assets/img/avatars/profiles/R.png" class="avatar-img rounded-circle" alt="...">
                </div>
              </a>
      
              <!-- Menu -->
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="sidebarSmallIcon">
                <a href="profile.php" class="dropdown-item">Profile</a>
                <a href="account-general.html" class="dropdown-item">Settings</a>
                <hr class="dropdown-divider">
                <a href="../controller/logout.php" class="dropdown-item">Logout</a>
              </div>
      
            </div>
      
          </div>
      
          <!-- Collapse -->
          <div class="collapse navbar-collapse" id="sidebarSmallCollapse">
      
            <!-- Form -->
            
      
            <!-- Divider -->
            <hr class="navbar-divider d-none d-md-block mt-0 mb-3">
      
            <!-- Navigation -->
            <ul class="navbar-nav">
            <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" data-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' title="Dashboard">
                <a class="nav-link " href="dashboard.php">
                  <i class="fe fe-home"></i> <span class="d-md-none">Dashboard</span>
                </a>
              </li>
              <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" data-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' title="Notice">
                <a class="nav-link " href="notice.php">
                  <i class="fe fe-plus"></i> <span class="d-md-none">Notice</span>
                </a>
              </li>
              <li class="nav-item dropend">
                <a class="nav-link dropdown-toggle active" id="sidebarSmallDashboards" href="#" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true" title="Dashboards">
                  <i class="fe fe-book"></i> <span class="d-md-none">Marks</span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="sidebarSmallDashboards">
                  <li class="dropdown-header d-none d-md-block">
                    <h6 class="text-uppercase mb-0">Marks</h6>
                  </li>
                  <li>
                    <a href="add_marks.php" class="dropdown-item ">
                      Add Marks
                    </a>
                  </li>
                  <li>
                    <a href="View_Marks.php" class="dropdown-item ">
                    View Marks
                </a>
                  </li>
                  <li>
                    <a href="Export_Marks.php" class="dropdown-item ">
                      Export Marks
                    </a>
                  </li>
               
                 
                </ul>
              </li>
              
             
             
            </ul>
      
            <!-- Divider -->
            <hr class="navbar-divider my-3">
      
            <!-- Navigation -->
            
      
            <!-- Push content down -->
            <div class="mt-auto"></div>
      
              <!-- Customize -->
              <div class="mb-4" data-bs-toggle="tooltip" data-bs-placement="right" data-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' title="Customize">
                <a class="btn w-100 btn-primary" data-bs-toggle="offcanvas" href="#offcanvasDemo" aria-controls="offcanvasDemo">
                  <i class="fe fe-sliders"></i> <span class="d-md-none ms-2">Customize</span>
                </a>
              </div>
      
              <!-- User (md) -->
              <div class="navbar-user d-none d-md-flex flex-column" id="sidebarSmallUser">
      
                <!-- Icon -->
                <a class="navbar-user-link mb-3" data-bs-toggle="offcanvas" href="#sidebarOffcanvasSearch" aria-controls="sidebarOffcanvasSearch">
                  <span class="icon">
                    <i class="fe fe-search"></i>
                  </span>
                </a>
      
               
      
                <!-- Dropup -->
                <div class="dropend">
      
                  <!-- Toggle -->
                  <a href="#" id="sidebarSmallIconCopy" class="dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-sm avatar-online">
                      <img src="../../assets/img/avatars/profiles/R.png" class="avatar-img rounded-circle" alt="...">
                    </div>
                  </a>
      
                  <!-- Menu -->
                  <div class="dropdown-menu" aria-labelledby="sidebarSmallIconCopy">
                    <a href="profile.php" class="dropdown-item">Profile</a>
                    <hr class="dropdown-divider">
                    <a href="../controller/logout.php" class="dropdown-item">Logout</a>
                  </div>
      
                </div>
      
              </div>
      
          </div> <!-- / .navbar-collapse -->
      
        </div>
      </nav>
    </div>
    <div data-bs-theme="">
      <nav class="navbar navbar-expand-lg" id="topnav">
        <div class="container">
      
          <!-- Toggler -->
          <button class="navbar-toggler me-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
      
          <!-- Brand -->
          <a class=" me-auto" style="display:flex;width:100px;height:50px;justify-content:center;align-items:center;" href="dashboard.php">
            <img src="../../assets/img/logo.webp" alt="..."  style="width:50px;height:50px;">
          </a>
      
          <!-- Form -->
          <form class="form-inline me-4 d-none d-lg-flex">
            <div class="input-group input-group-rounded input-group-merge input-group-reverse" data-list='{"valueNames": ["name"]}'>
      
              <!-- Input -->
      
              <!-- Icon -->
             
      
              <!-- Menu -->
              <!-- / .dropdown-menu -->
      
            </div>
          </form>
      
          <!-- User -->
          <div class="navbar-user">
      
           
            <!-- Dropdown -->
            <div class="dropdown">
      
              <!-- Toggle -->
              <a href="#" class="avatar avatar-sm avatar-online dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="../../assets/img/avatars/profiles/R.png" alt="..." class="avatar-img rounded-circle">
              </a>
      
              <!-- Menu -->
              <div class="dropdown-menu dropdown-menu-end">
                <a href="profile.php" class="dropdown-item">Profile</a>
                <hr class="dropdown-divider">
                <a href="../controller/logout.php" class="dropdown-item">Logout</a>
              </div>
      
            </div>
      
          </div>
      
          <!-- Collapse -->
          <div class="collapse navbar-collapse me-lg-auto order-lg-first" id="navbar">
      
            <!-- Form -->
            
      
            <!-- Navigation -->
            <ul class="navbar-nav me-lg-auto">
              <li class="nav-item dropdown">
                <a class="nav-link active" href="dashboard.php" >
                  Dashboards
                </a>
                
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link " href="notice.php" >
                  Notice
                </a>
                
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle " href="#" id="topnavDocumentation" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                  Marks
                </a>
                <ul class="dropdown-menu" aria-labelledby="topnavDocumentation">
                <li>
                    <a class="dropdown-item " href="add_marks.php">
                      Add Marks
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item " href="View_Marks.php">
                      View Marks
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item " href="Export_Marks.php">
                      Export Marks 
                    </a>
                  </li>
                 
                  
                </ul>
              </li>


             
             
            
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="offcanvas" href="#offcanvasDemo" aria-controls="offcanvasDemo">
                  Customize
                </a>
              </li>
            </ul>
      
          </div>
      
        </div> <!-- / .container -->
      </nav>
    </div>
    <!-- MAIN CONTENT -->
    <div class="main-content">

      <nav class="navbar navbar-expand-md navbar-light d-none d-md-flex" id="topbar">
  <div class="container-fluid">
 <!-- Form -->
        <form class="form-inline me-4 d-none d-md-flex">
      <div class="input-group input-group-flush input-group-merge input-group-reverse" data-list='{"valueNames": ["name"]}'>

        <!-- Input -->
        <!-- <input type="search" class="form-control dropdown-toggle list-search" data-bs-toggle="dropdown" placeholder="Search Students" aria-label="Search"> -->

        <!-- Prepend -->
        <!-- <div class="input-group-text">
          <i class="fe fe-search"></i>
        </div> -->

        <!-- Menu -->
       <!-- / .dropdown-menu -->

      </div>
    </form>
    <!-- User -->
    <div class="navbar-user">

      

      <!-- Dropdown -->
      <div class="dropdown">

        <!-- Toggle -->
        <a href="#" class="avatar avatar-sm avatar-online dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="../../assets/img/avatars/profiles/R.png" alt="..." class="avatar-img rounded-circle">
        </a>

        <!-- Menu -->
        <div class="dropdown-menu dropdown-menu-end">
          <a href="profile.php" class="dropdown-item">Profile</a>
          <hr class="dropdown-divider">
          <a href="../controller/logout.php" class="dropdown-item">Logout</a>
        </div>

      </div>

    </div>
   

   

  </div>
</nav>
 
<div class="wrapper">
    <!-- / .main-content -->

    
