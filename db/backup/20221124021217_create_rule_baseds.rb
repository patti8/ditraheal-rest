class CreateRuleBaseds < ActiveRecord::Migration[7.0]
  def change
    create_table :rule_baseds do |t|
      t.string :mode
      t.string :description
      t.string :rule
      t.string :ref

      t.timestamps
    end
  end
end
