module Ditraheal::IdentitiesHelper

    def sort_link_to(name, column, **options)
       
        if params[:sort] == column.to_s
            direction = params[:direction] == "asc" ? "desc" : "asc"
        else
            direction = "asc"
        end

        link_to name, request.params.merge(sort: column, direction: direction), **options
    end

    def level_trauma(nilai)
        if nilai <= 40
            "Level Trauma Rendah"
        elsif nilai > 40 && nilai <= 70
            "Level Trauma Sedang"
        elsif nilai > 70
            "Level Trauma Tinggi"
        end
    end

end
