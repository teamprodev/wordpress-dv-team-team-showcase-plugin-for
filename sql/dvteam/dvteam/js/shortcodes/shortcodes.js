(function () {
    tinymce.PluginManager.add('dvteam_mce_button', function (editor, url) {
        editor.addButton('dvteam_mce_button', {
            text: 'DV Team',
            icon: 'icon dashicons-groups',
            type: 'menubutton',
            menu: [
                {
                    text: 'DV Team Grid',
                    onclick: function () {
                        editor.windowManager.open({
                            title: 'Insert DV Team Grid',
                            body: [
                                {
                                    type: 'textbox',
                                    name: 'max',
                                    label: 'Max. number of members:',
                                    value: '99'
								},
                                {
                                    type: 'textbox',
                                    name: 'catid',
                                    label: 'Category ID:',
                                    value: ''
								},
                                {
                                    type: 'listbox',
                                    name: 'gridstyle',
                                    label: 'Grid Style:',
                                    'values': [
                                        {
                                            text: 'Full',
                                            value: 'full'
                                        },
                                        {
                                            text: 'Square',
                                            value: 'square'
                                        },
                                        {
                                            text: 'Rectangle',
                                            value: 'rectangle'
                                        }
                                    ]
								},
                                {
                                    type: 'textbox',
                                    name: 'offset',
                                    label: 'Offset:',
                                    value: '20'
								},
                                {
                                    type: 'textbox',
                                    name: 'itemwidth',
                                    label: 'Max. Item Width:',
                                    value: '250'
								},
                                {
                                    type: 'listbox',
                                    name: 'side',
                                    label: 'Panel side:',
                                    'values': [
                                        {
                                            text: 'Right',
                                            value: 'right'
                                        },
                                        {
                                            text: 'Center',
                                            value: 'center'
                                        },
                                        {
                                            text: 'Left',
                                            value: 'left'
                                        }
                                    ]
                                },
                                {
                                    type: 'listbox',
                                    name: 'rounded',
                                    label: 'Rounded:',
                                    'values': [
                                        {
                                            text: 'No',
                                            value: 'dvteamgrid-default'
                                        },
                                        {
                                            text: 'Yes',
                                            value: 'dvteamgrid-circle'
                                        }
                                    ]
								}
                            ],
                            onsubmit: function (e) {
                                if (isNaN(e.data.max)) {
                                    editor.windowManager.alert('Maximum number of members must be a number.');
                                    return false;
                                }
                                if (isNaN(e.data.offset)) {
                                    editor.windowManager.alert('Offset must be a number.');
                                    return false;
                                }
                                if (isNaN(e.data.itemwidth)) {
                                    editor.windowManager.alert('Max item width must be a number.');
                                    return false;
                                }
                                editor.insertContent('[dvteam max="' + e.data.max + '" categoryid="' + e.data.catid + '" gridstyle="' + e.data.gridstyle + '" offset="' + e.data.offset + '" itemwidth="' + e.data.itemwidth + '" side="' + e.data.side + '" rounded="' + e.data.rounded + '"]');
                            }
                        });
                    }
                },
                {
                    text: 'DV Team Filterable Grid',
                    onclick: function () {
                        editor.windowManager.open({
                            title: 'Insert DV Team Filterable Grid',
                            body: [
                                {
                                    type: 'textbox',
                                    name: 'max',
                                    label: 'Max. number of members:',
                                    value: '99'
								},
                                {
                                    type: 'listbox',
                                    name: 'gridstyle',
                                    label: 'Grid Style:',
                                    'values': [
                                        {
                                            text: 'Full',
                                            value: 'full'
                                        },
                                        {
                                            text: 'Square',
                                            value: 'square'
                                        },
                                        {
                                            text: 'Rectangle',
                                            value: 'rectangle'
                                        }
                                    ]
								},
                                {
                                    type: 'textbox',
                                    name: 'offset',
                                    label: 'Offset:',
                                    value: '20'
								},
                                {
                                    type: 'textbox',
                                    name: 'itemwidth',
                                    label: 'Max. Item Width:',
                                    value: '250'
								},
                                {
                                    type: 'listbox',
                                    name: 'side',
                                    label: 'Panel side:',
                                    'values': [
                                        {
                                            text: 'Right',
                                            value: 'right'
                                        },
                                        {
                                            text: 'Center',
                                            value: 'center'
                                        },
                                        {
                                            text: 'Left',
                                            value: 'left'
                                        }
                                    ]
                                },
                                {
                                    type: 'textbox',
                                    name: 'exclude',
                                    label: 'Exclude:',
                                    value: ''
								},
                                {
                                    type: 'listbox',
                                    name: 'rounded',
                                    label: 'Rounded:',
                                    'values': [
                                        {
                                            text: 'No',
                                            value: 'dvteamgrid-default'
                                        },
                                        {
                                            text: 'Yes',
                                            value: 'dvteamgrid-circle'
                                        }
                                    ]
								}
                            ],
                            onsubmit: function (e) {
                                if (isNaN(e.data.max)) {
                                    editor.windowManager.alert('Maximum number of members must be a number.');
                                    return false;
                                }
                                if (isNaN(e.data.offset)) {
                                    editor.windowManager.alert('Offset must be a number.');
                                    return false;
                                }
                                if (isNaN(e.data.itemwidth)) {
                                    editor.windowManager.alert('Max item width must be a number.');
                                    return false;
                                }
                                editor.insertContent('[dvteamfilter max="' + e.data.max + '" gridstyle="' + e.data.gridstyle + '" offset="' + e.data.offset + '" itemwidth="' + e.data.itemwidth + '" side="' + e.data.side + '" exclude="' + e.data.exclude + '" rounded="' + e.data.rounded + '"]');
                            }
                        });
                    }
                },
                {
                    text: 'DV Team Thumbnails',
                    onclick: function () {
                        editor.windowManager.open({
                            title: 'Insert DV Team Thumbnails',
                            body: [
                                {
                                    type: 'textbox',
                                    name: 'max',
                                    label: 'Max. number of members:',
                                    value: '99'
								},
                                {
                                    type: 'textbox',
                                    name: 'catid',
                                    label: 'Category ID:',
                                    value: ''
								},
                                {
                                    type: 'textbox',
                                    name: 'offset',
                                    label: 'Offset:',
                                    value: '0'
								},
                                {
                                    type: 'listbox',
                                    name: 'side',
                                    label: 'Panel side:',
                                    'values': [
                                        {
                                            text: 'Right',
                                            value: 'right'
                                        },
                                        {
                                            text: 'Center',
                                            value: 'center'
                                        },
                                        {
                                            text: 'Left',
                                            value: 'left'
                                        }
                                    ]
                                },
                                {
                                    type: 'listbox',
                                    name: 'rounded',
                                    label: 'Rounded:',
                                    'values': [
                                        {
                                            text: 'No',
                                            value: 'dvteamgrid-default'
                                        },
                                        {
                                            text: 'Yes',
                                            value: 'dvteamgrid-circle'
                                        }
                                    ]
								}
                            ],
                            onsubmit: function (e) {
                                if (isNaN(e.data.max)) {
                                    editor.windowManager.alert('Maximum number of members must be a number.');
                                    return false;
                                }
                                editor.insertContent('[dvthumbnails max="' + e.data.max + '" categoryid="' + e.data.catid + '" offset="' + e.data.offset + '" side="' + e.data.side + '" rounded="' + e.data.rounded + '"]');
                            }
                        });
                    }
                },
                {
                    text: 'DV Team Carousel',
                    onclick: function () {
                        editor.windowManager.open({
                            title: 'Insert DV Team Carousel',
                            body: [
                                {
                                    type: 'textbox',
                                    name: 'max',
                                    label: 'Max. number of members:',
                                    value: '99'
								},
                                {
                                    type: 'textbox',
                                    name: 'catid',
                                    label: 'Category ID:',
                                    value: ''
								},
                                {
                                    type: 'listbox',
                                    name: 'gridstyle',
                                    label: 'Grid Style:',
                                    'values': [
                                        {
                                            text: 'Full',
                                            value: 'full'
                                        },
                                        {
                                            text: 'Square',
                                            value: 'square'
                                        },
                                        {
                                            text: 'Rectangle',
                                            value: 'rectangle'
                                        }
                                    ]
								},
                                {
                                    type: 'listbox',
                                    name: 'columns',
                                    label: 'Columns:',
                                    'values': [
                                        {
                                            text: '3',
                                            value: '3'
                                        },
                                        {
                                            text: '2',
                                            value: '2'
                                        },
                                        {
                                            text: '1',
                                            value: '1'
                                        },
                                        {
                                            text: '4',
                                            value: '4'
                                        }
                                    ]
								},
                                {
                                    type: 'listbox',
                                    name: 'autoplay',
                                    label: 'Autoplay:',
                                    'values': [
                                        {
                                            text: 'On',
                                            value: 'true'
                                        },
                                        {
                                            text: 'Off',
                                            value: 'false'
                                        }
                                    ]
								},
                                {
                                    type: 'textbox',
                                    name: 'duration',
                                    label: 'Autoplay Duration:',
                                    value: '4'
								},
                                {
                                    type: 'textbox',
                                    name: 'spacing',
                                    label: 'Spacing:',
                                    value: '20'
								},
                                {
                                    type: 'listbox',
                                    name: 'side',
                                    label: 'Panel side:',
                                    'values': [
                                        {
                                            text: 'Right',
                                            value: 'right'
                                        },
                                        {
                                            text: 'Center',
                                            value: 'center'
                                        },
                                        {
                                            text: 'Left',
                                            value: 'left'
                                        }
                                    ]
                                },
                                {
                                    type: 'listbox',
                                    name: 'rounded',
                                    label: 'Rounded:',
                                    'values': [
                                        {
                                            text: 'No',
                                            value: 'dvteamgrid-default'
                                        },
                                        {
                                            text: 'Yes',
                                            value: 'dvteamgrid-circle'
                                        }
                                    ]
								}
                            ],
                            onsubmit: function (e) {
                                if (isNaN(e.data.max)) {
                                    editor.windowManager.alert('Maximum number of members must be a number.');
                                    return false;
                                }
                                if (isNaN(e.data.duration)) {
                                    editor.windowManager.alert('Autoplay duration must be a number.');
                                    return false;
                                }
                                if (isNaN(e.data.spacing)) {
                                    editor.windowManager.alert('Spacing must be a number.');
                                    return false;
                                }
                                editor.insertContent('[dvteamcarousel max="' + e.data.max + '" categoryid="' + e.data.catid + '" columns="' + e.data.columns + '" gridstyle="' + e.data.gridstyle + '" autoplay="' + e.data.autoplay + '" duration="' + e.data.duration + '" spacing="' + e.data.spacing + '" side="' + e.data.side + '" rounded="' + e.data.rounded + '"]');
                            }
                        });
                    }
                }
			]
        });
    });
})();