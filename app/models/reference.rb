class Reference < ApplicationRecord
    # validates_uniqueness_of :deskripsi, scope: :jenis, conditon: -> {where(jenis: 1)}, on: :create, message: "must be unique"
    # validates_uniqueness_of :ref_code, scope: :jenis, conditon: -> {where(jenis: 14)}, on: :create, message: "must be unique"

end
