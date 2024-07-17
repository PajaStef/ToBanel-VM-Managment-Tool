<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $template = escapeshellarg($_POST["template"]);	
    $vmname = escapeshellarg($_POST["vmname"]);
    $specs = $_POST["specs"];

    list($cores, $ram) = explode('_', $specs);

    // Path to your bash script
    $script = '/var/www/html/start.sh'; //path to the script that creates the vm

    // Execute the bash script with the provided parameters
    $command = "bash $script $template $vmname $cores $ram";
    $output = shell_exec($command);

    echo "<h1>VM Creation Status</h1>";
    echo "<pre>$output</pre>";
} else {
    echo "Invalid request method.";
}
?>
