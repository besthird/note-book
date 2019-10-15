import { OK, FAILED } from '../config/constatns'

export default {
    async request(method, url, data) {
        let that = this;
        let baseUri = 'http://127.0.0.1:9501';
        let app = getApp();
        let token = '';
        if (app.globalData.token) {
            token = app.globalData.token;
        }

        return uni.request({
            url: baseUri + url,
            method: method,
            data: data,
            header: {
                'X-Token': token
            }
        }).then(res => {
            var [err, res] = res;
            if (err !== null) {
                return that.attempts({ title: '网络连接失败' }, method, url, data);
            }

            if (res.statusCode !== 200) {
                return that.attempts({ title: '网络连接失败' }, method, url, data);
            }

            if (res.data.code !== OK) {
                uni.showModal({
                    title: '提示',
                    content: res.data.message
                });
                return [res.data.code, res.data.message];
            }

            return [OK, res.data.data];
        });
    },

    async attempts(model, method, url, data) {
        let that = this;
        return uni.showModal(model).then(res => {
            var [, res] = res;
            if (res.confirm) {
                return that.request(method, url, data);
            }
            return [CANCEL];
        });
    },

    async login() {
        var [, res] = await uni.login({
            provider: 'weixin'
        });

        var [err, data] = await this.request('POST', '/login', {
            code: res.code
        })

        if (err !== 0) {
        }

        return data;
    },
}