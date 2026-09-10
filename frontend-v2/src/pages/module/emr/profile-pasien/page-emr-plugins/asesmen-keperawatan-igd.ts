
export function JenisKasus(): any {
    return [
        {
            "title": "Trauma",
            "model": "JenisKasus"
        },
        {
            "title": "Non Trauma",
            "model": "JenisKasus"
        },
        {
            "title": "Anak",
            "model": "JenisKasus"
        },
    ]
}
export function transportasiIGD(): any {
    return [
        {
            "type": "checkbox",
            "value": [
                {
                    "model": "transportIGD_1",
                    "title": "Datang Sendiri",
                },
                {
                    "model": "transportIGD_1",
                    "title": "Ambulance",
                },
                {
                    "model": "rujukanIGD",
                    "title": "Rujukan Dari",
                },
            ],
            "opsional": {
                "type": "textbox",
                "title": "Rujukan dari",
                "model": "asalRujukan"
            }
        },
        {
            "type": "checkbox",
            "value": [
                {
                    "model": "transportIGD_2",
                    "title": "Auto Anamnesa",
                },
                {
                    "model": "transportIGD_2",
                    "title": "Allo Anamnesa",
                },
            ],
            "opsional": {
                "type": "textbox",
                "title": "Diantar",
                "model": "diantar"
            }
        },
    ]
}
export function riwayatKesehatan(): any {
    return [
        {
            "title": "Keluhan utama",
            "model": "keluhanUtama",
            "type": "textarea",
        },
        {
            "title": "Keluhan saat ini",
            "model": "keluhanSaatIni",
            "type": "textarea",
        },
        {
            "title": "Riwayat penyakit dahulu",
            "model": "riwayatPenyakit",
            "type": "textarea",
        },
    ]
}
export function riwayatAlergi(): any {
    return [
        {
            "title": "Ya",
            "model": "alergi",
            "type": "checkbox",
        },
        {
            "title": "",
            "model": "ketAlergi",
            "type": "textbox",
        },
        {
            "title": "Tidak",
            "model": "alergi",
            "type": "checkbox",
        },
    ]
}
export function tandaVital(): any {
    return [
        {
            "title": "TD",
            "model": "tekananDarah",
            "satuan": "mmHg"
        },
        {
            "model": "RR",
            "title": "RR",
            "satuan": "x/min"
        },
        {
            "title": "HR",
            "model": "nadi",
            "satuan": ""
        },
        {
            "title": "Suhu",
            "model": "suhu",
            "satuan": "°C"
        },
        {
            "title": "SPO2",
            "model": "SPO2",
            "satuan": ""
        },
        {
            "title": "TB",
            "model": "tinggiBadan",
            "satuan": "Cm"
        },
        {
            "title": "BB",
            "model": "beratBadan",
            "satuan": "Kg"
        },
        {
            "title": "LP",
            "model": "lingkarPerut",
            "satuan": ""
        },
    ]
}
export function keadaanUmum(): any {
    return [
        {
            "title": "Sakit Ringan",
            "model": "keadaanUmum"
        },
        {
            "title": "Sakit Sedang",
            "model": "keadaanUmum"
        },
        {
            "title": "Sakit Berat",
            "model": "keadaanUmum"
        },
    ]
}
export function kualitasNyeri(): any {
    return [
        {
            "title": "Nyeri Tumpul",
            "model": "kualitasNyeri"
        },
        {
            "title": "Nyeri Tajam",
            "model": "kualitasNyeri"
        },
        {
            "title": "Panas/ Terbakar",
            "model": "kualitasNyeri"
        },
        {
            "title": "Lainnya",
            "model": "kualitasNyeri"
        },
    ]
}
export function Menjalar(): any {
    return [
        {
            "type": "checkbox",
            "value": [
                {
                    "model": "menjalar",
                    "title": "Tidak",
                },
                {
                    "model": "menjalar",
                    "title": "Ya, Sebutkan",
                }
            ],
            "opsional": {
                "type": "textbox",
                "title": "Keterangan",
                "model": "keteranganMenjalar"
            }
        },
    ]
}
export function listfrekuensiNyeri(): any {
    return [
        {
            "title": "Jarang",
            "model": "frekuensiNyeri",
        },
        {
            "title": "Hilang / Timbul",
            "model": "frekuensiNyeri",
        },
        {
            "title": "Terus Menerus",
            "model": "frekuensiNyeri",
        },

    ]
}

export function resikoJatuh(): any {
    return [
        {
            "title": "1. Perhatikan cara berjalan pasien saat akan duduk dikursi, apakah pasien tampak seimbang(sempoyongan/limbung) ?",
            "value": [
                {
                    "subTitle": "Tidak",
                    "model": "caraBerjalanNormalatauTidak",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Ya",
                    "model": "caraBerjalanNormalatauTidak",
                    "type": "checkbox",
                },
            ]
        },
        {
            "title": "2. Apakah pasien memegang pinggiran kursi atau meja atau benda lain sebagai penopang saat akan duduk ?"
            ,
            "value": [
                {
                    "subTitle": "Tidak",
                    "model": "perluPenompang",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Ya",
                    "model": "perluPenompang",
                    "type": "checkbox",
                },
            ]
        },
        {
            "title": "3. Hasil",
            "value": [
                {
                    "subTitle": "Tidak berisiko (tidak ditemukan a dan b)",
                    "model": "hasilResikoJatuh",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Risiko tinggi (ditemukan a dan b)",
                    "model": "hasilResikoJatuh",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Risiko rendah-sedang (ditemukan a atau b)",
                    "model": "hasilResikoJatuh",
                    "type": "checkbox",
                },
            ]
        },


    ]
}
export function listNutrisional(): any {
    return {
        "pertama": [{
            "no": 1,
            "parameter": "Apakah ada penurunan berat badan yang tidak diinginkan selama 6 bulan terakhir ?",
            "pengkajian": [
                {
                    "model": "penurunanBB",
                    "title": "Tidak",
                    "keterangan": "",
                    "poin": 0,
                    "value":
                    {
                        "poin": 0,
                        "keterangan": "Tidak",
                    }

                },
                {
                    "model": "penurunanBB",
                    "title": "Tidak Yakin",
                    "poin": 2,
                    "value":
                    {
                        "poin": 2,
                        "keterangan": "Tidak Yakin",
                    }

                },
                {
                    "model": "penurunanBB",
                    "title": "Ya,1-5 Kg",
                    "keterangan": "",
                    "poin": 1,
                    "value":
                    {
                        "poin": 1,
                        "keterangan": "Ya,1-5 Kg",
                    }

                },
                {
                    "model": "penurunanBB",
                    "title": "6-10 Kg",
                    "keterangan": "",
                    "poin": 2,
                    "value":
                    {
                        "poin": 2,
                        "keterangan": "6-10 Kg",
                    }

                },
                {
                    "model": "penurunanBB",
                    "title": "11-15 Kg",
                    "keterangan": "",
                    "poin": 3,
                    "value":
                    {
                        "poin": 3,
                        "keterangan": "11-15 Kg",
                    }

                },
                {
                    "model": "penurunanBB",
                    "title": "> 15 Kg",
                    "keterangan": "",
                    "poin": 4,
                    "value":
                    {
                        "poin": 4,
                        "keterangan": "> 15 Kg",
                    }

                },
            ],
        }],
        "kedua": [{
            "no": 2,
            "parameter": "Apakah asupan makan menurun yang dikarenakan adanya penurunan nafsu makan/kesulitan menerima makan ?",
            "pengkajian": [
                {
                    "model": "penurunanNafsuMakan",
                    "title": "Tidak",
                    "keterangan": "",
                    "poin": 0,
                    "value":
                    {
                        "poin": 0,
                        "keterangan": "Tidak",
                    }

                },
                {
                    "model": "penurunanNafsuMakan",
                    "title": "Ya",
                    "keterangan": "",
                    "poin": 1,
                    "value":
                    {
                        "poin": 1,
                        "keterangan": "Ya",
                    }

                },
            ],
        }],
    }
}
export function descripJumlah(): any {
    return [
        {
            "rangeNilai": "Skor 0 - 1",
            "desc": ": Tidak Beresiko",
        },
        {
            "rangeNilai": "Skor 2 - 3",
            "desc": ": Beresiko (Asuhan Gizi Oleh Dietizen)",
        },
        {
            "rangeNilai": "Skor > 4",
            "desc": ": Malnutrisi (Asuhan Gizi oleh Dietisen)",
        },
    ]
}
export function psikologis(): any {
    return [
        {
            "title": "Depresi",
            "model": "psikologis",
        },
        {
            "title": "Takut",
            "model": "psikologis",
        },
        {
            "title": "Agresif",
            "model": "psikologis",
        },
        {
            "title": "Melukai diri sendiri",
            "model": "psikologis",
        },
        {
            "title": "Tidak ada gejala",
            "model": "psikologis",
        },
    ]
}
export function kesulitan(): any {
    return [
        { label: "Tidak Ada", value: "Tidak Ada" },
        { label: "Ada, Jelaskan", value: "Ada" },
    ]
}
export function bahasa(): any {
    return [
        { label: "Indonesia", value: "Indonesia" },
        { label: "Mandarin", value: "Mandarin" },
        { label: "Inggris", value: "Inggris" },
        { label: "Lainnya", value: "lainnya" },
    ]
}
export function penterjemah(): any {
    return [
        { label: "Tidak Perlu", value: "Tidak Perlu" },
        { label: "Perlu", value: "Perlu" },
        { label: "Lainnya", value: "Lainnya" },
    ]
}
export function hambatan(): any {
    return [
        {
            "title": "Tidak ada hambatan edukasi",
            "model": "hambatan_",
        },
        {
            "title": "Gangguan Penglihatan",
            "model": "hambatan_",
        },
        {
            "title": "Gangguan Pendengaran",
            "model": "hambatan_",
        },
        {
            "title": "Gangguan Emosional",
            "model": "hambatan_",
        },
        {
            "title": "Gangguan Proses Pikir",
            "model": "hambatan_",
        },
        {
            "title": "Hambatan Bahasa",
            "model": "hambatan_",
        },
        {
            "title": "Kemampuan Membaca",
            "model": "hambatan_",
        },
        {
            "title": "Motivasi Belajar",
            "model": "hambatan_",
        },
        {
            "title": "Batas Jasmani dan Kognitif",
            "model": "hambatan_",
        },
        {
            "title": "Hambatan Lainnya",
            "model": "hambatan_",
        },
    ]
}
export function kebutuhanEdukasi(): any {
    return [
        {
            "title": "Kondisi Medis dan Diagnosa",
            "model": "kebutuhanEdukasi_",
            "value": {
                "code": "KMD",
                "description": "Kondisi Medis dan Diagnosa"
            }
        },
        {
            "title": "Rencana Pengobatan/Medis",
            "model": "kebutuhanEdukasi_",
            "value": {
                "code": "RPM",
                "description": "Rencana Pengobatan/Medis"
            }
        },
        {
            "title": "Penggunaan alat medis/Keperawatan",
            "model": "kebutuhanEdukasi_",
            "value": {
                "code": "PAM",
                "description": "Penggunaan alat medis/Keperawatan"
            }
        },
        {
            "title": "Penggunaan obat secara aman dan efektif/ Farmasi",
            "model": "kebutuhanEdukasi_",
            "value": {
                "code": "POA",
                "description": "Penggunaan obat secara aman dan efektif/ Farmasi"
            }
        },
        {
            "title": "Rencana Keperawatan",
            "model": "kebutuhanEdukasi_",
            "value": {
                "code": "RK",
                "description": "Rencana Keperawatan"
            }
        },
        {
            "title": "Manajemen nyeri/Keperawatan",
            "model": "kebutuhanEdukasi_",
            "value": {
                "code": "MK",
                "description": "Manajemen nyeri/Keperawatan"
            }
        },
        {
            "title": "Perawatan luka/Keperawatan",
            "model": "kebutuhanEdukasi_",
            "value": {
                "code": "PL",
                "description": "Perawatan luka/Keperawatan"
            }
        },
        {
            "title": "Diet atau nutrisi/Dokter Gizi Ahli Gizi",
            "model": "kebutuhanEdukasi_",
            "value": {
                "code": "DN",
                "description": "Diet atau nutrisi/Dokter Gizi Ahli Gizi"
            }
        },
        {
            "title": "Interaksi obat dan makanan/Farmasi",
            "model": "kebutuhanEdukasi_",
            "value": {
                "code": "IOM",
                "description": "Interaksi obat dan makanan/Farmasi"
            }
        },
        {
            "title": "Teknik rehabilitasi/Rehabilitasi Medis",
            "model": "kebutuhanEdukasi_",
            "value": {
                "code": "TRM",
                "description": "Teknik rehabilitasi/Rehabilitasi Medis"
            }
        },
        {
            "title": "Pengisian inform consent/Keperawatan",
            "model": "kebutuhanEdukasi_",
            "value": {
                "code": "PIC",
                "description": "Pengisian inform consent/Keperawatan"
            }
        },
        {
            "title": "Teknik cuci tangan/Keperawatan",
            "model": "kebutuhanEdukasi_",
            "value": {
                "code": "TCT",
                "description": "Teknik cuci tangan/Keperawatan"
            }
        },
        {
            "title": "Edukasi Resiko Jatuh / Keperawatan",
            "model": "kebutuhanEdukasi_",
            "value": {
                "code": "ERJ",
                "description": "Edukasi Resiko Jatuh / Keperawatan"
            }
        },
        {
            "title": "Perawatan Lanjutan setelah pasien pulang/ Keperawatan",
            "model": "kebutuhanEdukasi_",
            "value": {
                "code": "PLP",
                "description": "Perawatan Lanjutan setelah pasien pulang/ Keperawatan"
            }
        },
    ]
}
export function masalahKeperawatan(): any {
    return [
        {
            "nama": "Penurunan curah jantung",
            "model": "masalahKeperawatan_",
            "value": {
                "code": "PCJ",
                "description": "Penurunan curah jantung"
            }
        },
        {
            "nama": "Perfusi jaringan perifer tidak efektif",
            "model": "masalahKeperawatan_",
            "value": {
                "code": "PJP",
                "description": "Perfusi jaringan perifer tidak efektif"
            }
        },
        {
            "nama": "Perfusi jaringan serebral tidak efektif",
            "model": "masalahKeperawatan_",
            "value": {
                "code": "PJS",
                "description": "Perfusi jaringan serebral tidak efektif"
            }
        },
        {
            "nama": "Gangguan ventilasi spontan",
            "model": "masalahKeperawatan_",
            "value": {
                "code": "GVS",
                "description": "Gangguan ventilasi spontan"
            }
        },
        {
            "nama": "Bersihkan jalan nafas tidak efektif",
            "model": "masalahKeperawatan_",
            "value": {
                "code": "BJN",
                "description": "Bersihkan jalan nafas tidak efektif"
            }
        },
        {
            "nama": "Pola nafas tidak efektif",
            "model": "masalahKeperawatan_",
            "value": {
                "code": "PNT",
                "description": "Pola nafas tidak efektif"
            }
        },
        {
            "nama": "Gangguan pertukaran gas",
            "model": "masalahKeperawatan_",
            "value": {
                "code": "GPG",
                "description": "Gangguan pertukaran gas"
            }
        },
        {
            "nama": "Nyeri akut",
            "model": "masalahKeperawatan_",
            "value" : {
                "code" : "NA",
                "description" : "Nyeri akut"
            }
        },
        {
            "nama": "Nyeri Kronik",
            "model": "masalahKeperawatan_",
            "value": {
                "code": "NK",
                "description": "Nyeri Kronik"
            }
        },
        {
            "nama": "Kekurangan volume cairan",
            "model": "masalahKeperawatan_",
            "value": {
                "code": "KVC",
                "description": "Kekurangan volume cairan"
            }
        },
        {
            "nama": "Kelebihan volume cairan",
            "model": "masalahKeperawatan_",
            "value": {
                "code": "BVC",
                "description": "Kelebihan volume cairan"
            }
        },
        {
            "nama": "Mual",
            "model": "masalahKeperawatan_",
            "value": {
                "code": "MUAL",
                "description": "Mual"
            }
        },
        {
            "nama": "Hipertermia",
            "model": "masalahKeperawatan_",
            "value": {
                "code": "HPR",
                "description": "Hipertermia"
            }
        },
        {
            "nama": "Hipotermia",
            "model": "masalahKeperawatan_",
            "value": {
                "code": "HPO",
                "description": "Hipotermia"
            }
        },
        {
            "nama": "Kerusakan integritas kulit",
            "model": "masalahKeperawatan_",
            "value": {
                "code": "KIK",
                "description": "Kerusakan integritas kulit"
            }
        },
        {
            "nama": "Diare",
            "model": "masalahKeperawatan_",
            "value": {
                "code": "DIARE",
                "description": "Diare"
            }
        },
        {
            "nama": "Konstipasi",
            "model": "masalahKeperawatan_",
            "value" : {
                "code" : "KTI",
                "description" : "Konstipasi"
            }
        },
        {
            "nama": "Ansietas",
            "model": "masalahKeperawatan_",
            "value" : {
                "code" : "ASS",
                "description" : "Ansietas"
            }
        },
        {
            "nama": "Kurang Pengetahuan",
            "model": "masalahKeperawatan_",
            "value" : {
                "code" : "KP",
                "description" : "Kurang Pengetahuan"
            }
        },
        {
            "nama": "Hiperglikemi",
            "model": "masalahKeperawatan_",
            "value" : {
                "code" : "HGI",
                "description" : "Hiperglikemi"
            }
        },
        {
            "nama": "Hipoglikemi",
            "model": "masalahKeperawatan_",
            "value" : {
                "code" : "HLI",
                "description" : "Hiperglikemi"
            }
        },
    ]
}
export function Rencana(): any {
    return [
        {
            "nama": "Head tilt",
            "model": "Rencana_",
            "value": {
                "code": "HT",
                "description": "Head tilt"
            }
        },
        {
            "nama": "Chin lift",
            "model": "Rencana_",
            "value": {
                "code": "CT",
                "description": "Chin lift"
            }
        },
        {
            "nama": "Jaw trust", "model": "Rencana_",
            "value": {
                "code": "JWT",
                "description": "Jaw trust"
            }
        },
        {
            "nama": "Posisikan pasien", "model": "Rencana_",
            "value": {
                "code": "PP",
                "description": "Posisikan pasien"
            }
        },
        {
            "nama": "Kaji airway,breathing,circulation dan disability",
            "model": "Rencana_",
            "value": {
                "code": "ABCD",
                "description": "Kaji airway,breathing,circulation dan disability"
            }
        },
        {
            "nama": "Kaji nyeri", "model": "Rencana_",
            "value": {
                "code": "KN",
                "description": "Kaji nyeri"
            }
        },
        {
            "nama": "Kaji tingkat kecemasan/ansietas", "model": "Rencana_",
            "value": {
                "code": "cemas",
                "description": "Kaji tingkat kecemasan/ansietas"
            }
        },
        {
            "nama": "Lakukan resusitasi jantung paru", "model": "Rencana_",
            "value": {
                "code": "LRJ",
                "description": "Lakukan resusitasi jantung paru"
            }
        },
        {
            "nama": "Lakukan penghisapan sekret", "model": "Rencana_",
            "value": {
                "code": "LPS",
                "description": "Lakukan penghisapan sekret"
            }
        },
        {
            "nama": "Lakukan perawatan luka",
            "model": "Rencana_",
            "value": {
                "code": "LPL",
                "description": "Lakukan perawatan luka"
            }
        },
        {
            "nama": "Pantau intake-output cairan", "model": "Rencana_",
            "value": {
                "code": "PIC",
                "description": "Pantau intake-output cairan"
            }
        },
        {
            "nama": "Pasangan restrain (bila diperlukan)",
            "model": "Rencana_",
            "value": {
                "code": "PR",
                "description": "Pasangan restrain (bila diperlukan)"
            }
        },
        {
            "nama": "Beri minum air hangat",
            "model": "Rencana_",
            "value": {
                "code": "MIM",
                "description": "Beri minum air hangat"
            }
        },
        {
            "nama": "Libatkan keluarga dalam perencanaan",
            "model": "Rencana_",
            "value": {
                "code": "LKP",
                "description": "Libatkan keluarga dalam perencanaan"
            }
        },
        {
            "nama": "Manajemen nyeri", "model": "Rencana_",
            "value": {
                "code": "MN",
                "description": "Manajemen nyeri"
            }
        },
        {
            "nama": "Pasangan bed site monitor",
            "model": "Rencana_",
            "value": {
                "code": "PBS",
                "description": "Pasangan bed site monitor"
            }
        },
        { "nama": "Pasangan Orophraingeal Airway",
         "model": "Rencana_",
         "value": {
            "code": "POA",
            "description": "Pasangan Orophraingeal Airway"
        }

        },
        { "nama": "Pasangan bidai", "model": "Rencana_",
        "value": {
            "code": "PBI",
            "description": "Pasangan bidai"
        }
    },
        { "nama": "Membersihkan luka", "model": "Rencana_",
        "value": {
            "code": "ML",
            "description": "Membersihkan luka"
        }
    },
    ]
}

export function KondisiPasien(): any {
    return [
        { "nama": "Membaik" },
        { "nama": "Memburuk" },
        { "nama": "Kritis" },
        { "nama": "Meinggal" },
        { "nama": "Stabil" },
    ]
}
export function TindakLanjut(): any {
    return [
        { "nama": "Pulang" },
        { "nama": "Rawat Inap" },
        { "nama": "Rujuk" },
        { "nama": "Pulang Paksa" },
    ]
}
export function kesadaran(): any {
    return [
        {
            "no": 1,
            "parameter": "E",
            "nilai": [
                {
                    "model": "kesadaranE",
                    "poin": "1"
                },
                {
                    "model": "kesadaranE",
                    "poin": "2"
                },
                {
                    "model": "kesadaranE",
                    "poin": "3"
                },
                {
                    "model": "kesadaranE",
                    "poin": "4"
                },
            ]
        },
        {
            "no": 2,
            "parameter": "M",
            "nilai": [
                {
                    "model": "kesadaranM",
                    "poin": "6"
                },
                {
                    "model": "kesadaranM",
                    "poin": "5"
                },
                {
                    "model": "kesadaranM",
                    "poin": "4"
                },
                {
                    "model": "kesadaranM",
                    "poin": "3"
                },
                {
                    "model": "kesadaranM",
                    "poin": "2"
                },
                {
                    "model": "kesadaranM",
                    "poin": "1"
                },
            ]
        },
        {
            "no": 3,
            "parameter": "V",
            "nilai": [
                {
                    "model": "kesadaranV",
                    "poin": "5"
                },
                {
                    "model": "kesadaranV",
                    "poin": "4"
                },
                {
                    "model": "kesadaranV",
                    "poin": "3"
                },
                {
                    "model": "kesadaranV",
                    "poin": "2"
                },
                {
                    "model": "kesadaranV",
                    "poin": "1"
                },
            ]
        },
    ]
}
export function descRangeKesadaran(): any {
    return [
        {
            "des": "CMC (14-15)",
            "model": "rangeKesadaran",
            "value": {
                "keterangan": "CMC (14-15)",
                "poin": 15
            },
        },
        {
            "des": "Apatis (12-13)",
            "model": "rangeKesadaran",
            "value": {
                "keterangan": "Apatis (12-13)",
                "poin": 13
            },
        },
        {
            "des": "Somnolen (10-11)",
            "model": "rangeKesadaran",
            "value": {
                "keterangan": "Somnolen (10-11)",
                "poin": 11
            },
        },
        {
            "des": "Delirium (7-9)",
            "model": "rangeKesadaran",
            "value": {
                "keterangan": "Delirium (7-9)",
                "poin": 9
            },
        },
        {
            "des": "Stupar (4-6)",
            "model": "rangeKesadaran",
            "value": {
                "keterangan": "Stupar (4-6)",
                "poin": 6
            },
        },
        {
            "des": "Koma ( <= 3)",
            "model": "rangeKesadaran",
            "value": {
                "keterangan": "Koma ( <= 3)",
                "poin": 3
            },
        },
    ]
}
