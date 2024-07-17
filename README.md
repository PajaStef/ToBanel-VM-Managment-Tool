Its just a cool project I made to test my skills. if you want full details go: https://www.pavlestefanovic.com/projects.html

To get this working, go into every file and replace place-holders: proxmox_ip(IP/Domain of your proxmox server) and token(the API token you have to access your Proxmox's API). Then go into index.php and change the $templates array to add all your templates that you setup on your proxmox.  Also make sure that your template machines have qemu guest agent setup so the scripts can retrive IPs of new machines.

Optional but you can change the values of the specs in index.php if you want diffrenet VM sizes other than the default.
