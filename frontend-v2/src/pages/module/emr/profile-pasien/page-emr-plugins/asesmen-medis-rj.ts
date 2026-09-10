export function anamnesa() : any {
    return  [
        {
            "title": "Pemeriksaan Penunjang",
            "model": "hasilpemeriksaanpenunjang"
        },
        // {
        //     "title": "Intruksi",
        //     "model": "intruksi" 
        // }
    ]
}
export function anamnesa1() : any {
    return  [
        {
            "title": "Alloanamnesis",
            "model": "alloanamnesis"
        },
        {
            "title": "Anamnesis",
            "model": "anamnesis"
        }
    ]
}
export function faktorResJantung() : any {
    return [
        {
            "title": "Hipertensi",
            "detail": [
                {
                    "model": "hipertensi",
                    "label": "Ya",
                },
                {
                    "model": "hiperKontrol",
                    "label": "Terkontrol",
                },
                {
                    "model": "hiperKontrol",
                    "label": "Tidak Terkontrol",
                },
                {
                    "model": "hipertensi",
                    "label": "Tidak",
                },
            ]
        },
        {
            "title": "Diabetes Melitus",
            "detail" : [
                {
                    "model": "diabetes",
                    "label": "Ya",
                },
                {
                    "model": "diabetesKontrol",
                    "label": "Terkontrol",
                },
                {
                    "model": "diabetesKontrol",
                    "label": "Tidak Terkontrol",
                },
                {
                    "model": "diabetes",
                    "label": "Tidak",
                },
            ]
        },
        {
            "title": "Dyslipidemia (kelainan kolestrol darah)",
            "detail" : [
                {
                    "model": "dyslipidemia",
                    "label": "Ya",
                },
                {
                    "model": "dyslipidemiaKontrol",
                    "label": "Terkontrol",
                },
                {
                    "model": "dyslipidemiaKontrol",
                    "label": "Tidak Terkontrol",
                },
                {
                    "model": "dyslipidemia",
                    "label": "Tidak",
                },
            ]
        },
        {
            "title": "Riwayat serangan jantung dini pada orang tua (pria <55 tahun atau wanita <65 tahun)",
            "detail": [
                {
                    "model" : "riwayatJantung",
                    "label" : "Ya",
                },
                {
                    "model" : "riwayatJantung",
                    "label" : "Tidak",
                },
            ]
        },
    ]
}
export function keadaanUmum_1() : any {
    return [
        {
            "title": "1. Keadaan Umum",
            "value": ["Baik", "Sakit Ringan", "Sakit Sedang", "Sakit Berat"],
            "model": 'keadaanUmum'
        }
    ]
}
export function pemeriksaanFisik() : any {
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
                    "model": "IMT",
                },
            ]
        },
    ]
}
export function kesadaran() : any {
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
export function dscRangeKesadaran() : any {
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
export function keadaanUmum_2() : any {
    return [
        {
            "title": "1. Kepala",
            "value": [
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Kepala",
                            "model" : "kepala",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketKepala",
                            "div"   : "2"
                        },
                        {
                            "label" : "Ubun Ubun Besar",
                            "model" : "ubunubun",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketUbunubun",
                            "div"   : "2"
                        },
                        {
                            "label" : "Normal",
                            "model" : "normal",
                            "div"   : "2"
                        },
                        {
                            "label" : "Mikrosefali",
                            "model" : "mikrosefali",
                            "div"   : "2"
                        },
                        {
                            "label" : "Makrosefali",
                            "model" : "makrosefali",
                            "div"   : "2"
                        },
                        
                    ]
                },
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Lingkar Kepala",
                            "model" : "lingkarkepala",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketLingkarKepala",
                            "div"   : "2"
                        },
                        {
                            "label" : "Lainnya",
                            "model" : "lainnya",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketLainnya",
                            "div"   : "2"
                        },
                    ]
                },
            ]
        },
        {
            "title": "2. Mata",
            "value": [
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Anemis",
                            "model" : "anemis",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketAnemis",
                            "div"   : "2"
                        },
                        {
                            "label" : "Konjungtiva Pucat",
                            "model" : "konjungtiva",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketKonjungtiva",
                            "div"   : "2"
                        },
                        {
                            "label" : "Pupil Isokor",
                            "model" : "pupil",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketPupil",
                            "div"   : "2"
                        }
                        
                    ]
                },
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Ikterus",
                            "model" : "ikterus",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketIkterus",
                            "div"   : "2"
                        },
                        {
                            "label" : "Hiperemi",
                            "model" : "hiperemi",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketHiperemi",
                            "div"   : "2"
                        },
                        {
                            "label" : "Refleks Cahaya",
                            "model" : "refleks",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketRefleks",
                            "div"   : "2"
                        }
                        
                    ]
                },
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Refleks Pupil",
                            "model" : "refpupil",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketRefpupil",
                            "div"   : "2"
                        },
                        {
                            "label" : "Secret",
                            "model" : "secret",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketSecret",
                            "div"   : "2"
                        },
                        {
                            "label" : "Oedema",
                            "model" : "oedema",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketOedema",
                            "div"   : "2"
                        }
                        
                    ]
                },
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Oedema Palpebrae",
                            "model" : "oedemapal",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketOedemapal",
                            "div"   : "2"
                        },
                        {
                            "label" : "Sklera Ikteris",
                            "model" : "skleraik",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketSkleraik",
                            "div"   : "2"
                        }
                    ]
                },
            ]
        },
        {
            "title": "3. THT",
            "value": [
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Tonsil",
                            "model" : "tonsil",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketTonsil",
                            "div"   : "2"
                        },
                        {
                            "label" : "Hidung",
                            "model" : "hidung",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketHidung",
                            "div"   : "2"
                        },
                        {
                            "label" : "Lain-Lain",
                            "model" : "lainlain",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketLain",
                            "div"   : "2"
                        }
                        
                    ]
                },
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Pharing",
                            "model" : "pharing",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketPharing",
                            "div"   : "2"
                        },
                        {
                            "label" : "Bibir",
                            "model" : "bibir",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketBibir",
                            "div"   : "2"
                        }
                    ]
                },
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Telinga",
                            "model" : "telinga",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketTelinga",
                            "div"   : "2"
                        },
                        {
                            "label" : "Lidah",
                            "model" : "lidah",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketLidah",
                            "div"   : "2"
                        }
                    ]
                },
            ]
        },
        {
            "title": "4. Leher",
            "value": [
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "JVP",
                            "model" : "jvp",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketJVP",
                            "div"   : "2"
                        },
                        {
                            "label" : "Kaku Kuduk",
                            "model" : "kakukuduk",
                            "div"   : "2"
                        },
                        {
                            "label" : "Tunggal",
                            "model" : "tunggal",
                            "div"   : "2"
                        },
                        {
                            "label" : "Multiple",
                            "model" : "multiple",
                            "div"   : "2"
                        },
                        
                    ]
                },
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Pembesaran Kelenjar",
                            "model" : "kelenjar",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketKelenjar",
                            "div"   : "2"
                        },
                        {
                            "label" : "Lainnya",
                            "model" : "lainnya",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketLainnya",
                            "div"   : "2"
                        },
                    ]
                },
            ]
        },
        {
            "title": "5. Thorax",
            "value": [
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Simetris / Asimetris",
                            "model" : "simetris",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketSimetris",
                            "div"   : "2"
                        },
                        {
                            "label" : "Retraksi",
                            "model" : "retraksi",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketRetraksi",
                            "div"   : "2"
                        },
                    ]
                },
            ]
        },
        {
            "title": "6. Cor",
            "value": [
                {
                    "subTitle": "",
                    "item": []
                },
            ]
        },
        {
            "title": "7. Pulmo",
            "value": [
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Inspeksi: Statis",
                            "model" : "statis",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketStatis",
                            "div"   : "2"
                        },
                        {
                            "label" : "Perkusi",
                            "model" : "perkusi",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketPerkusi",
                            "div"   : "2"
                        },
                        {
                            "label" : "Auskultasi_Vesikuler",
                            "model" : "auskultasi",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketAuskultasi",
                            "div"   : "2"
                        }
                        
                    ]
                },
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Inspeksi: Dinamis",
                            "model" : "dinamis",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketDinamis",
                            "div"   : "2"
                        },
                        {
                            "label" : "Ronchi",
                            "model" : "ronchi",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketRonchi",
                            "div"   : "2"
                        },
                        {
                            "label" : "Suara Nafas",
                            "model" : "nafas",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketNafas",
                            "div"   : "2"
                        }
                        
                    ]
                },
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Palpasi: SF",
                            "model" : "palpasi",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketPalpasi",
                            "div"   : "2"
                        },
                        {
                            "label" : "Auskultasi_Wheezing",
                            "model" : "wheezing",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketWheezing",
                            "div"   : "2"
                        },
                        {
                            "label" : "Lain-lain",
                            "model" : "lainlainnya",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketLainlainnya",
                            "div"   : "2"
                        }
                        
                    ]
                },
            ]
        },
        {
            "title": "8. Abdomen",
            "value": [
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Peristaltik",
                            "model" : "peristaltik",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketPeristaltik",
                            "div"   : "2"
                        },
                        {
                            "label" : "Distensi",
                            "model" : "distensi",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketDistensi",
                            "div"   : "2"
                        },
                        {
                            "label" : "Meteorismus",
                            "model" : "meteorismus",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketMeteorismus",
                            "div"   : "2"
                        }
                        
                    ]
                },
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Souffle",
                            "model" : "souffle",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketSouffle",
                            "div"   : "2"
                        },
                        {
                            "label" : "Ascites",
                            "model" : "ascites",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketAscites",
                            "div"   : "2"
                        },
                        {
                            "label" : "Turgor",
                            "model" : "turgor",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketTurgor",
                            "div"   : "2"
                        }
                        
                    ]
                },
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Nyeri tekan lokasi",
                            "model" : "nyeri",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketNyeri",
                            "div"   : "2"
                        },
                        {
                            "label" : "Lien",
                            "model" : "lien",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketLien",
                            "div"   : "2"
                        },
                        {
                            "label" : "Massa",
                            "model" : "massa",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketMassa",
                            "div"   : "2"
                        }
                        
                    ]
                },
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Hepar",
                            "model" : "nyeri",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketHepar",
                            "div"   : "2"
                        }
                    ]
                }
            ]
        },
        {
            "title": "9. Ekstermitas",
            "value": [
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Ekstermitas",
                            "model" : "ekstermitas",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketEkstermitas",
                            "div"   : "2"
                        },
                        {
                            "label" : "Odema",
                            "model" : "odema",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketOdema",
                            "div"   : "2"
                        },
                        {
                            "label" : "Capillary Refill Time",
                            "model" : "capillary",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketCapillary",
                            "div"   : "2"
                        }
                        
                    ]
                },
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Lian-lain",
                            "model" : "lanlan",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketLanlan",
                            "div"   : "2"
                        }
                    ]
                }
            ]
        },
        {
            "title": "10. Lainnya",
            "value": [
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Kulit",
                            "model" : "kulit",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketKulit",
                            "div"   : "2"
                        },
                        {
                            "label" : "Pubertas_Perempuan",
                            "model" : "pubertasp",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketPubertasp",
                            "div"   : "2"
                        },
                        {
                            "label" : "Lain-Lain",
                            "model" : "lala",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketLala",
                            "div"   : "2"
                        }
                        
                    ]
                },
                {
                    "subTitle": "",
                    "item": [
                        {
                            "label" : "Genetalia Ekxterna",
                            "model" : "genetalia",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketGenetalia",
                            "div"   : "2"
                        },
                        {
                            "label" : "Pubertas_Laki-laki",
                            "model" : "pubertasl",
                            "div"   : "2"
                        },
                        {
                            "label" : "",
                            "model" : "ketPubertasl",
                            "div"   : "2"
                        },
                    ]
                },
            ]
        }
        
    ]
}

export function penunjang() : any {
    return[
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
            "title": "laboratorium",
            "model": "pemeriksaanLaboratorium",
            "value": "Ya",
            "desc": "ketLaboratorium"
        },
        {
            "title": "Radiologi",
            "model": "pemeriksaanRadiologi",
            "value": "Ya",
            "desc": "ketRadiologi"
        },
    ]
}

export function dateAndDescrip() : any {
    return [
        {
            "name": "F. Tata Laksana",
            "detail": [{
                "title": "Tanggal dan Jam",
                "model": "waktuTataLaksana",
                "descrip": [{
                    "subTitle": "",
                    "model": "ketTataLaksana"
                }]
            }]
    
        },
        {
            "name": "G. Rencana Selanjutnya",
            "detail": [{
                "title": "Kontrol Tanggal",
                "model": "waktuKontrol",
                "descrip": [
                    {
                        "subTitle": "Rawat",
                        "model": "rawat"
                    },
                    {
                        "subTitle": "Rujuk",
                        "model": "rujuk"
                    },
                ]
            }]
    
        },
    ]
}

export function prognosis() :any {
    return ["Bonam","Dubia ad Bonam","Dubia ad Malam","Malam"]
}

export function lokalisGiziKlinik(): any {
    return [
        {
            header: '',
            class: [],
            body: [
                {
                    label: 'Kehilangan BB 6 BI terakhir',
                    type: 'dropdown',
                    model: 'kehilanganbbGizi',
                    class: ['is-6'],
                    children: [
                        {
                            label: 'Tidak Ada',
                            value: '0',
                        },
                        {
                            label: '< 10% BB Biasa',
                            value: '1',    
                        },
                        {
                            label: '> 10% BB Biasa',
                            value: '2',    
                        },
                    ],
                },
                {
                    label: 'Asupan Makanan 5 Hari Terakhir',
                    type: 'dropdown',
                    model: 'asupanterakhirGizi',
                    class: ['is-6'],
                    children: [
                        {
                            label: 'Tidak Berubah',
                            value: '0',
                        },
                        {
                            label: 'Menurun < 50%',
                            value: '1',
                        },
                        {
                            label: 'Menurun > 50%',
                            value: '2',
                        },
                    ],
                },
                {
                    label: 'Gangguan Saluran cerna persisten 2 Minggu terakhir',
                    type: 'dropdown',
                    model: 'gangguansaluranGizi',
                    class: ['is-6'],
                    children: [
                        {
                            label: 'Tidak Ada',
                            value: '0',
                        },
                        {
                            label: 'Mual, ANOREKSIA',
                            value: '1',
                        },
                        {
                            label: 'Muntah, Diare',
                            value: '2',
                        },
                    ],
                },
                {
                    label: 'Kapasitas Fungsional',
                    type: 'dropdown',
                    model: 'kapasitasfungsiGizi',
                    class: ['is-6'],
                    children: [
                        {
                            label: 'Tidak Berubah',
                            value: '0',
                        },
                        {
                            label: 'Menurun/sub-optimal 2 Minggu',
                            value: '1',
                        },
                        {
                            label: 'Bedridden 2 Minggu/lebih',
                            value: '2',
                        },
                    ],
                },
                {
                    label: 'Penyakit Stress Metabolik',
                    type: 'dropdown',
                    model: 'stressMetabolikGizi',
                    class: ['is-6'],
                    children: [
                        {
                            label: 'Ringan',
                            value: '0',
                        },
                        {
                            label: 'Sedang',
                            value: '1',
                        },
                        {
                            label: 'Berat',
                            value: '2',
                        },
                    ],
                },
                {
                    label: 'Pemeriksaan Fisik : < lemak subkutan & muscle wasting',
                    type: 'dropdown',
                    model: 'pemeriksafisikkGizi',
                    class: ['is-6'],
                    children: [
                        {
                            label: 'Tidak Ada',
                            value: '0',
                        },
                        {
                            label: 'Ringan: +1',
                            value: '1',
                        },
                        {
                            label: 'Sedang: +2',
                            value: '2',
                        },
                        {
                            label: 'Berat: +3',
                            value: '3',
                        },
                    ],
                },
                {
                    label: 'SGA',
                    type: 'dropdown',
                    model: 'sgaGizi',
                    class: ['is-6'],
                    children: [
                        {
                            label: 'A(0) status gizi baik',
                            value: '0',
                        },
                        {
                            label: 'B(1-2) malnutrisi ringan',
                            value: '1',
                        },
                        {
                            label: 'C(>2) malnutrisi berat',
                            value: '2',
                        },
                    ],
                },
                {
                    label: 'IMT(kg/m2)',
                    type: 'dropdown',
                    model: 'imtGizi',
                    class: ['is-6'],
                    children: [
                        {
                            label: '18,5-25',
                            value: '0',
                        },
                        {
                            label: '25,1-30',
                            value: '1',
                        },
                        {
                            label: '<18,5 atau>30',
                            value: '2',
                        },
                    ],
                },
                {
                    label: 'Kadar Albumin (g/dl)',
                    type: 'dropdown',
                    model: 'albuminGizi',
                    class: ['is-6'],
                    children: [
                        {
                            label: '>3,4',
                            value: '0',
                        },
                        {
                            label: '2,5-3,4',
                            value: '1',
                        },
                        {
                            label: '< 2,5',
                            value: '2',
                        },
                    ],    
                },
                {
                    label: 'TLC',
                    type: 'dropdown',
                    model: 'tclGizi',
                    class: ['is-6'],
                    children: [
                        {
                            label: '>1500',
                            value: '0',
                        },
                        {
                            label: '900-1500',
                            value: '1',
                        },
                        {
                            label: '< 1500',
                            value: '2',
                        },
                    ],
                },
                {
                    label: 'Total Score',
                    type: 'text',
                    model: 'totalScoreGizi',
                    disabled: true,
                    class: ['is-6'],
                    value: 0
                },
                {
                    label: 'Tingkat Resiko Malnutrisi',
                    type: 'text',
                    model: 'malnutrisiGizi',
                    class: ['is-6'],
                    disabled: true,
                    value: 0
                },                  
            ]
        },
        {
            header: 'Kondisi',
            class: ['is-bold'],
            body: [
                {
                    label: 'Diagnosis Klinis',
                    type: 'text',
                    model: 'diagnosisGizi',
                    class: ['is-12'],
                    value: ''
                },
                {
                    label: 'Status Metabolisme',
                    type: 'text',
                    model: 'metabolismeGizi',
                    class: ['is-12'],
                    value: ''
                },
                {
                    label: 'Status Saluran Cerna',
                    type: 'text',
                    model: 'saluranGizi',
                    class: ['is-12'],
                    value: ''
                },
                {
                    label: 'Resiko Malnutrisi',
                    type: 'dropdown',
                    model: 'resikoMalnutrisiGizi',
                    class: ['is-12'],
                    disabled: true,
                    children: [
                        {
                            label: 'Rendah',
                            value: '0',
                        },
                        {
                            label: 'Sedang -> kontrol kembali, mengikuti penyakit utamanya',
                            value: '1',
                        },
                        {
                            label: 'Tinggi -> kontrol setiap minggu',
                            value: '2',
                        },
                    ],
                },
                // {
                //     label: 'Resiko Malnutrisi',
                //     type: 'text',
                //     model: 'resikoMalnutrisiGizi',
                //     class: ['is-12'],
                //     value: ''
                // },
            ]
        }

    ]
}

export function lokalisPoliVCT(): any {
    return [
        {
            header: 'Keluhan',
            class: [],
            body: [
                {
                    label: '',
                    type: 'checkboxMulti',
                    model: 'kondisiVCT',
                    class: ['is-12'],
                    children: [
                        {
                            label: 'BB Turun',
                            value: 'BB Turun',
                        },
                        {
                            label: 'Diare',
                            value: 'Diare',    
                        },
                        {
                            label: 'Badan Panas',
                            value: '0',    
                        },
                        {
                            label: 'Jamur dimulut',
                            value: '0',    
                        },
                        {
                            label: 'Sulit menelan',
                            value: '0',    
                        },
                        {
                            label: 'Batuk',
                            value: '0',    
                        },
                        {
                            label: 'Gatal pada kulit',
                            value: '0',    
                        },
                        {
                            label: 'Kelainan kulit',
                            value: '0',    
                        },
                        {
                            label: 'Gangguan visus',
                            value: '0',    
                        },
                        {
                            label: 'Herpes simplex',
                            value: '0',    
                        },
                        {
                            label: 'Herpes zoster',
                            value: '0',    
                        },
                        {
                            label: 'ISPA berulang',
                            value: '0',    
                        },
                        {
                            label: 'Nyeri kepala',
                            value: '0',    
                        },
                        {
                            label: 'TB paru',
                            value: '0',    
                        },
                        {
                            label: 'IMS',
                            value: '0',    
                        },
                        {
                            label: 'Infeksi paru non TB',
                            value: '0',    
                        },
                        {
                            label: 'Kesadaran menurun',
                            value: '0',    
                        },
                        {
                            label: 'Pembesaran KGB',
                            value: '0',    
                        },
                    ],
                },              
            ]
        },
        {
            header: '',
            class: ['is-bold'],
            body: [
                {
                    label: 'Kebutuhan Lain',
                    type: 'text',
                    model: 'kebutuhanlainVCT',
                    class: ['is-6'],
                    value: ''
                },
                {
                    label: 'Lokasi KGB',
                    type: 'text',
                    model: 'lokasiKGBVCT',
                    class: ['is-6'],
                    value: ''
                },

            ]
        },
        {
            header: 'AIDS Defening illnes',
            class: [],
            body: [
                {
                    label: '',
                    type: 'checkboxMulti',
                    model: 'aidsVCT',
                    class: ['is-12'],
                    children: [
                        {
                            label: 'Lymphoma Burkitt`s',
                            value: '',
                        },
                        {
                            label: 'Cocodiodomycosis',
                            value: '',    
                        },
                        {
                            label: 'Cytomegalovirus',
                            value: '0',    
                        },
                        {
                            label: 'Herpes simplex',
                            value: '0',    
                        },
                        {
                            label: 'Kandidiasis Esofagus',
                            value: '0',    
                        },
                        {
                            label: 'Cryptococcosis',
                            value: '0',    
                        },
                        {
                            label: 'Sarkoma Kaposi',
                            value: '0',    
                        },
                        {
                            label: 'Histoplasmosis',
                            value: '0',    
                        },
                        {
                            label: 'Ca Cervix invasif',
                            value: '0',    
                        },
                        {
                            label: 'Cryptosporidiosis',
                            value: '0',    
                        },
                        {
                            label: 'HIV encephalopathy',
                            value: '0',    
                        },
                        {
                            label: 'Isosporiasis',
                            value: '0',    
                        },
                        {
                            label: 'HIV Wasting syndrome',
                            value: '0',    
                        },
                        {
                            label: 'Penicilliosis',
                            value: '0',    
                        },
                        {
                            label: 'Reccurent pneumonia',
                            value: '0',    
                        },
                        {
                            label: 'Lymphoma Otak',
                            value: '0',    
                        },
                        {
                            label: 'Salmonella septicemia',
                            value: '0',    
                        },
                        {
                            label: 'PCP',
                            value: '0',    
                        },
                        {
                            label: 'M. Tuberculosis complex',
                            value: '0',    
                        },
                        {
                            label: 'Toxoplasmosis',
                            value: '0',    
                        },
                        {
                            label: 'Lymphoma Immunoblaastic',
                            value: '0',    
                        },
                        {
                            label: 'Cytomegalovirus retintis',
                            value: '0',    
                        },
                        {
                            label: 'Kandidiasis trakea/broncus/paru',
                            value: '0',    
                        },
                        {
                            label: 'Progressive Multifocal Leukeuncephalopathy',
                            value: '0',    
                        },
                        {
                            label: 'Mycobacterium non TBC',
                            value: '0',    
                        },
                    ],
                },              
            ]
        },
        {
            header: 'Terapi Infeksi Opportunistik',
            class: ['is-bold'],
            body: [
                {
                    label: 'OAT',
                    type: 'text',
                    model: 'terapiOatVCT',
                    class: ['is-6'],
                    value: ''
                },
                {
                    label: 'Antitoxoplasmosis',
                    type: 'text',
                    model: 'antitoxopVCT',
                    class: ['is-6'],
                    value: ''
                },
                {
                    label: 'Terapi PCP',
                    type: 'text',
                    model: 'pcpVCT',
                    class: ['is-6'],
                    value: ''
                },
                {
                    label: 'Anti jamur',
                    type: 'text',
                    model: 'jamurVCT',
                    class: ['is-6'],
                    value: ''
                },
                {
                    label: 'Terapi lain',
                    type: 'text',
                    model: 'terapilainVCT',
                    class: ['is-12'],
                    value: ''
                },

            ]
        },
        {
            header: 'Obat Profilaksis Infeksi Oportunistik',
            class: [],
            body: [
                {
                    label: '',
                    type: 'checkbox',
                    model: 'profilaksisVCT',
                    class: ['is-12'],
                    children: [
                        {
                            label: 'INH Primer',
                            value: '',
                        },
                        {
                            label: 'INH Sekunder',
                            value: '',    
                        },
                        {
                            label: 'Fansidar Primer',
                            value: '0',    
                        },
                        {
                            label: 'Fansidar Sekunder',
                            value: '0',    
                        },
                        {
                            label: 'Klindamisin',
                            value: '0',    
                        },
                        {
                            label: 'Fluconazol Primer',
                            value: '0',    
                        },
                        {
                            label: 'Fluconazol Sekunder',
                            value: '0',    
                        },
                        {
                            label: 'Cotrimoxazol Primer',
                            value: '0',    
                        },
                        {
                            label: 'Cotrimoxazol Sekunder',
                            value: '0',    
                        },
                        {
                            label: 'Pirimetamin',
                            value: '0',    
                        },
                    ],
                },              
            ]
        },
        {
            header: 'Terapi Infeksi Opportunistik',
            class: ['is-bold'],
            body: [
                {
                    label: 'Obat lain',
                    type: 'text',
                    model: 'obatLainVCT',
                    class: ['is-12'],
                    value: ''
                },
                {
                    label: 'Keterangan lain-lain',
                    type: 'text',
                    model: 'keteranganLainVCT',
                    class: ['is-12'],
                    value: ''
                },
                {
                    label: 'Dokter Pemeriksa',
                    type: 'text',
                    model: 'dokterpemeriksaVCT',
                    class: ['is-12'],
                    value: ''
                }
            ]
        },
        {
            header: 'Riwayat Terapi Antiretroviral',
            class: [],
            body: [
                {
                    label: 'Pernah menerima ART ?',
                    type: 'checkbox',
                    model: 'profilaksisVCT',
                    class: ['is-12'],
                    children: [
                        {
                            label: 'Ya',
                            value: '',
                        },
                        {
                            label: 'Tidak',
                            value: '',    
                        },
                    ],
                },              
            ]
        },
        {
            header: 'Jika ya',
            class: [],
            body: [
                {
                    label: '',
                    type: 'checkboxSatuan',
                    model: '',
                    class: ['is-12'],
                    children: [
                        {
                            label: 'PMTCT',
                            value: 'PMTCT_VCT',
                        },
                        {
                            label: 'ART',
                            value: 'ART_VCT',    
                        },
                        {
                            label: 'PPP',
                            value: 'PPP_VCT',    
                        },
                    ],
                },              
            ]
        },
        {
            header: 'Tempat ART Dulu',
            class: [],
            body: [
                {
                    label: '',
                    type: 'checkboxSatuan',
                    model: '',
                    class: ['is-12'],
                    children: [
                        {
                            label: 'RS. Pem',
                            value: 'RSPEM_VCT',
                        },
                        {
                            label: 'RS. Swasta',
                            value: 'RSSwasta_VCT',    
                        },
                        {
                            label: 'PKM',
                            value: 'PKM_VCT',    
                        },
                    ],
                },           
                {
                    label: 'Nama Dosis ART dan lama penggunaannya',
                    type: 'text',
                    model: 'namaDosisART_VCT',
                    class: ['is-12'],
                    value: ''
                }   
            ]
        },
    ]
}

export function lokalisGigi(): any {
    return [
        {
            header: '',
            class: [],
            body: [
                {
                    label: 'Elemen',
                    type: 'text',
                    model: 'elemen',
                    class: ['is-6'],
                    value: ''
                },
                {
                    label: 'Elemen',
                    type: 'text',
                    model: 'elemen2',
                    class: ['is-6'],
                    value: ''
                },
                {
                    label: 'Karies',
                    type: 'text',
                    model: 'karies',
                    class: ['is-6'],
                    value: ''
                },
                {
                    label: 'Karies',
                    type: 'text',
                    model: 'karies2',
                    class: ['is-6'],
                    value: ''
                },
                {
                    label: 'Sondasi',
                    type: 'text',
                    model: 'sondasi',
                    class: ['is-6'],
                    value: ''
                },
                {
                    label: 'Sondasi',
                    type: 'text',
                    model: 'sondasi2',
                    class: ['is-6'],
                    value: ''
                    
                },
                {
                    label: 'Perkusi',
                    type: 'text',
                    model: 'perkusi',
                    class: ['is-6'],
                    value: ''
                },
                {
                    label: 'Perkusi',
                    type: 'text',
                    model: 'perkusi2',
                    class: ['is-6'],
                    value: ''
                },
                {
                    label: 'Druk',
                    type: 'text',
                    model: 'druk',
                    class: ['is-6'],
                    value: ''
                },
                {
                    label: 'Druk',
                    type: 'text',
                    model: 'druk2',
                    class: ['is-6'],
                    value: ''
                },
                {
                    label: 'Ce',
                    type: 'text',
                    model: 'ce',
                    class: ['is-6'],
                    value: ''
                },
                {
                    label: 'Ce',
                    type: 'text',
                    model: 'ce2',
                    class: ['is-6'],
                    value: ''
                }, 
                {
                    label: 'RO Foto',
                    type: 'text',
                    model: 'rofoto',
                    class: ['is-6'],
                    value: ''
                },
                {
                    label: 'RO Foto',
                    type: 'text',
                    model: 'rofoto2',
                    class: ['is-6'],
                    value: ''
                },               
                // {
                //     label: 'Resiko Malnutrisi',
                //     type: 'text',
                //     model: 'resikoMalnutrisiGizi',
                //     class: ['is-12'],
                //     value: ''
                // },
            ]
        }

    ]
}
