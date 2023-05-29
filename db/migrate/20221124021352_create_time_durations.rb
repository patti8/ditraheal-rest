class CreateTimeDurations < ActiveRecord::Migration[7.0]
  def change
    create_table :time_durations do |t|
      t.string :deskripsi
      t.string :ref_duration

      t.timestamps
    end
  end
end
