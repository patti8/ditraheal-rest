class AddColumnInferensi < ActiveRecord::Migration[7.0]
  def change
    add_column :periode_treatments, :inferensi, :string
    # remove_column :periode_treatments, :rule_base
  end
end
