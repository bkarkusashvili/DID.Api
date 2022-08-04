const isNotFile = type => new Set(['file', 'image', 'video']).has(type);
const isGroup = type => type === 'group';

export const getError = (errors, relation, group, name) => errors[!relation ?
    name :
    group ?
        `${relation}.${group}.${name}` :
        `${relation}.${name}`
];

const getFieldList = (list, data = {}) => {
    const form = {};

    list.forEach(item => {
        let key = item.name;
        let value = isNotFile(item.type) ? data[key] : null;

        if (item.relation && !form[item.relation]) {
            form[item.relation] = {}
        }

        if (isGroup(item.type)) {
            value = [getFieldList(item.list, data[key])];
        }

        if (item.relation) {
            form[item.relation][key] = value;
        } else {
            form[key] = value;
        }
    });

    return form;
};

export const getInitForm = (fields, data = {}) => {
    let form = {};

    fields.map(field => Object.assign(form, getFieldList(field.list, data)));

    return form;
};
