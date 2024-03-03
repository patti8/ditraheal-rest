class CreateTreatmentKelompoks < ActiveRecord::Migration[7.0]
  def change
    create_table :treatment_kelompoks do |t|
      t.integer :periode_treatment
      t.string :treat_kelompok_sekali
      t.boolean :check_treat_kelompok_sekali
      t.integer :jenis
      t.boolean :bercerita_tentang_hal_hal_berhubungan_dengan_hobi
      t.boolean :bercerita_aktifitas_sehari_hari_berhubungan_dengan_hobi
      t.boolean :saran_untuk_meningkatkan_kecintaan_keseruan_pada_hobi
      t.boolean :saling_memotivasi_sesama_anggota_kelompok
      t.string :saling_mendoakan_sesama_anggota_kelompok_menurut
      t.boolean :keyakinan_masing_masing
      t.boolean :melakukan_percakapan_pribadi_dengan_topik_ringan_lainnya_dengan_sesama_anggota_kelompok
      t.integer :sesi

      t.timestamps
    end
  end
end
