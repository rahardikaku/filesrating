
OCA.Files.fileActions.registerAction({
	name: 'myFileAction',
	displayName: t('filesrating', 'My file action'),
	mime: 'file',
	permissions: OC.PERMISSION_READ,
	iconClass: 'icon-filetype-file',
	actionHandler: (name, context) => {
		console.debug('---------- file action triggered', name, context)
		OC.dialogs.info('The file "' + name + '" has a size of ' + context.fileInfoModel.attributes.size, 'My file action')
	},
})