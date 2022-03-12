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

ActiveRecord::Schema[7.0].define(version: 2022_03_12_074244) do
  create_table "action_text_rich_texts", charset: "latin1", force: :cascade do |t|
    t.string "name", null: false
    t.text "body", size: :medium
    t.string "record_type", null: false
    t.bigint "record_id", null: false
    t.datetime "created_at", precision: nil, null: false
    t.datetime "updated_at", precision: nil, null: false
    t.index ["record_type", "record_id", "name"], name: "index_action_text_rich_texts_uniqueness", unique: true
  end

  create_table "active_storage_attachments", charset: "latin1", force: :cascade do |t|
    t.string "name", null: false
    t.string "record_type", null: false
    t.bigint "record_id", null: false
    t.bigint "blob_id", null: false
    t.datetime "created_at", precision: nil, null: false
    t.index ["blob_id"], name: "index_active_storage_attachments_on_blob_id"
    t.index ["record_type", "record_id", "name", "blob_id"], name: "index_active_storage_attachments_uniqueness", unique: true
  end

  create_table "active_storage_blobs", charset: "latin1", force: :cascade do |t|
    t.string "key", null: false
    t.string "filename", null: false
    t.string "content_type"
    t.text "metadata"
    t.bigint "byte_size", null: false
    t.string "checksum", null: false
    t.datetime "created_at", precision: nil, null: false
    t.index ["key"], name: "index_active_storage_blobs_on_key", unique: true
  end

  create_table "admin_artikels", charset: "latin1", force: :cascade do |t|
    t.string "title"
    t.string "author"
    t.datetime "created_at", precision: nil, null: false
    t.datetime "updated_at", precision: nil, null: false
  end

  create_table "admin_banners", charset: "latin1", force: :cascade do |t|
    t.string "title"
    t.string "desc"
    t.datetime "created_at", precision: nil, null: false
    t.datetime "updated_at", precision: nil, null: false
  end

  create_table "admin_departemen", charset: "latin1", force: :cascade do |t|
    t.string "title"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "admin_dokters", charset: "latin1", force: :cascade do |t|
    t.string "nama"
    t.string "spesialis"
    t.text "desc"
    t.datetime "created_at", precision: nil, null: false
    t.datetime "updated_at", precision: nil, null: false
  end

  create_table "admin_galleries", charset: "latin1", force: :cascade do |t|
    t.string "caption"
    t.datetime "created_at", precision: nil, null: false
    t.datetime "updated_at", precision: nil, null: false
  end

  create_table "admin_jadwals", charset: "latin1", force: :cascade do |t|
    t.string "poli"
    t.string "senin"
    t.string "selasa"
    t.string "rabu"
    t.string "kamis"
    t.string "jumat"
    t.string "sabtu"
    t.string "dokter"
    t.string "keterangan"
    t.datetime "created_at", precision: nil, null: false
    t.datetime "updated_at", precision: nil, null: false
    t.integer "position"
  end

  create_table "admin_profiles", charset: "latin1", force: :cascade do |t|
    t.string "nama"
    t.string "email"
    t.text "alamat"
    t.string "kota"
    t.string "provinsi"
    t.string "pimpinan"
    t.string "tlp_igd"
    t.string "tlp_lainnya"
    t.datetime "created_at", precision: nil, null: false
    t.datetime "updated_at", precision: nil, null: false
    t.string "visi"
    t.text "misi"
    t.text "sejarah"
    t.string "moto"
  end

  create_table "admin_references", id: false, charset: "latin1", force: :cascade do |t|
    t.integer "jenis"
    t.integer "id"
    t.string "deskripsi"
    t.boolean "status"
    t.datetime "created_at", precision: nil, null: false
    t.datetime "updated_at", precision: nil, null: false
  end

  create_table "admin_unggulans", charset: "latin1", force: :cascade do |t|
    t.string "title"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "identies", charset: "latin1", force: :cascade do |t|
    t.integer "no_hp"
    t.date "tgl_lahir"
    t.text "alamat"
    t.integer "follower"
    t.integer "hobi"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "references", charset: "latin1", force: :cascade do |t|
    t.integer "jenis"
    t.string "deskripsi"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "users", charset: "latin1", force: :cascade do |t|
    t.string "username"
    t.string "password_digest"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  add_foreign_key "active_storage_attachments", "active_storage_blobs", column: "blob_id"
end
