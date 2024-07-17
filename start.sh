#!/bin/bash

#grab new vmid
vmid=$(curl -s --insecure -H 'Authorization: PVEAPIToken=token' https://proxmox_ip:8006/api2/json/nodes/your_node/qemu/ | jq -r '.data[].vmid' | sort -n | tail -1)
#echo "vm id: $vmid"
#specifying variables
newvmid=$(($vmid+1))
#debug
#getting info from PHP script
template=$1
vmname=$2
cores=$3
mem=$4

#cloning
clone_response=$(curl --insecure -X POST -H 'Authorization: PVEAPIToken=token' -d "newid=$newvmid&name=$vmname&full=1" https://proxmox_ip:8006/api2/json/nodes/your_node/qemu/$template/clone)
#debug
#echo "Clone response: $clone_response"

if [ $? -eq 0 ]; then
    echo "Creation of a VM is successful"
else
    echo "The creation failed with exit status $?."
fi

sleep 20

#OPTIONAL: giving it a tag
#$(curl --insecure -X POST -H 'Authorization: PVEAPIToken=token' -d "tags=tag_name" -s -o /dev/null https://proxmox_ip:8006/api2/json/nodes/your_node/qemu/$newvmid/config)

#configuring the machine(cores and mem)

$(curl -s --insecure -X POST -H 'Authorization: PVEAPIToken=token' -d "cores=$cores&memory=$mem" https://proxmox_ip:8006/api2/json/nodes/your_node/qemu/$newvmid/config > /dev/null)

#starting the vm
$(curl --insecure -X POST -H 'Authorization: PVEAPIToken=token' -s -o /dev/null https://proxmox_ip:8006/api2/json/nodes/your_node/qemu/$newvmid/status/start)



if [ $? -eq 0 ]; then
    echo "VM Started!"
else
    echo "The starting failed with exit status $?."
fi

sleep 20

#getting the ip address
json_ip=$(curl -s --insecure -H 'Authorization: PVEAPIToken=token' https://proxmox_ip:8006/api2/json/nodes/your_node/qemu/$newvmid/agent/network-get-interfaces | jq -r '.data.result[]."ip-addresses"[] | select(.["ip-address-type"] == "ipv4" and .["ip-address"] != "127.0.0.1") | .["ip-address"]')

echo "VM is ready, the IP of the VM is: $json_ip"
echo "SSH details:"
echo "Command: ssh user@$json_ip"

if [ "$template" == "vmid_of_your_template" ]; then
	echo "Password is: password for the template"
else
	echo "Password is: password of the other template"
fi

