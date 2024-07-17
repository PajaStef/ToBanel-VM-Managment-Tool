<?php
if (isset($_POST['action']) && isset($_POST['vmid'])) {
    $action = $_POST['action'];
    $vmid = $_POST['vmid'];

    // Define the shell script paths, the paths are for scripts that start, stop or reboot the vm
    $scripts = [
        "start" => "/var/www/html/start_vm.sh",
        "stop" => "/var/www/html/stop_vm.sh",
        "reboot" => "/var/www/html/reboot_vm.sh"
    ];

    if (array_key_exists($action, $scripts)) {
        $script = $scripts[$action];
	$command = "$script $vmid";

	#echo $command;

        // Log the command being executed
        error_log("Executing command: $command");

        // Execute the shell script
        $output = shell_exec($command);
        echo "<pre>$output</pre>";
    } else {
        echo "Invalid action.";
    }
} else {
    echo "Missing parameters.";
}
?>

