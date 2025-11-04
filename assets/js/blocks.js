(function(blocks, element, components, editor) {
    var el = element.createElement;
    var TextControl = components.TextControl;
    var TextareaControl = components.TextareaControl;
    var InspectorControls = editor.InspectorControls;
    var PanelBody = components.PanelBody;

    // 1. Methodology Card Block
    blocks.registerBlockType('renaissance/methodology-card', {
        title: 'Methodology Card',
        icon: 'lightbulb',
        category: 'renaissance',
        attributes: {
            title: {
                type: 'string',
                default: 'Methodology Title'
            },
            description: {
                type: 'string',
                default: 'Description of the methodology...'
            }
        },
        edit: function(props) {
            var title = props.attributes.title;
            var description = props.attributes.description;

            function onChangeTitle(newTitle) {
                props.setAttributes({ title: newTitle });
            }

            function onChangeDescription(newDescription) {
                props.setAttributes({ description: newDescription });
            }

            return [
                el(InspectorControls, {},
                    el(PanelBody, { title: 'Card Settings', initialOpen: true },
                        el(TextControl, {
                            label: 'Title',
                            value: title,
                            onChange: onChangeTitle
                        }),
                        el(TextareaControl, {
                            label: 'Description',
                            value: description,
                            onChange: onChangeDescription
                        })
                    )
                ),
                el('div', { className: 'methodology-item', style: { background: 'rgba(124, 58, 237, 0.1)', padding: '1.5rem', borderRadius: '12px', marginBottom: '1rem' } },
                    el('div', { style: { marginBottom: '1rem' } },
                        el('svg', { width: '40', height: '40', viewBox: '0 0 40 40', fill: 'none' },
                            el('rect', { width: '40', height: '40', rx: '8', fill: 'rgba(124, 58, 237, 0.2)' }),
                            el('path', { d: 'M12 20L18 26L28 14', stroke: '#a855f7', 'stroke-width': '2', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' })
                        )
                    ),
                    el('h4', { style: { color: '#fff', marginBottom: '0.5rem' } }, title),
                    el('p', { style: { color: 'rgba(255,255,255,0.7)', margin: 0 } }, description)
                )
            ];
        },
        save: function() {
            return null; // 使用 PHP 渲染
        }
    });

    // 2. Result Card Block
    blocks.registerBlockType('renaissance/result-card', {
        title: 'Result Card',
        icon: 'chart-bar',
        category: 'renaissance',
        attributes: {
            number: {
                type: 'string',
                default: '100%'
            },
            label: {
                type: 'string',
                default: 'Metric Label'
            },
            description: {
                type: 'string',
                default: 'Metric description'
            }
        },
        edit: function(props) {
            var number = props.attributes.number;
            var label = props.attributes.label;
            var description = props.attributes.description;

            return [
                el(InspectorControls, {},
                    el(PanelBody, { title: 'Card Settings', initialOpen: true },
                        el(TextControl, {
                            label: 'Number/Value',
                            value: number,
                            onChange: function(val) { props.setAttributes({ number: val }); }
                        }),
                        el(TextControl, {
                            label: 'Label',
                            value: label,
                            onChange: function(val) { props.setAttributes({ label: val }); }
                        }),
                        el(TextControl, {
                            label: 'Description',
                            value: description,
                            onChange: function(val) { props.setAttributes({ description: val }); }
                        })
                    )
                ),
                el('div', { className: 'result-card', style: { background: 'rgba(138, 43, 226, 0.1)', padding: '1.5rem', borderRadius: '12px', textAlign: 'center', marginBottom: '1rem' } },
                    el('div', { style: { fontSize: '2rem', fontWeight: '700', color: '#a855f7', marginBottom: '0.5rem' } }, number),
                    el('div', { style: { fontSize: '1rem', color: '#fff', marginBottom: '0.25rem' } }, label),
                    el('div', { style: { fontSize: '0.85rem', color: 'rgba(255,255,255,0.6)' } }, description)
                )
            ];
        },
        save: function() {
            return null;
        }
    });

    // 3. Results Grid Container
    blocks.registerBlockType('renaissance/results-grid', {
        title: 'Results Grid',
        icon: 'grid-view',
        category: 'renaissance',
        attributes: {
            title: {
                type: 'string',
                default: 'Results & Performance'
            }
        },
        edit: function(props) {
            var InnerBlocks = editor.InnerBlocks;
            var title = props.attributes.title;

            return [
                el(InspectorControls, {},
                    el(PanelBody, { title: 'Grid Settings', initialOpen: true },
                        el(TextControl, {
                            label: 'Section Title',
                            value: title,
                            onChange: function(val) { props.setAttributes({ title: val }); }
                        })
                    )
                ),
                el('div', { style: { padding: '1rem', background: 'rgba(255,255,255,0.05)', borderRadius: '8px' } },
                    el('h3', { style: { color: '#fff', marginBottom: '1rem' } }, title),
                    el(InnerBlocks, {
                        allowedBlocks: ['renaissance/result-card'],
                        template: [
                            ['renaissance/result-card'],
                            ['renaissance/result-card'],
                            ['renaissance/result-card'],
                            ['renaissance/result-card']
                        ]
                    })
                )
            ];
        },
        save: function(props) {
            var InnerBlocks = editor.InnerBlocks;
            return el(InnerBlocks.Content);
        }
    });

    // 4. Methodology Grid Container
    blocks.registerBlockType('renaissance/methodology-grid', {
        title: 'Methodology Grid',
        icon: 'grid-view',
        category: 'renaissance',
        attributes: {
            title: {
                type: 'string',
                default: 'Methodology & Approach'
            }
        },
        edit: function(props) {
            var InnerBlocks = editor.InnerBlocks;
            var title = props.attributes.title;

            return [
                el(InspectorControls, {},
                    el(PanelBody, { title: 'Grid Settings', initialOpen: true },
                        el(TextControl, {
                            label: 'Section Title',
                            value: title,
                            onChange: function(val) { props.setAttributes({ title: val }); }
                        })
                    )
                ),
                el('div', { style: { padding: '1rem', background: 'rgba(255,255,255,0.05)', borderRadius: '8px' } },
                    el('h3', { style: { color: '#fff', marginBottom: '1rem' } }, title),
                    el(InnerBlocks, {
                        allowedBlocks: ['renaissance/methodology-card'],
                        template: [
                            ['renaissance/methodology-card'],
                            ['renaissance/methodology-card'],
                            ['renaissance/methodology-card']
                        ]
                    })
                )
            ];
        },
        save: function(props) {
            var InnerBlocks = editor.InnerBlocks;
            return el(InnerBlocks.Content);
        }
    });

})(
    window.wp.blocks,
    window.wp.element,
    window.wp.components,
    window.wp.blockEditor || window.wp.editor
);

