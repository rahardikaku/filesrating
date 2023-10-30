import Vue from 'vue'
import { translate, translatePlural } from '@nextcloud/l10n'

import FilesRatingTab from './FilesRatingTab.vue'

Vue.prototype.t = translate
Vue.prototype.n = translatePlural

const View = Vue.extend(FilesRatingTab)
let tabInstance = null

window.addEventListener('DOMContentLoaded', function() {
	if (OCA.Files && OCA.Files.Sidebar) {
		const filesratingTab = new OCA.Files.Sidebar.Tab({
			id: 'filesrating',
			name: t('filesrating', 'Rating File'),
			icon: 'icon-star',

			async mount(el, fileInfo, context) {
				if (tabInstance) {
					tabInstance.$destroy()
				}
				tabInstance = new View({
					// Better integration with vue parent component
					parent: context,
				})
				// Only mount after we have all the info we need
				await tabInstance.update(fileInfo)
				tabInstance.$mount(el)

			},
			update(fileInfo) {
				tabInstance.update(fileInfo)
			},
			destroy() {
				tabInstance.$destroy()
				tabInstance = null
			},
			enabled(fileInfo) {
				return !(fileInfo?.isDirectory() ?? true)
			},
		})
		OCA.Files.Sidebar.registerTab(filesratingTab)
	}
})
