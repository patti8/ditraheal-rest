class Resources::Tools


    def self.create_treatment_by(periode_treatment_id)
        
        periode_treatment = PeriodeTreatment.find_by(id: periode_treatment_id)
        pre_test = PreTest.find_by(periode_treatment_id: periode_treatment.id)
        identy = Identy.find_by(id: periode_treatment.identitas_id)
        hobi = Reference.find_by(jenis: 1, id: identy.hobi).deskripsi

        rule_base = rule_base(level_trauma(pre_test.total_level_trauma_id), hobi)
        
        treatment = Treatment.new

        tgl_akhir = Date.today + 30.days
        (Date.today..tgl_akhir).each do |date|
            
            treatment.tanggal = date
            treatment.periode_treatment_id = periode_treatment.id

            if rule_base == 163 # MODE I

                data = [
                    date, 
                    periode_treatment.id, 
                    8, # JENIS
                    2 # REF CODE
                ]

                # HOBI
                create_hobi_treatment(data)
               
                # LEVEL TRAUMA 
                Reference.where(jenis: 6, ref_code: 49).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end

            elsif rule_base == 164 # MODE II

                # HOBI
                data = [
                    date, 
                    periode_treatment.id, 
                    8, # JENIS
                    2 # REF CODE
                ]
                
                # HOBI
                create_hobi_treatment(data)

                # LEVEL TRAUMA
                Reference.where(jenis: 6, ref_code: 50).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end

            elsif rule_base == 165 # MODE III

                # HOBI
                data = [
                    date, 
                    periode_treatment.id, 
                    
                    8, # JENIS
                    2 # REF CODE
                ]
                
                # HOBI
                create_hobi_treatment(data)

                # LEVEL TRAUMA
                Reference.where(jenis: 6, ref_code: 51).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end
            
            elsif rule_base == 166 # MODE IV
                
                # HOBI
                data = [
                    date, 
                    periode_treatment.id, 
                    
                    8, # JENIS
                    4 # REF CODE
                ]
                
                # HOBI
                create_hobi_treatment(data)
                
                # LEVEL TRAUMA
                Reference.where(jenis: 6, ref_code: 49).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end

            elsif rule_base == 167 # MODE V
                
                # HOBI
                data = [
                    date, 
                    periode_treatment.id, 
                    
                    8, # JENIS
                    4 # REF CODE
                ]
                
                # HOBI
                create_hobi_treatment(data)
                
                # LEVEL TRAUMA
                Reference.where(jenis: 6, ref_code: 50).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end

            elsif rule_base == 168 # MODE VI
                
                # HOBI
                data = [
                    date, 
                    periode_treatment.id, 
                    
                    8, # JENIS
                    4 # REF CODE
                ]
                
                # HOBI
                create_hobi_treatment(data)
                
                # LEVEL TRAUMA
                Reference.where(jenis: 6, ref_code: 51).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end

            elsif rule_base == 169 # MODE VII
                
                # HOBI
                data = [
                    date, 
                    periode_treatment.id, 
                    
                    8, # JENIS
                    1 # REF CODE
                ]
                
                # HOBI
                create_hobi_treatment(data)
                
                # LEVEL TRAUMA
                Reference.where(jenis: 6, ref_code: 49).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end
            elsif rule_base == 170 # MODE VIII
                
                # HOBI
                data = [
                    date, 
                    periode_treatment.id, 
                    
                    8, # JENIS
                    1 # REF CODE
                ]
                
                # HOBI
                create_hobi_treatment(data)
                
                # LEVEL TRAUMA
                Reference.where(jenis: 6, ref_code: 50).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end

            elsif rule_base == 171 # MODE IX
                
                # HOBI
                data = [
                    date, 
                    periode_treatment.id, 
                    
                    8, # JENIS
                    1 # REF CODE
                ]
                
                # HOBI
                create_hobi_treatment(data)
                
                # LEVEL TRAUMA
                Reference.where(jenis: 6, ref_code: 51).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end

            elsif rule_base == 172 # MODE X
                
                # HOBI
                data = [
                    date, 
                    periode_treatment.id, 
                    
                    8, # JENIS
                    3 # REF CODE
                ]
                
                # HOBI
                create_hobi_treatment(data)
                
                # LEVEL TRAUMA
                Reference.where(jenis: 6, ref_code: 49).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end

            elsif rule_base == 173 # MODE XI
                
                # HOBI
                data = [
                    date, 
                    periode_treatment.id, 
                    
                    8, # JENIS
                    3 # REF CODE
                ]
                
                # HOBI
                create_hobi_treatment(data)
                
                # LEVEL TRAUMA
                Reference.where(jenis: 6, ref_code: 50).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end

            elsif rule_base == 174 # MODE XII
                
                # HOBI
                data = [
                    date, 
                    periode_treatment.id, 
                    
                    8, # JENIS
                    3 # REF CODE
                ]
                
                # HOBI
                create_hobi_treatment(data)
                
                # LEVEL TRAUMA
                Reference.where(jenis: 6, ref_code: 51).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end
            end

        end
        
    end

    def self.create_hobi_treatment(data)

         Reference.where(jenis: data[2], ref_code: data[3]).each do |referensi|
            Treatment.create(
                tanggal: data[0],
                periode_treatment_id: data[1],
                treat: referensi.id
            )
        end

    end


    def self.level_trauma(nilai)
        if nilai <= 40
            "Level Trauma Rendah"
        elsif nilai > 40 && nilai <= 70
            "Level Trauma Sedang"
        elsif nilai > 70
            "Level Trauma Tinggi"
        end
    end

    def self.rule_base(level_trauma, hobi)

        if level_trauma == "Level Trauma Rendah" && hobi == "Musik"
            163 # JENIS 9
        elsif level_trauma == "Level Trauma Sedang" && hobi == "Musik"
            164
        elsif level_trauma == "Level Trauma Tinggi" && hobi == "Musik"
            165
        elsif level_trauma == "Level Trauma Rendah" && hobi == "Olahraga"
            166 # JENIS 9
        elsif level_trauma == "Level Trauma Sedang" && hobi == "Olahraga"
            167
        elsif level_trauma == "Level Trauma Tinggi" && hobi == "Olahraga"
            168
        elsif level_trauma == "Level Trauma Rendah" && hobi == "Art/Seni"
            169 # JENIS 9
        elsif level_trauma == "Level Trauma Sedang" && hobi == "Art/Seni"
            170
        elsif level_trauma == "Level Trauma Tinggi" && hobi == "Art/Seni"
            171
        elsif level_trauma == "Level Trauma Rendah" && hobi == "Membaca/Menonton"
            172 # JENIS 9
        elsif level_trauma == "Level Trauma Sedang" && hobi == "Membaca/Menonton"
            173
        elsif level_trauma == "Level Trauma Tinggi" && hobi == "Membaca/Menonton"
            174
        end

    end


end