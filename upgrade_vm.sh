#!/bin/bash

vmid=$1
cores=$2
ram=$3

#echo $vmid
#echo $cores
#echo $ram

config=$(curl -s --insecure -X POST -H 'Authorization: PVEAPIToken=token' -d "cores=$cores&memory=$ram" https://proxmox_ip:8006/api2/json/nodes/your_node/qemu/$vmid/config )
#echo "Config response: $config_response"

if [[ "$config" == *"errors"* ]]; then
    echo "The upgrade failed with the above error."
    exit 1
else
    echo "VM (ID: $vmid) successfully upgraded to $cores cores and $ram MB RAM."
fi

#echo "successful"
