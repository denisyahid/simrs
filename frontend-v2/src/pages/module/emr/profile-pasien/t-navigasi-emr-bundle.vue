<script setup lang="ts">
import { onMounted, ref, computed } from 'vue'

const emit = defineEmits<{
    (e: 'accessMenu', value: any): void,
    (e: 'accessMenuEMR', value: any): void
}>()
const props = withDefaults(
    defineProps<{
        menuEMR?: any,
        filterMenu?: any,
        totalMenu?: any,
        detail?: any,
        isLoading?: boolean
    }>(),
    {
        menuEMR: [],
        filterMenu: '',
        totalMenu: 0,
        detail: [],
        isLoading: false,
    }
)
const recursiveFilterByName = (array: any[], name: string): any[] => {
    return array.map((item) => {
        const matches = item.caption?.match(new RegExp(name, 'i'));
        if (matches) {
            return { ...item };
        }
        return null;
    }).filter(Boolean);
};

const filterMenuEMRDetail = computed(() => {
    if (!props.filterMenu && props.filterMenu == '') {
        return props.detail.data
    }
    const filteredArray = recursiveFilterByName(props.detail.data, props.filterMenu)
    return filteredArray;
})

const recursiveFilterByName2 = (array: any[], name: string): any[] => {
    return array.map((item) => {
        const matches = item.name?.match(new RegExp(name, 'i'));
        if (matches) {
            return { ...item };
        }
        return null;
    }).filter(Boolean);
};

const filterMenuEMR = computed(() => {
    if (!props.filterMenu && props.filterMenu == '') {
        return props.menuEMR
    }
    const filteredArray = recursiveFilterByName2(props.menuEMR, props.filterMenu)
    return filteredArray;
})

const getColor = (index) => {
    const classes = ['orange', 'green'];
    return classes[index % classes.length];
};

</script>
<template>
    <div class="css-1iefgdn">
        <div class="css-113hzvq ">
            <div class="columns is-multiline m-0"
                v-if="menuEMR.length > 0 && detail != null && detail.data != null && detail.data.length">
                <div class="column is-4 pt-0 is-clickable" v-for="(data, index) in filterMenuEMRDetail" :key="index"
                    @click="$emit('accessMenuEMR', data)">
                    <a :style="{ color: getColor(index) }">{{ data.caption }}</a>
                </div>
            </div>
            <div class="css-11p7ov6" v-else>
                <div class="columns is-multiline" v-if="isLoading == true">
                    <div class="column is-4" v-for="index of 30">
                        <VPlaceloadText :lines="1" />
                    </div>
                </div>
                <div v-for="(items, index) in filterMenuEMR" :key="items.name"
                    class="inner-list-item media-flex-center is-clickable" @click="$emit('accessMenu', items)" v-else>
                    <VIconBox :color="items.color">
                        <i aria-hidden="true" :class="items.icon"></i>
                    </VIconBox>
                    <div class="flex-meta is-light">
                        <a>{{ items.name }}</a>
                    </div>
                    <div class="flex-end">
                        <a class="go-icon">
                            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss">
.css-1iefgdn {
    margin-top: -10px;
}

.margin-head {

    margin-left: -170px;
    margin-top: 10px;
}

.f-bold {
    font-weight: bold;
    width: 150px !important;
    white-space: nowrap;
    overflow: hidden !important;
    text-overflow: ellipsis;
}

.f-menu {
    font-family: var(--font);
    font-size: 0.9rem;
    color: var(--light-text);

}

.f-head-menu {
    line-height: 1.5;
}

.f-menu:hover {
    color: var(--success);
}

.f-ul-height {
    overflow-y: auto;
    overflow-x: none;
}

.css-bfgk5qx {
    color: var(--danger);
    margin-top: 0.6rem !important;
    margin-right: 0.3rem !important;
}

.css-113hzvq {
    display: flex;
    flex-direction: column;
    flex: 1 1 0%;
    padding: 0 32px 0 32px;
}

.css-s0g7na {
    column-count: 3;
    column-gap: 24px;
}

.css-1owj1eu {
    width: 100%;
    vertical-align: top;
    display: inline-block;
    margin-bottom: 24px;
}

.css-11p7ov6 {
    flex: 1 1 0%;
    overflow: auto;
}

.css-1okvkby {
    margin: 0px 0px 16px;
    font-family: inherit;
    font-weight: 700;
    line-height: 20px;
    color: var(--NN950, #212121);
    font-size: 12px;
}

.css-bfgk5q {
    flex-direction: column;
    color: var(--NN950, #212121);
    font-size: 12px;
    display: flex;
    text-decoration: none;
    transition: color 280ms ease 0s;
    -webkit-font-smoothing: antialiased;
    white-space: normal;
    flex-direction: column;
}

.css-bfgk5q-flex {
    margin-left: 15px;
    flex-direction: column;
    color: var(--NN950, #212121);
    font-size: 12px;
    display: flex;
    text-decoration: none;
    transition: color 280ms ease 0s;
    -webkit-font-smoothing: antialiased;
    white-space: normal;
    flex-direction: column;
}

.css-bfgk5q-flex-2 {
    margin-left: 15px;
    flex-direction: column;
    color: var(--NN950, #212121);
    font-size: 12px;
    display: flex;
    text-decoration: none;
    transition: color 280ms ease 0s;
    -webkit-font-smoothing: antialiased;
    white-space: normal;

}


.css-ges1q2 {
    line-height: 1.5;
    margin: 4px 0px;
    display: flex;
    color: var(--NN950, #212121);
}

.css-ges1q2-2 {
    line-height: 1.5;
    margin: 4px 0px;
    display: flex;
    color: var(--NN950, #212121);
}

.css-ges1q2-2:before {
    content: "• ";
    font-size: 15px;
    line-height: 1.3;
    margin-right: 3px;
}

.css-ges1q2-2-2:before {
    content: "•• ";
    font-size: 15px;
    line-height: 1.3;
    margin-right: 3px;
}


.css-ges1q2:hover {
    color: var(--info)
}

.css-gvoll6 {
    display: flex;
}

.css-zk2hyh {
    padding: 8px;
    width: 40px;
    border-radius: 10px;
    height: 40px;
    background: var(--android);
    margin-right: 5px;
}

.css-31czp9 {
    color: var(--NN950, #212121);
    font-size: 20px;
    font-weight: bold;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    margin: 5px 0px 10px;
    -webkit-font-smoothing: antialiased;

}

a.is-success {
    color: var(--success)
}
</style>
