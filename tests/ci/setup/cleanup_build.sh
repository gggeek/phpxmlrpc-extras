#!/bin/sh

# @todo enable this when we are sure the commands below never fail
# set -e

# @todo any other known cache/log files which we could remove? Eg. /var/cache/*, /var/log/*, stuff in /tmp, /home/$user, ...

export DEBIAN_FRONTEND=noninteractive

apt-get -y autoremove
apt-get -y autoclean
apt-get -y clean
