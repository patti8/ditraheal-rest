class Ditraheal::HomeController < DitrahealController
    # before_action :checklist_percentage, only: :index

    def index
        @presentase_pengerjaan_treatment = [
            ["Telah dikerjakan", checklist_percentage(1)],
            ["Belum dikerjakan", checklist_percentage(0)]
        ]
        
        @user = Identy.all
        @periode_treatment = PeriodeTreatment.all
        @chart_data = [5, 10, 15, 20, 25, 30]

        respond_to do |format|
            format.html {  }
            format.turbo_stream do 
                # turbo_stream.replace(
                #     @chart,
                #     partial: 'chart',
                #     locals: { chart: @chart }
                # )
            end
        end
    end

    private 
        def checklist_percentage(checklist)
            total_treatments = Treatment.count
            checklist_treatments = Treatment.where(checklist: checklist).count
            percentage = (checklist_treatments.to_f / total_treatments.to_f) * 100.0
            percentage.round(2) # round to 2 decimal places
        end
        
        # This method returns the group based on the checklist percentage
        def checklist_group
            percentage = self.checklist_percentage
            if percentage >= 50.0
                "Group 1"
            else
                "Group 2"
            end
        end


end
