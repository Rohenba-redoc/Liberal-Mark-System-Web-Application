

</div>
</div>
 <!-- Footer -->
 <footer class="sticky-footer ">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                    <span>© 2024 All Rights Reserved
                    Designed by <a href="http://www.solvexgiga.in/" target="_blank">SOLVEXGIGA TECHNOLOGIES</a></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
            <script>
    function fetchData() {
        fetch('../functions/student_graph.php')
            .then(response => response.json())
            .then(data => {
                console.log('Data fetched:', data); // Debug statement
                const courseTitles = data.map(entry => entry.course_name);
                const numStudents = data.map(entry => entry.num_students);

                createChart(courseTitles, numStudents);
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    function createChart(courseTitles, numStudents) {
        console.log('Creating chart with data:', courseTitles, numStudents); // Debug statement

        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: courseTitles,
                datasets: [{
                    label: 'Number of Students Enrolled',
                    data: numStudents,
                    backgroundColor: 'lightblue',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            precision: 0,
                            stepSize: 1
                        }
                    }]
                },
               
            }
        });
    }

    fetchData();
</script>


<!-- JAVASCRIPT -->
        
    <!-- Vendor JS -->
    <script src="../assets/js/vendor.bundle.js"></script>
    
    <!-- Theme JS -->
    <script src="../assets/js/theme.bundle.js"></script>
    <script
src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>

  </body>

<!-- Mirrored from dashkit.goodthemes.co/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 26 Jul 2024 11:44:14 GMT -->
</html>