#!/bin/bash

vmid=$1

#echo "vm id: $vmid"

stop=$(curl --insecure -X POST -H "Authorization: PVEAPIToken=token" https://proxmox_ip:8006/api2/json/nodes/your_node/qemu/$vmid/status/shutdown)

echo "response: $stop"
