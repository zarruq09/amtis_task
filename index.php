<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Consumption Calculator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { padding: 20px; }
        .result-box { border: 1px solid #ccc; padding: 15px; margin-top: 20px; }
        .text-blue { color: #007bff; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4">Calculate</h2> 

    <form method="POST" action="">
        <div class="form-group">
            <label for="voltage">Voltage (V)</label>
            <input type="number" step="any" class="form-control" id="voltage" name="voltage" value="<?php echo isset($_POST['voltage']) ? $_POST['voltage'] : ''; ?>" placeholder="Eg. 240" required>
        </div>
        <div class="form-group">
            <label for="current">Current (A)</label>
            <input type="number" step="any" class="form-control" id="current" name="current" value="<?php echo isset($_POST['current']) ? $_POST['current'] : ''; ?>" placeholder="Eg. 10" required>
        </div>
        <div class="form-group">
            <label for="rate">Current Rate (sen/kWh)</label>
            <input type="number" step="any" class="form-control" id="rate" name="rate" value="<?php echo isset($_POST['rate']) ? $_POST['rate'] : ''; ?>" placeholder="Eg. 21.80" required>
        </div>
        <div class="text-center">
            <button type="submit" name="calculate" class="btn btn-primary">Calculate</button>
        </div>
    </form>

    <?php
    // Function to calculate Power (kW)
    function calculatePower($voltage, $current) {
        return ($voltage * $current) / 1000;
    }

    // Function to calculate Total Cost for a specific hour
    function calculateTotalCost($power_kw, $rate_rm, $hour) {
        $energy = $power_kw * $hour;
        $total_cost = $energy * $rate_rm;
        return [
            'energy' => $energy,
            'total' => $total_cost
        ];
    }

    if (isset($_POST['calculate'])) {
        $voltage = floatval($_POST['voltage']);
        $current = floatval($_POST['current']);
        $rate_sen = floatval($_POST['rate']);


        if ($voltage <= 0 || $current <= 0 || $rate_sen <= 0) {
            echo '<div class="alert alert-danger mt-4"><b>Error!</b> Voltage, current, and rate must be positive numbers.</div>';
        } else {
        
        // 1. Calculate Power using Function
        $power_kw = calculatePower($voltage, $current);

        // 2. Rate in RM = Rate (sen) / 100
        $rate_rm = $rate_sen / 100;
    ?>

    <div class="result-box">
        <div class="p-3 mb-3 text-primary" style="background-color: #e3f2fd; border: 1px solid #90caf9;">
            <p class="mb-1"><strong>POWER : </strong> <?php echo number_format($power_kw, 5); ?> kw</p>
            <p class="mb-0"><strong>RATE : </strong> <?php echo number_format($rate_rm, 3); ?> RM</p>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Hour</th>
                    <th>Energy (kWh)</th>
                    <th>TOTAL (RM)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop for 24 hours
                for ($hour = 1; $hour <= 24; $hour++) {
                    // Calculate Energy and Cost using Function
                    $result = calculateTotalCost($power_kw, $rate_rm, $hour);
                ?>
                <tr>
                    <td><?php echo $hour; ?></td>
                    <td><?php echo $hour; ?></td>
                    <td><?php echo number_format($result['energy'], 5); ?></td>
                    <td><?php echo number_format($result['total'], 2); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php 
        } 
    } 
    ?>
</div>

<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

</body>
</html>