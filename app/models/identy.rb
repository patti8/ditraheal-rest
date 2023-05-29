class Identy < ApplicationRecord
    include PgSearch::Model
    pg_search_scope :search, against: [:no_hp, :name, :tgl_lahir, :hobi], using: { tsearch: { prefix: true } }

    # validates_presence_of :no_hp
    validates_uniqueness_of :no_hp
    # validates_presence_of :tgl_lahir
    # validates_uniqueness_of :tgl_lahir
    # has_one :login
end
