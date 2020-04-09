#!/bin/sh

DIR=`dirname $0`;

pushd $DIR > /dev/null

./cr_send_grade_mail_v2.php >& $DIR/log/cr_send_grade_mail_v2_`date +%Y%m%d%H%M%S`.log

popd > /dev/null
