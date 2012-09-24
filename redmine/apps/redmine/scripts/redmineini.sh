#!/bin/sh

. /opt/redmine/scripts/setenv.sh
cd /opt/redmine/apps/redmine/htdocs
bundle install --local --without development test postgresql sqlite
rake generate_secret_token
rake db:migrate RAILS_ENV="production"
echo en | rake redmine:load_default_data RAILS_ENV="production"
