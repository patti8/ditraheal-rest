class Resources::TreatmentGenerator

    def self.generate_level_trauma(skor, periode_treatment_id)
        
        periode_treatment = PeriodeTreatment.find_by(id: periode_treatment_id)
        
        if skor <= 26
            
            if periode_treatment.update(level_trauma: "rendah")
                response(
                    200,
                    periode_treatment
                )
            else
                response(
                    400,
                    nil
                )
            end
        elsif skor >= 26 && skor <= 43
            
            if periode_treatment.update(level_trauma: "sedang")
                response(
                    200,
                    periode_treatment
                )
            else
                response(
                    400,
                    nil
                )
            end

        elsif skor >= 44

            if periode_treatment.update(level_trauma: "tinggi")
                response(
                    200,
                    periode_treatment
                )
            else
                response(
                    400,
                    nil
                )
            end
        end

    end

    def self.rule_base(periode_treatment_id)
        
        periode_treatment = PeriodeTreatment.find_by(id: periode_treatment_id)
        
        user_hobi = Reference.find_by(jenis: 1, id: Identy.find_by(id: periode_treatment.identitas_id).hobi).deskripsi

        if periode_treatment.level_trauma== "rendah" && user_hobi == "Olahraga"
          
            if periode_treatment.update(rule: 9)
                response(200, periode_treatment)
            else
                response(400, nil)
            end
        
        elsif periode_treatment.level_trauma== "sedang" && user_hobi == "Olahraga"
           
            if periode_treatment.update(rule: 5)
                response(200, periode_treatment)
            else
                response(400, nil)
            end
        
        elsif periode_treatment.level_trauma== "tinggi" && user_hobi == "Olahraga"
            
            if periode_treatment.update(rule: 1)
                response(200, periode_treatment)
            else
                response(400, nil)
            end

        elsif periode_treatment.level_trauma== "rendah" && user_hobi == "Musik"
            
            if periode_treatment.update(rule: 10)
                response(200, periode_treatment)
            else
                response(400, nil)
            end
        
        elsif periode_treatment.level_trauma== "sedang" && user_hobi == "Musik"
            
            if periode_treatment.update(rule: 6)
                response(200, periode_treatment)
            else
                response(400, nil)
            end

        elsif periode_treatment.level_trauma== "tinggi" && user_hobi == "Musik"
            
            if periode_treatment.update(rule: 2)
                response(200, periode_treatment)
            else
                response(400, nil)
            end

        elsif periode_treatment.level_trauma== "rendah" && user_hobi == "Art/Seni"
            
            if periode_treatment.update(rule: 12)
                response(200, periode_treatment)
            else
                response(400, nil)
            end
            
        elsif periode_treatment.level_trauma== "sedang" && user_hobi == "Art/Seni"
            
            if periode_treatment.update(rule: 8)
                response(200, periode_treatment)
            else
                response(400, nil)
            end

        elsif periode_treatment.level_trauma== "tinggi" && user_hobi == "Art/Seni"
            
            if periode_treatment.update(rule: 4)
                response(200, periode_treatment)
            else
                response(400, nil)
            end

        elsif periode_treatment.level_trauma== "rendah" && user_hobi == "Membaca/Menonton"
            
            if periode_treatment.update(rule: 11)
                response(200, periode_treatment)
            else
                response(400, nil)
            end
        
        elsif periode_treatment.level_trauma== "sedang" && user_hobi == "Membaca/Menonton"
            
            if periode_treatment.update(rule: 7)
                response(200, periode_treatment)
            else
                response(400, nil)
            end
        
        elsif periode_treatment.level_trauma== "tinggi" && user_hobi == "Membaca/Menonton"
            
            if periode_treatment.update(rule: 3)
                response(200, periode_treatment)
            else
                response(400, nil)
            end

        end 

    end

    def  self.create_treatment_by(date_range, treat_master, periode_treatment_id)
        
        (date_range).each do |date|
            treat_master.each do |treat|
                treat = Treatment.create(
                    treat: treat.id,
                    checklist: 0,
                    periode_treatment_id: periode_treatment_id,
                    tanggal: date                
                )

                puts treat.errors.full_messages
                
            end
        end
    end

    def self.treat_kelompok(periode_treatment, hobi)

        link = Reference.find_by(ref_code: hobi)

        tk_sekali_waktu = Reference.where(jenis: 11)

        PeriodeTreatment.find_by(id: periode_treatment).update(
            link_group: link.id
        )

        tk_sekali_waktu.each do |treat|
            TreatmentKelompok.create(
                periode_treatment: periode_treatment,
                jenis: 1,
                treat_kelompok_sekali: treat.id
            )

        end

        # treatment berulang 
        (1..10).each do |treat|
            TreatmentKelompok.create(
                periode_treatment: periode_treatment,
                jenis: 2
            )
        end    

    end

    def self.response(status, data)

        if status == 200
            {
                success: 200, 
                messages: true,
                data: data
                
            }
        else
            {
                success: 400, 
                messages: false,
                data: data
            }
        end
    end


end