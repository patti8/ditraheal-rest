class AddColumnRuleBased < ActiveRecord::Migration[7.0]
  def change
    add_column :periode_treatments, :rule, :integer
  end
end
