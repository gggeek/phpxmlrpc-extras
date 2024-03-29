#!/bin/sh

# Has to be run as admin

set -e

echo "Installing base software packages..."

apt-get update

DEBIAN_FRONTEND=noninteractive apt-get install -y \
    git sudo unzip wget

echo "Done installing base software packages"
