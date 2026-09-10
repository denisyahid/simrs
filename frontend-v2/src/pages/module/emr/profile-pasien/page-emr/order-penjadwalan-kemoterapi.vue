<style lang="scss"></style>

<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px; padding-bottom: 15px;">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3>Order Penjadwalan Kemoterapi</h3>
                    </div>
                    <div class="right buttons">
                        <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                            :loading="isLoading" @click="simpan()"> Simpan
                        </VButton>
                    </div>
                </div>
            </div>

            <div class="column">
                <div class="columns is-multiline m-0">
                    <div class="column is-6">
                        <VField label="Tanggal">
                            <VDatePicker v-model="item.tglorder" mode="date" style="width: 100%;">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" disabled />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Tanggal Penjadwalan">
                            <VDatePicker v-model="item.tglpenjadwalan" mode="date" style="width: 100%;">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Ruangan Asal">
                            <VControl icon="feather:home">
                                <VInput type="text" placeholder="" autocomplete="off"
                                    v-model="item.registrasi.namaruangan" disabled />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Ruangan Tujuan" class="is-rounded-select_Z  is-autocomplete-select"
                            v-slot="{ id }">
                            <VControl icon="feather:map-pin" fullwidth>
                                <Multiselect mode="single" v-model="item.ruanganTujuan" :options="d_Ruangan"
                                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Pengorder" class="is-rounded-select_Z is-autocomplete-select" v-slot="{ id }">
                            <VControl icon="fa:user-md" fullwidth>
                                <Multiselect mode="single" v-model="item.pegawaiorderfk" placeholder="Pilih data"
                                    :searchable="true" :min-chars="3" :attrs="{ id }" :delay="0" :options="d_Pegawai"
                                    autocomplete="off" @complete="fetchDokter($event)" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField label="Dokter KHOM" class="is-rounded-select_Z is-autocomplete-select" v-slot="{ id }">
                            <VControl icon="fa:user-md" fullwidth>
                                <Multiselect mode="single" v-model="item.pegawaifk" placeholder="Pilih data"
                                    :searchable="true" :min-chars="3" :attrs="{ id }" :delay="0" :options="d_Pegawai"
                                    autocomplete="off" @complete="fetchDokter($event)" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Diagnosa">
                            <VTextarea rows="2" v-model="item.diagnosa"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Regimen Kemoterapi">
                            <VTextarea rows="2" v-model="item.regimen"></VTextarea>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField label="Keterangan">
                            <VTextarea rows="2" v-model="item.keterangan"></VTextarea>
                        </VField>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useToaster } from '/@src/composable/toaster'
import { useUserSession } from '/@src/stores/userSession'
import { useConfirm } from "primevue/useconfirm"
import ConfirmDialog from 'primevue/confirmdialog'

useHead({ title: 'Order Penjadwalan Kemoterapi - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(props.pasien ? true : false)

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pasien_daftar as string
const props = defineProps({
    registrasi: {
        type: Object as PropType<any>,
    },
    pasien: {
        type: Object as PropType<any>,
    },
    selected: undefined,
    type: undefined,
    align: undefined,
    NOREC_PD: {
        type: Object as PropType<any>,
    },
    nocmfk: {
        type: Object as PropType<any>,
    },
})
const isLoadingPasien: any = ref(false)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    tglorder: new Date(),
    pegawaiorderfk: useUserSession().getUser().id,
})
if (NOREC_PD == undefined) {
    NOREC_PD = props.registrasi.norec_pd
}
const listChecked: any = ref([])
const selected_count = ref(0);
const colors: any = ref(Object.keys(useThemeColors()))
const listColor: any = ref([])
for (let i = 0; i < colors.value.length; i++) {
    const element = colors.value[i];
    if (i <= 9 && element != 'primary')
        listColor.value.push(element)
}
const listColor2: any = ref(['primary', 'info', 'orange', 'yellow', 'success'])
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isShow: any = ref(false)
const pasien: any = ref({})
const d_Ruangan: any = ref([])
const isLoading = ref(false)
const confirm = useConfirm();
const d_Produk: any = ref([])
const d_Pegawai: any = ref([])
const d_ProdukDef: any = ref([])
const d_JenisOperasi: any = ref([])
const d_Kamar: any = ref([])
const filterLayanan: any = ref('')
const listRiwayat = ref([])
const emit = defineEmits<{
    (e: 'update:selected', value: string): void
}>()


function pasienByID(id: any) {
    if (props.pasien != undefined) {
        pasien.value = props.pasien
        item.NOREC_APD = props.registrasi.norec_apd
        item.RUANGAN_LAST = props.registrasi.objectruanganlastfk
        item.registrasi = props.registrasi
        item.kelompokpasien = props.kelompokpasien
    } else {
        isLoadingPasien.value = true
        isLoading.value = true
        useApi().get(`/general/header-pasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}`).then((response: any) => {
            pasien.value = response.pasien
            item.NOREC_APD = response.last_registrasi.norec_apd
            item.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
            item.registrasi = response.registrasi
            item.kelompokpasien = pasien.value.kelompokpasien
            isLoadingPasien.value = false
            isLoading.value = false
        })
    }

    getAutoFill(item.NOREC_PD);

}

async function getAutoFill(norecpd: string) {
    isLoading.value = true;
    await useApi().get(`bedah/get-data-autofill-bedah?norec_pd=${NOREC_PD}`).then((response: any) => {
        item.kelompokpasien = response.namarekanan;
        item.nohpkel = response.telponpenanggungjawab
        item.nohp = response.nohp
        item.diagnosis = response.diagnosa ?? ''
        // item.tb = response.tinggiBadan ?? ''
        // item.bb = response.beratBadan ?? ''
        item.dokterOperator = response.objectdpjp
        isLoading.value = false;
    });

}

function fetchDropdown() {
    useApi().get(`penjadwalan-kemoterapi/list-dropdown`).then((response: any) => {
        d_Ruangan.value = response['ruangan'].map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
    })
}

function fetchOnlyDokter(e: any) {
    let params = e != undefined ? e.filter : '';
    useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap,kddokterbpjs&param_search=namalengkap&query=${params}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter`).then((response) => {
        d_Pegawai.value = response
        d_Pegawai.value.forEach(element => {
            if (props.registrasi.objectpegawaifk == element.value) {
                item.pegawaiorderfk = element.value
            }
            if (element.value == 1355) {
                item.pegawaifk = element.value
            }
        });
    })
}

async function simpan() {
    // Validasi
    if (!item.ruanganTujuan || item.ruanganTujuan == '' || item.ruanganTujuan == null) {
        H.alert('warning', 'Tanggal Penjadwalan perlu diisi!');
        return;
    }
    if (!item.ruanganTujuan || item.ruanganTujuan == '' || item.ruanganTujuan == null) {
        H.alert('warning', 'Ruangan Tujuan harus diisi!');
        return;
    }
    if (!item.pegawaiorderfk || item.pegawaiorderfk == '' || item.pegawaiorderfk == null) {
        H.alert('warning', 'Petugas Order harus diisi!');
        return;
    }
    if (!item.diagnosa || item.diagnosa == '' || item.diagnosa == null) {
        H.alert('warning', 'Diagnosa harus diisi!');
        return;
    }
    if (!item.regimen || item.regimen == '' || item.regimen == null) {
        H.alert('warning', 'Regimen harus diisi!');
        return;
    }

    isLoading.value = true
    var objSave = {
        nocmfk: ID_PASIEN,
        noregistrasi: item.registrasi.noregistrasi,
        tanggal: H.formatDate(item.tglorder, 'YYYY-MM-DD HH:mm:ss'),
        tglpenjadwalan: H.formatDate(item.tglpenjadwalan, 'YYYY-MM-DD HH:mm:ss'),
        norec_apd: item.NOREC_APD,
        norec_pd: props.registrasi.norec_pd,
        objectruanganfk: item.registrasi.objectruanganlastfk,
        pegawaiorderfk: item.pegawaiorderfk,
        pegawaifk: item.pegawaifk,
        regimen: item.regimen,
        diagnosa: item.diagnosa,
        objectruangantujuanfk: item.ruanganTujuan,
        keterangan: item.keterangan != undefined ? item.keterangan : null,
        jenis: 'Kemoterapi'
    }

    await useApi().post(`/penjadwalan-kemoterapi/save-penjadwalan`, objSave).then((response: any) => {
        isLoading.value = false
    }).catch((e: any) => {
        isLoading.value = false 
    })
}

async function fetchDokter(filter: any) {
    let query = ''
    if (filter) {
        query = filter.toLowerCase()
    }
    const response = await useApi().get(`/general/dokter-paging?name= ${query}&limit=10`)
    return response.dokter.map((item: any) => {
        return { value: item.id, label: item.namalengkap, default: item }
    })
}

const DialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda serius menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            hapusItems(e)

        },
        reject: () => { },
    })
}

function hapusItems(e: any) {
    if (e.status != 'pending') {
        H.alert('error', 'Order sudah diverifikasi')
        return
    }
    useApi().post(`/bedah/delete-order-bedah`, { noorder: e.noorder }).then((response: any) => {
        isLoading.value = false
        loadRiwayat()
    }).catch((e: any) => {
        isLoading.value = false
    })
}

onMounted(async () => {
    await pasienByID(ID_PASIEN)
    fetchDropdown()
    fetchOnlyDokter();
})

</script>