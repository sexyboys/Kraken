set :domain, "88.191.142.160"
set :application, "Meet Darkness"
set :deploy_to, "/home/erik/sites/meetdarkness"
set :app_path, "app"

set :repository,  "file:///home/erik/repositories/darkmeet.git"
set :local_repository, "88.191.142.160:/home/erik/repositories/darkmeet.git"
set :scm, :git
set :model_manager, "doctrine"

set :keep_releases, 3

role :web, domain                          # Your HTTP server, Apache/etc
role :app, domain                          # This may be the same as your `Web` server
role :db,  domain, :primary => true # This is where Rails migrations will run

logger.level = Logger::MAX_LEVEL


set :use_composer, true

# if you want to clean up old releases on each deploy uncomment this:
# after "deploy:restart", "deploy:cleanup"

# if you're still using the script/reaper helper you will need
# these http://github.com/rails/irs_process_scripts

# If you are using Passenger mod_rails uncomment this:
# namespace :deploy do
#   task :start do ; end
#   task :stop do ; end
#   task :restart, :roles => :app, :except => { :no_release => true } do
#     run "#{try_sudo} touch #{File.join(current_path,'tmp','restart.txt')}"
#   end
# end
