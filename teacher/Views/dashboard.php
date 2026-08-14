<?php include '../includes/header.php'; ?>



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
                <a href="import_mark.php" class="btn btn-primary lift">
                  Import Marks
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
           <a href="add_notice.php"> 
           <div class="card">
              <div class="card-body">
                <div class="row align-items-center gx-0">
                  <div class="col">

                    <!-- Title -->
                    <h6 class="text-uppercase text-body-secondary mb-2">
                      Add Notice
                    </h6>

                    <!-- Heading -->
                    <span class="h2 mb-0">
                    </span>

                   
                  </div>
                  <div class="col-auto">

                    <!-- Icon -->
                    <span class="h2 fe fe-info text-body-secondary mb-0"></span>

                  </div>
                </div> <!-- / .row -->
              </div>
            </div>

          </div></a>
          <div class="col-12 col-lg-6 col-xl">

          <a href="add_marks.php">
          <div class="card">
              <div class="card-body">
                <div class="row align-items-center gx-0">
                  <div class="col">

                    <!-- Title -->
                    <h6 class="text-uppercase text-body-secondary mb-2">
                      Add Marks
                    </h6>

                    <!-- Heading -->
                    <span class="h2 mb-0">
                      
                      
                    </span>

                  </div>
                  <div class="col-auto">

                    <!-- Icon -->
                    <span class="h2 fe fe-plus text-body-secondary mb-0"></span>

                  </div>
                </div> <!-- / .row -->
              </div>
            </div>
          </a>

          </div>
          <div class="col-12 col-lg-6 col-xl">

           <a href="View_Marks.php">
           <div class="card">
              <div class="card-body">
                <div class="row align-items-center gx-0">
                  <div class="col">

                    <!-- Title -->
                    <h6 class="text-uppercase text-body-secondary mb-2">
                View Marks      
                </h6>

                    <!-- Heading -->
                    <span class="h2 mb-0">
                    
                    </span>

                  </div>
                  <div class="col-auto">

                     <!-- Icon -->
                     <span class="h2 fe fe-eye text-body-secondary mb-0"></span>


                  </div>
                </div> <!-- / .row -->
              </div>
            </div>
           </a>

          </div>
          <div class="col-12 col-lg-6 col-xl">

            <a href="Export_Marks.php">
            <div class="card">
              <div class="card-body">
                <div class="row align-items-center gx-0">
                  <div class="col">

                    <!-- Title -->
                    <h6 class="text-uppercase text-body-secondary mb-2">
                      Export Marks
                    </h6>

                    <!-- Heading -->
                    <span class="h2 mb-0">
                   
                 
                    </span>

                  </div>
                  <div class="col-auto">

                    <!-- Icon -->
                    <span class="h2 fe fe-download text-body-secondary mb-0"></span>

                  </div>
                </div> <!-- / .row -->
              </div>
            </div>
            </a>

          </div>
        </div> <!-- / .row -->
        <div class="row">
          
          <div class="col-12 col-xl-12">

            <!-- Traffic -->
            <div class="card">
              <div class="card-header">

                <!-- Title -->
                <h4 class="card-header-title">
                      NOTICE
              </h4>

                

              </div>
              <div class="card-body">

                <!-- Chart -->
                <div class="" >
                <marquee  direction="up" onMouseOver="this.stop()" onMouseOut="this.start()" onclick="this.start()">
          <?php
            include '../../includes/config.php';

            $sql=" SELECT title, message, created_at FROM `admin_notice` WHERE type='all'
                UNION ALL
                SELECT title, message, created_at FROM `teacher_notice` 
                ORDER BY created_at DESC
                    ";
       
                      $result=mysqli_query($conn,$sql);
            if($result)
            {
            
                    while( $row=mysqli_fetch_assoc($result))
                    {
                          $title=$row['title'];
                          $message=$row['message'];
                          echo'
    

                          '.$title.'<br>
                        '.$message.'<hr>
                        ';
                    }
            }
       
                          
       
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
    $(function() {
    $('marquee').mouseover(function() {
        $(this).attr('scrollamount',0);
    }).mouseout(function() {
         $(this).attr('scrollamount',5);
    });
});
</script>
<?php include '../includes/footer.php'; ?>