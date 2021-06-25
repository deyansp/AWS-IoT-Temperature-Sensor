<?php
include "navbar.php";
if (!isset($_SESSION['id'])) {
    header("Location: https://3.222.193.155/login_form.php");
    exit;
}
include "db.php";
$rows = getLatestFromDB();
?>
    <style>
        body {
            background: #007bff;
            background: linear-gradient(to right, #0062E6, #33AEFF);
        }
        .container {
            background: #fff;
            padding: 4rem;
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
        }
    </style>
    
<body>
<div class="container" style="margin-top: 10%;">
    
        <div class="col-md-12">
            <h5 class="text-center">Current Temperature:</h5>
            <h4 class="display-4  text-center" id="temp" style="font-weight: 600; font-size: 5rem;"><?php echo $rows["temperature"] . " " . $rows["unit"];?></h4>
            <h5 class="text-center" id="date"><?php echo $rows["date_taken"];?></h5>
            <input type="hidden" id="gap" value="<?php echo $rows["gap"];?>"></input>
            <div class="text-center" style="margin-top: 1rem;">
                <a href ="readings.php"><button class="btn btn-md btn-primary">View all readings</button></a>
            </div>
        </div>
</div>
<script>
            let temp = document.getElementById("temp");
            let date = document.getElementById("date");
            let gap = document.getElementById("gap");
            
        function updateTemp() {
            let ajax = new XMLHttpRequest();
            ajax.open("POST", "update.php", true);
            ajax.send();
        
            ajax.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var data = JSON.parse(this.responseText);
                    temp.innerHTML = data.temperature + " °" + data.unit;
                    date.innerHTML = data.date_taken;
                    gap.value = data.gap;    
                }
            };
            setTimeout(updateTemp, parseInt(gap.value, 10) * 1000);
        }
        updateTemp();
</script>
</body>
</html>
