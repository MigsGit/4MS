#!/bin/bash

#----------------------------------------
#DATETIME
#----------------------------------------
YEAR=$(date +%Y)
MONTH=$(date +%m)
DAY=$(date +%d)
HOUR=$(date +%H)
MIN=$(date +%M)
SEC=$(date +%S)
FILE_NAME="$YEAR$MONTH$DAY"
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
PATH_BACKUP='/rapidx/AM'

#Create Folder to Other location
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP; mkdir $FILE_NAME"

#Generate/export mysql backup from Database to local path
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_aidrc_v2 > db_aidrc_v2.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_bbtfs > db_bbtfs.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_cash_advance > db_cash_advance.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_cn_ppts > db_cn_ppts.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_customer_claim > db_customer_claim.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_dmr_pqc > db_dmr_pqc.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_dstlms > db_dstlms.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_fas_jo_request > db_fas_jo_request.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_grinding_inventory > db_grinding_inventory.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_ilqcm > db_ilqcm.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_jo_request_v3 > db_jo_request_v3.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_jsox > db_jsox.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_kfoms > db_kfoms.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_ohswp > db_ohswp.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_online_it_library > db_online_it_library.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_paper_consumption > db_paper_consumption.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_pats_cn > db_pats_cn.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_pats_ts > db_pats_ts.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_pats_ts_maverick > db_pats_ts_maverick.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_pats_ts_ppts > db_pats_ts_ppts.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_pats_ts_ppts_maverick > db_pats_ts_ppts_maverick.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_pmcsfes > db_pmcsfes.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_pms > db_pms.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_pps_ims > db_pps_ims.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_preshipment > db_preshipment.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_qc_patrol > db_qc_patrol.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_rapidx > db_rapidx.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_recall_exam > db_recall_exam.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_shuttle_allocation > db_shuttle_allocation.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_statusboard > db_statusboard.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_ts_ppts > db_ts_ppts.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_ts_ppts_cn_ppts > db_ts_ppts_cn_ppts.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_ts_pts > db_ts_pts.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_wbs_print > db_wbs_print.sql
sudo sqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_pats_ppd_cn > db_pats_ppd_cn.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_arrs > db_arrs.sql

sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_invoice_revision_request > db_invoice_revision_request.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_cn_ppts > db_cn_ppts_v2.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_fetls > db_fetls.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_iqc_inspection_system > db_iqc_inspection_system.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_iss_service_request > db_iss_service_request.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_pats_cn_automotive > db_pats_cn_automotive.sql
sudo mysqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_pats_cn_v2 > db_pats_cn_v2.sql
sudo sqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_pats_ppd > db_pats_ppd.sql
sudo sqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_pats_ppd > db_pats_ppd_cn_f3.sql
sudo sqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_pmcsfes_v2 > db_pmcsfes_v2.sql
sudo sqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_walk_in > db_walk_in.sql
sudo sqldump -h$DB_ADDRESS -u$DB_USER -p$DB_PASSWORD -e db_rfq_v4 > db_rfq_v4.sql







#Copy the gerated DB from localhost to other location
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_aidrc_v2.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_bbtfs.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_cash_advance.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_cn_ppts.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_customer_claim.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_dmr_pqc.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_dstlms.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_fac_etls.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_grinding_inventory.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_ilqcm.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_jo_request_v3.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_jsox.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_kfoms.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_ohswp.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_online_it_library.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_paper_consumption.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_pats_cn.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_pats_ts.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_pats_ts_maverick.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_pats_ts_ppts.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_pats_ts_ppts_maverick.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_pmcsfes.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_pms.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_pps_ims.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_preshipment.sql"


smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_invoice_revision_request.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_cn_ppts.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_fetls.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_iqc_inspection_system.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_iss_service_request.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_pats_cn_automotive.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_pats_cn_v2.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_pats_ppd.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_pats_ppd_cn_f3.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_pmcsfes_v2.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_walk_in.sql"
smbclient $ADDRESS -U $DOMAIN/$USER%$PASSWORD -c "cd $PATH_BACKUP/$FILE_NAME; put db_rfq_v4.sql"





#Remove SQL to local path NOTE: to keep the sql updated copy
rm db_aidrc_2.sql
rm db_bbtfs.sql
rm db_cash_advance.sql
rm db_cn_ppts.sql
rm db_customer_claim.sql
rm db_dmr_pqc.sql
rm db_dstlms.sql
rm db_fac_etls.sql
rm db_grinding_inventory.sql
rm db_ilqcm.sql
rm db_jo_request_v2.sql
rm db_jsox.sql
rm db_kfoms.sql
rm db_ohswp.sql
rm db_online_it_library.sql
rm db_paper_consumption.sql
rm db_pats_cn.sql
rm db_pats_ts.sql
rm db_pats_ts_maverick.sql
rm db_pats_ts_ppts_maverick.sql
rm db_pats_ts_ppts.sql
rm db_pmcsfes.sql
rm db_pms.sql
rm db_pps_ims.sql
rm db_preshipment.sql
rm db_qc_patrol.sql
rm db_rapidx.sql
rm db_recall_exam.sql
rm db_shuttle_allocation.sql
rm db_statusboard.sql
rm db_ts_ppts.sql
rm db_ts_ppts_cn_ppts.sql
rm db_ts_pts.sql
rm db_wbs_print.sql
rm db_pats_ppd_cn.sql
rm db_arrs.sql

rm db_invoice_revision_request.sql
rm db_cn_ppts.sql
rm db_fetls.sql
rm db_iqc_inspection_system.sql
rm db_iss_service_request.sql
rm db_pats_cn_automotive.sql
rm db_pats_cn_v2.sql
rm db_pats_ppd.sql
rm db_pats_ppd_cn_f3.sql
rm db_pmcsfes_v2.sql
rm db_walk_in.sql
rm db_rfq_v4.sql


