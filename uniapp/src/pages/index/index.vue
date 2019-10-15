<template>
    <view class="content">
        <view class="uni-padding-wrap uni-common-mt">
            <view>
                <scroll-view scroll-y="true" class="scroll-Y" @scrolltoupper="upper" @scrolltolower="lower"
                             @scroll="scroll">
                    <view id="demo1" class="scroll-view-item">A</view>
                </scroll-view>
            </view>
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

    export default {
        components: { uniPopup, uniCard, uniFab },
        data() {
            return {
                isLogin: true,
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
        },

        methods: {
            async trigger(e) {
                console.log(e);
                this.$refs.fab.close();

                switch (e.index) {
                    case 0:
                        await this.popup();
                        break;
                }
            },
            async popup() {
                this.$refs.popup.open();
            },
            async submit(e) {
                let formId = e.detail.formId;
                let value = e.detail.value;

                this.$refs.popup.close();
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
