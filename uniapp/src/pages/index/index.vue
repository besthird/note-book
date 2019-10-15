<template>
    <view class="content">
        <image class="logo" src="/static/logo.png"></image>
        <view>
            <text class="title">{{title}}</text>

            <button v-if="!isLogin" open-type="getUserInfo" lang="zh_CN" @getuserinfo="regist" withCredentials="true">登录
            </button>
            <button v-else lang="zh_CN" @click="popup">发布信息</button>
            <uni-popup ref="popup" type="bottom">
                <view class="uni-textarea">
                    <textarea @blur="bindTextAreaBlur" auto-height />
                </view>
                <view class="uni-title uni-common-pl">占位符字体是红色的textarea</view>
                <view class="uni-textarea">
                    <textarea placeholder-style="color:#F76260" placeholder="占位符字体是红色的"/>
                </view>
            </uni-popup>
        </view>
    </view>
</template>

<script>
    import request from "../../core/request";
    import { OK, CANCEL } from "../../config/constatns";
    import uniPopup from "@dcloudio/uni-ui/lib/uni-popup/uni-popup.vue"
    import core from "../../core/core";

    export default {
        components: { uniPopup },
        data() {
            return {
                title: 'Hello',
                isLogin: true,
            }
        },

        async onLoad() {
        },

        async onShow() {
            await request.login();
            this.isLogin = core.isLogin();
        },

        methods: {
            async popup() {
                this.$refs.popup.open()
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
