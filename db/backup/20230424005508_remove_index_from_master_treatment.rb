class RemoveIndexFromMasterTreatment < ActiveRecord::Migration[7.0]
  def change
    remove_index :master_treatments, name: "index_master_treatments_on_time_duration_id"
  end
end
