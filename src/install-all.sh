#!/bin/bash
install_by_step() {
    cd $1
    php /var/www/html/composer.phar install
    cd ..
}
cd $(cd $(dirname $0); pwd)
for i in `seq 1 15`; do
    install_by_step "step$i"
done

