#!/bin/sh

    PHABRICATOR_START="/home/sun/phabricator/php/bin/php /home/sun/phabricator/apps/phabricator/htdocs/bin/phd start"
    PHABRICATOR_STOP="/home/sun/phabricator/php/bin/php /home/sun/phabricator/apps/phabricator/htdocs/bin/phd stop"
    PHABRICATOR_STATUS_CMD="/home/sun/phabricator/php/bin/php /home/sun/phabricator/apps/phabricator/htdocs/bin/phd status"

PHABRICATOR_STATUS=""
ERROR=0

is_service_running() {
    DEAD=`$PHABRICATOR_STATUS_CMD 2>&1`
    ERRORCODE=$?
    DEAD=`echo $DEAD | grep '<DEAD>' -c`
    if [ $ERRORCODE -eq 1 ] ; then
        RUNNING=0
    else
        if [ $DEAD -ne 0 ] ; then
            RUNNING=0
        else
            RUNNING=1
        fi
    fi

    return $RUNNING
}

is_phabricator_running() {
    is_service_running
    RUNNING=$?
    if [ $RUNNING -eq 0 ]; then
        PHABRICATOR_STATUS="Phabricator not running"
    else
        PHABRICATOR_STATUS="Phabricator already running"
    fi
    return $RUNNING
}

start_phabricator() {
    is_phabricator_running
    RUNNING=$?

    if [ $DEAD -ne 0 ] ; then
            if [ `id|sed -e s/uid=//g -e s/\(.*//g` -eq 0 ]; then
                su daemon -s /bin/sh -c "$PHABRICATOR_STOP > /dev/null 2>&1"
            else
                $PHABRICATOR_STOP > /dev/null 2>&1
            fi
    fi  


    if [ $RUNNING -eq 1  ]; then
        echo "$0 $ARG: Phabricator already running"
        exit
    else 

        if [ `id|sed -e s/uid=//g -e s/\(.*//g` -eq 0 ]; then
            su daemon -s /bin/sh -c "$PHABRICATOR_START > /dev/null 2>&1"
        else
            $PHABRICATOR_START > /dev/null 2>&1
        fi
    fi
    sleep 1
    is_phabricator_running
    RUNNING=$?
    if [ $RUNNING -eq 0 ]; then
        ERROR=1
    fi
    if [ $ERROR -eq 0 ]; then
	echo "$0 $ARG: Phabricator started"
    else
	echo "$0 $ARG: Phabricator could not be started"
	ERROR=3
    fi

}

stop_phabricator() {
    NO_EXIT_ON_ERROR=$1
    is_phabricator_running
    RUNNING=$?
    if [ $RUNNING -eq 0 -o $DEAD -ne 0 ]; then
        echo "$0 $ARG: $PHABRICATOR_STATUS"
        if [ "x$NO_EXIT_ON_ERROR" != "xno_exit" ]; then
            exit
        else
            return
        fi
    fi

    if [ `id|sed -e s/uid=//g -e s/\(.*//g` -eq 0 ]; then
        su daemon -s /bin/sh -c "$PHABRICATOR_STOP > /dev/null 2>&1"
    else
        $PHABRICATOR_STOP > /dev/null 2>&1
    fi

    is_phabricator_running
    RUNNING=$?
    if [ $RUNNING -eq 0 ]; then
            echo "$0 $ARG: Phabricator stopped"
        else
            echo "$0 $ARG: Phabricator could not be stopped"
            ERROR=4
    fi
}

if [ "x$1" = "xstart" ]; then
    start_phabricator
elif [ "x$1" = "xstop" ]; then
    stop_phabricator
elif [ "x$1" = "xstatus" ]; then
    is_phabricator_running
    echo "$PHABRICATOR_STATUS"
fi

exit $ERROR
