Rails.application.routes.draw do
  devise_for :admins do
    get '/admins/sign_out' => 'devise/sessions#destroy'
  end
  # Define your application routes per the DSL in https://guides.rubyonrails.org/routing.html

  # Defines the root path route ("/")
  root "ditraheal/home#index"

  # resources :stack, only: ['index','show']

  # -- WEB SERVICE -- 
  SECRET_KEY = Rails.application.secrets.secret_key_base. to_s

  resources :users, param: :_username
  post '/login', to: 'users#login'
  post '/logout', to: 'users#logout'


  # get 'sign_in', to: 'sessions#new'
  # post 'sign_in', to: 'sessions#create', as: 'log_in'
  # delete 'logout', to: 'sessions#destroy'

  namespace :api do
    namespace :v1 do

      post "/pre_test/efikasi", to: "pre_test#effication_pre_test"
      get "/pre_test/efikasi", to: "pre_test#get_effication_pre_test"

      post "/pre_test/level_trauma", to: "pre_test#level_trauma_pre_test"

      post '/treatment', to: "treatment#periode"
      get '/treatment', to: "treatment#show_periode"

      # IDENTY 
      resources :identies, except: %i[create]
      scope 'identies' do
        post '/', to: 'identies#add_identy'
      end

      resources :tes_efikasi
      
      scope '/references' do 
        get '/hobi', to: 'references#hobby'
        get '/tes_efikasi', to: 'references#effication_test'
      end
  
    end
  end 

  # DITRAHEAL 

  # get '/ditraheal', to: 'ditraheal#index'
  namespace :ditraheal do
    # root 'home#index'
    resources :identities

    resources :efficacious_tests

    resources :trauma_levels

    # resources :references
    get '/master', to:  'references#index'
    # get '/master/soal_level_trauma', to: 'references#level_trauma'
    namespace :references do
      resources :master_soal_efikasi
      resources :master_soal_level_trauma
      resources :master_hobi
    end

    
    resources :group_sosmed
  end

end
