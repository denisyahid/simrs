<template>
    <div class="columns is-multiline" v-if="props.hasillab.length > 0">
        <div class="column is-12" v-for="items in props.hasillab" :key="items.id">
            <DataTable :value="items.hasil" class="p-datatable-sm" breakpoint="960px" sortMode="multiple"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
                <template #empty> Hasil Lab Belum Tersedia. </template>
                <template #header>
                    <label class="has-text-weight-bold">PEMERIKSAAN {{ items.noorder }} TANGGAL {{ items.tglorder }}</label>
                </template>
                <Column field="detailpemeriksaan" header="Nama Pemeriksaan">
                    <template #body="{ data }">
                        <span :class="[
                            data.nilaitext == '' && 'has-text-weight-bold',
                            data.nilaitext != '' && 'ml-5',
                        ]">{{ data.detailpemeriksaan }}</span>
                        
                    </template>
                </Column>
                <Column field="hasil" header="Hasil" class="has-text-centered">
                    <template #body="{ data }">
                        <span :class="[
                            data.flag == 'H' && 'has-text-danger',
                            data.flag == 'L' && 'has-text-danger',
                        ]">{{ data.hasil }}</span>
                    </template>
                </Column>
                <Column field="nilaitext" header="Nilai Normal" class="has-text-centered"></Column>
                <Column field="satuanstandar" header="Satuan Standar" class="has-text-centered"></Column>
                <Column field="tglhasil" header="Tgl Hasil" class="has-text-centered"></Column>
            </DataTable>
        </div>
    </div>
    <div class="columns is-multiline" v-else>
        <div class="column is-12">
            <VPlaceholderPage
              title="We couldn't find any matching results." subtitle="Too bad. Looks like we couldn't find any matching results for the
            search terms you've entered. Please try different search terms or
            criteria." larger>
              <template #image>
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderPage>
        </div>
    </div>
</template>
<script setup lang="ts">
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
const props = defineProps({
    hasillab: Array
})
</script>