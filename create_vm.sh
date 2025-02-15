#!/bin/bash

# Proxmox API credentials
PVE_TOKEN="PVEAPIToken=your_token"
PROXMOX_IP="your_proxmox_ip:8006"
NODE="proxmox" #set your node name you can see it on the proxmox web interface

# Grab new VM ID
vmid=$(curl -s --insecure -H "Authorization: $PVE_TOKEN" https://$PROXMOX_IP:8006/api2/json/nodes/$NODE/qemu/ | jq -r '.data[].vmid' | sort -n | tail -1)

# Specify variables
newvmid=$((vmid+1))
template=$1
vmname=$2
cores=$3
mem=$4

# Clone the VM
clone_response=$(curl --insecure -X POST -H "Authorization: $PVE_TOKEN" -d "newid=$newvmid&name=$vmname&full=1" https://$PROXMOX_IP:8006/api2/json/nodes/$NODE/qemu/$template/clone)

if [ $? -eq 0 ]; then
    echo "Creation of a VM is successful"
else
    echo "The creation failed with exit status $?."
fi

sleep 20

# OPTIONAL: Assigning a tag
#curl --insecure -X POST -H "Authorization: $PVE_TOKEN" -d "tags=tag_name" -s -o /dev/null https://$PROXMOX_IP:8006/api2/json/nodes/$NODE/qemu/$newvmid/config

# Configure the VM (cores and memory)
curl -s --insecure -X POST -H "Authorization: $PVE_TOKEN" -d "cores=$cores&memory=$mem" https://$PROXMOX_IP:8006/api2/json/nodes/$NODE/qemu/$newvmid/config > /dev/null

# Start the VM
curl --insecure -X POST -H "Authorization: $PVE_TOKEN" -s -o /dev/null https://$PROXMOX_IP:8006/api2/json/nodes/$NODE/qemu/$newvmid/status/start

if [ $? -eq 0 ]; then
    echo "VM Started!"
else
    echo "The starting failed with exit status $?."
fi

sleep 20

# Get the VM's IP address
json_ip=$(curl -s --insecure -H "Authorization: $PVE_TOKEN" https://$PROXMOX_IP:8006/api2/json/nodes/$NODE/qemu/$newvmid/agent/network-get-interfaces | jq -r '.data.result[]."ip-addresses"[] | select(.["ip-address-type"] == "ipv4" and .["ip-address"] != "127.0.0.1") | .["ip-address"]')

echo "VM is ready, the IP of the VM is: $json_ip"
echo "SSH details:"
echo "Command: ssh user@$json_ip"

if [ "$template" == "vmid_of_your_template" ]; then
    echo "Password is: password for the template"
else
    echo "Password is: password of the other template"
fi
