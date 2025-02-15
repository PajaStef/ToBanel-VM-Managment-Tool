<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToBanel</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ced4da;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            margin-top: 15px;
        }
        nav {
            text-align: center;
            margin-top: 20px;
        }
        nav a {
            margin: 0 10px;
            text-decoration: none;
            color: #007bff;
        }
        nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ToBanel - VM Creation Pag</h1>
        <form action="create_vm.php" method="POST" class="form-group">
            <label for="template">Select Template:</label>
            <select id="template" name="template" class="form-control" required>
                <option value="">Select a template</option>
                <?php
                $templates = [
                    ["id" => "104", "name" => "Web Server Template"], // add your templates here by setting the proxmox vm id of the template and setting a name for it
                    ["id" => "123", "name" => "Ubuntu Template"]
                ];
                foreach ($templates as $template) {
                    echo "<option value='{$template['id']}'>{$template['name']}</option>";
                }
                ?>
            </select><br>
            <label for="vmname">VM Name:</label>
            <input type="text" id="vmname" name="vmname" class="form-control" required><br>
            <label for="specs">Select Specs:</label>
            <select id="specs" name="specs" class="form-control" required>
                <option value="2_2048">2 Cores, 2 GB RAM</option>  <!-- in the value 2_2048 2-number of cores 2048-mb of ram -->
                <option value="4_4096">4 Cores, 4 GB RAM</option>
            </select><br>
            <input type="submit" value="Create VM" class="btn btn-primary">
        </form>
        <nav>
            <a href="http://ip_of_the_panel_server/upgrade.php" class="btn btn-secondary">Upgrade Page</a>
            <a href="http://ip_of_the_panel_server/vm_control.php" class="btn btn-secondary">VM Control Page</a>
        </nav>
    </div>
</body>
</html>
