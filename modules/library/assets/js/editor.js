(function(t) {
    'use strict';
    var i = window.ColorwayHFLibreryData || {},
        e, n, a, l;
    n = {
        LibraryLayoutView: null,
        LibraryHeaderView: null,
        LibraryLoadingView: null,
        LibraryErrorView: null,
        LibraryBodyView: null,
        LibraryCollectionView: null,
        FiltersCollectionView: null,
        LibraryTabsCollectionView: null,
        LibraryTabsItemView: null,
        FiltersItemView: null,
        LibraryTemplateItemView: null,
        LibraryInsertTemplateBehavior: null,
        LibraryTabsCollection: null,
        LibraryCollection: null,
        CategoriesCollection: null,
        LibraryTemplateModel: null,
        CategoryModel: null,
        TabModel: null,
        KeywordsModel: null,
        KeywordsView: null,
        LibraryPreviewView: null,
        LibraryHeaderBack: null,
        LibraryHeaderInsertButton: null,
        LibraryProButton: null,
        init: function() {
            var t = this;
            t.LibraryTemplateModel = Backbone.Model.extend({
                defaults: {
                    template_id: 0,
                    name: '',
                    title: '',
                    thumbnail: '',
                    preview: '',
                    source: '',
                    package: '',
                    livelink: '',
                    categories: [],
                    keywords: []
                }
            });
            t.CategoryModel = Backbone.Model.extend({
                defaults: {
                    slug: '',
                    title: ''
                }
            });
            t.CategoryModel = Backbone.Model.extend({
                defaults: {
                    slug: '',
                    title: ''
                }
            });
            t.TabModel = Backbone.Model.extend({
                defaults: {
                    slug: '',
                    title: ''
                }
            });
            t.KeywordsModel = Backbone.Model.extend({
                defaults: {
                    keywords: {}
                }
            });
            t.LibraryCollection = Backbone.Collection.extend({
                model: t.LibraryTemplateModel
            });
            t.CategoriesCollection = Backbone.Collection.extend({
                model: t.CategoryModel
            });
            t.LibraryTabsCollection = Backbone.Collection.extend({
                model: t.TabModel
            });
            t.LibraryLoadingView = Marionette.ItemView.extend({
                id: 'colorwayhf-template-library-loading',
                template: '#view-colorwayhf-template-library-loading'
            });
            t.LibraryErrorView = Marionette.ItemView.extend({
                id: 'colorwayhf-template-library-error',
                template: '#view-colorwayhf-template-library-error'
            });
            t.LibraryHeaderView = Marionette.LayoutView.extend({
                id: 'colorwayhf-template-library-header',
                template: '#view-colorwayhf-template-library-header',
                ui: {
                    closeModal: '#colorwayhf-template-library-header-close-modal'
                },
                events: {
                    'click @ui.closeModal': 'onCloseModalClick'
                },
                regions: {
                    headerTabs: '#colorwayhf-template-library-header-tabs',
                    headerActions: '#colorwayhf-template-library-header-actions'
                },
                onCloseModalClick: function() {
                    e.closeModal()
                }
            });
            t.LibraryPreviewView = Marionette.ItemView.extend({
                template: '#view-colorwayhf-template-library-preview',
                id: 'colorwayhf-template-library-preview',
                ui: {
                    img: 'img'
                },
                onRender: function() {
                    this.ui.img.attr('src', this.getOption('preview'))
                }
            });
            t.LibraryHeaderBack = Marionette.ItemView.extend({
                template: '#view-colorwayhf-template-library-header-back',
                id: 'colorwayhf-template-library-header-back',
                ui: {
                    button: 'button'
                },
                events: {
                    'click @ui.button': 'onBackClick',
                },
                onBackClick: function() {
                    e.setPreview('back')
                }
            });
            t.LibraryInsertTemplateBehavior = Marionette.Behavior.extend({
                ui: {
                    insertButton: '.colorwayhf-template-library-template-insert'
                },
                events: {
                    'click @ui.insertButton': 'onInsertButtonClick'
                },
                onInsertButtonClick: function() {
                    var t = this.view.model,
                        i = {};
                    e.layout.showLoadingView();
                    elementor.templates.requestTemplateContent(t.get('source'), t.get('template_id'), {
                        data: {
                            tab: e.getTab(),
                            page_settings: !0
                        },
                        success: function(n) {
                            if (n.licenseError) {
                                e.layout.showLicenseError();
                                return
                            };
                            e.closeModal();
                            elementor.channels.data.trigger('template:before:insert', t);
                            if (null !== e.atIndex) {
                                i.at = e.atIndex
                            };
                            elementor.sections.currentView.addChildModel(n.content, i);
                            if (n.page_settings) {
                                elementor.settings.page.model.set(n.page_settings)
                            };
                            elementor.channels.data.trigger('template:after:insert', t);
                            e.atIndex = null
                        }
                    })
                }
            });
            t.LibraryHeaderInsertButton = Marionette.ItemView.extend({
                template: '#view-colorwayhf-template-library-insert-button',
                id: 'colorwayhf-template-library-insert-button',
                behaviors: {
                    insertTemplate: {
                        behaviorClass: t.LibraryInsertTemplateBehavior
                    }
                }
            });
            t.LibraryProButton = Marionette.ItemView.extend({
                template: '#view-colorwayhf-template-library-pro-button',
                id: 'colorwayhf-template-library-pro-button',
            });
            t.LibraryTemplateItemView = Marionette.ItemView.extend({
                template: '#view-colorwayhf-template-library-item',
                className: function() {
                    var t = ' colorwayhf-template-has-url',
                        e = ' elementor-template-library-template-';
                    if ('' === this.model.get('preview')) {
                        t = ' colorwayhf-template-no-url'
                    };
                    if ('colorwayhf-local' === this.model.get('source')) {
                        e += 'local'
                    } else {
                        e += 'remote'
                    };
                    return 'elementor-template-library-template' + e + t
                },
                ui: function() {
                    return {
                        previewButton: '.elementor-template-library-template-preview',
                    }
                },
                events: function() {
                    return {
                        'click @ui.previewButton': 'onPreviewButtonClick',
                    }
                },
                onPreviewButtonClick: function() {
                    if ('' === this.model.get('preview')) {
                        return
                    };
                    e.setPreview(this.model)
                },
                behaviors: {
                    insertTemplate: {
                        behaviorClass: t.LibraryInsertTemplateBehavior
                    }
                }
            });
            t.FiltersItemView = Marionette.ItemView.extend({
                template: '#view-colorwayhf-template-library-filters-item',
                className: function() {
                    return 'colorwayhf-filter-item'
                },
                ui: function() {
                    return {
                        filterLabels: '.colorwayhf-template-library-filter-label'
                    }
                },
                events: function() {
                    return {
                        'click @ui.filterLabels': 'onFilterClick'
                    }
                },
                onFilterClick: function(t) {
                    var i = jQuery(t.target);
                    e.setFilter('category', i.val())
                }
            });
            t.LibraryTabsItemView = Marionette.ItemView.extend({
                template: '#view-colorwayhf-template-library-tabs-item',
                className: function() {
                    return 'elementor-template-library-menu-item'
                },
                ui: function() {
                    return {
                        tabsLabels: 'label',
                        tabsInput: 'input'
                    }
                },
                events: function() {
                    return {
                        'click @ui.tabsLabels': 'onTabClick'
                    }
                },
                onRender: function() {
                    if (this.model.get('slug') === e.getTab()) {
                        this.ui.tabsInput.attr('checked', 'checked')
                    }
                },
                onTabClick: function(t) {
                    var i = jQuery(t.target);
                    e.setTab(i.val());
                    e.setFilter('keyword', '')
                }
            });
            t.LibraryCollectionView = Marionette.CompositeView.extend({
                template: '#view-colorwayhf-template-library-templates',
                id: 'colorwayhf-template-library-templates',
                childViewContainer: '#colorwayhf-template-library-templates-container',
                initialize: function() {
                    this.listenTo(e.channels.templates, 'filter:change', this._renderChildren)
                },
                filter: function(t) {
                    var i = e.getFilter('category'),
                        n = e.getFilter('keyword');
                    if (!i && !n) {
                        return !0
                    };
                    if (n && !i) {
                        return _.contains(t.get('keywords'), n)
                    };
                    if (i && !n) {
                        return _.contains(t.get('categories'), i)
                    };
                    return _.contains(t.get('categories'), i) && _.contains(t.get('keywords'), n)
                },
                getChildView: function(e) {
                    return t.LibraryTemplateItemView
                },
                onRenderCollection: function() {
                    var n = this.$childViewContainer,
                        a = this.$childViewContainer.children(),
                        i = e.getTab();
                    if ('colorwayhf_page' === i || 'local' === i) {
                        return
                    };
                    setTimeout(function() {
                        t.masonry.init({
                            container: n,
                            items: a,
                        })
                    }, 200)
                }
            });
            t.LibraryTabsCollectionView = Marionette.CompositeView.extend({
                template: '#view-colorwayhf-template-library-tabs',
                childViewContainer: '#colorwayhf-template-library-tabs-items',
                initialize: function() {},
                getChildView: function(e) {
                    return t.LibraryTabsItemView
                }
            });
            t.FiltersCollectionView = Marionette.CompositeView.extend({
                id: 'colorwayhf-template-library-filters',
                template: '#view-colorwayhf-template-library-filters',
                childViewContainer: '#colorwayhf-template-library-filters-container',
                getChildView: function(e) {
                    return t.FiltersItemView
                }
            });
            t.LibraryBodyView = Marionette.LayoutView.extend({
                id: 'colorwayhf-template-library-content',
                className: function() {
                    return 'library-tab-' + e.getTab()
                },
                template: '#view-colorwayhf-template-library-content',
                regions: {
                    contentTemplates: '.colorwayhf-templates-list',
                    contentFilters: '.colorwayhf-filters-list',
                    contentKeywords: '.colorwayhf-keywords-list'
                }
            });
            t.LibraryLayoutView = Marionette.LayoutView.extend({
                el: '#colorwayhf-template-library-modal',
                regions: i.modalRegions,
                initialize: function() {
                    this.getRegion('modalHeader').show(new t.LibraryHeaderView());
                    this.listenTo(e.channels.tabs, 'filter:change', this.switchTabs);
                    this.listenTo(e.channels.layout, 'preview:change', this.switchPreview)
                },
                switchTabs: function() {
                    this.showLoadingView();
                    e.setFilter('keyword', '');
                    e.requestTemplates(e.getTab())
                },
                switchPreview: function() {
                    var i = this.getHeaderView(),
                        n = e.getPreview();
                    if ('back' === n) {
                        i.headerTabs.show(new t.LibraryTabsCollectionView({
                            collection: e.collections.tabs
                        }));
                        i.headerActions.empty();
                        e.setTab(e.getTab());
                        return
                    };
                    if ('initial' === n) {
                        i.headerActions.empty();
                        return
                    };
                    this.getRegion('modalContent').show(new t.LibraryPreviewView({
                        'preview': n.get('preview')
                    }));
                    i.headerTabs.show(new t.LibraryHeaderBack());
                    if (n.get('package') != 'pro') {
                        i.headerActions.show(new t.LibraryHeaderInsertButton({
                            model: n
                        }))
                    } else {
                        i.headerActions.show(new t.LibraryProButton({
                            model: n
                        }))
                    }
                },
                getHeaderView: function() {
                    return this.getRegion('modalHeader').currentView
                },
                getContentView: function() {
                    return this.getRegion('modalContent').currentView
                },
                showLoadingView: function() {
                    this.modalContent.show(new t.LibraryLoadingView())
                },
                showLicenseError: function() {
                    this.modalContent.show(new t.LibraryErrorView())
                },
                showTemplatesView: function(i, n, l) {
                    this.getRegion('modalContent').show(new t.LibraryBodyView());
                    var a = this.getContentView(),
                        r = this.getHeaderView(),
                        o = new t.KeywordsModel({
                            keywords: l
                        });
                    e.collections.tabs = new t.LibraryTabsCollection(e.getTabs());
                    r.headerTabs.show(new t.LibraryTabsCollectionView({
                        collection: e.collections.tabs
                    }));
                    a.contentTemplates.show(new t.LibraryCollectionView({
                        collection: i
                    }));
                    a.contentFilters.show(new t.FiltersCollectionView({
                        collection: n
                    }))
                }
            })
        },
        masonry: {
            self: {},
            elements: {},
            init: function(e) {
                var i = this;
                i.settings = t.extend(i.getDefaultSettings(), e);
                i.elements = i.getDefaultElements();
                i.run()
            },
            getSettings: function(e) {
                if (e) {
                    return this.settings[e]
                } else {
                    return this.settings
                }
            },
            getDefaultSettings: function() {
                return {
                    container: null,
                    items: null,
                    columnsCount: 3,
                    verticalSpaceBetween: 30
                }
            },
            getDefaultElements: function() {
                return {
                    $container: jQuery(this.getSettings('container')),
                    $items: jQuery(this.getSettings('items'))
                }
            },
            run: function() {
                var e = [],
                    t = this.elements.$container.position().top,
                    i = this.getSettings(),
                    n = i.columnsCount;
                t += parseInt(this.elements.$container.css('margin-top'), 10);
                this.elements.$container.height('');
                this.elements.$items.each(function(a) {
                    var c = Math.floor(a / n),
                        o = a % n,
                        l = jQuery(this),
                        m = l.position(),
                        s = l[0].getBoundingClientRect().height + i.verticalSpaceBetween;
                    if (c) {
                        var r = m.top - t - e[o];
                        r -= parseInt(l.css('margin-top'), 10);
                        r *= -1;
                        l.css('margin-top', r + 'px');
                        e[o] += s
                    } else {
                        e.push(s)
                    }
                });
                this.elements.$container.height(Math.max.apply(Math, e))
            }
        }
    };
    a = {
        ColorwayHFSearchView: null,
        init: function() {
            var e = this;
            e.ColorwayHFSearchView = window.elementor.modules.controls.BaseData.extend({
                onReady: function() {
                    var i = this.model.attributes.action,
                        e = this.model.attributes.query_params;
                    this.ui.select.find('option').each(function(e, i) {
                        t(this).attr('selected', !0)
                    });
                    this.ui.select.select2({
                        ajax: {
                            url: function() {
                                var n = '';
                                if (e.length > 0) {
                                    t.each(e, function(e, t) {
                                        if (window.elementor.settings.page.model.attributes[t]) {
                                            n += '&' + t + '=' + window.elementor.settings.page.model.attributes[t]
                                        }
                                    })
                                };
                                return ajaxurl + '?action=' + i + n
                            },
                            dataType: 'json'
                        },
                        placeholder: 'Please enter 3 or more characters',
                        minimumInputLength: 3
                    })
                },
                onBeforeDestroy: function() {
                    if (this.ui.select.data('select2')) {
                        this.ui.select.select2('destroy')
                    };
                    this.$el.remove()
                }
            });
            window.elementor.addControlView('colorwayhf_search', e.ColorwayHFSearchView)
        }
    };
    l = {
        getDataToSave: function(e) {
            e.id = window.elementor.config.post_id;
            return e
        },
        init: function() {
            if (window.elementor.settings.colorwayhf_template) {
                window.elementor.settings.colorwayhf_template.getDataToSave = this.getDataToSave
            };
            if (window.elementor.settings.colorwayhf_page) {
                window.elementor.settings.colorwayhf_page.getDataToSave = this.getDataToSave;
                window.elementor.settings.colorwayhf_page.changeCallbacks = {
                    custom_header: function() {
                        this.save(function() {
                            elementor.reloadPreview();
                            elementor.once('preview:loaded', function() {
                                elementor.getPanelView().setPage('colorwayhf_page_settings')
                            })
                        })
                    },
                    custom_footer: function() {
                        this.save(function() {
                            elementor.reloadPreview();
                            elementor.once('preview:loaded', function() {
                                elementor.getPanelView().setPage('colorwayhf_page_settings')
                            })
                        })
                    }
                }
            }
        }
    };
    e = {
        modal: !1,
        layout: !1,
        collections: {},
        tabs: {},
        defaultTab: '',
        channels: {},
        atIndex: null,
        init: function() {
            window.elementor.on('preview:loaded', window._.bind(e.onPreviewLoaded, e));
            n.init();
            a.init()
        },
        onPreviewLoaded: function() {
            this.initAizenButton();
            window.elementor.$previewContents.on('click.addColorwayHFTemplate', '.add-colorwayhf-template', _.bind(this.showTemplatesModal, this));
            this.channels = {
                templates: Backbone.Radio.channel('cw_THEME_EDITOR:templates'),
                tabs: Backbone.Radio.channel('cw_THEME_EDITOR:tabs'),
                layout: Backbone.Radio.channel('cw_THEME_EDITOR:layout'),
            };
            this.tabs = i.tabs;
            this.defaultTab = i.defaultTab
        },
        initAizenButton: function() {
            var n = window.elementor.$previewContents.find('.elementor-add-new-section'),
                a = '',
                l;
            if (n.length && i.libraryButton) {
                l = t(a).prependTo(n)
            };
            window.elementor.$previewContents.on('click.addColorwayHFTemplate', '.elementor-editor-section-settings .elementor-editor-element-add', function() {
                var l = t(this),
                    n = l.closest('.elementor-top-section'),
                    r = n.data('model-cid');
                if (window.elementor.sections.currentView.collection.length) {
                    t.each(window.elementor.sections.currentView.collection.models, function(t, i) {
                        if (r === i.cid) {
                            e.atIndex = t
                        }
                    })
                };
                if (i.libraryButton) {
                    setTimeout(function() {
                        var e = n.prev('.elementor-add-section').find('.elementor-add-new-section');
                        e.prepend(a)
                    }, 100)
                }
            })
        },
        getFilter: function(e) {
            return this.channels.templates.request('filter:' + e)
        },
        setFilter: function(e, t) {
            this.channels.templates.reply('filter:' + e, t);
            this.channels.templates.trigger('filter:change')
        },
        getTab: function() {
            return this.channels.tabs.request('filter:tabs')
        },
        setTab: function(e, t) {
            this.channels.tabs.reply('filter:tabs', e);
            if (!t) {
                this.channels.tabs.trigger('filter:change')
            }
        },
        getTabs: function() {
            var e = [];
            _.each(this.tabs, function(t, i) {
                e.push({
                    slug: i,
                    title: t.title
                })
            });
            return e
        },
        getPreview: function(e) {
            return this.channels.layout.request('preview')
        },
        setPreview: function(e, t) {
            this.channels.layout.reply('preview', e);
            if (!t) {
                this.channels.layout.trigger('preview:change')
            }
        },
        getKeywords: function() {
            var e = [];
            _.each(this.keywords, function(e, t) {
                tabs.push({
                    slug: t,
                    title: e
                })
            });
            return e
        },
        showTemplatesModal: function() {
            this.getModal().show();
            if (!this.layout) {
                this.layout = new n.LibraryLayoutView();
                this.layout.showLoadingView()
            };
            this.setTab(this.defaultTab, !0);
            this.requestTemplates(this.defaultTab);
            this.setPreview('initial')
        },
        requestTemplates: function(e) {
            var i = this,
                a = i.tabs[e];
            i.setFilter('category', !1);
            if (a.data.templates && a.data.categories) {
                i.layout.showTemplatesView(a.data.templates, a.data.categories, a.data.keywords)
            } else {
                t.ajax({
                    url: ajaxurl,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        action: 'colorwayhf_get_layouts',
                        tab: e,
                    },
                    success: function(t) {
                        var a = new n.LibraryCollection(t.data.templates),
                            l = new n.CategoriesCollection(t.data.categories);
                        i.tabs[e].data = {
                            templates: a,
                            categories: l,
                            keywords: t.data.keywords
                        };
                        i.layout.showTemplatesView(a, l, t.data.keywords)
                    }
                })
            }
        },
        closeModal: function() {
            this.getModal().hide()
        },
        getModal: function() {
            if (!this.modal) {
                this.modal = elementor.dialogsManager.createWidget('lightbox', {
                    id: 'colorwayhf-template-library-modal',
                    closeButton: !1
                })
            };
            return this.modal
        }
    };
    t(window).on('elementor:init', e.init)
})(jQuery);