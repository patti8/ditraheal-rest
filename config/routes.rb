Rails.application.routes.draw do
  namespace :api do
    namespace :v1 do
      resources :references
      resources :identies
    end
  end
  

  root "home#index"
end
