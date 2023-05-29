class AddColumnLevelTrauma < ActiveRecord::Migration[7.0]
  def change
    add_column :periode_treatments, :level_trauma, :string
    #Ex:- add_column("admin_users", "username", :string, :limit =>25, :after => "email")
  end
end
