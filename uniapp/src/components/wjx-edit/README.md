# 富文本编辑器插件使用说明

------

 1.引入插件所在的目录

    import edit from '../../components/uni-edit/uni-edit.vue';
2.注册组件，可选择全局注册或者局部注册

    components:{
		edit
	},
3.可以使用了

    <edit paddingBottom="20" ref="edit" placeText="写点什么吧..."></edit>
    
> 该组件的一些参数
> * paddingBottom 当键盘弹起的时候，输入框距离工具栏的距离
> * placeText  文本框默认的文字

