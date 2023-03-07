class Resources::Tools

    def self.create_treatment_by(periode_treatment_id)
        
        periode_treatment = PeriodeTreatment.find_by(id: periode_treatment_id)
        # pre_test = PreTest.find_by(periode_treatment_id: periode_treatment.id)
        identy = Identy.find_by(id: periode_treatment.identitas_id)
        hobi = Reference.find_by(jenis: 1, id: identy.hobi).deskripsi

        rule_base = rule_base(level_trauma(pre_test.total_level_trauma_id), hobi)
        
        treatment = Treatment.new

        tgl_akhir = Date.today + 30.days
        (Date.today..tgl_akhir).each do |date|
            
            treatment.tanggal = date
            treatment.periode_treatment_id = periode_treatment.id

            if rule_base == 10 # MODE I

                # data = [
                #     date, 
                #     periode_treatment.id, 
                #     8, # JENIS
                #     2 # REF CODE
                # ]

                # HOBI
                # create_hobi_treatment(data)
               
                # TREAT 
                MasterTreatment.where(status: 1, rule_based_id: 10).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end
                # Reference.where(jenis: 6, ref_code: 49).each do |referensi|
                #     treatment.treat = referensi.id
                #     treatment.save
                # end

            elsif rule_base == 6 # MODE II

                # HOBI
                data = [
                    date, 
                    periode_treatment.id, 
                    8, # JENIS
                    2 # REF CODE
                ]
                
                # HOBI
                # create_hobi_treatment(data)

                # LEVEL TRAUMA
                # TREAT 
                MasterTreatment.where(status: 1, rule_based_id: 6).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end
                # Reference.where(jenis: 6, ref_code: 50).each do |referensi|
                #     treatment.treat = referensi.id
                #     treatment.save
                # end

            elsif rule_base == 2 # MODE III

                # HOBI
                # data = [
                #     date, 
                #     periode_treatment.id, 
                    
                #     8, # JENIS
                #     2 # REF CODE
                # ]
                
                # HOBI
                # create_hobi_treatment(data)

                # LEVEL TRAUMA
                MasterTreatment.where(status: 1, rule_based_id: 2).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end
                # Reference.where(jenis: 6, ref_code: 51).each do |referensi|
                #     treatment.treat = referensi.id
                #     treatment.save
                # end
            
            elsif rule_base == 9 # MODE IV
                
                # HOBI
                # data = [
                #     date, 
                #     periode_treatment.id, 
                    
                #     8, # JENIS
                #     4 # REF CODE
                # ]
                
                # # HOBI
                # create_hobi_treatment(data)
                
                # LEVEL TRAUMA
                MasterTreatment.where(status: 1, rule_based_id: 9).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end
                # Reference.where(jenis: 6, ref_code: 49).each do |referensi|
                #     treatment.treat = referensi.id
                #     treatment.save
                # end

            elsif rule_base == 5 # MODE V
                
                # HOBI
                # data = [
                #     date, 
                #     periode_treatment.id, 
                    
                #     8, # JENIS
                #     4 # REF CODE
                # ]
                
                # # HOBI
                # create_hobi_treatment(data)
                
                # LEVEL TRAUMA
                MasterTreatment.where(status: 1, rule_based_id: 5).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end

                # Reference.where(jenis: 6, ref_code: 50).each do |referensi|
                #     treatment.treat = referensi.id
                #     treatment.save
                # end

            elsif rule_base == 1 # MODE VI
                
                # HOBI
                # data = [
                #     date, 
                #     periode_treatment.id, 
                    
                #     8, # JENIS
                #     4 # REF CODE
                # ]
                
                # # HOBI
                # create_hobi_treatment(data)
                
                # LEVEL TRAUMA
                MasterTreatment.where(status: 1, rule_based_id: 1).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end
                # Reference.where(jenis: 6, ref_code: 51).each do |referensi|
                #     treatment.treat = referensi.id
                #     treatment.save
                # end

            elsif rule_base == 12 # MODE VII
                
                # HOBI
                # data = [
                #     date, 
                #     periode_treatment.id, 
                    
                #     8, # JENIS
                #     1 # REF CODE
                # ]
                
                # # HOBI
                # create_hobi_treatment(data)
                
                # LEVEL TRAUMA
                MasterTreatment.where(status: 1, rule_based_id: 12).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end
                # Reference.where(jenis: 6, ref_code: 49).each do |referensi|
                #     treatment.treat = referensi.id
                #     treatment.save
                # end
            elsif rule_base == 8 # MODE VIII
                
                # HOBI
                # data = [
                #     date, 
                #     periode_treatment.id, 
                    
                #     8, # JENIS
                #     1 # REF CODE
                # ]
                
                # # HOBI
                # create_hobi_treatment(data)
                
                # LEVEL TRAUMA
                MasterTreatment.where(status: 1, rule_based_id: 8).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end
                # Reference.where(jenis: 6, ref_code: 50).each do |referensi|
                #     treatment.treat = referensi.id
                #     treatment.save
                # end

            elsif rule_base == 4 # MODE IX
                
                # HOBI
                # data = [
                #     date, 
                #     periode_treatment.id, 
                    
                #     8, # JENIS
                #     1 # REF CODE
                # ]
                
                # HOBI
                # create_hobi_treatment(data)
                
                # # LEVEL TRAUMA
                MasterTreatment.where(status: 1, rule_based_id: 4).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end
                # Reference.where(jenis: 6, ref_code: 51).each do |referensi|
                #     treatment.treat = referensi.id
                #     treatment.save
                # end

            elsif rule_base == 11 # MODE X
                
                # HOBI
                # data = [
                #     date, 
                #     periode_treatment.id, 
                    
                #     8, # JENIS
                #     3 # REF CODE
                # ]
                
                # # HOBI
                # create_hobi_treatment(data)
                
                # # LEVEL TRAUMA
                # Reference.where(jenis: 6, ref_code: 49).each do |referensi|
                #     treatment.treat = referensi.id
                #     treatment.save
                # end
                MasterTreatment.where(status: 1, rule_based_id: 11).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end

            elsif rule_base == 7 # MODE XI
                
                # HOBI
                # data = [
                #     date, 
                #     periode_treatment.id, 
    
                #     8, # JENIS
                #     3 # REF CODE
                # ]
                
                # # HOBI
                # create_hobi_treatment(data)
                
                # # LEVEL TRAUMA
                # Reference.where(jenis: 6, ref_code: 50).each do |referensi|
                #     treatment.treat = referensi.id
                #     treatment.save
                # end
                MasterTreatment.where(status: 1, rule_based_id: 7).each do |referensi|
                    treatment.treat = referensi.id
                    treatment.save
                end

            elsif rule_base == 3 # MODE XII
                
                # HOBI
                # data = [
                #     date, 
                #     periode_treatment.id, 
                    
                #     8, # JENIS
                #     3 # REF CODE
                # ]
                
                # # HOBI
                # create_hobi_treatment(data)
                
                # # LEVEL TRAUMA
                # Reference.where(jenis: 6, ref_code: 51).each do |referensi|
                #     treatment.treat = referensi.id
                #     treatment.save
                # end
                MasterTreatment.where(status: 1, rule_based_id: 3).each do |referensi|
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

    def self.generate_date_for_treatment(rule_based_id, periode_treatment_id)

        # treatment = Treatment.where(periode_treatment_id: periode_treatment_id)
        treat_with_rule = MasterTreatment.where(rule_based_id: rule_based_id)
       
       treat_sesi1 = {
            sesi: 1,
            data: treat_with_rule.where(ref_sesi: 1)
       }
        
       treat_sesi2 = {
            sesi: 2,
            data: treat_with_rule.where(ref_sesi: 2)
       }
       
       treat_sesi3 = {
            sesi: 3,
            data: treat_with_rule.where(ref_sesi: 3)
       }

       treat_sesi4 = { 
            sesi: 4,
            data: treat_with_rule.where(ref_sesi: 4)
       }

       treat_sesi = [1, 2, 3, 4]

       treat_sesi.each do |sesi|
            
            if sesi == 1
        
                start_date = DateTime.now
                end_date = DateTime.now + 14
        
                Resources::TreatmentGenerator.create_treatment_by(
                    start_date..end_date, 
                    treat_sesi1[:data], 
                    periode_treatment_id
                )
            
           elsif sesi == 2
    
                start_date = DateTime.now + 14
                end_date = start_date + 14

                Resources::TreatmentGenerator.create_treatment_by(
                    start_date..end_date, 
                    treat_sesi2[:data], 
                    periode_treatment_id
                )
        
           elsif sesi == 3
    
                start_date = DateTime.now + 14 + 14
                end_date = start_date + 14
        
                Resources::TreatmentGenerator.create_treatment_by(
                    start_date..end_date, 
                    treat_sesi3[:data], 
                    periode_treatment_id,
                )
                
      
    
           elsif sesi == 4
            
                start_date = DateTime.now + 14 + 14 + 14
                end_date = start_date + 14
        
                Resources::TreatmentGenerator.create_treatment_by(
                    start_date..end_date, 
                    treat_sesi4[:data], 
                    periode_treatment_id,
                )
    
           end
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
            10 # JENIS 9
        elsif level_trauma == "Level Trauma Sedang" && hobi == "Musik"
            6
        elsif level_trauma == "Level Trauma Tinggi" && hobi == "Musik"
            2
        elsif level_trauma == "Level Trauma Rendah" && hobi == "Olahraga"
            9 # JENIS 9
        elsif level_trauma == "Level Trauma Sedang" && hobi == "Olahraga"
            5
        elsif level_trauma == "Level Trauma Tinggi" && hobi == "Olahraga"
            1
        elsif level_trauma == "Level Trauma Rendah" && hobi == "Art/Seni"
            12 # JENIS 9
        elsif level_trauma == "Level Trauma Sedang" && hobi == "Art/Seni"
            8
        elsif level_trauma == "Level Trauma Tinggi" && hobi == "Art/Seni"
            4
        elsif level_trauma == "Level Trauma Rendah" && hobi == "Membaca/Menonton"
            11 # JENIS 9
        elsif level_trauma == "Level Trauma Sedang" && hobi == "Membaca/Menonton"
            7
        elsif level_trauma == "Level Trauma Tinggi" && hobi == "Membaca/Menonton"
            3
        end

    end


end