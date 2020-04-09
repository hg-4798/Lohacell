#!/bin/sh

DIR=`dirname $0`;

pushd $DIR > /dev/null

./cr_auto_day.php memeber_sleep >& $DIR/log/cr_auto_day_member_sleep_`date +%Y%m%d%H%M%S`.log
./cr_auto_day.php member_sleep_noti >& $DIR/log/cr_auto_day_member_sleep_noti_`date +%Y%m%d%H%M%S`.log

popd > /dev/null
