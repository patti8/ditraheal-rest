class AddColumnStatusToMasterTreatment < ActiveRecord::Migration[7.0]
  def change
    add_column :master_treatments, :status, :boolean
  end
end
