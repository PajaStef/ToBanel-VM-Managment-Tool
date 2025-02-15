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
    <script>
        async function updateConfig() {
            const vmid = document.getElementById('vmid').value;
            if (!vmid) return;
            fetch(`get_vm_config.php?vmid=${vmid}`)
                .then(response => response.json())
                .then(config => {
                    if (config.error) {
                        alert(config.error);
                        return;
                    }
                    document.getElementById('cores').value = config.cores;
                    document.getElementById('ram').value = config.memory;
                })
                .catch(error => {
                    console.error('Error fetching VM config:', error);
                    alert('Failed to fetch VM configuration.');
                });
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>ToBanel - Upgrade an Existing VM</h1>
        <form action="upgrade_vm_handler.php" method="POST" class="form-group">
            <label for="vmid">Select VM:</label>
            <select id="vmid" name="vmid" class="form-control" onchange="updateConfig()" required>
                <option value="">Select a VM</option>
                <!-- populating the dropdown -->
                <?php
                $vms = json_decode(shell_exec("curl -s --insecure -H 'Authorization: PVEAPIToken=yourtoken' https://proxmox_ip:8006/api2/json/nodes/your_node/qemu"), true);
                if ($vms && isset($vms['data'])) {
                    $vm_data = $vms['data'];
                    usort($vm_data, function ($a, $b) {
                        return strcmp($a['name'], $b['name']);
                    });
                    foreach ($vm_data as $vm) {
                        echo "<option value='{$vm['vmid']}'>{$vm['name']} (ID: {$vm['vmid']})</option>";
                    }
                }
                ?>
            </select><br>
            <label for="cores">Number of Cores:</label>
            <input type="number" id="cores" name="cores" class="form-control" required><br>
            <label for="ram">RAM (in MB):</label>
            <input type="number" id="ram" name="ram" class="form-control" required><br>
            <input type="submit" value="Upgrade VM" class="btn btn-primary">
        </form>
        <nav>
            <a href="http://ip_of_panel_server/vm_control.php" class="btn btn-secondary">VM Control Page</a>
            <a href="http://ip_of_panel_server/" class="btn btn-secondary">Creation Page</a>
        </nav>
    </div>
</body>
</html>
