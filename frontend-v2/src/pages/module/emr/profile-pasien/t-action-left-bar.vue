<script lang="ts">
import { ref, watch } from 'vue';
import * as H from '/@src/utils/appHelper'
export default {
    emits: ['reload', 'back', 'filter'],
    props: {
        filterTgl: {}
    },
    setup(props: any) {
        const periodeAwal = ref()
        const periodeAkhir = ref()
        periodeAwal.value = H.formatDateOnly(props.filterTgl.start)
        periodeAkhir.value = H.formatDateOnly(props.filterTgl.end)

        watch(
            () => props.filterTgl.start,
            (newValue, oldValue) => {
                if (newValue != oldValue) {
                    periodeAwal.value = H.formatDateOnly(newValue)
                }
            }
        )
        watch(
            () => props.filterTgl.end,
            (newValue, oldValue) => {
                if (newValue != oldValue) {
                    periodeAkhir.value = H.formatDateOnly(newValue)
                }
            }
        )
        return { periodeAwal, periodeAkhir }
    }
}
</script>
    
<template>
    <VDropdown icon="feather:more-vertical" spaced right>
        <template #content>
            <a role="menuitem" class="dropdown-item is-media" @click="$emit('filter')">
                <div class="icon">
                    <i aria-hidden="true" class="lnil lnil-calendar"></i>
                </div>
                <div class="meta">
                    <span>Filter Periode Registrasi</span>
                    <span>{{periodeAwal +' s/d '+periodeAkhir}}</span>
                </div>
            </a>
            <a role="menuitem" class="dropdown-item is-media" @click="$emit('reload')">
                <div class="icon">
                    <i aria-hidden="true" class="lnil lnil-reload"></i>
                </div>
                <div class="meta">
                    <span>Refresh</span>
                    <span>Reload data riwayat</span>
                </div>
            </a>

            <hr class="dropdown-divider" />

            <a role="menuitem" class="dropdown-item is-media" @click="$emit('back')">
                <div class="icon">
                    <i aria-hidden="true" class="lnil lnil-chevron-left-circle"></i>
                </div>
                <div class="meta">
                    <span>Kembali</span>
                    <span>Balik ke menu sebelumnya</span>
                </div>
            </a>

        </template>
    </VDropdown>
</template>
