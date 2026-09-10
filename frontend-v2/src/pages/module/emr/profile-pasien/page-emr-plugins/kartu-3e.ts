export function detailHDD(): any {
    return [
        {
            child: [
                { type: "text", caption: "a. HBsAg" },
                { type: "tanggal" },
                { type: "textbox" },
                { type: "2cb" }
            ]
        },
        {
            child: [
                { type: "text", caption: "b. HIV" },
                { type: "tanggal" },
                { type: "textbox" },
                { type: "2cb" }
            ]
        },
        {
            child: [
                { type: "text", caption: "c. SIFILIS" },
                { type: "tanggal" },
                { type: "textbox" },
                { type: "2cb" }
            ]
        }
    ]
}

export function detailHDD2(): any {
    return [
        {
            child: [
                { type: "head", caption: "2. Bumil dirujuk untuk tata laksana", colspan: 2 },
            ]
        },
        {
            child: [
                { type: "subhead", caption: "a. HIV" },
                { type: "tanggal", caption: "Tgl Ibu Hamil masuk PDP" },
            ]
        },
        {
            child: [
                { type: "subhead", caption: "b. Sifilis", colspan: 2 },
            ]
        },
        {
            child: [
                { type: "text", caption: "Ditangani" },
                { type: "2cb"},
            ]
        },
        {
            child: [
                { type: "text", caption: "Diobati adequat" },
                { type: "2cb"},
            ]
        },
        {
            child: [
                { type: "subhead", caption: "c. Hepatitis B dirujuk"},
                { type: "2cb"}
            ]
        },
        {
            child: [
                { type: "head", caption: "3. Pasangan mengetahui status HIV"},
                { type: "2cb"}
            ]
        },
        {
            child: [
                { type: "head", caption: "4. Apakah pasangan diperiksa Sifilis"},
                { type: "2cb"}
            ]
        },
        {
            child: [
                { type: "head", caption: "5. Faskes Rujukan"},
                { type: "textbox"}
            ]
        },
        {
            child: [
                { type: "text", caption: "Tgl mulai ARV"},
                { type: "tanggal"}
            ]
        },
    ]
}

export function detailPemantauanBayi(): any {
    return [
        {
            child: [
                { type: "text", caption: "Pemberian ARV" },
                { type: "tanggal"},
                { }
            ]
        },
        {
            child: [
                { type: "text", caption: "DBS EID pada usia 6-8 Minggu" },
                { type: "tanggal"},
                { type: "2cb" }
            ]
        },
        {
            child: [
                { type: "text", caption: "Konfirmasi EID dalam 12 bulan" },
                { type: "tanggal"},
                { type: "2cb" }
            ]
        },
        {
            child: [
                { type: "text", caption: "Pemeriksaan Balita terdeteksi HIV (serologis) (bayi usia >= 9 bulan atau anak balita)" },
                { type: "tanggal"},
                { type: "2cb" }
            ]
        },
        {
            child: [
                { type: "text", caption: "Balita HIV masuk perawatan PDP" },
                { type: "tanggal"},
                { }
            ]
        },
        {
            child: [
                { type: "text", caption: "Balita HIV mendapat pengobatan ARV" },
                { type: "tanggal"},
                { }
            ]
        },
    ]
}