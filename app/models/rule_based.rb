class RuleBased < ApplicationRecord
    validates_uniqueness_of :mode, on: :create, message: "must be unique"

end
