<template>
    <view class="content">
        <view v-for="item in items">
            <uni-card :title="item.user.nickname" :thumbnail="item.user.avatar"
                      :extra="item.user.gender==1?'男':'女'">
                {{item.text}}
            </uni-card>
            <view style="height:10px"></view>
        </view>

        <uni-fab ref="fab"
                 :pattern="pattern"
                 :content="content"
                 :horizontal="horizontal"
                 :vertical="vertical"
                 :direction="direction"
                 :show="false"
                 @trigger="trigger"
        ></uni-fab>

        <uni-popup ref="popup" type="bottom">
            <button v-if="!isLogin" open-type="getUserInfo" @getuserinfo="regist"
                    withCredentials="true">login
            </button>
            <form v-else @submit="submit" report-submit="true">
                <view class="uni-title uni-common-pl">发布信息</view>
                <view class="uni-textarea">
                    <textarea name="text" show-confirm-bar="true" @submit="submit"/>
                </view>
                <view class="uni-btn-v">
                    <button form-type="reset" size="mini">Reset</button>
                    <button form-type="submit" size="mini">Submit</button>
                </view>
            </form>
        </uni-popup>
    </view>


</template>

<script>
    import request from "../../core/request";
    import { OK, CANCEL } from "../../config/constatns";
    import uniPopup from "@dcloudio/uni-ui/lib/uni-popup/uni-popup.vue"
    import uniCard from "@dcloudio/uni-ui/lib/uni-card/uni-card"
    import uniFab from '@dcloudio/uni-ui/lib/uni-fab/uni-fab.vue'
    import core from "../../core/core";
    import note from '../../core/note';

    export default {
        components: { uniPopup, uniCard, uniFab },
        data() {
            return {
                isLogin: true,
                limit: 10,
                offset: 0,
                items: [],
                end: false,
                // 以下为 uniFab 配置
                horizontal: 'right',
                vertical: 'bottom',
                direction: 'horizontal',
                pattern: {
                    color: '#7A7E83',
                    backgroundColor: '#fff',
                    selectedColor: '#007AFF',
                    buttonColor: "#007AFF"
                },
                content: [
                    {
                        iconPath: '/static/edit_line.png',
                        selectedIconPath: '/static/edit_line.png',
                        text: '发布',
                        active: false
                    }
                ]
            }
        },

        async onLoad() {
            await request.login();
            this.isLogin = core.isLogin();
        },

        async onShow() {
            this.search()
        },

        async onReachBottom() {
            await this.search();
        },

        async onPullDownRefresh() {
            await this.refresh();
        },

        methods: {
            async trigger(e) {
                this.$refs.fab.close();

                switch (e.index) {
                    case 0:
                        this.$refs.popup.open();
                        break;
                }
            },
            async search() {
                if (this.end) {
                    return;
                }

                let [err, data] = await note.search(this.offset, this.limit);
                if (data.length == 0) {
                    this.end = true;
                } else {
                    this.offset += this.limit;
                    this.items = [...this.items, ...data];
                }
            },
            async refresh() {
                this.offset = 0;

                let [err, data] = await note.search(this.offset, this.limit);
                this.offset += this.limit;
                this.items = data;
                this.end = false;
            },
            async submit(e) {
                let formId = e.detail.formId;
                let value = e.detail.value;

                this.$refs.popup.close();

                await note.save(value.text, 0);
                await this.refresh();
            },
            async regist(res) {
                var encryptedData = res.detail.encryptedData
                var iv = res.detail.iv;

                var [, res] = await uni.login({
                    provider: 'weixin'
                });

                var code = res.code;

                var [err, data] = await request.request('POST', '/regist', {
                    code: code,
                    encrypted_data: encryptedData,
                    iv: iv
                })

                if (err == OK) {
                    var app = getApp();
                    app.globalData.token = data.token;
                    app.globalData.user = data.user;
                    this.isLogin = core.isLogin();
                }
            },
        }
    }
</script>

<style>
    .content {
        text-align: center;
        height: 400 upx;
    }

    .logo {
        height: 200 upx;
        width: 200 upx;
        margin-top: 200 upx;
    }

    .title {
        font-size: 36 upx;
        color: #8f8f94;
    }
</style>
