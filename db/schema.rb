# This file is auto-generated from the current state of the database. Instead
# of editing this file, please use the migrations feature of Active Record to
# incrementally modify your database, and then regenerate this schema definition.
#
# This file is the source Rails uses to define your schema when running `bin/rails
# db:schema:load`. When creating a new database, `bin/rails db:schema:load` tends to
# be faster and is potentially less error prone than running all of your
# migrations from scratch. Old migrations may fail to apply correctly if those
# migrations use external dependencies or application code.
#
# It's strongly recommended that you check this file into your version control system.

ActiveRecord::Schema[7.0].define(version: 2023_02_09_020338) do
  create_table "admins", force: :cascade do |t|
    t.string "email", default: "", null: false
    t.string "encrypted_password", default: "", null: false
    t.string "reset_password_token"
    t.datetime "reset_password_sent_at"
    t.datetime "remember_created_at"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
    t.index ["email"], name: "index_admins_on_email", unique: true
    t.index ["reset_password_token"], name: "index_admins_on_reset_password_token", unique: true
  end

  create_table "history_tokens", force: :cascade do |t|
    t.integer "user"
    t.string "token"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "identies", force: :cascade do |t|
    t.string "no_hp"
    t.date "tanggal_lahir"
    t.text "alamat"
    t.integer "follower"
    t.integer "hobi"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
    t.string "name"
  end

  create_table "level_traumas", force: :cascade do |t|
    t.integer "referensi_soal"
    t.integer "jawaban"
    t.integer "pre_test_id"
    t.integer "post_test_id"
    t.integer "jenis"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "logins", force: :cascade do |t|
    t.integer "id_user"
    t.integer "status"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "master_treatments", force: :cascade do |t|
    t.string "deskripsi"
    t.integer "rule_based_id", null: false
    t.integer "time_duration_id", null: false
    t.integer "ref_sesi"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
    t.index ["rule_based_id"], name: "index_master_treatments_on_rule_based_id"
    t.index ["time_duration_id"], name: "index_master_treatments_on_time_duration_id"
  end

  create_table "periode_treatments", force: :cascade do |t|
    t.integer "identitas_id"
    t.integer "status"
    t.date "tanggal_awal"
    t.date "tanggal_akhir"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
    t.string "inferensi"
    t.integer "rule"
    t.string "level_trauma"
    t.integer "link_group"
  end

  create_table "references", force: :cascade do |t|
    t.integer "jenis"
    t.string "deskripsi"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
    t.string "ref_code"
  end

  create_table "rule_baseds", force: :cascade do |t|
    t.string "mode"
    t.string "description"
    t.string "rule"
    t.string "ref"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "skor_efikasis", force: :cascade do |t|
    t.integer "referensi_soal"
    t.integer "jawaban"
    t.integer "pre_test_id"
    t.integer "post_test_id"
    t.integer "jenis"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "tests", force: :cascade do |t|
    t.integer "total_skor_efikasi"
    t.integer "total_level_trauma_id"
    t.integer "periode_treatment_id"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
    t.integer "jenis"
    t.integer "post_test_efikasi"
  end

  create_table "time_durations", force: :cascade do |t|
    t.string "deskripsi"
    t.string "ref_duration"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "treatment_kelompoks", force: :cascade do |t|
    t.integer "periode_treatment"
    t.string "treat_kelompok_sekali"
    t.boolean "check_treat_kelompok_sekali"
    t.integer "jenis"
    t.boolean "bercerita_tentang_hal_hal_berhubungan_dengan_hobi"
    t.boolean "bercerita_aktifitas_sehari_hari_berhubungan_dengan_hobi"
    t.boolean "saran_untuk_meningkatkan_kecintaan_keseruan_pada_hobi"
    t.boolean "saling_memotivasi_sesama_anggota_kelompok"
    t.string "saling_mendoakan_sesama_anggota_kelompok_menurut"
    t.boolean "keyakinan_masing_masing"
    t.boolean "melakukan_percakapan_pribadi_dengan_topik_ringan_lainnya_dengan_sesama_anggota_kelompok"
    t.integer "sesi"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
    t.integer "link"
  end

  create_table "treatments", force: :cascade do |t|
    t.integer "treat"
    t.boolean "checklist"
    t.integer "periode_treatment_id"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
    t.date "tanggal"
  end

  create_table "users", force: :cascade do |t|
    t.string "username"
    t.string "password_digest"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  add_foreign_key "master_treatments", "rule_baseds"
  add_foreign_key "master_treatments", "time_durations"
end
