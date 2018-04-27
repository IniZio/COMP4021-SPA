import slim from 'observable-slim'

const SingleSlim = (() => {
  let instance

  function createInstance () {
      const object = slim.create({}, true)
      return object
  }

  return {
      getInstance () {
          if (!instance) {
              instance = createInstance()
          }
          return instance
      }
  }
})()

function path (paths, obj) {
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


function set (paths, value, obj) {
  var schema = obj
  var pList = Array.isArray(paths) ? paths : paths.split('.')
  var len = pList.length
  for(var i = 0; i < len-1; i++) {
      var elem = pList[i]
      if( !schema[elem] ) schema[elem] = {}
      schema = schema[elem]
  }

  schema[pList[len-1]] = value
}

class MyElement extends HTMLElement {
  constructor () {
    super(...arguments)

    this._listeners = []
    this._mapper = {}
    this.context = SingleSlim.getInstance()
    this._ctxMapper = {}

    if (!this.shadowRoot) {
      // Attach shadow DOM
      this.attachShadow({mode: 'open'})
      // Append template content to custom element
      this.shadowRoot.appendChild(this.template.content.cloneNode(true))
    }
  }

  connectedCallback () {
    this.applyListeners()
    this.activateState()
    this.activateContext()
  }

  activateState () {
    const instance = this

    const initial = (
      instance.data instanceof Function
      ? instance.data
      : () => (instance.data || {})
    )()

    this.shadowRoot.querySelectorAll('*')
      .map(node => {
        // Attribute sync
        Array.from(node.attributes)
          // e.g. :value
          .filter(attr => attr.name.startsWith(':'))
          .map(attr => {
            const name = attr.name.slice(1)
            node.removeAttribute(attr.name)
            if (name === 'children') {
              node.innerText = path(attr.value, initial)
            } else node.setAttribute(name, path(attr.value, initial))
            if (this._mapper.hasOwnProperty(attr.value)) {
              this._mapper[attr.value].push({name, node})
            } else this._mapper[attr.value] = [{name, node}]
          })
      })

    this.data = slim.create(
      initial,
      true,
      changes => {
        changes.map(change => {
          Object.keys(instance._mapper)
            .filter(paths => paths.startsWith(change.currentPath))
            .map(paths =>
              instance._mapper[paths].map(
                ({name, node}) => {
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

  diactivateState () {
    slim.remove(this.data)
  }

  activateContext () {
    const instance = this

    Array.from(this.attributes)
      // e.g. :value
      .filter(attr => attr.name.startsWith('~'))
      .map(attr => {
        const name = attr.name.slice(1)
        this.removeAttribute(attr.name)
        if (this._ctxMapper.hasOwnProperty(attr.value)) {
          this._ctxMapper[attr.value].push({name, node: this})
        } else this._ctxMapper[attr.value] = [{name, node: this}]
      })

    slim.observe(SingleSlim.getInstance(), changes => {
      changes.map(change => {
        // console.log(change.currentPath, instance._ctxMapper)
        Object.keys(instance._ctxMapper)
          .filter(paths => paths.startsWith(change.currentPath))
          .map(paths =>
            instance._ctxMapper[paths].map(
              ({name, node}) => {
                console.log(name, node)
                const newNode = path(paths, instance.context)
                set(name, newNode, instance.data)
              }
            )
          )
      })
    })
  }

  disconnectedCallback () {
    this.clearListeners()
    this.diactivateState()
    this.diactivateContext()
  }

  applyListeners () {
    const instance = this

    this.shadowRoot.querySelectorAll('*')
      .map(node =>
        Array.from(node.attributes)
          // e.g. @click
          .filter(attr => attr.name.startsWith('@'))
          .map(attr => {
            node.removeAttribute(attr.name)
            if (instance[attr.value] instanceof Function) {
              const handler = instance[attr.value].bind(instance)
              node.addEventListener(attr.name.slice(1), handler)
              instance._listeners.push({el: node, event: attr.name.slice(1), handler})
            }
          })
      )
  }

  clearListeners () {
    this._listeners.map(({el, event, handler}) => el.removeEventListener(event, handler))
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
