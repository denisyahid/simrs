<template>
    <VCard custom="card-green" :class="[isaktif == true ? '' : 'nonaktif']">
        <h3 class="title is-5 mb-4">Detail Registrasi</h3>
        <div class="columns is-multiline" v-if="selectedRegistrasi.norec == undefined">
            <div class="column is-12">
                <VField>
                    <VPlaceload height="15px" />
                    <VPlaceload height="20px" class="mt-2" />
                </VField>
            </div>
            <div class="column is-12">
                <VField>
                    <VPlaceload height="15px" />
                    <VPlaceload height="20px" class="mt-2" />
                </VField>
            </div>
        </div>
        <div class="columns is-multiline" v-if="selectedRegistrasi.norec != undefined">
            <div class="column is-6">
                <VField>
                    <VLabelText>No Registrasi</VLabelText>
                    <VLabel>{{ selectedRegistrasi.noregistrasi }}</VLabel>
                </VField>
            </div>

            <div class="column is-6">
                <VField>
                    <VLabelText>Ruangan</VLabelText>
                    <VLabel>{{ selectedRegistrasi.namaruangan }} </VLabel>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <VLabelText>Kelas</VLabelText>
                    <VLabel>{{ selectedRegistrasi.namakelas }} </VLabel>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <VLabelText>Tipe Pembiayaan</VLabelText>
                    <VLabel>{{ selectedRegistrasi.kelompokpasien + ' / ' + selectedRegistrasi.namarekanan }} </VLabel>
                </VField>
            </div>
        </div>
    </VCard>
</template>

<script setup lang="ts">
// export default {
//     props: {
//         selectedRegistrasi: {}
//     },
//     setup(props: any) {

//     }
// }
const props = withDefaults(
    defineProps<{
        isaktif?: boolean
        selectedRegistrasi?: any
    }>(),
    {
        selectedRegistrasi: {},
        isaktif: true
    }
)

</script>