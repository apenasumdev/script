import kebabCase from 'lodash/kebabCase'
import join from 'lodash/join'

export default {
    install: function (Vue) {
        //Require in a base component context
        const requireComponent = require.context('.', true, /[A-Za-z0-9-_,\s]+\.vue$/)
        requireComponent.keys().forEach(fileName => {
            //Get component config
            //const componentConfig = requireComponent(fileName);
            const name_explode = fileName.split('/')
            let joined_name = join(name_explode,'_')
            const resolvedFileName = fileName.replace('./','')
            joined_name = joined_name.replace('.vue','')
            //Get PascalCase name of component
            const componentName = 
                kebabCase(joined_name)
                    .replace(/^\.\//, '')
                    .replace(/|.\w+$/, '')
                    .replace('vue', '')
            
            Vue.component(`x-${componentName}`, () => import(/* webpackChunkName: "" */ `./${resolvedFileName}`)
                .then(c=>c.default))
        })
    }
}