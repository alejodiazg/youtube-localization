# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  config.vm.box = "hashicorp/precise64"

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  config.vm.network "private_network", ip: "192.168.33.10"
  config.vm.hostname = "localizator.dev"
  if Vagrant::Util::Platform.windows?
    puts "Vagrant launched from windows. No hostupdate available"
  else
    puts "Vagrant launched from unix."
    config.hostsupdater.aliases = ["www.localizator.dev", "localizator.dev"]
    config.hostsupdater.remove_on_suspend = false
  end

  # Create a public network, which generally matched to bridged network.
  # Bridged networks make the machine appear as another physical device on
  # your network.
  # config.vm.network "public_network"
  #config.vm.synced_folder ".", "/var/www/localizator", type: "rsync", rsync__exclude: "node_modules/", mount_options: ["dmode=775,fmode=775"];

  if Vagrant::Util::Platform.windows?
    puts "Vagrant launched from windows. No rsync available using virtualbox for sync"
    config.vm.synced_folder ".", "/var/www/localizator", owner: "vagrant", group: "www-data", mount_options: ["dmode=775,fmode=664"];
  else
    puts "Vagrant launched. Using rsync for sync"
    config.vm.synced_folder ".", "/var/www/localizator", type: "rsync", rsync__exclude: "node_modules/", mount_options: ["dmode=775,fmode=775"];
  end


  # Provider-specific configuration so you can fine-tune various
  # backing providers for Vagrant. These expose provider-specific options.
  # Example for VirtualBox:
  #
  config.vm.provider "virtualbox" do |vb|
     vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
     vb.memory = "2048"
  end

  # Forward ssh keys
  config.ssh.forward_agent = true

  # Use same global gitconfig
  config.vm.provision "file", source: "~/.gitconfig", destination: ".gitconfig"

  config.vm.provision :shell, path: "bootstrap.sh"
  #config.vm.provision :shell, path: "bootstrap-mongo.sh"
end
