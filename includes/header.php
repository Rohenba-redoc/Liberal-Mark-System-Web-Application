<?php 
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['user'])) {
  // Redirect to login page if not logged in
  header('Location: sign-in.php');
  exit();
}

// Retrieve user data from session
$userId = $_SESSION['user']['id'];
$username = $_SESSION['user']['username'];
$email = $_SESSION['user']['email'];
$password = $_SESSION['user']['password'];
$status = $_SESSION['user']['status'];
$level = $_SESSION['user']['level'];
?>
<?php 
include 'config.php';
function fetchstudent() {
  $query = "SELECT * FROM students";
  global $conn;

  $stmt = $conn->prepare($query);
  
  $stmt->execute();
  $result = $stmt->get_result();
  return $result->fetch_all(MYSQLI_ASSOC);
}
$studentss= fetchstudent();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="">
  
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Liberal College Admin Section">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/img/logo.webp" type="image/x-icon">
    

    <!-- Libs CSS -->
    <link rel="stylesheet" href="../assets/css/libs.bundle.css">
    
    <!-- Theme CSS -->
    <link rel="stylesheet" href="../assets/css/theme.bundle.css">
    
    <style>body { display: none; }</style>
    
    <!-- Title -->
    <title>LC-Hub</title>
    
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

    <!-- OFFCANVAS -->
      <!-- Offcanvas: Search -->
    <div class="offcanvas offcanvas-start" id="sidebarOffcanvasSearch" tabindex="-1">
      <div class="offcanvas-body" data-list='{"valueNames": ["name"]}'>
    
        <!-- Form -->
        <form class="mb-4">
          <div class="input-group input-group-merge input-group-rounded input-group-reverse">
            <input class="form-control list-search" type="search" placeholder="Search">
            <div class="input-group-text">
              <span class="fe fe-search"></span>
            </div>
          </div>
        </form>
    
        <!-- List group -->
        <div class="my-n3">
          <div class="list-group list-group-flush list-group-focus list">
            
            <?php if (!empty($studentss)): ?>
                      <?php foreach ($studentss as $student): ?>
                      <a class="list-group-item" href="student_details.php?id=<?php echo htmlspecialchars($student['student_id']); ?>">
                      <div class="row align-items-center">
                        <div class="col-auto">
      
                          <!-- Avatar -->
                          <div class="avatar">
                            <img src="../assets/img/avatars/profiles/undraw_profile.svg" alt="..." class="avatar-img rounded-circle">
                          </div>
      
                        </div>
                        <div class="col ms-n2">
      
                          <!-- Title -->
                          <h4 class="text-body text-focus mb-1 name">
                          <?php echo htmlspecialchars($student['student_name']); ?>
                          </h4>
      
                          <!-- Status -->
                          <p class="text-body small mb-0">
                          <span class="text-danger">Unique-ID</span> - <?php echo htmlspecialchars($student['student_id']); ?>
                          </p>
      
                        </div>
                      </div> <!-- / .row -->
                      </a>
                      <?php endforeach; ?>

                    <?php else: ?>
                      <h4 class="text-body text-focus mb-1 name">
                            No Students Found
                          </h4>
                  <?php endif; ?>
          </div> 
        </div>
    
      </div>
    </div>
      <!-- Offcanvas: Demo -->
      <form class="offcanvas offcanvas-end" id="offcanvasDemo" tabindex="-1">
        <div class="offcanvas-body">
      
          <!-- Close -->
          <a class="btn-close" href="#" data-bs-dismiss="offcanvas" aria-label="Close"></a>
      
          <!-- Image -->
          <!-- <div class="text-center">
            <img src="../assets/img/illustrations/designer-life.svg" alt="..." class="img-fluid mb-3">
          </div> -->
      
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
          <a  href="index.php" style="display:flex;">
            <img src="../assets/img/logo.webp" class="navbar-brand-img mx-auto" alt="..." style="width:50px;">
            
          </a>
      
          <!-- User (xs) -->
          <div class="navbar-user d-md-none">
      
            <!-- Dropdown -->
            <div class="dropdown">
      
              <!-- Toggle -->
              <a href="#" id="sidebarIcon" class="dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-sm avatar-online">
                  <img src="../assets/img/avatars/profiles/R.png" class="avatar-img rounded-circle" alt="...">
                </div>
              </a>
      
              <!-- Menu -->
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="sidebarIcon">
                <a href="profile.php" class="dropdown-item">Profile</a>
                <hr class="dropdown-divider">
                <a href="../functions/logout.php" class="dropdown-item">Logout</a>
              </div>
      
            </div>
      
          </div>
      
          <!-- Collapse -->
          <div class="collapse navbar-collapse" id="sidebarCollapse">
      
            <!-- Form -->
            <form class="mt-4 mb-3 d-md-none">
              <div class="input-group input-group-rounded input-group-merge input-group-reverse">
                <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-text">
                  <span class="fe fe-search"></span>
                </div>
              </div>
            </form>
      
            <!-- Navigation -->
            <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link active" href="index.php" >
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
                  <i class="fe fe-book"></i> Programs
                </a>
                <div class="collapse show" id="sidebarDashboards">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a href="stream.php" class="nav-link ">
                        Streams
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="semester.php" class="nav-link ">
                        Semester
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="department.php" class="nav-link ">
                       Department
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="subject.php" class="nav-link ">
                       Subject
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="course.php" class="nav-link ">
                       Discipline
                      </a>
                    </li>
                   
                  
                  </ul>
                </div>
              </li>
             
             
              
              
            </ul>
      
            <!-- Divider -->
            <hr class="navbar-divider my-3">
      
            <!-- Heading -->
            <h6 class="navbar-heading">
              User
            </h6>
      
            <!-- Navigation -->
            <ul class="navbar-nav mb-md-4">
            <li class="nav-item">
                <a class="nav-link" href="#sidebarAuth" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAuth">
                  <i class="fe fe-user"></i> User
                </a>
                <div class="collapse" id="sidebarAuth">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a href="#sidebarSignIn" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSignIn">
                       Student
                      </a>
                      <div class="collapse" id="sidebarSignIn">
                        <ul class="nav nav-sm flex-column">
                          <li class="nav-item">
                            <a href="student.php" class="nav-link">
                              Students
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="enrolled_student.php" class="nav-link">
                              Enrolled_Students
                            </a>
                          </li>
                         
                        </ul>
                      </div>
                    </li>
                    <li class="nav-item">
                      <a href="#sidebarSignUp" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSignUp">
                            Teacher 
                                             </a>
                      <div class="collapse" id="sidebarSignUp">
                        <ul class="nav nav-sm flex-column">
                          <li class="nav-item">
                            <a href="teacher.php" class="nav-link">
                              User
                            </a>
                          </li>
                          
                          
                        </ul>
                      </div>
                    </li>
                   <?php if($level == 'Super Admin'){?>
                      <li class="nav-item">
                      <a href="#sidebarPassword" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPassword">
                        Admin
                      </a>
                      <div class="collapse" id="sidebarPassword">
                        <ul class="nav nav-sm flex-column">
                          <li class="nav-item">
                            <a href="user.php" class="nav-link">
                              User
                            </a>
                          </li>
                        </ul>
                      </div>
                    </li>
                    <li class="nav-item">
                      <a href="pass.php" class="nav-link">
                        PassKey
                      </a>
                      
                    </li>
                   <?php }?>
                   
                   
                  </ul>
                </div>
              </li>
            </ul>
      
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
                      <img src="../assets/img/avatars/profiles/R.png" class="avatar-img rounded-circle" alt="...">
                    </div>
                  </a>
      
                  <!-- Menu -->
                  <div class="dropdown-menu" aria-labelledby="sidebarIconCopy">
                    <a href="profile.php" class="dropdown-item">Profile</a>
                    <hr class="dropdown-divider">
                    <a href="../functions/logout.php" class="dropdown-item">Logout</a>
                  </div>
      
                </div>
      
                <!-- Icon -->
                <a class="navbar-user-link" data-bs-toggle="offcanvas" href="#sidebarOffcanvasSearch" aria-controls="sidebarOffcanvasSearch">
                  <span class="icon">
                    <i class="fe fe-search"></i>
                  </span>
                </a>
      
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
          <a  href="index.php" style="justify-content:center;display: flex;align-items: center;">
            <img src= "../assets/img/logo.webp" class="navbar-brand-img 
            mx-auto" alt="logo" >
          </a>
      
          <!-- User (xs) -->
          <div class="navbar-user d-md-none">
      
            <!-- Dropdown -->
            <div class="dropdown">
      
              <!-- Toggle -->
              <a href="#" id="sidebarSmallIcon" class="dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-sm avatar-online">
                  <img src="../assets/img/avatars/profiles/R.png" class="avatar-img rounded-circle" alt="...">
                </div>
              </a>
      
              <!-- Menu -->
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="sidebarSmallIcon">
                <a href="profile.php" class="dropdown-item">Profile</a>
                <a href="account-general.html" class="dropdown-item">Settings</a>
                <hr class="dropdown-divider">
                <a href="../functions/logout.php" class="dropdown-item">Logout</a>
              </div>
      
            </div>
      
          </div>
      
          <!-- Collapse -->
          <div class="collapse navbar-collapse" id="sidebarSmallCollapse">
      
            <!-- Form -->
            <form class="mt-4 mb-3 d-md-none">
              <div class="input-group input-group-rounded input-group-merge input-group-reverse">
                <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-text">
                  <span class="fe fe-search"></span>
                </div>
              </div>
            </form>
      
            <!-- Divider -->
            <hr class="navbar-divider d-none d-md-block mt-0 mb-3">
      
            <!-- Navigation -->
            <ul class="navbar-nav">
            <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" data-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' title="Dashboard">
                <a class="nav-link " href="index.php">
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
                  <i class="fe fe-book"></i> <span class="d-md-none">Programs</span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="sidebarSmallDashboards">
                  <li class="dropdown-header d-none d-md-block">
                    <h6 class="text-uppercase mb-0">Programs</h6>
                  </li>
                  <li>
                    <a href="stream.php" class="dropdown-item ">
                      Streams
                    </a>
                  </li>
                  <li>
                    <a href="semester.php" class="dropdown-item ">
                    Semester
                </a>
                  </li>
                  <li>
                    <a href="department.php" class="dropdown-item ">
                      Department
                    </a>
                  </li>
                  <li>
                    <a href="subject.php" class="dropdown-item ">
                      Subject
                    </a>
                  </li>
                  <li>
                    <a href="course.php" class="dropdown-item ">
                      Discipline
                    </a>
                  </li>
                
                 
                </ul>
              </li>
              
             
             
            </ul>
      
            <!-- Divider -->
            <hr class="navbar-divider my-3">
      
            <!-- Navigation -->
            <ul class="navbar-nav mb-md-4">
            <li class="nav-item dropend">
                <a class="nav-link dropdown-toggle" id="sidebarSmallAuth" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-haspopup="true" aria-expanded="false">
                  <i class="fe fe-user"></i> <span class="d-md-none">User</span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="sidebarSmallAuth">
                  <li class="dropdown-header d-none d-md-block">
                    <h6 class="text-uppercase mb-0">User</h6>
                  </li>
                  <li class="dropend">
                    <a class="dropdown-item dropdown-toggle" href="#" id="sidebarSmallSignIn" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Student
                    </a>
                    <div class="dropdown-menu" aria-labelledby="sidebarSmallSignIn">
                      <a class="dropdown-item" href="student.php">
                        Student
                      </a>
                      <a class="dropdown-item" href="enrolled_student.php">
                      Enrolled_Student
                      </a>
                     
                    </div>
                  </li>
                  <li class="dropend">
                    <a class="dropdown-item dropdown-toggle" href="#" id="sidebarSmallSignUp" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Teacher
                    </a>
                    <div class="dropdown-menu" aria-labelledby="sidebarSmallSignUp">
                      <a class="dropdown-item" href="teacher.php">
                        User
                      </a>
                      
                    </div>
                  </li>
                  <?php if($level == 'Super Admin') {?>

                  <li class="dropend">
                    <a class="dropdown-item dropdown-toggle" href="#" id="sidebarSmallPassword" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Admin
                    </a>
                    <div class="dropdown-menu" aria-labelledby="sidebarSmallPassword">
                      <a class="dropdown-item" href="user.php">
                        User
                      </a>
                      
                    </div>
                  </li>
                  <li class="dropend">
                    <a class="dropdown-item " href="pass.php">
                      Passkey
                    </a>

                      
                    
                  </li>
                  <?php } ?>
                 
                </ul>
              </li>
            </ul>
      
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
                      <img src="../assets/img/avatars/profiles/R.png" class="avatar-img rounded-circle" alt="...">
                    </div>
                  </a>
      
                  <!-- Menu -->
                  <div class="dropdown-menu" aria-labelledby="sidebarSmallIconCopy">
                    <a href="profile.php" class="dropdown-item">Profile</a>
                    <hr class="dropdown-divider">
                    <a href="../functions/logout.php" class="dropdown-item">Logout</a>
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
          <a class=" me-auto" style="display:flex;" href="index.php">
            <img src="../assets/img/logo.webp" alt="..." class="navbar-brand-img" >
            
          </a>
      
          <!-- Form -->
          <form class="form-inline me-4 d-none d-lg-flex">
            <div class="input-group input-group-rounded input-group-merge input-group-reverse" data-list='{"valueNames": ["name"]}'>
      
              <!-- Input -->
              <input type="search" class="form-control dropdown-toggle list-search" data-bs-toggle="dropdown" placeholder="Search" aria-label="Search">
      
              <!-- Icon -->
              <div class="input-group-text">
                <i class="fe fe-search"></i>
              </div>
      
              <!-- Menu -->
              <div class="dropdown-menu dropdown-menu-card">
                <div class="card-body">
      
                  <!-- List group -->
                  <div class="list-group list-group-flush list-group-focus list my-n3">
                  <?php if (!empty($studentss)): ?>
                      <?php foreach ($studentss as $student): ?>
                      <a class="list-group-item" href="student_details.php?id=<?php echo htmlspecialchars($student['student_id']); ?>">
                      <div class="row align-items-center">
                        <div class="col-auto">
      
                          <!-- Avatar -->
                          <div class="avatar">
                            <img src="../assets/img/avatars/profiles/undraw_profile.svg" alt="..." class="avatar-img rounded-circle">
                          </div>
      
                        </div>
                        <div class="col ms-n2">
      
                          <!-- Title -->
                          <h4 class="text-body text-focus mb-1 name">
                          <?php echo htmlspecialchars($student['student_name']); ?>
                          </h4>
      
                          <!-- Status -->
                          <p class="text-body small mb-0">
                          <span class="text-danger">Unique-ID</span> - <?php echo htmlspecialchars($student['student_id']); ?>
                          </p>
      
                        </div>
                      </div> <!-- / .row -->
                      </a>
                      <?php endforeach; ?>

                    <?php else: ?>
                      <h4 class="text-body text-focus mb-1 name">
                            No Students Found
                          </h4>
                  <?php endif; ?>
                  </div>
      
                </div>
              </div> <!-- / .dropdown-menu -->
      
            </div>
          </form>
      
          <!-- User -->
          <div class="navbar-user">
      
           
            <!-- Dropdown -->
            <div class="dropdown">
      
              <!-- Toggle -->
              <a href="#" class="avatar avatar-sm avatar-online dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="../assets/img/avatars/profiles/R.png" alt="..." class="avatar-img rounded-circle">
              </a>
      
              <!-- Menu -->
              <div class="dropdown-menu dropdown-menu-end">
                <a href="profile.php" class="dropdown-item">Profile</a>
                <hr class="dropdown-divider">
                <a href="../functions/logout.php" class="dropdown-item">Logout</a>
              </div>
      
            </div>
      
          </div>
      
          <!-- Collapse -->
          <div class="collapse navbar-collapse me-lg-auto order-lg-first" id="navbar">
      
            <!-- Form -->
            <form class="mt-4 mb-3 d-md-none">
              <input type="search" class="form-control form-control-rounded" placeholder="Search" aria-label="Search">
            </form>
      
            <!-- Navigation -->
            <ul class="navbar-nav me-lg-auto">
              <li class="nav-item dropdown">
                <a class="nav-link active" href="index.php" >
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
                  Programs
                </a>
                <ul class="dropdown-menu" aria-labelledby="topnavDocumentation">
                <li>
                    <a class="dropdown-item " href="stream.php">
                      Streams
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item " href="semester.php">
                      Semester
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item " href="department.php">
                      Department
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item " href="subject.php">
                      Subject 
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item " href="course.php">
                      Discipline
                    </a>
                  </li>
                 
                  
                </ul>
              </li>


             
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="topnavAuth" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                  User
                </a>
                <ul class="dropdown-menu" aria-labelledby="topnavAuth">
                  <li class="dropend">
                    <a class="dropdown-item dropdown-toggle" href="#" id="topnavSignIn" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Student
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnavSignIn">
                      <a class="dropdown-item" href="student.php">
                        Students
                      </a>
                      <a class="dropdown-item" href="enrolled_student.php">
                        Enrolled_Students
                      </a>
                     
                    </div>
                  </li>
                  <li class="dropend">
                    <a class="dropdown-item dropdown-toggle" href="#" id="topnavSignUp" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Teacher
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnavSignUp">
                      <a class="dropdown-item" href="teacher.php">
                        User
                      </a>
                      
                    </div>
                  </li>
                  <?php if($level == 'Super Admin'){?>
                  <li class="dropend">
                    <a class="dropdown-item dropdown-toggle" href="#" id="topnavPassword" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Admin
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnavPassword">
                      <a class="dropdown-item" href="user.php">
                        User
                      </a>
                     
                    </div>
                  </li>
                  <li class="dropend">
                    <a class="dropdown-item" href="pass.php" >
                      Passkey
                    </a>
                    
                  </li>
                  <?php }?>
                 
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
        <input type="search" class="form-control dropdown-toggle list-search" data-bs-toggle="dropdown" placeholder="Search Students" aria-label="Search">

        <!-- Prepend -->
        <div class="input-group-text">
          <i class="fe fe-search"></i>
        </div>

        <!-- Menu -->
        <div class="dropdown-menu dropdown-menu-card">
          <div class="card-body">

            <!-- List group -->
            <div class="list-group list-group-flush list-group-focus list my-n3">
           <?php if (!empty($studentss)): ?>
                      <?php foreach ($studentss as $student): ?>
                      <a class="list-group-item" href="student_details.php?id=<?php echo htmlspecialchars($student['student_id']); ?>">
                      <div class="row align-items-center">
                        <div class="col-auto">
      
                          <!-- Avatar -->
                          <div class="avatar">
                            <img src="../assets/img/avatars/profiles/undraw_profile.svg" alt="..." class="avatar-img rounded-circle">
                          </div>
      
                        </div>
                        <div class="col ms-n2">
      
                          <!-- Title -->
                          <h4 class="text-body text-focus mb-1 name">
                          <?php echo htmlspecialchars($student['student_name']); ?>
                          </h4>
      
                          <!-- Status -->
                          <p class="text-body small mb-0">
                            <span class="text-danger">Unique-ID</span> - <?php echo htmlspecialchars($student['student_id']); ?>

                          </p>
      
                        </div>
                      </div> <!-- / .row -->
                      </a>
                      <?php endforeach; ?>

                    <?php else: ?>
                      <h4 class="text-body text-focus mb-1 name">
                            No Students Found
                          </h4>
                  <?php endif; ?>
              
            </div>
          </div>
        </div> <!-- / .dropdown-menu -->

      </div>
    </form>
    <!-- User -->
    <div class="navbar-user">

      

      <!-- Dropdown -->
      <div class="dropdown">

        <!-- Toggle -->
        <a href="#" class="avatar avatar-sm avatar-online dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="../assets/img/avatars/profiles/R.png" alt="..." class="avatar-img rounded-circle">
        </a>

        <!-- Menu -->
        <div class="dropdown-menu dropdown-menu-end">
          <a href="profile.php" class="dropdown-item">Profile</a>
          <hr class="dropdown-divider">
          <a href="../functions/logout.php" class="dropdown-item">Logout</a>
        </div>

      </div>

    </div>
   

   

  </div>
</nav>
 
<div class="wrapper">
    <!-- / .main-content -->

    
