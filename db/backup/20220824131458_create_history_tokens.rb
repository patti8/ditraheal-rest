class CreateHistoryTokens < ActiveRecord::Migration[7.0]
  def change
    create_table :history_tokens do |t|
      t.integer :user
      t.string :token

      t.timestamps
    end
  end
end
