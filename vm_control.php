<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToBanel</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // getting the VM status(on/off)
        async function updateVMStatus() {
            const vmid = document.getElementById('vmid').value;
            if (!vmid) return;

            console.log('Selected VM ID:', vmid);

            try {
                const response = await fetch(`get_vm_status.php?vmid=${vmid}`);
                const text = await response.text();
                console.log('Raw response:', text);

                const result = JSON.parse(text);
                console.log('Status response:', result);

                const status = result.status || "Unknown";
                document.getElementById('vm_status').innerText = `Status: ${status}`;
            } catch (error) {
                console.error('Error fetching VM status:', error);
                document.getElementById('vm_status').innerText = "Status: Error";
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const vmSelect = document.getElementById('vmid');
            vmSelect.addEventListener('change', updateVMStatus);
        });
    </script>
    <style>
        body {
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
        }
        .btn-group {
            margin-top: 20px;
        }
        #vm_status {
            font-weight: bold;
            margin-top: 20px;
        }
        nav a {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">ToBanel - VM Control Centre</h1>
        <form action="control_vm_handler.php" method="POST" class="form-group">
            <label for="vmid">Select VM:</label>
            <select id="vmid" name="vmid" class="form-control" required>
                <option value="">Select a VM</option>
                <!-- populating the dropdown alphabetically -->
                <?php
                $vms = json_decode(shell_exec("curl -s --insecure -H 'Authorization: PVEAPIToken=token' https://proxmox_ip:8006/api2/json/nodes/your_node/qemu"), true);
                if ($vms && isset($vms['data'])) {
                    $vm_data = $vms['data'];
                    usort($vm_data, function ($a, $b) {
                        return strcmp($a['name'], $b['name']);
                    });
                    foreach ($vm_data as $vm) {
                        echo "<option value='{$vm['vmid']}'>{$vm['name']} (ID: {$vm['vmid']})</option>";
                    }
                } else {
                    echo "<option value=''>No VMs available</option>";
                }
                ?>
            </select>
            <div class="btn-group" role="group">
                <button type="submit" name="action" value="start" class="btn btn-success">Start VM</button>
                <button type="submit" name="action" value="stop" class="btn btn-danger">Stop VM</button>
                <button type="submit" name="action" value="reboot" class="btn btn-warning">Reboot VM</button>
            </div>
        </form>
        <div id="vm_status" class="alert alert-info">Status: N/A</div>
        <nav>
            <a href="http://192.168.1.180/upgrade.php" class="btn btn-primary">Upgrade Page</a>
            <a href="http://192.168.1.180/" class="btn btn-secondary">Creation Page</a>
        </nav>
    </div>
</body>
</html>

