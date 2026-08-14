<?php 

include '../functions/display.php';

include '../includes/header.php';
$students = fetchStudents();
?>

   <!-- HEADER -->
    <div class="header">
        <div class="container-fluid">

          <!-- Body -->
          <div class="header-body">
            <div class="row align-items-end">
              <div class="col">

                <!-- Pretitle -->
                <h6 class="header-pretitle">
                  Overview
                </h6>

                <!-- Title -->
                <h1 class="header-title">
                  Dashboard
                </h1>

              </div>
              <div class="col-auto">

                <!-- Button -->
                <a href="upload_student.php" class="btn btn-primary lift">
                  Import Student
                </a>

              </div>
            </div> <!-- / .row -->
          </div> <!-- / .header-body -->

        </div>
    </div> <!-- / .header -->

      <!-- CARDS -->
      <div class="container-fluid">
        <div class="row">
          <div class="col-12 col-lg-6 col-xl">

            <!-- Value  -->
            <div class="card">
              <div class="card-body">
                <div class="row align-items-center gx-0">
                  <div class="col">

                    <!-- Title -->
                    <h6 class="text-uppercase text-body-secondary mb-2">
                      Students
                    </h6>

                    <!-- Heading -->
                    <span class="h2 mb-0">
                    <?php echo count($students); ?>
                    </span>

                   
                  </div>
                  <div class="col-auto">

                    <!-- Icon -->
                    <span class="h2 fe fe-users text-body-secondary mb-0"></span>

                  </div>
                </div> <!-- / .row -->
              </div>
            </div>

          </div>
          <div class="col-12 col-lg-6 col-xl">

            <!-- Hours -->
            <div class="card">
              <div class="card-body">
                <div class="row align-items-center gx-0">
                  <div class="col">

                    <!-- Title -->
                    <h6 class="text-uppercase text-body-secondary mb-2">
                      Teacher
                    </h6>

                    <!-- Heading -->
                    <span class="h2 mb-0">
                      <?php $teachers=fetchTeacher();
                          echo count($teachers);
                      ?>
                      
                    </span>

                  </div>
                  <div class="col-auto">

                    <!-- Icon -->
                    <span class="h2 fe fe-users text-body-secondary mb-0"></span>

                  </div>
                </div> <!-- / .row -->
              </div>
            </div>

          </div>
          <div class="col-12 col-lg-6 col-xl">

            <!-- Exit -->
            <div class="card">
              <div class="card-body">
                <div class="row align-items-center gx-0">
                  <div class="col">

                    <!-- Title -->
                    <h6 class="text-uppercase text-body-secondary mb-2">
                Discipline      
                </h6>

                    <!-- Heading -->
                    <span class="h2 mb-0">
                    <?php 
                      include '../includes/config.php';

                          // Initialdiscipline variable
                          $discipline = 0;

                      try {
                          // Prepare the SQL statement
                          $sql = "SELECT COUNT(*) AS count FROM course";
                          $result = $conn->query($sql);

                          // Check if the query was successful
                          if ($result && $result->num_rows > 0) {
                              $row = $result->fetch_assoc();
                              $discipline = $row['count'];
                          } else {
                              throw new Exception('Query failed or no data found');
                          }

                          // Close the database connection
                          $result->free();
                          $conn->close();

                      } catch (Exception $e) {
                                    $discipline = 0;
                      }
                    ?>
                    <?php echo htmlspecialchars($discipline); ?>
                    </span>

                  </div>
                  <div class="col-auto">

                     <!-- Icon -->
                     <span class="h2 fe fe-book text-body-secondary mb-0"></span>


                  </div>
                </div> <!-- / .row -->
              </div>
            </div>

          </div>
          <div class="col-12 col-lg-6 col-xl">

            <!-- Time -->
            <div class="card">
              <div class="card-body">
                <div class="row align-items-center gx-0">
                  <div class="col">

                    <!-- Title -->
                    <h6 class="text-uppercase text-body-secondary mb-2">
                      Streams
                    </h6>

                    <!-- Heading -->
                    <span class="h2 mb-0">
                   
                    <?php 
                      include '../includes/config.php';

                          // Initialize count variable
                          $count = 0;

                      try {
                          // Prepare the SQL statement
                          $sql = "SELECT COUNT(*) AS count FROM streams";
                          $result = $conn->query($sql);

                          // Check if the query was successful
                          if ($result && $result->num_rows > 0) {
                              $row = $result->fetch_assoc();
                              $count = $row['count'];
                          } else {
                              throw new Exception('Query failed or no data found');
                          }

                          // Close the database connection
                          $result->free();
                          $conn->close();

                      } catch (Exception $e) {
                                    $count = 0;
                      }
                    ?>
                    <?php echo htmlspecialchars($count); ?>
                    </span>

                  </div>
                  <div class="col-auto">

                    <!-- Icon -->
                    <span class="h2 fe fe-bookmark text-body-secondary mb-0"></span>

                  </div>
                </div> <!-- / .row -->
              </div>
            </div>

          </div>
        </div> <!-- / .row -->
        <div class="row">
          <div class="col-12 col-xl-8">

            <!-- Convertions -->
            <div class="card">
              <div class="card-header">

                <!-- Title -->
                <h4 class="card-header-title">
                  Total Number of Students
                </h4>

                

               

              </div>
              <div class="card-body">

                <!-- Chart -->
                <div class="chart">
                <canvas id="myChart" class="chart-canvas"></canvas>
                  <!-- <canvas id="conversionsChart" class="chart-canvas"></canvas> -->
                </div>

              </div>
            </div>
          </div>
          <div class="col-12 col-xl-4">

            <!-- Traffic -->
            <div class="card">
              <div class="card-header">

                <!-- Title -->
                <h4 class="card-header-title">
                      NOTICE
              </h4>

                <!-- Tabs -->
                <!-- <ul class="nav nav-tabs nav-tabs-sm card-header-tabs">
                  <li class="nav-item" data-toggle="chart" data-target="#trafficChart" data-trigger="click" data-action="toggle" data-dataset="0">
                    <a href="#" class="nav-link active" data-bs-toggle="tab">
                      All
                    </a>
                  </li>
                  <li class="nav-item" data-toggle="chart" data-target="#trafficChart" data-trigger="click" data-action="toggle" data-dataset="1">
                    <a href="#" class="nav-link" data-bs-toggle="tab">
                      Direct
                    </a>
                  </li>
                </ul> -->

              </div>
              <div class="card-body">

                <!-- Chart -->
                <div style="height:300px" >
                <marquee  direction="up" onMouseOver="this.stop()" onMouseOut="this.start()" onclick="this.start()" style="height:300px">
         <?php
          include '../includes/config.php';

        $sql="Select * from `admin_notice`";
       
        $result=mysqli_query($conn,$sql);
        if($result){
            
            while( $row=mysqli_fetch_assoc($result)){
                $id=$row['id'];
                $title=$row['title'];
                $message=$row['message'];
                echo'
    

                '.$title.'
              '.$message.'<hr>
              
               ';
            }
        }
        // <img src="../assets/img/flashing.gif">
        //       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          
       
           ?>  
            </marquee>
                </div>

                <!-- Legend -->
                <div id="trafficChartLegend" class="chart-legend"></div>

              </div>
            </div>
          </div>
        </div> <!-- / .row -->
        
      </div>
      <script>
  
</script>
<?php 
include '../includes/footer.php'
?>