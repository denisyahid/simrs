<script setup lang="ts">
import { onMounted, ref, computed } from 'vue'
const emit = defineEmits<{
    (e: 'accessMenu', value: any): void,
}>()
const props = withDefaults(
    defineProps<{
        menuEMR?: any,
        filterMenu?: any,
        totalMenu?: any
    }>(),
    {
        menuEMR: [],
        filterMenu: '',
        totalMenu: 0
    }
)
const recursiveFilterByName: any = (array: any, name: any) => {
    return array.reduce((acc: any, curr: any) => {
        if (curr.label.match(new RegExp(name, 'i'))) {
            acc.push(curr);
        }
        if (curr.child && curr.child.length > 0) {
            const filteredChildren = recursiveFilterByName(curr.child, name);
            if (filteredChildren.length > 0) {
                acc = acc.concat(filteredChildren);
            }
        }
        return acc;
    }, []);
}

const filterMenuEMR = computed(() => {
    if (!props.filterMenu && props.filterMenu == '') {
        return props.menuEMR
    }
    const filteredArray = recursiveFilterByName(props.menuEMR, props.filterMenu)

    return filteredArray;
})
// console.log(filterMenuEMR)

</script>
<template>
    <div class="css-1iefgdn" >
        <div class="css-113hzvq ">
            <div class="css-gvoll6"><img src="/images/simrs/medical-app.png" alt="" class="css-zk2hyh">
                <div class="css-31czp9">Electronic Medical Record</div>
                <VTag color="danger" :label="totalMenu" outlined class="ml-3 mt-3" />
                <VTag class="ml-3 mt-3" :color="'success'" :label="'Sudah Ada Form'" />
            </div>
            <div class="css-11p7ov6">
                <div class="css-s0g7na">
                    <div class="css-1owj1eu" data-testid="catNavigation#1" v-for="(items, key)  in filterMenuEMR"
                        :key="key">
                        <div @click="$emit('accessMenu', items)">
                            <a class="css-1okvkby" :class="items.url_form != null?'is-success':''">{{ items.label }}</a>
                        </div>
                        <div class="css-bfgk5q" v-for="(itemsChild, keyChild) in items.child" :key="keyChild">
                            <div style="flex-direction: row;display: flex;"
                                @click="itemsChild.child && itemsChild.child.length ? itemsChild.isshow = !itemsChild.isshow : $emit('accessMenu', itemsChild)">
                                <i class="fas fa-angle-double-right css-bfgk5qx" aria-hidden="true"
                                    v-if="itemsChild.child && itemsChild.child.length"></i>
                                <a data-testid="categoryNavigation#1" class="css-ges1q2" :class="itemsChild.url_form != null?'is-success':''">{{ itemsChild.label }}</a>
                            </div>
                            <div class="margin-heads" v-if="itemsChild.isshow">
                                <div class="css-bfgk5q-flex" v-for="(itemsChildChild, keyChildChild) in itemsChild.child"
                                    :key="keyChildChild">
                                    <div style="flex-direction: row;display: flex;"
                                        @click="itemsChildChild.child && itemsChildChild.child.length ? itemsChildChild.isshow = !itemsChildChild.isshow : $emit('accessMenu', itemsChildChild)">
                                        <i class="fas fa-angle-double-right css-bfgk5qx " style="margin-top:5px !important"
                                            aria-hidden="true"
                                            v-if="itemsChildChild.child && itemsChildChild.child.length"></i>
                                        <a data-testid="categoryNavigation#1"
                                            :class="[itemsChildChild.child && itemsChildChild.child.length ? '' : 'css-ges1q2-2',itemsChildChild.url_form != null?'is-success':'']">{{
                                                itemsChildChild.label }}</a>
                                    </div>
                                    <div class="margin-heads" v-if="itemsChildChild.isshow">
                                        <div class="css-bfgk5q-flex-2"
                                            v-for="(itemsChildChildChild, keyChildChildChild) in itemsChildChild.child"
                                            :key="keyChildChildChild"

                                            @click="$emit('accessMenu', itemsChildChild)">
                                            <i class="fas fa-arrow-right css-bfgk5qx" aria-hidden="true"
                                                v-if="itemsChildChildChild.child && itemsChildChildChild.child.length"></i>
                                            <a data-testid="categoryNavigation#1" class="css-ges1q2-2-2"
                                            :class="[itemsChildChildChild.url_form != null?'is-success':'']">{{
                                                itemsChildChildChild.label }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
a.is-success{
    color:var(--success)
}
</style>
