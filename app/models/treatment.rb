class Treatment < ApplicationRecord
    validates_uniqueness_of :periode_treatment_id, :scope => [:treat, :tanggal]
end
