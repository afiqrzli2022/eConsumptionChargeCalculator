<?php
        
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $voltage = $_POST['voltage'];
            $current = $_POST['current'];
            $cRate = $_POST['cRate'];
        }

        function chargeCalculator($voltage, $current, $cRate, $hour){ //function for calculate charge of electric consumption
        
            $power = $voltage * $current; //calculating Power (Watt)
                
            $energy = $power * $hour / 1000; //calculating Energy (kWh)
                
            $total = $energy * ($cRate/100); //calculating price per hour in RM
                
            //returning the value in array type
            return [ 
                'power' => $power,
                'energy' => $energy,
                'total' => round($total,2),
            ];
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>E-Consumption Charge Calculator</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                background: rgb(30,32,35);
                background: linear-gradient(90deg, rgba(30,32,35,1) 0%, rgba(33,37,41,1) 35%, rgba(55,60,64,1) 100%);
                color: var(--bs-dark-text-emphasis);
                padding: 20px;
            }

            h1 {
                color: white;
                margin-bottom: 20px;
            }
            
            h5 {
                color: var(--bs-primary);
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            }

            .form-floating {
                margin-bottom: 15px;
            }

            table {
                margin-top: 20px;
                border-radius: 15px;
                overflow: hidden;
            }

            th, td {
                padding: 8px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="container my-4">
            <div class="row">
                <div class="col text-center">
                    <h1>E-Consumption Charge Calculator</h1>
                </div>
            </div>
            <div class="row"    >
                <div class="col-md-6 offset-md-3">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="voltage" name="voltage" pattern="[0-9]+([.][0-9]+)?" value="<?php echo  $voltage; ?>" placeholder="Voltage" title="Only digit and dot(.) is allowed" required required aria-describedby="voltageHelp">
                            <label class="form-label" for="voltage">Voltage</label>
                            <div id="voltageHelp" class="form-text" style="color:white">Volt (V)</div>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="current" name="current" pattern="[0-9]+([.][0-9]+)?" value="<?php echo  $current; ?>" placeholder="Current" title="Only digit and dot(.) is allowed" required required aria-describedby="currentHelp">
                            <label class="form-label" for="current">Current</label>
                            <div id="currentHelp" class="form-text" style="color:white">Ampere (A)</div>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="cRate" name="cRate" pattern="[0-9]+([.][0-9]+)?" value="<?php echo  $cRate; ?>" placeholder="Current Rate" title="Only digit and dot(.) is allowed" required required aria-describedby="rateHelp">
                            <label class="form-label" for="cRate">Current Rate</label>
                            <div id="rateHelp" class="form-text" style="color:white">cent/kWh</div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" name="submit" class="btn btn-primary p-2">Calculate</button>
                            <a href="<?php echo $_SERVER["PHP_SELF"]; ?>" class="btn btn-secondary p-2">Clear</a>
                            <a href="TNBChargeCalculator.php" class="btn btn-secondary p-2">TNB Charge Calculator</a>
                        </div>
                    </form>
                </div>
            </div>
            <?php 
                if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){ //this if statement to ensure that only when form is submitted this section is display

                    $result = chargeCalculator($voltage, $current, $cRate, 24);

                    echo    "<div class='container my-4'>
                                <div class='row'>
                                    <div class='col-md-6 offset-md-3'>
                                        <div class='card text-center shadow p-3 bg-white rounded'>
                                            <h5>Current Rate : ".$cRate." cent/kWh</h5>
                                            <h5>Power : ".$result['power']." Watt</h5>
                                            <h5>Energy consumption Per Day : ".$result['energy']." kWh</h5>
                                            <h5>Total Rate Per Day : RM ".$result['total']."</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>";

                    echo    "<div class='container my-4'>
                                <div class='row'>
                                    <div class='col-md-6 offset-md-3'>
                                        <table class='table table-hover table-bordered'>
                                            <thead class='table-primary'>
                                                <tr>
                                                    <th scope='col'>#</th>
                                                    <th scope='col'>Hour</th>
                                                    <th scope='col'>Energy (kWh)</th>
                                                    <th scope='col'>Total (RM)</th>
                                                </tr>
                                            </thead>
                                            <tbody>";
                    
                    for($hour = 1; $hour <= 24; $hour++){ //iterate the function of charge calculator to calculate for every hour
                        $result = chargeCalculator($voltage, $current, $cRate, $hour);
                        echo    "           <tr>
                                                <td scope='row'>".$hour."</td>
                                                <td>".$hour."</td>
                                                <td>".$result['energy']."</td>
                                                <td>".$result['total']."</td>
                                            </tr>";
                    }
                    echo    "               </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>";
                }
            ?>
        </div> 
        <script src="js/bootstrap.bundle.js"></script>
    </body>
</html>
