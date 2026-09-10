export function header():any {
    return [
        {
            "label" : "Bulan/Tahun",
            "model" : "bulan/tahun"
        },
        {
            "label" : "No. Urut Pencatatan Kematian",
            "model" : "noUrutPencatatan",
        },
        {
            "label" : "Kode Rumah Sakit",
            "model" : "kodeRS",
        },
        {
            "label" : "No. Rekam Medis",
            "model" : "norm",
        },
    ]
}

export function hubunganKepalaRumah():any{
    return [
        {
            "label" : "Kepala Rumah Tanggal",
            "code" : "KRT",
            "model" : "hubungan_1"
        },
        {
            "label" : "Suami",
            "code" : "SM",
            "model" : "hubungan_2"
        },
        {
            "label" : "Istri",
            "code" : "IR",
            "model" : "hubungan_3"
        },
        {
            "label" : "Anak",
            "code" : "AK",
            "model" : "hubungan_4"
        },
        {
            "label" : "Cucu",
            "code" : "CU",
            "model" : "hubungan_5"
        },
        {
            "label" : "Orangtua",
            "code" : "OT",
            "model" : "hubungan_6"
        },
        {
            "label" : "Mertua",
            "code" : "MA",
            "model" : "hubungan_7"
        },
        {
            "label" : "Keluara Lain",
            "code" : "KL",
            "model" : "hubungan_8"
        },
        {
            "label" : "Pembantu Rumah Tangga",
            "code" : "PRA",
            "model" : "hubungan_9"
        },
    ]
}

export function tempatMeninggal():any{
    return [
        {
            "label" : "Rumah Sakit",
            "code" : "RS",
        },
        {
            "label" : "Puskesmas",
            "code" : "PS",
        },
        {
            "label" : "Rumah Tempat Meninggal",
            "code" : "RTM",
        },
        {
            "label" : "Rumah Bersalin",
            "code" : "RB",
        },
        {
            "label" : "Lainnya (termasuk meninggal diperjalanan / Dead On Arrival)",
            "code" : "LN",
        },

    ]
}

export function statusJenazah():any{
    return [
        {
            "label" : "Belum dimakamkan",
            "code" : "BDKM",
        },
        {
            "label" : "Belum dikremasi",
            "code" : "BDKI",
        },
        {
            "label" : "Telah dimakamkan",
            "code" : "TDKM",
        },
        {
            "label" : "Telah dikremasi",
            "code" : "TDKI",
        },
    ]
}

export function penyebabKematian():any{
    return [
        {
            "title" : "Dasar Diagnosis ( dapat lebih dari satu )",
            "detail" : [
                {
                    "subTitle" : "",
                    "model" : "dasarDiagnosis",
                    "value" : [
                        {
                            "label" : "Rekam Medis",
                            "code" : "RM",
                        },
                        {
                            "label" : "Pemeriksaan Luar Jenazah",
                            "code" : "PLJ",
                        },
                        {
                            "label" : "Autopsi Forensik ",
                            "code" : "AF",
                        },
                        {
                            "label" : "Autopsi Medis",
                            "code" : "AM",
                        },
                        {
                            "label" : "Autopsi Verbal",
                            "code" : "AV",
                        },
                    ]
                }
                
            ],
            "optional" : {
                "label" : "Surat Keterangan Lainnya",
                "model" : "KetSuratLainnya"
            } 
        },
        {
            "title" : "Kelompok Penyebab Kematian",
            "detail" : [
                {
                    "subTitle" : "a. Penyakit/Gangguan (kehamilan/persalinan/nifas)",
                    "model" : "penyakit",
                    "value" : [
                        {
                            "label" : "Penyakit Khusus",
                            "code" : "PK",
                        },
                        {
                            "label" : "Penyakit Menular",
                            "code" : "PM",
                        },
                        {
                            "label" : "Penyakit Tidak Menular",
                            "code" : "PTM",
                        },
                        {
                            "label" : "Ganguan Maternal",
                            "code" : "GM",
                        },
                        {
                            "label" : "Gangguan Perinatal (0-6 hari)",
                            "code" : "GP",
                        },
                        {
                            "label" : "Gejala, Tanda dan Kondisi Lainnya",
                            "code" : "GTKL",
                        },
                    ]
                },
                {
                    "subTitle" : "b. Cedera",
                    "model" : "cedera",
                    "value" : [
                        {
                            "label" : "Cedera Kecelakaan Lalu Lintas",
                            "code" : "CKL",
                        },
                        {
                            "label" : "Cedera Kecelakaan Kerja",
                            "code" : "CKK",
                        },
                        {
                            "label" : "Cedera Lainnya",
                            "code" : "CL",
                        },
                    ]
                },
            ]
        }

    ]
}