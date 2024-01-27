<?php
        
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $voltage = $_POST['voltage'];
            $current = $_POST['current'];
        }

        function chargeCalculator($voltage, $current){ //function for calculate charge of electric consumption - tnb
        
            $power = $voltage * $current; //calculating Power (Watt)
                
            $energy = $power * 744 / 1000; //calculating Energy (kWh)
            $energyForDisplay = $energy;
                
            $tariffRates = [        //defining the range of tariff in kWh and respective rate
                ['range' => [901, PHP_INT_MAX], 'rate' => 57.10],
                ['range' => [601, 900], 'rate' => 54.60],
                ['range' => [301, 600], 'rate' => 51.60],
                ['range' => [201, 300], 'rate' => 33.40],
                ['range' => [1, 200], 'rate' => 21.80],
            ];
                
            $totalCharge = 0;

            foreach ($tariffRates as $rate) {           //check one by one tariff rate
                $difference = 0;                        //every iteration, set it to 0 first
                list($start, $end) = $rate['range'];    //define start range and end range for the test tariff
                if($energy >= $start){                  // if energy is bigger or equal than tariff, 
                    $difference = $energy - $start +1;  //find the difference between energy and tariff threshold so we can calculate price and minus it afterward
                    $totalCharge += $difference * $rate['rate'];  
                    $energy -= $difference;             //deduct energy that we have calculate before
                }
            }

            $totalCharge = round($totalCharge/100,2); //convert cent to RM

            if($totalCharge < 3){
                $totalCharge = 3;
            }

            return [ 
                'power' => $power,
                'energy' => $energyForDisplay,
                'totalCharge' => $totalCharge,
            ];

        }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TNB Charge Calculator</title>
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
                    <h1>TNB Charge Calculator</h1>
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
                        <div class="d-grid gap-2">
                            <button type="submit" name="submit" class="btn btn-primary p-2">Calculate</button>
                            <a href="<?php echo $_SERVER["PHP_SELF"]; ?>" class="btn btn-secondary p-2">Clear</a>
                            <a href="eConsumptionChargeCalculator.php" class="btn btn-secondary p-2">E-Consumption Charge Calculator</a>
                        </div>
                    </form>
                </div>
            </div>
            <?php 
                if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){ //this if statement to ensure that only when form is submitted this section is display

                    $result = chargeCalculator($voltage, $current);

                    echo    "<div class='container my-4'>
                                <div class='row'>
                                    <div class='col-md-6 offset-md-3'>
                                        <div class='card text-center shadow p-3 bg-white rounded'>
                                            <h5>Energy consumption Per Month : ".$result['energy']." kWh</h5>
                                            <h5>Total Rate Per Month : RM ".$result['totalCharge']."</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                }
            ?>
        </div> 
        <script src="js/bootstrap.bundle.js"></script>
    </body>
</html>
