class PeriodeTreatment < ApplicationRecord
    validates_presence_of :identitas_id, :status, :tanggal_awal #, :tanggal_akhir
end
