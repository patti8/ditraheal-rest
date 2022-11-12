Ext.define(null, {
    override: "rekammedis.perencanaan.jadwalkontrol.Form",
    first: true,
    onBeforeCallParentInitComponent: function (a) {
        if (a.first) {
            var b = [];
            a.buttons.forEach(function (d) {
                b.push(d);
                if (d.text == "Simpan") {
                    b.push({
                        xtype: "button",
                        ui: "soft-green",
                        margin: "30 0 0 0",
                        iconCls: "x-fa fa-send",
                        text: "Ambil Antrian & Simpan",
                        reference: "antrian",
                        handler: "onGetAntrian",
                        bind: {
                            hidden: "{formConfig.viewOnly}"
                        }
                    })
                }
            });
            a.buttons = b
        }
    },
    onLoadRecord: function (a, b) {
        a.callParent([a, b]);
        a.recBefore = Ext.clone(b);
        a.setFormConfig({
            nomorAntrian: true
        })
    }
});
Ext.define(null, {
    override: "rekammedis.perencanaan.jadwalkontrol.FormController",
    tujuanSelected: undefined,
    antrian: {
        service: undefined
    },
    init: function () {
        var a = this;
        a.callParent();
        a.antrian.service = Ext.create("antrian.Service", {})
    },
    onGetAntrian: function (n) {
        var y = this,
            m = y.getView(),
            x = y.getViewModel(),
            g = m.getRecord();
        if (!g) {
            return
        }
        var r = m.down("[name=NOMOR_ANTRIAN]"),
            e = m.down("[name=NOMOR_BOOKING]"),
            d = r.getValue(),
            f = m.down("[name=TANGGAL]").getValue(),
            j = m.down("[name=RUANGAN]").getValue(),
            p = x.get("dpjpPjmnRS"),
            u = m.down("dokter-combo"),
            v = u.getSelection(),
            k = Ext.Date.format(f, "Y-m-d"),
            h = Ext.Date.format(g.get("TANGGAL"), "Y-m-d"),
            a = m.getReferensi(g.getData(), "KUNJUNGAN"),
            q = m.getReferensi(a, "PENDAFTARAN"),
            w = m.getReferensi(q, "PASIEN"),
            b = y.tujuanSelected ? m.getReferensi(y.tujuanSelected, "PENJAMIN_SUB_SPESIALISTIK") : null,
            l = p ? p.findRecord("DPJP_RS", v.get("ID"), 0, false, true, true) : null,
            s = null;
        if (b) {
            b.forEach(function (t) {
                if (t.PENJAMIN == 2) {
                    s = t
                }
            })
        }
        if (k == h && d != "" && v.get("ID") == g.get("DOKTER")) {
            return
        }
        if (!y.antrian.service) {
            return
        }
        var o = "";
        if (w.KARTUIDENTITAS) {
            if (Ext.isArray(w.KARTUIDENTITAS)) {
                w.KARTUIDENTITAS.forEach(function (t) {
                    if (t.JENIS == 1) {
                        o = t.NOMOR
                    }
                })
            }
        }
        m.showMessageBox({
            title: "Antrian",
            message: "Anda yakin ingin mengambil nomor antrian?",
            ui: "window-red",
            buttons: Ext.Msg.YESNO,
            icon: Ext.Msg.QUESTION,
            animateTarget: n,
            fn: function (B) {
                if (B === "yes") {
                    var D = {
                            NIK: o,
                            JENIS: 2,
                            JENIS_APLIKASI: 3,
                            REF: q.NOMOR,
                            NORM: q.NORM,
                            CARABAYAR: q.PENJAMIN ? q.PENJAMIN.JENIS : 1,
                            NO_KARTU_BPJS: q.PENJAMIN ? q.PENJAMIN.NOMOR : "",
                            NO_REF_BPJS: q.RUJUKAN ? q.RUJUKAN.NOMOR : "",
                            POLI_BPJS: s ? s.SUB_SPESIALIS_PENJAMIN : "",
                            POLI: j,
                            TANGGALKUNJUNGAN: k,
                            DOKTER: l ? l.get("DPJP_PENJAMIN") : ""
                        },
                        C = Ext.Date.format(m.recBefore.get("TANGGAL"), "Y-m-d"),
                        A = Ext.Date.format(g.get("TANGGAL"), "Y-m-d"),
                        t = m.recBefore.get("DOKTER"),
                        z = g.get("DOKTER");
                    if ((A != C || z != t) && (e != "" && e != null)) {
                        D.UBAH_KONTROL_OK = 1;
                        D.REF_RESERVASI = e
                    }
                    m.setLoading(true);
                    y.antrian.service.createAntrian(D, function (F, G, E) {
                        m.notifyMessage(G.metadata.message);
                        r.setValue(G.response.nomorantrean);
                        e.setValue(G.response.kodebooking);
                        m.setLoading(false);
                        y.onSimpan(n)
                    })
                }
            }
        })
    }
});
Ext.define("antrian.antrianpoli.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/antrianpoli",
    fields: [{
        name: "ASAL_REF",
        type: "int"
    }, {
        name: "REF",
        type: "string"
    }, {
        name: "NOMOR",
        type: "int"
    }, {
        name: "POLI",
        type: "string"
    }, {
        name: "TANGGAL",
        type: "date",
        dateFormat: "Y-m-d"
    }, {
        name: "STATUS",
        type: "int",
        defaultValue: 1
    }]
});
Ext.define("antrian.antrianpoli.Store", {
    extend: "data.store.Store",
    model: "antrian.antrianpoli.Model",
    alias: "store.antrian-antrianpoli-store"
});
Ext.define("antrian.carabayar.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/carabayar",
    fields: [{
        name: "ID",
        type: "int"
    }, {
        name: "DESKRIPSI",
        type: "string"
    }, {
        name: "STATUS",
        type: "int",
        defaultValue: 1
    }],
    idProperty: "ID"
});
Ext.define("antrian.carabayar.Store", {
    extend: "data.store.Store",
    model: "antrian.carabayar.Model",
    alias: "store.antrian-carabayar-store"
});
Ext.define("antrian.hfis.jadwaldokter.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/jadwaldokterhfis",
    fields: [{
        name: "ID",
        type: "int"
    }, {
        name: "KD_DOKTER",
        type: "string"
    }, {
        name: "NM_DOKTER",
        type: "string"
    }, {
        name: "KD_SUB_SPESIALIS",
        type: "int"
    }, {
        name: "NM_SUB_SPESIALIS",
        type: "string"
    }, {
        name: "KD_POLI",
        type: "string"
    }, {
        name: "NM_POLI",
        type: "string"
    }, {
        name: "HARI",
        type: "int"
    }, {
        name: "TANGGAL",
        type: "date",
        dateFormat: "Y-m-d"
    }, {
        name: "NM_HARI",
        type: "string"
    }, {
        name: "JAM",
        type: "string"
    }, {
        name: "KAPASITAS",
        type: "int"
    }, {
        name: "LIBUR",
        type: "int"
    }, {
        name: "STATUS",
        type: "int"
    }],
    idProperty: "ID"
});
Ext.define("antrian.hfis.jadwaldokter.Store", {
    extend: "data.store.Store",
    model: "antrian.hfis.jadwaldokter.Model",
    alias: "store.antrian-hfis-jadwaldokter-store"
});
Ext.define("antrian.hfis.jadwaldokter.referensi.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/bpjs/getJadwalDokterHfis",
    fields: [{
        name: "ID",
        type: "int"
    }, {
        name: "KD_DOKTER",
        type: "string"
    }, {
        name: "NM_DOKTER",
        type: "string"
    }, {
        name: "KD_SUB_SPESIALIS",
        type: "int"
    }, {
        name: "NM_SUB_SPESIALIS",
        type: "string"
    }, {
        name: "KD_POLI",
        type: "string"
    }, {
        name: "NM_POLI",
        type: "string"
    }, {
        name: "HARI",
        type: "int"
    }, {
        name: "TANGGAL",
        type: "date",
        dateFormat: "Y-m-d"
    }, {
        name: "NM_HARI",
        type: "string"
    }, {
        name: "JAM",
        type: "string"
    }, {
        name: "KAPASITAS",
        type: "int"
    }, {
        name: "LIBUR",
        type: "int"
    }, {
        name: "STATUS",
        type: "int"
    }],
    idProperty: "ID"
});
Ext.define("antrian.hfis.jadwaldokter.referensi.Store", {
    extend: "data.store.Store",
    model: "antrian.hfis.jadwaldokter.referensi.Model",
    alias: "store.antrian-hfis-jadwaldokter-referensi-store"
});
Ext.define("antrian.jadwal.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/jadwaldokter",
    fields: [{
        name: "ID",
        type: "int"
    }, {
        name: "DOKTER",
        type: "string"
    }, {
        name: "RUANGAN",
        type: "string"
    }, {
        name: "HARI",
        type: "int"
    }, {
        name: "MULAI",
        type: "string"
    }, {
        name: "SELESAI",
        type: "string"
    }, {
        name: "TANGGAL_AWAL",
        type: "date",
        dateFormat: "Y-m-d"
    }, {
        name: "TANGGAL_AKHIR",
        type: "date",
        dateFormat: "Y-m-d"
    }, {
        name: "STATUS",
        type: "int"
    }],
    idProperty: "ID"
});
Ext.define("antrian.jadwal.Store", {
    extend: "data.store.Store",
    model: "antrian.jadwal.Model",
    alias: "store.antrian-jadwal-store"
});
Ext.define("antrian.jadwal.pergantian.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/jadwaldokter/pergantian/detail",
    fields: [{
        name: "ID_PERGANTIAN",
        type: "int"
    }, {
        name: "ID_TANGGAL",
        type: "string"
    }, {
        name: "STATUS",
        type: "int"
    }]
});
Ext.define("antrian.jadwal.pergantian.Store", {
    extend: "data.store.Store",
    model: "antrian.jadwal.pergantian.Model",
    alias: "store.antrian-jadwal-pergantian-store"
});
Ext.define("antrian.jadwal.tanggal.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/jadwal/tanggal",
    fields: [{
        name: "ID",
        type: "int"
    }, {
        name: "DOKTER",
        type: "string"
    }, {
        name: "RUANGAN",
        type: "string"
    }, {
        name: "HARI",
        type: "int"
    }, {
        name: "MULAI",
        type: "string"
    }, {
        name: "SELESAI",
        type: "string"
    }, {
        name: "TANGGAL_AWAL",
        type: "date",
        dateFormat: "Y-m-d"
    }, {
        name: "TANGGAL_AKHIR",
        type: "date",
        dateFormat: "Y-m-d"
    }, {
        name: "STATUS",
        type: "int"
    }],
    idProperty: "ID"
});
Ext.define("antrian.jadwal.tanggal.Store", {
    extend: "data.store.Store",
    model: "antrian.jadwal.tanggal.Model",
    alias: "store.antrian-jadwal-tanggal-store"
});
Ext.define("antrian.jenispasien.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/jenispasien",
    fields: [{
        name: "ID",
        type: "int"
    }, {
        name: "DESKRIPSI",
        type: "string"
    }],
    idProperty: "ID"
});
Ext.define("antrian.jenispasien.Store", {
    extend: "data.store.Store",
    model: "antrian.jenispasien.Model",
    alias: "store.antrian-jenispasien-store"
});
Ext.define("antrian.monitoring.kemkes.jadwaldokter.Model", {
    extend: "data.model.Model",
    serviceName: "kemkes/jadwal/dokter",
    fields: [{
        name: "ID",
        type: "int"
    }, {
        name: "KLINIK",
        type: "string"
    }, {
        name: "DOKTER",
        type: "string"
    }, {
        name: "HARI",
        type: "int"
    }, {
        name: "JAM_MULAI",
        type: "string"
    }, {
        name: "JAM_TUTUP",
        type: "string"
    }, {
        name: "KUOTA",
        type: "int"
    }, {
        name: "STATUS",
        type: "int",
        defaultValue: 1
    }],
    idProperty: "ID"
});
Ext.define("antrian.monitoring.kemkes.jadwaldokter.Store", {
    extend: "data.store.Store",
    model: "antrian.monitoring.kemkes.jadwaldokter.Model",
    alias: "store.antrian-monitoring-kemkes-jadwaldokter-store"
});
Ext.define("antrian.monitoring.kemkes.reservasi.Model", {
    extend: "data.model.Model",
    serviceName: "kemkes/reservasi/antrian",
    fields: [{
        name: "ID",
        type: "int"
    }, {
        name: "PASIEN",
        type: "int"
    }, {
        name: "NAMA",
        type: "string"
    }, {
        name: "TEMPAT_LAHIR",
        type: "date"
    }, {
        name: "TANGGAL_LAHIR",
        type: "date"
    }, {
        name: "KONTAK",
        type: "string"
    }, {
        name: "JENIS",
        type: "int"
    }, {
        name: "TANGGAL_DAFTAR",
        type: "date"
    }, {
        name: "TANGGAL_KUNJUNGAN",
        type: "date"
    }, {
        name: "RUANGAN",
        type: "string"
    }, {
        name: "DOKTER",
        type: "string"
    }, {
        name: "PENJAMIN",
        type: "string"
    }, {
        name: "NOMOR",
        type: "int"
    }, {
        name: "JAM",
        type: "string"
    }, {
        name: "STATUS",
        type: "int",
        defaultValue: 1
    }],
    idProperty: "ID"
});
Ext.define("antrian.monitoring.kemkes.reservasi.Store", {
    extend: "data.store.Store",
    model: "antrian.monitoring.kemkes.reservasi.Model",
    alias: "store.antrian-monitoring-kemkes-reservasi-store"
});
Ext.define("antrian.kirimantrian.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/plugins/kirimAntrian",
    fields: []
});
Ext.define("antrian.kirimantrian.Store", {
    extend: "data.store.Store",
    model: "antrian.kirimantrian.Model",
    alias: "store.antrian-kirimantrian-store"
});
Ext.define("antrian.loket.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/loketantrian",
    fields: [{
        name: "ID",
        type: "int"
    }, {
        name: "DESKRIPSI",
        type: "string"
    }, {
        name: "STATUS",
        type: "int"
    }],
    idProperty: "ID"
});
Ext.define("antrian.loket.Store", {
    extend: "data.store.Store",
    model: "antrian.loket.Model",
    alias: "store.antrian-loket-store"
});
Ext.define("antrian.panggilantrian.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/panggilantrian",
    fields: [{
        name: "ID",
        type: "int"
    }, {
        name: "LOKET",
        type: "int"
    }, {
        name: "NOMOR",
        type: "int"
    }, {
        name: "POS",
        type: "string"
    }, {
        name: "CARA_BAYAR",
        type: "int"
    }, {
        name: "TANGGAL",
        type: "date",
        dateFormat: "Y-m-d"
    }],
    idProperty: "ID"
});
Ext.define("antrian.panggilantrian.Store", {
    extend: "data.store.Store",
    model: "antrian.panggilantrian.Model",
    alias: "store.antrian-panggilantrian-store"
});
Ext.define("antrian.panggilantrianpoli.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/panggilantrianpoli",
    fields: [{
        name: "ID",
        type: "int"
    }, {
        name: "POLI",
        type: "string"
    }, {
        name: "NOMOR",
        type: "int"
    }, {
        name: "TANGGAL",
        type: "date",
        dateFormat: "Y-m-d"
    }],
    idProperty: "ID"
});
Ext.define("antrian.panggilantrianpoli.Store", {
    extend: "data.store.Store",
    model: "antrian.panggilantrianpoli.Model",
    alias: "store.antrian-panggilantrianpoli-store"
});
Ext.define("antrian.pasien.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/pasien",
    fields: [{
        name: "NORM",
        type: "int"
    }, {
        name: "NAMA",
        type: "string"
    }, {
        name: "PANGGILAN",
        type: "string"
    }, {
        name: "GELAR_DEPAN",
        type: "string"
    }, {
        name: "GELAR_BELAKANG",
        type: "string"
    }, {
        name: "TEMPAT_LAHIR",
        type: "string"
    }, {
        name: "TANGGAL_LAHIR",
        type: "date",
        dateFormat: "Y-m-d H:t:s"
    }, {
        name: "JENIS_KELAMIN",
        type: "int"
    }, {
        name: "ALAMAT",
        type: "string"
    }, {
        name: "RT",
        type: "string"
    }, {
        name: "RW",
        type: "string"
    }, {
        name: "KODEPOS",
        type: "string"
    }, {
        name: "AGAMA",
        type: "int"
    }, {
        name: "PENDIDIKAN",
        type: "int"
    }, {
        name: "PEKERJAAN",
        type: "int"
    }, {
        name: "STATUS_PERKAWINAN",
        type: "int"
    }, {
        name: "GOLONGAN_DARAH",
        type: "int"
    }, {
        name: "KEWARGANEGARAAN",
        type: "int"
    }, {
        name: "TANGGAL",
        type: "date",
        dateFormat: "Y-m-d H:t:s"
    }, {
        name: "OLEH",
        type: "int"
    }, {
        name: "STATUS",
        type: "int",
        defaultValue: 1
    }],
    idProperty: "NORM"
});
Ext.define("antrian.pasien.Store", {
    extend: "data.store.Store",
    model: "antrian.pasien.Model",
    alias: "store.antrian-pasien-store"
});
Ext.define("antrian.pengaturan.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/pengaturan",
    fields: [{
        name: "ID",
        type: "int"
    }, {
        name: "LIMIT_DAFTAR",
        type: "int"
    }, {
        name: "DURASI",
        type: "int"
    }, {
        name: "MULAI",
        type: "date",
        dateFormat: "H:i:s"
    }, {
        name: "STATUS",
        type: "int",
        defaultValue: 1
    }],
    idProperty: "ID"
});
Ext.define("antrian.pengaturan.Store", {
    extend: "data.store.Store",
    model: "antrian.pengaturan.Model",
    alias: "store.antrian-pengaturan-store"
});
Ext.define("antrian.polibpjs.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/refpolibpjs",
    fields: [{
        name: "KDPOLI",
        type: "string"
    }, {
        name: "NMPOLI",
        type: "string"
    }, {
        name: "ANTRIAN",
        type: "int"
    }, {
        name: "STATUS",
        type: "int",
        defaultValue: 1
    }],
    idProperty: "KDPOLI"
});
Ext.define("antrian.polibpjs.Store", {
    extend: "data.store.Store",
    model: "antrian.polibpjs.Model",
    alias: "store.antrian-polibpjs-store"
});
Ext.define("antrian.polibpjs.referensi.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/bpjs/getRefPoli",
    fields: []
});
Ext.define("antrian.polibpjs.referensi.Store", {
    extend: "data.store.Store",
    model: "antrian.polibpjs.referensi.Model",
    alias: "store.antrian-polibpjs-referensi-store"
});
Ext.define("antrian.posantrian.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/posantrian",
    fields: [{
        name: "ID",
        type: "int"
    }, {
        name: "DESKRIPSI",
        type: "string"
    }, {
        name: "STATUS",
        type: "int",
        defaultValue: 1
    }],
    idProperty: "ID"
});
Ext.define("antrian.posantrian.Store", {
    extend: "data.store.Store",
    model: "antrian.posantrian.Model",
    alias: "store.antrian-posantrian-store"
});
Ext.define("antrian.reservasi.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/reservasi",
    fields: [{
        name: "ID",
        type: "string"
    }, {
        name: "TANGGALKUNJUNGAN",
        type: "date",
        dateFormat: "Y-m-d"
    }, {
        name: "NORM",
        type: "int"
    }, {
        name: "NAMA",
        type: "string"
    }, {
        name: "TEMPAT_LAHIR",
        type: "string"
    }, {
        name: "TANGGAL_LAHIR",
        type: "date",
        dateFormat: "Y-m-d"
    }, {
        name: "ALAMAT",
        type: "string"
    }, {
        name: "PEKERJAAN",
        type: "int"
    }, {
        name: "INSTANSI_ASAL",
        type: "string"
    }, {
        name: "JK",
        type: "string"
    }, {
        name: "WILAYAH",
        type: "string"
    }, {
        name: "POLI",
        type: "int"
    }, {
        name: "DOKTER",
        type: "string"
    }, {
        name: "CARABAYAR",
        type: "string"
    }, {
        name: "CONTACT",
        type: "string"
    }, {
        name: "NO",
        type: "int"
    }, {
        name: "JAM",
        type: "string"
    }, {
        name: "POS_ANTRIAN",
        type: "string"
    }, {
        name: "NO_REF_BPJS",
        type: "string"
    }, {
        name: "JENIS",
        type: "int"
    }, {
        name: "STATUS",
        type: "int",
        defaultValue: 1
    }, {
        name: "JENIS_APLIKASI",
        type: "int"
    }],
    idProperty: "ID"
});
Ext.define("antrian.reservasi.Store", {
    extend: "data.store.Store",
    model: "antrian.reservasi.Model",
    alias: "store.antrian-reservasi-store"
});
Ext.define("antrian.responantrian.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/plugins/responAntrian",
    fields: []
});
Ext.define("antrian.ruangan.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/ruangan",
    fields: [{
        name: "ID",
        type: "int"
    }, {
        name: "DESKRIPSI",
        type: "string"
    }, {
        name: "ANTRIAN",
        type: "string"
    }, {
        name: "STATUS",
        type: "int",
        defaultValue: 1
    }, {
        name: "LIMIT_DAFTAR",
        type: "int",
        defaultValue: 100
    }, {
        name: "DURASI_PENDAFTARAN",
        type: "int"
    }, {
        name: "DURASI_PELAYANAN",
        type: "int"
    }, {
        name: "MULAI",
        type: "date",
        dateFormat: "H:i:s"
    }, {
        name: "JUMLAH_MEJA",
        type: "int"
    }],
    idProperty: "ID"
});
Ext.define("antrian.ruangan.Store", {
    extend: "data.store.Store",
    model: "antrian.ruangan.Model",
    alias: "store.ruangan-antrian-store"
});
Ext.define("antrian.useraksespos.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/useraksesposantrian",
    fields: [{
        name: "ID",
        type: "int"
    }, {
        name: "USER",
        type: "int"
    }, {
        name: "POS_ANTRIAN",
        type: "string"
    }, {
        name: "STATUS",
        type: "int",
        defaultValue: 1
    }],
    idProperty: "ID"
});
Ext.define("antrian.useraksespos.Store", {
    extend: "data.store.Store",
    model: "antrian.useraksespos.Model",
    alias: "store.useraksespos-antrian-store"
});
Ext.define("antrian.waktutungguantrian.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/waktutungguantrian",
    fields: [{
        name: "ID",
        type: "int"
    }, {
        name: "TASK_ID",
        type: "int"
    }, {
        name: "TANGGAL",
        type: "date",
        dateFormat: "Y-m-d H:i:s"
    }, {
        name: "ANTRIAN",
        type: "string"
    }],
    idProperty: "ID"
});
Ext.define("antrian.waktutungguantrian.Store", {
    extend: "data.store.Store",
    model: "antrian.waktutungguantrian.Model",
    alias: "store.waktu-tunggu-antrian-store"
});
Ext.define("antrian.waktutungguantrian.batal.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/plugins/batalkanAntrian",
    fields: []
});
Ext.define("antrian.waktutungguantrian.manual.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/plugins/kirimWaktuTunggu",
    fields: []
});
Ext.define("antrian.waktutungguantrian.plugins.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/plugins/updateTaskId",
    fields: []
});
Ext.define("antrian.waktutungguantrian.update.Model", {
    extend: "data.model.Model",
    serviceName: "registrasionline/bpjs/updateWaktuTunggu",
    fields: []
});
Ext.define("antrian.waktutungguantrian.update.Store", {
    extend: "data.store.Store",
    model: "antrian.waktutungguantrian.update.Model",
    alias: "store.waktu-tunggu-antrian-update-store"
});
Ext.define("antrian.Audio", {
    extend: "com.Form",
    xtype: "antrian-audio",
    audios: [],
    idx: 0,
    parent: undefined,
    audioCount: 10,
    constructor: function (a) {
        var f = this;
        f.config = Ext.apply(f.config, a);
        f.callParent();
        f.initConfig(f.config);
        var d = 0;
        if (a.parent.viewModel.data.posAntrian == "E") {
            f.audioCount = 9
        } else {
            f.audioCount = 10
        }
        for (var b = 0; b <= f.audioCount - 1; b++) {
            var e = Ext.create("com.Audio", {
                controls: false,
                volume: 10
            });
            e.on("ended", function () {
                if (f.idx < (f.audios.length - 1)) {
                    f.idx++;
                    f.audios[f.idx].play()
                } else {
                    f.idx = 0;
                    f.config.parent.onAKhir()
                }
            });
            f.audios.push(e)
        }
    },
    getAudios: function () {
        return this.audios
    },
    speechAntrian: function (a) {
        var e = this,
            d = location.origin + location.pathname,
            b = d + "resources/ringtone/";
        if (Ext.isArray(a)) {
            Ext.Array.each(a, function (f, g) {
                if (g == 0) {
                    e.audios[g].setSrc(b + f)
                } else {
                    e.audios[g].setSrc(b + f);
                    e.audios[g].load()
                }
            })
        } else {
            e.audios[e.idx].setSrc(b + a)
        }
        e.audios[e.idx].play();
        e.audios[e.idx].on("ended", function () {
            return 1
        })
    },
    load: function (a) {
        var d = this;
        if (Ext.isArray(a)) {
            var b = Ext.JSON.encode(a);
            d.request("tts?q=" + b + "&ty=json")
        } else {
            d.request("tts?q=" + a + "&ty=json")
        }
    },
    replay: function () {
        this.audios[this.idx].play()
    },
    destroy: function () {
        var d = this;
        if (d.audios.length > 0) {
            for (var a = 0; a <= d.audioCount - 1; a++) {
                var b = d.audios.pop();
                if (b) {
                    if (b.destroy) {
                        b.destroy()
                    }
                }
            }
            d.audios = []
        }
        d.callParent()
    }
});
Ext.define("antrian.registrasi.ComboCaraBayar", {
    extend: "com.Combo",
    alias: "widget.antrian-combo-carabayar",
    viewModel: {
        stores: {
            store: {
                type: "antrian-carabayar-store"
            }
        }
    },
    bind: {
        store: "{store}"
    },
    emptyText: "[ Cara Bayar ]",
    queryMode: "local",
    displayField: "DESKRIPSI",
    valueField: "ID",
    allowBlank: false,
    typeAhead: true,
    forceSelection: true,
    validateOnBlur: true
});
Ext.define("antrian.registrasi.ComboJadwalDokter", {
    extend: "com.Combo",
    alias: "widget.antrian-combo-jadwal-dokter",
    viewModel: {
        stores: {
            store: {
                type: "antrian-hfis-jadwaldokter-store"
            }
        }
    },
    bind: {
        store: "{store}"
    },
    emptyText: "[ Dokter ]",
    queryMode: "local",
    displayField: "NM_DOKTER",
    valueField: "KD_DOKTER",
    typeAhead: true,
    minChars: 2,
    tpl: Ext.create("Ext.XTemplate", '<tpl for=".">', '<div class="x-boundlist-item">', "[ {NM_DOKTER} ] - <strong>Pukul: {JAM}</strong>", "</div>", "</tpl>"),
    displayTpl: Ext.create("Ext.XTemplate", '<tpl for=".">', "{NM_DOKTER} - {JAM}", "</tpl>")
});
Ext.define("antrian.registrasi.ComboJenis", {
    extend: "com.Combo",
    alias: "widget.antrian-combo-jenis",
    viewModel: {
        stores: {
            store: {
                type: "antrian-jenispasien-store"
            }
        }
    },
    bind: {
        store: "{store}"
    },
    emptyText: "[ Jenis Pasien ]",
    queryMode: "local",
    displayField: "DESKRIPSI",
    valueField: "ID",
    allowBlank: false,
    typeAhead: true,
    forceSelection: true,
    validateOnBlur: true
});
Ext.define("antrian.ComboLoket", {
    extend: "com.Combo",
    alias: "widget.antrian-combo-loket",
    viewModel: {
        stores: {
            store: {
                type: "antrian-loket-store"
            }
        }
    },
    bind: {
        store: "{store}"
    },
    emptyText: "[ Loket Antrian ]",
    queryMode: "local",
    displayField: "DESKRIPSI",
    valueField: "ID",
    allowBlank: false,
    typeAhead: true,
    forceSelection: true,
    validateOnBlur: true
});
Ext.define("antrian.ComboPoli", {
    extend: "com.Combo",
    alias: "widget.antrian-combo-poli",
    viewModel: {
        stores: {
            store: {
                type: "ruangan-antrian-store"
            }
        }
    },
    bind: {
        store: "{store}"
    },
    emptyText: "[ Poli Tujuan ]",
    queryMode: "remote",
    displayField: "DESKRIPSI",
    queryParam: "QUERY",
    valueField: "ID",
    allowBlank: false,
    typeAhead: true,
    minChars: 2,
    forceSelection: true,
    validateOnBlur: true
});
Ext.define("antrian.registrasi.ComboPoliBpjs", {
    extend: "com.Combo",
    alias: "widget.antrian-combo-poli-bpjs",
    viewModel: {
        stores: {
            store: {
                type: "antrian-polibpjs-store"
            }
        }
    },
    bind: {
        store: "{store}"
    },
    emptyText: "[ Poliklinik ]",
    queryMode: "remote",
    hideTrigger: true,
    queryParam: "QUERY",
    displayField: "NMPOLI",
    valueField: "KDPOLI",
    typeAhead: true,
    minChars: 2,
    tpl: Ext.create("Ext.XTemplate", '<tpl for=".">', '<div class="x-boundlist-item">', "[ {KDPOLI} ] - <strong>{NMPOLI}</strong>", "</div>", "</tpl>"),
    displayTpl: Ext.create("Ext.XTemplate", '<tpl for=".">', "{KDPOLI} - {NMPOLI}", "</tpl>")
});
Ext.define("antrian.ComboPosAntrian", {
    extend: "com.Combo",
    alias: "widget.antrian-combo-pos-antrian",
    viewModel: {
        stores: {
            store: {
                type: "antrian-posantrian-store"
            }
        }
    },
    bind: {
        store: "{store}"
    },
    emptyText: "[ Pilih Pos Antrian ]",
    queryMode: "local",
    displayField: "DESKRIPSI",
    valueField: "NOMOR",
    typeAhead: true,
    forceSelection: true,
    validateOnBlur: true
});
Ext.define("antrian.registrasi.Form.Baru", {
    extend: "com.Form",
    xtype: "antrian-form-baru",
    requires: ["Ext.picker.Time"],
    layout: {
        type: "vbox",
        align: "stretch"
    },
    border: false,
    bodyPadding: 0,
    defaults: {
        margin: "0 0 10 0"
    },
    items: [{
        xtype: "hiddenfield",
        name: "JENIS",
        value: 2
    }, {
        xtype: "textfield",
        emptyText: "[ Nama Pasien ]",
        name: "NAMA"
    }, {
        xtype: "textfield",
        emptyText: "[ Tempat Lahir ]",
        name: "TEMPAT_LAHIR"
    }, {
        xtype: "datefield",
        emptyText: "[ Tanggal Lahir ]",
        format: "Y-m-d",
        name: "TANGGAL_LAHIR"
    }, {
        xtype: "textfield",
        emptyText: "[ Telepon / Kontak ]",
        name: "CONTACT"
    }]
});
Ext.define("antrian.registrasi.Form.Form", {
    extend: "com.Form",
    xtype: "antrian-form-form",
    requires: ["Ext.picker.Time"],
    viewModel: {
        data: {
            isPasienLama: true,
            isPasienBaru: true
        }
    },
    controller: "antrian-form-form",
    layout: {
        type: "vbox",
        align: "stretch"
    },
    items: [{
        xtype: "antrian-combo-jenis",
        name: "JENIS",
        firstLoad: true,
        allowBlank: false,
        listeners: {
            change: "onChange"
        }
    }, {
        xtype: "antrian-form-baru",
        bind: {
            hidden: "{isPasienBaru}"
        }
    }, {
        xtype: "antrian-form-lama",
        bind: {
            hidden: "{isPasienLama}"
        }
    }],
    getJenis: function () {
        var d = this,
            f = d.down("[name=JENIS]").getValue(),
            a = d.down("antrian-form-baru").getValues(),
            b = d.down("antrian-form-lama").getValues(),
            e = [];
        e.push({
            JENIS: f,
            BARU: a,
            LAMA: b
        });
        return e
    }
});
Ext.define("antrian.registrasi.Form.FormController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-form-form",
    onChange: function (d, f, a, b) {
        var e = this;
        if (f != "") {
            if (f == 1) {
                e.getViewModel().set("isPasienLama", false);
                e.getViewModel().set("isPasienBaru", true)
            } else {
                e.getViewModel().set("isPasienLama", true);
                e.getViewModel().set("isPasienBaru", false)
            }
        }
    }
});
Ext.define("antrian.registrasi.Form.Lama", {
    extend: "com.Form",
    xtype: "antrian-form-lama",
    requires: ["Ext.picker.Time"],
    layout: {
        type: "vbox",
        align: "stretch"
    },
    border: false,
    bodyPadding: 0,
    defaults: {
        margin: "0 0 10 0"
    },
    initComponent: function () {
        var a = this;
        a.items = [{
            xtype: "textfield",
            emptyText: "[ No.RM ]",
            name: "NORM"
        }, {
            xtype: "datefield",
            emptyText: "[ Tanggal Lahir ]",
            name: "TANGGAL_LAHIR",
            format: "Y-m-d",
            maxValue: a.getSysdate()
        }];
        a.callParent(arguments)
    }
});
Ext.define("antrian.registrasi.Form.Workspace", {
    extend: "com.Form",
    xtype: "antrian-form-workspace",
    viewModel: {
        data: {
            kjgn: undefined
        }
    },
    layout: "border",
    bodyPadding: 1,
    items: [{
        region: "center",
        xtype: "antrian-form-form",
        border: false,
        scrollable: "y"
    }],
    getData: function () {
        var a = this.down("antrian-form-form").getJenis();
        return a
    }
});
Ext.define("antrian.registrasi.Register.Form", {
    extend: "com.Form",
    xtype: "antrian-register-form",
    model: "antrian.reservasi.Model",
    requires: ["Ext.picker.Time"],
    layout: {
        type: "vbox",
        align: "stretch"
    },
    defaults: {
        margin: "0 0 10 0"
    },
    initComponent: function () {
        var a = this;
        a.items = [{
            xtype: "textfield",
            emptyText: "[ No.Rekam Medis ]",
            name: "NORM",
            disabled: true
        }, {
            xtype: "textfield",
            emptyText: "[ Nama Pasien ]",
            name: "NAMA",
            disabled: true
        }, {
            xtype: "textfield",
            emptyText: "[ Tempat Lahir ]",
            name: "TEMPAT_LAHIR",
            disabled: true
        }, {
            xtype: "datefield",
            format: "Y-m-d",
            emptyText: "[ Tanggal Lahir ]",
            name: "TANGGAL_LAHIR",
            disabled: true
        }, {
            xtype: "textfield",
            emptyText: "[ Telepon / Kontak ]",
            name: "CONTACT",
            allowBlank: false
        }, {
            xtype: "antrian-combo-poli",
            emptyText: "[ Poli Tujuan ]",
            name: "POLI",
            firstLoad: true,
            allowBlank: false
        }, {
            xtype: "datefield",
            emptyText: "[ Tanggal Kunjungan ]",
            name: "TANGGALKUNJUGAN",
            format: "Y-m-d",
            minValue: a.getSysdate()
        }, {
            xtype: "antrian-combo-carabayar",
            emptyText: "[ Cara Bayar ]",
            name: "CARABAYAR",
            firstLoad: true,
            allowBlank: false
        }];
        a.callParent(arguments)
    }
});
Ext.define("antrian.registrasi.Register.Workspace", {
    extend: "com.Form",
    xtype: "antrian-register-workspace",
    viewModel: {
        data: {
            idnPsn: undefined
        }
    },
    layout: "border",
    bodyPadding: 1,
    items: [{
        region: "center",
        xtype: "antrian-register-form",
        border: false,
        scrollable: "y"
    }],
    onLoadRecord: function (d) {
        var b = this,
            a = b.down("antrian-register-form");
        a.createRecord(d);
        b.getViewModel().set("idnPsn", d)
    },
    getData: function () {
        var b = this,
            a = b.down("antrian-register-form"),
            d = a.getRecord();
        return d
    }
});
Ext.define("antrian.Sukses.Form", {
    extend: "com.Form",
    xtype: "antrian-sukses-form",
    viewModel: {
        data: {
            nomor: "00",
            namars: "RS.SIMpel",
            poliklinik: "-",
            tglkunjungan: "0000-00-00",
            jamkunjungan: "00:00"
        }
    },
    layout: {
        type: "hbox"
    },
    border: false,
    bodyPadding: 15,
    initComponent: function () {
        var a = this;
        a.items = [{
            layout: {
                type: "vbox",
                align: "center"
            },
            border: false,
            width: 150,
            defaultType: "component",
            items: [{
                html: "Nomor Antrian",
                margin: "0 0 15 0"
            }, {
                flex: 1,
                bind: {
                    html: "{nomor}"
                },
                margin: "0 0 5 0",
                style: "font-size:35px"
            }]
        }, {
            layout: {
                type: "vbox",
                align: "stretch"
            },
            border: false,
            defaultType: "component",
            items: [{
                bind: {
                    html: "Rumah Sakit Tujuan : {namars}",
                    margin: "0 0 5 0"
                }
            }, {
                bind: {
                    html: "Klinik : {poliklinik}",
                    margin: "0 0 5 0"
                }
            }, {
                bind: {
                    html: "Jadwal Kunjungan : {tglkunjungan} ,  Jam : {jamkunjungan}",
                    margin: "0 0 25 0"
                }
            }, {
                html: "<i><b>* Harap Membawa Bukti Transaksi Dan Surat Rujukan Beserta Kelengkapan Berkas Lainnya</b></i>",
                margin: "0 0 5 0",
                style: "font-size:9px"
            }]
        }];
        a.callParent(arguments)
    },
    onLoadRecord: function (d) {
        var a = this,
            b = Ext.Date.format(d.get("TANGGALKUNJUGAN"), "d M Y");
        a.getViewModel().set("nomor", d.get("NO"));
        a.getViewModel().set("namars", "RSIA. Nasanapura Palu");
        a.getViewModel().set("poliklinik", d.get("REFERENSI").POLI.DESKRIPSI);
        a.getViewModel().set("tglkunjungan", b);
        a.getViewModel().set("jamkunjungan", d.get("JAM"))
    },
    onCetak: function () {
        var a = this,
            b = "1803220001";
        a.cetak({
            NAME: "penjualan.CetakFakturPenjualan",
            TYPE: _type,
            EXT: _ext,
            PARAMETER: {
                PTAGIHAN: b,
                PJENIS: 4
            },
            REQUEST_FOR_PRINT: _print,
            PRINT_NAME: "CetakResep"
        }, function (d) {
            a.printPreview(_title, d)
        })
    }
});
Ext.define("antrian.Sukses.Workspace", {
    extend: "com.Form",
    xtype: "antrian-sukses-workspace",
    viewModel: {
        stores: {
            store: {
                type: "antrian-reservasi-store"
            }
        }
    },
    layout: "border",
    bodyPadding: 1,
    items: [{
        region: "center",
        type: "component",
        items: [{
            xtype: "antrian-sukses-form",
            border: false,
            scrollable: "x"
        }],
        scrollable: "y"
    }],
    onLoadRecord: function (d) {
        var b = this,
            a = b.getViewModel().get("store");
        if (a) {
            a.removeAll();
            a.queryParams = {
                NOMOR: d.NOMOR
            };
            a.load({
                callback: function (h, f, g) {
                    if (g) {
                        var e = b.down("antrian-sukses-form");
                        e.onLoadRecord(h[0])
                    }
                }
            })
        }
    },
    getData: function () {
        var b = this,
            a = b.getViewModel().get("store").getData().items[0];
        return a
    }
});
Ext.define("antrian.registrasi.Workspace", {
    extend: "com.Form",
    alias: "widget.antrian-workspace",
    controller: "antrian-workspace",
    viewModel: {
        stores: {
            store: {
                type: "antrian-pasien-store"
            }
        },
        data: {
            card1: false,
            card2: false,
            card3: false
        }
    },
    xtype: "layout-card",
    layout: "card",
    border: false,
    plugins: "responsive",
    width: 129,
    responsiveConfig: {
        "width < 600": {
            style: "height:200px;margin-left:auto;margin-right:auto;padding:20px;background:#24928f"
        },
        "width >= 600": {
            style: "height:200px;margin-left:150px;margin-right:150px; margin-top:10px;margin-bottom:10px;border-radius:1px;box-shadow:white 1px 2px 3px 1px"
        }
    },
    tbar: {
        reference: "progress",
        defaultButtonUI: "wizard-soft-purple",
        cls: "wizardprogressbar",
        defaults: {
            disabled: true,
            iconAlign: "top",
            plugins: "responsive"
        },
        layout: {
            pack: "center"
        },
        items: [{
            step: 0,
            iconCls: "fa fa-user",
            bind: {
                pressed: "{card1}"
            },
            enableToggle: true,
            text: "Verifikasi"
        }, {
            step: 1,
            bind: {
                pressed: "{card2}"
            },
            iconCls: "fa fa-home",
            enableToggle: true,
            text: "Tujuan Pasien"
        }, {
            step: 2,
            bind: {
                pressed: "{card3}"
            },
            iconCls: "fa fa-check-circle",
            enableToggle: true,
            text: "Selesai"
        }]
    },
    bbar: ["->", {
        itemId: "card-home",
        iconCls: "x-fa fa-home",
        text: "Home",
        xtype: "button",
        reference: "btnHome",
        hidden: true,
        handler: "doHome"
    }, {
        itemId: "card-prev",
        text: "&laquo; Previous",
        ui: "soft-blue",
        reference: "btnShowPrevious",
        handler: "showPrevious",
        disabled: true
    }, {
        itemId: "card-next",
        text: "Next &raquo;",
        ui: "soft-blue",
        reference: "btnShowNext",
        handler: "showNext"
    }, {
        itemId: "card-finish",
        text: "Simpan &raquo;",
        ui: "soft-green",
        iconCls: "x-fa fa-save",
        xtype: "button",
        reference: "btnSimpan",
        hidden: true,
        handler: "onSimpan"
    }, {
        itemId: "card-cetak",
        iconCls: "x-fa fa-print",
        text: "Cetak & Download",
        ui: "soft-blue",
        xtype: "button",
        reference: "btnCetak",
        hidden: true,
        handler: "onCetak"
    }],
    items: [{
        id: "card-0",
        xtype: "antrian-form-workspace",
        reference: "formidentitas",
        border: false
    }, {
        id: "card-1",
        xtype: "antrian-register-workspace",
        reference: "formregister",
        border: false
    }, {
        id: "card-2",
        xtype: "antrian-sukses-workspace",
        reference: "formsuksesregistrasi",
        border: false
    }],
    onLoadRecord: function (d) {
        var b = this,
            a = b.down("antrian-form-workspace");
        b.getViewModel().set("card1", true)
    },
    isCekIdentitas: function (d) {
        var b = this,
            a = b.getViewModel().get("store");
        if (d) {
            a.removeAll();
            a.queryParams = {
                NORM: d.NORM,
                TANGGAL_LAHIR: d.TANGGAL
            };
            a.load({
                callback: function (g, e, f) {
                    if (f) {
                        return g
                    }
                }
            })
        }
    }
});
Ext.define("antrian.registrasi.WorkspaceController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-workspace",
    onTabChange: function (d, a) {
        var b = this.getView().rec;
        if (a.load) {
            a.onLoadRecord({})
        }
    },
    showNext: function (b) {
        var e = this,
            d = e.getView().getLayout().getActiveItem(),
            a = e.getView().getViewModel().get("store"),
            f = d.getData();
        if (f[0].JENIS) {
            if (f[0].JENIS == 1) {
                if (f[0].LAMA.NORM) {
                    if (f[0].LAMA.TANGGAL_LAHIR) {
                        var g = e.getView().isCekIdentitas({
                            NORM: f[0].LAMA.NORM,
                            TANGGAL: f[0].LAMA.TANGGAL_LAHIR
                        });
                        a.doAfterLoad = function (h) {
                            c = [];
                            h.each(function (j) {
                                c.push({
                                    JENIS: 1,
                                    NORM: j.get("NORM"),
                                    NAMA: j.get("NAMA"),
                                    TEMPAT_LAHIR: j.get("TEMPAT_LAHIR"),
                                    TANGGAL_LAHIR: j.get("TANGGAL_LAHIR"),
                                    STATUS: true
                                })
                            });
                            if (c.length > 0) {
                                e.getView().notifyMessage("Data Ditemukan");
                                e.doCardNavigation(1);
                                e.getView().getLayout().getActiveItem().onLoadRecord(c[0])
                            } else {
                                e.getView().notifyMessage("Data Tidak Ditemukan")
                            }
                        }
                    } else {
                        e.getView().notifyMessage("Isi Tanggal Lahir Terlebih Dahulu")
                    }
                } else {
                    e.getView().notifyMessage("Isi No.RM Terlebih Dahulu")
                }
            } else {
                e.doCardNavigation(1);
                e.getView().getLayout().getActiveItem().onLoadRecord(f[0].BARU)
            }
        }
    },
    showPrevious: function (a) {
        this.doCardNavigation(-1)
    },
    doCardNavigation: function (g) {
        var f = this,
            e = f.getReferences(),
            a = f.getView().getLayout(),
            b = a.activeItem.id.split("card-")[1],
            d = parseInt(b, 10) + g;
        a.setActiveItem(d);
        e.btnShowPrevious.setDisabled(d === 0);
        e.btnShowNext.setHidden(d === 1);
        e.btnSimpan.setHidden(d === 0);
        e.btnCetak.setHidden(true);
        e.btnHome.setHidden(true);
        f.onSetAktifbar(d)
    },
    onSetAktifbar: function (d) {
        var b = this,
            a = b.getViewModel();
        if (d == 1) {
            a.set("card1", false);
            a.set("card2", true);
            a.set("card3", false)
        } else {
            if (d == 2) {
                a.set("card1", false);
                a.set("card2", false);
                a.set("card3", true)
            } else {
                a.set("card1", true);
                a.set("card2", false);
                a.set("card3", false)
            }
        }
    },
    onSimpan: function (a) {
        var d = this,
            b = d.getView().getLayout().getActiveItem(),
            e = b.getData();
        if (e) {
            e.set("STATUS", 1);
            e.save({
                callback: function (j, f, g) {
                    var h = Ext.JSON.decode(f._response.responseText);
                    if (h.status) {
                        d.getView().notifyMessage("Sukses");
                        d.doCetakBukti(h)
                    } else {
                        d.getView().notifyMessage(h.PESAN);
                        d.showPrevious()
                    }
                }
            })
        }
    },
    doCetakBukti: function (g) {
        var f = this,
            e = f.getReferences(),
            a = f.getView().getLayout(),
            b = a.activeItem.id.split("card-")[1],
            d = parseInt(b, 10) + 1;
        a.setActiveItem(d);
        e.btnShowPrevious.setHidden(true);
        e.btnShowNext.setHidden(true);
        e.btnSimpan.setHidden(true);
        e.btnCetak.setHidden(false);
        e.btnHome.setHidden(false);
        f.getView().getLayout().getActiveItem().onLoadRecord(g);
        f.onSetAktifbar(2)
    },
    doHome: function (e) {
        var d = this,
            b = d.getReferences(),
            a = d.getView().getLayout();
        a.setActiveItem(0);
        b.btnShowPrevious.setHidden(false);
        b.btnShowPrevious.setDisabled(true);
        b.btnShowNext.setHidden(false);
        b.btnSimpan.setHidden(true);
        b.btnCetak.setHidden(true);
        b.btnHome.setHidden(true);
        d.onSetAktifbar(0)
    },
    onCetak: function (a) {
        var d = this,
            b = d.getView().getLayout().getActiveItem(),
            e = b.getData();
        var f = {
            NAME: "regonline.CetakBuktiRegistrasiOnline",
            TYPE: "Word",
            EXT: "doc",
            PARAMETER: {
                DOWNLOAD: true,
                PNOMOR: e.get("ID")
            },
            REQUEST_FOR_PRINT: false,
            PRINT_NAME: "CetakRL"
        };
        paramstring = JSON.stringify(f);
        json = Ext.JSON.decode(paramstring);
        f.PARAMETER.PNOMOR = e.get("ID");
        json = Ext.apply(json, f);
        this.getView().fireEvent("requestreport", "Bukti Registrasi", json, true)
    }
});
Ext.define("antrian.chat.Form", {
    extend: "com.Form",
    alias: "widget.antrian-chat-form",
    controller: "antrian-chat-form",
    viewModel: {
        data: {
            isResep: false
        }
    },
    layout: {
        type: "vbox",
        align: "stretch"
    },
    ui: "panel-cyan",
    border: true,
    header: {
        iconCls: "x-fa fa-file",
        padding: "7px 7px 7px 7px",
        title: "Chat"
    },
    fieldDefaults: {
        labelAlign: "top"
    },
    items: [{
        xtype: "container",
        margin: "0 0 10 0",
        layout: {
            type: "hbox",
            align: "stretch"
        },
        items: [{
            name: "LOKET",
            reference: "loket",
            xtype: "textfield",
            margin: "0 5 0 0",
            emptyText: "[ Loket ]",
            flex: 1
        }, {
            name: "PESAN",
            reference: "pesan",
            xtype: "textfield",
            margin: "0 5 0 0",
            emptyText: "[ Pesan ]",
            flex: 1
        }, {
            xtype: "button",
            iconCls: "x-fa fa-save",
            reference: "btnaddbarang",
            text: "Kirim",
            ui: "soft-green",
            handler: "onKirim"
        }]
    }, {
        name: "KETERANGAN",
        reference: "keterangan",
        xtype: "textarea",
        emptyText: "[ Keterangan ]",
        flex: 1
    }],
    onLoadRecord: function () {
        var a = this;
        a.onOpenConnection("display1")
    },
    onOpenConnection: function (b) {
        var a = Ext.create("Ext.ux.WebSocket", {
            url: "ws://" + window.location.hostname + ":8899",
            listeners: {
                open: function (d) {
                    console.log(d)
                },
                message: function (d, f) {
                    var e = JSON.parse(f);
                    console.log(e)
                },
                close: function (d) {
                    console.log("Close : " + d)
                }
            }
        })
    }
});
Ext.define("antrian.chat.FormController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-chat-form",
    onKirim: function () {
        var b = this,
            a = b.getReferences();
        console.log(a.pesan.getValue())
    }
});
Ext.define("antrian.chat.Workspace", {
    extend: "com.Form",
    alias: "widget.antrian-chat-workspace",
    layout: {
        type: "hbox",
        align: "stretch"
    },
    items: [{
        flex: 1,
        xtype: "antrian-chat-form",
        reference: "penjualanpengunjung"
    }],
    onLoadRecord: function () {
        var b = this,
            a = b.down("antrian-chat-form");
        a.focus("PESAN");
        a.onLoadRecord()
    }
});
Ext.define("antrian.display.List", {
    extend: "com.Form",
    xtype: "antrian-display-list",
    controller: "antrian-display-list",
    viewModel: {
        data: {
            formConfig: {
                disabledField: true
            }
        }
    },
    layout: {
        type: "vbox",
        align: "stretch"
    },
    flex: 1,
    border: true,
    posantrians: undefined,
    initComponent: function () {
        var a = this;
        a.items = [{
            xtype: "antrian-monitoring-posantrian-list",
            reference: "posantrianlist",
            ui: "panel-cyan",
            hideHeaders: true,
            posAntrianAkses: false,
            checkboxModel: true,
            flex: 1,
            selModel: {
                selType: "checkboxmodel",
                checkOnly: true
            }
        }, {
            xtype: "toolbar",
            ui: "toolbar-blue",
            items: ["->", {
                fieldLabel: "Tanggal Kunjungan",
                labelWidth: 180,
                name: "TANGGAL",
                reference: "tglkumjungan",
                width: 350,
                xtype: "datefield",
                margin: "0px 50px 0px 0px",
                emptyText: "[Pilih Tanggal Kunjungan]",
                format: "Y-m-d",
                bind: {
                    minValue: "{minDate}"
                },
                value: a.getSysdate(),
                maxValue: a.getSysdate(),
                allowBlank: false
            }, {
                xtype: "antrian-combo-loket",
                value: 4,
                fieldLabel: "Jumlah Loket",
                name: "LOKET",
                margin: "0px 50px 0px 0px",
                firstLoad: true,
                reference: "jumlahloket"
            }, {
                xtype: "combobox",
                reference: "jumlahkolom",
                fieldLabel: "Jumlah Kolom",
                value: 2,
                store: {
                    data: [{
                        ID: 1
                    }, {
                        ID: 2
                    }, {
                        ID: 3
                    }, {
                        ID: 4
                    }, {
                        ID: 5
                    }]
                },
                displayField: "ID",
                valueField: "ID"
            }, {
                text: "Mulai",
                listeners: {
                    click: "onMulai"
                }
            }]
        }];
        a.callParent(arguments)
    },
    onLoadRecord: function (e) {
        var d = this,
            a = d.down("antrian-monitoring-posantrian-list"),
            b = d.down("[reference=jumlahloket]");
        a.setParams({
            STATUS: 1
        });
        b.on("select", function (f, g) {
            d.jumlahloket = g.get("ID")
        });
        a.loadStore()
    }
});
Ext.define("antrian.display.ListController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-display-list",
    onClickRespon: function (d, g, b) {
        var e = this,
            a = e.getView(),
            f = d.getStore().getAt(g);
        if (f.get("STATUS") == 1) {
            e.onResponPasien(f)
        } else {
            a.notifyMessage("Data Sudah Di Respon")
        }
    },
    onMulai: function (l) {
        var f = this,
            h = f.getView(),
            g = f.getReferences(),
            k = [],
            e = g.posantrianlist,
            b = g.jumlahloket.getSelection().get("ID"),
            j = g.jumlahkolom.getSelection().get("ID"),
            d = g.tglkumjungan.getValue(),
            a = e.getSelection();
        Ext.Array.each(a, function (m) {
            k.push(m)
        });
        if (k.length == 0) {
            h.notifyMessage("Silahkan Pilih Salah Satu Pos Antrian Yang Ingin Ditampilkan");
            return
        }
        if (k.length > 1) {
            h.notifyMessage("Maksimal 1 Pos Antrian Yang Dapat Ditampilkan");
            return
        }
        dialog = h.openDialog("", true, 0, 0, {
            xtype: "antrian-display-workspace",
            title: "Informasi Antrian Online",
            ui: "panel-cyan",
            showCloseButton: true,
            hideColumns: true
        }, function (n, o) {
            var m = o.down("antrian-display-workspace");
            m.onLoadRecord(k, b, d, j)
        })
    }
});
Ext.define("antrian.display.View", {
    extend: "Ext.view.View",
    xtype: "antrian-display-view",
    viewModel: {
        stores: {
            store: {
                type: "antrian-panggilantrian-store"
            }
        },
        data: {
            jmlKolom: 2
        }
    },
    layout: {
        type: "vbox",
        align: "stretch"
    },
    loadMask: false,
    autoScroll: true,
    cls: "laporan-main laporan-dataview",
    bind: {
        store: "{store}"
    },
    jmlKolom: 1,
    itemSelector: "div.thumb-wrap",
    tpl: Ext.create("Ext.XTemplate", '<tpl for=".">', '<div class="thumb-wrap big-33 small-50" style="text-align:center;width:{KOLOM}%;display:grid;padding:15px;">', '<a class="thumb" href="#" style="background:#E9967A">', '<div class="thumb-title-container" style="float:left;width:50%">', '<div class="thumb-title"><p style="font-size:22px;color:green">LOKET</p></div>', '<div class="thumb-title">', '<p style="font-size:74px;color:green">{LOKET} </p>', "</div>", "</div>", '<div class="thumb-title-container" style="float:right;width:50%;border-left:2px #DDD solid">', '<div class="thumb-title"><p style="font-size:22px;">ANTRIAN</p></div>', '<div class="thumb-title">', '<p style="font-size:64px;"><u>{POS}{CARA_BAYAR}</u></p><p style="font-size:64px;">{[this.formatNomor(values.NOMOR)]}</p>', "</div>", "</div>", '<div class="thumb-title-container" style="float:left;width:100%;border-top:2px #DDD solid">', '<div class="thumb-title"><p style="font-size:22px;color:{[this.formatColorStatus(values.STATUS)]}">{[this.formatStatus(values.STATUS)]}</p></div>', "</div>", "</a>", "</div>", "</tpl>", {
        formatNomor: function (a) {
            var b = Ext.String.leftPad(a, 3, "0");
            return b
        },
        formatStatus: function (a) {
            if (a == 1) {
                return "Buka"
            }
            return "Tutup"
        },
        formatColorStatus: function (a) {
            if (a == 1) {
                return "green"
            }
            return "#DDD"
        }
    }),
    onLoadRecord: function (a) {
        var e = this,
            d = e.getViewModel().get("store");
        e.getViewModel().set("jmlKolom", a.KOLOM);
        if (d) {
            d.setQueryParams({
                POS: a.POS,
                TANGGAL: a.TANGGAL,
                KOLOM: a.KOLOM,
                start: 0,
                limit: a.LIMIT
            });
            d.load()
        }
    },
    getStore: function () {
        return this.getViewModel().get("store")
    },
    reload: function () {
        var b = this.getViewModel().get("store");
        if (b) {
            b.reload()
        }
    }
});
Ext.define("antrian.display.Workspace", {
    extend: "com.Form",
    xtype: "antrian-display-workspace",
    controller: "antrian-display-workspace",
    viewModel: {
        data: {
            ruangans: [],
            refreshTime: 0,
            instansi: undefined,
            infoTeks: "",
            infoKelasKamar: "",
            tglNow: "-",
            statusWebsocket: "Disconnect",
            suaraIn: "",
            panggilsuara: "",
            suaraOut: "",
            suaraNo1: "",
            suaraNo2: "",
            suaraNo3: "",
            posAntrian: ""
        },
        stores: {
            store: {
                type: "instansi-store"
            }
        }
    },
    audio: {
        integrasi: undefined,
        service: undefined
    },
    layout: {
        type: "vbox",
        align: "stretch"
    },
    defaults: {
        border: false
    },
    datapanggil: [],
    dataAntrian: [],
    audioCount: 1,
    audios: [],
    idx: 0,
    bodyStyle: "background-color:#aa8a51",
    initComponent: function () {
        if (window.location.protocol == "http:") {
            var d = "ws"
        } else {
            var d = "wss"
        }
        var b = this;
        var a = Ext.create("Ext.ux.WebSocket", {
            url: "ws://" + window.location.hostname + ":8899",
            listeners: {
                open: function (e) {
                    b.getViewModel().set("statusWebsocket", "Connected")
                },
                message: function (e, h) {
                    var f = JSON.parse(h);
                    if (f) {
                        if (f.act) {
                            if (f.act == "PANGGIL") {
                                if (b.getViewModel().get("posAntrian") == f.pos) {
                                    var g = f.pos + "" + f.carabayar + "" + f.nomor + "" + f.loket;
                                    if (!b.dataAntrian.includes(g)) {
                                        b.datapanggil.push(f);
                                        b.dataAntrian.push(g)
                                    }
                                    if (b.datapanggil.length === 1) {
                                        b.setProsesPanggil();
                                        b.onRefreshView(f.pos)
                                    }
                                }
                            }
                            if (f.act == "REFRESH_LOKET") {
                                if (b.getViewModel().get("posAntrian") == f.pos) {
                                    b.onRefreshView(f.pos)
                                }
                            }
                        }
                    }
                },
                close: function (e) {
                    b.getViewModel().set("statusWebsocket", "Disonnected Socket")
                }
            }
        });
        b.items = [{
            layout: {
                type: "hbox",
                align: "middle"
            },
            border: false,
            height: 50,
            bodyStyle: "padding-left:10px;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#356aa0), color-stop(100%,#356aa0));",
            items: [{
                xtype: "image",
                bind: {
                    src: "classic/resources/images/{instansi}.png"
                },
                id: "idImage",
                width: 40,
                border: false,
                bodyStyle: "background-color:transparent;"
            }, {
                flex: 1,
                bind: {
                    data: {
                        items: "{store.data.items}"
                    }
                },
                tpl: new Ext.XTemplate('<tpl for="items">', "{data.REFERENSI.PPK.NAMA}", "</tpl>"),
                border: false,
                bodyStyle: "background-color:transparent; font-size: 18px; color: white; "
            }, {
                xtype: "label",
                bind: {
                    html: "{tglNow}"
                },
                width: 350,
                border: false,
                style: "background-color:transparent; font-size: 20px; color: white; "
            }]
        }, {
            flex: 1,
            layout: {
                type: "hbox",
                align: "stretch"
            },
            defaults: {
                flex: 1,
                margin: "0 1 0 1"
            },
            border: false,
            reference: "informasi",
            items: [{
                flex: 2,
                border: false,
                layout: {
                    type: "vbox",
                    align: "stretch"
                },
                defaults: {
                    bodyStyle: "background-color:#D8F1EC"
                },
                items: [{
                    border: true,
                    style: "padding:15px;background-color:#A5C8D1;border-bottom:1px #DDD solid",
                    bodyStyle: "background-color:transparent",
                    html: '<iframe width="100%" height="300px" src="classic/resources/images/banner-antrian/video.mp4" frameborder="0" allow="accelerometer loop="true" autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
                }, {
                    xtype: "image",
                    src: "classic/resources/images/banner-antrian/image.jpg",
                    style: "width:100%;height:300px;padding:15px;background-color:#A5C8D1;border-bottom:1px #DDD solid",
                    border: true,
                    bodyStyle: "background-color:transparent;"
                }, {
                    xtype: "container",
                    style: "background-color:#A5C8D1;",
                    flex: 1
                }, {
                    style: "padding:10px;font-size:14px;border-top:1px #DDD solid;text-left:center;font-style:italic;color:#434343;background-color:#A5C8D1;",
                    bodyStyle: "background-color:transparent",
                    bind: {
                        html: "Status : {statusWebsocket}"
                    }
                }]
            }, {
                flex: 5,
                border: false,
                layout: {
                    type: "vbox",
                    align: "stretch"
                },
                bodyPadding: "20",
                items: [{
                    xtype: "component",
                    html: "NOMOR ANTRIAN YANG DI LAYANI",
                    style: "padding:15px;font-size:22px;text-align:center;color:#434343;background-color:#A5C8D1;border-radius:4px;margin-top:20px"
                }, {
                    flex: 1,
                    reference: "dataview",
                    style: "margin-top:10px;background-color:#FFF",
                    xtype: "antrian-display-view",
                    viewConfig: {
                        loadMask: false
                    }
                }]
            }]
        }, {
            layout: {
                type: "hbox",
                align: "middle"
            },
            height: 30,
            border: false,
            bodyStyle: "background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#356aa0), color-stop(100%,#356aa0));",
            items: [{
                xtype: "displayfield",
                flex: 1,
                fieldStyle: "background-color:transparent;font-size: 14px;  margin-left: 10px;color: white;",
                border: false,
                bind: {
                    value: "<marquee>{infoTeks}</marquee>"
                }
            }]
        }];
        b.callParent(arguments)
    },
    onLoadRecord: function (b, g, d, h) {
        var e = this,
            f = e.down("antrian-display-view"),
            a = e.getController();
        a.mulai();
        if (b) {
            f.onLoadRecord({
                POS: b[0].get("NOMOR"),
                LIMIT: g,
                TANGGAL: Ext.Date.format(d, "Y-m-d"),
                KOLOM: h
            });
            e.getViewModel().set("posAntrian", b[0].get("NOMOR"))
        }
        e.audio.service = Ext.create("antrian.Audio", {
            parent: e,
            audioCount: 10,
            posAntrian: e.getViewModel().get("posAntrian")
        });
        e.audios = e.audio.service.getAudios();
        if (e.audio.service) {
            e.add(e.audios)
        }
    },
    onAKhir: function (a) {
        var b = this,
            d = b.getViewModel().get("posAntrian");
        b.dataAntrian.shift();
        b.datapanggil.shift();
        b.setProsesPanggil();
        b.onRefreshView(d)
    },
    getPosAntrian: function () {
        var a = this,
            b = a.getViewModel().get("posAntrian");
        return b
    },
    onRefreshView: function (e) {
        var d = this,
            a = d.down("antrian-display-view").getStore(),
            b = a.getQueryParams().POS;
        if (b == e) {
            a.reload()
        }
    },
    runLogo: function () {
        if (this.deg == 360) {
            this.deg = 0
        } else {
            this.deg += 5
        }
        Ext.getCmp("idImage").setStyle("-webkit-transform: rotateY(" + this.deg + "deg)")
    },
    remove: function (d, b) {
        var a = Ext.Array.indexOf(d, b);
        if (a !== -1) {
            erase(d, a, 1)
        }
        return d
    },
    setProsesPanggil: function () {
        var b = this;
        if (b.datapanggil.length > 0) {
            var e = Ext.String.leftPad(b.datapanggil[0].nomor, 3, "0"),
                d = e.split("", 3);
            var a = {
                POS: b.datapanggil[0].pos,
                CB: b.datapanggil[0].carabayar,
                LOKET: b.datapanggil[0].loket,
                NOMOR1: d[0],
                NOMOR2: d[1],
                NOMOR3: d[2]
            };
            b.callAntrian(a)
        }
    },
    privates: {
        callAntrian: function (d) {
            var b = this;
            if (d) {
                if (d.POS == "E") {
                    var a = b.audio.service.speechAntrian(["in.wav", "nomor_antrian.mp3", d.POS + ".mp3", d.CB + ".mp3", d.NOMOR1 + ".mp3", d.NOMOR2 + ".mp3", d.NOMOR3 + ".mp3", "silahkan_kelaboratorium.mp3", "out.wav"])
                } else {
                    var a = b.audio.service.speechAntrian(["in.wav", "nomor_antrian.mp3", d.POS + ".mp3", d.CB + ".mp3", d.NOMOR1 + ".mp3", d.NOMOR2 + ".mp3", d.NOMOR3 + ".mp3", "silahkan_ke_loket.mp3", d.LOKET + ".mp3", "out.wav"])
                }
            }
        }
    }
});
Ext.define("antrian.display.WorkspaceController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-display-workspace",
    currentRefreshTimeRuangan: 0,
    refreshTime: 0,
    load: function (d) {
        var b = this,
            a = b.getView();
        a.createWidgets(d);
        a.items.each(function (j, f, g) {
            try {
                j.onLoadRecord({
                    STATUS: 1
                })
            } catch (h) {}
        })
    },
    onAfterRender: function () {
        var e = this,
            b = e.getViewModel(),
            a = b.get("store"),
            d = Ext.getStore("instansi");
        if (d) {
            b.set("store", d);
            new Ext.util.DelayedTask(function () {
                b.set("instansi", d.getAt(0).get("PPK"))
            }).delay(1000)
        } else {
            a.doAfterLoad = function (f, h, g, j) {
                if (j) {
                    if (h.length > 0) {
                        b.set("instansi", h[0].get("PPK"))
                    }
                }
            };
            a.load()
        }
    },
    mulai: function (d) {
        var b = this,
            a = b.getViewModel();
        b.currentRefreshTimeRuangan = a.get("refreshTime");
        b.refreshTime = a.get("refreshTime");
        if (b.task == undefined) {
            b.task = {
                run: function () {
                    a.set("tglNow", Ext.Date.format(new Date(), "l, d F Y H:i:s"));
                    if (b.currentRefreshTimeRuangan == 0) {
                        b.currentRefreshTimeRuangan = b.refreshTime
                    }
                    b.currentRefreshTimeRuangan--;
                    a.set("refreshTime", b.currentRefreshTimeRuangan)
                },
                interval: 1000
            };
            Ext.TaskManager.start(b.task)
        }
    },
    destroy: function () {
        var a = this;
        Ext.TaskManager.stop(a.task);
        a.callParent()
    }
});
Ext.define("antrian.monitoring.List", {
    extend: "com.Grid",
    xtype: "antrian-monitoring-list",
    controller: "antrian-monitoring-list",
    penggunaAksesPos: [],
    viewModel: {
        stores: {
            store: {
                type: "antrian-reservasi-store"
            },
            storepemanggil: {
                type: "antrian-panggilantrian-store"
            }
        },
        data: {
            tgltemp: undefined,
            tglSkrng: undefined,
            statusWebsocket: "disconnect",
            statusBtnWebsocket: "red",
            isConnect: true,
            aksesResponPasien: true,
            listConfig: {
                autoRefresh: true
            }
        },
        formulas: {
            autoRefreshIcon: function (a) {
                return a("listConfig.autoRefresh") ? "x-fa fa-stop" : "x-fa fa-play"
            },
            tooltipAutoRefresh: function (a) {
                return a("listConfig.autoRefresh") ? "Hentikan Perbarui Otomatis" : "Jalankan Perbarui Otomatis"
            }
        }
    },
    initComponent: function () {
        var d = this;
        if (window.location.protocol == "http:") {
            var b = "ws"
        } else {
            var b = "wss"
        }
        d.createMenuContext();
        var a = Ext.create("Ext.ux.WebSocket", {
            url: "ws://" + window.location.hostname + ":8899",
            listeners: {
                message: function (e, g) {
                    var f = JSON.parse(g);
                    if (f != null) {
                        if (f.act) {
                            if (f.act == "ANTRIAN_BARU") {
                                if (d.down("antrian-combo-pos-antrian").getValue() == f.pos) {
                                    d.getViewModel().get("store").reload()
                                }
                            }
                        }
                    }
                }
            }
        });
        d.dockedItems = [{
            xtype: "toolbar",
            dock: "top",
            style: "background:#19c5bf;border:1px #CCC solid",
            items: [{
                ui: "soft-red",
                text: "Antrian Kemkes",
                reference: "btnAntrian",
                hidden: true,
                tooltip: "Setting",
                listeners: {
                    click: "onToPendaftaranOnline"
                }
            }, "->", {
                bind: {
                    html: '<span style="color:{statusBtnWebsocket}">{statusWebsocket}</span>'
                }
            }, {
                xtype: "combobox",
                name: "POS_ANTRIAN",
                allowBlank: false,
                enterFocus: true,
                reference: "fpos",
                enforceMaxLength: true,
                forceSelection: true,
                validateOnBlur: true,
                displayField: "DESKRIPSI",
                valueField: "NOMOR",
                queryMode: "local",
                typeAhead: true,
                emptyText: "[ Pilih Pos Antrian ]",
                store: {
                    model: "data.model.Kategori"
                },
                reference: "fpos",
                listeners: {
                    select: "onChangeTgl"
                }
            }, {
                xtype: "antrian-combo-poli",
                emptyText: "[ Semua Poliklinik ]",
                enterFocus: true,
                enforceMaxLength: true,
                forceSelection: true,
                validateOnBlur: true,
                allowBlank: false,
                reference: "fpoli",
                name: "POLI",
                listeners: {
                    select: "onChangeTgl"
                }
            }, {
                xtype: "antrian-combo-loket",
                emptyText: "[ Pilih Loket ]",
                name: "LOKET",
                firstLoad: true,
                reference: "loketpemanggil",
                listeners: {
                    select: "onChangeLoket"
                }
            }, {
                text: "Buka Loket",
                ui: "soft-red",
                tooltip: "Buka Loket",
                bind: {
                    hidden: "{isConnect}"
                },
                listeners: {
                    click: "onBukaLoket"
                }
            }, {
                text: "Tutup Loket",
                ui: "soft-blue",
                tooltip: "Tutup Loket",
                bind: {
                    hidden: "{!isConnect}"
                },
                listeners: {
                    click: "onTutupLoket"
                }
            }, {
                xtype: "datefield",
                name: "FTANGGAL",
                format: "d-m-Y",
                reference: "ftanggal",
                listeners: {
                    change: "onChangeTgl"
                }
            }, {
                xtype: "antrian-combo-jenis",
                name: "JENIS",
                firstLoad: true,
                reference: "fjenis",
                listeners: {
                    select: "onChangeTgl"
                }
            }, {
                xtype: "combo",
                reference: "combointerval",
                width: 75,
                store: {
                    fields: ["ID"],
                    data: [{
                        ID: 5
                    }, {
                        ID: 10
                    }, {
                        ID: 15
                    }, {
                        ID: 20
                    }, {
                        ID: 25
                    }, {
                        ID: 30
                    }]
                },
                editable: false,
                displayField: "ID",
                valueField: "ID",
                value: 15,
                bind: {
                    disabled: "{listConfig.autoRefresh}"
                }
            }, {
                xtype: "button",
                enableToggle: true,
                pressed: true,
                bind: {
                    iconCls: "{autoRefreshIcon}",
                    tooltip: "{tooltipAutoRefresh}"
                },
                toggleHandler: "onToggleRefresh"
            }]
        }, {
            xtype: "pagingtoolbar",
            bind: {
                store: "{store}"
            },
            dock: "bottom",
            displayInfo: true,
            items: ["-", {}, {}, {
                xtype: "search-field",
                cls: "x-text-border",
                autoFocus: true,
                emptyText: "Cari Nama Pasien",
                flex: 2,
                listeners: {
                    search: "onsearch",
                    clear: "onClear"
                }
            }, {
                xtype: "combobox",
                reference: "statusrespon",
                emptyText: "[ Filter Status ]",
                store: Ext.create("Ext.data.Store", {
                    fields: ["value", "desk"],
                    data: [{
                        value: "ALL",
                        desk: "Semua"
                    }, {
                        value: "1",
                        desk: "Belum Respon"
                    }, {
                        value: "2",
                        desk: "Sudah Respon"
                    }]
                }),
                queryMode: "local",
                displayField: "desk",
                value: 1,
                flex: 1,
                valueField: "value",
                listeners: {
                    select: "onChangeTgl"
                }
            }, {
                xtype: "combobox",
                reference: "jeniscarabayar",
                emptyText: "[ Filter Penjamin ]",
                store: Ext.create("Ext.data.Store", {
                    fields: ["value", "desk"],
                    data: [{
                        value: "0",
                        desk: "Semua"
                    }, {
                        value: "1",
                        desk: "Umum / Corporate"
                    }, {
                        value: "2",
                        desk: "BPJS / JKN"
                    }]
                }),
                queryMode: "local",
                displayField: "desk",
                flex: 1,
                valueField: "value",
                listeners: {
                    select: "onSelectCaraBayar"
                }
            }]
        }], d.columns = [{
            text: "Pos | Loket",
            dataIndex: "POS_ANTRIAN",
            align: "left",
            flex: 0.3,
            menuDisabled: true,
            sortable: false,
            renderer: "onPostAntrian"
        }, {
            text: "Jenis",
            align: "left",
            menuDisabled: true,
            sortable: false,
            dataIndex: "JENIS_APLIKASI",
            flex: 0.3,
            renderer: "onRenderJenisApp"
        }, {
            text: "No.Antrian",
            dataIndex: "NO",
            menuDisabled: true,
            sortable: false,
            align: "left",
            flex: 0.3,
            renderer: "onAntrian"
        }, {
            text: "No.Rujukan",
            dataIndex: "NO_KARTU_BPJS",
            menuDisabled: true,
            sortable: false,
            align: "left",
            flex: 0.3,
            renderer: "onNoRef"
        }, {
            text: "Poli Tujuan",
            dataIndex: "POLI",
            menuDisabled: true,
            sortable: false,
            align: "left",
            flex: 0.5,
            renderer: "onPoli"
        }, {
            text: "Dokter",
            align: "left",
            menuDisabled: true,
            sortable: false,
            dataIndex: "DOKTER",
            flex: 0.5,
            renderer: "onDokter"
        }, {
            text: "Cara Bayar",
            align: "left",
            menuDisabled: true,
            sortable: false,
            dataIndex: "CARABAYAR",
            flex: 0.5,
            renderer: "onCaraBayar"
        }, {
            text: "No RM",
            align: "left",
            menuDisabled: true,
            sortable: false,
            dataIndex: "NORM",
            width: 80,
            renderer: "onNorm"
        }, {
            text: "Nama",
            dataIndex: "NAMA",
            align: "left",
            menuDisabled: true,
            sortable: false,
            renderer: "onRenderNama",
            flex: 0.5
        }, {
            text: "Contact",
            align: "left",
            menuDisabled: true,
            sortable: false,
            dataIndex: "CONTACT",
            flex: 0.5,
            renderer: "onCont"
        }, {
            text: "Tgl. Lahir",
            dataIndex: "TANGGAL_LAHIR",
            menuDisabled: true,
            sortable: false,
            align: "left",
            renderer: "onRenderTgl",
            flex: 0.5
        }, {
            text: "Jenis Pasien",
            align: "left",
            dataIndex: "JENIS",
            menuDisabled: true,
            sortable: false,
            flex: 0.5,
            renderer: "onRenderJenis"
        }, {
            text: "Panggil",
            menuDisabled: true,
            sortable: false,
            xtype: "actioncolumn",
            align: "center",
            width: 50,
            items: [{
                xtype: "tool",
                iconCls: "x-fa fa-bullhorn",
                tooltip: "Panggil Antrian",
                handler: "onClickPanggil"
            }]
        }, {
            text: "Res",
            xtype: "actioncolumn",
            menuDisabled: true,
            sortable: false,
            align: "center",
            width: 50,
            items: [{
                xtype: "tool",
                iconCls: "x-fa fa-arrow-circle-right",
                tooltip: "Respon Kedatangan Pasien",
                handler: "onClickRespon"
            }]
        }, {
            xtype: "templatecolumn",
            text: "Status Antrian Admisi",
            align: "left",
            flex: 0.5,
            tpl: "<div style='color:red'>Check In : {REFERENSI.TASK_ID_ANTRIAN.TASK_2}</div><div style='color:green'>Check Out : {REFERENSI.TASK_ID_ANTRIAN.TASK_3}</div>"
        }];
        d.callParent(arguments)
    },
    listeners: {
        rowcontextmenu: "onKlikKananMenu"
    },
    createMenuContext: function () {
        var b = this;
        b.menucontext = new Ext.menu.Menu({
            items: [{
                text: "Refresh",
                glyph: "xf021@FontAwesome",
                handler: function () {
                    b.getController().onRefresh()
                }
            }]
        });
        return b.menucontext
    },
    onSetGrid: function () {
        var h = this,
            b = h.getViewModel(),
            a = h.down("[reference = btnAntrian]"),
            j = h.down("[reference=fpos]"),
            e = webservice.app.xpriv,
            g = h.down("[reference=ftanggal]"),
            k = Ext.Date.format(b.get("tgltemp"), "d-m-Y"),
            d = Ext.Date.format(h.getSysdate(), "d-m-Y"),
            f = Ext.Date.format(h.getSysdate(), "Y-m-d");
        j.getStore().loadData(h.getAksesPosAntrian());
        a.setHidden(!(e("900301", true) && e("900302", true)));
        if (h.getPropertyConfig("900304") == "TRUE") {
            b.set("aksesResponPasien", true)
        } else {
            b.set("aksesResponPasien", false)
        }
        if (k != d) {
            g.setValue(h.getSysdate());
            b.set("tgltemp", h.getSysdate())
        }
        b.set("tglSkrng", f)
    },
    getAksesPosAntrian: function () {
        var a = this;
        return a.penggunaAksesPos ? a.penggunaAksesPos : []
    },
    loadData: function () {
        var a = this,
            b = SIMpel.app.getApplication();
        Ext.Ajax.request({
            url: webservice.location + "registrasionline/plugins/getAksesPosAntrian",
            useDefaultXhrHeader: false,
            withCredentials: true,
            success: function (e) {
                var d = Ext.JSON.decode(e.responseText);
                var f = d.data.AKSES_POS_ANTRIAN;
                a.penggunaAksesPos = f;
                a.onSetGrid(f)
            },
            failure: function (d) {
                return []
            }
        })
    }
});
Ext.define("antrian.monitorin.ListController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-monitoring-list",
    websocket: undefined,
    init: function () {
        var d = this;
        if (window.location.protocol == "http:") {
            var b = "ws"
        } else {
            var b = "wss"
        }
        var a = b + "://" + window.location.hostname + ":8899";
        d.websocket = new WebSocket(a);
        d.websocket.onopen = function (e) {
            d.getViewModel().set("statusWebsocket", "Connected");
            d.getViewModel().set("statusBtnWebsocket", "green")
        };
        d.websocket.onerror = function (e) {
            d.getViewModel().set("statusWebsocket", "Error");
            d.getViewModel().set("statusBtnWebsocket", "red")
        };
        d.websocket.onclose = function (e) {
            d.getViewModel().set("statusWebsocket", "Disconnect");
            d.getViewModel().set("statusBtnWebsocket", "red")
        }
    },
    onToPendaftaranOnline: function () {
        var a = this.getView();
        a.fireEvent("changeActiveLayout", 0)
    },
    onsearch: function (j, g) {
        var h = this,
            a = h.getView(),
            d = h.getViewModel(),
            b = d.get("store");
        if (b) {
            b.removeAll();
            parameter = b.getQueryParams();
            b.setQueryParams({
                QUERY: g,
                TANGGALKUNJUNGAN: parameter.TANGGALKUNJUNGAN
            });
            b.load()
        }
    },
    onSelectStatus: function (b, g) {
        var f = this,
            e = f.getViewModel(),
            d = e.get("store");
        if (g.get("value") == "0") {
            delete d.queryParams.STATUS;
            d.removeAll()
        } else {
            d.removeAll();
            parameter = d.getQueryParams();
            d.setQueryParams({
                STATUS: g.get("value"),
                TANGGALKUNJUNGAN: parameter.TANGGALKUNJUNGAN,
                QUERY: parameter.QUERY
            })
        }
        d.load()
    },
    onSelectCaraBayar: function (b, g) {
        var f = this,
            e = f.getViewModel(),
            d = e.get("store");
        d.removeAll();
        parameter = d.getQueryParams();
        d.setQueryParams({
            FILTER_CB: g.get("value"),
            TANGGALKUNJUNGAN: parameter.TANGGALKUNJUNGAN,
            POS_ANTRIAN: parameter.POS_ANTRIAN
        });
        d.load()
    },
    onSelectPos: function (b, g) {
        var f = this,
            e = f.getViewModel(),
            d = e.get("store");
        if (g.get("value") == "0") {
            delete d.queryParams.POS_ANTRIAN;
            d.removeAll();
            d.load()
        } else {
            d.removeAll();
            parameter = d.getQueryParams();
            d.setQueryParams({
                POS_ANTRIAN: g.get("value"),
                TANGGALKUNJUNGAN: parameter.TANGGALKUNJUNGAN,
                STATUS: parameter.STATUS,
                QUERY: parameter.QUERY
            });
            d.load()
        }
    },
    onClear: function () {
        var e = this,
            f = e.getViewModel(),
            a = f.get("store");
        delete a.queryParams.QUERY;
        a.removeAll();
        a.load()
    },
    onToggleRefresh: function (e, g) {
        var f = this,
            a = f.getView(),
            d = f.getReferences(),
            b = Number(d.combointerval.getValue()) * 1000;
        a.setListConfig({
            autoRefresh: e.pressed
        });
        if (e.pressed) {
            a.start(b)
        } else {
            a.stop()
        }
    },
    onSetting: function (b) {
        var d = this,
            a = d.getView();
        dialog = a.openDialog("", true, 0, 0, {
            xtype: "antrian-monitoring-pengaturan-workspace",
            header: {
                iconCls: "x-fa fa-cog",
                padding: "7px 7px 7px 7px",
                title: "Pengaturan Registrasi Online"
            },
            ui: "panel-cyan",
            showCloseButton: true,
            hideColumns: true
        }, function (f, g) {
            var e = g.down("antrian-monitoring-pengaturan-workspace");
            e.load({})
        })
    },
    onSettingRuangan: function (b) {
        var d = this,
            a = d.getView();
        dialog = a.openDialog("", true, 0, 0, {
            xtype: "antrian-monitoring-ruangan-workspace",
            ui: "panel-cyan",
            showCloseButton: true,
            hideColumns: true
        }, function (f, g) {
            var e = g.down("antrian-monitoring-ruangan-workspace");
            e.onLoadRecord()
        })
    },
    onJadwalDokter: function (b) {
        var d = this,
            a = d.getView();
        dialog = a.openDialog("", true, 0, 0, {
            xtype: "monitoringantrian-kemkes-jadwaldokter-workspace",
            header: {
                iconCls: "x-fa fa-users",
                padding: "7px 7px 7px 7px",
                title: "Jadwal Dokter"
            },
            ui: "panel-cyan",
            showCloseButton: true,
            hideColumns: true
        }, function (f, g) {
            var e = g.down("monitoringantrian-kemkes-jadwaldokter-workspace");
            e.load()
        })
    },
    onChangeTgl: function (r, f, b, h) {
        var j = this,
            m = j.getReferences(),
            a = m.ftanggal,
            q = m.fjenis.getValue(),
            l = m.fpos.getValue(),
            p = m.fpoli.getValue(),
            g = m.statusrespon.getValue(),
            k = m.fpoli.getStore(),
            d = {};
        obj = {
            TANGGALKUNJUNGAN: Ext.Date.format(a.getValue(), "Y-m-d"),
            POS_ANTRIAN: ""
        };
        if (r.name == "POS_ANTRIAN") {
            k.queryParams.ANTRIAN = l;
            k.load()
        }
        if (q) {
            d = {
                JENIS: q
            };
            obj = Ext.apply(obj, d)
        }
        if (l) {
            d = {
                POS_ANTRIAN: l
            };
            obj = Ext.apply(obj, d)
        }
        if (p) {
            d = {
                POLI: p
            };
            obj = Ext.apply(obj, d)
        }
        if (g) {
            d = {
                STATUS: g
            };
            obj = Ext.apply(obj, d)
        }
        j.getView().load(obj)
    },
    onPostAntrian: function (b, a, e) {
        var d = e.get("REFERENSI") ? e.get("REFERENSI").POS_ANTRIAN.DESKRIPSI + " | " + e.get("REFERENSI").LOKET_PANGGIL : "-";
        this.setBackGround(a, e.get("REFERENSI").STATUS_PANGGIL);
        return d
    },
    onRenderJenis: function (d, b, f) {
        var e = f.get("REFERENSI") ? f.get("REFERENSI").JENIS_PSN.DESKRIPSI : "Baru";
        if (f.get("JENIS_APLIKASI") == 2) {
            if (f.get("WAKTU_CHECK_IN") != "") {
                var a = e + " | " + f.get("WAKTU_CHECK_IN")
            } else {
                var a = e
            }
        } else {
            var a = e
        }
        this.setBackGround(b, f.get("REFERENSI").STATUS_PANGGIL);
        return a
    },
    onRenderJenisApp: function (b, a, e) {
        var d = e.get("REFERENSI").JENIS_APP;
        this.setBackGround(a, e.get("REFERENSI").STATUS_PANGGIL);
        return d
    },
    onRenderNama: function (b, a, e) {
        var d = e.get("NAMA");
        this.setBackGround(a, e.get("REFERENSI").STATUS_PANGGIL);
        return d
    },
    onNoRef:  function (b, a, e) {
        var d = e.get("NO_KARTU_BPJS");
        this.setBackGround(a, e.get("REFERENSI").STATUS_PANGGIL);
        return d
    },
    onAntrian: function (d, b, e) {
        this.setBackGround(b, e.get("REFERENSI").STATUS_PANGGIL);
        if (e.get("CARABAYAR") == 2) {
            var a = 2
        } else {
            var a = 1
        }
        return e.get("POS_ANTRIAN") + "" + a + "-" + Ext.String.leftPad(d, 3, "0")
    },
    onPoli: function (d, b, e) {
        if (e.get("JENIS_APLIKASI") == 2) {
            var a = e.get("REFERENSI").POLI_BPJS.NMPOLI
        } else {
            if (e.get("JENIS_APLIKASI") == 5) {
                var a = "-"
            } else {
                var a = e.get("REFERENSI").POLI.DESKRIPSI
            }
        }
        this.setBackGround(b, e.get("REFERENSI").STATUS_PANGGIL);
        return a
    },
    onDokter: function (d, b, e) {
        var a = e.get("REFERENSI").DOKTER_HFIS.NM_DOKTER;
        this.setBackGround(b, e.get("REFERENSI").STATUS_PANGGIL);
        return a
    },
    onRenderStatusCheckOut: function (d, b, e) {
        if (e.get("JENIS_APLIKASI") == 2) {
            if (e.get("STATUS_TASK_ID3") == 1) {
                var a = e.get("REFERENSI").TASK_ID_ANTRIAN.TASK_3
            } else {
                var a = "-"
            }
        } else {
            var a = "-"
        }
        this.setBackGround(b, e.get("REFERENSI").STATUS_PANGGIL);
        return a
    },
    onTglK: function (b, a, d) {
        this.setBackGround(a, d.get("REFERENSI").STATUS_PANGGIL);
        return Ext.Date.format(d.get("TANGGALKUNJUNGAN"), "d-m-Y")
    },
    onCaraBayar: function (b, a, e) {
        var d = e.get("REFERENSI").CARABAYAR.DESKRIPSI;
        this.setBackGround(a, e.get("REFERENSI").STATUS_PANGGIL);
        return d
    },
    onNorm: function (d, b, e) {
        var a = e.get("NORM") == 0 ? 0 : e.get("NORM");
        this.setBackGround(b, e.get("REFERENSI").STATUS_PANGGIL);
        return a
    },
    onCont: function (b, a, d) {
        this.setBackGround(a, d.get("REFERENSI").STATUS_PANGGIL);
        return b
    },
    onRenderTgl: function (b, a, e) {
        var d = Ext.Date.format(b, "Y-m-d H:i:s");
        this.setBackGround(a, e.get("REFERENSI").STATUS_PANGGIL);
        return d
    },
    setBackGround: function (b, a) {
        if (a == 2) {
            b.style = "background-color:#0df775;color:#000000;font-weight: bold"
        }
        if (a == 1) {
            b.style = "color:#000000;font-weight: bold"
        }
        if (a == 99) {
            b.style = "background-color:#00BFFF;color:#000000;font-weight: bold"
        }
        if (a == 0) {
            b.style = "background-color:#A9A9A9;color:#000000;font-weight: bold"
        }
    },
    onKlikKananMenu: function (e, j, n, k, l) {
        var o = this,
            m = l.getXY();
        l.stopEvent();
        o.getView().menucontext.showAt(m)
    },
    onClickRespon: function (d, g, b) {
        var e = this,
            a = e.getView(),
            f = d.getStore().getAt(g);
        if (f.get("STATUS") == 1) {
            e.onResponPasien(f)
        }
        if (f.get("STATUS") == 2) {
            a.notifyMessage("Data Sudah Di Respon")
        }
        if (f.get("STATUS") == 0) {
            a.notifyMessage("Data Antrian Dibatalkan")
        }
        if (f.get("STATUS") == 99) {
            a.notifyMessage("Pasien Belum Checkin")
        }
    },
    onResponPasien: function (h) {
        var f = this,
            b = f.getView(),
            e = f.getViewModel().get("tglSkrng"),
            d = f.getViewModel().get("store"),
            a = Ext.create("antrian.responantrian.Model", {}),
            g = Ext.Date.format(h.get("TANGGALKUNJUNGAN"), "Y-m-d"),
            j = h.get("JENIS") == 1 ? "Terima kedatangan pasien norm " + h.get("NORM") + " - " + h.get("NAMA") + " ?" : "Terima kedatangan pasien dengan nama " + h.get("NAMA") + " ?";
        if (h.get("STATUS") == 1) {
            if (e === g) {
                Ext.Msg.show({
                    title: "Respon Pasien",
                    message: j,
                    buttons: Ext.Msg.YESNO,
                    icon: Ext.Msg.QUESTION,
                    animateTarget: h,
                    fn: function (k) {
                        if (k === "yes") {
                            b.setLoading(true);
                            a.set("STATUS", 2);
                            a.set("ID", h.get("ID"));
                            a.save({
                                callback: function (m, l, n) {
                                    if (n) {
                                        var o = JSON.parse(l._response.responseText);
                                        b.notifyMessage(o.metadata.message);
                                        d.reload();
                                        f.onResponAntrian(h);
                                        b.setLoading(false)
                                    } else {
                                        b.notifyMessage("Data Gagal Di Respon");
                                        b.setLoading(false)
                                    }
                                }
                            })
                        }
                    }
                })
            } else {
                b.notifyMessage("Pasien Belum Bisa Di Daftar, Hanya tanggal Kunjungan Hari ini yang dapat direspon")
            }
        }
    },
    onResponAntrian: function (b) {
        var d = this,
            a = d.getView(),
            e = d.getViewModel().get("aksesResponPasien");
        if (e) {
            a.fireEvent("openpasien", b, b.get("JENIS"))
        }
    },
    onClickPanggil: function (a, o, d) {
        var j = this,
            h = j.getViewModel(),
            n = h.get("store"),
            m = j.getView(),
            f = a.getStore().getAt(o),
            l = j.getReferences(),
            k = l.loketpemanggil.getValue(),
            b = j.getViewModel().get("isConnect"),
            g = Ext.create("antrian.panggilantrian.Model", {});
        if (b) {
            if (j.websocket) {
                if (k > 0) {
                    if (f.get("STATUS") == 0) {
                        j.getView().notifyMessage("Status Antrian Sudah Batal", "danger-red");
                        return false
                    }
                    if (f.get("STATUS") == 99) {
                        j.getView().notifyMessage("Status Antrian Belum Check In Mobile JKN / Counter", "danger-red");
                        return false
                    }
                    if (f.get("NO") > 0) {
                        if (f.get("CARABAYAR") == 2) {
                            var e = 2
                        } else {
                            var e = 1
                        }
                        g.set("LOKET", k);
                        g.set("NOMOR", f.get("NO"));
                        g.set("POS", f.get("POS_ANTRIAN"));
                        g.set("TANGGAL", m.getSysdate());
                        g.set("CARA_BAYAR", e);
                        m.setLoading(true);
                        g.save({
                            callback: function (q, r, s) {
                                if (s) {
                                    j.getView().notifyMessage("Antrian Dalam Proses Pemanggilan");
                                    var p = {
                                        loket: k,
                                        pesan: "Silahkan Ke Loket",
                                        pos: f.get("POS_ANTRIAN"),
                                        nomor: f.get("NO"),
                                        carabayar: e,
                                        act: "PANGGIL"
                                    };
                                    if (m.getViewModel().get("statusWebsocket") == "Connected") {
                                        j.websocket.send(JSON.stringify(p))
                                    } else {
                                        m.notifyMessage("Koneksi Socket Terputus", "danger-red")
                                    }
                                    n.reload();
                                    m.setLoading(false)
                                } else {
                                    var t = JSON.parse(r.error.response.responseText);
                                    j.getView().notifyMessage(t.detail, "danger-red");
                                    m.setLoading(false)
                                }
                            }
                        })
                    } else {
                        m.notifyMessage("Nomor Antrian Tidak Di Temukan", "danger-red")
                    }
                } else {
                    m.notifyMessage("Silahkan Pilih Loket Pemanggil", "danger-red")
                }
            } else {
                m.notifyMessage("Koneksi Ke Monitor Terputus", "danger-red")
            }
        } else {
            m.notifyMessage("Silahkan Buka Loket Terlebih Dahulu", "danger-red")
        }
    },
    onUpdateWaktuTunggu: function (a) {
        var b = this,
            d = b.getView().getSelection()[0];
        b.onCheckInWaktuTunggu(d, a)
    },
    onCheckInWaktuTunggu: function (a, e) {
        var f = this,
            d = f.getViewModel(),
            k = d.get("store"),
            j = f.getView(),
            h = f.getReferences(),
            g = h.loketpemanggil.getValue(),
            b = Ext.create("antrian.waktutungguantrian.plugins.Model", {});
        if (g > 0) {
            if (a.get("NO") > 0) {
                b.set("taskid", e);
                b.set("kodebooking", a.get("ID"));
                j.setLoading(true);
                b.save({
                    callback: function (l, m, n) {
                        if (n) {
                            var o = JSON.parse(m._response.responseText);
                            j.notifyMessage(o.metadata.message);
                            k.reload();
                            j.setLoading(false)
                        } else {
                            var o = JSON.parse(m.error.response.responseText);
                            f.getView().notifyMessage(o.detail, "danger-red");
                            j.setLoading(false)
                        }
                    }
                })
            } else {
                j.notifyMessage("Nomor Antrian Tidak Di Temukan", "danger-red")
            }
        } else {
            j.notifyMessage("Silahkan Pilih Loket Pemanggil", "danger-red")
        }
    },
    onRefresh: function () {
        var a = this.getView();
        a.reload()
    },
    onCetakSkrining: function (g) {
        var f = this,
            e = f.getStore("store"),
            d = g.ownerCt.getWidgetRecord();
        d.showError = true;
        d.animateTarget = g;
        d.scope = f;
        f.cetakFormSkrining(d.get("ID"), d)
    },
    privates: {
        cetakFormSkrining: function (j, h) {
            var f = this,
                a = f.getView(),
                b = "Word",
                e = "docx",
                g = true,
                d = "Cetak Form Skrining Vaksin Covid :" + j;
            Ext.Msg.show({
                title: d,
                message: "Tekan tombol Yes/Ya utk cetak langsung<br/>Tekan tombol No/Tidak untuk Preview",
                buttons: Ext.Msg.YESNO,
                icon: Ext.Msg.QUESTION,
                defaultButton: "yes",
                animateTarget: f,
                fn: function (k) {
                    if (k != "yes") {
                        b = "Pdf";
                        e = "pdf";
                        g = false
                    }
                    a.cetak({
                        NAME: "plugins.regonline.CetakSkriningCovid",
                        TYPE: b,
                        EXT: e,
                        PARAMETER: {
                            PNOMOR: j
                        },
                        REQUEST_FOR_PRINT: g,
                        PRINT_NAME: "CetakRincian"
                    }, function (l) {
                        a.openDialog("Print Preview", true, 0, 0, {
                            xtype: "panel",
                            title: d
                        }, function (m, n) {
                            n.down("panel").update("<iframe style='width: 100%; height: 100%' src='" + l + "'></iframe>")
                        })
                    })
                }
            })
        }
    },
    onCetak: function (d, f) {
        var b = this,
            a = b.getView(),
            e = d;
        a.cetak({
            NAME: "plugins.antrian.CetakSkriningCovid",
            TYPE: e ? "Pdf" : "Word",
            EXT: e ? "pdf" : "docx",
            PARAMETER: {
                PNOMOR: f
            },
            REQUEST_FOR_PRINT: !e,
            PRINT_NAME: "CetakSkriningCovid"
        }, function (g) {
            if (e) {
                a.openDialog("Print Preview", true, 0, 0, {
                    xtype: "panel",
                    title: "Preview"
                }, function (h, j) {
                    j.down("panel").update("<iframe style='width: 100%; height: 100%' src='" + g + "'></iframe>")
                })
            }
        })
    },
    onChangeLoket: function (f, d) {
        var g = this,
            b = g.getView(),
            e = g.getReferences(),
            a = e.fpos.getValue();
        if (a) {
            g.onCekLoket(a, d.get("ID"), Ext.Date.format(b.getSysdate(), "Y-m-d"))
        } else {
            b.notifyMessage("Silahkan Pilih Pos Antrian", "danger-red");
            g.getViewModel().set("isConnect", true)
        }
    },
    onCekLoket: function (g, e, d) {
        var f = this,
            a = f.getView(),
            b = f.getViewModel().get("storepemanggil");
        if (b) {
            b.queryParams = {
                POS: g,
                LOKET: e,
                TANGGAL: d,
                STATUS: 1
            };
            b.load(function (j, h, k) {
                if (k) {
                    f.getViewModel().set("isConnect", true)
                } else {
                    f.getViewModel().set("isConnect", false)
                }
            })
        }
    },
    onTutupLoket: function () {
        var g = this,
            d = g.getView(),
            e = g.getReferences(),
            f = e.loketpemanggil.getValue(),
            b = e.fpos.getValue(),
            a = Ext.create("antrian.panggilantrian.Model", {});
        if (f) {
            if (b) {
                a.set("LOKET", f);
                a.set("POS", b);
                a.set("TANGGAL", Ext.Date.format(d.getSysdate(), "Y-m-d"));
                a.set("STATUS", 0);
                a.set("STATUS_LOKET", 1);
                d.setLoading(true);
                a.save({
                    callback: function (k, l, m) {
                        if (m) {
                            g.getView().notifyMessage("Loket Antrian Berhasil Di Tutup");
                            var j = {
                                pos: b,
                                act: "REFRESH_LOKET"
                            };
                            if (d.getViewModel().get("statusWebsocket") == "Connected") {
                                g.websocket.send(JSON.stringify(j))
                            }
                            g.onCekLoket(b, f, Ext.Date.format(d.getSysdate(), "Y-m-d"));
                            d.setLoading(false)
                        } else {
                            var n = JSON.parse(l.error.response.responseText);
                            g.getView().notifyMessage(n.detail, "danger-red");
                            d.setLoading(false)
                        }
                    }
                })
            } else {
                d.notifyMessage("Silahkan Pilih Pos Antrian", "danger-red")
            }
        } else {
            d.notifyMessage("Silahkan Pilih Loket", "danger-red")
        }
    },
    onBukaLoket: function () {
        var g = this,
            d = g.getView(),
            e = g.getReferences(),
            f = e.loketpemanggil.getValue(),
            b = e.fpos.getValue(),
            a = Ext.create("antrian.panggilantrian.Model", {});
        if (f) {
            if (b) {
                a.set("LOKET", f);
                a.set("POS", b);
                a.set("TANGGAL", Ext.Date.format(d.getSysdate(), "Y-m-d"));
                a.set("STATUS", 1);
                a.set("STATUS_LOKET", 1);
                d.setLoading(true);
                a.save({
                    callback: function (k, l, m) {
                        if (m) {
                            g.getView().notifyMessage("Loket Antrian Berhasil Di Buka");
                            var j = {
                                act: "REFRESH_LOKET",
                                pos: b
                            };
                            if (d.getViewModel().get("statusWebsocket") == "Connected") {
                                g.websocket.send(JSON.stringify(j))
                            }
                            g.onCekLoket(b, f, Ext.Date.format(d.getSysdate(), "Y-m-d"));
                            d.setLoading(false)
                        } else {
                            var n = JSON.parse(l._response.responseText);
                            g.getView().notifyMessage(n.detail, "danger-red");
                            d.setLoading(false)
                        }
                    }
                })
            } else {
                d.notifyMessage("Silahkan Pilih Pos Antrian", "danger-red")
            }
        } else {
            d.notifyMessage("Silahkan Pilih Loket", "danger-red")
        }
    },
    onJadwalDokter: function (b) {
        var d = this,
            a = d.getView();
        a.openDialog("", true, 0, 0, {
            xtype: "antrian-monitoring-jadwal-Workspace",
            showCloseButton: true,
            title: "Jadwal Dokter",
            ui: "panel-cyan",
            iconCls: b.iconCls
        }, function (f, g) {
            var e = g.down("antrian-monitoring-jadwal-Workspace");
            e.load({})
        }, b)
    }
});
Ext.define("antrian.monitoring.Workspace", {
    extend: "com.Form",
    xtype: "antrian-monitoring-workspace",
    controller: "antrian-monitoring-workspace",
    viewModel: {
        stores: {
            pendaftaranstore: {
                type: "pendaftaran-store"
            }
        }
    },
    bodyPadding: 2,
    layout: "fit",
    border: true,
    initComponent: function () {
        var a = this;
        a.items = [{
            region: "center",
            xtype: "container",
            flex: 1,
            layout: "card"
        }];
        a.callParent(arguments)
    },
    load: function () {
        var b = this,
            d = webservice.app.xpriv,
            a = b.down("container");
        if (d("900302", true)) {
            list = Ext.create("antrian.monitoring.List", {
                flex: 1,
                listeners: {
                    changeActiveLayout: "onChangeActive",
                    openpasien: "onResponPasien"
                }
            });
            a.add(list);
            list.loadData()
        }
        if (d("900301", true)) {
            kemkes = Ext.create("antrian.monitoring.kemkes.reservasi.Grid", {
                flex: 1,
                listeners: {
                    changeActiveLayout: "onChangeActive",
                    openpasien: "onResponPasien"
                }
            });
            a.add(kemkes);
            kemkes.loadData()
        }
    },
    refresh: function () {
        var a = this,
            d = a.down("monitoringantrian-kemkes-reservasi-grid"),
            b = a.down("list-monitoring-antrian");
        d.loadData();
        b.loadData()
    }
});
Ext.define("antrian.monitorin.WorkspaceController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-monitoring-workspace",
    onChangeActive: function (a) {
        var e = this,
            d = e.getView(),
            b = d.down("container");
        b.getLayout().setActiveItem(a)
    },
    onCekStatusPendaftaran: function (d) {
        var e = this,
            b = e.getViewModel(),
            a = b.get("pendaftaranstore");
        a.queryParams = {
            NORM: d,
            STATUS: 1,
            start: 0,
            limit: 1
        };
        a.load(function (j, f, g) {
            if (j.length > 0) {
                return true
            }
        })
    },
    onResponPasien: function (d, b) {
        var e = this,
            a = e.getView(),
            f = new data.model.Pasien(),
            g = f.getId().replace("data.model.", "");
        if (b == 2) {
            e.onBaru(d)
        } else {
            if (d.get("JENIS_APLIKASI") == 5) {
                e.onBaru(d)
            } else {
                a.fireEvent("onTabPasien", d, e.getIconSex(d), true, "pasien.Workspace", "psn-" + d.get("NORM"), d.get("NORM"))
            }
        }
    },
    onBaru: function (b) {
        var d = this,
            a = d.getView(),
            e = new data.model.Pasien(),
            f = e.getId().replace("data.model.", "");
        e.set("NAMA", b.get("NAMA"));
        e.set("TEMPAT_LAHIR", b.get("TEMPAT_LAHIR"));
        e.set("TANGGAL_LAHIR", b.get("TANGGAL_LAHIR"));
        a.fireEvent("onTabPasien", e, d.getIconSex(e), true, "pasien.Workspace", f, f)
    },
    privates: {
        getIconSex: function (a) {
            return "x-fa fa-" + (a.get("JENIS_KELAMIN") == 1 ? "male" : "female")
        },
        toFormatNorm: function (b) {
            var a = Ext.String.leftPad(b, 8, "0"),
                d = Ext.String.insert(Ext.String.insert(Ext.String.insert(a, ".", 2), ".", 5), ".", 8);
            return d
        }
    }
});
Ext.define("antrian.monitoring.jadwal.Form", {
    extend: "com.Form",
    xtype: "antrian-monitoring-jadwal-form",
    controller: "antrian-monitoring-jadwal-form",
    model: "antrian.jadwal.Model",
    defaults: {
        border: false
    },
    items: [{
        layout: "vbox",
        defaults: {
            margin: "1 1 1 1",
            allowBlank: false,
            width: "100%"
        },
        items: [{
            xtype: "combo",
            emptyText: "[ Hari ]",
            store: Ext.create("data.store.Store", {
                fields: ["ID", "DEKS"],
                data: [{
                    ID: 0,
                    DESK: "Senin"
                }, {
                    ID: 1,
                    DESK: "Selasa"
                }, {
                    ID: 2,
                    DESK: "Rabu"
                }, {
                    ID: 3,
                    DESK: "Kamis"
                }, {
                    ID: 4,
                    DESK: "Jumat"
                }, {
                    ID: 5,
                    DESK: "Sabtu"
                }, {
                    ID: 6,
                    DESK: "Minggu"
                }]
            }),
            valueField: "ID",
            displayField: "DESK",
            name: "HARI"
        }]
    }, {
        layout: "hbox",
        defaults: {
            margin: "1 1 1 1",
            allowBlank: false,
            width: "50%"
        },
        defaultType: "timefield",
        items: [{
            emptyText: "[ Jam Mulai ]",
            format: "H:i:s",
            name: "MULAI",
            minValue: "07:00:00",
            maxValue: "23:00:00"
        }, {
            emptyText: "[ Jam Selesai ]",
            format: "H:i:s",
            name: "SELESAI",
            minValue: "07:00:00",
            maxValue: "23:00:00"
        }]
    }, {
        layout: "hbox",
        defaults: {
            margin: "1 1 1 1",
            allowBlank: false,
            format: "Y-m-d",
            width: "50%"
        },
        defaultType: "datefield",
        items: [{
            emptyText: "[ Tanggal Mulai ]",
            name: "TANGGAL_AWAL"
        }, {
            emptyText: "[ Tanggal Akhir ]",
            name: "TANGGAL_AKHIR"
        }]
    }],
    buttons: [{
        text: "Simpan",
        iconCls: "x-fa fa-save",
        ui: "soft-blue",
        handler: "onCLickBtnSimpan"
    }]
});
Ext.define("antrian.monitoring.jadwal.FormController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-monitoring-jadwal-form",
    onCLickBtnSimpan: function (b) {
        var d = this,
            a = d.getView();
        if (a.getForm().isValid()) {
            a.setLoading(true);
            a.save(b, {}, function (f, e, g) {
                if (g) {
                    a.setLoading(false)
                } else {
                    a.setLoading(false)
                }
            })
        }
    }
});
Ext.define("antrian.monitoring.jadwal.Grid", {
    extend: "com.Grid",
    xtype: "antrian-monitoring-jadwal-grid",
    controller: "antrian-monitoring-jadwal-grid",
    viewModel: {
        stores: {
            store: {
                type: "antrian-jadwal-store"
            }
        }
    },
    columns: [{
        xtype: "rownumberer",
        text: "No",
        align: "left",
        width: 60
    }, {
        xtype: "templatecolumn",
        text: "Dokter",
        dataIndex: "DOKTER",
        flex: 1,
        tpl: new Ext.XTemplate('<div class="thumb-wrap">', '<div class="thumb">', "<div>{[this.getNamaLengkap(values.REFERENSI.PEGAWAI)]}</div>", "<div>{DOKTER}</div>", "</div>", "</div>", {
            getNamaLengkap: function (b) {
                var e = b.GELAR_DEPAN ? b.GELAR_DEPAN + ". " : "",
                    a = b.NAMA ? b.NAMA : "",
                    d = b.GELAR_BELAKANG ? ", " + b.GELAR_BELAKANG : "";
                return e + "" + a + "" + d
            }
        })
    }, {
        text: "Jadwal",
        flex: 1,
        columns: [{
            xtype: "templatecolumn",
            text: "Hari",
            dataIndex: "HARI",
            tpl: "<div>{DESK_HARI}</div>"
        }, {
            text: "Jam Mulai",
            dataIndex: "MULAI"
        }, {
            text: "Jam Akhir",
            dataIndex: "SELESAI"
        }, {
            xtype: "templatecolumn",
            text: "Berlaku",
            tpl: new Ext.XTemplate('<div class="thumb-wrap">', '<div class="thumb">', "<div>{[this.formatDate(values.TANGGAL_AWAL)]} s/d</div>", "<div>{[this.formatDate(values.TANGGAL_AKHIR)]}</div>", "</div>", "</div>", {
                formatDate: function (b) {
                    var a = b ? Ext.Date.format(b, "Y-m-d") : "";
                    return a
                }
            })
        }]
    }, {
        xtype: "checkcolumn",
        header: "STATUS",
        dataIndex: "STATUS",
        width: 80,
        listeners: {
            checkchange: "onChangeStatus"
        }
    }, {
        menuDisabled: true,
        sortable: false,
        align: "center",
        xtype: "actioncolumn",
        width: 50,
        items: [{
            iconCls: "x-fa fa-calendar x-color-blue",
            tooltip: "Tanggal",
            handler: "onViewJadwalBerdasarkanTanggal"
        }]
    }]
});
Ext.define("antrian.monitoring.jadwal.GridController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-monitoring-jadwal-grid",
    onChangeStatus: function (f, a, j) {
        var h = this,
            b = h.getView(),
            g = h.getViewModel(),
            d = g.get("store"),
            e = d.getAt(a);
        e.set("STATUS", j ? 1 : 0);
        e.scope = h;
        e.save({
            callback: function (k, l, m) {
                if (m) {
                    b.notifyMessage("Status jadwal dokter di" + (j == true ? "Aktifkan" : "NonAktifkan "), "danger-blue");
                    b.reload()
                }
            }
        })
    },
    onViewJadwalBerdasarkanTanggal: function (d, j, b, k, f, h) {
        var e = this,
            a = e.getView();
        a.openDialog("", true, "60%", 400, {
            xtype: "antrian-monitoring-jadwal-tanggal-workspace",
            showCloseButton: true,
            ui: "panel-cyan",
            title: "Tanggal",
            iconCls: "x-fa fa-edit"
        }, function (m, l) {
            var g = l.down("antrian-monitoring-jadwal-tanggal-workspace");
            g.load(h)
        }, d)
    }
});
Ext.define("antrian.monitoring.jadwal.Workspace", {
    extend: "com.Form",
    xtype: "antrian-monitoring-jadwal-Workspace",
    controller: "antrian-monitoring-jadwal-Workspace",
    layout: {
        type: "border"
    },
    bodyPadding: 1,
    items: [{
        region: "west",
        title: "Ruangan",
        flex: 0.6,
        ui: "panel-black",
        border: true,
        margin: "1 1 1 1",
        iconCls: "x-fa fa-list",
        xtype: "ruangan-list",
        listeners: {
            select: "onSelectListRuangan"
        }
    }, {
        region: "center",
        flex: 1,
        border: false,
        layout: {
            type: "vbox",
            align: "stretch"
        },
        defaults: {
            border: true
        },
        items: [{
            title: "Dokter Ruangan",
            flex: 1,
            ui: "panel-black",
            iconCls: "x-fa fa-users",
            reference: "GridDokterRuangan",
            xtype: "dokterruangan-list"
        }, {
            title: "Jadwal Dokter Ruangan",
            border: true,
            ui: "panel-black",
            iconCls: "x-fa fa-calendar",
            flex: 1,
            reference: "GridJadwal",
            xtype: "antrian-monitoring-jadwal-grid"
        }]
    }],
    initComponent: function () {
        var b = this,
            a = b.getController();
        b.callParent(arguments);
        var d = b.down("dokterruangan-list");
        d.columns.forEach(function (e) {
            if (e.xtype) {
                if (e.xtype == "actioncolumn" || e.xtype == "checkcolumn" || e.xtype == "widgetcolumn") {
                    d.getHeaderContainer().remove(e.fullColumnIndex)
                }
            }
        });
        d.getHeaderContainer().add({
            menuDisabled: true,
            sortable: false,
            align: "center",
            xtype: "actioncolumn",
            width: 50,
            items: [{
                iconCls: "x-fa fa-calendar x-color-black",
                tooltip: "Ubah Pegawai",
                scope: a,
                handler: "onClikJadwalBaru"
            }]
        })
    },
    load: function () {
        var b = this,
            a = b.down("antrian-monitoring-jadwal-grid"),
            e = b.down("ruangan-list"),
            d = b.down("dokterruangan-list");
        a.load({
            RUANGAN: 0
        });
        d.load({
            RUANGAN: 0,
            STATUS: 1
        });
        e.load({
            JENIS: 5,
            JENIS_KUNJUNGAN: 1,
            STATUS: 1
        })
    }
});
Ext.define("antrian.monitoring.jadwal.WorkspaceController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-monitoring-jadwal-Workspace",
    onSelectListRuangan: function (b, e) {
        var d = this,
            a = d.getReferences();
        a.GridJadwal.load({
            RUANGAN: e.get("ID"),
            STATUS: 1
        });
        a.GridDokterRuangan.load({
            RUANGAN: e.get("ID"),
            STATUS: 1
        })
    },
    onClikJadwalBaru: function (n, o, a, l, e, b) {
        var j = this,
            m = j.getView(),
            k = j.getReferences(),
            d = k.GridDokterRuangan,
            f = (b.get("REFERENSI")) ? (b.get("REFERENSI").DOKTER) ? true : false : false,
            h = d.getStore().getQueryParams();
        if (f) {
            m.openDialog("", true, 500, 200, {
                xtype: "antrian-monitoring-jadwal-form",
                showCloseButton: true,
                ui: "panel-cyan",
                title: "Form Tambah Jadwal",
                iconCls: "x-fa fa-edit"
            }, function (q, p) {
                var g = p.down("antrian-monitoring-jadwal-form");
                g.createRecord({
                    DOKTER: b.get("REFERENSI").DOKTER.NIP,
                    RUANGAN: h.RUANGAN,
                    STATUS: 1
                });
                g.on("save", function () {
                    p.close();
                    k.GridJadwal.reload()
                })
            }, n)
        } else {
            m.showMessageBox({
                title: "Warning",
                message: "Dokter tidak terdaftar di master pegawai",
                ui: "window-red",
                buttons: Ext.Msg.OK
            })
        }
    }
});
Ext.define("antrian.monitoring.jadwal.tanggal.Datepicker", {
    extend: "com.Form",
    xtype: "antrian-monitoring-jadwal-tanggal-datepicker",
    viewModel: {
        stores: {
            jadwatTanggalStore: {
                type: "antrian-jadwal-tanggal-store"
            }
        }
    },
    layout: {
        type: "vbox",
        align: "left"
    },
    border: false,
    initComponent: function () {
        var a = this;
        Ext.Date.dayNames = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
        Ext.Date.monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        a.items = [{
            xtype: "datepicker",
            flex: 1,
            showToday: false,
            showPrevMonth: function (b) {
                a.fireEvent("loadTanggal", Ext.Date.add(this.activeDate, Ext.Date.MONTH, -1));
                return this.update(Ext.Date.add(this.activeDate, Ext.Date.MONTH, -1))
            },
            showNextMonth: function (b) {
                a.fireEvent("loadTanggal", Ext.Date.add(this.activeDate, Ext.Date.MONTH, 1));
                return this.update(Ext.Date.add(this.activeDate, Ext.Date.MONTH, 1))
            },
            onOkClick: function (d, f) {
                var g = f[0],
                    e = f[1],
                    b = new Date(e, g, this.getActive().getDate());
                a.fireEvent("loadTanggal", Ext.Date.add(b));
                this.update(Ext.Date.add(b));
                this.hideMonthPicker()
            },
            listeners: {
                select: function (d, f) {
                    var e = a.getViewModel(),
                        b = e.get("jadwatTanggalStore");
                    find = b.findRecord("TANGGAL", Ext.Date.format(f, "Y-m-d"), 0, false, true, true);
                    a.fireEvent("selectTanggal", f, find)
                }
            },
            cls: "jadwalDinas"
        }];
        a.callParent(arguments)
    },
    loadJadwal: function (e) {
        var d = this,
            a = d.getViewModel(),
            b = a.get("jadwatTanggalStore");
        d.setLoading(true);
        b.setQueryParams(e);
        b.load({
            callback: function (g, f, h) {
                d.setLoading(false);
                d.highlightDates()
            }
        })
    },
    highlightDates: function () {
        var f = this,
            d = f.getViewModel(),
            e = d.get("jadwatTanggalStore");
        picker = f.down("datepicker"), cells = picker.cells.elements;
        for (var j = 0; j < cells.length; j++) {
            var a = Ext.fly(cells[j]),
                g = a.dom.firstChild.dateValue,
                b = new Date(g),
                h = e.findRecord("TANGGAL", Ext.Date.format(b, "Y-m-d"), 0, false, true, true);
            if (h) {
                a.dom.style = "background-color: #e71f31;"
            } else {
                a.dom.style = "background-color: white;"
            }
        }
    }
});
Ext.define("antrian.monitoring.jadwal.tanggal.Wokrspace", {
    extend: "com.Form",
    xtype: "antrian-monitoring-jadwal-tanggal-workspace",
    controller: "antrian-monitoring-jadwal-tanggal-workspace",
    viewModel: {
        stores: {
            jadwatTanggalStore: {
                type: "antrian-jadwal-tanggal-store"
            }
        },
        data: {
            jadwal: undefined
        }
    },
    bodyPadding: 0,
    layout: {
        type: "hbox",
        align: "stretch"
    },
    items: [{
        xtype: "antrian-monitoring-jadwal-tanggal-datepicker",
        listeners: {
            loadTanggal: "onLoadTanggal",
            selectTanggal: "onSelectTanggal"
        }
    }, {
        title: "Daftar Pengganti",
        margin: "6 6 6 6",
        border: true,
        flex: 1,
        reference: "gridpengganti",
        xtype: "antrian-monitoring-jadwal-tanggal-pergantian-grid"
    }],
    load: function (g, b) {
        var f = this,
            a = f.down("antrian-monitoring-jadwal-tanggal-datepicker"),
            e = f.getViewModel(),
            d = (b) ? b : f.getSysdate();
        e.set("jadwal", g);
        a.loadJadwal({
            ID_JADWAL: g.get("ID"),
            BULAN: Ext.Date.format(d, "Y-m"),
            STATUS: 1
        })
    }
});
Ext.define("antrian.monitoring.jadwal.tanggal.Workspace", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-monitoring-jadwal-tanggal-workspace",
    onLoadTanggal: function (b) {
        var e = this,
            d = e.getViewModel(),
            a = e.getView();
        a.load(d.get("jadwal"), b)
    },
    onSelectTanggal: function (b, f) {
        var d = this,
            a = d.getReferences(),
            e = a.gridpengganti;
        if (f) {
            e.load({
                ID_TANGGAL: f.get("ID")
            })
        }
    }
});
Ext.define("antrian.monitoring.jadwal.tanggal.pergantian.Grid", {
    extend: "com.Grid",
    xtype: "antrian-monitoring-jadwal-tanggal-pergantian-grid",
    viewModel: {
        stores: {
            store: {
                type: "antrian-jadwal-pergantian-store"
            }
        }
    },
    hideHeaders: true,
    columns: [{
        flex: 1,
        dataIndex: "ID",
        xtype: "templatecolumn",
        tpl: new Ext.XTemplate('<div class="thumb-wrap">', '<div class="thumb">', '<div style="white-space:normal !important;"><b>Pengirim :</b> {[this.getPenerima(values)]}</div>', '<div style="white-space:normal !important;"><b>Penerima :</b> {[this.getPengirim(values)]}</div>', "</div>", "</div>", {
            getPenerima: function (b) {
                var a = b.REFERENSI ? b.REFERENSI.PERGANTIAN ? b.REFERENSI.PERGANTIAN : undefined : undefined,
                    a = a.REFERENSI ? a.REFERENSI.PENERIMA ? a.REFERENSI.PENERIMA : undefined : undefined;
                if (a) {
                    return a.NAMA + " (" + a.NIP + ")"
                }
                return ""
            },
            getPengirim: function (b) {
                var a = b.REFERENSI ? b.REFERENSI.PERGANTIAN ? b.REFERENSI.PERGANTIAN : undefined : undefined,
                    a = a.REFERENSI ? a.REFERENSI.PENGIRIM ? a.REFERENSI.PENGIRIM : undefined : undefined;
                if (a) {
                    return a.NAMA + " (" + a.NIP + ")"
                }
                return ""
            }
        })
    }, {
        text: "Status",
        dataIndex: "STATUS",
        menuDisabled: true,
        sortable: false,
        width: 100,
        align: "center",
        xtype: "templatecolumn",
        tpl: new Ext.XTemplate('<div class="thumb-wrap">', '<div class="thumb">', '<div style="color:white; padding:2px; border-radius:10px; background:{[this.deskColorStatus(values.STATUS)]}; white-space:normal;">{[this.deskStatus(values.STATUS)]}</div>', "</div>", "</div>", {
            deskStatus: function (b) {
                var a = ["Proses", "Terima", "Tolak"];
                return a[b]
            },
            deskColorStatus: function (b) {
                var a = ["#4f4d4d", "#2e8bfe", "#e76666"];
                return a[b]
            }
        })
    }]
});
Ext.define("antrian.monitoring.kemkes.jadwaldokter.Form", {
    extend: "com.Form",
    xtype: "antrian-monitoring-kemkes-jadwaldokter-form",
    controller: "antrian-monitoring-kemkes-jadwaldokter-form",
    layout: "hbox",
    defaults: {
        width: "30%",
        margin: "1.1.1.1"
    },
    items: [{
        xtype: "combo",
        fieldLabel: "Hari",
        labelAlign: "top",
        valueField: "ID",
        displayField: "DESK",
        reference: "hari",
        value: 1,
        name: "HARI",
        store: Ext.create("data.store.Store", {
            field: ["ID", "DESK"],
            data: [{
                ID: 0,
                DESK: "Minggu"
            }, {
                ID: 1,
                DESK: "Senin"
            }, {
                ID: 2,
                DESK: "Selasa"
            }, {
                ID: 3,
                DESK: "Rabu"
            }, {
                ID: 4,
                DESK: "Kamis"
            }, {
                ID: 5,
                DESK: "Jumat"
            }, {
                ID: 6,
                DESK: "Sabtu"
            }]
        }),
        listeners: {
            select: "onSelectHari"
        }
    }, {
        xtype: "ruangan-konsul-combo",
        name: "KLINIK",
        reference: "ruangan",
        fieldLabel: "Ruangan",
        params: {
            STATUS: 1
        },
        firstLoad: true,
        labelAlign: "top",
        listeners: {
            select: "onSelectRuangan"
        }
    }]
});
Ext.define("antrian.monitoring.kemkes.jadwaldokter.FormController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-monitoring-kemkes-jadwaldokter-form",
    onSelectHari: function (a, b) {
        this.filteJadwal(a)
    },
    onSelectRuangan: function (a, b) {
        this.filteJadwal(a)
    },
    privates: {
        filteJadwal: function (d) {
            var f = this,
                a = f.getView(),
                b = f.getReferences(),
                e = b.hari.getValue(),
                g = b.ruangan.getValue();
            if ((e) && (g)) {
                a.fireEvent("filter", {
                    HARI: e,
                    RUANGAN: g
                })
            }
        }
    }
});
Ext.define("antrian.monitoring.kemkes.jadwaldokter.Grid", {
    extend: "com.Grid",
    xtype: "antrian-monitoring-kemkes-jadwaldokter-grid",
    controller: "antrian-monitoring-kemkes-jadwaldokter-grid",
    viewModel: {
        stores: {
            store: {
                type: "antrian-monitoring-kemkes-jadwaldokter-store"
            }
        }
    },
    columns: [{
        xtype: "rownumberer",
        text: "No",
        align: "left",
        width: 50
    }, {
        xtype: "templatecolumn",
        text: "Dokter",
        flex: 1,
        tpl: new Ext.XTemplate('<div class="thumb-wrap">', '<div class="thumb">', '<div style="align : center; white-space:normal !important;">{REFERENSI.NAMA}</div>', "</div>", "</div>", {})
    }, {
        text: "Jam Kerja",
        flex: 1,
        columns: [{
            dataIndex: "JAM_MULAI",
            text: "Mulai"
        }, {
            dataIndex: "JAM_TUTUP",
            text: "Selesai"
        }, {
            text: "Kuota",
            dataIndex: "KUOTA"
        }]
    }, {
        xtype: "checkcolumn",
        text: "Status",
        dataIndex: "STATUS",
        width: 90,
        stopSelection: true,
        listeners: {
            checkchange: "onChange"
        }
    }]
});
Ext.define("antrian.monitoring.kemkes.jadwaldoter.GridController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-monitoring-kemkes-jadwaldokter-grid",
    onChange: function (o, a, l) {
        var m = this,
            j = m.getView(),
            n = m.getViewModel(),
            p = n.get("store"),
            k = p.getAt(a);
        k.set("STATUS", l ? 1 : 0);
        k.scope = this;
        k.save({
            callback: function (d, e, b) {
                if (b) {
                    j.notifyMessage("Status jadwal dokter di" + (l == true ? "Aktifkan" : "NonAktifkan"))
                } else {
                    k.set("STATUS", l ? 0 : 1)
                }
            }
        })
    }
});
Ext.define("antrian.monitoring.kemkes.jadwaldokter.Workspace", {
    extend: "com.Form",
    xtype: "antrian-monitoring-kemkes-jadwaldokter-workspace",
    controller: "antrian-monitoring-kemkes-jadwaldokter-workspace",
    layout: {
        type: "border"
    },
    defaults: {
        margin: "2.2.2.2"
    },
    initComponent: function () {
        var a = this;
        a.items = [{
            region: "north",
            height: 100,
            title: "Filter",
            ui: "panel-black",
            xtype: "antrian-monitoring-kemkes-jadwaldokter-form",
            listeners: {
                filter: "onFilterJadwalDokter"
            }
        }, {
            region: "center",
            flex: 1,
            title: "Jadwal Dokter",
            ui: "panel-black",
            xtype: "antrian-monitoring-kemkes-jadwaldokter-grid"
        }, {
            region: "west",
            flex: 1,
            title: "Dokter di ruangan",
            ui: "panel-black",
            xtype: "dokterruangan-list",
            listeners: {
                itemdblclick: "onItemDblClickPetugas"
            }
        }];
        a.callParent(arguments)
    },
    load: function () {
        var a = this,
            b = a.down("dokterruangan-list");
        b.getHeaderContainer().remove(3);
        b.setListConfig({
            paging: true
        })
    }
});
Ext.define("antrian.monitoring.kemkes.jadwaldokter.WorkspaceController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-monitoring-kemkes-jadwaldokter-workspace",
    filter: undefined,
    onFilterJadwalDokter: function (e) {
        var d = this,
            b = d.getView(),
            a = b.down("antrian-monitoring-kemkes-jadwaldokter-grid"),
            f = b.down("dokterruangan-list");
        d.filter = e;
        a.load({
            KLINIK: e.RUANGAN,
            HARI: e.HARI
        });
        f.load({
            RUANGAN: e.RUANGAN,
            STATUS: 1
        })
    },
    onItemDblClickPetugas: function (a, f) {
        var g = this,
            d = g.getViewModel(),
            e = g.filter;
        if (e) {
            model = Ext.create("antrian.monitoring.kemkes.jadwaldokter.Model", {
                KLINIK: e.RUANGAN,
                HARI: e.HARI,
                DOKTER: f.get("REFERENSI").DOKTER.NIP,
                STATUS: 1
            });
            g.simpanPetugas(model)
        }
    },
    privates: {
        simpanPetugas: function (g, a, h) {
            var j = this,
                e = j.getView();
            wind = e.openDialog("", true, 500, 100, {
                xtype: "com-form",
                ui: "panel-red",
                showCloseButton: true,
                hideColumns: true,
                title: "Jadwal",
                defaults: {
                    allowBlank: false,
                    width: "35%",
                    margin: "1.1.1.1"
                },
                layout: "hbox",
                items: [{
                    xtype: "timefield",
                    name: "MULAI",
                    format: "H:i:s",
                    emptyText: "[JAM MULAI]"
                }, {
                    xtype: "timefield",
                    name: "AKHIR",
                    format: "H:i:s",
                    emptyText: "[JAM AKHIR]"
                }, {
                    xtype: "numberfield",
                    name: "KUOTA",
                    width: "29%",
                    maxValue: 100,
                    hideTrigger: true,
                    emptyText: "[KUOTA]"
                }],
                buttons: [{
                    text: "Simpan",
                    iconCls: "x-fa fa-save",
                    listeners: {
                        click: function (b) {
                            var d = wind.down("com-form"),
                                f = d.getForm().getValues();
                            console.log(f);
                            if (d.getForm().isValid()) {
                                g.set("JAM_MULAI", f.MULAI);
                                g.set("JAM_TUTUP", f.AKHIR);
                                g.set("KUOTA", f.KUOTA);
                                g.showError = true;
                                g.save({
                                    callback: function (m, n, l) {
                                        if (l) {
                                            e.notifyMessage("Data telah tersimpan");
                                            j.loadPetugasPembagian();
                                            wind.close()
                                        }
                                    }
                                })
                            }
                        }
                    }
                }]
            })
        },
        loadPetugasPembagian: function () {
            var a = this;
            a.onFilterJadwalDokter(a.filter)
        }
    }
});
Ext.define("antrian.monitoring.kemkes.reservasi.Grid", {
    extend: "com.Grid",
    xtype: "antrian-monitoring-kemkes-reservasi-grid",
    controller: "antrian-monitoring-kemkes-reservasi-grid",
    viewModel: {
        stores: {
            store: {
                type: "antrian-monitoring-kemkes-reservasi-store"
            }
        },
        data: {
            tgltemp: undefined,
            tglSkrng: undefined,
            listConfig: {
                autoRefresh: true
            }
        },
        formulas: {
            autoRefreshIcon: function (a) {
                return a("listConfig.autoRefresh") ? "x-fa fa-stop" : "x-fa fa-play"
            },
            tooltipAutoRefresh: function (a) {
                return a("listConfig.autoRefresh") ? "Hentikan Perbarui Otomatis" : "Jalankan Perbarui Otomatis"
            }
        }
    },
    initComponent: function () {
        var a = this;
        a.createMenuContext();
        a.dockedItems = [{
            xtype: "toolbar",
            dock: "top",
            style: "background:#81d684;border:1px #CCC solid",
            items: [{
                ui: "soft-red",
                reference: "btnAntrian",
                text: "Antrian Online",
                hidden: true,
                tooltip: "Setting",
                listeners: {
                    click: "onToPendaftaranOnline"
                }
            }, {
                xtype: "button",
                ui: "soft-black",
                iconCls: "x-fa fa-cog",
                menu: [{
                    ui: "soft-blue",
                    iconCls: "x-fa fa-users",
                    tooltip: "Setting",
                    text: "Jadwal Dokter",
                    listeners: {
                        click: "onJadwalDokter"
                    }
                }]
            }, "->", {
                xtype: "datefield",
                name: "FTANGGAL",
                format: "d-m-Y",
                reference: "ftanggal",
                listeners: {
                    change: "onChangeTgl"
                }
            }, {
                xtype: "combo",
                reference: "combointerval",
                width: 75,
                store: {
                    fields: ["ID"],
                    data: [{
                        ID: 5
                    }, {
                        ID: 10
                    }, {
                        ID: 15
                    }, {
                        ID: 20
                    }, {
                        ID: 25
                    }, {
                        ID: 30
                    }]
                },
                editable: false,
                displayField: "ID",
                valueField: "ID",
                value: 15,
                bind: {
                    disabled: "{listConfig.autoRefresh}"
                }
            }, {
                xtype: "button",
                enableToggle: true,
                pressed: true,
                bind: {
                    iconCls: "{autoRefreshIcon}",
                    tooltip: "{tooltipAutoRefresh}"
                },
                toggleHandler: "onToggleRefresh"
            }]
        }, {
            xtype: "pagingtoolbar",
            bind: {
                store: "{store}"
            },
            dock: "bottom",
            displayInfo: true,
            items: ["-", {}, {}, {
                xtype: "search-field",
                cls: "x-text-border",
                autoFocus: true,
                emptyText: "Cari Nama Pasien",
                flex: 2,
                listeners: {
                    search: "onsearch",
                    clear: "onClear"
                }
            }, {
                xtype: "combobox",
                reference: "statusrespon",
                emptyText: "[ Filter Status ]",
                store: Ext.create("Ext.data.Store", {
                    fields: ["value", "desk"],
                    data: [{
                        value: "0",
                        desk: "Semua"
                    }, {
                        value: "1",
                        desk: "Belum Respon"
                    }, {
                        value: "2",
                        desk: "Sudah Respon"
                    }]
                }),
                queryMode: "local",
                displayField: "desk",
                flex: 1,
                valueField: "value",
                listeners: {
                    select: "onSelectStatus"
                }
            }]
        }];
        a.columns = [{
            text: "Nomor",
            dataIndex: "NOMOR",
            align: "left",
            flex: 0.3,
            renderer: "onAntrian"
        }, {
            text: "Poli Tujuan",
            dataIndex: "RUANGAN",
            align: "left",
            flex: 0.5,
            renderer: "onPoli"
        }, {
            text: "Dokter",
            dataIndex: "DOKTER",
            align: "left",
            flex: 0.5,
            renderer: "onDokterTujuan"
        }, {
            text: "Tgl. Kunjungan",
            align: "left",
            dataIndex: "TANGGAL_KUNJUGAN",
            flex: 0.5,
            renderer: "onTglK"
        }, {
            text: "Cara Bayar",
            align: "left",
            dataIndex: "PENJAMIN",
            flex: 0.5,
            renderer: "onCaraBayar"
        }, {
            text: "No RM",
            align: "left",
            dataIndex: "PASIEN",
            flex: 0.5,
            renderer: "onNorm"
        }, {
            text: "Nama",
            dataIndex: "NAMA",
            align: "left",
            renderer: "onRenderNama",
            flex: 0.5
        }, {
            text: "Tgl. Lahir",
            dataIndex: "TANGGAL_LAHIR",
            align: "left",
            renderer: "onRenderTgl",
            flex: 0.5
        }, {
            text: "Contact",
            align: "left",
            dataIndex: "KONTAK",
            flex: 0.5,
            renderer: "onCont"
        }, {
            text: "Jenis Pendaftar",
            align: "left",
            dataIndex: "JENIS",
            flex: 0.5,
            renderer: "onJenisPendaftaran"
        }, {
            text: "Res",
            xtype: "actioncolumn",
            align: "center",
            width: 50,
            items: [{
                xtype: "tool",
                iconCls: "x-fa fa-arrow-circle-right",
                tooltip: "Terima Kedatangan Pasien",
                handler: "onClickRespon"
            }]
        }];
        a.callParent(arguments)
    },
    listeners: {
        rowcontextmenu: "onKlikKananMenu"
    },
    createMenuContext: function () {
        var b = this;
        b.menucontext = new Ext.menu.Menu({
            items: [{
                text: "Terima Kedatangan Pasien",
                iconCls: "x-fa fa-arrow-circle-right",
                handler: function () {
                    b.getController().onRespon()
                }
            }, {
                text: "Refresh",
                glyph: "xf021@FontAwesome",
                handler: function () {
                    b.getController().onRefresh()
                }
            }]
        });
        return b.menucontext
    },
    loadData: function () {
        var f = this,
            b = f.down("[reference = btnAntrian]"),
            d = f.getViewModel(),
            j = webservice.app.xpriv,
            h = f.down("[reference=ftanggal]"),
            g = Ext.Date.format(d.get("tgltemp"), "d-m-Y"),
            e = Ext.Date.format(f.getSysdate(), "d-m-Y"),
            a = Ext.Date.format(f.getSysdate(), "Y-m-d");
        b.setHidden(!(j("900301", true) && j("900302", true)));
        if (g != e) {
            h.setValue(f.getSysdate());
            d.set("tgltemp", f.getSysdate())
        } else {
            f.reload()
        }
        d.set("tglSkrng", a)
    }
});
Ext.define("antrian.monitoring.kemkes.reservasi.GridController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-monitoring-kemkes-reservasi-grid",
    onToPendaftaranOnline: function () {
        var a = this.getView();
        a.fireEvent("changeActiveLayout", 1)
    },
    onsearch: function (j, g) {
        var h = this,
            a = h.getView(),
            d = h.getViewModel(),
            b = d.get("store");
        if (b) {
            b.removeAll();
            parameter = b.getQueryParams();
            b.setQueryParams({
                QUERY: g,
                TANGGAL_KUNJUNGAN: parameter.TANGGAL_KUNJUNGAN
            });
            b.load()
        }
    },
    onSelectStatus: function (b, g) {
        var f = this,
            e = f.getViewModel(),
            d = e.get("store");
        if (g.get("value") == "0") {
            delete d.queryParams.STATUS;
            d.removeAll()
        } else {
            d.removeAll();
            parameter = d.getQueryParams();
            d.setQueryParams({
                STATUS: g.get("value"),
                TANGGAL_KUNJUNGAN: parameter.TANGGAL_KUNJUNGAN,
                QUERY: parameter.QUERY
            })
        }
        d.load()
    },
    onClear: function () {
        var e = this,
            f = e.getViewModel(),
            a = f.get("store");
        delete a.queryParams.QUERY;
        a.removeAll();
        a.load()
    },
    onToggleRefresh: function (e, g) {
        var f = this,
            a = f.getView(),
            d = f.getReferences(),
            b = Number(d.combointerval.getValue()) * 1000;
        a.setListConfig({
            autoRefresh: e.pressed
        });
        if (e.pressed) {
            a.start(b)
        } else {
            a.stop()
        }
    },
    onJadwalDokter: function (b) {
        var d = this,
            a = d.getView();
        dialog = a.openDialog("", true, 0, 0, {
            xtype: "antrian-monitoring-kemkes-jadwaldokter-workspace",
            header: {
                iconCls: "x-fa fa-users",
                padding: "7px 7px 7px 7px",
                title: "Jadwal Dokter"
            },
            ui: "panel-cyan",
            showCloseButton: true,
            hideColumns: true
        }, function (f, g) {
            var e = g.down("antrian-monitoring-kemkes-jadwaldokter-workspace");
            e.load()
        })
    },
    onChangeTgl: function () {
        var d = this,
            a = d.getReferences(),
            e = a.ftanggal,
            b = {};
        obj = {
            TANGGAL_KUNJUNGAN: Ext.Date.format(e.getValue(), "Y-m-d")
        };
        d.getView().load(obj)
    },
    onPostAntrian: function (b, a, e) {
        var d = "Pos 1";
        this.setBackGround(a, e.get("STATUS"));
        return d
    },
    onDokterTujuan: function (b, a, e) {
        var d = e.get("REFERENSI") ? e.get("REFERENSI").NAMA : "";
        this.setBackGround(a, e.get("STATUS"));
        return d
    },
    onJenisPendaftaran: function (b, a, e) {
        var d = (b == 2) ? "Pasien Lama" : "Pasien Baru";
        this.setBackGround(a, e.get("STATUS"));
        return d
    },
    onRenderJenis: function (b, a, e) {
        var d = e.get("JENIS") == 1 ? "Pasien Lama" : "Pasien Baru";
        this.setBackGround(a, e.get("STATUS"));
        return d
    },
    onRenderNama: function (b, a, e) {
        var d = e.get("NAMA");
        this.setBackGround(a, e.get("STATUS"));
        return d
    },
    onAntrian: function (b, a, d) {
        this.setBackGround(a, d.get("STATUS"));
        return b
    },
    onPoli: function (d, b, e) {
        var a = e.get("REFERENSI").RUANGAN.DESKRIPSI;
        this.setBackGround(b, e.get("STATUS"));
        return a
    },
    onTglK: function (b, a, d) {
        this.setBackGround(a, d.get("STATUS"));
        return Ext.Date.format(d.get("TANGGAL_KUNJUNGAN"), "Y-m-d")
    },
    onCaraBayar: function (b, a, d) {
        this.setBackGround(a, d.get("STATUS"));
        return b
    },
    onNorm: function (d, b, e) {
        var a = d == 0 ? "" : d;
        this.setBackGround(b, e.get("STATUS"));
        return a
    },
    onCont: function (b, a, d) {
        this.setBackGround(a, d.get("STATUS"));
        return b
    },
    onRenderTgl: function (b, a, e) {
        var d = Ext.Date.format(b, "Y-m-d H:i:s");
        this.setBackGround(a, e.get("STATUS"));
        return d
    },
    setBackGround: function (b, a) {
        if (a == 2) {
            b.style = "background-color:#0df775;color:#000000;font-weight: bold"
        }
    },
    onKlikKananMenu: function (e, j, n, k, l) {
        var o = this,
            m = l.getXY();
        l.stopEvent();
        o.getView().menucontext.showAt(m)
    },
    onClickRespon: function (d, g, b) {
        var e = this,
            a = e.getView(),
            f = d.getStore().getAt(g);
        if (f.get("STATUS") == 1) {
            e.onResponPasien(f)
        } else {
            a.notifyMessage("Data Sudah Di Respon")
        }
    },
    onResponPasien: function (f) {
        var d = this,
            a = d.getView(),
            b = d.getViewModel().get("tglSkrng"),
            e = Ext.Date.format(f.get("TANGGAL_KUNJUNGAN"), "Y-m-d"),
            g = f.get("JENIS") == 2 ? "Terima kedatangan pasien norm " + f.get("PASIEN") + " - " + f.get("NAMA") + "?" : "Terima kedatangan pasien dengan nama " + f.get("NAMA") + " ?";
        if (b === e) {
            Ext.Msg.show({
                title: "Respon Pasien",
                message: g,
                buttons: Ext.Msg.YESNO,
                icon: Ext.Msg.QUESTION,
                animateTarget: f,
                fn: function (h) {
                    if (h === "yes") {
                        f.set("STATUS", 2);
                        f.save({
                            callback: function (k, j, l) {
                                if (l) {
                                    a.notifyMessage("Data Berhasil Di Respon");
                                    f.set("NORM", f.get("PASIEN"));
                                    a.fireEvent("openpasien", f, f.get("JENIS") == 2)
                                } else {
                                    a.notifyMessage("Data Gagal Di Respon")
                                }
                            }
                        })
                    }
                }
            })
        } else {
            a.notifyMessage("Pasien Belum Bisa Di Daftar, Hanya tanggal Kunjungan Hari ini yang dapat direspon")
        }
    },
    onRespon: function () {
        var a = this,
            b = a.getView().getSelection()[0];
        a.onResponPasien(b)
    },
    onRefresh: function () {
        var a = this.getView();
        a.reload()
    }
});
Ext.define("antrian.monitoring.pengaturan.Form", {
    extend: "com.Form",
    xtype: "antrian-monitoring-pengaturan-form",
    requires: ["Ext.picker.Time"],
    controller: "antrian-monitoring-pengaturan-form",
    viewModel: {
        stores: {
            storesCekDuplikat: {
                type: "antrian-pengaturan-store",
                queryParams: {
                    STATUS: 1,
                    start: 0,
                    limit: 1
                }
            }
        },
        data: {
            isNewForm: true
        }
    },
    layout: {
        type: "hbox"
    },
    model: "antrian.pengaturan.Model",
    initComponent: function () {
        var a = this;
        a.items = [{
            xtype: "antrian-combo-pos-antrian",
            name: "POS_ANTRIAN",
            firstLoad: true,
            reference: "fpos",
            allowBlank: false,
            margin: "0 10 0 0",
            flex: 1
        }, {
            xtype: "numberfield",
            emptyText: "[ Limit ]",
            name: "LIMIT_DAFTAR",
            flex: 1,
            allowBlank: false,
            hideTrigger: true,
            margin: "0 10 0 0"
        }, {
            xtype: "numberfield",
            emptyText: "[ Batas Maksimal Hari ]",
            name: "BATAS_MAX_HARI",
            flex: 1,
            allowBlank: false,
            hideTrigger: true,
            margin: "0 10 0 0"
        }, {
            xtype: "numberfield",
            emptyText: "[ Batas Maksimal Hari Mobile JKN ]",
            name: "BATAS_MAX_HARI_BPJS",
            flex: 1,
            allowBlank: false,
            hideTrigger: true,
            margin: "0 10 0 0"
        }, {
            xtype: "numberfield",
            emptyText: "[ Durasi Layanan (menit) ]",
            name: "DURASI",
            allowBlank: false,
            flex: 1,
            margin: "0 10 0 0"
        }, {
            name: "MULAI",
            reference: "mulai",
            flex: 0.5,
            xtype: "timefield",
            emptyText: "[Jam:Mnt:Dtk]",
            format: "H:i:s",
            hideTrigger: true,
            margin: "0 10 0 0"
        }, {
            xtype: "button",
            text: "Simpan",
            ui: "soft-blue",
            handler: "onSimpan"
        }];
        a.callParent(arguments)
    },
    onLoadRecord: function (e) {
        var d = this,
            b = d.down("[name=MULAI]"),
            a = d.getSysdate();
        b.setValue(a)
    },
    isDuplikcatDataEntry: function (e) {
        var b = this,
            a = b.getViewModel().get("storesCekDuplikat");
        if (a) {
            var d = b.down("antrian-combo-pos-antrian").getValue();
            a.queryParams.POS_ANTRIAN = d;
            a.load(function (g, f, h) {
                if (Ext.isArray(g)) {
                    e(g.length > 0)
                }
            })
        }
    }
});
Ext.define("antrian.monitoring.pengaturan.FormController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-monitoring-pengaturan-form",
    onSimpan: function (b) {
        var d = this,
            a = d.getView();
        a.isDuplikcatDataEntry(function (f) {
            if (f) {
                var e = d.getViewModel().get("storesCekDuplikat"),
                    g = e.getAt(0);
                g.set("STATUS", 0);
                g.save({
                    callback: function (h, j, k) {
                        if (k) {
                            a.save(b)
                        } else {
                            a.notifyMessage("Gagal")
                        }
                    }
                })
            } else {
                a.save(b)
            }
        })
    }
});
Ext.define("antrian.monitoring.pengaturan.List", {
    extend: "com.Grid",
    alias: "widget.antrian-monitoring-pengaturan-list",
    controller: "antrian-monitoring-pengaturan-list",
    viewModel: {
        stores: {
            store: {
                type: "antrian-pengaturan-store"
            }
        }
    },
    cls: "x-br-top",
    border: true,
    initComponent: function () {
        var a = this;
        a.columns = [{
            xtype: "rownumberer",
            text: "No",
            align: "left",
            width: 40
        }, {
            dataIndex: "POS_ANTRIAN",
            text: "POS",
            width: 70,
            renderer: "onRenderPos"
        }, {
            dataIndex: "LIMIT_DAFTAR",
            text: "Limit",
            flex: 1,
            renderer: "onRenderLimit"
        }, {
            dataIndex: "BATAS_MAX_HARI",
            text: "Maksimal Hari Pendaftaran",
            flex: 1,
            renderer: "onRenderLimitHari"
        }, {
            dataIndex: "BATAS_MAX_HARI_BPJS",
            text: "Maksimal Hari Pendaftaran Mobile JKN",
            flex: 1,
            renderer: "onRenderLimitHariMobileJkn"
        }, {
            dataIndex: "TANGGALKUNJUNGAN",
            text: "Durasi",
            flex: 1,
            renderer: "onRenderDurasi"
        }, {
            xtype: "datecolumn",
            dataIndex: "MULAI",
            text: "Jam Mulai Layanan",
            flex: 1,
            format: "H:i:s",
            renderer: "onRenderMulai"
        }], a.dockedItems = [{
            xtype: "pagingtoolbar",
            bind: {
                store: "{store}"
            },
            dock: "bottom",
            displayInfo: true
        }];
        a.callParent(arguments)
    },
    onLoadRecord: function () {
        var b = this,
            a = b.getViewModel().get("store");
        a.load()
    }
});
Ext.define("antrian.monitoring.pengaturan.ListController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-monitoring-pengaturan-list",
    onRenderPos: function (b, a, d) {
        var e = d.get("POS_ANTRIAN");
        this.setBackGround(a, d.get("STATUS"));
        return e
    },
    onRenderLimit: function (d, b, e) {
        var a = e.get("LIMIT_DAFTAR");
        this.setBackGround(b, e.get("STATUS"));
        return a
    },
    onRenderLimitHari: function (d, b, e) {
        var a = e.get("BATAS_MAX_HARI");
        this.setBackGround(b, e.get("STATUS"));
        return a
    },
    onRenderLimitHariMobileJkn: function (d, b, e) {
        var a = e.get("BATAS_MAX_HARI_BPJS");
        this.setBackGround(b, e.get("STATUS"));
        return a
    },
    onRenderDurasi: function (b, a, e) {
        var d = e.get("DURASI");
        this.setBackGround(a, e.get("STATUS"));
        return d
    },
    onRenderMulai: function (d, b, e) {
        var a = Ext.Date.format(e.get("MULAI"), "H:i:s");
        this.setBackGround(b, e.get("STATUS"));
        return a
    },
    setBackGround: function (b, a) {
        if (a == 1) {
            b.style = "background-color:#0df775;color:#000000;font-weight: bold"
        }
    }
});
Ext.define("antrian.monitoring.pengaturan.Workspace", {
    extend: "com.Form",
    xtype: "antrian-monitoring-pengaturan-workspace",
    controller: "antrian-monitoring-pengaturan-workspace",
    layout: {
        type: "hbox",
        align: "stretch"
    },
    flex: 1,
    initComponent: function () {
        var a = this;
        a.items = [{
            flex: 1,
            layout: "border",
            items: [{
                region: "north",
                border: true,
                xtype: "antrian-monitoring-pengaturan-form",
                reference: "formpengaturanregistrasi",
                listeners: {
                    save: "onSuccess"
                }
            }, {
                region: "center",
                xtype: "antrian-monitoring-pengaturan-list",
                reference: "listpengaturanregistrasi",
                header: {
                    iconCls: "x-fa fa-list",
                    padding: "7px 7px 7px 7px",
                    title: "Detail"
                }
            }]
        }];
        a.callParent(arguments)
    },
    load: function () {
        var a = this;
        form = a.down("antrian-monitoring-pengaturan-form");
        list = a.down("antrian-monitoring-pengaturan-list");
        form.createRecord({});
        list.onLoadRecord()
    }
});
Ext.define("antrian.monitoring.pengaturan.WorkspaceController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-monitoring-pengaturan-workspace",
    onSuccess: function (d, g, b) {
        var f = this,
            a = f.getView(),
            e = f.getViewModel();
        a.load()
    }
});
Ext.define("antrian.monitoring.posantrian.List", {
    extend: "com.Grid",
    alias: "widget.antrian-monitoring-posantrian-list",
    controller: "antrian-monitoring-posantrian-list",
    viewModel: {
        stores: {
            store: {
                type: "antrian-posantrian-store",
                queryParams: {}
            }
        }
    },
    checkboxModel: false,
    posAntrianAkses: true,
    initComponent: function () {
        var a = this;
        a.columns = [{
            xtype: "rownumberer",
            text: "No",
            align: "left",
            width: 60,
            hidden: a.checkboxModel
        }, {
            text: a.posAntrianAkses ? "Ruangan Akses" : "Ruangan",
            flex: 1,
            dataIndex: "DESKRIPSI",
            renderer: "onRenderer"
        }, {
            text: "Status",
            flex: 1,
            hidden: a.posAntrianAkses,
            dataIndex: "STATUS",
            renderer: "onStatus"
        }];
        a.callParent(arguments)
    },
    doAfterLoad: function (g, h, j, a) {
        var e = this,
            b = e.getSelectionModel();
        if (Ext.isArray(h)) {
            if (h.length > 0) {
                for (var d = 0; d < h.length; d++) {
                    if (b) {
                        if (e.checkboxModel) {
                            var f = h[d].get("checked") ? (h[d].get("checked") == 1) : false;
                            if (f) {
                                b.select(h[d], true)
                            }
                        }
                    }
                }
            }(new Ext.util.DelayedTask(function () {})).delay(100)
        }
    }
});
Ext.define("antrian.monitoring.posantrian.ListController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-monitoring-posantrian-list",
    onRenderer: function (a, k, b, j) {
        var f = this,
            h = f.getView(),
            e = a.length,
            g = ((b.get("JENIS") - 1) * 10),
            d = h.getSelectionModel();
        return h.ruanganAkses ? Ext.String.leftPad(a, e + g, "&nbsp;") : a
    },
    onStatus: function (g, d, f, a) {
        var b = this,
            e = (f.get("STATUS") == 1) ? "Aktif" : "Non Aktif";
        if (f.get("STATUS") == 1) {
            return '<span style="color:green;">' + e + "</span>"
        } else {
            return '<span style="color:red;">' + e + "</span>"
        }
    }
});
Ext.define("antrian.monitoring.ruangan.List", {
    extend: "com.Grid",
    alias: "widget.antrian-monitoring-ruangan-list",
    controller: "antrian-monitoring-ruangan-list",
    viewModel: {
        stores: {
            store: {
                type: "ruangan-antrian-store",
                autoSync: true
            }
        },
        data: {
            validasiAntrianPerRuangan: true
        }
    },
    ui: "panel-cyan",
    initComponent: function () {
        var a = this;
        a.cellEditing = new Ext.grid.plugin.CellEditing({
            clicksToEdit: 1,
            listeners: {
                beforeedit: "BeforeEdit"
            }
        });
        a.plugins = [a.cellEditing], a.columns = [{
            xtype: "rownumberer",
            text: "No",
            align: "left",
            width: 60
        }, {
            text: "Ruangan / Poliklinik",
            flex: 1,
            dataIndex: "DESKRIPSI"
        }, {
            text: "Pos Antrian",
            width: 100,
            dataIndex: "ANTRIAN",
            renderer: "onRenderPos",
            editor: {
                xtype: "antrian-combo-pos-antrian",
                name: "ANTRIAN",
                firstLoad: true
            }
        }, {
            text: "Limit / Kouta",
            width: 100,
            dataIndex: "LIMIT_DAFTAR",
            renderer: "onRenderLimit",
            bind: {
                hidden: "{validasiAntrianPerRuangan}"
            },
            editor: {
                xtype: "numberfield",
                name: "LIMIT_DAFTAR",
                firstLoad: true
            }
        }, {
            text: "Durasi Pendaftaran (menit)",
            width: 100,
            dataIndex: "DURASI_PENDAFTARAN",
            renderer: "onRenderDurasi",
            bind: {
                hidden: "{validasiAntrianPerRuangan}"
            },
            editor: {
                xtype: "numberfield",
                name: "DURASI_PENDAFTARAN",
                firstLoad: true
            }
        }, {
            text: "Durasi Pelayanan (menit)",
            width: 100,
            dataIndex: "DURASI_PELAYANAN",
            renderer: "onRenderDurasiPelayanan",
            bind: {
                hidden: "{validasiAntrianPerRuangan}"
            },
            editor: {
                xtype: "numberfield",
                name: "DURASI_PELAYANAN",
                firstLoad: true
            }
        }, {
            text: "Jam Mulai",
            width: 100,
            dataIndex: "MULAI",
            renderer: "onRenderMulai",
            bind: {
                hidden: "{validasiAntrianPerRuangan}"
            },
            editor: {
                xtype: "timefield",
                name: "MULAI",
                format: "H:i:s",
                firstLoad: true,
                hideTrigger: true
            }
        }, {
            text: "JUMLAH_MEJA",
            width: 100,
            dataIndex: "JUMLAH_MEJA",
            renderer: "onRenderJmlMeja",
            bind: {
                hidden: "{validasiAntrianPerRuangan}"
            },
            editor: {
                xtype: "numberfield",
                name: "JUMLAH_MEJA",
                firstLoad: true
            }
        }], a.dockedItems = [{
            xtype: "pagingtoolbar",
            bind: {
                store: "{store}"
            },
            dock: "bottom",
            displayInfo: true
        }];
        a.callParent(arguments)
    },
    onLoadRecord: function () {
        var d = this,
            b = d.getViewModel(),
            a = d.getViewModel().get("store");
        if (d.getPropertyConfig("33") == "TRUE") {
            b.set("validasiAntrianPerRuangan", false)
        } else {
            b.set("validasiAntrianPerRuangan", true)
        }
        a.load()
    }
});
Ext.define("antrian.monitoring.ruangan.ListController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-monitoring-ruangan-list",
    currentRecord: undefined,
    BeforeEdit: function (b, f, a) {
        var d = this;
        d.currentRecord = f.record
    },
    onSelectPos: function (e, d) {
        var f = this;
        f.currentRecord = d
    },
    onSimpan: function (d) {
        var e = this,
            b = e.getStore("store"),
            f = b.getModifiedRecords(),
            a = 0;
        Ext.Array.each(f, function (g) {
            g.save({
                callback: function (h, j, k) {
                    if (k) {
                        if (a === (f.length - 1)) {
                            e.getView().notifyMessage("Data telah disimpan")
                        }
                    }
                    a++
                }
            })
        })
    },
    onRefresh: function (b) {
        var d = this,
            a = d.getView();
        a.refresh()
    },
    onStatusCheck: function (d, f, b) {
        var e = this,
            g = f ? 1 : 0,
            a = d.getWidgetRecord();
        if (a) {
            if (a.get("STATUS") === g) {
                return
            }
            a.set("STATUS", g);
            a.save({
                callback: function (h, j, k) {
                    if (k) {
                        e.getView().notifyMessage("Data telah disimpan")
                    }
                }
            })
        }
    },
    onRenderPos: function (e, b, d) {
        var a = d.get("ANTRIAN");
        return a
    },
    onRenderLimit: function (e, b, d) {
        var a = d.get("LIMIT_DAFTAR");
        return a
    },
    onRenderDurasi: function (e, b, d) {
        var a = d.get("DURASI_PENDAFTARAN");
        return a
    },
    onRenderDurasiPelayanan: function (e, b, d) {
        var a = d.get("DURASI_PELAYANAN");
        return a
    },
    onRenderMulai: function (e, b, d) {
        var a = Ext.Date.format(d.get("MULAI"), "H:i:s");
        return a
    },
    onRenderJmlMeja: function (e, b, d) {
        var a = d.get("JUMLAH_MEJA");
        return a
    },
    onRuanganCombo: function (a, e) {
        var d = this.getView().getSelection();
        if (e) {
            if (Ext.isArray(d)) {
                if (d.length > 0) {
                    var b = d[0].get("REFERENSI") || {};
                    b.RUANGAN_RS = e.data;
                    d[0].set("REFERENSI", b)
                }
            }
        }
    },
    onRuanganRenderer: function (f, d, b) {
        var e = b.get("REFERENSI"),
            a = e ? e.RUANGAN_RS : null;
        if (a) {
            return a.DESKRIPSI
        }
        return f
    }
});
Ext.define("antrian.monitoring.ruangan.Workspace", {
    extend: "com.Form",
    xtype: "antrian-monitoring-ruangan-workspace",
    bodyPadding: 2,
    layout: {
        type: "hbox",
        align: "stretch"
    },
    flex: 1,
    initComponent: function () {
        var a = this;
        a.items = [{
            xtype: "antrian-monitoring-ruangan-list",
            flex: 1,
            reference: "listmonitoringruangan",
            header: {
                iconCls: "x-fa fa-list",
                padding: "7px 7px 7px 7px",
                title: "Daftar Pos Antrian Ruangan"
            }
        }];
        a.callParent(arguments)
    },
    onLoadRecord: function (d) {
        var a = this,
            b = a.down("antrian-monitoring-ruangan-list");
        b.onLoadRecord({})
    }
});
Ext.define("antrian.onsite.CaraBayar", {
    extend: "com.Form",
    alias: "widget.antrian-onsite-carabayar",
    layout: {
        type: "vbox",
        align: "middle",
        pack: "center"
    },
    initComponent: function () {
        var a = this;
        a.items = [{
            layout: {
                type: "vbox",
                pack: "center"
            },
            width: 750,
            height: 400,
            items: [{
                xtype: "component",
                margin: "0 0 20 0",
                style: "font-size:18px;padding:15px;background:#D8F1EC;width:100%",
                html: "* Ambil Antrian Berdasarkan Jaminan *"
            }, {
                xtype: "button",
                style: "border-radius:10px",
                html: '<b style="font-size:32px">BPJS / JKN</b>',
                ui: "soft-blue",
                margin: "0 0 20 0",
                width: "100%",
                height: 100,
                scale: "large",
                handler: "onPasienBpjs"
            }, {
                xtype: "button",
                style: "border-radius:10px",
                html: '<b style="font-size:32px">UMUM / CORPORATE</b>',
                ui: "soft-green",
                width: "100%",
                height: 100,
                scale: "large",
                handler: "onPasienNonBpjs"
            }]
        }];
        a.callParent(arguments)
    }
});
Ext.define("antrian.onsite.List", {
    extend: "com.Grid",
    xtype: "antrian-onsite-list",
    controller: "antrian-onsite-list",
    viewModel: {
        stores: {
            store: {
                type: "antrian-posantrian-store"
            }
        }
    },
    initComponent: function () {
        var a = this;
        a.columns = [{
            text: "Pos Antrian",
            flex: 1,
            dataIndex: "DESKRIPSI"
        }, {
            text: "View",
            xtype: "actioncolumn",
            align: "center",
            width: 50,
            items: [{
                xtype: "tool",
                iconCls: "x-fa fa-arrow-circle-right",
                tooltip: "View Display Ambil Antrian",
                handler: "onViewDisplay"
            }]
        }, {
            text: "V2",
            xtype: "actioncolumn",
            align: "center",
            width: 50,
            items: [{
                xtype: "tool",
                iconCls: "x-fa fa-arrow-circle-right",
                tooltip: "View Display Ambil Antrian V2",
                handler: "onViewAntrianV2"
            }]
        }];
        a.callParent(arguments)
    },
    onLoadRecord: function (d) {
        var b = this,
            a = b.getViewModel().get("store");
        a.setQueryParams({
            STATUS: 1
        });
        a.load()
    }
});
Ext.define("antrian.onsite.ListController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-onsite-list",
    onViewDisplay: function (d, g, b) {
        var e = this,
            a = e.getView(),
            f = d.getStore().getAt(g);
        if (f) {
            a.openDialog("", true, "100%", "100%", {
                xtype: "antrian-onsite-workspace",
                ui: "panel-cyan",
                title: "Ambil Antrian",
                scrollable: false
            }, function (k, j) {
                var h = j.down("antrian-onsite-workspace");
                h.onLoadRecord(f)
            }, e, true, false)
        } else {
            a.notifyMessage("Record Not Found")
        }
    },
    onViewAntrianV2: function (d, g, b) {
        var e = this,
            a = e.getView(),
            f = d.getStore().getAt(g);
        if (f) {
            a.openDialog("", true, "100%", "100%", {
                xtype: "antrian-onsite-v2-workspace",
                ui: "panel-cyan",
                title: "Ambil Antrian",
                scrollable: false
            }, function (k, j) {
                var h = j.down("antrian-onsite-v2-workspace");
                h.onLoadRecord(f)
            }, e, true, false)
        } else {
            a.notifyMessage("Record Not Found")
        }
    }
});
Ext.define("antrian.onsite.V2.Baru.Form", {
    extend: "com.Form",
    alias: "widget.antrian-onsite-v2-baru-form",
    layout: {
        type: "vbox",
        align: "stretch"
    },
    model: "antrian.reservasi.Model",
    defaultType: "textfield",
    fieldDefaults: {
        labelAlign: "top",
        fieldStyle: "font-size:16px;font-weight:bold"
    },
    items: [{
        name: "NIK",
        reference: "nik",
        emptyText: "NIK",
        fieldLabel: "NIK Pasien",
        allowBlank: false,
        height: 70,
        hideTrigger: true,
        margin: "0 0 20 0"
    }, {
        name: "NAMA",
        reference: "nama",
        emptyText: "Nama",
        fieldLabel: "Nama Pasien",
        allowBlank: false,
        height: 70,
        hideTrigger: true,
        margin: "0 0 20 0"
    }, {
        xtype: "fieldcontainer",
        layout: "hbox",
        defaultType: "textfield",
        items: [{
            name: "TEMPAT_LAHIR",
            reference: "tmplahir",
            emptyText: "Tempat Lahir",
            fieldLabel: "Tempat Lahir",
            allowBlank: false,
            height: 70,
            flex: 1,
            margin: "0 10 20 0"
        }, {
            xtype: "datefield",
            name: "TANGGAL_LAHIR",
            reference: "tgllahirnew",
            fieldLabel: "Tanggal Lahir",
            height: 70,
            flex: 1,
            format: "Y-m-d",
            emptyText: "Tanggal Lahir",
            allowBlank: false,
            margin: "0 0 20 0"
        }]
    }, {
        name: "KONTAK",
        reference: "kontak",
        emptyText: "No.Telepon / HP",
        fieldLabel: "Kontak Pasien",
        allowBlank: false,
        height: 70,
        hideTrigger: true,
        margin: "0 0 45 0"
    }, {
        text: "Lanjut",
        xtype: "button",
        margin: "0 0 20 0",
        iconCls: "x-fa fa-arrow-right",
        reference: "btndoformnew",
        scale: "large",
        iconAlign: "right",
        ui: "soft-blue",
        handler: "onNextNew"
    }],
    onLoadRecord: function (a) {
        var f = this,
            e = f.down("[reference=nik]"),
            h = f.down("[reference=nama]"),
            g = f.down("[reference=kontak]"),
            d = f.down("[reference=tmplahir]"),
            b = f.down("[reference=tgllahirnew]");
        e.setValue("");
        h.setValue("");
        g.setValue("");
        d.setValue("");
        b.setValue("");
        f.focus("NIK")
    }
});
Ext.define("antrian.onsite.V2.Form", {
    extend: "com.Tab",
    alias: "widget.antrian-onsite-v2-form",
    controller: "antrian-onsite-v2-form",
    viewModel: {
        stores: {
            storepsn: {
                type: "antrian-pasien-store"
            }
        },
        data: {
            posAntrian: undefined
        }
    },
    activeTab: 0,
    cls: "x-br-top",
    ui: "header-tab",
    tabBar: {
        layout: {
            pack: "center"
        },
        border: false
    },
    initComponent: function () {
        var a = this;
        a.items = [{
            title: "<div id='psn1' style='color:black;font-weight:bold;font-size:16px'>Pasien Lama</div>",
            bodyPadding: 50,
            reference: "formpasienlama",
            xtype: "antrian-onsite-v2-lama-form"
        }, {
            title: "<div id='psn2' style='color:black;font-weight:bold;font-size:16px'>Pasien Baru</div>",
            bodyPadding: 50,
            reference: "formpasienbaru",
            xtype: "antrian-onsite-v2-baru-form"
        }];
        a.callParent(arguments)
    },
    loadStore: function (d) {
        var b = this,
            a = this.getActiveTab();
        b.rec = d;
        if (a) {
            a.onLoadRecord({})
        } else {
            if (b.items.getCount() > 0) {
                b.setActiveTab(0)
            }
        }
    },
    onLoadRecord: function (a) {
        var b = this;
        psnlama = b.down("[reference=formpasienlama]"), psnbaru = b.down("[reference=formpasienbaru]");
        psnlama.createRecord({
            POS_ANTRIAN: a.get("NOMOR")
        });
        psnbaru.createRecord({
            POS_ANTRIAN: a.get("NOMOR")
        });
        psnlama.focus("NORM");
        b.getViewModel().set("posAntrian", a.get("NOMOR"))
    },
    listeners: {
        tabchange: "onTabChange"
    }
});
Ext.define("antrian.onsite.V2.FormController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-onsite-v2-form",
    onTabChange: function (d, a) {
        var b = this,
            e = b.getView().getViewModel().get("posAntrian");
        if (a.load) {
            a.createRecord({
                POS_ANTRIAN: e
            })
        }
    },
    onRefresh: function (a) {
        var d = this;
        d.getView().load({})
    },
    onNextOld: function (a) {
        var b = this;
        view = b.getView(), ref = b.getReferences(), store = view.getViewModel().get("storepsn"), rec = ref.formpasienlama.getRecord();
        if (rec) {
            store.queryParams = {
                NORM: rec.get("NORM"),
                TANGGAL_LAHIR: Ext.Date.format(rec.get("TANGGAL_LAHIR"), "Y-m-d")
            };
            view.setLoading(true);
            store.load({
                callback: function (f, e, g) {
                    if (g) {
                        var d = {
                            NORM: f[0].get("NORM"),
                            NAMA: f[0].get("NAMA"),
                            TEMPAT_LAHIR: f[0].get("REFERENSI") ? (f[0].get("REFERENSI").TEMPATLAHIR ? f[0].get("REFERENSI").TEMPATLAHIR.DESKRIPSI : "-") : "-",
                            TANGGAL_LAHIR: f[0].get("TANGGAL_LAHIR"),
                            JENIS_KELAMIN: f[0].get("JENIS_KELAMIN"),
                            ALAMAT: f[0].get("ALAMAT"),
                            NIK: f[0].get("KARTUIDENTITAS") ? f[0].get("KARTUIDENTITAS")[0].NOMOR : "0",
                            NO_KARTU_BPJS: f[0].get("NO_KARTU_BPJS") ? f[0].get("NO_KARTU_BPJS") : "",
                            JENIS: 1,
                            POS_ANTRIAN: rec.get("POS_ANTRIAN"),
                            TANGGALKUNJUNGAN: Ext.Date.format(view.getSysdate(), "Y-m-d"),
                            JENIS_APLIKASI: 22,
                            CONTACT: f[0].get("KONTAK") ? f[0].get("KONTAK")[0].NOMOR : "0"
                        };
                        b.showWindow(a, d, "Registrasi Antrian Pasien Lama");
                        view.setLoading(false)
                    } else {
                        b.getView().notifyMessage("Data Identitas Pasien tidak ditemukan", "danger-red")
                    }
                    view.setLoading(false)
                }
            })
        } else {
            b.getView().notifyMessage("Silahkan lengkapi data", "danger-red")
        }
    },
    onNextNew: function (b) {
        var d = this;
        view = d.getView(), ref = d.getReferences(), rec = ref.formpasienbaru.getRecord();
        if (rec) {
            view.setLoading(true);
            var a = {
                NORM: rec.get("NORM"),
                NAMA: rec.get("NAMA"),
                TEMPAT_LAHIR: rec.get("TEMPAT_LAHIR"),
                TANGGAL_LAHIR: rec.get("TANGGAL_LAHIR"),
                JENIS_KELAMIN: "",
                ALAMAT: "",
                NIK: rec.get("NIK"),
                NO_KARTU_BPJS: "",
                JENIS: 2,
                POS_ANTRIAN: rec.get("POS_ANTRIAN"),
                TANGGALKUNJUNGAN: Ext.Date.format(view.getSysdate(), "Y-m-d"),
                JENIS_APLIKASI: 22,
                CONTACT: rec.get("KONTAK")
            };
            d.showWindow(b, a, "Registrasi Antrian Pasien Baru");
            view.setLoading(false)
        } else {
            d.getView().notifyMessage("Silahkan lengkapi data", "danger-red")
        }
    },
    privates: {
        showWindow: function (d, b, f) {
            var e = this,
                a = e.getView();
            a.openDialog("", true, "100%", "100%", {
                title: f,
                xtype: "antrian-onsite-v2-registrasi-workspace",
                ui: "panel-cyan",
                hideHeaders: false,
                scrollable: true,
                showCloseButton: true
            }, function (j, h) {
                var g = h.down("antrian-onsite-v2-registrasi-workspace");
                g.onLoadRecord(b);
                g.on("success", function (k) {
                    e.onSetNewAntrian();
                    h.close()
                })
            }, d, true, true)
        }
    },
    onSetNewAntrian: function () {
        var e = this,
            b = e.getReferences(),
            a = e.getView().getViewModel().get("posAntrian"),
            f = b.formpasienlama,
            d = b.formpasienbaru;
        f.createRecord({
            POS_ANTRIAN: a
        });
        d.createRecord({
            POS_ANTRIAN: a
        });
        f.focus("NORM")
    }
});
Ext.define("antrian.onsite.V2.Lama.Form", {
    extend: "com.Form",
    alias: "widget.antrian-onsite-v2-lama-form",
    layout: {
        type: "vbox",
        align: "stretch"
    },
    model: "antrian.reservasi.Model",
    defaultType: "textfield",
    fieldDefaults: {
        labelAlign: "top",
        fieldStyle: "font-size:16px;font-weight:bold"
    },
    items: [{
        name: "NORM",
        reference: "norm",
        emptyText: "No. Rekam Medis / NIK",
        fieldLabel: "No. Rekam Medis / NIK",
        allowBlank: false,
        height: 70,
        hideTrigger: true,
        margin: "0 0 20 0"
    }, {
        xtype: "datefield",
        name: "TANGGAL_LAHIR",
        reference: "tgllahirold",
        fieldLabel: "Tanggal Lahir",
        height: 70,
        format: "Y-m-d",
        emptyText: "Tanggal Lahir",
        allowBlank: false,
        margin: "0 0 45 0"
    }, {
        text: "Lanjut",
        xtype: "button",
        margin: "0 0 20 0",
        iconCls: "x-fa fa-arrow-right",
        reference: "btndoformold",
        scale: "large",
        iconAlign: "right",
        ui: "soft-blue",
        handler: "onNextOld"
    }],
    onLoadRecord: function (a) {
        var e = this,
            d = e.down("[reference=norm]"),
            b = e.down("[reference=tgllahirold]");
        d.setValue("");
        b.setValue("");
        e.focus("NORM")
    }
});
Ext.define("antrian.onsite.V2.Registrasi.Bukti", {
    extend: "com.Form",
    alias: "widget.antrian-onsite-v2-registrasi-bukti",
    controller: "antrian-onsite-v2-registrasi-bukti",
    viewModel: {
        data: {
            recordantrian: undefined
        }
    },
    layout: {
        type: "vbox",
        align: "stretch"
    },
    bodyPadding: 40,
    fieldDefaults: {
        fieldStyle: "font-size:16px;font-style:italic",
        labelStyle: "font-size:16px;font-style:italic"
    },
    items: [{
        style: "padding:10px;font-size:20px;border-bottom:1px #DDD solid;text-left:center;font-style:italic;color:teal;background-color:teal;",
        bodyStyle: "background-color:transparent;color:#FFF;font-size:20px;text-align:center",
        html: "PROSES PENGAMBILAN ANTRIAN BERHASIL",
        margin: "0 0 20 0"
    }, {
        xtype: "displayfield",
        reference: "kodeantrian",
        fieldLabel: "Kode Antrian",
        height: 30,
        labelWidth: 200,
        margin: "0 0 20 0"
    }, {
        xtype: "displayfield",
        reference: "noantrian",
        fieldLabel: "No. Antrian",
        height: 30,
        labelWidth: 200,
        margin: "0 0 20 0"
    }, {
        xtype: "displayfield",
        reference: "namapasien",
        fieldLabel: "Nama Pasien",
        height: 30,
        labelWidth: 200,
        margin: "0 0 20 0"
    }, {
        xtype: "displayfield",
        reference: "politujuan",
        fieldLabel: "Poliklinik Tujuan",
        height: 30,
        labelWidth: 200,
        margin: "0 0 20 0",
        readOnly: true
    }, {
        xtype: "displayfield",
        reference: "dokter",
        fieldLabel: "Dokter",
        height: 30,
        labelWidth: 200,
        margin: "0 0 20 0",
        readOnly: true
    }, {
        text: "PRINT / CETAK",
        xtype: "button",
        margin: "0 0 20 0",
        iconCls: "x-fa fa-print",
        scale: "large",
        iconAlign: "right",
        ui: "soft-blue",
        handler: "onCetak"
    }],
    onSetData: function (f) {
        var h = this,
            g = h.down("[reference=kodeantrian]"),
            b = h.down("[reference=noantrian]"),
            a = h.down("[reference=namapasien]"),
            e = h.down("[reference=politujuan]"),
            d = h.down("[reference=dokter]");
        h.getViewModel().set("recordantrian", f);
        g.setValue("");
        b.setValue("");
        a.setValue("");
        e.setValue("");
        d.setValue("");
        g.setValue(f.kodebooking);
        b.setValue(f.nomorantrean);
        a.setValue(f.nama);
        e.setValue(f.namapoli);
        d.setValue(f.namadokter)
    }
});
Ext.define("antrian.onsite.V2.Registrasi.BuktiController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-onsite-v2-registrasi-bukti",
    onCetak: function (b) {
        var d = this,
            a = d.getView(),
            e = a.getViewModel().get("recordantrian");
        a.cetak({
            TITLE: "Cetak Antrian",
            NAME: "plugins.regonline.CetakKarcisAntrian",
            TYPE: "Word",
            EXT: "docx",
            PARAMETER: {
                PNOMOR: e.kodebooking
            },
            REQUEST_FOR_PRINT: true,
            PRINT_NAME: "CetakAntrian"
        });
        a.setLoading(false);
        d.onProgressClick(b)
    },
    onProgressClick: function (d) {
        var f = this,
            a = f.getView(),
            b = 0,
            e, g;
        Ext.MessageBox.show({
            title: "Sedang Proses",
            msg: "Proses Cetak...",
            progressText: "Initializing...",
            width: 300,
            progress: true,
            closable: false,
            animateTarget: d
        });
        e = function () {
            f.timer = null;
            ++b;
            if (b === 12 || !Ext.MessageBox.isVisible()) {
                Ext.MessageBox.hide();
                a.fireEvent("success")
            } else {
                g = b / 11;
                Ext.MessageBox.updateProgress(g, Math.round(100 * g) + "% completed");
                f.timer = Ext.defer(e, 500)
            }
        };
        f.timer = Ext.defer(e, 500)
    }
});
Ext.define("antrian.onsite.V2.Registrasi.Detail", {
    extend: "com.Form",
    alias: "widget.antrian-onsite-v2-registrasi-detail",
    model: "antrian.reservasi.Model",
    layout: {
        type: "vbox",
        align: "stretch"
    },
    bodyPadding: 30,
    fieldDefaults: {
        fieldStyle: "font-size:16px;color:teal;font-style:italic",
        labelStyle: "font-size:16px;color:teal;font-style:italic"
    },
    items: [{
        style: "padding:10px;font-size:20px;border-bottom:1px #DDD solid;text-left:center;font-style:italic;color:teal;background-color:#A5C8D1;",
        bodyStyle: "background-color:transparent;color:teal;font-size:20px;",
        html: "IDENTITAS PASIEN"
    }, {
        name: "TANGGALKUNJUNGAN",
        reference: "tanggalkunjungan",
        allowBlank: false,
        xtype: "datefield",
        format: "Y-m-d",
        hidden: true
    }, {
        xtype: "hiddenfield",
        name: "POS_ANTRIAN",
        reference: "posantrian",
        allowBlank: false
    }, {
        xtype: "textfield",
        name: "NORM",
        reference: "norm",
        fieldLabel: "No. Rekam Medis",
        allowBlank: false,
        height: 40,
        labelWidth: 150,
        margin: "30 0 20 0",
        readOnly: true
    }, {
        xtype: "textfield",
        name: "NAMA",
        reference: "norm",
        fieldLabel: "Nama",
        allowBlank: false,
        height: 40,
        labelWidth: 150,
        margin: "0 0 20 0",
        readOnly: true
    }, {
        xtype: "fieldcontainer",
        fieldLabel: "Tempat / Tgl.Lahir",
        labelWidth: 150,
        labelStyle: "font-size:16px;color:teal;font-style:italic",
        layout: "hbox",
        margin: "0 0 20 0",
        combineErrors: true,
        defaultType: "textfield",
        defaults: {
            hideLabel: "true"
        },
        items: [{
            name: "TEMPAT_LAHIR",
            reference: "norm",
            allowBlank: false,
            fieldStyle: "font-size:16px;color:teal;font-style:italic",
            height: 40,
            flex: 3,
            margin: "0 10 0 0"
        }, {
            name: "TANGGAL_LAHIR",
            reference: "norm",
            xtype: "datefield",
            format: "Y-m-d",
            allowBlank: false,
            fieldStyle: "font-size:16px;color:teal;font-style:italic",
            height: 40,
            flex: 2,
            readOnly: true
        }]
    }, {
        xtype: "textfield",
        name: "NIK",
        reference: "nik",
        fieldLabel: "NIK",
        allowBlank: false,
        height: 40,
        labelWidth: 150,
        margin: "0 0 20 0"
    }, {
        xtype: "textfield",
        name: "CONTACT",
        reference: "kontak",
        fieldLabel: "Kontak Pasien",
        height: 40,
        labelWidth: 150,
        margin: "0 0 20 0"
    }, {
        xtype: "container",
        style: "background-color:#A5C8D1;",
        flex: 1
    }, {
        style: "padding:10px;font-size:14px;border-top:1px #DDD solid;text-left:center;font-style:italic;color:red;background-color:#A5C8D1;",
        bodyStyle: "background-color:transparent;color:red",
        html: "Ket : Sebelum Ambil ANTRIAN, Pastikan data yang tampil sudah sesuai"
    }],
    onLoadRecord: function (b, a) {},
    getNik: function () {
        var b = this,
            a = b.down("[reference=nik]").getValue();
        return a
    }
});
Ext.define("antrian.onsite.V2.Registrasi.Form", {
    extend: "com.Form",
    alias: "widget.antrian-onsite-v2-registrasi-form",
    viewModel: {
        data: {
            isPilihPoli: true,
            isSimpanAntrian: true,
            isSelectJaminan: true,
            isAntrianJkn: true
        }
    },
    layout: {
        type: "vbox",
        align: "stretch"
    },
    bodyPadding: 20,
    model: "antrian.reservasi.Model",
    fieldDefaults: {
        labelAlign: "top",
        fieldStyle: "font-size:18px;font-weight:bold;background:powderblue;color:#000",
        labelStyle: "font-size:16px"
    },
    items: [{
        name: "CARABAYAR",
        xtype: "antrian-combo-carabayar",
        reference: "jeniscarabayar",
        emptyText: "[ Filter Penjamin / Cara Bayar]",
        allowBlank: false,
        height: 54,
        margin: "0 0 20 0",
        firstLoad: true,
        params: {
            STATUS: 1
        },
        listeners: {
            select: "onSelectCaraBayar",
            blur: "onBlurCaraBayar"
        }
    }, {
        xtype: "fieldcontainer",
        layout: "hbox",
        defaultType: "textfield",
        items: [{
            name: "NO_KARTU_BPJS",
            reference: "nokartu",
            emptyText: "No. Kartu Jaminan JKN / BPJS",
            fieldStyle: "font-size:18px;font-weight:bold;",
            height: 54,
            flex: 1,
            margin: "0 10 18 0",
            bind: {
                disabled: "{isAntrianJkn}"
            }
        }, {
            name: "NO_REF_BPJS",
            reference: "norujukan",
            emptyText: "Masukkan No. Rujukan",
            format: "Y-m-d",
            fieldStyle: "font-size:18px;font-weight:bold;",
            margin: "0 10 18 0",
            height: 54,
            flex: 1,
            disabled: true
        }, {
            text: "Cari Rujukan",
            xtype: "button",
            margin: "0 5 18 0",
            iconCls: "x-fa fa-search",
            reference: "btncarirujukan",
            iconAlign: "right",
            height: 54,
            ui: "soft-blue",
            handler: "onCariRujukan",
            bind: {
                disabled: "{isAntrianJkn}"
            }
        }, {
            text: "Rujukan Lainnya",
            xtype: "button",
            margin: "0 0 18 0",
            iconCls: "x-fa fa-barcode",
            reference: "btnrujukanlainnya",
            iconAlign: "right",
            height: 54,
            ui: "soft-cyan",
            handler: "onRujukanLainnya",
            bind: {
                disabled: "{isAntrianJkn}"
            }
        }, {
            text: "Kontrol Pasca Inap",
            xtype: "button",
            margin: "0 0 18 0",
            iconCls: "x-fa fa-list",
            reference: "btnkontrol",
            iconAlign: "right",
            height: 54,
            handler: "onSuratKontrol",
            bind: {
                disabled: "{isAntrianJkn}"
            }
        }]
    }, {
        xtype: "antrian-combo-poli",
        name: "POLI",
        reference: "poli",
        allowBlank: false,
        height: 54,
        margin: "0 0 20 0",
        firstLoad: true,
        params: {
            STATUS: 1,
            ANTRIAN: "-"
        },
        bind: {
            disabled: "{isSelectJaminan}"
        },
        listeners: {
            select: "onSelectPoli",
            blur: "onBlurPoli"
        }
    }, {
        xtype: "antrian-combo-jadwal-dokter",
        name: "DOKTER",
        reference: "dokter",
        allowBlank: false,
        height: 54,
        margin: "0 0 40 0",
        params: {
            STATUS: 1,
            POLI: "-"
        },
        bind: {
            disabled: "{isPilihPoli}"
        },
        listeners: {
            select: "onSelectDokter",
            blur: "onBlurDokter"
        }
    }, {
        text: "Ambil Antrian",
        xtype: "button",
        margin: "0 0 20 0",
        iconCls: "x-fa fa-print",
        reference: "btndoantrian",
        scale: "large",
        iconAlign: "right",
        ui: "soft-green",
        handler: "onAmbilAntrian",
        bind: {
            disabled: "{isSimpanAntrian}"
        }
    }],
    onLoadRecord: function (d, b) {
        var d = this,
            a = d.down("antrian-combo-poli");
        a.params.ANTRIAN = b.get("POS_ANTRIAN");
        a.load()
    },
    onSetDokter: function (a) {
        var d = this,
            b = d.down("antrian-combo-jadwal-dokter");
        b.params.TANGGAL = Ext.Date.format(d.getSysdate(), "Y-m-d");
        b.params.POLI = a.get("REFERENSI").PENJAMIN.RUANGAN_PENJAMIN;
        b.load({
            callback: function (f, e, g) {
                if (g) {
                    if (f.length == 0) {
                        d.notifyMessage("Poliklinik Tersebut belum memiliki jadwal Dokter Hari Ini", "danger-red")
                    }
                } else {
                    d.notifyMessage("Poliklinik Tersebut belum memiliki jadwal Dokter Hari Ini", "danger-red")
                }
            }
        })
    },
    onBeforeGetRecord: function (b, e) {
        var d = b.down("antrian-combo-poli").getSelection(),
            a = b.down("antrian-combo-jadwal-dokter").getSelection(),
            f = b.down("[reference=norujukan]").getValue();
        e.REFERENSI = {
            DESKRIPSI: {
                KETERANGAN: "Ambil Antrian"
            }
        };
        e.NO_REF_BPJS = f;
        e.POLI_BPJS = d.data.REFERENSI.PENJAMIN ? d.data.REFERENSI.PENJAMIN.RUANGAN_PENJAMIN : "0";
        e.JAM_PRAKTEK = a.data.JAM
    },
    getNokartu: function () {
        var b = this,
            a = b.down("[reference=nokartu]").getValue();
        return a
    },
    onResetForm: function () {
        var d = this,
            a = d.down("antrian-combo-poli"),
            b = d.down("antrian-combo-poli"),
            e = d.down("[reference=norujukan]");
        e.setValue("");
        b.removeDataStore();
        delete a.getStore().queryParams.RUANGAN_PENJAMIN;
        a.load();
        a.select(null)
    },
    isNullMappingPoli: function (a, e) {
        var d = this,
            b = d.down("antrian-combo-poli");
        norujukan = d.down("[reference=norujukan]");
        b.select(null);
        b.params.RUANGAN_PENJAMIN = a.poliRujukan.kode;
        b.load(function (g, f, h) {
            if (Ext.isArray(g)) {
                if (g.length > 0) {
                    b.setSelection(g[0]);
                    norujukan.setValue(a.noKunjungan)
                }
                e(g.length == 0)
            }
        })
    }
});
Ext.define("antrian.onsite.V2.Registrasi.Info", {
    extend: "com.Form",
    alias: "widget.antrian-onsite-v2-registrasi-info",
    controller: "antrian-onsite-v2-registrasi-info",
    layout: {
        type: "vbox",
        align: "stretch"
    },
    bodyPadding: 50,
    fieldDefaults: {
        fieldStyle: "font-size:16px;color:teal;font-style:italic",
        labelStyle: "font-size:16px;color:teal;font-style:italic"
    },
    items: [{
        style: "padding:10px;font-size:20px;border-bottom:1px #DDD solid;text-left:center;font-style:italic;color:teal;background-color:#A5C8D1;",
        bodyStyle: "background-color:transparent;color:teal;font-size:20px;text-align:center",
        html: "INFORMASI DATA RUJUKAN",
        margin: "0 0 20 0"
    }, {
        xtype: "textfield",
        reference: "norujukan",
        fieldLabel: "No. Rujukan",
        height: 40,
        labelWidth: 200,
        margin: "0 0 20 0",
        readOnly: true
    }, {
        xtype: "textfield",
        reference: "faskesasal",
        fieldLabel: "Faskes Asal Rujukan",
        height: 40,
        labelWidth: 200,
        margin: "0 0 20 0",
        readOnly: true
    }, {
        xtype: "textfield",
        reference: "politujuan",
        fieldLabel: "Poliklinik Tujuan",
        height: 40,
        labelWidth: 200,
        margin: "0 0 20 0",
        readOnly: true
    }, {
        text: "LANJUT DAFTAR",
        xtype: "button",
        margin: "0 0 20 0",
        iconCls: "x-fa fa-arrow-right",
        scale: "large",
        iconAlign: "right",
        ui: "soft-blue",
        handler: "onLanjutAntrian"
    }],
    onSetData: function (b) {
        var f = this,
            e = f.down("[reference=norujukan]"),
            d = f.down("[reference=faskesasal]"),
            a = f.down("[reference=politujuan]");
        e.setValue("");
        d.setValue("");
        a.setValue("");
        e.setValue(b.noKunjungan);
        d.setValue(b.provPerujuk.nama);
        a.setValue(b.poliRujukan.nama + " (" + b.poliRujukan.kode + ")")
    }
});
Ext.define("antrian.onsite.V2.Registrasi.InfoController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-onsite-v2-registrasi-info",
    onLanjutAntrian: function (b) {
        var d = this,
            a = d.getView();
        a.fireEvent("success")
    }
});
Ext.define("antrian.onsite.V2.Registrasi.Kontrol", {
    extend: "com.Grid",
    xtype: "antrian-onsite-v2-registrasi-kontrol",
    controller: "antrian-onsite-v2-registrasi-kontrol",
    viewModel: {
        stores: {
            store: {
                type: "plugins-bpjs-rujukan-store"
            }
        }
    },
    bind: {
        store: "{store}"
    },
    columns: [{
        text: "Kunjungan",
        flex: 1,
        columns: [{
            text: "No. Kunjungan",
            flex: 1,
            dataIndex: "noKunjungan",
            sortable: false,
            align: "center"
        }, {
            xtype: "datecolumn",
            format: "d-m-Y",
            flex: 1,
            text: "Tgl. Kunjungan",
            dataIndex: "tglKunjungan",
            sortable: false,
            align: "center"
        }]
    }, {
        text: "Peserta",
        flex: 1,
        columns: [{
            xtype: "templatecolumn",
            text: "No. Kartu",
            flex: 1,
            sortable: false,
            align: "center",
            tpl: "{peserta.noKartu}"
        }, {
            xtype: "templatecolumn",
            text: "No. RM",
            flex: 1,
            sortable: false,
            align: "center",
            tpl: "{peserta.mr.noMR}"
        }, {
            xtype: "templatecolumn",
            text: "Nama",
            flex: 1,
            sortable: false,
            tpl: "{peserta.nama}"
        }]
    }, {
        text: "Diagnosa",
        flex: 1,
        columns: [{
            xtype: "templatecolumn",
            text: "Kode",
            sortable: false,
            align: "center",
            tpl: "{diagnosa.kode}"
        }, {
            xtype: "templatecolumn",
            text: "Nama",
            sortable: false,
            tpl: "{diagnosa.nama}"
        }]
    }, {
        text: "Poli Rujukan",
        flex: 1,
        columns: [{
            xtype: "templatecolumn",
            text: "Kode",
            sortable: false,
            flex: 1,
            align: "center",
            tpl: "{poliRujukan.kode}"
        }, {
            xtype: "templatecolumn",
            text: "Nama",
            sortable: false,
            flex: 1,
            tpl: "{poliRujukan.nama}"
        }]
    }, {
        text: "Perujuk",
        flex: 1,
        columns: [{
            xtype: "templatecolumn",
            text: "Kode",
            sortable: false,
            align: "center",
            flex: 1,
            tpl: "{provPerujuk.kode}"
        }, {
            xtype: "templatecolumn",
            text: "Nama",
            flex: 1,
            sortable: false,
            tpl: "{provPerujuk.nama}"
        }]
    }, {
        xtype: "widgetcolumn",
        align: "center",
        text: "Pilih Surat Kontrol",
        widget: {
            xtype: "fieldcontainer",
            defaults: {
                padding: 2
            },
            items: [{
                xtype: "button",
                text: "Pilih & Daftar",
                ui: "soft-green",
                margin: "0 1 0 0",
                tooltip: "Gunakan Data Surat Kontrol Ini",
                handler: "onPilihKontrol"
            }]
        }
    }],
    onSetData: function (a) {
        var e = this,
            d = e.getViewModel().get("store");
        for (i = 0; i < a.length; i++) {
            var b = Ext.create("plugins.bpjs.rujukan.Model", a[i]);
            d.add(b)
        }
    }
});
Ext.define("antrian.onsite.V2.Registrasi.KontrolController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-onsite-v2-registrasi-kontrol",
    onPilihRujukan: function (e) {
        var b = this,
            a = b.getView(),
            d = e.ownerCt.getWidgetRecord();
        a.fireEvent("success", d)
    }
});
Ext.define("antrian.onsite.V2.Registrasi.Rujukan", {
    extend: "com.Grid",
    xtype: "antrian-onsite-v2-registrasi-rujukan",
    controller: "antrian-onsite-v2-registrasi-rujukan",
    viewModel: {
        stores: {
            store: {
                type: "plugins-bpjs-rujukan-store"
            }
        }
    },
    bind: {
        store: "{store}"
    },
    columns: [{
        text: "Kunjungan",
        flex: 1,
        columns: [{
            text: "No. Kunjungan",
            flex: 1,
            dataIndex: "noKunjungan",
            sortable: false,
            align: "center"
        }, {
            xtype: "datecolumn",
            format: "d-m-Y",
            flex: 1,
            text: "Tgl. Kunjungan",
            dataIndex: "tglKunjungan",
            sortable: false,
            align: "center"
        }]
    }, {
        text: "Peserta",
        flex: 1,
        columns: [{
            xtype: "templatecolumn",
            text: "No. Kartu",
            flex: 1,
            sortable: false,
            align: "center",
            tpl: "{peserta.noKartu}"
        }, {
            xtype: "templatecolumn",
            text: "No. RM",
            flex: 1,
            sortable: false,
            align: "center",
            tpl: "{peserta.mr.noMR}"
        }, {
            xtype: "templatecolumn",
            text: "Nama",
            flex: 1,
            sortable: false,
            tpl: "{peserta.nama}"
        }]
    }, {
        text: "Diagnosa",
        flex: 1,
        columns: [{
            xtype: "templatecolumn",
            text: "Kode",
            sortable: false,
            align: "center",
            tpl: "{diagnosa.kode}"
        }, {
            xtype: "templatecolumn",
            text: "Nama",
            sortable: false,
            tpl: "{diagnosa.nama}"
        }]
    }, {
        text: "Poli Rujukan",
        flex: 1,
        columns: [{
            xtype: "templatecolumn",
            text: "Kode",
            sortable: false,
            flex: 1,
            align: "center",
            tpl: "{poliRujukan.kode}"
        }, {
            xtype: "templatecolumn",
            text: "Nama",
            sortable: false,
            flex: 1,
            tpl: "{poliRujukan.nama}"
        }]
    }, {
        text: "Perujuk",
        flex: 1,
        columns: [{
            xtype: "templatecolumn",
            text: "Kode",
            sortable: false,
            align: "center",
            flex: 1,
            tpl: "{provPerujuk.kode}"
        }, {
            xtype: "templatecolumn",
            text: "Nama",
            flex: 1,
            sortable: false,
            tpl: "{provPerujuk.nama}"
        }]
    }, {
        xtype: "widgetcolumn",
        align: "center",
        text: "Pilih Rujukan",
        widget: {
            xtype: "fieldcontainer",
            defaults: {
                padding: 2
            },
            items: [{
                xtype: "button",
                text: "Pilih & Daftar",
                ui: "soft-green",
                margin: "0 1 0 0",
                tooltip: "Pilih Data Rujukan Ini",
                handler: "onPilihRujukan"
            }]
        }
    }],
    onSetData: function (a) {
        var e = this,
            d = e.getViewModel().get("store");
        for (i = 0; i < a.length; i++) {
            var b = Ext.create("plugins.bpjs.rujukan.Model", a[i]);
            d.add(b)
        }
    }
});
Ext.define("antrian.onsite.V2.Registrasi.RujukanController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-onsite-v2-registrasi-rujukan",
    onPilihRujukan: function (e) {
        var b = this,
            a = b.getView(),
            d = e.ownerCt.getWidgetRecord();
        a.fireEvent("success", d)
    }
});
Ext.define("antrian.onsite.V2.Registrasi.Workspace", {
    extend: "com.Form",
    alias: "widget.antrian-onsite-v2-registrasi-workspace",
    controller: "antrian-onsite-v2-registrasi-workspace",
    layout: {
        type: "hbox",
        align: "stretch"
    },
    bodyPadding: 0,
    items: [{
        xtype: "antrian-onsite-v2-registrasi-detail",
        bodyStyle: "background-color:rgb(165, 200, 209)",
        reference: "formdetailantrian",
        margin: "0 10 0 0",
        flex: 2
    }, {
        xtype: "antrian-onsite-v2-registrasi-form",
        reference: "formantrian",
        margin: "0 0 0 10",
        flex: 3
    }],
    onLoadRecord: function (a) {
        var e = this,
            b = e.down("antrian-onsite-v2-registrasi-detail"),
            d = e.down("antrian-onsite-v2-registrasi-form");
        b.createRecord(a);
        d.createRecord(a)
    }
});
Ext.define("antrian.onsite.V2.Registrasi.WorkspaceController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-onsite-v2-registrasi-workspace",
    recordRujukan: undefined,
    init: function () {
        var b = this,
            a = b.getView();
        b.integrasi = a.app.xitr("ID", 1);
        if (b.integrasi) {
            b.service = Ext.create(b.integrasi.NAMA_KLAS, {})
        }
        b.serviceantrian = Ext.create("antrian.Service", {})
    },
    showNotif: function (g, e, d) {
        var b = this,
            a = Ext.create("Ext.window.MessageBox", {
                ui: d ? d : "window-red",
                title: g,
                header: {
                    padding: 5
                }
            }),
            f = {
                msg: e,
                buttons: Ext.MessageBox.OK,
                scope: b.scope,
                icon: Ext.Msg.ERROR
            };
        a.show(f)
    },
    isValidNik: function (a) {
        if (a.length != 16) {
            return "NIK Harus 16 Digit / karakter"
        }
        if (a == "") {
            return "NIK Harus Di Terisi"
        }
        return false
    },
    isValidNoKartu: function (a) {
        if (a.length != 13) {
            return "No.Kartu Jaminan JKN / BPJS Harus 13 Digit / karakter"
        }
        if (a == "") {
            return "No.Kartu Jaminan JKN / BPJS Harus Di Terisi"
        }
        return false
    },
    onSelectCaraBayar: function (d, a) {
        var e = this,
            b = e.getReferences();
        b.formantrian.getViewModel().set("isAntrianJkn", true);
        if (a) {
            if (a.get("ID") == 2) {
                b.formantrian.getViewModel().set("isAntrianJkn", false);
                b.formantrian.focus("NO_KARTU_BPJS");
                b.formantrian.getViewModel().set("isSelectJaminan", true);
                b.formantrian.getViewModel().set("isPilihPoli", true)
            } else {
                b.formantrian.onResetForm();
                b.formantrian.focus("POLI");
                b.formantrian.getViewModel().set("isPilihPoli", true);
                b.formantrian.getViewModel().set("isSelectJaminan", false)
            }
        } else {
            b.formantrian.onResetForm();
            b.formantrian.getViewModel().set("isPilihPoli", true);
            b.formantrian.getViewModel().set("isSelectJaminan", true)
        }
    },
    onBlurCaraBayar: function (a) {
        if (a.getSelection() == null) {
            this.onSelectPoli(a, null)
        }
    },
    onSelectPoli: function (d, a) {
        var e = this,
            b = e.getReferences();
        b.formantrian.getViewModel().set("isPilihPoli", true);
        if (a) {
            if (a.get("REFERENSI")) {
                if (a.get("REFERENSI").PENJAMIN) {
                    if (a.get("REFERENSI").PENJAMIN.BPJS) {
                        b.formantrian.getViewModel().set("isPilihPoli", false);
                        b.formantrian.onSetDokter(a)
                    } else {
                        e.getView().notifyMessage("Poliklinik tersebut belum memiliki penjamin", "danger-red")
                    }
                } else {
                    e.getView().notifyMessage("Poliklinik tersebut belum memiliki penjamin", "danger-red")
                }
            } else {
                e.getView().notifyMessage("Poliklinik tersebut belum memiliki penjamin", "danger-red")
            }
        }
    },
    onBlurPoli: function (a) {
        if (a.getSelection() == null) {
            this.onSelectPoli(a, null);
            a.setSelection(null)
        }
    },
    onSelectDokter: function (d, a) {
        var e = this,
            b = e.getReferences();
        b.formantrian.getViewModel().set("isSimpanAntrian", true);
        if (a) {
            b.formantrian.getViewModel().set("isSimpanAntrian", false)
        }
    },
    onBlurDokter: function (a) {
        if (a.getSelection() == null) {
            this.onSelectDokter(a, null)
        }
    },
    onCariRujukan: function (h) {
        var j = this,
            f = j.getReferences(),
            a = j.getView(),
            g = f.formdetailantrian.getNik(),
            e = f.formantrian.getNokartu(),
            d = j.isValidNik(g),
            b = j.isValidNoKartu(e);
        if (d) {
            j.getView().notifyMessage(d, "danger-red");
            return false
        }
        if (b) {
            j.getView().notifyMessage(b, "danger-red");
            return false
        }
        if (j.service) {
            j.recordRujukan = undefined;
            a.setLoading(true);
            j.service.cariRujukanDgnNoKartuBPJS(e, function (m, l) {
                if (m) {
                    var k = l.data.rujukan;
                    j.onSetDataRujukan(h, k, g)
                }
                a.setLoading(false)
            })
        }
    },
    onRujukanLainnya: function (h) {
        var j = this,
            f = j.getReferences(),
            a = j.getView(),
            g = f.formdetailantrian.getNik(),
            e = f.formantrian.getNokartu(),
            d = j.isValidNik(g),
            b = j.isValidNoKartu(e);
        if (d) {
            j.getView().notifyMessage(d, "danger-red");
            return false
        }
        if (b) {
            j.getView().notifyMessage(b, "danger-red");
            return false
        }
        if (j.service) {
            j.recordRujukan = undefined;
            a.setLoading(true);
            j.cariRujukanDgnNoKartuBPJS(e, 2, function (m, l) {
                if (m) {
                    if (l.success) {
                        var k = l.data.rujukan;
                        if (l.data.rujukan.length > 1) {
                            j.onSetListDataRujukan(h, k, g)
                        } else {
                            j.onSetDataRujukan(h, k[0], g)
                        }
                    } else {
                        j.cariRujukanDgnNoKartuBPJS(e, 1, function (p, o) {
                            if (p) {
                                if (o.success) {
                                    if (o.data.rujukan.length > 1) {
                                        var n = o.data.rujukan[0];
                                        j.onSetDataRujukan(h, n, g)
                                    } else {
                                        var n = o.data.rujukan[0];
                                        j.onSetDataRujukan(h, n, g)
                                    }
                                } else {
                                    j.showNotif("Informasi", "Rujukan Tidak Ditemukan Dengan No.Kartu Jaminan JKN / BPJS Tersebut Baik Dari Faskes 1 Ataupun 2", "window-red");
                                    a.setLoading(false)
                                }
                            } else {
                                a.setLoading(false)
                            }
                        })
                    }
                    a.setLoading(false)
                }
                a.setLoading(false)
            })
        }
    },
    onSuratKontrol: function (j) {
        var e = this,
            g = e.getReferences(),
            h = e.getView(),
            f = g.formdetailantrian.getNik(),
            k = g.formantrian.getNokartu(),
            b = e.isValidNik(f),
            a = e.isValidNoKartu(k);
        if (b) {
            e.getView().notifyMessage(b, "danger-red");
            return false
        }
        if (a) {
            e.getView().notifyMessage(a, "danger-red");
            return false
        }
        if (e.service) {
            e.recordRujukan = undefined;
            var d = e.getView().getSysdate();
            h.setLoading(true);
            e.cariRujukanDgnNoKartuBPJS(k, d, function (n, m) {
                if (n) {
                    if (m.success) {
                        var l = m.data;
                        e.onSetListDataKontrol(j, l, f)
                    } else {
                        e.showNotif("Informasi", "Surat Kontrol Tidak Ditemukan Dengan No.Kartu Jaminan JKN / BPJS Tersebut", "window-red");
                        h.setLoading(false)
                    }
                }
                h.setLoading(false)
            })
        }
    },
    cariRujukanDgnNoKartuBPJS: function (d, a, e) {
        var b = this;
        b.serviceantrian.request("plugins/getListRujukanKartu?noKartu=" + d + "&faskes=" + a, "GET", "", e)
    },
    cariKontrolNoKartuBPJS: function (d, a, e) {
        var b = this;
        b.serviceantrian.request("plugins/getListSuratKontrol?nokartu=" + d + "&tanggal=" + a, "GET", "", e)
    },
    onSetListDataRujukan: function (f, d, e) {
        var g = this,
            b = g.getReferences(),
            a = g.getView();
        a.setLoading(true);
        a.openDialog("", true, "100%", "100%", {
            title: "List Data Rujukan Pasien <i><b>( Silahkan Pilih Salah Satu Rujukan Yang Ingin Digunakan )</b></i>",
            xtype: "antrian-onsite-v2-registrasi-rujukan",
            ui: "panel-cyan",
            hideHeaders: false,
            scrollable: true,
            showCloseButton: true
        }, function (k, j) {
            var h = j.down("antrian-onsite-v2-registrasi-rujukan");
            h.onSetData(d);
            h.on("success", function (l) {
                g.onSetDataRujukan(f, l.data, e);
                j.close()
            })
        }, this, true, true)
    },
    onSetListDataKontrol: function (f, d, e) {
        var g = this,
            b = g.getReferences(),
            a = g.getView();
        a.setLoading(true);
        a.openDialog("", true, "100%", "100%", {
            title: "List Data Surat Kontrol Pasien <i><b>( Silahkan Pilih Salah Satu Data SUrat Kontrol Yang Ingin Digunakan )</b></i>",
            xtype: "antrian-onsite-v2-registrasi-kontrol",
            ui: "panel-cyan",
            hideHeaders: false,
            scrollable: true,
            showCloseButton: true
        }, function (k, j) {
            var h = j.down("antrian-onsite-v2-registrasi-kontrol");
            h.onSetData(d);
            h.on("success", function (l) {
                console.log(l);
                g.onSetDataRujukan(f, l.data, e);
                j.close()
            })
        }, this, true, true)
    },
    onSetDataRujukan: function (f, d, e) {
        var g = this,
            b = g.getReferences(),
            a = g.getView();
        if (d.peserta.nik != e) {
            g.showNotif("Informasi", "NIK tidak Sesuai Dengan No.Kartu Jaminan JKN / BPJS", "window-red");
            a.setLoading(false);
            return false
        }
        g.recordRujukan = d;
        a.openDialog("", true, 700, 470, {
            title: "Data Rujukan Pasien",
            xtype: "antrian-onsite-v2-registrasi-info",
            ui: "panel-cyan",
            hideHeaders: false,
            scrollable: true,
            showCloseButton: true
        }, function (k, j) {
            var h = j.down("antrian-onsite-v2-registrasi-info");
            h.onSetData(d);
            h.on("success", function (l) {
                a.setLoading(true);
                b.formantrian.isNullMappingPoli(g.recordRujukan, function (m) {
                    if (m) {
                        g.showNotif("Error", "Poli tujuan belum di mapping, silahkan hubungi petugas registrasi", "window-red");
                        a.setLoading(false)
                    } else {
                        b.formantrian.getViewModel().set("isSelectJaminan", false);
                        b.formantrian.getViewModel().set("isPilihPoli", false);
                        j.close();
                        a.setLoading(false)
                    }
                })
            })
        }, this, true, true)
    },
    onAmbilAntrian: function (h) {
        var g = this,
            d = g.getReferences(),
            b = g.getView(),
            a = d.formdetailantrian.getRecord(),
            f = d.formantrian.getRecord();
        record = Ext.create("antrian.reservasi.Model", {});
        if (f) {
            b.setLoading(true);
            if (f.get("CARABAYAR") == 2) {
                if (f.get("NO_KARTU_BPJS") == "") {
                    g.getView().notifyMessage("No.Kartu Jaminan JKN / BPJS Harus Di Terisi", "danger-red");
                    d.formantrian.focus("NO_KARTU_BPJS");
                    b.setLoading(false);
                    return false
                }
                if (f.get("NO_REF_BPJS") == "") {
                    g.getView().notifyMessage("No.Rujukan Jaminan JKN / BPJS Harus Di Terisi", "danger-red");
                    d.formantrian.focus("NO_REF_BPJS");
                    b.setLoading(false);
                    return false
                }
            }
            record.set("NORM", a.get("NORM"));
            record.set("NAMA", a.get("NAMA"));
            record.set("NIK", a.get("NIK"));
            record.set("CONTACT", a.get("CONTACT"));
            record.set("TEMPAT_LAHIR", a.get("TEMPAT_LAHIR"));
            record.set("TANGGAL_LAHIR", a.get("TANGGAL_LAHIR"));
            record.set("JENIS_APLIKASI", a.get("JENIS_APLIKASI"));
            record.set("TANGGALKUNJUNGAN", a.get("TANGGALKUNJUNGAN"));
            record.set("ALAMAT", a.get("ALAMAT"));
            record.set("JK", a.get("JENIS_KELAMIN"));
            record.set("POS_ANTRIAN", a.get("POS_ANTRIAN"));
            record.set("POLI", f.get("JENIS_KELAMIN"));
            record.set("CARABAYAR", f.get("CARABAYAR"));
            record.set("NO_KARTU_BPJS", f.get("NO_KARTU_BPJS"));
            record.set("NO_REF_BPJS", f.get("NO_REF_BPJS"));
            record.set("POLI", f.get("POLI"));
            record.set("DOKTER", f.get("DOKTER"));
            record.set("JAM_PRAKTEK", f.get("JAM_PRAKTEK"));
            record.set("POLI_BPJS", f.get("POLI_BPJS"));
            record.set("JENIS", f.get("JENIS"));
            record.showError = true;
            record.save({
                callback: function (j, k, e) {
                    var l = JSON.parse(k._response.responseText);
                    if (e) {
                        b.fireEvent("success", l.response.pos);
                        b.setLoading(false);
                        g.onSetDataAntrian(h, l.response)
                    } else {
                        b.notifyMessage(l.metadata.message, "danger-red");
                        b.setLoading(false)
                    }
                }
            })
        }
    },
    onSetDataAntrian: function (d, b) {
        var e = this,
            a = e.getView();
        var f = Ext.create("Ext.Window", {
            header: false,
            modal: true,
            constrain: true,
            closable: true,
            width: 700,
            height: 600,
            layout: "fit",
            items: {
                title: "Bukti Registrasi Antrian",
                xtype: "antrian-onsite-v2-registrasi-bukti",
                ui: "panel-cyan",
                hideHeaders: false,
                scrollable: true,
                showCloseButton: true,
                listeners: {
                    cetakantrian: "onCetak"
                }
            }
        });
        f.on("show", function () {
            var g = f.down("antrian-onsite-v2-registrasi-bukti");
            g.onSetData(b);
            g.on("success", function (h) {
                f.close()
            })
        });
        f.show()
    }
});
Ext.define("antrian.onsite.V2.Workspace", {
    extend: "com.Form",
    xtype: "antrian-onsite-v2-workspace",
    controller: "antrian-onsite-v2-workspace",
    viewModel: {
        data: {
            ruangans: [],
            refreshTime: 0,
            instansi: undefined,
            infoTeks: "",
            tglNow: "-",
            statusWebsocket: "Disconnect",
            posAntrian: ""
        },
        stores: {
            store: {
                type: "instansi-store"
            }
        }
    },
    audio: {
        integrasi: undefined,
        service: undefined
    },
    layout: {
        type: "vbox",
        align: "stretch"
    },
    defaults: {
        border: false
    },
    idx: 0,
    bodyStyle: "background-color:#aa8a51",
    initComponent: function () {
        if (window.location.protocol == "http:") {
            var d = "ws"
        } else {
            var d = "wss"
        }
        var b = this;
        var a = Ext.create("Ext.ux.WebSocket", {
            url: "ws://" + window.location.hostname + ":8899",
            listeners: {
                open: function (e) {
                    b.getViewModel().set("statusWebsocket", "Connected")
                },
                close: function (e) {
                    b.getViewModel().set("statusWebsocket", "Disonnected Socket")
                }
            }
        });
        b.items = [{
            layout: {
                type: "hbox",
                align: "middle"
            },
            border: false,
            height: 50,
            bodyStyle: "padding-left:10px;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#356aa0), color-stop(100%,#356aa0));",
            items: [{
                xtype: "image",
                bind: {
                    src: "classic/resources/images/{instansi}.png"
                },
                id: "idImage",
                width: 40,
                border: false,
                bodyStyle: "background-color:transparent;"
            }, {
                flex: 1,
                bind: {
                    data: {
                        items: "{store.data.items}"
                    }
                },
                tpl: new Ext.XTemplate('<tpl for="items">', "{data.REFERENSI.PPK.NAMA}", "</tpl>"),
                border: false,
                bodyStyle: "background-color:transparent; font-size: 18px; color: white; "
            }, {
                xtype: "label",
                bind: {
                    html: "{posAntrian} | {tglNow}"
                },
                flex: 1,
                border: false,
                style: "background-color:transparent; font-size: 20px; color: white;text-align:right "
            }]
        }, {
            flex: 1,
            layout: {
                type: "hbox",
                align: "stretch"
            },
            defaults: {
                flex: 1,
                margin: "0 1 0 1"
            },
            border: false,
            reference: "informasi",
            items: [{
                flex: 2,
                border: false,
                layout: {
                    type: "vbox",
                    align: "stretch"
                },
                defaults: {
                    bodyStyle: "background-color:#D8F1EC"
                },
                items: [{
                    border: true,
                    style: "padding:15px;background-color:#A5C8D1;border-bottom:1px #DDD solid",
                    bodyStyle: "background-color:transparent",
                    html: '<iframe width="100%" height="300px" src="classic/resources/images/banner-antrian/video.mp4" frameborder="0" allow="accelerometer loop="true" autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
                }, {
                    xtype: "container",
                    style: "background-color:#A5C8D1;",
                    flex: 1
                }, {
                    style: "padding:10px;font-size:14px;border-top:1px #DDD solid;text-left:center;font-style:italic;color:#434343;background-color:#A5C8D1;",
                    bodyStyle: "background-color:transparent",
                    bind: {
                        html: "Status : {statusWebsocket}"
                    }
                }]
            }, {
                flex: 5,
                border: false,
                xtype: "antrian-onsite-v2-form"
            }]
        }, {
            layout: {
                type: "hbox",
                align: "middle"
            },
            height: 30,
            border: false,
            bodyStyle: "background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#356aa0), color-stop(100%,#356aa0));",
            items: [{
                xtype: "displayfield",
                flex: 1,
                fieldStyle: "background-color:transparent;font-size: 14px;  margin-left: 10px;color: white;",
                border: false,
                bind: {
                    value: "<marquee>{infoTeks}</marquee>"
                }
            }]
        }];
        b.callParent(arguments)
    },
    onLoadRecord: function (b) {
        var e = this,
            d = e.down("antrian-onsite-v2-form"),
            a = e.getController();
        e.getViewModel().set("posAntrian", b.get("DESKRIPSI"));
        a.mulai();
        d.onLoadRecord(b)
    },
    onRefreshView: function (e) {
        var d = this,
            a = d.down("antrian-display-view").getStore(),
            b = a.getQueryParams().POS;
        if (b == e) {
            a.reload()
        }
    },
    runLogo: function () {
        if (this.deg == 360) {
            this.deg = 0
        } else {
            this.deg += 5
        }
        Ext.getCmp("idImage").setStyle("-webkit-transform: rotateY(" + this.deg + "deg)")
    }
});
Ext.define("antrian.onsite.V2.WorkspaceController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-onsite-v2-workspace",
    currentRefreshTimeRuangan: 0,
    refreshTime: 0,
    onAfterRender: function () {
        var e = this,
            b = e.getViewModel(),
            a = b.get("store"),
            d = Ext.getStore("instansi");
        if (d) {
            b.set("store", d);
            new Ext.util.DelayedTask(function () {
                b.set("instansi", d.getAt(0).get("PPK"))
            }).delay(1000)
        } else {
            a.doAfterLoad = function (f, h, g, j) {
                if (j) {
                    if (h.length > 0) {
                        b.set("instansi", h[0].get("PPK"))
                    }
                }
            };
            a.load()
        }
    },
    mulai: function (d) {
        var b = this,
            a = b.getViewModel();
        b.currentRefreshTimeRuangan = a.get("refreshTime");
        b.refreshTime = a.get("refreshTime");
        if (b.task == undefined) {
            b.task = {
                run: function () {
                    a.set("tglNow", Ext.Date.format(new Date(), "l, d F Y H:i:s"));
                    if (b.currentRefreshTimeRuangan == 0) {
                        b.currentRefreshTimeRuangan = b.refreshTime
                    }
                    b.currentRefreshTimeRuangan--;
                    a.set("refreshTime", b.currentRefreshTimeRuangan)
                },
                interval: 1000
            };
            Ext.TaskManager.start(b.task)
        }
    },
    destroy: function () {
        var a = this;
        Ext.TaskManager.stop(a.task);
        a.callParent()
    }
});
Ext.define("antrian.onsite.View1", {
    extend: "Ext.view.View",
    alias: "widget.antrian-onsite-view1",
    viewModel: {
        stores: {
            store: {
                type: "antrian-ruangan-store"
            }
        }
    },
    autoScroll: true,
    cls: "laporan-main laporan-dataview",
    bind: {
        store: "{store}"
    },
    itemSelector: "div.thumb-wrap",
    tpl: Ext.create("Ext.XTemplate", '<tpl for=".">', '<div class="thumb-wrap" style="padding:5px">', '<a class="thumb" href="#" style="background-color:darkcyan">', '<div class="thumb-title-container">', '<div class="thumb-title" style="font-size:62px;color:whitesmoke;font-weight: bold;font-variant:all-petite-caps;font-family:monospace;"><strong>{DESKRIPSI}</strong></div>', "</div>", '<div class="thumb-download"></div>', "</a>", "</div>", "</tpl>"),
    load: function () {
        var b = this.getViewModel().get("store");
        if (b) {
            b.removeAll();
            b.load()
        }
    }
});
Ext.define("antrian.onsite.View2", {
    extend: "com.Form",
    alias: "widget.antrian-onsite-view2",
    layout: {
        type: "vbox",
        align: "middle",
        pack: "center"
    },
    initComponent: function () {
        var a = this;
        a.items = [{
            layout: {
                type: "vbox",
                pack: "center"
            },
            width: 750,
            height: 400,
            items: [{
                xtype: "component",
                margin: "0 0 20 0",
                style: "font-size:14px;padding:15px;background:#D8F1EC;width:100%",
                html: "* Cetak Antrian Berdasarkan Jenis Pasien *"
            }, {
                xtype: "button",
                html: '<b style="font-size:32px">PASIEN LAMA</b>',
                ui: "soft-blue",
                margin: "0 0 20 0",
                width: "100%",
                height: 100,
                scale: "large",
                handler: "onPasienLama"
            }, {
                xtype: "button",
                html: '<b style="font-size:32px">PASIEN BARU</b>',
                ui: "soft-green",
                width: "100%",
                height: 100,
                scale: "large",
                handler: "onPasienBaru"
            }]
        }];
        a.callParent(arguments)
    }
});
Ext.define("antrian.onsite.View3", {
    extend: "com.Form",
    alias: "widget.antrian-onsite-view3",
    layout: "fit",
    bodyPadding: 2,
    title: "Antrian Onsite",
    ui: "panel-blue",
    items: [{
        title: "Pasien Lama",
        html: "Konten Pasien Lama"
    }, {
        title: "Pasien Baru",
        html: "Konten Pasien Lama"
    }],
    onLoadRecord: function () {}
});
Ext.define("antrian.onsite.Workspace", {
    extend: "com.Form",
    xtype: "antrian-onsite-workspace",
    controller: "antrian-onsite-workspace",
    viewModel: {
        stores: {
            storeRuangan: {
                type: "ruangan-antrian-store",
                queryParams: {
                    DEFAULT: 1,
                    start: 0,
                    limit: 1
                }
            }
        },
        data: {
            formConfig: {
                disabledField: true
            },
            headTitle: "Ambil Antrian Pendaftaran",
            posAntrian: "-",
            recordPos: "A",
            posAntrianList: true,
            hiddenPosAntrianJenis: true,
            hiddenPosAntrianView3: true,
            hiddenPosAntrianCaraBayar: true
        }
    },
    layout: {
        type: "vbox",
        align: "stretch"
    },
    header: {
        iconCls: "x-fa fa-list",
        padding: "7 7 7 7"
    },
    bind: {
        title: "{headTitle} | {posAntrian}"
    },
    ui: "panel-cyan",
    flex: 1,
    border: true,
    bodyPadding: 0,
    posantrians: undefined,
    initComponent: function () {
        var a = this;
        a.items = [{
            xtype: "container",
            height: 60,
            border: false,
            style: "padding:5px;text-align:center;border-bottom:0px #red solid;border-radius:0px;background:#1E90FF",
            bind: {
                hidden: "{posAntrianList}"
            },
            items: [{
                xtype: "textfield",
                width: "100%",
                height: 50,
                border: false,
                fieldStyle: "border-radius:5px",
                emptyText: "Cari Poliklinik Tujuan",
                listeners: {
                    search: "onSearch",
                    clear: "onClear"
                }
            }]
        }, {
            xtype: "antrian-onsite-view1",
            reference: "antrianonsiteview1",
            ui: "panel-cyan",
            hideHeaders: true,
            bind: {
                hidden: "{posAntrianList}"
            },
            flex: 1,
            listeners: {
                select: "onSelect"
            }
        }, {
            xtype: "antrian-onsite-view2",
            reference: "antrianonsiteview2",
            ui: "panel-cyan",
            hideHeaders: true,
            bind: {
                hidden: "{hiddenPosAntrianJenis}"
            },
            flex: 1,
            listeners: {
                select: "onSelect"
            }
        }, {
            xtype: "antrian-onsite-carabayar",
            reference: "antrianonsitecarabayar",
            ui: "panel-cyan",
            hideHeaders: true,
            bind: {
                hidden: "{hiddenPosAntrianCaraBayar}"
            },
            flex: 1,
            listeners: {
                select: "onSelect"
            }
        }, {
            xtype: "antrian-onsite-v2-workspace",
            reference: "antrianonsiteview3",
            ui: "panel-cyan",
            hideHeaders: true,
            bind: {
                hidden: "{hiddenPosAntrianView3}"
            },
            flex: 1,
            listeners: {
                select: "onSelect"
            }
        }];
        a.callParent(arguments)
    },
    onLoadRecord: function (e) {
        var d = this,
            b = d.down("antrian-onsite-view1"),
            a = d.getViewModel().get("storeRuangan");
        a.load();
        d.setLoading(true);
        if (e) {
            d.getViewModel().set("posAntrian", e.get("DESKRIPSI"));
            d.getViewModel().set("recordPos", e.get("NOMOR"))
        }
        d.getViewModel().set("posAntrianList", true);
        d.getViewModel().set("hiddenPosAntrianCaraBayar", true);
        d.getViewModel().set("hiddenPosAntrianJenis", true);
        if (d.getPropertyConfig("900301") == "TRUE") {
            d.getViewModel().set("posAntrianList", false);
            b.load();
            d.setLoading(false)
        }
        if (d.getPropertyConfig("900302") == "TRUE") {
            d.getViewModel().set("hiddenPosAntrianCaraBayar", false);
            b.load();
            d.setLoading(false)
        }
        if (d.getPropertyConfig("900303") == "TRUE") {
            d.getViewModel().set("hiddenPosAntrianJenis", false);
            b.load();
            d.setLoading(false)
        }
    }
});
Ext.define("antrian.onsite.WorkspaceController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-onsite-workspace",
    onPasienBpjs: function (f) {
        var g = this,
            d = g.getView(),
            e = g.getViewModel().get("storeRuangan"),
            a = g.getViewModel().get("recordPos"),
            h = e.getData().items[0].get("ID"),
            b = Ext.create("antrian.reservasi.Model", {});
        b.set("POS_ANTRIAN", a);
        b.set("POLI", h);
        b.set("TANGGALKUNJUNGAN", g.getView().getSysdate());
        b.set("JENIS", 2);
        b.set("JENIS_APLIKASI", 5);
        b.set("CARABAYAR", 2);
        d.setLoading(true);
        b.save({
            callback: function (j, k, m) {
                if (m) {
                    g.getView().notifyMessage("Sukses, Sedang Proses Cetak Antrian");
                    var o = JSON.parse(k._response.responseText),
                        l = o.response.kodebooking,
                        n = o.response.pos;
                    g.cetakAntrian(l, n)
                } else {
                    var o = JSON.parse(k.error.response.responseText);
                    g.getView().notifyMessage(o.detail, "danger-red");
                    d.setLoading(false)
                }
            }
        })
    },
    onPasienNonBpjs: function (f) {
        var g = this,
            d = g.getView(),
            e = g.getViewModel().get("storeRuangan"),
            a = g.getViewModel().get("recordPos"),
            h = e.getData().items[0].get("ID"),
            b = Ext.create("antrian.reservasi.Model", {});
        b.set("POS_ANTRIAN", a);
        b.set("POLI", h);
        b.set("TANGGALKUNJUNGAN", g.getView().getSysdate());
        b.set("JENIS", 2);
        b.set("JENIS_APLIKASI", 5);
        b.set("CARABAYAR", 1);
        d.setLoading(true);
        b.save({
            callback: function (j, k, m) {
                if (m) {
                    g.getView().notifyMessage("Sukses, Sedang Proses Cetak Antrian");
                    var o = JSON.parse(k._response.responseText),
                        l = o.response.kodebooking,
                        n = o.response.pos;
                    g.cetakAntrian(l, n)
                } else {
                    var o = JSON.parse(k.error.response.responseText);
                    g.getView().notifyMessage(o.detail, "danger-red");
                    d.setLoading(false)
                }
            }
        })
    },
    onPasienLama: function (f) {
        var g = this,
            d = g.getView(),
            e = g.getViewModel().get("storeRuangan"),
            a = g.getViewModel().get("recordPos"),
            h = e.getData().items[0].get("ID"),
            b = Ext.create("antrian.reservasi.Model", {});
        b.set("POS_ANTRIAN", a);
        b.set("POLI", h);
        b.set("TANGGALKUNJUNGAN", g.getView().getSysdate());
        b.set("JENIS", 1);
        b.set("JENIS_APLIKASI", 5);
        b.set("CARABAYAR", 1);
        d.setLoading(true);
        b.save({
            callback: function (j, k, m) {
                if (m) {
                    g.getView().notifyMessage("Sukses, Sedang Proses Cetak Antrian");
                    var o = JSON.parse(k._response.responseText),
                        l = o.response.kodebooking,
                        n = o.response.pos;
                    g.cetakAntrian(l, n)
                } else {
                    var o = JSON.parse(k.error.response.responseText);
                    g.getView().notifyMessage(o.detail, "danger-red");
                    d.setLoading(false)
                }
            }
        })
    },
    onPasienBaru: function (f) {
        var g = this,
            d = g.getView(),
            e = g.getViewModel().get("storeRuangan"),
            h = e.getData().items[0].get("ID"),
            a = g.getViewModel().get("recordPos"),
            b = Ext.create("antrian.reservasi.Model", {});
        b.set("POS_ANTRIAN", a);
        b.set("POLI", h);
        b.set("TANGGALKUNJUNGAN", g.getView().getSysdate());
        b.set("JENIS", 2);
        b.set("JENIS_APLIKASI", 5);
        b.set("CARABAYAR", 1);
        d.setLoading(true);
        b.save({
            callback: function (j, k, m) {
                if (m) {
                    g.getView().notifyMessage("Sukses, Sedang Proses Cetak Antrian");
                    var o = JSON.parse(k._response.responseText),
                        l = o.response.kodebooking,
                        n = o.response.pos;
                    g.cetakAntrian(l, n)
                } else {
                    var o = JSON.parse(k.error.response.responseText);
                    g.getView().notifyMessage(o.detail, "danger-red");
                    d.setLoading(false)
                }
            }
        })
    },
    cetakAntrian: function (d, e) {
        var b = this,
            a = b.getView();
        a.cetak({
            TITLE: "Cetak Antrian",
            NAME: "plugins.regonline.CetakKarcisAntrian",
            TYPE: "Word",
            EXT: "docx",
            PARAMETER: {
                PNOMOR: d
            },
            REQUEST_FOR_PRINT: true,
            PRINT_NAME: "CetakAntrian"
        });
        a.setLoading(false);
        b.onKirimEvenSocket(e)
    },
    onKirimEvenSocket: function (d) {
        console.log(d);
        if (window.location.protocol == "http:") {
            var b = "ws"
        } else {
            var b = "wss"
        }
        var a = b + "://" + window.location.hostname + ":8899";
        websocket = new WebSocket(a);
        websocket.onopen = function (f) {
            var e = {
                pos: d,
                act: "ANTRIAN_BARU"
            };
            websocket.send(JSON.stringify(e))
        }
    }
});
Ext.define("antrian.pengaturan.Workspace", {
    extend: "com.Form",
    xtype: "antrian-pengaturan-workspace",
    controller: "antrian-pengaturan-workspace",
    layout: "fit",
    bodyPadding: 2,
    items: [{
        xtype: "com-form",
        ui: "panel-blue",
        title: "Pengaturan",
        titleFieldConfig: {},
        fullscreenEnabled: true,
        menuConfig: {
            enabled: true,
            width: 300,
            buttonConfig: {}
        },
        fullscreenConfig: {},
        header: {
            items: [{}]
        }
    }],
    defaults: {
        border: false,
        listeners: {
            requestreport: "onRequestReport"
        }
    },
    load: function (a) {
        var d = this,
            b = this.down("com-form");
        b.setMenus(d.childmenu("9003", 3, 2))
    },
    childmenu: function (f, e, a) {
        var d = this,
            b = [];
        xuam = Ext.JSON.decode(Ext.util.Base64.decode(d.app.xuam));
        xuam.forEach(function (h, g) {
            h = Ext.JSON.decode(Ext.util.Base64.decode(h));
            if (h.ID.substring(0, a + 2) == f && h.LEVEL == e + 1) {
                var j = {
                    text: h.DESKRIPSI,
                    leaf: h.HAVE_CHILD == 0,
                    iconCls: h.ICON_CLS
                };
                if (h.HAVE_CHILD == 0 || h.CLASS) {
                    j.className = h.CLASS;
                    j.idClassName = d.replaceAll(h.CLASS.toLowerCase(), ".", "_")
                } else {
                    j.children = d.childmenu(h.ID, parseInt(h.LEVEL), a + 2)
                }
                b.push(j)
            }
        });
        return b
    }
});
Ext.define("antrian.pengaturan.WorkspaceController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-pengaturan-workspace",
    onTabChange: function (b, a) {
        if (a.load) {
            a.load({})
        }
    },
    onRefresh: function (a) {
        var d = this;
        d.getView().loadStore({})
    },
    onRequestReport: function (f, d, e) {
        console.log("request");
        this.getView().fireEvent("requestreport", f, d, e)
    }
});
Ext.define("antrian.pengaturan.hakakses.Detil", {
    extend: "com.Grid",
    alias: "widget.antrian-pengaturan-hakakses-detil",
    controller: "antrian-pengaturan-hakakses-detil",
    viewModel: {
        stores: {
            store: {
                type: "antrian-posantrian-store"
            }
        },
        data: {
            recordPengguna: undefined
        }
    },
    initComponent: function () {
        var a = this;
        a.columns = [{
            xtype: "rownumberer",
            text: "No",
            align: "left",
            width: 60,
            listeners: {
                checkchange: "onChange"
            }
        }, {
            flex: 1,
            dataIndex: "DESKRIPSI"
        }, {
            xtype: "checkcolumn",
            dataIndex: "STATUS_AKSES_POS_ANTRIAN",
            width: 90,
            listeners: {
                checkchange: "onChange"
            }
        }];
        a.callParent(arguments)
    },
    onLoadRecord: function (d) {
        var b = this,
            a = b.getViewModel().get("store");
        if (d) {
            a.queryParams = {
                AKSES_PENGGUNA: d.get("ID")
            };
            a.load();
            b.getViewModel().set("recordPengguna", d)
        } else {
            b.getViewModel().set("recordPengguna", undefined)
        }
    }
});
Ext.define("antrian.pengaturan.hakakses.DetilController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-pengaturan-hakakses-detil",
    onChange: function (l, o, h) {
        var j = this,
            n = j.getView(),
            k = j.getViewModel(),
            p = k.get("store"),
            m = k.get("recordPengguna"),
            q = p.getAt(o),
            a = Ext.create("antrian.useraksespos.Model", {});
        if (a) {
            n.setLoading(true);
            a.set("STATUS", h ? 1 : 0);
            a.set("USER", m.get("ID"));
            a.set("POS_ANTRIAN", q.get("NOMOR"));
            a.scope = this;
            a.save({
                callback: function (d, e, b) {
                    if (b) {
                        j.getView().notifyMessage("Akses Pos Antrian Berhasil Di " + (h == true ? "Aktifkan" : "NonAktifkan"));
                        p.reload();
                        n.setLoading(false)
                    } else {
                        j.getView().notifyMessage("Akses Pos Antrian Gagal Di " + (h == true ? "Aktifkan" : "NonAktifkan"));
                        a.set("STATUS", h ? 0 : 1);
                        n.setLoading(false);
                        p.reload()
                    }
                }
            })
        }
    }
});
Ext.define("antrian.pengaturan.hakakses.List", {
    extend: "com.Grid",
    alias: "widget.antrian-pengaturan-hakakses-list",
    controller: "antrian-pengaturan-hakakses-list",
    viewModel: {
        stores: {
            store: {
                type: "pengguna-store"
            }
        }
    },
    initComponent: function () {
        var a = this;
        a.columns = [{
            text: "NIP",
            flex: 1,
            dataIndex: "NIP"
        }, {
            text: "LOGIN",
            flex: 1,
            dataIndex: "LOGIN"
        }, {
            text: "NAMA",
            flex: 1,
            dataIndex: "NAMA"
        }, {
            text: "NIK",
            flex: 1,
            dataIndex: "NIK"
        }], a.dockedItems = [{
            xtype: "pagingtoolbar",
            bind: {
                store: "{store}"
            },
            dock: "bottom",
            items: ["-", {}, {}, {
                xtype: "search-field",
                cls: "x-text-border",
                autoFocus: true,
                emptyText: "Nama",
                flex: 1,
                listeners: {
                    search: "onsearch",
                    clear: "onClear"
                }
            }]
        }];
        a.callParent(arguments)
    },
    onLoadRecord: function () {
        var b = this,
            a = b.getViewModel().get("store");
        if (a) {
            a.queryParams = {
                STATUS: 1
            };
            a.load()
        }
    }
});
Ext.define("antrian.pengaturan.hakakses.ListController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-pengaturan-hakakses-list",
    onsearch: function (k, g) {
        var h = this,
            j = h.getViewModel(),
            a = j.get("store");
        a.queryParams = {
            NAMA: g,
            page: 1,
            start: 0
        };
        a.load()
    },
    onClear: function () {
        var e = this,
            f = e.getViewModel(),
            a = f.get("store");
        delete a.queryParams.NAMA;
        delete a.queryParams.page;
        delete a.queryParams.start;
        a.load()
    }
});
Ext.define("antrian.pengaturan.hakakses.Workspace", {
    extend: "com.Form",
    xtype: "antrian-pengaturan-hakakses-workspace",
    controller: "antrian-pengaturan-hakakses-workspace",
    viewModel: {
        stores: {
            store: {
                type: "grouppenggunaaksesmodule-store"
            }
        }
    },
    layout: "border",
    defaults: {
        flex: 1
    },
    border: false,
    bodyPadding: 5,
    ui: "panel-cyan",
    initComponent: function () {
        var a = this;
        a.items = [{
            region: "center",
            flex: 2,
            xtype: "antrian-pengaturan-hakakses-list",
            margin: "0px 5px 0px 0px",
            header: {
                iconCls: "x-fa fa-user",
                padding: "7px 7px 7px 7px",
                title: "Pengguna"
            },
            ui: "panel-cyan",
            reference: "penggunalist",
            checkboxModel: false,
            listeners: {
                select: "onSelectPengguna"
            }
        }, {
            region: "east",
            flex: 1,
            header: {
                iconCls: "x-fa fa-list",
                padding: "7px 7px 7px 7px",
                title: "Pos Antrian"
            },
            xtype: "antrian-pengaturan-hakakses-detil",
            reference: "jenisaksesposantrian",
            ui: "panel-cyan",
            hideHeaders: true,
            checkboxModel: true
        }];
        a.callParent(arguments)
    },
    load: function (a) {
        console.log("load");
        var d = this,
            e = d.down("antrian-pengaturan-hakakses-list");
        e.onLoadRecord()
    }
});
Ext.define("antrian.pengaturan.hakakses.WorkspaceController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-pengaturan-hakakses-workspace",
    init: function () {
        var e = this,
            a = e.getView(),
            f = e.getViewModel().get("store");
        if (f) {
            f.on("beforesync", function () {
                a.setLoading(true)
            })
        }
    },
    onRefresh: function () {
        var e = this,
            f = e.getReferences(),
            a = e.getViewModel().get("store");
        if (a) {
            a.reload();
            f.penggunamodulchecktree.loadStore()
        }
    },
    onCheckChange: function (e, f) {
        var a = this;
        a.storeData(e, f);
        e.cascadeBy(function (b) {
            b.set("checked", f);
            a.storeData(b, f)
        })
    },
    privates: {
        storeData: function (k, l) {
            var m = this,
                n = m.getReferences(),
                a = n.penggunagrouplist.getSelection(),
                o = m.getViewModel().get("store"),
                j = o.findRecord("MODUL", k.get("ID"), 0, false, true, true);
            if (j) {
                if (Ext.isNumeric(j.get("ID")) && j.get("ID") != 0) {
                    j.set("STATUS", l ? 1 : 0);
                    m.checkGroup(k, l)
                } else {
                    if (!l) {
                        o.remove(j);
                        m.checkGroup(k, l)
                    }
                }
            } else {
                if (l) {
                    o.add(Ext.create("antrian.useraksespos.Model", {
                        USER: a[0].get("ID"),
                        POS_ANTRIAN: k.get("ID"),
                        STATUS: 1
                    }))
                }
                m.checkGroup(k, l)
            }
        },
        checkGroup: function (h, d) {
            var g = this,
                f = g.getReferences(),
                a = f.penggunamodulchecktree,
                b = a.getStore(),
                e = h.get("ID").length,
                j = parseInt(e) - 2;
            if (j != 0) {
                id_substr = h.get("ID").substr(0, j);
                find = b.findRecord("ID", id_substr, 0, false, true, true);
                if (d) {
                    find.set("checked", d);
                    if (find) {
                        g.storeData(find, d)
                    }
                }
            }
        }
    },
    onSelectPengguna: function (j, h, a) {
        var f = this,
            d = f.getReferences();
        if (h) {
            d.jenisaksesposantrian.onLoadRecord(h)
        }
    },
    onSimpan: function (g) {
        var f = this,
            e = f.getView(),
            a = f.getViewModel().get("store");
        a.sync({
            callback: function (h, d, b) {
                e.setLoading(false);
                e.notifyMessage("Data Akses Pos Antrian telah disimpan", "danger-blue")
            }
        })
    }
});
Ext.define("antrian.pengaturan.hfis.jadwaldokter.List", {
    extend: "com.Grid",
    alias: "widget.antrian-pengaturan-hfis-jadwaldokter-list",
    controller: "antrian-pengaturan-hfis-jadwaldokter-list",
    viewModel: {
        stores: {
            store: {
                type: "antrian-hfis-jadwaldokter-referensi-store"
            }
        }
    },
    ui: "panel-cyan",
    header: {
        iconCls: "x-fa fa-list",
        padding: "5px 5px 5px 5px",
        title: "List Jadwal DOkter (By: HFIS)",
        items: [{
            xtype: "search-field",
            cls: "x-text-border",
            emptyText: "Cari Nama Dokter",
            margin: "0 5 0 0",
            flex: 1,
            listeners: {
                search: "onSearch",
                clear: "onClear"
            }
        }, {
            xtype: "antrian-combo-poli-bpjs",
            name: "POLI",
            firstLoad: true,
            reference: "poli",
            margin: "0 5 0 0"
        }, {
            xtype: "datefield",
            name: "TANGGAL",
            format: "d-m-Y",
            reference: "tanggal",
            margin: "0 5 0 0"
        }, {
            xtype: "button",
            ui: "soft-green",
            iconCls: "x-fa fa-refresh",
            tooltip: "Filter Jadwal Yang SUdah Di Tarik Dari HFIS",
            text: "Filter",
            listeners: {
                click: "onFilterJadwal"
            }
        }, {
            xtype: "button",
            ui: "soft-blue",
            iconCls: "x-fa fa-refresh",
            tooltip: "Ambil Jadwal Dari HFIS",
            text: "Ambil Jadwal Dari HFIS",
            listeners: {
                click: "onSinkronJadwal"
            }
        }]
    },
    initComponent: function () {
        var a = this;
        a.columns = [{
            xtype: "rownumberer",
            text: "No",
            align: "left",
            width: 60
        }, {
            text: "Sub Spesialis",
            flex: 1,
            dataIndex: "NM_SUB_SPESIALIS",
            renderer: "onRenderSubSpesialis"
        }, {
            text: "Piliklinik",
            flex: 1,
            dataIndex: "NM_POLI",
            renderer: "onRenderPoli"
        }, {
            text: "Dokter",
            flex: 2,
            dataIndex: "NM_DOKTER"
        }, {
            text: "Hari",
            width: 150,
            dataIndex: "NM_HARI"
        }, {
            text: "Jam",
            width: 150,
            dataIndex: "JAM"
        }, {
            text: "Kapasitas Pasien",
            width: 100,
            dataIndex: "KAPASITAS"
        }, {
            xtype: "checkcolumn",
            header: "STATUS",
            dataIndex: "STATUS",
            width: 90,
            listeners: {
                checkchange: "onChange"
            }
        }], a.dockedItems = [{
            xtype: "pagingtoolbar",
            bind: {
                store: "{store}"
            },
            dock: "bottom",
            displayInfo: true
        }];
        a.callParent(arguments)
    },
    onLoadRecord: function () {
        var d = this,
            b = d.getViewModel(),
            a = d.getViewModel().get("store");
        a.load()
    }
});
Ext.define("antrian.pengaturan.hfis.jadwaldokter.ListController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-pengaturan-hfis-jadwaldokter-list",
    onSearch: function (k, g) {
        var h = this,
            j = h.getViewModel(),
            a = j.get("store");
        a.queryParams = {
            QUERY: g,
            page: 1,
            start: 0
        };
        a.load()
    },
    onClear: function () {
        var e = this,
            f = e.getViewModel(),
            a = f.get("store");
        delete a.queryParams.QUERY;
        delete a.queryParams.page;
        delete a.queryParams.start;
        a.load()
    },
    onSinkronJadwal: function (f) {
        var g = this,
            d = g.getReferences(),
            b = g.getViewModel().get("store"),
            e = d.tanggal.getValue(),
            a = d.poli.getValue();
        b.queryParams = {
            sendToWs: 1,
            tanggal: e,
            poli: a
        };
        b.load()
    },
    onFilterJadwal: function (f) {
        var g = this,
            d = g.getReferences(),
            b = g.getViewModel().get("store"),
            e = d.tanggal.getValue(),
            a = d.poli.getValue();
        b.queryParams = {
            tanggal: e,
            poli: a
        };
        b.load()
    },
    onRefresh: function (b) {
        var d = this,
            a = d.getView();
        a.refresh()
    },
    onRenderPoli: function (b, a, e) {
        var d = e.get("REFERENSI") ? e.get("REFERENSI").POLI.KDPOLI + " | " + e.get("REFERENSI").POLI.NMPOLI : "-";
        return d
    },
    onRenderSubSpesialis: function (b, a, e) {
        var d = e.get("REFERENSI") ? e.get("REFERENSI").SUBSPESIALIS.KDSUBSPESIALIS + " | " + e.get("REFERENSI").SUBSPESIALIS.NMSUBSPESIALIS : "-";
        return d
    },
    onChange: function (o, a, l) {
        var m = this,
            j = m.getView(),
            n = m.getViewModel(),
            p = n.get("store"),
            k = p.getAt(a);
        k.set("STATUS", l ? 1 : 0);
        k.scope = this, record = Ext.create("antrian.hfis.jadwaldokter.Model", {});
        record.set("ID", k.get("ID"));
        record.set("UBAH_STATUS", 1);
        record.set("STATUS", l ? 1 : 0);
        record.save({
            callback: function (d, e, b) {
                if (b) {
                    j.notifyMessage("Status di" + (l == true ? "Aktifkan" : "Non Aktifkan"), "danger-blue");
                    j.reload()
                }
            }
        })
    }
});
Ext.define("antrian.pengaturan.hfis.jadwaldokter.Workspace", {
    extend: "com.Form",
    xtype: "antrian-pengaturan-hfis-jadwaldokter-workspace",
    bodyPadding: 2,
    layout: {
        type: "hbox",
        align: "stretch"
    },
    flex: 1,
    initComponent: function () {
        var a = this;
        a.items = [{
            xtype: "antrian-pengaturan-hfis-jadwaldokter-list",
            flex: 1,
            reference: "listjadwaldokterhfis"
        }];
        a.callParent(arguments)
    },
    onLoadRecord: function (d) {
        var a = this,
            b = a.down("antrian-pengaturan-hfis-jadwaldokter-list");
        b.onLoadRecord({})
    }
});
Ext.define("antrian.pengaturan.hfis.refpoli.List", {
    extend: "com.Grid",
    alias: "widget.antrian-pengaturan-hfis-refpoli-list",
    controller: "antrian-pengaturan-hfis-refpoli-list",
    viewModel: {
        stores: {
            store: {
                type: "antrian-polibpjs-referensi-store"
            }
        }
    },
    ui: "panel-cyan",
    header: {
        iconCls: "x-fa fa-list",
        padding: "5px 5px 5px 5px",
        title: "Referensi Poli (By: HFIS)",
        items: [{
            xtype: "button",
            ui: "soft-blue",
            iconCls: "x-fa fa-refresh",
            tooltip: "Ambil Referensi Poli Dari HFIS",
            text: "Ambil Referensi Poli Dari HFIS",
            listeners: {
                click: "onSinkronJadwal"
            }
        }]
    },
    initComponent: function () {
        var a = this;
        a.columns = [{
            xtype: "rownumberer",
            text: "No",
            align: "left",
            width: 60
        }, {
            text: "Kode Poli",
            flex: 1,
            dataIndex: "KDPOLI",
            renderer: "onRenderPoli"
        }, {
            text: "Nama Poli",
            flex: 1,
            dataIndex: "NMPOLI",
            renderer: "onRenderNmPoli"
        }, {
            text: "Kode Sub Spesialis",
            flex: 1,
            dataIndex: "KDSUBSPESIALIS",
            renderer: "onRenderSubSpesialis"
        }, {
            text: "Nama Sub Spesialis",
            flex: 1,
            dataIndex: "NMSUBSPESIALIS",
            renderer: "onRenderNmSubSpesialis"
        }], a.dockedItems = [{
            xtype: "pagingtoolbar",
            bind: {
                store: "{store}"
            },
            dock: "bottom",
            displayInfo: true
        }];
        a.callParent(arguments)
    },
    onLoadRecord: function () {
        var d = this,
            b = d.getViewModel(),
            a = d.getViewModel().get("store");
        a.load()
    }
});
Ext.define("antrian.pengaturan.hfis.refpoli.ListController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-pengaturan-hfis-refpoli-list",
    onSinkronJadwal: function (d) {
        var e = this,
            b = e.getReferences(),
            a = e.getViewModel().get("store");
        a.queryParams = {
            sendToWs: 1
        };
        a.load()
    },
    onRefresh: function (b) {
        var d = this,
            a = d.getView();
        a.refresh()
    },
    onRenderPoli: function (b, a, e) {
        var d = e.get("KDPOLI") ? e.get("KDPOLI") : "-";
        return d
    },
    onRenderNmPoli: function (b, a, e) {
        var d = e.get("NMPOLI") ? e.get("NMPOLI") : "-";
        return d
    },
    onRenderSubSpesialis: function (b, a, e) {
        var d = e.get("KDSUBSPESIALIS") ? e.get("KDSUBSPESIALIS") : "-";
        return d
    },
    onRenderNmSubSpesialis: function (b, a, e) {
        var d = e.get("NMSUBSPESIALIS") ? e.get("NMSUBSPESIALIS") : "-";
        return d
    }
});
Ext.define("antrian.pengaturan.hfis.refpoli.Workspace", {
    extend: "com.Form",
    xtype: "antrian-pengaturan-hfis-refpoli-workspace",
    bodyPadding: 2,
    layout: {
        type: "hbox",
        align: "stretch"
    },
    flex: 1,
    initComponent: function () {
        var a = this;
        a.items = [{
            xtype: "antrian-pengaturan-hfis-refpoli-list",
            flex: 1,
            reference: "listrefpolihfis"
        }];
        a.callParent(arguments)
    },
    onLoadRecord: function (d) {
        var a = this,
            b = a.down("antrian-pengaturan-hfis-refpoli-list");
        b.onLoadRecord({})
    }
});
Ext.define("antrian.pengaturan.monitoring.Form", {
    extend: "com.Form",
    xtype: "antrian-pengaturan-monitoring-form",
    requires: ["Ext.picker.Time"],
    controller: "antrian-pengaturan-monitoring-form",
    layout: {
        type: "hbox"
    },
    initComponent: function () {
        var a = this;
        a.items = [{
            xtype: "button",
            text: "Kirim Antrian",
            ui: "soft-blue",
            handler: "onKirimAntrian",
            margin: "0 10 0 0"
        }, {
            xtype: "button",
            text: "Task (1)",
            ui: "soft-green",
            handler: "onUpdateWaktuTunggu1",
            margin: "0 10 0 0"
        }, {
            xtype: "button",
            text: "Task (2)",
            ui: "soft-green",
            handler: "onUpdateWaktuTunggu2",
            margin: "0 10 0 0"
        }, {
            xtype: "button",
            text: "Task (3)",
            ui: "soft-green",
            handler: "onUpdateWaktuTunggu3",
            margin: "0 10 0 0"
        }, {
            xtype: "button",
            text: "Task (4)",
            ui: "soft-green",
            handler: "onUpdateWaktuTunggu4",
            margin: "0 10 0 0"
        }, {
            xtype: "button",
            text: "Task (5)",
            ui: "soft-green",
            handler: "onUpdateWaktuTunggu5",
            margin: "0 10 0 0"
        }, {
            xtype: "button",
            text: "Task (6)",
            ui: "soft-green",
            handler: "onUpdateWaktuTunggu6",
            margin: "0 10 0 0"
        }, {
            xtype: "button",
            text: "Task (7)",
            ui: "soft-green",
            handler: "onUpdateWaktuTunggu7",
            margin: "0 10 0 0"
        }, {
            xtype: "button",
            text: "Batalkan",
            ui: "soft-red",
            handler: "onBatalAntrian",
            margin: "0 10 0 0"
        }];
        a.callParent(arguments)
    },
    onLoadRecord: function (d) {
        var b = this,
            a = b.getSysdate()
    }
});
Ext.define("antrian.pengaturan.monitoring.FormController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-pengaturan-monitoring-form",
    onKirimAntrian: function (b) {
        var d = this,
            a = d.getView();
        a.fireEvent("kirimantrian")
    },
    onUpdateWaktuTunggu1: function (b) {
        var d = this,
            a = d.getView();
        a.fireEvent("updatewaktutunggu", 1)
    },
    onUpdateWaktuTunggu2: function (b) {
        var d = this,
            a = d.getView();
        a.fireEvent("updatewaktutunggu", 2)
    },
    onUpdateWaktuTunggu3: function (b) {
        var d = this,
            a = d.getView();
        a.fireEvent("updatewaktutunggu", 3)
    },
    onUpdateWaktuTunggu4: function (b) {
        var d = this,
            a = d.getView();
        a.fireEvent("updatewaktutunggu", 4)
    },
    onUpdateWaktuTunggu5: function (b) {
        var d = this,
            a = d.getView();
        a.fireEvent("updatewaktutunggu", 5)
    },
    onUpdateWaktuTunggu6: function (b) {
        var d = this,
            a = d.getView();
        a.fireEvent("updatewaktutunggu", 6)
    },
    onUpdateWaktuTunggu7: function (b) {
        var d = this,
            a = d.getView();
        a.fireEvent("updatewaktutunggu", 7)
    },
    onBatalAntrian: function (b) {
        var d = this,
            a = d.getView();
        a.fireEvent("batalantrian", 99)
    }
});
Ext.define("antrian.pengaturan.monitoring.List", {
    extend: "com.Grid",
    alias: "widget.antrian-pengaturan-monitoring-list",
    controller: "antrian-pengaturan-monitoring-list",
    viewModel: {
        stores: {
            store: {
                type: "antrian-reservasi-store"
            }
        }
    },
    penggunaAksesPos: [],
    ui: "panel-cyan",
    header: {
        iconCls: "x-fa fa-list",
        padding: "5px 5px 5px 5px",
        title: "Monitoring Status Pengiriman Antrian Ke BPJS",
        items: [{
            xtype: "combobox",
            name: "POS_ANTRIAN",
            allowBlank: false,
            enterFocus: true,
            reference: "posantrian",
            enforceMaxLength: true,
            forceSelection: true,
            validateOnBlur: true,
            displayField: "DESKRIPSI",
            valueField: "NOMOR",
            queryMode: "local",
            margin: "0 5 0 0",
            typeAhead: true,
            emptyText: "[ Pilih Pos Antrian ]",
            store: {
                model: "data.model.Kategori"
            }
        }, {
            xtype: "combobox",
            reference: "jeniscarabayar",
            emptyText: "[ Filter Penjamin ]",
            store: Ext.create("Ext.data.Store", {
                fields: ["value", "desk"],
                data: [{
                    value: "",
                    desk: "Semua"
                }, {
                    value: "1",
                    desk: "Umum / Corporate"
                }, {
                    value: "2",
                    desk: "BPJS / JKN"
                }]
            }),
            queryMode: "local",
            displayField: "desk",
            value: 2,
            margin: "0 5 0 0",
            valueField: "value"
        }, {
            xtype: "datefield",
            name: "TANGGAL",
            format: "d-m-Y",
            reference: "tanggal",
            margin: "0 5 0 0"
        }, {
            xtype: "combobox",
            reference: "jenisaplikasiantrian",
            emptyText: "[ Jenis Antrian ]",
            margin: "0 5 0 0",
            store: Ext.create("Ext.data.Store", {
                fields: ["value", "desk"],
                data: [{
                    value: "2",
                    desk: "Mobile JKN"
                }, {
                    value: "3",
                    desk: "Kontrol"
                }, {
                    value: "22",
                    desk: "Web & Mobile APK"
                }]
            }),
            queryMode: "local",
            displayField: "desk",
            valueField: "value"
        }, {
            xtype: "button",
            ui: "soft-green",
            iconCls: "x-fa fa-refresh",
            tooltip: "Filter Data Antrian",
            text: "Filter",
            listeners: {
                click: "onFilterAntrian"
            }
        }]
    },
    initComponent: function () {
        var a = this;
        a.cellEditing = new Ext.grid.plugin.CellEditing({
            clicksToEdit: 1,
            listeners: {
                afteredit: "afterEdit",
                beforeedit: "beforeEdit"
            }
        });
        a.plugins = [a.cellEditing];
        a.columnLines = true, a.selModel = {
            type: "checkboxmodel",
            checkOnly: true
        };
        a.columns = [{
            text: "No.Antrian",
            dataIndex: "NO",
            menuDisabled: true,
            sortable: false,
            align: "left",
            width: 100,
            renderer: "onAntrian"
        }, {
            text: "No.RM",
            dataIndex: "NORM",
            menuDisabled: true,
            sortable: false,
            align: "left",
            width: 100,
            renderer: "onRm",
            editor: {
                xtype: "textfield",
                emptyText: "Norm",
                name: "NORM",
                disabled: true
            }
        }, {
            text: "No.Kartu",
            dataIndex: "NO_KARTU_BPJS",
            menuDisabled: true,
            sortable: false,
            align: "left",
            width: 100,
            renderer: "onKartu",
            editor: {
                xtype: "textfield",
                emptyText: "No.Kartu",
                name: "NO_KARTU_BPJS",
                disabled: true
            }
        }, {
            text: "Poli Tujuan",
            dataIndex: "POLI",
            menuDisabled: true,
            sortable: false,
            align: "left",
            width: 200,
            renderer: "onPoli"
        }, {
            text: "Status Task ID",
            flex: 1,
            columns: [{
                dataIndex: "STATUS_TASK_ID1",
                text: "Task ID 1",
                renderer: "onTask1",
                editor: {
                    xtype: "datetimefield",
                    emptyText: "[Jam:Mnt:Dtk]",
                    format: "Y-m-d H:i:s",
                    name: "TASK1",
                    TASK: 1
                }
            }, {
                dataIndex: "STATUS_TASK_ID2",
                text: "Task ID 2",
                renderer: "onTask2",
                editor: {
                    xtype: "datetimefield",
                    emptyText: "[Jam:Mnt:Dtk]",
                    format: "Y-m-d H:i:s",
                    name: "TASK2",
                    TASK: 2
                }
            }, {
                dataIndex: "STATUS_TASK_ID3",
                text: "Task ID 3",
                renderer: "onTask3",
                editor: {
                    xtype: "datetimefield",
                    emptyText: "[Jam:Mnt:Dtk]",
                    format: "Y-m-d H:i:s",
                    name: "TASK3",
                    TASK: 3
                }
            }, {
                dataIndex: "STATUS_TASK_ID4",
                text: "Task ID 4",
                renderer: "onTask4",
                editor: {
                    xtype: "datetimefield",
                    emptyText: "[Jam:Mnt:Dtk]",
                    format: "Y-m-d H:i:s",
                    name: "TASK4",
                    TASK: 4
                }
            }, {
                dataIndex: "STATUS_TASK_ID5",
                text: "Task ID 5",
                renderer: "onTask5",
                editor: {
                    xtype: "datetimefield",
                    emptyText: "[Jam:Mnt:Dtk]",
                    format: "Y-m-d H:i:s",
                    name: "TASK5",
                    TASK: 5
                }
            }, {
                dataIndex: "STATUS_TASK_ID6",
                text: "Task ID 6",
                renderer: "onTask6",
                editor: {
                    xtype: "datetimefield",
                    emptyText: "[Jam:Mnt:Dtk]",
                    format: "Y-m-d H:i:s",
                    name: "TASK6",
                    TASK: 6
                }
            }, {
                dataIndex: "STATUS_TASK_ID7",
                text: "Task ID 7",
                renderer: "onTask7",
                editor: {
                    xtype: "datetimefield",
                    emptyText: "[Jam:Mnt:Dtk]",
                    format: "Y-m-d H:i:s",
                    name: "TASK7",
                    TASK: 7
                }
            }]
        }, {
            text: "Status Kirim BPJS",
            width: 300,
            renderer: "onRenderStatusKirim"
        }], a.dockedItems = [{
            xtype: "pagingtoolbar",
            bind: {
                store: "{store}"
            },
            dock: "bottom",
            displayInfo: true
        }];
        a.callParent(arguments)
    },
    onSetGrid: function (d) {
        var b = this,
            e = b.down("[reference=posantrian]"),
            a = b.getViewModel().get("store");
        e.getStore().loadData(b.getAksesPosAntrian());
        a.queryParams = {
            GET_VARIABEL_BPJS: 1,
            TANGGALKUNJUNGAN: "0000-00-00",
            POS_ANTRIAN: "",
            JENIS_APLIKASI: "0"
        };
        a.load()
    },
    getAksesPosAntrian: function () {
        var a = this;
        return a.penggunaAksesPos ? a.penggunaAksesPos : []
    },
    loadData: function () {
        var a = this;
        Ext.Ajax.request({
            url: webservice.location + "registrasionline/plugins/getAksesPosAntrian",
            useDefaultXhrHeader: false,
            withCredentials: true,
            success: function (d) {
                var b = Ext.JSON.decode(d.responseText);
                var e = b.data.AKSES_POS_ANTRIAN;
                a.penggunaAksesPos = e;
                a.onSetGrid(e)
            },
            failure: function (b) {
                return []
            }
        })
    }
});
Ext.define("antrian.pengaturan.monitoring.ListController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-pengaturan-monitoring-list",
    beforeEdit: function (e, d, b) {
        var g = this,
            f = g.getViewModel(),
            a = f.getStore("store"),
            h = a.getAt(d.rowIdx);
        if (h.get(d.field) == "1") {
            d.cancel = true
        }
    },
    afterEdit: function (g, j, d) {
        var h = this,
            a = h.getView(),
            f = h.getViewModel(),
            b = f.getStore("store"),
            k = j.record;
        record = Ext.create("antrian.waktutungguantrian.Model", {});
        record.set("ANTRIAN", k.get("ID"));
        record.set("TASK_ID", j.column.field.TASK);
        record.set("TANGGAL", j.value);
        record.set("STATUS", 0);
        record.set("RESPONSE", "LOCAL");
        a.setLoading(true);
        record.save({
            callback: function (e, l, m) {
                if (m) {
                    b.reload();
                    a.setLoading(false)
                } else {
                    var n = JSON.parse(l.error.response.responseText);
                    h.getView().notifyMessage(n.detail, "danger-red");
                    b.reload();
                    a.setLoading(false)
                }
            }
        })
    },
    onFilterAntrian: function (f) {
        var g = this,
            b = g.getReferences(),
            a = g.getViewModel().get("store"),
            e = b.tanggal.getValue(),
            j = b.posantrian.getValue(),
            d = b.jeniscarabayar.getValue(),
            h = b.jenisaplikasiantrian.getValue();
        a.queryParams = {
            GET_VARIABEL_BPJS: 1,
            TANGGALKUNJUNGAN: e,
            POS_ANTRIAN: j,
            FILTER_CB: d,
            JENIS_APLIKASI: h
        };
        a.load()
    },
    onRefresh: function (b) {
        var d = this,
            a = d.getView();
        a.refresh()
    },
    onAntrian: function (d, b, e) {
        if (e.get("CARABAYAR") == 2) {
            var a = 2
        } else {
            var a = 1
        }
        this.setBackGround(b, e.get("STATUS_KIRIM_BPJS"));
        return e.get("POS_ANTRIAN") + "" + a + "-" + Ext.String.leftPad(d, 3, "0")
    },
    onPoli: function (d, b, e) {
        var a = e.get("REFERENSI").POLI_BPJS.NMPOLI;
        this.setBackGround(b, e.get("STATUS_KIRIM_BPJS"));
        return a
    },
    onRm: function (d, b, e) {
        var a = e.get("NORM");
        this.setBackGround(b, e.get("STATUS_KIRIM_BPJS"));
        return a
    },
    onKartu: function (b, a, e) {
        var d = e.get("NO_KARTU_BPJS");
        this.setBackGround(a, e.get("STATUS_KIRIM_BPJS"));
        return d
    },
    onTask1: function (d, b, e) {
        var a = e.get("REFERENSI").TASK_ID_ANTRIAN.TASK_1;
        this.setBackGroundTask(b, e.get("STATUS_TASK_ID1"));
        return a
    },
    onTask2: function (d, b, e) {
        var a = e.get("REFERENSI").TASK_ID_ANTRIAN.TASK_2;
        this.setBackGroundTask(b, e.get("STATUS_TASK_ID2"));
        return a
    },
    onTask3: function (d, b, e) {
        var a = e.get("REFERENSI").TASK_ID_ANTRIAN.TASK_3;
        this.setBackGroundTask(b, e.get("STATUS_TASK_ID3"));
        return a
    },
    onTask4: function (d, b, e) {
        var a = e.get("REFERENSI").TASK_ID_ANTRIAN.TASK_4;
        this.setBackGroundTask(b, e.get("STATUS_TASK_ID4"));
        return a
    },
    onTask5: function (d, b, e) {
        var a = e.get("REFERENSI").TASK_ID_ANTRIAN.TASK_5;
        this.setBackGroundTask(b, e.get("STATUS_TASK_ID5"));
        return a
    },
    onTask6: function (d, b, e) {
        var a = e.get("REFERENSI").TASK_ID_ANTRIAN.TASK_6;
        this.setBackGroundTask(b, e.get("STATUS_TASK_ID6"));
        return a
    },
    onTask7: function (d, b, e) {
        var a = e.get("REFERENSI").TASK_ID_ANTRIAN.TASK_7;
        this.setBackGroundTask(b, e.get("STATUS_TASK_ID7"));
        return a
    },
    onRenderStatusKirim: function (d, b, e) {
        var a = e.get("REFERENSI").STATUS_KIRIM_BPJS.RESPONSE;
        this.setBackGround(b, e.get("STATUS_KIRIM_BPJS"));
        return a
    },
    setBackGround: function (b, a) {
        if (a == 1) {
            b.style = "background-color:#0df775;color:#000000;font-weight: bold"
        }
    },
    setBackGroundTask: function (b, a) {
        if (a == 1) {
            b.style = "background-color:#0df775;color:#000000;font-weight: bold"
        } else {
            if (a == 0) {
                b.style = "background-color:#A9A9A9;color:#000000;font-weight: bold"
            } else {
                b.style = "color:#DDD"
            }
        }
    }
});
Ext.define("antrian.pengaturan.monitoring.Workspace", {
    extend: "com.Form",
    xtype: "antrian-pengaturan-monitoring-workspace",
    controller: "antrian-pengaturan-monitoring-workspace",
    bodyPadding: 2,
    layout: {
        type: "hbox",
        align: "stretch"
    },
    flex: 1,
    initComponent: function () {
        var a = this;
        a.items = [{
            flex: 1,
            layout: "border",
            items: [{
                region: "center",
                xtype: "antrian-pengaturan-monitoring-list",
                reference: "listmonitoringstatusantrian",
                flex: 1
            }, {
                region: "south",
                xtype: "antrian-pengaturan-monitoring-form",
                reference: "formmonitoringstatusantrian",
                border: true,
                listeners: {
                    kirimantrian: "onKirimAntrian",
                    updatewaktutunggu: "onUpdateWaktuTunggu",
                    batalantrian: "onBatalAntrian"
                }
            }]
        }];
        a.callParent(arguments)
    },
    onLoadRecord: function (e) {
        var b = this,
            d = b.down("antrian-pengaturan-monitoring-list"),
            a = b.down("antrian-pengaturan-monitoring-form");
        d.loadData();
        a.onLoadRecord()
    }
});
Ext.define("antrian.pengaturan.monitoring.WorkspaceController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-pengaturan-monitoring-workspace",
    onKirimAntrian: function () {
        var f = this,
            b = f.getView(),
            e = f.getReferences(),
            h = e.listmonitoringstatusantrian.getSelectionModel(),
            d = e.listmonitoringstatusantrian.getStore(),
            g = h.selected,
            a = Ext.create("antrian.kirimantrian.Model", {});
        c = [];
        g.each(function (j) {
            if (j.get("STATUS_KIRIM_BPJS") == "0") {
                c.push({
                    kodebooking: j.get("ID"),
                    jenispasien: j.get("REFERENSI").RESERVASI_ANTRIAN.jenispasien,
                    nomorkartu: j.get("NO_KARTU_BPJS"),
                    nik: j.get("NIK"),
                    nohp: j.get("CONTACT"),
                    kodepoli: j.get("REFERENSI").RESERVASI_ANTRIAN.refpolibpjs,
                    namapoli: j.get("REFERENSI").POLI_BPJS.NMPOLI,
                    pasienbaru: j.get("REFERENSI").RESERVASI_ANTRIAN.pasienbaru,
                    norm: j.get("REFERENSI").RESERVASI_ANTRIAN.norm,
                    tanggalperiksa: j.get("REFERENSI").RESERVASI_ANTRIAN.tanggal,
                    kodedokter: j.get("DOKTER"),
                    namadokter: j.get("REFERENSI").RESERVASI_ANTRIAN.namadokter,
                    jampraktek: j.get("REFERENSI").RESERVASI_ANTRIAN.jampraktek,
                    jeniskunjungan: j.get("REFERENSI").RESERVASI_ANTRIAN.jeniskunjungan,
                    nomorreferensi: j.get("NO_REF_BPJS"),
                    nomorantrean: j.get("REFERENSI").RESERVASI_ANTRIAN.nomorantrian,
                    angkaantrean: j.get("NO"),
                    estimasidilayani: j.get("REFERENSI").RESERVASI_ANTRIAN.estimasidilayani,
                    sisakuotajkn: j.get("REFERENSI").RESERVASI_ANTRIAN.sisakuotajkn,
                    kuotajkn: j.get("REFERENSI").RESERVASI_ANTRIAN.kuotajkn,
                    sisakuotanonjkn: j.get("REFERENSI").RESERVASI_ANTRIAN.sisakuotanonjkn,
                    kuotanonjkn: j.get("REFERENSI").RESERVASI_ANTRIAN.kuotanonjkn,
                    keterangan: "Diharapkan datang paling lambat 15 menit sebelum estimasi jam pendaftaran"
                })
            }
        });
        if (c.length > 0) {
            a.set("DETAIL_ANTRIAN", c);
            b.setLoading(true);
            a.save({
                callback: function (j, k, l) {
                    if (l) {
                        d.reload();
                        b.setLoading(false)
                    } else {
                        var m = JSON.parse(k.error.response.responseText);
                        f.getView().notifyMessage(m.detail, "danger-red");
                        d.reload();
                        b.setLoading(false)
                    }
                }
            })
        } else {
            b.notifyMessage("Silahkan Pilih Row Data Yang Akan Di Kirim", "danger-red")
        }
    },
    onUpdateWaktuTunggu: function (f) {
        var g = this,
            b = g.getView(),
            e = g.getReferences(),
            j = e.listmonitoringstatusantrian.getSelectionModel(),
            d = e.listmonitoringstatusantrian.getStore(),
            h = j.selected,
            a = Ext.create("antrian.waktutungguantrian.manual.Model", {});
        c = [];
        h.each(function (l) {
            if (f == "1") {
                var k = l.get("REFERENSI").TASK_ID_ANTRIAN.TASK_1
            }
            if (f == "2") {
                var k = l.get("REFERENSI").TASK_ID_ANTRIAN.TASK_2
            }
            if (f == "3") {
                var k = l.get("REFERENSI").TASK_ID_ANTRIAN.TASK_3
            }
            if (f == "4") {
                var k = l.get("REFERENSI").TASK_ID_ANTRIAN.TASK_4
            }
            if (f == "5") {
                var k = l.get("REFERENSI").TASK_ID_ANTRIAN.TASK_5
            }
            if (f == "6") {
                var k = l.get("REFERENSI").TASK_ID_ANTRIAN.TASK_6
            }
            if (f == "7") {
                var k = l.get("REFERENSI").TASK_ID_ANTRIAN.TASK_6
            }
            if (f == "99") {
                var k = b.getSysdate()
            }
            c.push({
                kodebooking: l.get("ID"),
                taskid: f,
                waktu: k
            })
        });
        if (c.length > 0) {
            a.set("DETAIL_ANTRIAN", c);
            b.setLoading(true);
            a.save({
                callback: function (k, l, m) {
                    if (m) {
                        d.reload();
                        b.setLoading(false)
                    } else {
                        var n = JSON.parse(l.error.response.responseText);
                        g.getView().notifyMessage(n.detail, "danger-red");
                        d.reload();
                        b.setLoading(false)
                    }
                }
            })
        } else {
            b.notifyMessage("Silahkan Pilih Row Data Yang Akan Di Kirim", "danger-red")
        }
    },
    onBatalAntrian: function (f) {
        var g = this,
            b = g.getView(),
            e = g.getReferences(),
            j = e.listmonitoringstatusantrian.getSelectionModel(),
            d = e.listmonitoringstatusantrian.getStore(),
            h = j.selected,
            a = Ext.create("antrian.waktutungguantrian.batal.Model", {});
        c = [];
        h.each(function (k) {
            c.push({
                kodebooking: k.get("ID"),
                keterangan: "Tidak Datang"
            })
        });
        if (c.length > 0) {
            a.set("DETAIL_ANTRIAN", c);
            b.setLoading(true);
            a.save({
                callback: function (k, l, m) {
                    if (m) {
                        d.reload();
                        b.setLoading(false)
                    } else {
                        var n = JSON.parse(l.error.response.responseText);
                        g.getView().notifyMessage(n.detail, "danger-red");
                        d.reload();
                        b.setLoading(false)
                    }
                }
            })
        } else {
            b.notifyMessage("Silahkan Pilih Row Data Yang Akan Di Kirim", "danger-red")
        }
    }
});
Ext.define("antrian.poli.Display.Display", {
    extend: "com.Form",
    xtype: "antrian-poli-display-display",
    controller: "antrian-poli-display-display",
    viewModel: {
        data: {
            instansi: undefined,
            infoTeks: "",
            tglNow: "-",
            statusWebsocket: "Disconnect",
            poliAntrian: ""
        },
        stores: {
            store: {
                type: "instansi-store"
            }
        }
    },
    audio: {
        integrasi: undefined,
        service: undefined
    },
    layout: {
        type: "vbox",
        align: "stretch"
    },
    defaults: {
        border: false
    },
    datapanggil: [],
    dataAntrian: [],
    audioCount: 1,
    audios: [],
    idx: 0,
    bodyStyle: "background-color:#aa8a51",
    initComponent: function () {
        if (window.location.protocol == "http:") {
            var d = "ws"
        } else {
            var d = "wss"
        }
        var b = this;
        var a = Ext.create("Ext.ux.WebSocket", {
            url: "ws://" + window.location.hostname + ":8899",
            listeners: {
                open: function (e) {
                    b.getViewModel().set("statusWebsocket", "Connected")
                },
                message: function (e, h) {
                    var f = JSON.parse(h);
                    if (f) {
                        if (f.act) {
                            if (f.act == "PANGGIL_POLI") {
                                if (b.getViewModel().get("poliAntrian") == f.poli) {
                                    var g = f.nomor;
                                    if (!b.dataAntrian.includes(g)) {
                                        b.datapanggil.push(f);
                                        b.dataAntrian.push(g)
                                    }
                                    if (b.datapanggil.length === 1) {
                                        b.setProsesPanggil();
                                        b.onRefreshView(f.poli)
                                    }
                                }
                            }
                        }
                    }
                },
                close: function (e) {
                    b.getViewModel().set("statusWebsocket", "Disonnected Socket")
                }
            }
        });
        b.items = [{
            layout: {
                type: "hbox",
                align: "middle"
            },
            border: false,
            height: 50,
            bodyStyle: "padding-left:10px;background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#356aa0), color-stop(100%,#356aa0));",
            items: [{
                xtype: "image",
                bind: {
                    src: "classic/resources/images/{instansi}.png"
                },
                id: "idImage",
                width: 40,
                border: false,
                bodyStyle: "background-color:transparent;"
            }, {
                flex: 1,
                bind: {
                    data: {
                        items: "{store.data.items}"
                    }
                },
                tpl: new Ext.XTemplate('<tpl for="items">', "{data.REFERENSI.PPK.NAMA}", "</tpl>"),
                border: false,
                bodyStyle: "background-color:transparent; font-size: 18px; color: white; "
            }, {
                xtype: "label",
                bind: {
                    html: "{tglNow}"
                },
                width: 350,
                border: false,
                style: "background-color:transparent; font-size: 20px; color: white; "
            }]
        }, {
            flex: 1,
            layout: {
                type: "hbox",
                align: "stretch"
            },
            defaults: {
                flex: 1,
                margin: "0 1 0 1"
            },
            border: false,
            reference: "informasi",
            items: [{
                flex: 2,
                border: false,
                layout: {
                    type: "vbox",
                    align: "stretch"
                },
                defaults: {
                    bodyStyle: "background-color:#D8F1EC"
                },
                items: [{
                    border: true,
                    style: "padding:15px;background-color:#A5C8D1;border-bottom:1px #DDD solid",
                    bodyStyle: "background-color:transparent",
                    html: '<iframe width="100%" height="300px" src="classic/resources/images/banner-antrian/video.mp4" frameborder="0" allow="accelerometer loop="true" autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
                }, {
                    xtype: "image",
                    src: "classic/resources/images/banner-antrian/image.jpg",
                    style: "width:100%;height:300px;padding:15px;background-color:#A5C8D1;border-bottom:1px #DDD solid",
                    border: true,
                    bodyStyle: "background-color:transparent;"
                }, {
                    xtype: "container",
                    style: "background-color:#A5C8D1;",
                    flex: 1
                }, {
                    style: "padding:10px;font-size:14px;border-top:1px #DDD solid;text-left:center;font-style:italic;color:#434343;background-color:#A5C8D1;",
                    bodyStyle: "background-color:transparent",
                    bind: {
                        html: "Status : {statusWebsocket}"
                    }
                }]
            }, {
                flex: 5,
                border: false,
                layout: {
                    type: "vbox",
                    align: "stretch"
                },
                bodyPadding: "20",
                items: [{
                    xtype: "component",
                    html: "NOMOR ANTRIAN YANG DI LAYANI",
                    style: "padding:15px;font-size:22px;text-align:center;color:#434343;background-color:#A5C8D1;border-radius:4px;margin-top:20px"
                }, {
                    flex: 1,
                    reference: "dataview",
                    style: "margin-top:10px;background-color:#FFF",
                    xtype: "antrian-poli-display-view",
                    viewConfig: {
                        loadMask: false
                    }
                }]
            }]
        }, {
            layout: {
                type: "hbox",
                align: "middle"
            },
            height: 30,
            border: false,
            bodyStyle: "background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#356aa0), color-stop(100%,#356aa0));",
            items: [{
                xtype: "displayfield",
                flex: 1,
                fieldStyle: "background-color:transparent;font-size: 14px;  margin-left: 10px;color: white;",
                border: false,
                bind: {
                    value: "<marquee>{infoTeks}</marquee>"
                }
            }]
        }];
        b.callParent(arguments)
    },
    onLoadRecord: function (b, e) {
        var d = this,
            f = d.down("antrian-poli-display-view"),
            a = d.getController();
        a.mulai();
        if (b) {
            f.onLoadRecord(b, e);
            d.getViewModel().set("poliAntrian", b.get("ID"))
        }
        d.audio.service = Ext.create("antrian.Audio", {
            parent: d,
            audioCount: 8,
            posAntrian: "-"
        });
        d.audios = d.audio.service.getAudios();
        if (d.audio.service) {
            d.add(d.audios)
        }
    },
    onAKhir: function (b) {
        var d = this,
            a = d.getViewModel().get("poliAntrian");
        d.dataAntrian.shift();
        d.datapanggil.shift();
        d.setProsesPanggil();
        d.onRefreshView(a)
    },
    getPosAntrian: function () {
        var b = this,
            a = b.getViewModel().get("poliAntrian");
        return a
    },
    onRefreshView: function (b) {
        var e = this,
            d = e.down("antrian-poli-display-view").getStore(),
            a = d.getQueryParams().POLI;
        if (a == b) {
            d.reload()
        }
    },
    runLogo: function () {
        if (this.deg == 360) {
            this.deg = 0
        } else {
            this.deg += 5
        }
        Ext.getCmp("idImage").setStyle("-webkit-transform: rotateY(" + this.deg + "deg)")
    },
    remove: function (d, b) {
        var a = Ext.Array.indexOf(d, b);
        if (a !== -1) {
            erase(d, a, 1)
        }
        return d
    },
    setProsesPanggil: function () {
        var b = this;
        if (b.datapanggil.length > 0) {
            var e = Ext.String.leftPad(b.datapanggil[0].nomor, 3, "0"),
                d = e.split("", 3);
            var a = {
                POLI: b.datapanggil[0].nmpoli,
                NOMOR1: d[0],
                NOMOR2: d[1],
                NOMOR3: d[2]
            };
            b.callAntrian(a)
        }
    },
    privates: {
        callAntrian: function (d) {
            var b = this;
            if (d) {
                var a = b.audio.service.speechAntrian(["in.wav", "nomor_antrian.mp3", d.NOMOR1 + ".mp3", d.NOMOR2 + ".mp3", d.NOMOR3 + ".mp3", "Silahkan_Ke.mp3", d.POLI + ".mp3", "out.wav"])
            }
        }
    }
});
Ext.define("antrian.poli.Display.DisplayController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-poli-display-display",
    currentRefreshTimeRuangan: 0,
    refreshTime: 0,
    onAfterRender: function () {
        var e = this,
            b = e.getViewModel(),
            a = b.get("store"),
            d = Ext.getStore("instansi");
        if (d) {
            b.set("store", d);
            new Ext.util.DelayedTask(function () {
                b.set("instansi", d.getAt(0).get("PPK"))
            }).delay(1000)
        } else {
            a.doAfterLoad = function (f, h, g, j) {
                if (j) {
                    if (h.length > 0) {
                        b.set("instansi", h[0].get("PPK"))
                    }
                }
            };
            a.load()
        }
    },
    mulai: function (d) {
        var b = this,
            a = b.getViewModel();
        b.currentRefreshTimeRuangan = a.get("refreshTime");
        b.refreshTime = a.get("refreshTime");
        if (b.task == undefined) {
            b.task = {
                run: function () {
                    a.set("tglNow", Ext.Date.format(new Date(), "l, d F Y H:i:s"));
                    if (b.currentRefreshTimeRuangan == 0) {
                        b.currentRefreshTimeRuangan = b.refreshTime
                    }
                    b.currentRefreshTimeRuangan--;
                    a.set("refreshTime", b.currentRefreshTimeRuangan)
                },
                interval: 1000
            };
            Ext.TaskManager.start(b.task)
        }
    },
    destroy: function () {
        var a = this;
        Ext.TaskManager.stop(a.task);
        a.callParent()
    }
});
Ext.define("antrian.poli.Display.List", {
    extend: "com.Grid",
    alias: "widget.antrian-poli-display-list",
    controller: "antrian-poli-display-list",
    penggunaAksesPos: [],
    flex: 1,
    viewModel: {
        stores: {
            store: {
                type: "ruangan-antrian-store"
            }
        }
    },
    cls: "x-br-top",
    border: true,
    initComponent: function () {
        var a = this;
        a.dockedItems = [{
            xtype: "toolbar",
            dock: "top",
            style: "background:#19c5bf;border:1px #CCC solid",
            items: [{
                html: '<span style="font-weight:bold;font-size:14px">Display Antrian Poliklinik</span>'
            }, "->", {
                xtype: "combobox",
                name: "POS_ANTRIAN",
                allowBlank: false,
                enterFocus: true,
                reference: "posantrian",
                enforceMaxLength: true,
                forceSelection: true,
                validateOnBlur: true,
                displayField: "DESKRIPSI",
                valueField: "NOMOR",
                queryMode: "local",
                typeAhead: true,
                flex: 1,
                emptyText: "[ Pilih Pos Antrian ]",
                store: {
                    model: "antrian.posantrian.Model"
                },
                listeners: {
                    select: "onChangePos"
                }
            }]
        }, {
            xtype: "pagingtoolbar",
            bind: {
                store: "{store}"
            },
            dock: "bottom"
        }], a.columns = [{
            xtype: "templatecolumn",
            align: "left",
            tpl: '<div style="margin-bottom:5px;margin-top:5px;font-weight: 800; font-size:24px; white-space:normal !important;">{ID} | {DESKRIPSI}</div>',
            flex: 1
        }];
        a.callParent(arguments)
    },
    onSetGrid: function () {
        var a = this,
            b = a.down("[reference=posantrian]");
        b.getStore().loadData(a.getAksesPosAntrian())
    },
    getAksesPosAntrian: function () {
        var a = this;
        return a.penggunaAksesPos ? a.penggunaAksesPos : []
    },
    loadData: function () {
        var b = this,
            a = b.getViewModel().get("store");
        a.queryParams = {
            ANTRIAN: "",
            STATUS: 1
        };
        a.load();
        Ext.Ajax.request({
            url: webservice.location + "registrasionline/plugins/getAksesPosAntrian",
            useDefaultXhrHeader: false,
            withCredentials: true,
            success: function (e) {
                var d = Ext.JSON.decode(e.responseText);
                var f = d.data.AKSES_POS_ANTRIAN;
                b.penggunaAksesPos = f;
                b.onSetGrid(f)
            },
            failure: function (d) {
                return []
            }
        })
    }
});
Ext.define("antrian.poli.Display.ListController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-poli-display-list",
    onChangePos: function () {
        var d = this,
            a = d.getReferences(),
            e = a.posantrian.getValue(),
            b = {};
        obj = {
            STATUS: 1,
            ANTRIAN: ""
        };
        if (e) {
            b = {
                ANTRIAN: e
            };
            obj = Ext.apply(obj, b)
        }
        d.getView().load(obj)
    },
    onSearch: function (k, g) {
        var h = this,
            j = h.getViewModel(),
            a = j.get("store");
        parameter = a.getQueryParams();
        a.queryParams = {
            QUERY: g,
            start: 0,
            page: 1
        };
        a.load()
    },
    onClear: function () {
        var e = this,
            f = e.getViewModel(),
            a = f.get("store");
        delete a.queryParams.QUERY;
        delete a.queryParams.start;
        delete a.queryParams.page;
        a.load()
    }
});
Ext.define("antrian.poli.Display.View", {
    extend: "Ext.view.View",
    xtype: "antrian-poli-display-view",
    viewModel: {
        stores: {
            store: {
                type: "antrian-panggilantrianpoli-store"
            }
        }
    },
    layout: {
        type: "vbox",
        align: "stretch"
    },
    loadMask: false,
    autoScroll: true,
    cls: "laporan-main laporan-dataview",
    bind: {
        store: "{store}"
    },
    itemSelector: "div.thumb-wrap",
    tpl: Ext.create("Ext.XTemplate", '<tpl for=".">', '<div class="thumb-wrap big-33 small-50" style="text-align:center;width:100%;display:grid;padding:15px;">', '<a class="thumb" href="#" style="background:#E9967A">', '<div class="thumb-title-container" style="float:right;width:100%">', '<div class="thumb-title"><p style="font-size:22px;">ANTRIAN</p></div>', '<div class="thumb-title">', '<p style="font-size:64px;">{[this.formatNomor(values.nomorantrean)]}</p>', "</div>", "</div>", '<div class="thumb-title-container" style="float:left;width:100%;border-top:2px #DDD solid">', '<div class="thumb-title"><p style="font-size:22px;">NORM. {[this.getFormatNorm(values.norm)]}</p></div>', "</div>", "</a>", "</div>", "</tpl>", {
        formatNomor: function (a) {
            var b = Ext.String.leftPad(a, 3, "0");
            return b
        },
        getFormatNorm: function (e) {
            var a = Ext.String.leftPad(e, 8, "0"),
                f = Ext.String.insert(Ext.String.insert(Ext.String.insert(a, ".", 2), ".", 5), ".", 8);
            return f
        }
    }),
    onLoadRecord: function (a, e) {
        var d = this,
            b = d.getViewModel().get("store");
        if (b) {
            b.setQueryParams({
                GET_ANTRIAN_PANGGIL: 1,
                POLI: a.get("ID"),
                TANGGAL: e,
                start: 0,
                limit: 1
            });
            b.load()
        }
    },
    getStore: function () {
        return this.getViewModel().get("store")
    },
    reload: function () {
        var b = this.getViewModel().get("store");
        if (b) {
            b.reload()
        }
    }
});
Ext.define("antrian.poli.Display.Workspace", {
    extend: "com.Form",
    xtype: "antrian-poli-display-workspace",
    controller: "antrian-poli-display-workspace",
    layout: "fit",
    bodyPadding: 2,
    items: [{
        xtype: "antrian-poli-display-list",
        listeners: {
            itemdblclick: "onPilihPoli"
        }
    }],
    load: function () {
        var a = this,
            b = a.down("antrian-poli-display-list");
        b.loadData()
    }
});
Ext.define("antrian.poli.Display.WorkspaceController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-poli-display-workspace",
    onPilihPoli: function (b, e, a) {
        var d = this;
        d.onProsesDisplayAntrian(e)
    },
    onProsesDisplayAntrian: function (b) {
        var e = this,
            a = e.getView(),
            d = Ext.Date.format(a.getSysdate(), "Y-m-d");
        dialog = a.openDialog("", true, 0, 0, {
            xtype: "antrian-poli-display-display",
            title: "Informasi Antrian Ruangan / Poliklinik",
            ui: "panel-cyan",
            showCloseButton: true,
            hideColumns: true
        }, function (g, h) {
            var f = h.down("antrian-poli-display-display");
            f.onLoadRecord(b, d);
            h.on("close", function () {
                a.onLoadRecord()
            })
        })
    }
});
Ext.define("antrian.poli.List", {
    extend: "com.Grid",
    xtype: "antrian-poli-list",
    controller: "antrian-poli-list",
    penggunaAksesPos: [],
    viewModel: {
        stores: {
            store: {
                type: "antrian-antrianpoli-store"
            },
            storepemanggil: {
                type: "antrian-panggilantrianpoli-store"
            }
        },
        data: {
            tgltemp: undefined,
            tglSkrng: undefined,
            statusWebsocket: "disconnect",
            statusBtnWebsocket: "red",
            isConnect: true,
            aksesResponPasien: true,
            listConfig: {
                autoRefresh: true
            }
        },
        formulas: {
            autoRefreshIcon: function (a) {
                return a("listConfig.autoRefresh") ? "x-fa fa-stop" : "x-fa fa-play"
            },
            tooltipAutoRefresh: function (a) {
                return a("listConfig.autoRefresh") ? "Hentikan Perbarui Otomatis" : "Jalankan Perbarui Otomatis"
            }
        }
    },
    initComponent: function () {
        var a = this;
        a.createMenuContext();
        a.dockedItems = [{
            xtype: "toolbar",
            dock: "top",
            style: "background:#19c5bf;border:1px #CCC solid",
            items: [{
                html: '<span style="font-weight:bold;font-size:14px">Monitoring Antrian Poliklinik</span>'
            }, "->", {
                bind: {
                    html: '<span style="color:{statusBtnWebsocket}">{statusWebsocket}</span>'
                }
            }, {
                xtype: "combobox",
                name: "POLI",
                allowBlank: false,
                enterFocus: true,
                reference: "fpoli",
                enforceMaxLength: true,
                forceSelection: true,
                validateOnBlur: true,
                displayField: "DESKRIPSI",
                valueField: "ID",
                queryMode: "local",
                typeAhead: true,
                emptyText: "[ Pilih Poliklinik ]",
                store: {
                    model: "data.model.Kategori"
                },
                listeners: {
                    select: "onChangeTgl"
                }
            }, {
                xtype: "datefield",
                name: "FTANGGAL",
                format: "d-m-Y",
                reference: "ftanggal",
                listeners: {
                    change: "onChangeTgl"
                }
            }, {
                xtype: "combo",
                reference: "combointerval",
                width: 75,
                store: {
                    fields: ["ID"],
                    data: [{
                        ID: 5
                    }, {
                        ID: 10
                    }, {
                        ID: 15
                    }, {
                        ID: 20
                    }, {
                        ID: 25
                    }, {
                        ID: 30
                    }]
                },
                editable: false,
                displayField: "ID",
                valueField: "ID",
                value: 15,
                bind: {
                    disabled: "{listConfig.autoRefresh}"
                }
            }, {
                xtype: "button",
                enableToggle: true,
                pressed: true,
                bind: {
                    iconCls: "{autoRefreshIcon}",
                    tooltip: "{tooltipAutoRefresh}"
                },
                toggleHandler: "onToggleRefresh"
            }]
        }, {
            xtype: "pagingtoolbar",
            bind: {
                store: "{store}"
            },
            dock: "bottom",
            displayInfo: true,
            items: ["-", {}, {}, {
                xtype: "combobox",
                reference: "statusrespon",
                emptyText: "[ Filter Status ]",
                store: Ext.create("Ext.data.Store", {
                    fields: ["value", "desk"],
                    data: [{
                        value: "ALL",
                        desk: "Semua"
                    }, {
                        value: "1",
                        desk: "Belum Respon"
                    }, {
                        value: "2",
                        desk: "Sudah Respon"
                    }]
                }),
                queryMode: "local",
                displayField: "desk",
                value: 1,
                flex: 1,
                valueField: "value",
                listeners: {
                    select: "onChangeTgl"
                }
            }]
        }], a.columns = [{
            text: "Jenis",
            align: "left",
            menuDisabled: true,
            sortable: false,
            dataIndex: "REF",
            flex: 0.3,
            renderer: "onRenderJenisApp"
        }, {
            text: "No.Antrian",
            dataIndex: "NOMOR",
            menuDisabled: true,
            sortable: false,
            align: "left",
            flex: 0.3,
            renderer: "onAntrian"
        }, {
            text: "Poli Tujuan",
            dataIndex: "POLI",
            menuDisabled: true,
            sortable: false,
            align: "left",
            flex: 0.5,
            renderer: "onPoli"
        }, {
            text: "Tgl. Kunjungan",
            align: "left",
            menuDisabled: true,
            sortable: false,
            dataIndex: "TANGGAL",
            flex: 0.5,
            renderer: "onTglK"
        }, {
            text: "No RM",
            align: "left",
            menuDisabled: true,
            sortable: false,
            dataIndex: "REF",
            flex: 0.5,
            renderer: "onNorm"
        }, {
            text: "Nama",
            dataIndex: "REF",
            align: "left",
            menuDisabled: true,
            sortable: false,
            renderer: "onRenderNama",
            flex: 0.5
        }, {
            text: "Kontak",
            align: "left",
            menuDisabled: true,
            sortable: false,
            dataIndex: "REF",
            flex: 0.5,
            renderer: "onKontak"
        }, {
            text: "Tgl. Lahir",
            dataIndex: "REF",
            menuDisabled: true,
            sortable: false,
            align: "left",
            renderer: "onRenderTgl",
            flex: 0.5
        }, {
            text: "Panggil",
            menuDisabled: true,
            sortable: false,
            xtype: "actioncolumn",
            align: "center",
            width: 100,
            items: [{
                xtype: "tool",
                iconCls: "x-fa fa-bullhorn",
                tooltip: "Panggil Antrian",
                handler: "onClickPanggil"
            }]
        }, {
            text: "Terima",
            menuDisabled: true,
            sortable: false,
            xtype: "actioncolumn",
            align: "center",
            width: 50,
            items: [{
                xtype: "tool",
                iconCls: "x-fa fa-arrow-circle-right",
                tooltip: "Terima Kedatangan Pasien",
                handler: "onClickTerima"
            }]
        }];
        a.callParent(arguments)
    },
    listeners: {
        rowcontextmenu: "onKlikKananMenu"
    },
    createMenuContext: function () {
        var b = this;
        b.menucontext = new Ext.menu.Menu({
            items: [{
                text: "Terima Kedatangan Pasien",
                iconCls: "x-fa fa-arrow-circle-right",
                handler: function () {
                    b.getController().onRespon()
                }
            }, {
                text: "Refresh",
                glyph: "xf021@FontAwesome",
                handler: function () {
                    b.getController().onRefresh()
                }
            }]
        });
        return b.menucontext
    },
    loadData: function () {
        var e = this,
            d = e.getViewModel(),
            a = e.down("[reference=fpoli]"),
            f = e.down("[reference=ftanggal]"),
            b = Ext.Date.format(e.getSysdate(), "Y-m-d");
        a.getStore().loadData(e.getPenggunaRuangan());
        f.setValue(e.getSysdate());
        d.set("tglSkrng", b)
    }
});
Ext.define("antrian.poli.ListController", {
    extend: "Ext.app.ViewController",
    alias: "controller.antrian-poli-list",
    websocket: undefined,
    init: function () {
        var d = this;
        if (window.location.protocol == "http:") {
            var b = "ws"
        } else {
            var b = "wss"
        }
        var a = b + "://" + window.location.hostname + ":8899";
        d.websocket = new WebSocket(a);
        d.websocket.onopen = function (e) {
            d.getViewModel().set("statusWebsocket", "Connected");
            d.getViewModel().set("statusBtnWebsocket", "green")
        };
        d.websocket.onerror = function (e) {
            d.getViewModel().set("statusWebsocket", "Error");
            d.getViewModel().set("statusBtnWebsocket", "red")
        };
        d.websocket.onclose = function (e) {
            d.getViewModel().set("statusWebsocket", "Disconnect");
            d.getViewModel().set("statusBtnWebsocket", "red")
        }
    },
    onsearch: function (j, g) {
        var h = this,
            a = h.getView(),
            d = h.getViewModel(),
            b = d.get("store");
        if (b) {
            b.removeAll();
            parameter = b.getQueryParams();
            b.setQueryParams({
                QUERY: g,
                TANGGALKUNJUNGAN: parameter.TANGGALKUNJUNGAN
            });
            b.load()
        }
    },
    onSelectStatus: function (b, g) {
        var f = this,
            e = f.getViewModel(),
            d = e.get("store");
        if (g.get("value") == "0") {
            delete d.queryParams.STATUS;
            d.removeAll()
        } else {
            d.removeAll();
            parameter = d.getQueryParams();
            d.setQueryParams({
                STATUS: g.get("value"),
                TANGGALKUNJUNGAN: parameter.TANGGALKUNJUNGAN,
                QUERY: parameter.QUERY
            })
        }
        d.load()
    },
    onSelectPos: function (b, g) {
        var f = this,
            e = f.getViewModel(),
            d = e.get("store");
        if (g.get("value") == "0") {
            delete d.queryParams.POS_ANTRIAN;
            d.removeAll();
            d.load()
        } else {
            d.removeAll();
            parameter = d.getQueryParams();
            d.setQueryParams({
                POS_ANTRIAN: g.get("value"),
                TANGGALKUNJUNGAN: parameter.TANGGALKUNJUNGAN,
                STATUS: parameter.STATUS,
                QUERY: parameter.QUERY
            });
            d.load()
        }
    },
    onClear: function () {
        var e = this,
            f = e.getViewModel(),
            a = f.get("store");
        delete a.queryParams.QUERY;
        a.removeAll();
        a.load()
    },
    onToggleRefresh: function (e, g) {
        var f = this,
            a = f.getView(),
            d = f.getReferences(),
            b = Number(d.combointerval.getValue()) * 1000;
        a.setListConfig({
            autoRefresh: e.pressed
        });
        if (e.pressed) {
            a.start(b)
        } else {
            a.stop()
        }
    },
    onChangeTgl: function () {
        var e = this,
            b = e.getReferences(),
            f = b.ftanggal,
            a = b.fpoli.getValue(),
            d = {};
        obj = {
            TANGGAL: Ext.Date.format(f.getValue(), "Y-m-d"),
            POLI: ""
        };
        if (a) {
            d = {
                POLI: a
            };
            obj = Ext.apply(obj, d)
        }
        e.getView().load(obj)
    },
    onRenderJenisApp: function (b, a, e) {
        var d = e.get("REFERENSI").ANTRIAN.REFERENSI.JENIS_APP;
        this.setBackGround(a, e.get("STATUS"));
        return d
    },
    onRenderNama: function (b, a, e) {
        var d = e.get("REFERENSI").ANTRIAN.NAMA;
        this.setBackGround(a, e.get("STATUS"));
        return d
    },
    onAntrian: function (b, a, d) {
        this.setBackGround(a, d.get("STATUS"));
        return Ext.String.leftPad(b, 3, "0")
    },
    onPoli: function (d, b, e) {
        if (e.get("REFERENSI").ANTRIAN.JENIS_APLIKASI == 2) {
            var a = e.get("REFERENSI").ANTRIAN.REFERENSI.POLI_BPJS.NMPOLI
        } else {
            if (e.get("JENIS_APLIKASI") == 5) {
                var a = "-"
            } else {
                var a = e.get("REFERENSI").ANTRIAN.REFERENSI.POLI.DESKRIPSI
            }
        }
        this.setBackGround(b, e.get("STATUS"));
        return a
    },
    onTglK: function (b, a, d) {
        this.setBackGround(a, d.get("STATUS"));
        return Ext.Date.format(d.get("TANGGAL"), "d-m-Y")
    },
    onNorm: function (d, b, e) {
        var a = e.get("REFERENSI").ANTRIAN.NORM;
        this.setBackGround(b, e.get("STATUS"));
        return a
    },
    onKontak: function (b, a, e) {
        var d = e.get("REFERENSI").ANTRIAN.CONTACT;
        this.setBackGround(a, e.get("STATUS"));
        return d
    },
    onRenderTgl: function (b, a, e) {
        var d = e.get("REFERENSI").ANTRIAN.TANGGAL_LAHIR;
        this.setBackGround(a, e.get("STATUS"));
        return d
    },
    setBackGround: function (b, a) {
        if (a == 3) {
            b.style = "background-color:#DDD;color:#000000;font-weight: bold"
        }
        if (a == 2) {
            b.style = "background-color:#0df775;color:#000000;font-weight: bold"
        }
        if (a == 1) {
            b.style = "background-color:red;color:#000000;font-weight: bold"
        }
        if (a == 0) {
            b.style = "background-color:red;color:#000000;font-weight: bold"
        }
    },
    onKlikKananMenu: function (e, j, n, k, l) {
        var o = this,
            m = l.getXY();
        l.stopEvent();
        o.getView().menucontext.showAt(m)
    },
    onClickPanggil: function (a, n, b) {
        var j = this,
            l = j.getView(),
            e = j.getViewModel(),
            m = e.get("store"),
            d = a.getStore().getAt(n),
            f = j.getViewModel().get("tglSkrng"),
            k = j.getReferences(),
            h = Ext.Date.format(d.get("TANGGAL"), "Y-m-d");
        if (k.fpoli.getSelection()) {
            if (f === h) {
                var g = 0;
                if (d.get("STATUS") == 2) {
                    g = 1
                }
                if (d.get("STATUS") == 3) {
                    g = 1
                }
                if (g == 0) {
                    l.notifyMessage("Gagal..Antrian Belum Bisa DI Respon, Belum Di Lakukan Pendaftaran Loket")
                } else {
                    l.setLoading(true);
                    d.set("PANGGIL", 1);
                    d.set("ASAL_REFX", d.get("ASAL_REF"));
                    d.set("REFX", d.get("REF"));
                    d.set("POLIX", d.get("POLI"));
                    d.set("NOMORX", d.get("NOMOR"));
                    d.set("TANGGALX", h);
                    d.set("STATUSX", 2);
                    d.save({
                        callback: function (q, p, r) {
                            if (r) {
                                l.notifyMessage("Data Berhasil Di Respon");
                                var o = {
                                    pesan: "Silahkan Ke Poliklinik",
                                    poli: q.get("POLI"),
                                    nmpoli: k.fpoli.getSelection().get("DESKRIPSI"),
                                    nomor: q.get("NOMOR"),
                                    act: "PANGGIL_POLI"
                                };
                                if (l.getViewModel().get("statusWebsocket") == "Connected") {
                                    j.websocket.send(JSON.stringify(o))
                                } else {
                                    l.notifyMessage("Koneksi Socket Terputus", "danger-red")
                                }
                                m.reload();
                                l.setLoading(false)
                            } else {
                                l.notifyMessage("Data Gagal Di Respon");
                                l.setLoading(false)
                            }
                        }
                    })
                }
            } else {
                l.notifyMessage("Hanya tanggal Kunjungan Hari ini yang dapat direspon")
            }
        }
    },
    onClickTerima: function (a, n, b) {
        var j = this,
            l = j.getView(),
            e = j.getViewModel(),
            m = e.get("store"),
            d = a.getStore().getAt(n),
            f = j.getViewModel().get("tglSkrng"),
            k = j.getReferences(),
            h = Ext.Date.format(d.get("TANGGAL"), "Y-m-d");
        if (k.fpoli.getSelection()) {
            if (f === h) {
                var g = 0;
                if (d.get("STATUS") == 2) {
                    g = 1
                }
                if (d.get("STATUS") == 3) {
                    g = 1
                }
                if (g == 0) {
                    l.notifyMessage("Gagal..Antrian Belum Bisa DI Respon, Belum Di Lakukan Pendaftaran Loket")
                } else {
                    l.setLoading(true);
                    d.set("TERIMA", 1);
                    d.set("ASAL_REFX", d.get("ASAL_REF"));
                    d.set("REFX", d.get("REF"));
                    d.set("STATUSX", 3);
                    d.save({
                        callback: function (p, o, q) {
                            if (q) {
                                l.notifyMessage("Data Berhasil Di Respon");
                                m.reload();
                                l.setLoading(false)
                            } else {
                                l.notifyMessage("Data Gagal Di Respon");
                                l.setLoading(false)
                            }
                        }
                    })
                }
            } else {
                l.notifyMessage("Hanya tanggal Kunjungan Hari ini yang dapat direspon")
            }
        }
    },
    onRefresh: function () {
        var a = this.getView();
        a.reload()
    }
});
Ext.define("antrian.poli.Workspace", {
    extend: "com.Form",
    xtype: "antrian-poli-workspace",
    layout: "fit",
    bodyPadding: 2,
    items: [{
        xtype: "antrian-poli-list"
    }],
    load: function () {
        var a = this,
            b = a.down("antrian-poli-list");
        b.loadData()
    }
});
Ext.define("antrian.Service", {
    extend: "com.Service",
    xtype: "antrian-service",
    serviceName: "registrasionline",
    scope: this,
    animateTarget: null,
    showError: true,
    privates: {
        request: function (k, h, f, j) {
            var g = this;
            Ext.Ajax.request({
                url: webservice.location + "registrasionline/" + k,
                method: h,
                jsonData: f,
                success: function (b, a) {
                    if (Ext.isFunction(j)) {
                        j(true, Ext.JSON.decode(b.responseText), a)
                    }
                },
                failure: function (l, d) {
                    if (g.showError) {
                        try {
                            var m = Ext.JSON.decode(l.responseText),
                                b = m.detail ? (Ext.isObject(m.detail) ? Ext.JSON.encode(m.detail) : m.detail) : "Koneksi Gagal",
                                a = Ext.create("Ext.window.MessageBox", {
                                    ui: "window-red",
                                    title: l.status + " - " + l.statusText,
                                    header: {
                                        padding: 5
                                    }
                                }),
                                o = {
                                    msg: b,
                                    buttons: Ext.MessageBox.OK,
                                    scope: g.scope,
                                    icon: Ext.Msg.ERROR
                                };
                            if (g.animateTarget) {
                                o.animateTarget = g.animateTarget
                            }
                            a.show(o)
                        } catch (n) {
                            var a = Ext.create("Ext.window.MessageBox", {
                                    ui: "window-red",
                                    title: "Error",
                                    header: {
                                        padding: 5
                                    }
                                }),
                                o = {
                                    msg: "Koneksi Gagal",
                                    buttons: Ext.MessageBox.OK,
                                    scope: g.scope,
                                    icon: Ext.Msg.ERROR
                                };
                            if (g.animateTarget) {
                                o.animateTarget = g.animateTarget
                            }
                            a.show(o)
                        }
                    }
                    if (Ext.isFunction(j)) {
                        j(false, l, d)
                    }
                }
            })
        }
    },
    enabledShowError: function (a) {
        this.showError = a
    }
});
