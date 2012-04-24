#!/bin/bash

usage() {
    echo "usage: $0 <-d database> <-t table> <-i type> [-u username] [-p password]  [-r retention] [-l lookahead] [-h] [-s]"
    echo ""
    echo "    -d database"
    echo "           Database name where partitioned table resides."
    echo ""
    echo "    -t table"
    echo "           Table to update the partitions on."
    echo ""
    echo "    -i type"
    echo "           The type of partition increment. Valid values are \"day\" or \"month\""
    echo ""
    echo "    -r retention"
    echo "           How many old partitions to keep.  If this is not specified, no cleanup"
    echo "           operations are performed."
    echo ""
    echo "    -l lookahead"
    echo "           How many new partitions to create relative to today.  If this is not specified,"
    echo "           only the next partition relative to today will be created."
    echo ""
    echo "    -u username"
    echo "           MySQL username"
    echo ""
    echo "    -p password"
    echo "           MySQL password"
    echo ""
    echo "    -s"
    echo "           Do not actually perform the changes, only show the queries that would be performed."
    echo ""
    echo "    -h"
    echo "           Display this help screen"
}

# Variable initializers
DATABASE=""
TABLE=""
TYPE=""
RETENTION=0
LOOKAHEAD=0
SIMULATE=0
MYSQL_USER=""
MYSQL_PASSWORD=""

# Parse command line options
while getopts "d:t:i:r:l:u:p:hs" options; do
    case $options in
        d ) DATABASE=${OPTARG}
            ;;
        t ) TABLE=${OPTARG}
            ;;
        i ) TYPE=`echo ${OPTARG} | tr "[:upper:]" "[:lower:]"`
            ;;
        r ) RETENTION=${OPTARG}
            ;;
        l ) LOOKAHEAD=${OPTARG}
            ;;
        u ) MYSQL_USER=${OPTARG}
            ;;
        p ) MYSQL_PASSWORD=${OPTARG}
            ;;
        s ) SIMULATE=1
            ;;
        h ) usage
            exit 3
            ;;
    esac
done

# Verify input
if [ "$DATABASE" == "" ] || [ "$TABLE" == "" ]; then
    usage
    exit 1
fi

# Build MySQL commandline
MYSQL="mysql"
if [ "$MYSQL_USER" != "" ]; then
    MYSQL="$MYSQL -u $MYSQL_USER"
fi
if [ "$MYSQL_PASSWORD" != "" ]; then
    MYSQL="$MYSQL -p$MYSQL_PASSWORD"
fi

# Create daily partitions
if [ "$TYPE" == "day" ]; then

    # Prune old partitions
    if [ $RETENTION -gt 0 ]; then
        OLD_PARTITIONS=`$MYSQL -NBe "SELECT partition_name FROM information_schema.partitions WHERE table_schema='$DATABASE' AND table_name='$TABLE' AND partition_description < \`date --date \"$RETENTION days ago 00:00:00\" +%s\` AND partition_description != 'MAXVALUE' ORDER BY partition_ordinal_position ASC;"`
        for PARTITION in $OLD_PARTITIONS; do
            SQL="ALTER TABLE \`$DATABASE\`.\`$TABLE\` DROP PARTITION $PARTITION;"
            if [ $SIMULATE -eq 1 ]; then
                echo $SQL
            else
                $MYSQL -e "$SQL"
            fi
        done
    fi

    # Calculate partition ranges
    LAST_PARTITION_RANGE=`$MYSQL -NBe "SELECT partition_description FROM information_schema.partitions WHERE table_schema='$DATABASE' AND table_name='$TABLE' AND partition_description != 'MAXVALUE' ORDER BY partition_ordinal_position DESC LIMIT 1;"`
    NEXT_PARTITION_RANGE=`date --date "\`date --date \"1970-01-01 $(($LAST_PARTITION_RANGE + 86400)) sec\" \"+%Y-%m-%d 00:00:00\"\`" +%s`
    END_PARTITION_RANGE=`date --date "$(($LOOKAHEAD + 2)) days 00:00:00" +%s`

    MAXVALUE=`$MYSQL -NBe "SELECT partition_description FROM information_schema.partitions WHERE table_schema='$DATABASE' AND table_name='$TABLE' AND partition_description = 'MAXVALUE' LIMIT 1;"`
    if [ "$MAXVALUE" == "MAXVALUE" ]; then

            # The last partition is a "cathcall" that needs to be modified
            if [ $LAST_PARTITION_RANGE -lt $END_PARTITION_RANGE ]; then
                MODIFY_PARTITION=`$MYSQL -NBe "SELECT partition_name FROM information_schema.partitions WHERE table_schema='$DATABASE' AND table_name='$TABLE' ORDER BY partition_ordinal_position DESC LIMIT 1;"`

                # Prepare SQL Statement
                SQL="ALTER TABLE \`$DATABASE\`.\`$TABLE\` REORGANIZE PARTITION $MODIFY_PARTITION INTO ("
                for TIMESTAMP in `seq $NEXT_PARTITION_RANGE 86400 $END_PARTITION_RANGE`; do
                    SQL="$SQL PARTITION p`date --date "1970-01-01 $(($TIMESTAMP - 86400)) sec" +%Y%m%d` VALUES LESS THAN ($TIMESTAMP),"
                done
                SQL="$SQL PARTITION $MODIFY_PARTITION VALUES LESS THAN MAXVALUE);"

                # Execute SQL Statement
                if [ $SIMULATE -eq 1 ]; then
                    echo $SQL
                else
                    $MYSQL -e "$SQL"
                fi
            fi

        else

            # We need to add partitions after the last partition
            if [ $LAST_PARTITION_RANGE -lt $END_PARTITION_RANGE ]; then

                # Prepare SQL Statement
                SQL="ALTER TABLE \`$DATABASE\`.\`$TABLE\` ADD PARTITION ("
                for TIMESTAMP in `seq $NEXT_PARTITION_RANGE 86400 $END_PARTITION_RANGE`; do
                    SQL="$SQL PARTITION p`date --date "1970-01-01 $(($TIMESTAMP - 86400)) sec" +%Y%m%d` VALUES LESS THAN ($TIMESTAMP)"
                    if [ $TIMESTAMP -ne $END_PARTITION_RANGE ]; then
                        SQL="$SQL,"
                    fi
                done
                SQL="$SQL );"

                # Execute SQL Statement
                if [ $SIMULATE -eq 1 ]; then
                    echo $SQL
                else
                    $MYSQL -e "$SQL"
                fi
            fi
   fi


# Create monthly partitions
elif [ "$TYPE" == "month" ]; then

    # Prune old partitions
    if [ $RETENTION -gt 0 ]; then
        OLD_DATE=`date --date "\`date --date \"$(($RETENTION - 1))months ago\" \"+%Y-%m-01 00:00:00\"\`" +%s`
        OLD_PARTITIONS=`$MYSQL -NBe "SELECT partition_name FROM information_schema.partitions WHERE table_schema='$DATABASE' AND table_name='$TABLE' AND partition_description < $OLD_DATE AND partition_description != 'MAXVALUE' ORDER BY partition_ordinal_position ASC;"`
        for PARTITION in $OLD_PARTITIONS; do
            SQL="ALTER TABLE \`$DATABASE\`.\`$TABLE\` DROP PARTITION $PARTITION;"
            if [ $SIMULATE -eq 1 ]; then
                echo $SQL
            else
                $MYSQL -e "$SQL"
            fi
        done
    fi

    # Calculate partition ranges
    LAST_PARTITION_RANGE=`$MYSQL -NBe "SELECT partition_description FROM information_schema.partitions WHERE table_schema='$DATABASE' AND table_name='$TABLE' AND partition_description != 'MAXVALUE' ORDER BY partition_ordinal_position DESC LIMIT 1;"`
    NEXT_PARTITION_RANGE=`date --date "\`date --date \"1970-01-01 $LAST_PARTITION_RANGE sec\" \"+%Y-%m-01 1 month\"\`" +%s`
    END_PARTITION_RANGE=`date --date "\`date --date \"$(($LOOKAHEAD + 1)) months\" \"+%Y-%m-01 00:00:00\"\`" +%s`

    MAXVALUE=`$MYSQL -NBe "SELECT partition_description FROM information_schema.partitions WHERE table_schema='$DATABASE' AND table_name='$TABLE' AND partition_description = 'MAXVALUE' LIMIT 1;"`
    if [ "$MAXVALUE" == "MAXVALUE" ]; then

            # The last partition is a "cathcall" that we need to modify
            if [ $NEXT_PARTITION_RANGE -le $END_PARTITION_RANGE ]; then
                MODIFY_PARTITION=`$MYSQL -NBe "SELECT partition_name FROM information_schema.partitions WHERE table_schema='$DATABASE' AND table_name='$TABLE' ORDER BY partition_ordinal_position DESC LIMIT 1;"`

                # Prepare SQL Statement
                SQL="ALTER TABLE \`$DATABASE\`.\`$TABLE\` REORGANIZE PARTITION $MODIFY_PARTITION INTO ("
                while [ $NEXT_PARTITION_RANGE -le $END_PARTITION_RANGE ]; do
                    SQL="$SQL PARTITION p`date --date "1970-01-01 $LAST_PARTITION_RANGE sec" +%Y%m` VALUES LESS THAN ($NEXT_PARTITION_RANGE),"
                    LAST_PARTITION_RANGE=`date --date "\`date --date \"1970-01-01 $LAST_PARTITION_RANGE sec\" \"+%Y-%m-01 1 month\"\`" +%s`
                    NEXT_PARTITION_RANGE=`date --date "\`date --date \"1970-01-01 $LAST_PARTITION_RANGE sec\" \"+%Y-%m-01 1 month\"\`" +%s`
                done
                SQL="$SQL PARTITION $MODIFY_PARTITION VALUES LESS THAN MAXVALUE);"

                # Execute SQL Statement
                if [ $SIMULATE -eq 1 ]; then
                    echo $SQL
                else
                    $MYSQL -e "$SQL"
                fi
            fi

        else

            # We need to add partitions after the last partition
            if [ $NEXT_PARTITION_RANGE -le $END_PARTITION_RANGE ]; then

                # Prepare SQL Statement
                SQL="ALTER TABLE \`$DATABASE\`.\`$TABLE\` ADD PARTITION ("
                while [ $NEXT_PARTITION_RANGE -le $END_PARTITION_RANGE ]; do
                    SQL="$SQL PARTITION p`date --date "1970-01-01 $LAST_PARTITION_RANGE sec" +%Y%m` VALUES LESS THAN ($NEXT_PARTITION_RANGE)"
                    LAST_PARTITION_RANGE=`date --date "\`date --date \"1970-01-01 $LAST_PARTITION_RANGE sec\" \"+%Y-%m-01 1 month\"\`" +%s`
                    NEXT_PARTITION_RANGE=`date --date "\`date --date \"1970-01-01 $LAST_PARTITION_RANGE sec\" \"+%Y-%m-01 1 month\"\`" +%s`
                    if [ $NEXT_PARTITION_RANGE -le $END_PARTITION_RANGE ]; then
                        SQL="$SQL,"
                    fi
                done
                SQL="$SQL );"

                # Execute SQL Statement
                if [ $SIMULATE -eq 1 ]; then
                    echo $SQL
                else
                    $MYSQL -e "$SQL"
                fi
            fi

       fi

# Error
else
    usage
    exit 1
fi
