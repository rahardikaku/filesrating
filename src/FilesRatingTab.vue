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
						:glow="2"
						:rating="parseInt(state.data?.rate_user)"
						active-color='#800000' />
				</div>
				<div>
					<NcButton @click="save()"
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
						<NcProgressBar :value="computePercent(file_count_5)" size="medium" />
						<span class="count-people">{{ file_count_5 }}</span>
					</div>
					<div class="bar-item">
						<span class="label-star">4</span>
						<span class="icon-star">
							<StarOutlineIcon />
						</span>
						<NcProgressBar :value="computePercent(file_count_4)" size="medium" />
						<span class="count-people">{{ file_count_4 }}</span>
					</div>
					<div class="bar-item">
						<span class="label-star">3</span>
						<span class="icon-star">
							<StarOutlineIcon />
						</span>
						<NcProgressBar :value="0" size="medium" />
						<span class="count-people">{{ file_count_3 }}</span>
					</div>
					<div class="bar-item">
						<span class="label-star">2</span>
						<span class="icon-star">
							<StarOutlineIcon />
						</span>
						<NcProgressBar :value="0" size="medium" />
						<span class="count-people">{{ file_count_2 }}</span>
					</div>
					<div class="bar-item">
						<span class="label-star">1</span>
						<span class="icon-star">
							<StarOutlineIcon />
						</span>
						<NcProgressBar :value="0" size="medium" />
						<span class="count-people">{{ file_count_1 }}</span>
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
			state: { data: { rate_avg: 0, rate_user: 0, rate_group: [], file_count: 0 } },
			tmp: 4,
			file_count_1: 0,
			file_count_2: 0,
			file_count_3: 0,
			file_count_4: 0,
			file_count_5: 0,
			file_cont_n: 0
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
		initState() {
			this.file_count_1 = 0
			this.file_count_2 = 0
			this.file_count_3 = 0
			this.file_count_4 = 0
			this.file_count_5 = 0
			this.file_cont_n = 0
		},
		setInitialState() {
			const fileId = this.fileInfo.id
			const url = generateOcsUrl('apps/filesrating/api/v1/rating/initialstate/{fileId}', { fileId })
			axios.get(url).then(response => {
				this.setState(response)
			}).catch((error) => {
				showError(t('filesrating', 'error get initial state'))
				console.error(error)
			})
		},
		computePercent(rate) {
			if (rate > 0 && this.file_cont_n > 0) {
				return (rate / this.file_cont_n) * 100
			} else {
				return 0
			}
		},
		setState(response) {
			this.state.data = response?.data?.ocs?.data
			if (this.state.data.rate_group) {
				for (const r of this.state.data.rate_group) {
					switch (r.rate) {
					case 1:
						this.file_count_1 = r.fileId
						break
					case 2:
						this.file_count_2 = r.fileId
						break
					case 3:
						this.file_count_3 = r.fileId
						break
					case 4:
						this.file_count_4 = r.fileId
						break
					case 5:
						this.file_count_5 = r.fileId
						break
					default:
						break
					}
					this.file_cont_n += r.fileId
				}
			}
		},
		setRating(rating) {
			this.rating = rating
		},
		save() {
			const options = {
				rate: this.rating,
			}
			const id = this.fileInfo.id
			const url = generateOcsUrl('apps/filesrating/api/v1/rating/{id}', { id })
			axios.put(url, options).then(response => {
				this.initState()
				this.setState(response)
				showSuccess('Simpan rating berhasil')
			}).catch((error) => {
				showError(t('filesrating', 'Error simpan rating'))
				console.error(error)
			})
		},
		update(fileInfo) {
			this.fileInfo = fileInfo
			this.initState()
			this.setInitialState()
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
