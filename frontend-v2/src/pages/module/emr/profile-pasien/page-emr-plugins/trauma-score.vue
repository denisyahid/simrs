<template>
    <div class="column is-12">
        <table class="tg">
            <tr>
                <td width="50%">
                    <div>
                        <span style="font-size: large;"><b>Trauma Score</b></span><br />
                        <span>A. Frekwensi Pernafasan</span>
                        <div style="margin-top: 10px">
                            <div class="columns" v-for="(data, itemIndex) in FrekwensiPernafasanList" :key="itemIndex">
                                <div class="column is-6">{{ data.caption }}</div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square
                                            :true-value="data.nilai" :label="data.nilai"
                                            v-model="input['CBFP']" :value="data.nilai" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td width="50%">
                    <div>
                        <span>B. Usaha bernafas</span>
                        <div style="margin-top: 10px">
                            <div class="columns" v-for="(data, itemIndex) in UsahaBernafasList" :key="itemIndex">
                                <div class="column is-6">{{ data.caption }}</div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square
                                            :true-value="data.nilai" :label="data.nilai"
                                            v-model="input['CBUB']" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div>
                        <span>C. Tekanan Darah</span>
                        <div style="margin-top: 10px">
                            <div class="columns" v-for="(data, itemIndex) in TekananDarahList" :key="itemIndex">
                                <div class="column is-6">{{ data.caption }}</div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square
                                            :true-value="data.nilai" :label="data.nilai"
                                            v-model="input['CBTD']" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <span>D. Pengisian Kapiler</span>
                    <div style="margin-top: 10px">
                        <div class="columns" v-for="(data, itemIndex) in PengisianKapilerList" :key="itemIndex">
                            <div class="column is-6">{{ data.caption }}</div>
                            <div class="column is-6">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square :true-value="data.nilai"
                                        :label="data.nilai" v-model="input['CBPK']" />
                                </VControl>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <span>E. Glasgow Coma Score (GCS)</span>
                    <div style="margin-top: 10px">
                        <div class="columns" v-for="(data, itemIndex) in GCSList" :key="itemIndex">
                            <div class="column is-6">{{ data.caption }}</div>
                            <div class="column is-6">
                                <VControl raw subcontrol>
                                    <VCheckbox class="p-0" color="primary" square :true-value="data.nilai"
                                        :label="data.nilai" v-model="input['CBGC']" />
                                </VControl>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <label style="font-weight: bold;">TOTAL TRAUMA SCORE (A+B+C+D+E)</label>
                    <VControl>
                        <VInput type="text" class="input" :value="totalTraumaScore"
                            v-model="input.TBTotalTraumaScore" disabled />
                    </VControl>

                    <label style="font-weight: bold;">REAKSI PUPIL</label>
                    <div style="margin-top: 20px;">
                        <table class="tg">
                            <tr>
                                <th></th>
                                <th>Kanan</th>
                                <th>Ukuran (mm)</th>
                                <th>Kiri</th>
                                <th>Ukuran (mm)</th>
                            </tr>
                            <tr v-for="(data, item) in TTSdetail">
                                <td>{{ data.caption }}</td>
                                <td>
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square
                                            :true-value="data.caption"
                                            v-model="input['CBttsKanan_' + item]" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input"
                                            v-model="input['TBttsUkuranKanan_' + item]" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square
                                            :true-value="data.caption"
                                            v-model="input['CBttsKiri_' + item]" />
                                    </VControl>
                                </td>
                                <td>
                                    <VControl>
                                        <VInput type="text" class="input"
                                            v-model="input['TBttsUkuranKiri_' + item]" />
                                    </VControl>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="column is-12">
        <label style="font-size: large;font-weight:bold">PENOMORAN LOKASI LUKA</label>
        <div class="columns is-multiline mt-3">
            <div class="column is-3" v-for="(data, item) in PLL_List" :key="item" style="text-align: start;">
                <div v-if="item === 15">
                    <VControl raw subcontrol>
                        <VCheckbox class="p-0" color="primary" square true-value="16. Lain-lain"
                            label="16. Lain-lain" v-model="input.CBlainlainPLL" />
                    </VControl>
                    <VControl style="margin-top: 5px">
                        <VInput type="text" class="input" v-model="input.TBlainlainPLL" />
                    </VControl>
                </div>
                <VControl raw subcontrol v-else>
                    <VCheckbox class="p-0" color="primary" square :true-value="data.caption"
                        :label="data.caption" v-model="input['CBPLL_' + item]" />
                </VControl>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue';
import { defineProps, defineEmits, computed } from 'vue';

const props = withDefaults(
  defineProps<{
    input: any
  }>(),
  {
    input: {},
  }
)

let FrekwensiPernafasanList = ref([
    { caption: "10-25", nilai: "4" },
    { caption: "25- 35", nilai: "3" },
    { caption: "> 35", nilai: "2" },
    { caption: "< 10", nilai: "1" },
    { caption: "0", nilai: "0" }
])

let UsahaBernafasList = ref([
    { caption: "Normal", nilai: "1" },
    { caption: "Dangkal", nilai: "0" }
])

let TekananDarahList = ref([
    { caption: "> 89 mmHg", nilai: "4" },
    { caption: "70 – 89 mmHg", nilai: "3" },
    { caption: "50 – 69 mmHg", nilai: "2" },
    { caption: "1 – 49 mmH", nilai: "1" }
])

let PengisianKapilerList = ref([
    { caption: "< 2 dtk", nilai: "2" },
    { caption: "> 2 dtk", nilai: "1" },
    { caption: "Tidak ada", nilai: "0" }
])

let GCSList = ref([
    { caption: "14-15", nilai: "5" },
    { caption: "11-13", nilai: "4" },
    { caption: "8-10", nilai: "3" },
    { caption: "5-7", nilai: "2" },
    { caption: "3-4", nilai: "1" }
])

let TTSdetail = ref([
    { caption: "Cepat" },
    { caption: "Konstriksi" },
    { caption: "Lambat" },
    { caption: "Dilatasi" },
    { caption: "Tak bereaksi" }
])

let PLL_List = ref([
    { caption: "1. Laserasi" },
    { caption: "5. Dislokasi" },
    { caption: "9. Luka bakar" },
    { caption: "13. Avulsi" },
    { caption: "2. Abrasi" },
    { caption: "6. Fr. Terbuka" },
    { caption: "10. Luka dingin" },
    { caption: "14. Nyeri" },
    { caption: "3. Hematoma" },
    { caption: "7. Luka tembak" },
    { caption: "11. Edema" },
    { caption: "15. Fr. Tertutup" },
    { caption: "4. Kontusio" },
    { caption: "8. Luka tusuk" },
    { caption: "12. Amputasi" },
    { caption: "16. Lain-lain" },
])


const input = ref({});
const emit = defineEmits(['updateInput']);

watch(input.value, (newValue) => {
    emit('updateInput', newValue);
});

const sumCheckboxValues = (keys) => {
    return keys.reduce((sum, key) => {
        return sum + (parseInt(input.value[key], 10) || 0);
    }, 0);
};
let totalTraumaScore = computed(() => {
    const frekwensiPernafasanTotal = sumCheckboxValues(Object.keys(input.value).filter(key => key.startsWith('CBFP')));
    const usahaBernafasTotal = sumCheckboxValues(Object.keys(input.value).filter(key => key.startsWith('CBUB')));
    const tekananDarahTotal = sumCheckboxValues(Object.keys(input.value).filter(key => key.startsWith('CBTD')));
    const pengisianKapilerTotal = sumCheckboxValues(Object.keys(input.value).filter(key => key.startsWith('CBPK')));
    const gcsTotal = sumCheckboxValues(Object.keys(input.value).filter(key => key.startsWith('CBGC')));

    return frekwensiPernafasanTotal + usahaBernafasTotal + tekananDarahTotal + pengisianKapilerTotal + gcsTotal;
});

if(props.input) {
    console.log(props.input);
    input.value = props.input
}
// console.log("INPUT VALUE FROM TRAUMA", props.)
// function getScore() {
// }

</script>

<style lang="scss">
h1 {
    font-weight: bold;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
}

.tg td {
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
    text-align: center !important;
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: bold;
    overflow: hidden;
    background-color: aquamarine;
    vertical-align: middle;
    padding: 10px 5px;
    word-break: normal;
}
</style>