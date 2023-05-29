class Ditraheal::References::MasterSoalLevelTraumaController < Ditraheal::ReferencesController

    def index
        @soal_level_trauma = Reference.where(jenis: 3)
    end


end