#!/bin/bash

# Function: main
main_run () {
#  docker-compose rm -f -s

  read_domain

#  add_to_hosts_new_domain

#  copy_env_to_magento

#  bin/start

  read_mysql_config

#  load_dump_to_mysql

  change_base_url_in_magento_database

#  copy_all_files_to_container

#  build_magento

#  bin/static-compile

#  congratulations_install
}

copy_env_to_magento ()
{
  bin/copyenvtomagento
}

congratulations_install ()
{
  echo ""
  echo "-----------------------------------------------------------------"
  echo ""
  echo " Congratulations! https://$DOMAIN/"
  echo ""
}

# Function: read domain
read_domain ()
{
  printf "Enter WebSite local domain (without http or https, ex. m2.local)? -> "
  read DOMAIN
}

# Function: Read MySQL config
read_mysql_config()
{
  source Docker/db/db.env
}

# Function: check connection to mysql port
check_mysql_connection ()
{
  MYSQLCONNECTSTATUS="$(bin/cli nc -z -v "$MYSQL_HOST" "$MYSQL_PORT" 2>&1 | grep $MYSQL_PORT)"
}

# Function: Wait when mysql container open port
wait_mysql_connection ()
{
  while check_mysql_connection
  do
    if [[ "$MYSQLCONNECTSTATUS" == *"open"* ]];
    then
      echo 'MySQL READY'
      break
    else
      sleep 1
      echo 'Wait MySQL ... '
    fi
  done
}

# Function: Load mysql dump
load_dump_to_mysql ()
{
  DUMPFILE=$(find dump/ -type f -name "*.sql")
  if [ -f $DUMPFILE ]
  then
    wait_mysql_connection
    echo "Load dump ($DUMPFILE) ... "
    bin/mysql < $DUMPFILE
    echo "Load dump complete"
  fi
}

# Function: Add new domain to hosts
add_to_hosts_new_domain ()
{
  echo "Your system password has been requested to add an entry to /etc/hosts..."
  echo "127.0.0.1 ::1 $DOMAIN" | sudo tee -a /etc/hosts
}

# Function: change base_url in magento database
change_base_url_in_magento_database ()
{
  echo "Update url in magento database";
  bin/mysql -e "update $MYSQL_DATABASE.core_config_data set value = 'https://$DOMAIN' where path like 'web/secure/base_url' LIMIT 1"
  bin/mysql -e "update $MYSQL_DATABASE.core_config_data set value = 'http://$DOMAIN' where path like 'web/unsecure/base_url' LIMIT 1"
  echo "Fix themes titles in magento database";
  bin/mysql -e "update $MYSQL_DATABASE.theme set theme_title = ' - ' where theme_title = ''"
  echo "Fix elastic settings in magento database";
  bin/mysql -e "update $MYSQL_DATABASE.core_config_data set value = 'elasticsearch' where path like '%elastic%host%'"
  bin/mysql -e "update $MYSQL_DATABASE.core_config_data set value = 'elasticsearch' where path like '%es_client%host%'"
  bin/mysql -e "update $MYSQL_DATABASE.core_config_data set value = '9200' where path like '%elastic%port%'"
  bin/mysql -e "INSERT INTO $MYSQL_DATABASE.core_config_data SET scope = 'default', scope_id = 0, path = 'smile_elasticsuite_core_base_settings/es_client/servers', value = 'elasticsearch:9200'"
}

# Function: build magento
build_magento ()
{
  echo "Build magento ..."
  bin/magento setup:upgrade
  bin/magento index:reindex
  bin/magento cache:flush
}

# Function: copy all files to container
copy_all_files_to_container ()
{
  rm -rf src/vendor #Clear for step below
  bin/copytocontainer --all

  bin/clinotty chmod u+x bin/magento

  echo "Forcing reinstall of composer deps to ensure perms & reqs..."
  bin/composer install --ignore-platform-reqs

  bin/clinotty bin/magento setup:di:compile

  echo "Turning on developer mode.."
  bin/clinotty bin/magento deploy:mode:set developer

  echo "Forcing deploy of static content to speed up initial requests..."
  bin/clinotty bin/magento setup:static-content:deploy -f

  echo "Clearing the cache to apply updates..."
  bin/clinotty bin/magento cache:flush

  echo "Copying files from container to host after install..."
  bin/copyfromcontainer app
  bin/copyfromcontainer vendor

  echo "Generating SSL certificate..."
  bin/setup-ssl $DOMAIN

  # echo "Copy all files to container ... ";
  # find src -mindepth 1 -maxdepth 1 -exec bin/copytocontainersrc {} \;
  bin/fixowns
  bin/fixperms
}

main_run
