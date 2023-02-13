class SkorEfikasi < ApplicationRecord
    validates_presence_of :referensi_soal, :jawaban, :pre_test_id, :jenis 
    validates_uniqueness_of :referensi_soal, :scope => [:pre_test_id, :jenis]
end
