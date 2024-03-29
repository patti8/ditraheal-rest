Rails.application.routes.draw do
  
  # mount AdminPanel::Engine => "/admin_panel"
  
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
  get '/whoami', to: 'users#cek_token'
  post '/logout', to: 'users#logout'


  # get 'sign_in', to: 'sessions#new'
  # post 'sign_in', to: 'sessions#create', as: 'log_in'
  # delete 'logout', to: 'sessions#destroy'

  namespace :api do
    namespace :v1 do

      post "/pre_test/efikasi", to: "pre_test#effication_pre_test"
      get "/pre_test/efikasi", to: "pre_test#get_effication_pre_test"
      
      post "/post_test/efikasi", to: "post_test#effication_test"
      # post "/post_test/level_trauma", to: "post_test#level_trauma_test"
      post "/post_test/validasi", to: "post_test#validasi"
      post "/post_test/update_skor", to: "post_test#update_skor"

      get '/pre_test/skor/:periode_treatment_id', to: "pre_test#skor"
      post '/:test/update_skor/:periode_treatment_id', to: "pre_test#update_skor" 

      post "/pre_test/level_trauma", to: "pre_test#level_trauma_pre_test"

      post '/periode/treatment', to: "treatment#periode"
      get '/periode/treatment', to: "treatment#show_periode"

      post '/generate/treatment', to: "treatment#generate"
      get '/by_date/treatment', to: "treatment#by_date"
      post '/update/treat', to: "treatment#update_treat"

      namespace :treatments do
        get '/daily', to: "daily#show"
        post '/daily/treat/:id', to: "daily#create"
        post '/', to: 'treatment#create'
        
        # treat_kelompok 
        get '/kelompok/sekali', to: 'treatment#treatment_kelompok_tampil_sekali'
        get '/kelompok/berulang', to: 'treatment#treatment_kelompok_tampil_berulang'
        get '/kelompok/berulang/:periode_treatment_id/all', to: 'treatment#treatment_kelompok_tampil_berulang_all'
        post '/kelompok', to: 'treatment#treat_kelompok_checklist'
      end

      # IDENTY 
      resources :identies, except: %i[create]
      scope 'identies' do
        post '/', to: 'identies#add_identy'
      end

      resources :tes_efikasi
      
      scope '/references' do 
        get '/hobi', to: 'references#hobby'
        get '/tes_efikasi', to: 'references#effication_test'
        get '/level_trauma', to: 'references#level_trauma'
        get '/link_medsos', to: 'references#link_medsos'
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
      
      resources :master_treatment do
        get '/status', to: 'master_treatment#update_status'
      end
      
      put '/status/update_all', to: 'master_treatment#update_status_all' #, as: 'update_all_ditraheal_references_master_treatment'

      post '/ditraheal/references/master_treatment', to: 'master_treatment#create'

    end

    
    resources :group_sosmed
    patch '/group_sosmed/:id', to: 'group_sosmed#update', as: "update_sosmed"
  
  end

end
