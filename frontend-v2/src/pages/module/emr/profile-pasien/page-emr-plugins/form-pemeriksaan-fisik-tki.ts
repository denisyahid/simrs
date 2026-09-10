export function detailAnamnesa1(): any {
    return [
        { caption: "Tekanan darah tinggi" },
        { caption: "Kencing nanah" },
        { caption: "Stroke" },
        { caption: "Eksim" },
        { caption: "Sakit jantung" },
        { caption: "Alergi obat" },
        { caption: "Batuk berdahak lama (>2 minggu))" },
        { caption: "Berak darah" },
        { caption: "Batuk berdarah" },
        { caption: "Wasir" },
        { caption: "Sesak nafas" },
        { caption: "Kusta" },
        { caption: "Kelenjar gondok" },
        { caption: "Malaria" },
        { caption: "Kencing manis" },
        { caption: "Tuberculosis" },
        { caption: "Gastritis" },
        { caption: "Ayan/epilepsi" },
        { caption: "Usus buntu" },
        { caption: "Gangguan jiwa" },
        { caption: "Batu saluran kemih" },
        { caption: "Tumor" },
        { caption: "Kencing darah" },
        { caption: "Keganasan" }
    ]
}

export function detailAnamnesa2(): any {
    return [
        { caption: "Merokok" },
        { caption: "Minum alkohol" },
        { caption: "Menyalahgunakan narkoba" }
    ]
}

export function detailTandaVital(): any {
    return [
        { caption: "Nadi", satuan: "/menit" },
        { caption: "Pernafasan", satuan: "/menit" },
        { caption: "Tinggi Badan", satuan: "cm" },
        { caption: "Tekanan Darah (duduk)", satuan: "mmHg" },
        { caption: "Suhu badan", satuan: "°C" },
        { caption: "Berat badan", satuan: "Kg" },
    ]
}

export function detailTandaVitalTable(): any {
    return [
        {
            child:[
                {type: "head", caption: "Kepala", colspan: 4},
            ]
        },
        {
            child: [
                {type: "text", caption: "Tulang", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Kulit kepala", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Rambut", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "N. Cranialis", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child:[
                {type: "head", caption: "Mata", colspan: 4},
            ]
        },
        {
            child: [
                {type: "text", caption: "Visus", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Konjungtiva", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Sklera", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Kornea", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Pupil", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Lensa", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Tes buta warna", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child:[
                {type: "head", caption: "Telinga", colspan: 4},
            ]
        },
        {
            child: [
                {type: "text", caption: "Daun telinga", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Liang telinga", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Serumen", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Membran timpani", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child:[
                {type: "head", caption: "Hidung", colspan: 4},
            ]
        },
        {
            child: [
                {type: "text", caption: "Meatus nasi", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Septum nasi", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Konka nasal", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Nyeri ketok sinus", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child:[
                {type: "head", caption: "Tenggorokan", colspan: 4},
            ]
        },
        {
            child: [
                {type: "text", caption: "Pharynx", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Tonsil", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child:[
                {type: "head", caption: "Gigi dan mulut", colspan: 4},
            ]
        },
        {
            child: [
                {type: "text", caption: "Bibir", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Lidah", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Gusi", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Palatum", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Gigi geligi", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child:[
                {type: "head", caption: "Leher", colspan: 4},
            ]
        },
        {
            child: [
                {type: "text", caption: "Gerakan", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Kelenjar thyroid", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Pulsasi carotis", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Tekanan vena jugularis", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Trachea", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Tulang cervical", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Kelenjar getah bening leher", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child:[
                {type: "head", caption: "Dada", colspan: 4},
            ]
        },
        {
            child: [
                {type: "text", caption: "Bentuk", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Mammae", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Kelenjar getah bening ketiak", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child:[
                {type: "head", caption: "Paru", colspan: 4},
            ]
        },
        {
            child: [
                {type: "text", caption: "Inspeksi", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Palpasi", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Perkusi", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Auskultasi", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child:[
                {type: "head", caption: "Jantung", colspan: 4},
            ]
        },
        {
            child: [
                {type: "text", caption: "Inspeksi", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Palpasi (Ictus cordis)", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Perkusi", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Auskultasi", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child:[
                {type: "head", caption: "Abdomen", colspan: 4},
            ]
        },
        {
            child: [
                {type: "text", caption: "Inspeksi", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Perkusi", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Auskultasi", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Palpasi", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Anus/rektum/perianal", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Genitalia eksterna", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child:[
                {type: "head", caption: "Ekstremitas", colspan: 4},
            ]
        },
        {
            child: [
                {type: "text", caption: "Simetris", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Fungsi motorik", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Fungsi sensorik", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Fungsi otonom", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "list", caption: "", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Refleks fisiologis", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Refleks patologis", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Udem", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Kelenjar getah bening inguinal", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child:[
                {type: "head", caption: "Kulit dan Integumentum", colspan: 4},
            ]
        },
        {
            child: [
                {type: "text", caption: "Kuku", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
        {
            child: [
                {type: "text", caption: "Kulit", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
                {type: "textbox", colspan: 1},
            ]
        },
    ]
}