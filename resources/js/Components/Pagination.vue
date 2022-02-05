<template>
	<div class="flex justify-between mt-1">
		<div class="flex justify-center">
			<div class="mb-3 w-28">
				<select v-model="perPage" @change="filterPerPage()" class="form-select appearance-none
					block w-full px-3 py-1.5 m-0
					text-base font-normal text-gray-700
					bg-white bg-clip-padding bg-no-repeat
					border border-solid border-gray-300
					rounded transition ease-in-out
					focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none">
						<option value="10">10</option>
						<option value="20">20</option>
						<option value="30">30</option>
						<option value="50">50</option>
						<option value="100">100</option>
				</select>
			</div>
		</div>

		<nav aria-label="Page navigation">
			<ul class="flex list-style-none">
				<li class="page-item" :class=" data.prev_page_url ?? 'hidden' "><a
						class="page-link relative block py-1.5 px-3 rounded border-0 bg-transparent outline-none transition-all duration-300 rounded text-gray-800 hover:text-gray-800 hover:bg-gray-200 focus:shadow-none"
						href="#" @click="loadPage(data.current_page - 1)" aria-label="Previous">
						<span aria-hidden="true">&laquo;</span>
					</a></li>

				<li class="page-item" v-for="link in clearLinks" :key="link.label">
					<a :class="{'bg-blue-600 text-white' : link.active}" class="page-link relative block py-1.5 px-3 rounded border-0 bg-transparent outline-none transition-all duration-300 rounded hover:text-white hover:bg-blue-600 focus:shadow-none"
						href="#" @click="loadPage(link.label)">
						{{ link.label }}
					</a>
				</li>


				<li class="page-item" :class=" data.next_page_url ?? 'hidden' "><a
						class="page-link relative block py-1.5 px-3 rounded border-0 bg-transparent outline-none transition-all duration-300 rounded text-gray-800 hover:text-gray-800 hover:bg-gray-200 focus:shadow-none"
						href="#" @click="loadPage(data.current_page + 1)" aria-label="Next">
						<span aria-hidden="true">&raquo;</span>
					</a></li>
			</ul>
		</nav>

	</div>
</template>

<script>
import { setUrlParamr } from '@/Helpers/helpers.js';
export default {
  name: 'Pagination',
	props: {
		data: {
			type: Object
		},
	},
	data() {
		return {
			perPage: this.data.per_page ?? 10,
		};
	},
	methods: {
		setUrlParamr,
		filterPerPage() {
			this.$inertia.get(this.data.path, {perPage: this.perPage}, { preserveState: true});
		},
		loadPage(page) {
			let params = this.setUrlParamr('page', page);
			this.$inertia.get(this.data.path, params, { preserveState: true});
		},
	},
	computed: {
		clearLinks() {
			const cleanLinks = [...this.data.links];
			cleanLinks.shift();
			cleanLinks.pop();
			return cleanLinks;
		},
	},
};
</script>

<style>

</style>