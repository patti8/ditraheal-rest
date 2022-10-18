class Ditraheal::References::MasterSoalEfikasiController < Ditraheal::ReferencesController

    def index
        @soal_efikasi = Reference.where(jenis: 2)
    end


end