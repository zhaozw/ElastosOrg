RedmineApp::Application.routes.draw do
  match 'importer/:action', :controller => 'importer'
end
