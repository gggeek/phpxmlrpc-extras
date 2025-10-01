#!/bin/sh

# Has to be run as admin

set -e

echo "Installing base software packages..."

#   @todo make updating of preinstalled sw optional, so that f.e. we can have faster builds as part of CI

export DEBIAN_FRONTEND=noninteractive

apt-get update

apt-get upgrade -y

apt-get install -y \
    git sudo unzip wget

echo "Done installing base software packages"
