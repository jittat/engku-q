<?php
if(!isset($results)) {
  exit();
}

$num_students = count($results);
$num_attending = 0;
$num_parent_attending = 0;
foreach($results as $r) {
  if($r->is_attending_orientation == 1) {
    $num_attending++;
    if($r->is_parents_attending == 1)
      $num_parent_attending++;
  }
}
$num_not_attending = $num_students - $num_attending;
?>
<h1>สรุปผล</h1>
จำนวนผู้ตอบแบบสอบถาม: <?= $num_students ?> คน<br>
จำนวนเข้าปฐมนิเทศ: <?= $num_attending ?> คน, มีผู้ปกครองมาด้วย: <?= $num_parent_attending ?> คน<br>
<div id="attending_chart_id">
</div>
<div id="parent_attending_chart_id">
</div>
<script>
 google.load('visualization', '1.0', {'packages':['corechart']});
 google.setOnLoadCallback(drawAttendingChart);
 google.setOnLoadCallback(drawParentAttendingChart);

 function drawAttendingChart() {
   // Create the data table.
   var data = new google.visualization.arrayToDataTable([
     ['Students', 'Number of students'],
     ['Attending: <?= $num_attending ?>', <?= $num_attending ?>],
     ['Not attending: <?= $num_not_attending ?>', <?= $num_not_attending ?>]
   ]);

   // Set chart options
   var options = {'title':'จำนวนนิสิตที่เข้าร่วมการปฐมนิเทศ'};
   
   // Instantiate and draw our chart, passing in some options.
   var chart = new google.visualization.PieChart(document.getElementById('attending_chart_id'));
   chart.draw(data, options);
 }
 function drawParentAttendingChart() {
   // Create the data table.
   var data = new google.visualization.arrayToDataTable([
     ['Students', 'Number of students'],
     ['ผู้ปกครองเข้าร่วม: <?= $num_parent_attending ?>', <?= $num_attending ?>],
     ['ไม่มีผู้ปกครองเข้าร่วม: <?= $num_attending - $num_parent_attending ?>', <?= $num_attending - $num_parent_attending ?>]
   ]);

   // Set chart options
   var options = {'title':'จำนวนนิสิตที่ผู้ปกครองเข้าร่วมปฐมนิเทศด้วย'};
   
   // Instantiate and draw our chart, passing in some options.
   var chart = new google.visualization.PieChart(document.getElementById('parent_attending_chart_id'));
   chart.draw(data, options);
 }
</script>
