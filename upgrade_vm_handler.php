<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vmid = escapeshellarg($_POST["vmid"]);
    $cores = escapeshellarg($_POST["cores"]);
    $ram = escapeshellarg($_POST["ram"]);
    
    // Path to bash that upgrades the vm script
    $script = '/var/www/html/upgrade_vm.sh';  // Adjust the path as necessary

    // Ensure the script has execute permissions
    chmod($script, 0755);

    // Execute the bash script with the provided parameters
    $command = "$script $vmid $cores $ram";
    $output = shell_exec($command);
    echo "<h1>VM Upgrade Status</h1>";
    echo "<pre>$output</pre>";
} else {
    echo "Invalid request method.";
}
?>

