# DESCRIPTION FOR REFERENCES MASTER :
# 1 > Hobby
# 2 > Effication Question
# 3 > Level Trauma Question
# 4 > Trauma Level
# 5 > Ketrampilan berdasarkan trauma level
# 6 > Relaksasi berdasarkan trauma level
# 7 > Pengalihan berdasarkan trauma level
# 8 > Treatment berdasarkan Hobi

Reference.create(jenis: 1, deskripsi: "Musik", ref_code: "HM")
Reference.create(jenis: 1, deskripsi: "Olahraga", ref_code: "HOR")
Reference.create(jenis: 1, deskripsi: "Art/Seni", ref_code: "HAS")
Reference.create(jenis: 1, deskripsi: "Membaca dan atau Menonton", ref_code: "HMM")

Reference.create(jenis: 2, deskripsi: "Saya tidak akan terlalu sering mengingat-ingat awal mula kejadian bencana")
Reference.create(jenis: 2, deskripsi: "Saya jarang mengalami mengalami kesulitan untuk tidur")
Reference.create(jenis: 2, deskripsi: "Hal-hal disekitar saya tidak membuat saya memikirkan/mengingat hal yang menyangkut bencana yang terjadi")
Reference.create(jenis: 2, deskripsi: "Saya mampu untuk tidak larut dalam emosi ketika memikirkan atau teringat akan peristiwa bencana yang terjadi.")
Reference.create(jenis: 2, deskripsi: "Ketika saya berusaha untuk tidak memikirkan, maka kejadian bencana itu tidak lagi terlintas dipikiran saya.")


# TesEfikasi.create(identy: 1, question: 7, answer: 100 )

# (51..70).each do |id|
#     Identy.create!(
#         id: id,
#         name: Faker::Name.name,
#         tanggal_lahir: rand(1..1000).days.ago.to_date,
#         hobi: rand(1..3),
#         no_hp: "082199#{rand(500111..100011)}",
#         follower: rand(100..1000)
#     )
# end

Reference.create(jenis: 3, deskripsi: "Saya tidak akan terlalu sering mengingat-ingat awal mula kejadian bencana")
Reference.create(jenis: 3, deskripsi: "Saya jarang mengalami mengalami kesulitan untuk tidur")
Reference.create(jenis: 3, deskripsi: "Hal-hal disekitar saya tidak membuat saya memikirkan/mengingat hal yang menyangkut bencana yang terjadi")
Reference.create(jenis: 3, deskripsi: "Saya tidak mudah tersinggung dan marah")
Reference.create(jenis: 3, deskripsi: "Saya mampu untuk tidak larut dalam emosi ketika memikirkan atau teringat akan peristiwa bencana yang terjadi.")
Reference.create(jenis: 3, deskripsi: "Ketika saya berusaha untuk tidak memikirkan, maka kejadian bencana itu tidak lagi terlintas dipikiran saya")

# Admin.create(email: "admin@ditraheal.com", password: "qwe123", password_confirmation: "qwe123")
    

# MASTER TREATMENT 
MasterTreatment.create(
    relaksasi: "Menghirup aroma udara pagi",
    ketrampilan: "Pelatihan ulang napas, Bangun pagi pada waktu yang teratur
    , Bangkit setelah bangun pagi dengan waktu yang teratur",
    aktifitas_mental: "Hindari pikiran berkecamuk, alihkan dengan memilah hal-hal yang dianggap paling perlu untuk dipikirkan ",
    pengalihan_trauma: "",
    level_trauma: "",
    kode: "",
    jenis: ""
)