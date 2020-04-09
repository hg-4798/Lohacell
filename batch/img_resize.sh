#!/bin/sh

DIR=`dirname $0`;

pushd $DIR > /dev/null

./img_resize.php >> $DIR/log/img_resize_`date +%Y%m%d`.log

popd > /dev/null
