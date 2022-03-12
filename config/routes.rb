Rails.application.routes.draw do
  namespace :api do
    namespace :v1 do
      resources :identies
      resources :references
    end
  end
  

  root "home#index"
end
