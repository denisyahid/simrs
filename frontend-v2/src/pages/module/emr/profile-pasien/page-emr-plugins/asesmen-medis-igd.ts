export function anamnesa(): any {
    return [
        {
            "title": "Keluhan Utama",
            "model": "keluhanUtama"
        },
        {
            "title": "Riwayat Penyakit Sekarang",
            "model": "riwayatPenyakitSekarang"
        },
        {
            "title": "Riwayat Penyakit Dahulu",
            "model": "riwayatPenyakitDahulu"
        }
    ]
}
export function listFaktorResJantung(): any {
    return[
        {
            "title": "Hipertensi",
            "value": ["Ya", "Terkontrol", "Tidak Terkontrol", "Tidak"],
            "model": "hipertensi",
        },
        {
            "title": "Diabetes Melitus",
            "value": ["Ya", "Terkontrol", "Tidak Terkontrol", "Tidak"],
            "model": "diabetesMelitus",
        },
        {
            "title": "Dyslipidemia (kelainan kolestrol darah)",
            "value": ["Ya", "Terkontrol", "Tidak Terkontrol", "Tidak"],
            "model": "dyslipidemia",
        },
        {
            "title": "Riwayat serangan jantung dini pada orang tua (pria <55 tahun atau wanita <65 tahun)",
            "value": ["Ya", "Tidak"],
            "model": "riwayatSeranganJantungOrangtua",
        },  
    ]
}
export function keadaanUmum(): any {
    return [
        {
            "title": "1. Keadaan Umum",
            "value": ["Baik", "Sakit Ringan", "Sakit Sedang", "Sakit Berat"],
            "model": 'keadaanUmum'
        } 
    ]
}

export function pemeriksaanFisik(): any {
    return [
        {
            "title": "2. Tanda Vital",
            "value": [
                {
                    "subTitle": "Tekanan Darah",
                    "model": "tekananDarah",
                },
                {
                    "subTitle": "Frekuensi Nadi",
                    "model": "frekuensiNadi",
                },
                {
                    "subTitle": "Suhu",
                    "model": "suhu",
                },
                {
                    "subTitle": "Frekuensi Nafas",
                    "model": "frekuensiNafas",
                },
                {
                    "subTitle": "Skor Nyeri",
                    "model": "skorNyeri",
                },
            ]
        },
        {
            "title": "3. Antropometri",
            "value": [
                {
                    "subTitle": "Berat Badan",
                    "model": "beratBadan",
                },
                {
                    "subTitle": "Tinggi Badan",
                    "model": "tinggiBadan",
                },
                {
                    "subTitle": "Lingkar Perut",
                    "model": "lingkarPerut",
                },
                {
                    "subTitle": "IMT",
                    "model": "imt",
                },
            ]
        },
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
            "parameter": "v",
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
            "poin": "15",
        },
        {
            "des": "Apatis (12-13)",
            "model": "rangeKesadaran",
            "poin": "13",
        },
        {
            "des": "Somnolen (10-11)",
            "model": "rangeKesadaran",
            "poin": "11",
        },
        {
            "des": "Delirium (7-9)",
            "model": "rangeKesadaran",
            "poin": "9",
        },
        {
            "des": "Stupar (4-6)",
            "model": "rangeKesadaran",
            "poin": "6",
        },
        {
            "des": "Koma ( < 3)",
            "model": "rangeKesadaran",
            "poin": "2",
        },
    ]
}
export function keadaanUmum2() : any {
    return [
        {
            "title": "5. Mata",
            "value": [
                {
                    "subTitle": "Konjungtiva",
                    "model": "mataKonjungtiva",
                    "item": ["Normal", "Amnesia", "Lainnya"],
                    "optional": [{
                        'model': "ketKonjungtiva"
                    }]
                },
                {
                    "subTitle": "Sklera",
                    "model": "mataSklera",
                    "item": ["Normal", "Amnesia", "Lainnya"],
                    "optional": [{
                        'model': "ketSklera"
                    }]
                },
            ]
        },
        {
            "title": "6. Hidung",
            "value": [
                {
                    "subTitle": "",
                    "model": "hidung",
                    "item": ["Normal", "Nafas Cuping Hidung", "Lainnya"],
                    "optional": [{
                        'model': "ketHidung"
                    }]
                },
            ]
        },
        {
            "title": "7. Bibir",
            "value": [
                {
                    "subTitle": "",
                    "model": "bibir",
                    "item": ["Normal", "Sianosis", "Lainnya"],
                    "optional": [{
                        'model': "ketBibir"
                    }]
                },
            ]
        },
        {
            "title": "8. Thorax",
            "value": [
                {
                    "subTitle": "",
                    "model": "thorax",
                    "item": ["Simetris", "Tidak Simetris", "Barel Chest", "Pigeon Chest", "Pictus Excavatum",
                        "Pigeon Chest", "Picuts Excavatum", "Retraksi Dinding Dada",
                    ],
                    "optional": [{
                        'model': "ketThorax"
                    }]
                },
            ]
        },
        {
            "title": "9. Cor",
            "value": [
                {
                    "subTitle": "Inspeksi",
                    "model": "inspeksi",
                    "item": [" Ictus cordis tidak tampak", "Ictus cordis tampak", "Lainnya"],
                    "optional": [{
                        'model': "ketInspeksi"
                    }]
                },
                {
                    "subTitle": "Palpasi",
                    "model": "palpasi",
                    "item": [" Ictus cordis tidak teraba", "Ictus cordis teraba", "Lainnya"],
                    "optional": [{
                        'model': "ketPalpasi"
                    }]
                },
                {
                    "subTitle": "Perkusi",
                    "model": "perkusi",
                    "item": "",
                    "optional": [{
                        'model': "ketPerkusi"
                    }]
                },
                {
                    "subTitle": "Auskultasi",
                    "model": "auskultasi",
                    "item": ["S1 dan S2 Reguler", "Murmur", "Lainnya"],
                    "optional": [{
                        'model': "ketAuskultasi"
                    }]
                },
            ]
        },
        {
            "title": "10. Paru",
            "value": [
                {
                    "subTitle": "Inspeksi",
                    "model": "inspeksi",
                    "item": [" Ictus cordis tidak tampak", "Ictus cordis tampak", "Lainnya"],
                    "optional": [{
                        'model': "ketInspeksi"
                    }]
                },
                {
                    "subTitle": "Palpasi",
                    "model": "palpasi",
                    "item": [" Fremitus Kiri dan Kanan Sama", "Fremitus Kiri Menurun", "Fremitus Kiri Meningkat",
                        "Fremitus Kanan Menurun", "Fremitus Kanan Meningkat", "Lainnya"],
                    "optional": [{
                        'model': "ketPalpasi"
                    }]
                },
                {
                    "subTitle": "Perkusi",
                    "model": "perkusi",
                    "item": ["Sonor", "Hipersonor", "Lainnya"],
                    "optional": [{
                        'model': "ketPerkusi"
                    }]
                },
                {
                    "subTitle": "Auskultasi",
                    "model": "auskultasi",
                    "item": ["Ronki", "Wheezing"],
                    "optional": [
                        {
                            'model': "ketRonki"
                        },
                        {
                            'model': "ketWheezing"
                        }
                    ]
                },
            ]
        },
        {
            "title": "11. Abdomen",
            "value": [
                {
                    "subTitle": "a. Inspeksi",
                    "model": "inspeksi",
                    "item": ["Datar", "Cekung", "Lainnya"],
                    "optional": [{
                        'model': "ketInspeksi"
                    }]
                },
                {
                    "subTitle": "b. Palpasi",
                    "model": "palpasi",
                    "item": ["Normal", "Nyeri Tekan"],
                    "optional": [{
                        'model': "ketPalpasi"
                    }]
                },
                {
                    "subTitle": "c. Hepar",
                    "model": "hepar",
                    "item": [],
                    "optional": [{
                        'model': "ketHepar"
                    }]
                },
                {
                    "subTitle": "d. Limpa",
                    "model": "limpa",
                    "item": [],
                    "optional": [{
                        'model': "ketLimpa"
                    }]
                },
                {
                    "subTitle": "d. Perkusi",
                    "model": "perkusi",
                    "item": ["Timpani", "Hipertimpani", "Pekak"],
                    "optional": [
                        {
                            'model': "ketPerkusi"
                        },
    
                    ]
                },
            ]
        },
        {
            "title": "12. Ekstrmitas",
            "value": [
                {
                    "subTitle": "",
                    "model": "ekstermitas",
                    "item": ["Akral Hangat", "Akral Dingin", "CRT < 2 Detik", "CRT > 2 Detik", "Pitting Edem"],
                    "optional": [{
                        'model': "ketInspeksi"
                    }]
                },
            ]
        },
        {
            "title": "13. Kekuatan Otot",
            "value": [
                {
                    "subTitle": "",
                    "model": "kekuatanOtot",
                    "item": [],
                    "optional": [{
                        'model': "keKekuatanOtot"
                    }]
                },
            ]
        },
        {
            "title": "14. Pemeriksaan Neurologi",
            "value": [
                {
                    "subTitle": "",
                    "model": "pemeriksaanNeurologi",
                    "item": [],
                    "optional": [{
                        'model': "pemeriksaanNeurologi"
                    }]
                },
            ]
        },
    ]
}
export function pemeriksaanPenunjang(): any {
    return [
        {
            "title": "EKG",
            "model": "pemeriksaanEkg",
            "value": "Ya",
            "desc": "ketEKG"
        },
        {
            "title": "Echocardiography",
            "model": "pemeriksaanEchocardiography",
            "value": "Ya",
            "desc": "ketEchocardiography"
        },
        {
            "title": "Radiologi",
            "model": "pemeriksaanRadiologi",
            "value": "Ya",
            "desc": "ketRadiologi"
        },
    ]
}
export function Pragnosis(): any {
    return[
        {
            "title": "Bonam",
            "model": "Pragnosis_" 
        },
        {
            "title": "Dubia and Bonam",
            "model": "Pragnosis_" 
        },
        {
            "title": "Dubia and Malam",
            "model": "Pragnosis_" 
        },
        {
            "title": "Malam",
            "model": "Pragnosis_" 
        },
    ]
}
export function Edukasi(): any {
    return [
        {
            "title": "Pasien",
            "model": "Edukasi_" 
        },
        {
            "title": "Keluarga Pasien",
            "model": "Edukasi_" 
        },
        {
            "title": "Tidak ada memberikan informasi Edukasi kepada pasien atau keluarga ", value: "Tidak ada memberikan informasi Edukasi kepada pasien atau keluarga",
            "model": "Edukasi_" 
        },
    ]
}
export function pilihanRencana(): any {
    return [
        {
            "title": "Pulang",
            "model": "pilihanRencana_" 
        },
        {
            "title": "Izin Dokter",
            "model": "pilihanRencana_" 
        },
        {
            "title": "Atas Permintaan Sendiri",
            "model": "pilihanRencana_" 
        },
    ]
}
export function keadaanPasien(): any {
    return [
        {
            "title": "Membaik",
            "model": "keadaanPasien_" 
        },
        {
            "title": "Memburuk",
            "model": "keadaanPasien_" 
        },
        {
            "title": "Tetap",
            "model": "keadaanPasien_" 
        },
        {
            "title": "Meninggal",
            "model": "keadaanPasien_" 
        },
    ]
}
