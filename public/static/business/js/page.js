(function() {
	let tem = '<div class="am-fr" v-show="thisPage">' +
			'<ul class="am-pagination">'+
			'<li @click="controlPage(MycurPage-1)" :class="{\'am-disabled\': MycurPage === 1}"><a href="javascript:void(0)">上一页</a></li>'+
			'<li @click="controlPage(1)" :class="{\'am-disabled\': MycurPage === 1}"><a href="javascript:void(0)">首页</a></li>'+
			'<li v-for="page in pages" @click="controlPage(page)" :class="{\'am-active\': MycurPage === page}"><a href="javascript:void(0)">{{ page }}</a></li>'+
			'<li @click="controlPage(totalPages)" :class="{\'am-disabled\': MycurPage === totalPages}"><a href="javascript:void(0)">尾页</a></li>'+
			'<li @click="controlPage(MycurPage+1)" :class="{\'am-disabled\': MycurPage === totalPages}"><a href="javascript:void(0)">下一页</a></li>'+
			'</ul>'+
		'</div>';
	let pagination = Vue.extend({
		template: tem,
		props: {
			showPages: {
				type: Number,
				default: 5,
				required: true
			},
			totalPages: {
				type: Number,
				default: 20,
				required: true
			},
			curPage:{
				type: Number,
				default: 1,
				required: true
			},
			isPage:{
				type: Boolean,
				default:true,
				required: true
			}
		},
		data(){
			return {
				MycurPage:this.curPage,
				thisPage:this.isPage
			}
		},
		computed: {
			pages() {
				let left = 1,
					right = this.totalPages,
					movePoint = Math.ceil(this.showPages / 2),
					pages = [];
				if (this.MycurPage > movePoint && this.MycurPage < this.totalPages - movePoint + 1) {
					left = this.showPages % 2 === 0 ? this.MycurPage - movePoint : this.MycurPage - movePoint + 1;
					right = this.MycurPage + movePoint - 1;
				} else if (this.MycurPage <= movePoint) {
					left = 1;
					right = this.showPages;
				} else {
					left = this.totalPages - this.showPages + 1;
					right = this.totalPages;
				}
				while (left <= right) {
					pages.push(left);
					left++;
				}
				return pages;
			}
		},
		methods: {
			controlPage(page) {
				var _self = this;
				if (page > this.totalPages) {
					return false;
				} else if (page < 1) {
					return false;
				}
				if (this.MycurPage != page) {
					this.MycurPage = page;
					this.$emit('getlist', page);
				}
			}
		}
	})
	window.pagination = pagination;
})()