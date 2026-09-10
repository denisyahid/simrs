<template>
    <div class="columns is-multiline projects-card-grid" v-if="props.hasilrad.length > 0">
        <!--Project-->
        <div class="column is-12" v-for="item in props.hasilrad">
            <div class="grid-item">
                <div class="top-section">
                    <div class="head">
                        <h3>#{{ item.noorder }} {{ item.namaproduk }}</h3>
                    </div>
                    <div class="body" v-html="item.keterangan"></div>
                </div>
                <div class="bottom-section mt-4">
                    <div class="foot-block">
                        <h4 class="heading">Dokter</h4>
                        <p>{{ item.dokterrab }}</p>
                    </div>
                    <div class="foot-block">
                        <h4 class="heading">Tanggal Expertise</h4>
                        <p>{{ H.formatDate(item.tanggalreport, "DD-MM-YYYY HH:mm:ss") }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="columns is-multiline" v-else>
        <div class="column is-12">
            <VPlaceholderPage title="We couldn't find any matching results." subtitle="Too bad. Looks like we couldn't find any matching results for the
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
import * as H from '/@src/utils/appHelper'
const props = defineProps({
    hasilrad: Array
})
</script>
<style lang="scss">
.projects-card-grid {
    .grid-item {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 220px;
        padding: 20px;
        background: var(--white);
        border: 1px solid var(--fade-grey-dark-3);
        border-radius: var(--radius-large);

        .top-section {
            .head {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 8px;

                h3 {
                    font-size: 1rem;
                    font-family: var(--font-alt);
                    color: var(--dark-text);
                    font-weight: 600;
                }
            }

            .body {
                p {
                    font-family: var(--font);
                    color: var(--light-text);
                }
            }
        }

        .bottom-section {
            display: flex;

            .foot-block {
                margin-right: 30px;

                .heading {
                    font-family: var(--font-alt);
                    font-size: 0.75rem;
                    color: var(--light-text-dark-22);
                }

                >p {
                    padding-top: 5px;
                }

                .developers {
                    display: flex;

                    .v-avatar {
                        margin-right: 6px;
                    }
                }
            }
        }
    }
}

.is-dark {
    .projects-card-grid {
        .grid-item {
            background: var(--dark-sidebar-light-6);
            border-color: var(--dark-sidebar-light-12);

            .top-section {
                .head {
                    h3 {
                        color: var(--dark-dark-text);
                    }
                }
            }

            .bottom-section {
                .foot-block {
                    .heading {
                        color: var(--light-text-dark-12);
                    }
                }
            }
        }
    }
}
</style>