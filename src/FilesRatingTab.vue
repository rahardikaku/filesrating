<template>
	<div>
		<NcAppContent>
			<div class="input-container">
				<div>
					<h2 class="m-2 rating-heading">
						Masukan Rating Anda:
					</h2>
				</div>
				<div class="mb-2">
					<StarRating :star-size="30"
						@rating-selected ="setRating"
						:glow="10"
						active-color='#f00000' />
				</div>
				<div>
					<NcButton @click="save(1)"
						:wide="true"
						type="primary"
						text="Simpan">
						Simpan
					</NcButton>

				</div>
			</div>
			<hr class="hr" />
			<div class="rating-container">
				<h2 class="m-2 rating-heading">Rating Overview</h2>
				<h2 class="mb-2">{{state.data.rate_avg}}/5</h2>
				<StarRating :star-size="20"
					:glow="10"
					:read-only=true
					:rating="parseInt(state.data?.rate_avg)" />
				<div class="bar-container mb-2">
					<div class="bar-item">
						<span class="label-star">5</span>
						<span class="icon-star">
							<StarOutlineIcon />
						</span>
						<NcProgressBar :value="0" size="medium" />
						<span class="count-people">0</span>
					</div>
					<div class="bar-item">
						<span class="label-star">4</span>
						<span class="icon-star">
							<StarOutlineIcon />
						</span>
						<NcProgressBar :value="100" size="medium" />
						<span class="count-people">2</span>
					</div>
					<div class="bar-item">
						<span class="label-star">3</span>
						<span class="icon-star">
							<StarOutlineIcon />
						</span>
						<NcProgressBar :value="0" size="medium" />
						<span class="count-people">0</span>
					</div>
					<div class="bar-item">
						<span class="label-star">2</span>
						<span class="icon-star">
							<StarOutlineIcon />
						</span>
						<NcProgressBar :value="0" size="medium" />
						<span class="count-people">0</span>
					</div>
					<div class="bar-item">
						<span class="label-star">1</span>
						<span class="icon-star">
							<StarOutlineIcon />
						</span>
						<NcProgressBar :value="0" size="medium" />
						<span class="count-people">0</span>
					</div>
				</div>
			</div>
		</NcAppContent>
		<NcEmptyContent></NcEmptyContent>
	</div>
</template>

<script>

// import AppNavigationItem from '@nextcloud/vue/dist/Components/AppNavigationItem'
import NcEmptyContent from '@nextcloud/vue/dist/Components/NcEmptyContent.js'
import NcAppContent from '@nextcloud/vue/dist/Components/NcAppContent.js'
import NcButton from '@nextcloud/vue/dist/Components/NcButton.js'
import NcProgressBar from '@nextcloud/vue/dist/Components/NcProgressBar.js'
import StarRating from 'vue-star-rating'
import StarOutlineIcon from 'vue-material-design-icons/StarOutline.vue'
import { generateOcsUrl } from '@nextcloud/router'
import axios from '@nextcloud/axios'
import { showError, showSuccess } from '@nextcloud/dialogs'

export default {
	name: 'FilesRatingTab',
	components: {
		// AppNavigationItem,
		NcEmptyContent,
		StarRating,
		NcAppContent,
		NcProgressBar,
		NcButton,
		StarOutlineIcon
	},
	data() {
		return {
			rating: 0,
			loading: false,
			token: null,
			fileInfo: {},
			shares: [],
			state: { data: { rate_avg: 0 } },
			tmp: 4
		}
	},
	computed: {
		/**
		 * Returns the current active tab
		 * needed because AppSidebarTab also uses $parent.activeTab
		 *
		 * @return {string}
		 */
		activeTab() {
			return this.$parent.activeTab
		},
	},
	beforeDestroy() {
		try {
			this.tab.$destroy()
		} catch (error) {
			console.error('Unable to unmount FilesRating tab', error)
		}
	},
	methods: {
		setInitialState(userId) {
			const fileId = '5'
			const url = generateOcsUrl('apps/filesrating/api/v1/rating/initialstate/{userId}/{fileId}', { userId, fileId })
			axios.get(url).then(response => {
				this.setState(response)
			}).catch((error) => {
				showError(t('filesrating', 'error get initial state'))
				console.error(error)
			})
		},
		setState(response) {
			this.state.data = response?.data?.ocs?.data
		},
		setRating(rating) {
			this.rating = rating
		},
		save(id) {
			const options = {
				rate: this.rating,
			}
			const url = generateOcsUrl('apps/filesrating/api/v1/rating/{id}', { id })
			axios.put(url, options).then(response => {
				this.setState(response)
				showSuccess('Simpan rating berhasil')
			}).catch((error) => {
				showError(t('filesrating', 'Error simpan rating'))
				console.error(error)
			})
		},
		update(fileInfo) {

			// console.log(fileInfo)
			this.fileInfo = fileInfo
			this.setInitialState(1)
		},
	},
}
</script>

<style scoped>
.rating-container {
	display: flex;
	justify-content: center;
	flex-direction: column;
	align-items: center;
}

.m-2{
	margin-top:10px !important;
	margin-bottom:10px !important;
}
.mb-2 {
	margin-bottom:10px !important;
}
#tab-filesrating {
	height: 100%;
	padding: 0;
}
.bar-container {
	width: 100%;
	display: flex;
	justify-content: center;
	flex-direction: column;
    align-items: center;
    row-gap: 10px;
    margin-top: 10px;
}

.bar-item {
	width: 80%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.label-star{
	font-size: large;
}

.icon-star {
	margin-right: 20px;
}

.rating-heading {
	color:gray;
}

.count-people{
	margin-left: 10px;
	font-size: large;
}
.input-container{
	display:flex;
	flex-direction: column;
	row-gap: 10;
	align-items: center;
	justify-content: center;
}
.hr {
	border: 1px solid lightgray;
    margin-top: 20px;
}
</style>
