<?php
if (isset($_GET['vmid'])) {
    $vmid = $_GET['vmid'];
    $response = shell_exec("curl --insecure -H 'Authorization: PVEAPIToken=token' https://proxmox_ip:8006/api2/json/nodes/your_node/qemu/$vmid/status/current");
    $data = json_decode($response, true);

    $response = trim($response);
    $response = preg_replace('/^Array/', '', $response);
    $response = trim($response);

    if (json_last_error() === JSON_ERROR_NONE) {
        if (isset($data['data']['status'])) {
            echo json_encode(["status" => $data['data']['status']]);
        } else {
            echo json_encode(["status" => "Unknown"]);
        }
    } else {
        echo json_encode(["status" => "Invalid response from Proxmox API"]);
    }
} else {
    echo json_encode(["status" => "Invalid VM ID"]);
}
?>

