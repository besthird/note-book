export default {
    isLogin() {
        let app = getApp();

        if (app.globalData.token) {
            return true;
        }

        return false;
    }
}