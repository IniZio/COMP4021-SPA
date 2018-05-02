import slim from 'observable-slim'

const SingleSlim = (() => {
  let instance

  function createInstance() {
    const object = slim.create({}, true)
    return object
  }

  return {
    getInstance() {
      if (!instance) {
        instance = createInstance()
      }
      return instance
    }
  }
})()

function path(paths, obj) {
  paths = Array.isArray(paths) ? paths : paths.split('.')
  let val = obj
  let idx = 0
  while (idx < paths.length) {
    if (val == null) {
      return
    }
    val = val[paths[idx]]
    idx += 1
  }
  return val
}


function set(paths, value, obj) {
  var schema = obj
  var pList = Array.isArray(paths) ? paths : paths.split('.')
  var len = pList.length
  for (var i = 0; i < len - 1; i++) {
    var elem = pList[i]
    if (!schema[elem]) schema[elem] = {}
    schema = schema[elem]
  }

  schema[pList[len - 1]] = value
}

class MyElement extends HTMLElement {
  observe = slim.observe

  constructor() {
    super(...arguments)

    this._listeners = []
    this._mapper = {}
    this.context = SingleSlim.getInstance()
    this._ctxMapper = {}
    this.initState()

    if (!this.shadowRoot) {
      // Attach shadow DOM
      this.attachShadow({
        mode: 'open'
      })
      // Append template content to custom element
      this.shadowRoot.appendChild(this.template.content.cloneNode(true))
    }
  }

  connectedCallback() {
    this.compile()
  }

  initState() {
    const instance = this

    const initial = (
      instance.data instanceof Function ?
      instance.data :
      () => (instance.data || {})
    )()

    this.data = slim.create(
      initial,
      true,
      changes => {
        changes.map(change => {
          Object.keys(instance._mapper)
            .filter(paths => paths.startsWith(change.currentPath))
            .map(paths =>
              instance._mapper[paths].map(
                ({
                  name,
                  node
                }) => {
                  const newNode = path(paths, instance.data)
                  if (name === 'children') {
                    node.innerText = newNode
                  } else {
                    node.setAttribute(name, newNode)
                    // HACK: not sure if value attribute is the only one that sets default rather than live value?
                    if (name === 'value') node.value = newNode
                  }
                }
              )
            )
        })
      }
    )
  }

  compile(root = this.shadowRoot) {
    const instance = this

    root.querySelectorAll('[x-for]')
      .map(node => {
        const [name, iterable] = node.attributes['x-for'].value.split(':')
        node.removeAttribute('x-for')
        const template = node.cloneNode(true)
        // HACK: uses node itself as anchor so not removing it, but definitely not a nice solution
        node.style.display = 'none'

        const fragment = document.createDocumentFragment()
        let elements = []

        const renderList = changes => {
          // Clear current children
          elements.map(element => element.parentNode.removeChild(element))
          // Compile elements
          elements = path(iterable, instance.data)
            .map((single, index) => {
              const element = template.cloneNode(true)
              element.setAttribute('key', index)
              this.applyListeners(element)
              this.activateBinds(element, {[name]: single})
              this.activateContext(element)
              return element
            })

          elements.map(fragment.appendChild.bind(fragment))
          node.parentNode.insertBefore(fragment, node.nextSibling)
        }

        instance.observe(instance.data, renderList.bind(instance))
        renderList()
      })

    // Apply listeners and data binding to nodes without directives
    root.querySelectorAll('*')
      .filter(node => !Object.keys(node.attributes).find(attr => attr.startsWith('x-')))
      .map(node => {
        this.applyListeners(node)
        this.activateBinds(node)
        this.activateContext(node)
      })
  }

  activateBinds(node, overrides = {}) {
    const instance = this

    Array.from(node.attributes)
      // e.g. :value
      .filter(attr => attr.name.startsWith(':'))
      .map(attr => {
        node.removeAttribute(attr.name)
        const name = attr.name.slice(1)
        const value = overrides[attr.value] || path(attr.value, this.data)
        if (name === 'children') {
          node.innerText = value
        } else node.setAttribute(name, value)
        if (this._mapper.hasOwnProperty(attr.value)) {
          this._mapper[attr.value].push({name, node})
        } else this._mapper[attr.value] = [{name, node}]
      })
  }

  diactivateState() {
    slim.remove(this.data)
  }

  activateContext(node) {
    const instance = this;

    Array.from(node.attributes)
      // e.g. :value
      .filter(attr => attr.name.startsWith('~'))
      .map(attr => {
        const name = attr.name.slice(1)
        node.removeAttribute(attr.name)
        if (instance._ctxMapper.hasOwnProperty(attr.value)) {
          instance._ctxMapper[attr.value].push({
            name,
            node
          })
        } else instance._ctxMapper[attr.value] = [{
          name,
          node
        }]
      })

    slim.observe(SingleSlim.getInstance(), changes => {
      changes.map(change => {
        // console.log(change.currentPath, instance._ctxMapper)
        Object.keys(instance._ctxMapper)
          .filter(paths => paths.startsWith(change.currentPath))
          .map(paths =>
            instance._ctxMapper[paths].map(
              ({
                name,
                node
              }) => {
                const newNode = path(paths, instance.context)
                if (name === 'children') {
                  node.innerText = newNode
                } else {
                  node.setAttribute(name, newNode)
                  // HACK: not sure if value attribute is the only one that sets default rather than live value?
                  if (name === 'value') node.value = newNode
                }
              }
            )
          )
      })
    })
  }

  disconnectedCallback() {
    this.clearListeners()
    this.diactivateState()
    this.diactivateContext()
  }

  applyListeners(node) {
    const instance = this

    Array.from(node.attributes)
      // e.g. @click
      .filter(attr => attr.name.startsWith('@'))
      .map(attr => {
        node.removeAttribute(attr.name)
        if (instance[attr.value] instanceof Function) {
          const handler = instance[attr.value].bind(instance)
          node.addEventListener(attr.name.slice(1), handler)
          instance._listeners.push({
            el: node,
            event: attr.name.slice(1),
            handler
          })
        }
      })
  }

  clearListeners() {
    this._listeners.map(({
      el,
      event,
      handler
    }) => el.removeEventListener(event, handler))
    this._listeners.length = 0
  }
}

window.MyElement = MyElement

// Define custom element
window.exportTag = (name = '', Class = class extends MyElement {}) => {
  Class.template = Class.prototype.template = document.currentScript.parentElement.querySelector('template')
  return window.customElements.define(name, Class)
}

export default MyElement
