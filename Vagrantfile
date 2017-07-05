# -*- mode: ruby -*-
# vi: set ft=ruby :
Vagrant.require_version ">= 1.8.6"

VAGRANT_API_VERSION = "2"
GUEST_HOSTNAME = "testbox.dev"
GUEST_NETWORK_IP = "192.168.59.76"
GUEST_MEMORY_LIMIT = "1024"
GUEST_CPU_LIMIT = "1"

#########################################################
# You shouldn't have to modify anything below this line #
#########################################################

Vagrant.configure(VAGRANT_API_VERSION) do |config|

    config.vm.box = "bento/ubuntu-16.04"
    config.vm.hostname = GUEST_HOSTNAME
    config.vm.network "private_network", ip: GUEST_NETWORK_IP

    # Allow more memory usage for the VM
    config.vm.provider :virtualbox do |v|
        v.memory = GUEST_MEMORY_LIMIT
        v.cpus = GUEST_CPU_LIMIT
        v.name = GUEST_HOSTNAME
    end

    # forward agent for ansible access
    config.ssh.forward_agent = true

    config.vm.synced_folder "./", "/vagrant", type: "nfs"

    config.vm.provision "shell", inline: <<-SHELL
        apt-get update
        apt-get install -y -qq ansible git
        ssh -T git@github.com -o StrictHostKeyChecking=no
        PYTHONUNBUFFERED=1 ansible-pull \
            --url=git@github.com:formstack/server-playbooks-devtest.git \
            --inventory-file inventories/localhost \
            dev-standalone.yml
    SHELL

end
