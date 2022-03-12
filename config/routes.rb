Rails.application.routes.draw do
  SECRET_KEY = Rails.application.secrets.secret_key_base. to_s

  resources :users, param: :_username
  post '/login', to: 'users#login'
  get '/auto_login', to: 'users#auto_login'

  namespace :api do
    namespace :v1 do
      resources :identies
      resources :references
    end
  end
  

  root "home#index"
end
