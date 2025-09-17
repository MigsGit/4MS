
^#!/bin/bash
#----------------------------------------
# LOCAL CONFIG
#----------------------------------------
DB_ADDRESS='rapidx'
DB_USER='rapidx'       # MySQL User
DB_PASSWORD='Ne0nFutur3*' # MySQL Password
#----------------------------------------
# OTHER LOCATION CONFIG
#----------------------------------------
ADDRESS='//pmips02/PMIDatabase_Backup'
DOMAIN='PRICON'
USER='iss.software'       # Computer Username
PASSWORD='S0ftw@r32023*' # Computer Password
AM_PATH_BACKUP='/rapidx/HELLO'

# Read all folders
FOLDERS=$(smbclient $ADDRESS -U "$DOMAIN/$USER%$PASSWORD" -c "cd $AM_PATH_BACKUP; dir" | awk '/D/ {print $1}')

#For loop all files of everybackup then delete it to remove the directory by Date
for folder in $FOLDERS; do
    echo "Deleting folder: $folder"
    smbclient $ADDRESS -U "$DOMAIN/$USER%$PASSWORD" -c "cd $AM_PATH_BACKUP/$folder; del *; cd ..; rmdir $folder"
done
