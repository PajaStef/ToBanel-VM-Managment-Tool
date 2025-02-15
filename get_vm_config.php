<?php
if (isset($_GET['vmid'])) {
    $vmid = escapeshellarg($_GET['vmid']);

    // Fetch the VM configuration from Proxmox API
    $config = json_decode(shell_exec("curl --insecure -H 'Authorization: PVEAPIToken=yourtoken' https://proxmox_ip:8006/api2/json/nodes/your_node/qemu/$vmid/config"), true);
    
    if ($config && isset($config['data'])) {
        // Extract cores and memory
        $cores = $config['data']['cores'];
        $memory = $config['data']['memory'];
        
        // Return the configuration as JSON
        echo json_encode(['cores' => $cores, 'memory' => $memory]);
    } else {
        echo json_encode(['error' => 'Unable to fetch VM configuration.']);
    }
} else {
    echo json_encode(['error' => 'VM ID not provided.']);
}
?>
