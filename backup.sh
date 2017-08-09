#!/bin/bash                                                                                          
echo 'backup seotoaster database'
cd `dirname "$0"`
mysqldump -uroot -p`windossa5one` seotoaster  > db_backup/seotoaster.sql

readcmd() {
    for var in $*
    do
        i=`expr $i + 1`
    done
}

message="`date` $*"



## create and push Commit                                                                            

git add .
git reset -- cache
git commit -m "$message"
git push

