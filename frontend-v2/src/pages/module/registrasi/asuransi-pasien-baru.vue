<template>
    <div class="page-content-inner">
        <div class="is-navbar">
            <div class="form-layout">
                <div class="form-outer">
                    <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                        <div class="form-header-inner">
                            <div class="left">
                                <h3>Asuransi Pasien</h3>
                            </div>
                            <div class="right">
                                <div class="buttons">
                                    <VButton icon="lnir lnir-arrow-left rem-100"
                                        :to="{ name: 'module-sysadmin-master-asuransi-pasien' }" light dark-outlined>
                                        Cancel
                                    </VButton>
                                    <VButton type="button" icon="feather:save" :loading="isLoading" color="primary"
                                        raised @click="save()"> Save
                                    </VButton>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <!--Fieldset-->
                        <div class="form-fieldset">
                            <div class="fieldset-heading">
                                <h4>Data Diri Peserta</h4>
                            </div>
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>Nama Peserta</VLabel>
                                        <VControl icon="feather:user">
                                            <VInput type="text" v-model="item.namapeserta" placeholder="Nama Lengkap"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>Nomer Identitas</VLabel>
                                        <VControl icon="feather:user">
                                            <VInput type="text" v-model="item.noidentitas" placeholder="Nomer Identitas"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField>
                                        <VLabel>Jenis Kelamin</VLabel>
                                        <VControl>

                                            <div class="column is-6" v-if="d_JenisKelamin.length == 0">
                                                <VPlaceholderText :lines="1" />
                                            </div>
                                            <column class="is-6" v-for="items in d_JenisKelamin" :key="items.id">
                                                <VRadio v-model="item.objectjeniskelaminfk" :value="items.id"
                                                    :label="items.jeniskelamin" name="{{items.id}}" color="success" />
                                            </column>

                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-8">
                                    <VField>
                                        <VLabel>Alamat Lengkap</VLabel>
                                        <VControl>
                                            <VTextarea v-model="item.alamatlengkap" class="is-primary-focus" rows="2"
                                                placeholder="Alamat Lengkap..."></VTextarea>
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-4">
                                    <VDatePicker v-model="item.tgllahir" color="green" trim-weeks>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VLabel>Tanggal Lahir</VLabel>
                                                <VControl icon="feather:calendar">
                                                    <VInput type="text" placeholder="Select a date" :value="inputValue"
                                                        v-on="inputEvents" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </div>
                                <div class="column is-4">
                                    <VField>
                                        <VLabel>Telephone HP</VLabel>
                                        <VControl icon="feather:smartphone">
                                            <VInput type="text" v-model="item.notelpmobile" placeholder="+62....."
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField>
                                        <VLabel>Telephone Rumah</VLabel>
                                        <VControl icon="feather:phone">
                                            <VInput type="text" v-model="item.notelpfixed"
                                                placeholder="Nomer Telephone Rumah" class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>Jenis Peserta</VLabel>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.jenispeserta"
                                                placeholder="Jenis Peserta" class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField>
                                        <VLabel>Kode Penjamin Peserta</VLabel>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.kdpenjaminpasien"
                                                placeholder="Jenis Peserta" class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <!--Fieldset-->
                        <div class="form-fieldset">
                            <Accordion>
                                <AccordionTab header="Informasi Pegawai">
                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Nama Pegawai</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.objectpegawaifk"
                                                        :options="d_Pegawai" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Nomer Induk Pegawai</VLabel>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.nippns" placeholder="NIP PNS"
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <VField>
                                                <VLabel>Kode Institusi</VLabel>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.kdinstitusiasal"
                                                        placeholder="Kode Institusi Asal" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <VField>
                                                <VLabel>Unit Bagian</VLabel>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.lastunitbagian"
                                                        placeholder="Kode Institusi Asal" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <VField>
                                                <VLabel>Kode Unit Bagian</VLabel>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.kdlastunitbagian"
                                                        placeholder="Kode Institusi Asal" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </AccordionTab>
                                <AccordionTab header="Informasi Asuransi">
                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Provider</VLabel>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.nmprovider"
                                                        placeholder="Nama Provider" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Kode Provider</VLabel>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.kdprovider"
                                                        placeholder="Kode Provider" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>

                                        <div class="column is-4">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Hubungan Peserta</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.objecthubunganpesertafk"
                                                        :options="d_HubunganPeserta" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <VField>
                                                <VLabel>Nomer Asuransi</VLabel>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.noasuransi"
                                                        placeholder="Nomer Asuransi" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Golongan Asuransi</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.objectgolonganasuransifk"
                                                        :options="d_GolonganAsuransi" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <VField>
                                                <VLabel>Nomer Kepala Asuransi</VLabel>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.noasuransihead" placeholder="Kode Provider"
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-4">
                                            <VDatePicker v-model="item.tglmulaiberlakulast" color="green" trim-weeks>
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VField>
                                                        <VLabel>Tanggal Mulai Berlaku</VLabel>
                                                        <VControl icon="feather:calendar">
                                                            <VInput type="text" placeholder="Select a date"
                                                                :value="inputValue" v-on="inputEvents"
                                                                class="is-rounded_Z" />
                                                        </VControl>
                                                    </VField>
                                                </template>
                                            </VDatePicker>
                                        </div>
                                        <div class="column is-4">
                                            <VDatePicker v-model="item.tglakhirberlakulast" color="green" trim-weeks>
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VField>
                                                        <VLabel>Tanggal Batas Berlaku</VLabel>
                                                        <VControl icon="feather:calendar">
                                                            <VInput type="text" placeholder="Select a date"
                                                                :value="inputValue" v-on="inputEvents"
                                                                class="is-rounded_Z" />
                                                        </VControl>
                                                    </VField>
                                                </template>
                                            </VDatePicker>
                                        </div>
                                    </div>
                                </AccordionTab>
                            </Accordion>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import { useToaster } from '/@src/composable/toaster'
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';

useHead({
    title: 'Produk - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let ID_PERSASURANSI = useRoute().query.id as string
let ID_PERSASURANSI_SET = ref()
const date = ref(new Date())
const item: any = ref([])
let d_GolonganAsuransi: any = ref([])
let d_HubunganPeserta: any = ref([])
let d_Pegawai: any = ref([])
let d_JenisKelamin: any = ref([])
let isRegistrasi = ref(false)
let isLoading = ref(false)
const { y } = useWindowScroll()
const router = useRouter()
const isStuck = computed(() => {
    return y.value > 30
})

async function listDropdown() {

    const response = await useApi().get(`/sysadmin/master-asuransi-pasien/select-item`)
    d_JenisKelamin.value
    for (let x = 0; x < response.jeniskelamin.length; x++) {
        const element = response.jeniskelamin[x];
        if (element.jeniskelamin != '-') {
            d_JenisKelamin.value.push(element)
        }
    }
    d_Pegawai.value = response.pegawai.map((e: any) => { return { label: e.namalengkap, value: e.id } })
    d_HubunganPeserta.value = response.hubunganpeserta.map((e: any) => { return { label: e.hubunganpeserta, value: e.id } })
    d_GolonganAsuransi.value = response.golonganasuransi.map((e: any) => { return { label: e.golonganasuransi, value: e.id } })

    if (ID_PERSASURANSI) {
        ID_PERSASURANSI_SET.value = ID_PERSASURANSI
        asuransiPasienByID(ID_PERSASURANSI)
    }

}

async function save() {
    if (!item.value.namapeserta) {
        useToaster().error('Nama Peserta harus di isi')
        return
    }
    var objSave =
    {
        'asuransipasien': {
            'id': item.value.id ? item.value.id : '',
            'namapeserta': item.value.namapeserta,
            'tgllahir': item.value.tgllahir,
            'kdprovider': item.value.kdprovider,
            'tglakhirberlakulast': item.value.tglakhirberlakulast,
            'tglmulaiberlakulast': item.value.tglmulaiberlakulast,
            'notelpmobile': item.value.notelpmobile ? item.value.notelpmobile:'',
            'notelpfixed': item.value.notelpfixed ? item.value.notelpfixed:'',
            'noasuransi': item.value.noasuransi ? item.value.noasuransi:'',
            'noasuransihead': item.value.noasuransihead ? item.value.noasuransihead:'',
            'nippns': item.value.nippns ? item.value.nippns:'',
            'nikinstitusiasal': item.value.nikinstitusiasal ? item.value.nikinstitusiasal:'',
            'lastunitbagian': item.value.lastunitbagian ? item.value.lastunitbagian:'',
            'kdpenjaminpasien': item.value.kdpenjaminpasien,
            'kdlastunitbagian': item.value.kdlastunitbagian,
            'kdinstitusiasal': item.value.kdinstitusiasal,
            'objectpegawaifk': item.value.objectpegawaifk,
            'objectjeniskelaminfk': item.value.objectjeniskelaminfk,
            'objecthubunganpesertafk': item.value.objecthubunganpesertafk,
            'objectgolonganasuransifk': item.value.objectgolonganasuransifk,
            'alamatlengkap': item.value.alamatlengkap,
            'nmprovider': item.value.nmprovider,
        }
    }
    isLoading.value = true
    await useApi().post(
        `/sysadmin/master-asuransi-pasien/save`, objSave).then((response: any) => {
            isLoading.value = false
        }, (error) => {
            isLoading.value = false
            // console.log(error)
        })
}

async function asuransiPasienByID(id: any) {
    const { data: asuransipasien } = await useApi().get(`/sysadmin/asuransi-pasien-baru?id=${id}`)  
    item.value.namapeserta = asuransipasien[0].namapeserta
}


listDropdown()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
    max-width: 800px;
    margin: 0 auto;
}

.form-fieldset {
    padding: 20px 0;
    max-width: 780px;
    margin: 0 auto;
}
</style>
