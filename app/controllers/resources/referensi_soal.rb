class Resources::ReferensiSoal

    def self.efikasi_by(id)
        Reference.where(jenis: 2, id: id)
    end

    def self.jenis_soal(num)
        Reference.where(jenis: num)
    end

    def self.level_trauma_by(id)
        Reference.where(jenis: 3, id: id)
    end

end