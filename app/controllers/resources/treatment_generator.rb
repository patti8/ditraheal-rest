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

    def self.response(status, data)

        if status == 200
            {
                status: 200, 
                messages: "periode treatment successfully updated.",
                data: data
                
            }
        else
            {
                status: 400, 
                messages: false,
                data: data
            }
        end
    end


end